<?php
session_start(); // Start session, might be useful for flash messages if not using JS alerts
include 'db_connect.php'; // Your database connection file

// Function to sanitize input (though less critical here as we're not displaying it back directly)
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = isset($_POST['token']) ? sanitize_input($_POST['token']) : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : ''; // Don't sanitize password before hashing
    $confirm_new_password = isset($_POST['confirm_new_password']) ? $_POST['confirm_new_password'] : '';

    // --- Server-Side Validation ---
    if (empty($token)) {
        $errors[] = "ไม่พบโทเค็นสำหรับรีเซ็ตรหัสผ่าน โปรดลองอีกครั้งจากอีเมลของคุณ";
    }
    if (empty($new_password)) {
        $errors[] = "กรุณากรอกรหัสผ่านใหม่";
    } elseif (strlen($new_password) < 8) {
        $errors[] = "รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
    }
    if (empty($confirm_new_password)) {
        $errors[] = "กรุณายืนยันรหัสผ่านใหม่";
    } elseif ($new_password !== $confirm_new_password) {
        $errors[] = "รหัสผ่านใหม่และการยืนยันรหัสผ่านไม่ตรงกัน";
    }

    if (empty($errors)) {
        // Validate token against the database
        $current_time = date('Y-m-d H:i:s');
        $sql_check_token = "SELECT user_id, reset_token_expiry FROM resume_users WHERE reset_token = ?";
        $params_check_token = array($token);
        $stmt_check_token = sqlsrv_query($conn, $sql_check_token, $params_check_token);

        if ($stmt_check_token === false) {
            $errors[] = "เกิดข้อผิดพลาดในการตรวจสอบโทเค็น: " . print_r(sqlsrv_errors(), true);
        } else {
            if (sqlsrv_has_rows($stmt_check_token)) {
                $user_data = sqlsrv_fetch_array($stmt_check_token, SQLSRV_FETCH_ASSOC);
                $user_id = $user_data['user_id'];
                $token_expiry_db = $user_data['reset_token_expiry']; // This will be a DateTime object

                // Convert SQL Server DateTime object to a string for comparison if necessary, or compare directly
                $token_expiry_timestamp = $token_expiry_db ? $token_expiry_db->getTimestamp() : 0;
                $current_timestamp = strtotime($current_time);

                if ($token_expiry_timestamp < $current_timestamp) {
                    $errors[] = "โทเค็นสำหรับรีเซ็ตรหัสผ่านหมดอายุแล้ว กรุณาขอลิงก์ใหม่";
                } else {
                    // Token is valid and not expired, proceed to update password
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                    // Update password and clear the reset token fields
                    $sql_update_password = "UPDATE resume_users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE user_id = ?";
                    $params_update_password = array($hashed_password, $user_id);
                    $stmt_update_password = sqlsrv_query($conn, $sql_update_password, $params_update_password);

                    if ($stmt_update_password) {
                        $success_message = "รหัสผ่านของคุณถูกเปลี่ยนเรียบร้อยแล้ว! คุณสามารถเข้าสู่ระบบด้วยรหัสผ่านใหม่ได้เลย";
                    } else {
                        $errors[] = "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน: " . print_r(sqlsrv_errors(), true);
                    }
                    // No need to free $stmt_update_password as it's an UPDATE query
                }
            } else {
                $errors[] = "โทเค็นสำหรับรีเซ็ตรหัสผ่านไม่ถูกต้องหรือไม่พบในระบบ";
            }
            if ($stmt_check_token !== false && $stmt_check_token !== null) {
                sqlsrv_free_stmt($stmt_check_token);
            }
        }
    }
    if ($conn) {
        sqlsrv_close($conn);
    }
} else {
    // If accessed directly without POST
    $errors[] = "การเข้าถึงไม่ถูกต้อง";
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะการตั้งรหัสผ่านใหม่</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { font-family: 'Sarabun', sans-serif; }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <script>
        <?php if (!empty($success_message)): ?>
            Swal.fire({
                title: 'สำเร็จ!',
                text: '<?php echo $success_message; ?>',
                icon: 'success',
                confirmButtonText: 'ไปหน้าเข้าสู่ระบบ',
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
                    // Redirect back to the reset password form, ideally with the token if it was valid but passwords didn't match
                    // For simplicity, or if token was invalid, redirect to forgot password.
                    // If token was valid but other error, could redirect to guest_reset_password.php?token=<?php echo urlencode($token); ?>
                    window.location.href = 'guest_forgot_password.php'; // Or guest_reset_password.php if token was initially valid
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>
