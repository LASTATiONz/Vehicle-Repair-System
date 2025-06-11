<?php
session_start();
include 'db_connect.php'; // โหลด db
require_once 'include/write_log.php'; // โหลดฟังก์ชัน write_log';

// 1. VERIFY USER IS LOGGED IN
// =================================
if (!isset($_SESSION['user_id'])) {
    write_log("Error: User not logged in. Cannot submit application.");
    echo "Error: User not logged in. Cannot submit application.";
    exit;
}
$loggedInUserId = $_SESSION['user_id']; // รับค่า user_id จาก session Login

// 2. GET INITIAL DATA FROM THE FORM
// =================================
$new_branch = $_POST['branch'] ?? null;
$sub_branch = $_POST['sub_branch'] ?? null;
$idCard = $_POST['idCard'] ?? null;
$job_no = $_POST['job_name'] ?? null;

// Basic validation for essential fields
if (!$new_branch || !$sub_branch || !$idCard || !$job_no) {
    write_log("Error: Missing required POST data (branch, sub_branch, idCard, or job_no).");
    die("Error: Missing required fields like branch, sub_branch, idCard, or job_no.");
}

// 3. CHECK FOR EXISTING RESUME & HANDLE `guest_no` LOGIC
// ========================================================
$final_guest_no = null; // This will hold the guest_no to be used in the database operation
$is_new_record = true;  // Flag to determine if we need to INSERT or UPDATE

// Check if a resume already exists for this logged-in user and get its guest_no and branch
$query_check = "SELECT TOP 1 guest_no, branch_job FROM resume_job WHERE fk_user_id = ? ORDER BY rec_time_stamp DESC";
$params_check = array($loggedInUserId);
$stmt_check = sqlsrv_query($conn, $query_check, $params_check);

if ($stmt_check === false) {
    die("Error checking for existing resume: " . print_r(sqlsrv_errors(), true));
}
$row_check = sqlsrv_fetch_array($stmt_check, SQLSRV_FETCH_ASSOC);


if ($row_check) {
    // A. RECORD EXISTS: Prepare for UPDATE logic
    // ------------------------------------------
    $is_new_record = false;
    $existing_guest_no = $row_check['guest_no'];
    $existing_branch = $row_check['branch_job'];
    $final_guest_no = $existing_guest_no; // Default to the existing guest_no

    // ** CORE LOGIC: Check if the branch has changed **
    if ($new_branch !== $existing_branch) {
        write_log("Branch changed from '$existing_branch' to '$new_branch' for user $loggedInUserId. Generating new guest_no.");

        // --- Start: New Guest Number Generation ---
        $query_next = "SELECT guest_next_number FROM job_next_number WHERE branch = ?";
        $params_next = array($new_branch);
        $stmt_next = sqlsrv_query($conn, $query_next, $params_next);
        if ($stmt_next === false) die("Error querying next number: " . print_r(sqlsrv_errors(), true));
        $row_next = sqlsrv_fetch_array($stmt_next, SQLSRV_FETCH_ASSOC);

        if (isset($row_next['guest_next_number'])) {
            $guest_next_number = $row_next['guest_next_number'] + 1;
        } else {
            die("Error: Configuration for new branch '$new_branch' not found in job_next_number table.");
        }

        // Generate the new guest_no and set it as the one to use
        $new_generated_guest_no = sprintf("%sGUEST%05d", $new_branch, $guest_next_number);
        $final_guest_no = $new_generated_guest_no;
        
        // IMPORTANT: Update the counter for the NEW branch
        $update_next_q = "UPDATE job_next_number SET guest_next_number = ? WHERE branch = ?";
        $update_next_p = array($guest_next_number, $new_branch);
        if (sqlsrv_query($conn, $update_next_q, $update_next_p) === false) {
            write_log("CRITICAL: Failed to update guest_next_number for branch '$new_branch'.");
            die("Error updating numbering sequence. Please contact support.");
        }
        write_log("Generated new guest_no: $final_guest_no. Updated counter for branch '$new_branch' to $guest_next_number.");
        // --- End: New Guest Number Generation ---
    }

    // Now, we have the $final_guest_no. Proceed with the UPDATE logic below.
    // The main UPDATE query for session 1 will handle changing the guest_no in the DB.
    // We also store the original guest_no to find the record.
    $original_guest_no_for_update = $existing_guest_no;

} else {
    // B. NO RECORD EXISTS: Prepare for INSERT logic
    // --------------------------------------------
    $is_new_record = true;
    write_log("No existing resume for user $loggedInUserId. Generating new guest_no...");

    // --- Start: New Guest Number Generation ---
    $query_next = "SELECT guest_next_number FROM job_next_number WHERE branch = ?";
    $params_next = array($new_branch);
    $stmt_next = sqlsrv_query($conn, $query_next, $params_next);
    if ($stmt_next === false) die("Error querying next number: " . print_r(sqlsrv_errors(), true));
    $row_next = sqlsrv_fetch_array($stmt_next, SQLSRV_FETCH_ASSOC);

    if (isset($row_next['guest_next_number'])) {
        $guest_next_number = $row_next['guest_next_number'] + 1;
    } else {
        die("Error: Configuration for branch '$new_branch' not found in job_next_number table.");
    }

    $final_guest_no = sprintf("%sGUEST%05d", $new_branch, $guest_next_number);

    // IMPORTANT: Update the counter for the NEW branch
    $update_next_q = "UPDATE job_next_number SET guest_next_number = ? WHERE branch = ?";
    $update_next_p = array($guest_next_number, $new_branch);
    if (sqlsrv_query($conn, $update_next_q, $update_next_p) === false) {
        write_log("CRITICAL: Failed to update guest_next_number for branch '$new_branch' during new record insert.");
        die("Error updating numbering sequence. Please contact support.");
    }
    write_log("Generated new guest_no for new user: $final_guest_no. Updated counter for branch '$new_branch' to $guest_next_number.");
    // --- End: New Guest Number Generation ---
}

