<?php
$serverName = "192.168.15.100"; 
$connectionOptions = array(
    "Database" => "PE",
    "Uid" => "sa",
    "PWD" => "Newgen2021",
    "CharacterSet" => "UTF-8"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));  
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'submit_form') {
        $guest_no = $_POST['guest_no'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];

        // Update resume_job table with address and phone
        $sql = "UPDATE resume_job SET address = ?, phone = ? WHERE guest_no = ?";
        $params = array($address, $phone, $guest_no);
        $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Error updating data']);
        } else {
            echo json_encode(['success' => true, 'guest_no' => $guest_no]);
        }
    } else {
        $branch = $_POST['branch'];
        $name = $_POST['name'];
        $birthdate = $_POST['birthdate'];
        $email = $_POST['email'];
        $guest_no = $_POST['guest_no'];  // Check if guest_no exists
        // ใส่ค่าห้ามว่าง
        $job_no = 'n';
        $work_date =  $_POST['birthdate'];
        $create_date =  $_POST['birthdate'];
        $expiry_date =  $_POST['birthdate'];
        $thai_listen = 'n';
        $thai_speak	= 'n';
        $thai_read	= 'n';
        $thai_write	= 'n';
        $eng_listen	= 'n';
        $eng_speak	= 'n';
        $eng_read	= 'n';
        $eng_write	= 'n';
        $imformation = 'n';
        $penalize = 'n';
        $dismiss = 'n';
        $income_other = 'n';
        $health = 'n';
        $move_job = 'n';
        $agree_data = 'n';
        $rec_time_stamp = '2024-09-01 13:06:54.237';


        if ($guest_no) {
            // If guest_no exists, update the record
            $updateSql = "UPDATE resume_job SET name = ?, birthday = ?, email = ?, branch_job = ? WHERE guest_no = ?";
            $updateParams = array($name, $birthdate, $email, $branch, $guest_no);
            $updateStmt = sqlsrv_query($conn, $updateSql, $updateParams);

            if ($updateStmt === false) {
                $errors = print_r(sqlsrv_errors(), true);
                echo json_encode(['success' => false, 'message' => 'Error updating data', 'details' => $errors]);
            } else {
                echo json_encode(['success' => true, 'guest_no' => $guest_no]);
            }
        } else {
            // Fetch guest_next_number from job_next_number table
            $sql = "SELECT guest_next_number FROM job_next_number WHERE branch = ?";
            $params = array($branch);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false || !sqlsrv_has_rows($stmt)) {
                echo json_encode(['success' => false, 'message' => 'Branch not found']);
                exit;
            }

            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $guest_next_number = $row['guest_next_number'];
            
            // Generate guest_no
            $guest_no = $branch . str_pad($guest_next_number, 8, "0", STR_PAD_LEFT);

            // Insert form data into resume_job table
            $insertSql = "INSERT INTO resume_job (guest_no, name, birthday, email, branch_job, job_no, work_date, create_date, expiry_date, thai_listen,thai_speak , thai_read, thai_write, eng_listen, eng_speak, eng_read, eng_write, imformation, penalize, dismiss, income_other, health, move_job, agree_data, rec_time_stamp )  VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?,?, ?, ?, ?, ?)";
            $insertParams = array($guest_no, $name, $birthdate, $email, $branch,$job_no, $work_date, $create_date, $expiry_date, $thai_listen, $thai_speak, $thai_read, $thai_write, $eng_listen, $eng_speak, $eng_read, $eng_write, $imformation, $penalize, $dismiss, $income_other, $health, $move_job, $agree_data, $rec_time_stamp );

            $insertStmt = sqlsrv_query($conn, $insertSql, $insertParams);

            if ($insertStmt === false) {
                $errors = print_r(sqlsrv_errors(), true);
                echo json_encode(['success' => false, 'message' => 'Error inserting data', 'details' => $errors]);
            } else {
                // Update guest_next_number in job_next_number
                $updateSql = "UPDATE job_next_number SET guest_next_number = guest_next_number + 1 WHERE branch = ?";
                sqlsrv_query($conn, $updateSql, $params);

                echo json_encode(['success' => true, 'guest_no' => $guest_no]);
            }
        }
    }
}

sqlsrv_close($conn);
?>
