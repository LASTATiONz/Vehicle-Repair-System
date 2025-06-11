<?php
session_start();
ob_start();
include("db_connect.php");

echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = trim($_POST['employee_id']);
    $employee_pass = trim($_POST['employee_pass']);

    $sql = "SELECT * FROM employee WHERE employee_id = ? AND password = ? AND department_id = '0' AND status = 'Y'";
    $params = array($employee_id, $employee_pass);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    if (sqlsrv_has_rows($stmt)) {
        $data = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $_SESSION['ses_username'] = $data["employee_id"];
        $_SESSION['ses_password'] = $data["password"];
        $_SESSION['ses_name'] = $data["employee_name"];
        $_SESSION['ses_branch'] = $data["branch"];

        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'เข้าสู่ระบบสำเร็จ!',
                    text: 'กำลังนำทาง...',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = 'dashboard.php';
                });
            });
        </script>";
    } else {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!',
                    text: 'กรุณาลองใหม่อีกครั้ง',
                    timer: 2500,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'index.php';
                });
            });
        </script>";
    }

    sqlsrv_close($conn);
} else {
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'การเข้าถึงไม่ถูกต้อง!',
                text: 'กรุณาเข้าสู่ระบบผ่านหน้าเว็บ',
                timer: 2500,
                timerProgressBar: true
            }).then(() => {
                window.location.href = 'index.php';
            });
        });
    </script>";
}
?>
