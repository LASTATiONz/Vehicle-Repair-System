<?php
include 'db_connect.php';

if (isset($_POST['job_no'])) {
    $job_no = $_POST['job_no'];

    // SQL เพื่อดึง job_question ตาม job_no
    $sql = "SELECT job_question FROM jobs_require WHERE job_no = ?";
    $params = array($job_no);
    $query = sqlsrv_query($conn, $sql, $params);

    if ($query === false) {
        die(print_r(sqlsrv_errors(), true)); // ส่ง error ถ้ามีปัญหาใน query
    }
    
    $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC);

    if ($row) {
        echo $row['job_question']; // ส่ง job_question กลับ
    } else {
        echo ''; // ถ้าไม่พบข้อมูล ให้ส่งค่าที่ว่าง
    }
}
?>
