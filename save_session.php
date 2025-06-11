<?php
session_start();
include 'db_connect.php'; // โหลด db
require_once 'include/write_log.php'; // โหลดฟังก์ชัน write_log';

// เช็คว่าผู้ใช้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, should not happen if resume_ornategroup.php did its job.
    // Handle error: redirect to login, show error message, etc.
    echo "Error: User not logged in. Cannot submit application.";
    exit;
}

$loggedInUserId = $_SESSION['user_id']; // รับค่า user_id จาก session Login



// รับค่า sub_branch และ branch จาก POST
$sub_branch = $_POST['sub_branch'] ?? null;  // Use null as default if not set
$branch = $_POST['branch'] ?? null;          // Use null as default if not set
if (!$sub_branch || !$branch) {
    echo "Error: sub_branch or branch is missing!";
    exit;
}

// ตรวจสอบว่ามีการกำหนดค่า guest_no ใน session หรือไม่
$guest_no = '';


//เช็คuser_id เพื่อเรียก guest_no
$query = "SELECT TOP 1 guest_no,branch_job FROM resume_job WHERE fk_user_id = ? ORDER BY rec_time_stamp DESC";
$params = array($loggedInUserId);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Handle SQL query errors
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

// หากพบข้อมูล guest_no ในระบบแล้ว ให้ใช้งาน guest_no ล่าสุด
if ($row) {
    $guest_no = $row['guest_no'];
    $_SESSION['guest_no'] = $guest_no; // เก็บ guest_no ไว้ใน session
    echo "Debugging: Existing guest_no = $guest_no <br>";
    write_log("Using existing guest_no: $guest_no");
} else {
    // ไม่พบ guest_no ที่ตรงเงื่อนไข ให้ดำเนินการสร้าง guest_no ใหม่
    echo "Debugging: No existing guest_no found, generating a new one. <br>";
    write_log("No existing guest_no found for branch $branch. Generating new guest_no...");
    $query = "SELECT guest_next_number FROM job_next_number WHERE branch = ?";
    $params = array($branch);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        die("Error in SQL query: " . print_r(sqlsrv_errors(), true)); // Print SQL errors for debugging
        write_log($error_msg); // Log the error message
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if (!$row) {
        die("Error: No rows returned from the database.");
        write_log($error_msg);

    }

    if (isset($row['guest_next_number']) && $row['guest_next_number'] > 0) {
        $guest_next_number = $row['guest_next_number'] + 1;
        echo "Debugging: guest_next_number = $guest_next_number <br>";
    } else {
        die("Error: guest_next_number not found or invalid.");
        write_log($error_msg);
    }

    $guest_no = sprintf("%sGUEST%05d", $branch, $guest_next_number);
    $_SESSION['guest_no'] = $guest_no;
    echo "Debugging: Generated guest_no = $guest_no <br>";
    write_log("Generated new guest_no: $guest_no for branch $branch");

}

// รับค่า job_no จาก POST
$job_no = $_POST['job_name'] ?? null;

