<?php
session_start(); // Essential for managing user login state
include 'db_connect.php'; // Your database connection file

// Function to sanitize input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$errors = [];
$success_message = ""; // Not typically used for direct display on this page, but for session/redirect

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // Password itself

    // --- Server-Side Validation ---
    if (empty($email)) {
        $errors[] = "กรุณากรอกอีเมล";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "รูปแบบอีเมลไม่ถูกต้อง";
    }
    if (empty($password)) {
        $errors[] = "กรุณากรอกรหัสผ่าน";
    }

    if (empty($errors)) {
        // Check if email exists
        $sql_check_user = "SELECT user_id, first_name, last_name, email, password,title,phone FROM resume_users WHERE email = ?";
        $params_check_user = array($email);
        $stmt_check_user = sqlsrv_query($conn, $sql_check_user, $params_check_user);

        if ($stmt_check_user === false) {
            $errors[] = "เกิดข้อผิดพลาดในการเข้าสู่ระบบ โปรดลองอีกครั้งในภายหลัง"; // Generic error
            // For debugging: $errors[] = "เกิดข้อผิดพลาดในการตรวจสอบผู้ใช้: " . print_r(sqlsrv_errors(), true);
        } else {
            if (sqlsrv_has_rows($stmt_check_user)) {
                $user_data = sqlsrv_fetch_array($stmt_check_user, SQLSRV_FETCH_ASSOC);
                $stored_hashed_password = $user_data['password'];

                // Verify the password
                if (password_verify($password, $stored_hashed_password)) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user_data['user_id'];
                    $_SESSION['user_email'] = $user_data['email'];
                    $_SESSION['user_first_name'] = $user_data['first_name'];
                    $_SESSION['user_last_name'] = $user_data['last_name'];
                    // You can add more user details to the session if needed
                    $_SESSION['user_title'] = isset($user_data['title']) ? $user_data['title'] : '';
                    $_SESSION['user_phone'] = isset($user_data['phone']) ? $user_data['phone'] : '';


                    // Redirect to the main application page (e.g., where they fill the resume)
                    // IMPORTANT: Change 'resume_ornategroup.php' to your actual target page after login
                    header("Location: resume_ornategroup.php");
                    exit(); // Important to prevent further script execution

                } else {
                    // Invalid password
                    $errors[] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
                }
            } else {
                // Email not found
                $errors[] = "อีเมลหรือรหัสผ่านไม่ถูกต้อง";
            }
            if ($stmt_check_user !== false && $stmt_check_user !== null) {
                sqlsrv_free_stmt($stmt_check_user);
            }
        }
    }
    if ($conn) {
        sqlsrv_close($conn);
    }
} else {
    // If accessed directly without POST, redirect to login page
    header("Location: guest_login.php");
    exit();
}

// If there are errors, they will be displayed on the login page via a redirect with query params or session flash messages.
// For simplicity here, we'll redirect back to login and expect the login page to handle displaying errors if passed.
// A more robust way is to store errors in $_SESSION['login_errors'] and display them on guest_login.php.

if (!empty($errors)) {
    $_SESSION['login_errors'] = $errors;
    header("Location: guest_login.php");
    exit();
}

?>
