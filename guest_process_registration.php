<?php
session_start();
include 'db_connect.php'; // Your database connection file

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // --- Retrieve and Sanitize Form Data ---
    $title = isset($_POST['title']) ? sanitize_input($_POST['title']) : '';
    $first_name = isset($_POST['first_name']) ? sanitize_input($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_input($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    // Use 'phone' which comes from the hidden input 'phone_actual'
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Don't sanitize password before hashing, but validate length
    $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    $terms_checked = isset($_POST['terms_conditions_checkbox']);

    // --- Server-Side Validation ---
    if (empty($title)) {
        $errors[] = "กรุณาเลือกคำนำหน้า";
    }
    if (empty($first_name)) {
        $errors[] = "กรุณากรอกชื่อ";
    }
    if (empty($last_name)) {
        $errors[] = "กรุณากรอกนามสกุล";
    }
    if (empty($email)) {
        $errors[] = "กรุณากรอกอีเมล";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
    }
    if (empty($phone)) {
        $errors[] = "กรุณากรอกเบอร์โทรศัพท์มือถือ";
    } elseif (!preg_match("/^[0-9]{10}$/", $phone)) {
        $errors[] = "เบอร์โทรศัพท์มือถือต้องประกอบด้วยตัวเลข 10 หลัก";
    }
    if (empty($password)) {
        $errors[] = "กรุณากรอกรหัสผ่าน";
    } elseif (strlen($password) < 8) {
        $errors[] = "รหัสผ่านต้องมีความยาวอย่างน้อย 8 ตัวอักษร";
    }
    if (empty($confirm_password)) {
        $errors[] = "กรุณายืนยันรหัสผ่าน";
    } elseif ($password !== $confirm_password) {
        $errors[] = "รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน";
    }
    if (!$terms_checked) {
        $errors[] = "กรุณายอมรับข้อกำหนดและเงื่อนไข และนโยบายความเป็นส่วนตัว";
    }

    // --- Check if email already exists ---
    $stmt_check_email = null; // Initialize to null
    if (empty($errors) && !empty($email)) {
        $sql_check_email = "SELECT user_id FROM resume_users WHERE email = ?";
        $params_check_email = array($email);
        $stmt_check_email = sqlsrv_query($conn, $sql_check_email, $params_check_email);

        if ($stmt_check_email === false) {
            $errors[] = "เกิดข้อผิดพลาดในการตรวจสอบอีเมล: " . print_r(sqlsrv_errors(), true);
        } elseif (sqlsrv_has_rows($stmt_check_email)) {
            $errors[] = "อีเมลนี้มีผู้ใช้งานในระบบแล้ว กรุณาใช้อีเมลอื่น";
        }
        // Only free if it's a valid resource
        if ($stmt_check_email !== false && $stmt_check_email !== null) {
            sqlsrv_free_stmt($stmt_check_email);
        }
    }

    // --- If No Errors, Proceed to Insert Data ---
    $stmt_insert_user = null; // Initialize to null
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $registration_timestamp = date("Y-m-d H:i:s");

        $sql_insert_user = "INSERT INTO resume_users (title, first_name, last_name, email, password, phone, registration_timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $params_insert_user = array(
            $title,
            $first_name,
            $last_name,
            $email,
            $hashed_password,
            $phone,
            $registration_timestamp
        );

        $stmt_insert_user = sqlsrv_query($conn, $sql_insert_user, $params_insert_user);

        if ($stmt_insert_user) { // sqlsrv_query returns true on successful INSERT/UPDATE/DELETE
            $success_message = "การสมัครสมาชิกสำเร็จแล้ว! คุณสามารถเข้าสู่ระบบได้เลย";
            $_POST = array();
        } else {
            $errors[] = "เกิดข้อผิดพลาดในการบันทึกข้อมูล: " . print_r(sqlsrv_errors(), true);
        }
        // Only free if it's a valid resource (though for successful INSERT, $stmt_insert_user is true, not a resource)
        // For INSERT, UPDATE, DELETE, sqlsrv_query returns true on success, false on failure.
        // sqlsrv_free_stmt is typically used for statements that return result sets (SELECT).
        // However, it's good practice to check. If $stmt_insert_user was from a SELECT, this would be crucial.
        // Since it's an INSERT, if it's not false, it's true, and sqlsrv_free_stmt might not be strictly necessary
        // but checking doesn't hurt.
        if ($stmt_insert_user !== false && $stmt_insert_user !== null && is_resource($stmt_insert_user)) {
             sqlsrv_free_stmt($stmt_insert_user);
        }
    }
    if ($conn) { // Check if connection is still valid before closing
        sqlsrv_close($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานะการสมัครสมาชิก</title>
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
                    window.location.href = 'guest_register.php'; 
                }
            });
        <?php else: ?>
            // This case should ideally not be reached if the form is submitted.
            // It's a fallback if the page is accessed directly without POST.
            if (document.referrer.includes('guest_register.php') || !document.referrer) {
                 // Only show this if coming from register page or no referrer (direct access)
                 // to avoid showing it after a successful redirect from this page itself.
                Swal.fire({
                    title: 'ไม่พบข้อมูล',
                    text: 'กรุณาสมัครสมาชิกผ่านทางแบบฟอร์ม',
                    icon: 'warning',
                    confirmButtonText: 'กลับไปหน้าสมัครสมาชิก',
                    confirmButtonColor: '#3085d6'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'guest_register.php'; 
                    }
                });
            } else {
                // If navigated to this page by other means (e.g. refresh after success/error)
                // and messages are empty, redirect to avoid blank page with just script.
                window.location.href = 'guest_register.php';
            }
        <?php endif; ?>
    </script>
</body>
</html>