// หาชื่อของตำแหน่งงานจาก job_no
if ($job_no) {
    $query = "SELECT position FROM jobs_require WHERE job_no = ?";
    $params = array($job_no);
    $stmt = sqlsrv_query($conn, $query, $params);

    if ($stmt === false) {
        $error_msg = "SQL Error (job_no = $job_no): " . print_r(sqlsrv_errors(), true);
        write_log($error_msg);
        die($error_msg);
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($row) {
        $job_name = $row['position']; // Successfully retrieved job_name
    } else {
        $error_msg = "No job_name found for job_no: $job_no";
        write_log($error_msg);
        echo "Error: $error_msg";
        $job_name = ''; // Set default or handle error
    }
} else {
    $error_msg = "job_no not provided.";
    write_log($error_msg);
    echo "Error: $error_msg";
}

// ดึงข้อมูล user ที่ login เข้ามา
$name = $_POST['name'] ?? '';
$nickname = $_POST['nickname'] ?? '';
$salary = $_POST['salary'] ?? '';
$title = $_POST['title'] ?? '';
$sex = $_POST['sex'] ?? '';



// แปลงปี พศ เป็น คศ
function convertDateToCE($date) {
    $dateObj = new DateTime($date);
    $year = $dateObj->format('Y');

    // If the year is in Buddhist Era, subtract 543 to convert to Christian Era
    if ($year > 2400) {
        $year -= 543;
        $dateObj->setDate($year, $dateObj->format('m'), $dateObj->format('d'));
    }

    return $dateObj->format('Y-m-d'); // Return in 'YYYY-MM-DD' format
}


    // ตัวแปรวันที่
    $birthday = $_POST['birthday'];
    $work_date = $_POST['work_date'];
    $create_date = $_POST['create_date'];
    $expiry_date = $_POST['expiry_date'];

    // แปลงเป็น คศ
    $birthdayCE = convertDateToCE($birthday);
    $workDateCE = convertDateToCE($work_date);
    $createDateCE = convertDateToCE($create_date);
    $expiryDateCE = convertDateToCE($expiry_date);

//

//ประกาศตัวแปร
$age = $_POST['age'] ?? '';
$status = $_POST['status'] ?? '';
$idCard = $_POST['idCard'] ?? '';
$nationality = $_POST['nationality'] ?? '';
$religion = $_POST['religion'] ?? '';
$height = $_POST['height'] ?? '';
$weight = $_POST['weight'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$line_id = $_POST['line_id'] ?? '';
$address = $_POST['address'] ?? '';




// เช็๕ค่า radio button และรับค่าจาก input field ถ้ามีการเลือก
$military = isset($_POST['radiomilitary']) ? $_POST['radiomilitary'] : null;

$disabled = isset($_POST['radiodisabled']) ? $_POST['radiodisabled'] : null;
if ($disabled === 'พิการ') {
    $disabled = $_POST['disabled_input'];
}else{
    $disabled = 'ปกติ';
}

$congenital_disease = isset($_POST['radiocongenital_disease']) ? $_POST['radiocongenital_disease'] : null;
if ($congenital_disease === 'มี') {
    $congenital_disease = $_POST['disease_input'];
}else{
    $congenital_disease = 'ไม่มี';
}

//ใส่ค่าห้ามว่าง

$agree_data = 'n';

// รับค่าDatetimeมาจากนั้นเอามาแปลงเป็น UTC+7ตามเวลาประเทศไทย
$utc_time = new DateTime('now', new DateTimeZone('UTC'));// Get the current date and time in UTC
$utc_time->setTimezone(new DateTimeZone('Asia/Bangkok')); // Convert to your local timezone (UTC +7)
$rec_time_stamp = $utc_time->format('Y-m-d H:i:s');// Format the timestamp to insert into the database



//// ตรวจสอบว่ามี record ที่ guest_no นี้อยู่ใน resume_job หรือไม่
$query = "SELECT COUNT(*) AS count FROM resume_job WHERE guest_no = ?";
$params = array($guest_no);
$stmt = sqlsrv_query($conn, $query, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true)); // Print SQL errors for debugging
}

$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$recordExists = $row['count'];

