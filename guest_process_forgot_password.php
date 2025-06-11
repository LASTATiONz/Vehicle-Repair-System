<?php
session_start();
include 'db_connect.php'; // Your database connection file

// PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP; // Required for ENCRYPTION_STARTTLS

// Adjust the path to PHPMailer's autoload or individual files as per your project structure
// TRYING THIS PATH: Assuming PHPMailer folder is INSIDE the current directory (e.g., ORNJOBS/PHPMailer/src/)
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';


// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$success_message = "";
// $info_message = ""; // No longer needed as we will attempt to send a real email

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';

    // Validate email
    if (empty($email)) {
        $errors[] = "กรุณากรอกอีเมล";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
    }

    if (empty($errors)) {
        // Check if email exists in the database
        $sql_check_email = "SELECT user_id, first_name FROM resume_users WHERE email = ?";
        $params_check_email = array($email);
        $stmt_check_email = sqlsrv_query($conn, $sql_check_email, $params_check_email);

        if ($stmt_check_email === false) {
            $errors[] = "เกิดข้อผิดพลาดในการตรวจสอบอีเมล: " . print_r(sqlsrv_errors(), true);
        } else {
            if (sqlsrv_has_rows($stmt_check_email)) {
                // Email exists, proceed to generate token
                $user_data = sqlsrv_fetch_array($stmt_check_email, SQLSRV_FETCH_ASSOC);
                $user_id = $user_data['user_id'];
                $user_first_name = $user_data['first_name']; // Get user's first name for personalization

                $token = bin2hex(random_bytes(32));
                $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $sql_update_token = "UPDATE resume_users SET reset_token = ?, reset_token_expiry = ? WHERE user_id = ?";
                $params_update_token = array($token, $expiry_time, $user_id);
                $stmt_update_token = sqlsrv_query($conn, $sql_update_token, $params_update_token);

                if ($stmt_update_token) {
                    // Construct the reset link
                    $reset_link_path = "guest_reset_password.php?token=" . urlencode($token);
                    
                    // Determine if it's a local or live environment for the link
                    if ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '192.168.') === 0 || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') === 0) {
                        $reset_link = "http://localhost/ORNJOBS/" . $reset_link_path; // Adjust if your local project path is different
                    } else {
                        $reset_link = "https://www.ornwebapp.com/ORNJOBS/" . $reset_link_path; // Your live URL
                    }


                    // --- PHPMailer Email Sending Logic ---
                    $mail = new PHPMailer(true);
                    try {
                        //Server settings from your email_send.php
                        $mail->isSMTP();
                        $mail->Host       = 'mail.ornategroup.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = 'ornjob@ornategroup.com'; // Your SMTP username
                        $mail->Password   = 'ornjob1234';        // Your SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port       = 587;

                        //Recipients
                        $mail->setFrom('ornjob@ornategroup.com', 'ฝ่ายสนับสนุน Ornate Group'); // Sender
                        $mail->addAddress($email, $user_first_name);     // Add a recipient (the user)

                        // Content
                        $mail->isHTML(true);
                        $mail->CharSet = 'UTF-8';
                        $mail->Subject = 'คำขอรีเซ็ตรหัสผ่านสำหรับบัญชี Ornate Group';
                        $mail->Body    = "
                        <html>
                        <head>
                            <style>
                                body { font-family: 'Sarabun', Arial, sans-serif; line-height: 1.6; color: #333; }
                                .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; max-width: 600px; margin: 20px auto; background-color: #f9f9f9; }
                                .button { background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; }
                                p { margin-bottom: 15px; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <h2 style='color: #0056b3;'>คำขอรีเซ็ตรหัสผ่าน</h2>
                                <p>สวัสดีคุณ " . htmlspecialchars($user_first_name) . ",</p>
                                <p>เราได้รับคำขอให้รีเซ็ตรหัสผ่านสำหรับบัญชีของคุณที่ใช้อีเมลนี้ หากคุณไม่ได้ทำการร้องขอนี้ กรุณาไม่ต้องดำเนินการใดๆ</p>
                                <p>หากต้องการตั้งรหัสผ่านใหม่ กรุณาคลิกที่ลิงก์ด้านล่าง:</p>
                                <p><a href='" . htmlspecialchars($reset_link) . "' class='button'>ตั้งรหัสผ่านใหม่</a></p>
                                <p>ลิงก์นี้จะสามารถใช้งานได้เป็นเวลา 1 ชั่วโมง หากลิงก์หมดอายุ คุณจะต้องส่งคำขอใหม่อีกครั้ง</p>
                                <p>หากคุณมีปัญหาในการคลิกปุ่ม คุณสามารถคัดลอกและวาง URL ต่อไปนี้ลงในเว็บเบราว์เซอร์ของคุณได้โดยตรง:</p>
                                <p><a href='" . htmlspecialchars($reset_link) . "'>" . htmlspecialchars($reset_link) . "</a></p>
                                <br>
                                <p>ขอแสดงความนับถือ,<br>ทีมงาน Ornate Group</p>
                            </div>
                        </body>
                        </html>";
                        $mail->AltBody = "สวัสดีคุณ " . htmlspecialchars($user_first_name) . ",\n\nเราได้รับคำขอให้รีเซ็ตรหัสผ่านสำหรับบัญชีของคุณ กรุณาคัดลอกและวางลิงก์นี้ในเบราว์เซอร์ของคุณเพื่อตั้งรหัสผ่านใหม่: " . $reset_link . "\n\nลิงก์นี้จะหมดอายุใน 1 ชั่วโมง\n\nขอแสดงความนับถือ,\nทีมงาน Ornate Group";

                        $mail->send();
                        $success_message = "หากอีเมล " . htmlspecialchars($email) . " มีอยู่ในระบบของเรา คุณจะได้รับอีเมลพร้อมคำแนะนำในการรีเซ็ตรหัสผ่านในไม่ช้า";

                    } catch (Exception $e) {
                        $errors[] = "ไม่สามารถส่งอีเมลรีเซ็ตรหัสผ่านได้ กรุณาลองใหม่อีกครั้งในภายหลัง";
                        // For debugging: $errors[] = "Mailer Error: " . $mail->ErrorInfo;
                    }
                    // --- End of PHPMailer Email Sending Logic ---

                } else {
                    $errors[] = "เกิดข้อผิดพลาดในการอัปเดตข้อมูลโทเค็น: " . print_r(sqlsrv_errors(), true);
                }
                // No need to free $stmt_update_token as it's an UPDATE query
            } else {
                // Email does not exist - show a generic message for security
                $success_message = "หากอีเมล " . htmlspecialchars($email) . " มีอยู่ในระบบของเรา คุณจะได้รับอีเมลพร้อมคำแนะนำในการรีเซ็ตรหัสผ่านในไม่ช้า";
            }
            if ($stmt_check_email !== false && $stmt_check_email !== null) {
                sqlsrv_free_stmt($stmt_check_email);
            }
        }
    }
    if ($conn) {
        sqlsrv_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะการขอลืมรหัสผ่าน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <script>
        <?php if (!empty($success_message) && empty($errors)): // Show success only if no errors occurred during the process ?>
            Swal.fire({
                title: 'ส่งคำขอสำเร็จ',
                html: '<?php echo $success_message; ?>', // This message is generic and safe
                icon: 'success',
                confirmButtonText: 'ตกลง',
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'guest_login.php'; 
                }
            });
        <?php elseif (!empty($errors)): ?>
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                html: '<?php echo implode("<br>", array_map('htmlspecialchars', $errors)); ?>',
                icon: 'error',
                confirmButtonText: 'ลองอีกครั้ง',
                confirmButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'guest_forgot_password.php'; 
                }
            });
        <?php else: ?>
             // If accessed directly without POST
            Swal.fire({
                title: 'ไม่พบข้อมูล',
                text: 'กรุณาส่งคำขอผ่านทางแบบฟอร์มลืมรหัสผ่าน',
                icon: 'warning',
                confirmButtonText: 'กลับไปหน้าลืมรหัสผ่าน',
                confirmButtonColor: '#3085d6'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'guest_forgot_password.php';
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