// Set the guest_no in the session for subsequent page loads/requests
$_SESSION['guest_no'] = $final_guest_no;

// 4. PREPARE DATA FOR DATABASE
// =============================

// Get job_name from job_no
$job_name = '';
$query_job = "SELECT position FROM jobs_require WHERE job_no = ?";
$params_job = array($job_no);
$stmt_job = sqlsrv_query($conn, $query_job, $params_job);
if ($stmt_job && $row_job = sqlsrv_fetch_array($stmt_job, SQLSRV_FETCH_ASSOC)) {
    $job_name = $row_job['position'];
} else {
    write_log("Warning: Could not find position for job_no: $job_no");
}


// Date conversion function
function convertDateToCE($date) {
    if (empty($date)) return null;
    try {
        $dateObj = new DateTime($date);
        $year = $dateObj->format('Y');
        if ($year > 2400) { // If Buddhist Era year
            $year -= 543;
            $dateObj->setDate($year, $dateObj->format('m'), $dateObj->format('d'));
        }
        return $dateObj->format('Y-m-d');
    } catch (Exception $e) {
        return null;
    }
}

// Get current timestamp
$utc_time = new DateTime('now', new DateTimeZone('UTC'));
$utc_time->setTimezone(new DateTimeZone('Asia/Bangkok'));
$rec_time_stamp = $utc_time->format('Y-m-d H:i:s');


// 5. PERFORM DATABASE OPERATION: INSERT or UPDATE
// ===============================================
$currentSession = $_POST['currentSession'] ?? 'session1'; // Default to session1 if not set

if ($is_new_record) {
    // ***** IT'S A NEW RECORD, SO WE INSERT *****
    // This typically happens only on the first session submission.

    // Get data for session 1
    $name = $_POST['name'] ?? '';
    $nickname = $_POST['nickname'] ?? '';
    $salary = $_POST['salary'] ?? '';
    // ... (gather all other POST variables for the insert)
    $title = $_POST['title'] ?? '';
    $sex = $_POST['sex'] ?? '';
    $birthdayCE = convertDateToCE($_POST['birthday'] ?? null);
    $workDateCE = convertDateToCE($_POST['work_date'] ?? null);
    $createDateCE = convertDateToCE($_POST['create_date'] ?? null);
    $expiryDateCE = convertDateToCE($_POST['expiry_date'] ?? null);
    $age = $_POST['age'] ?? '';
    $status = $_POST['status'] ?? '';
    $nationality = $_POST['nationality'] ?? '';
    $religion = $_POST['religion'] ?? '';
    $height = $_POST['height'] ?? '';
    $weight = $_POST['weight'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $line_id = $_POST['line_id'] ?? '';
    $address = $_POST['address'] ?? '';
    $military = $_POST['radiomilitary'] ?? null;
    $disabled = ($_POST['radiodisabled'] === 'พิการ') ? $_POST['disabled_input'] : 'ปกติ';
    $congenital_disease = ($_POST['radiocongenital_disease'] === 'มี') ? $_POST['disease_input'] : 'ไม่มี';

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

        
    $stmt_insert = sqlsrv_query($conn, $insertQuery, $params);
    if ($stmt_insert === false) {
        $error_msg = "INSERT failed for new user ($loggedInUserId): " . print_r(sqlsrv_errors(), true);
        write_log($error_msg);
        die($error_msg);
    } else {
        write_log("New resume record INSERTED successfully. guest_no: $final_guest_no.");
        echo "Data inserted successfully.";
    }

} else {
    // ***** RECORD EXISTS, SO WE UPDATE *****

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

// Close the connection
sqlsrv_close($conn);
?>