if ($recordExists > 0) {
    // มี record ที่ guest_no นี้อยู่แล้ว
    if (isset($_POST['currentSession'])) {
        $currentSession = $_POST['currentSession'];

        // Session 1: Personal Information
        if ($currentSession == 'session1') {

            $updateQuery = "UPDATE resume_job 
                SET job_no = ?, job_name = ?, salary = ?, work_date = ?, title = ?, 
                    name = ?, nickname = ?, sex = ?, idcard = ?, create_date = ?, 
                    expiry_date = ?, nationality = ?, religion = ?, phone = ?, 
                    line_id = ?, address = ?, branch_job = ?, birthday = ?, age = ?, status=?, height = ?, weight = ?, 
                    email = ?, military = ?,disabled = ?, congenital_disease = ?, sub_branch = ?, rec_time_stamp = ?
                WHERE guest_no = ?";
                
            $params = array($job_no, $job_name, $salary, $workDateCE, $title, 
                    $name, $nickname, $sex, $idCard, $createDateCE, 
                    $expiryDateCE, $nationality, $religion, $phone, 
                    $line_id, $address, $branch, $birthdayCE, $age, $status, $height, $weight, 
                    $email, $military, $disabled, $congenital_disease, $sub_branch,$rec_time_stamp , $guest_no);

            $stmt = sqlsrv_query($conn, $updateQuery, $params);
    
            if ($stmt === false) {
                $error_msg = "UPDATE failed for session1 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                write_log($error_msg);
                die($error_msg);
            }else {
                write_log("Session1 updated successfully for guest_no: $guest_no.");
            }
        }
    
        // Session 2: Education and Work History
        if ($currentSession == 'session2') {
            $u_school = $_POST['u_school'] ?? ''; 
            $u_year = $_POST['u_year'] ?? ''; 
            $u_gpa = $_POST['u_gpa'] ?? ''; 
            $u_educational = $_POST['u_educational'] ?? ''; 
            $u_major = $_POST['u_major'] ?? ''; 
            $v_school = $_POST['v_school'] ?? ''; 
            $v_year = $_POST['v_year'] ?? ''; 
            $v_gpa = $_POST['v_gpa'] ?? ''; 
            $v_educational = $_POST['v_educational'] ?? ''; 
            $v_major = $_POST['v_major'] ?? ''; 
            $company_1 = $_POST['company_1'] ?? ''; 
            $position_1 = $_POST['position_1'] ?? ''; 
            $datestart_1 = $_POST['datestart_1'] ?? ''; 
            if (!empty($datestart_1)) {
                $datestart_1CE = convertDateToCE($datestart_1);
            } else {
                $datestart_1CE = null; 
            }
            $dateend_1 = $_POST['dateend_1'] ?? ''; 
            if (!empty($dateend_1)) {
                $dateend_1CE = convertDateToCE($dateend_1);
            } else {
                $dateend_1CE = null; 
            }
            $salary_1 = $_POST['salary_1'] ?? ''; 
            $detail_work_1 = $_POST['detail_work_1'] ?? ''; 
            $remark_leave_1 = $_POST['remark_leave_1'] ?? ''; 
            $company_2 = $_POST['company_2'] ?? ''; 
            $position_2 = $_POST['position_2'] ?? ''; 
            $datestart_2 = $_POST['datestart_2'] ?? ''; 
            if (!empty($datestart_2)) {
                $datestart_2CE = convertDateToCE($datestart_2);
            } else {
                $datestart_2CE = null; 
            }
            $dateend_2 = $_POST['dateend_2'] ?? ''; 
            if (!empty($dateend_2)) {
                $dateend_2CE = convertDateToCE($dateend_2);
            } else {
                $dateend_2CE = null; 
            }
            $salary_2 = $_POST['salary_2'] ?? ''; 
            $detail_work_2 = $_POST['detail_work_2'] ?? ''; 
            $remark_leave_2 = $_POST['remark_leave_2'] ?? ''; 
            $company_3 = $_POST['company_3'] ?? ''; 
            $position_3 = $_POST['position_3'] ?? ''; 
            $datestart_3 = $_POST['datestart_3'] ?? ''; 
            if (!empty($datestart_3)) {
                $datestart_3CE = convertDateToCE($datestart_3);
            } else {
                $datestart_3CE = null; 
            }
            $dateend_3 = $_POST['dateend_3'] ?? ''; 
            if (!empty($dateend_3)) {
                $dateend_3CE = convertDateToCE($dateend_3);
            } else {
                $dateend_3CE = null; 
            }
            $salary_3 = $_POST['salary_3'] ?? ''; 
            $detail_work_3 = $_POST['detail_work_3'] ?? ''; 
            $remark_leave_3 = $_POST['remark_leave_3'] ?? ''; 
            $company_4 = $_POST['company_4'] ?? ''; 
            $position_4 = $_POST['position_4'] ?? ''; 
            $datestart_4 = $_POST['datestart_4'] ?? ''; 
            if (!empty($datestart_4)) {
                $datestart_4CE = convertDateToCE($datestart_4);
            } else {
                $datestart_4CE = null; 
            }
            $dateend_4 = $_POST['dateend_4'] ?? ''; 
            if (!empty($dateend_4)) {
                $dateend_4CE = convertDateToCE($dateend_4);
            } else {
                $dateend_4CE = null; 
            }
            $salary_4 = $_POST['salary_4'] ?? ''; 
            $detail_work_4 = $_POST['detail_work_4'] ?? ''; 
            $remark_leave_4 = $_POST['remark_leave_4'] ?? ''; 

            $updateQuery = "UPDATE resume_job SET u_school = ?, u_year = ?, u_gpa = ?,u_educational = ?, u_major = ?, 
                v_school = ?, v_year = ?, v_gpa = ?, v_educational = ?, v_major = ?,  
                company_1 = ?, position_1 = ?, datestart_1 = ?, dateend_1 = ?, salary_1 = ?, detail_work_1 = ?, remark_leave_1 = ?, 
                company_2 = ?, position_2 = ?, datestart_2 = ?, dateend_2 = ?, salary_2 = ?, detail_work_2 = ?, remark_leave_2 = ?,
                company_3 = ?, position_3 = ?, datestart_3 = ?, dateend_3 = ?, salary_3 = ?, detail_work_3 = ?, remark_leave_3 = ?, 
                company_4 = ?, position_4 = ?, datestart_4 = ?, dateend_4 = ?, salary_4 = ?, detail_work_4 = ?, remark_leave_4 = ?
                WHERE guest_no = ?";
            $params = array($u_school, $u_year, $u_gpa, $u_educational, $u_major, $v_school, $v_year, $v_gpa, $v_educational, $v_major,
                            $company_1, $position_1, $datestart_1CE, $dateend_1CE, $salary_1, $detail_work_1, $remark_leave_1, 
                            $company_2, $position_2, $datestart_2CE, $dateend_2CE, $salary_2, $detail_work_2, $remark_leave_2,
                            $company_3, $position_3, $datestart_3CE, $dateend_3CE, $salary_3, $detail_work_3, $remark_leave_3, 
                            $company_4, $position_4, $datestart_4CE, $dateend_4CE, $salary_4, $detail_work_4, $remark_leave_4,
                            $guest_no);
            $stmt = sqlsrv_query($conn, $updateQuery, $params);
    
            if ($stmt === false) {
                $error_msg = "UPDATE failed for session2 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                write_log($error_msg);
                die($error_msg);
            }else {
                write_log("Session2 updated successfully for guest_no: $guest_no.");
            }
        }
        
        // Session 3: Family Information
            if ($currentSession == 'session3') {
                $father_name = $_POST['father_name'] ?? ''; 
                $father_age = $_POST['father_age'] ?? ''; 
                $father_occupation = $_POST['father_occupation'] ?? ''; 
                $father_Place_work = $_POST['father_Place_work'] ?? ''; 
                $father_talephone = $_POST['father_talephone'] ?? ''; 
                $father_status = $_POST['radiofather_status'] ?? ''; 
                $mother_name = $_POST['mother_name'] ?? ''; 
                $mother_age = $_POST['mother_age'] ?? ''; 
                $mother_occupation = $_POST['mother_occupation'] ?? ''; 
                $mother_Place_work = $_POST['mother_Place_work'] ?? ''; 
                $mother_talephone = $_POST['mother_talephone'] ?? ''; 
                $mother_status = $_POST['radiomother_status'] ?? ''; 
                $spouse_name = $_POST['spouse_name'] ?? ''; 
                $spouse_age = $_POST['spouse_age'] ?? ''; 
                $spouse_occupation = $_POST['spouse_occupation'] ?? ''; 
                $spouse_Place_work = $_POST['spouse_Place_work'] ?? ''; 
                $spouse_talephone = $_POST['spouse_talephone'] ?? ''; 
                $children = $_POST['children'] ?? ''; 
                $children_age = $_POST['children_age'] ?? ''; 
                $children_sex = $_POST['children_sex'] ?? ''; 
                $children_study = $_POST['children_study'] ?? ''; 
                $children_nonstudy = $_POST['children_nonstudy'] ?? ''; 
                $num_bro_sis = $_POST['num_brother_sisters'] ?? ''; 
                $num_sir = $_POST['num_sir'] ?? ''; 

    
                $updateQuery = "UPDATE resume_job SET 
                    father_name = ?, father_age = ?, father_occupation = ?,father_Place_work = ?, father_talephone = ?, father_status = ?, 
                    mother_name = ?, mother_age = ?, mother_occupation = ?, mother_Place_work = ?, mother_talephone = ?, mother_status = ?, 
                    spouse_name = ?, spouse_age = ?, spouse_occupation = ?, spouse_Place_work = ?, spouse_talephone = ?, 
                    children = ?, children_age = ?, children_sex = ?, children_study = ?, children_nonstudy = ?, num_bro_sis = ?, num_sir = ?
                    WHERE guest_no = ?";

                $params = array(
                    $father_name, $father_age, $father_occupation, $father_Place_work, $father_talephone, $father_status, 
                    $mother_name, $mother_age, $mother_occupation, $mother_Place_work,$mother_talephone, $mother_status, 
                    $spouse_name, $spouse_age, $spouse_occupation, $spouse_Place_work, $spouse_talephone, 
                    $children, $children_age, $children_sex, $children_study, $children_nonstudy,$num_bro_sis,$num_sir,
                    $guest_no);

                $stmt = sqlsrv_query($conn, $updateQuery, $params);
        
                if ($stmt === false) {
                    $error_msg = "UPDATE failed for session3 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                    write_log($error_msg);
                    die($error_msg);
                }else {
                    write_log("Session3 updated successfully for guest_no: $guest_no.");
                }
            }

        // Session 4: Skill and Experience
        
        if ($currentSession == 'session4') {

            $thai_listen = $_POST['thai_listen'] ?? '';
            $thai_speak	= $_POST['thai_speak'] ?? '';
            $thai_read	= $_POST['thai_read'] ?? '';
            $thai_write	= $_POST['thai_write'] ?? '';
            $eng_listen	=  $_POST['eng_listen'] ?? '';
            $eng_speak	= $_POST['eng_speak'] ?? '';
            $eng_read	= $_POST['eng_read'] ?? '';
            $eng_write	= $_POST['eng_write'] ?? '';
            $other_languages	= $_POST['other_languages'] ?? '';
            $computer_skill	= $_POST['computer_skill'] ?? '';
            $talent_skill	= $_POST['talent_skill'] ?? '';

            $person_name	= $_POST['person_name'] ?? '';
            $person_position	= $_POST['person_position'] ?? '';
            $person_relations	= $_POST['person_relations'] ?? '';
            $person_referen_name	= $_POST['person_referen_name'] ?? '';
            $person_referen_position	= $_POST['person_referen_position'] ?? '';
            $person_referen_phone	= $_POST['person_referen_phone'] ?? '';
            $person_referen_address	= $_POST['person_referen_address'] ?? '';
            $person_referen_relations	= $_POST['person_referen_relations'] ?? '';

            $checknews = implode(",", $_POST['checkboxnews']);
            $checkdrivers =  implode(",", $_POST['checkboxdriver']);
            $checkdrive_licenses =  implode(",", $_POST['checkboxdrive_license']);



            $imformation = $_POST['radioimformation'] ?? '';
            $penalize = $_POST['radiopenalize'] ?? '';
            $dismiss = $_POST['radiodismiss'] ?? '';
            $income_other = $_POST['radioincome_other'] ?? '';
            $health = $_POST['radiohealth'] ?? '';
            $move_job = $_POST['radiomove'] ?? '';


            $updateQuery = "UPDATE resume_job SET thai_listen = ?, thai_speak = ?, thai_read = ?, thai_write = ?, 
                eng_listen = ?, eng_speak = ?, eng_read = ?, eng_write = ?, other_languages = ?, 
                computer_skill = ?, talent_skill = ?, person_name = ?, person_position = ?, person_relations = ?, 
                person_referen_name = ?, person_referen_position = ?, person_referen_phone = ?, person_referen_address = ?, 
                person_referen_relations =?,news = ?, driver = ?, drive_license = ?, imformation = ?, penalize = ?, dismiss = ?, 
                income_other = ?, health = ?, move_job = ?
                WHERE guest_no = ?";
            $params = array($thai_listen, $thai_speak, $thai_read, $thai_write, 
                $eng_listen, $eng_speak, $eng_read, $eng_write, $other_languages,
                $computer_skill, $talent_skill, $person_name, $person_position, $person_relations, 
                $person_referen_name, $person_referen_position, $person_referen_phone, $person_referen_address,
                $person_referen_relations,$checknews, $checkdrivers, $checkdrive_licenses, $imformation, $penalize, $dismiss, 
                $income_other, $health, $move_job,             
                $guest_no);

            $stmt = sqlsrv_query($conn, $updateQuery, $params);
    
            if ($stmt === false) {
                $error_msg = "UPDATE failed for session4 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                write_log($error_msg);
                die($error_msg);
            }else {
                write_log("Session4 updated successfully for guest_no: $guest_no.");
            }
        }

        // Session 5: More Information
        if ($currentSession == 'session5') {
            $more_infor1 = $_POST['more_infor1'] ?? '';
            $more_infor2 = $_POST['more_infor2'] ?? '';
            $more_infor3 = $_POST['more_infor3'] ?? '';
            $more_infor4 = $_POST['more_infor4'] ?? '';
            $more_infor5 = $_POST['more_infor5'] ?? '';
            $more_infor6 = $_POST['more_infor6'] ?? '';
            
            $updateQuery = "UPDATE resume_job SET more_infor1 = ?, more_infor2 = ?, more_infor3 = ?, more_infor4 = ?, more_infor5 = ?, more_infor6 = ? WHERE guest_no = ?";
            
            $params = array($more_infor1, $more_infor2, $more_infor3, $more_infor4, $more_infor5, $more_infor6, $guest_no);
            
            $stmt = sqlsrv_query($conn, $updateQuery, $params);
    
            if ($stmt === false) {
                $error_msg = "UPDATE failed for session5 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                write_log($error_msg);
                die($error_msg);
            }else {
                write_log("Session5 updated successfully for guest_no: $guest_no.");
            }
        }

        // Session 6: Agreement
        if ($currentSession == 'session6') {

            $agree_data = $_POST['radioagree'] ?? '';

            $updateQuery = "UPDATE resume_job SET agree_data = ?  WHERE guest_no = ?";
            $params = array($agree_data, $guest_no);
            $stmt = sqlsrv_query($conn, $updateQuery, $params);
    
            if ($stmt === false) {
                $error_msg = "UPDATE failed for session6 (guest_no: $guest_no): " . print_r(sqlsrv_errors(), true);
                write_log($error_msg);
                die($error_msg);        
            } else {
                write_log("Session6 updated successfully for guest_no: $guest_no.");
                write_log("All sessions completed for guest_no: $guest_no.");
                write_log("--------------------------------------------------");
            }
        }

    }
    



} else {
    // ไม่มี record ที่ guest_no นี้อยู่ใน resume_job ให้ทำการ insert ใหม่
    //------------------
    // Insert or update into the resume_job table
    $insertQuery = "INSERT INTO resume_job (guest_no, job_no, job_name, branch_job, name, nickname, salary, work_date, title, sex, 
        birthday, age, status, idCard, create_date, expiry_date, 
        nationality, religion, height, weight, phone, email, 
        line_id, address, military, disabled, congenital_disease, 
        rec_time_stamp,sub_branch, fk_user_id)
        VALUES (?,?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,  ?, ?)";

    $params = array($guest_no, $job_no, $job_name, $branch, $name, $nickname, $salary, $workDateCE, $title, $sex, 
        $birthdayCE, $age, $status, $idCard, $createDateCE, $expiryDateCE, $nationality, 
        $religion, $height, $weight, $phone, $email, $line_id, $address, 
        $military, $disabled, $congenital_disease, $rec_time_stamp, $sub_branch, $loggedInUserId);

    $stmt = sqlsrv_query($conn, $insertQuery, $params);
    // Check for SQL errors
    if ($stmt === false) {
        $error_msg = "INSERT failed for guest_no $guest_no: " . print_r(sqlsrv_errors(), true);
        write_log($error_msg);
        die($error_msg);
    }else {
        write_log("Inserted resume_job session1 successfully for guest_no $guest_no.");
    }

    //อัพเดท guest_next_number หลังจาก insert ข้อมูลใหม่
    $updateQuery = "UPDATE job_next_number SET guest_next_number = $guest_next_number  WHERE branch = ?";
    $updateParams = array($branch);
    $updateStmt = sqlsrv_query($conn, $updateQuery, $updateParams);

    if ($updateStmt === false) {
        $error_msg = "UPDATE guest_next_number failed for branch $branch: " . print_r(sqlsrv_errors(), true);
        write_log($error_msg);
        die($error_msg);
    } else {
        write_log("Updated guest_next_number to $guest_next_number for branch $branch.");
    }

    echo "Data inserted/updated session1 successfully and guest_next_number incremented.";

}


// Log form data for debugging
error_log(print_r($_POST, true)); // This will log data to the PHP error log


sqlsrv_close($conn);
echo "Data inserted/updated successfully";

?>