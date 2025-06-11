<?php
header('Content-Type: application/json'); // บอกว่าเป็น JSON
error_reporting(0); // ปิด Error Reporting
ini_set('display_errors', 0); // ปิดการแสดง Error

include 'db_connect.php'; // ไฟล์เชื่อมต่อ Database

$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$branch = isset($_POST['branch']) ? trim($_POST['branch']) : '';

// เพิ่ม debug log
error_log("Search term: " . $search);
error_log("Branch: " . $branch);

try {
    $sql = "SELECT jr.*, 
            CASE 
                WHEN DATEDIFF(day, jr.annoudate, GETDATE()) <= 14 THEN 1 
                ELSE 0 
            END as is_new_job
            FROM jobs_require jr 
            WHERE jr.job_status = 'Y'";

    $params = array();
    $types = array();

    if (!empty($search)) {
        $sql .= " AND jr.position LIKE ?";
        $searchParam = "%$search%";
        $params[] = $searchParam;
        $types[] = 'string';
    }

    if (!empty($branch)) {
        $sql .= " AND jr.branch = ?";
        $params[] = $branch;
        $types[] = 'string';
    }

    $sql .= " ORDER BY jr.annoudate DESC";

    // เพิ่ม debug log สำหรับ SQL query
    error_log("SQL Query: " . $sql);
    error_log("Parameters: " . print_r($params, true));

    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        throw new Exception("SQL Error: " . print_r(sqlsrv_errors(), true));
    }

    $jobs = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        if (isset($row['annoudate'])) {
            $row['annoudate'] = $row['annoudate']->format('Y-m-d');
        }
        $jobs[] = $row;
    }

    // เพิ่ม debug log สำหรับจำนวนผลลัพธ์
    error_log("Number of results: " . count($jobs));

    echo json_encode(array(
        'status' => 'success',
        'data' => $jobs
    ));

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(array(
        'status' => 'error',
        'message' => 'เกิดข้อผิดพลาดในการโหลดข้อมูล',
        'debug' => $e->getMessage()
    ));
}
exit();
