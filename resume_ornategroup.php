<?php
session_start(); // Start the session at the very beginning

// Function to sanitize input 
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


// Check if the user is logged in, if not then redirect to login page
if (!isset($_SESSION["user_id"])) {
    header("Location: guest_login.php");
    exit;
}
$loggedInUserId = $_SESSION['user_id']; // Get the logged-in user's ID




// Retrieve user details from session for pre-filling
$user_title = isset($_SESSION['user_title']) ? htmlspecialchars($_SESSION['user_title']) : '';
$user_first_name = isset($_SESSION['user_first_name']) ? htmlspecialchars($_SESSION['user_first_name']) : '';
$user_last_name = isset($_SESSION['user_last_name']) ? htmlspecialchars($_SESSION['user_last_name']) : '';
$user_full_name = trim($user_first_name . " " . $user_last_name);
$user_email = isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '';
$user_phone_raw = isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : '';

//ระบบดึงข้อมูลจาก resume_job
$form_data = []; 
$page_mode = 'new'; 
$load_guest_no = null; // Initialize $load_guest_no

// Format phone number for display
$user_phone_display = $user_phone_raw; // Default to raw if not 10 digits
if (strlen($user_phone_raw) == 10 && ctype_digit($user_phone_raw)) {
    $user_phone_display = substr($user_phone_raw, 0, 3) . '-' .
                          substr($user_phone_raw, 3, 3) . '-' .
                          substr($user_phone_raw, 6, 4);
}

// The rest of your existing PHP code for this page follows
include 'db_connect.php'; // Your database connection


// include 'modle_notify.php'; // Uncomment if needed
$job_no_from_url = isset($_GET['job_no']) ? sanitize_input($_GET['job_no']) : ""; 

// --- Logic to determine which application to load ---
if (isset($_GET['load_guest_no'])) {
    $load_guest_no = sanitize_input($_GET['load_guest_no']);
} else {
    $sql_find_recent = "SELECT TOP 1 guest_no FROM resume_job WHERE fk_user_id = ? ORDER BY rec_time_stamp DESC";
    $params_find_recent = array($loggedInUserId);
    $query_find_recent = sqlsrv_query($conn, $sql_find_recent, $params_find_recent);

    if ($query_find_recent && sqlsrv_has_rows($query_find_recent)) {
        $recent_app_data = sqlsrv_fetch_array($query_find_recent, SQLSRV_FETCH_ASSOC);
        if ($recent_app_data && isset($recent_app_data['guest_no'])) {
            $load_guest_no = $recent_app_data['guest_no'];
        }
    }
    if ($query_find_recent !== false && $query_find_recent !== null) {
        sqlsrv_free_stmt($query_find_recent);
    }
}

// --- Load application data if $load_guest_no is set ---
if ($load_guest_no) {
    $strSQL_load = "SELECT * FROM resume_job WHERE guest_no = ? AND fk_user_id = ?"; 
    $params_load = array($load_guest_no, $loggedInUserId);
    $options_load = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
    $query_load = sqlsrv_query($conn, $strSQL_load, $params_load, $options_load);

    if ($query_load === false) {
        // Error loading application data (full load)
    } elseif ($query_load && sqlsrv_has_rows($query_load)) {
        $form_data = sqlsrv_fetch_array($query_load, SQLSRV_FETCH_ASSOC);
        if ($form_data) {
            $page_mode = 'edit';
        } else {
            $load_guest_no = null; 
        }
    } else {
        $load_guest_no = null; 
    }
    if ($query_load !== false && $query_load !== null) {
        sqlsrv_free_stmt($query_load);
    }
}


//ประกาศตัวแปรเพื่อดึงข้อมูลมาใส่ใน form ตามที่ login


// --- Prefill for Session 1: Personal Information ---
    // Job selection related prefill values
    $prefill_sub_branch = ($page_mode == 'edit' && isset($form_data['sub_branch'])) ? htmlspecialchars($form_data['sub_branch']) : '';
    $prefill_branch_job = ($page_mode == 'edit' && isset($form_data['branch_job'])) ? htmlspecialchars(trim($form_data['branch_job'])) : ''; // trim() is good for comparison
    $prefill_job_no_db = ($page_mode == 'edit' && isset($form_data['job_no'])) ? htmlspecialchars(trim($form_data['job_no'])) : ''; // trim()

    // Determine values for pre-filling, prioritizing $form_data if editing
    $prefill_title = ($page_mode == 'edit' && isset($form_data['title'])) ? htmlspecialchars($form_data['title']) : $user_title;
    $prefill_name = ($page_mode == 'edit' && isset($form_data['name'])) ? htmlspecialchars($form_data['name']) : $user_full_name;
    $prefill_nickname = ($page_mode == 'edit' && isset($form_data['nickname'])) ? htmlspecialchars($form_data['nickname']) : '';
    $prefill_email = ($page_mode == 'edit' && isset($form_data['email'])) ? htmlspecialchars($form_data['email']) : $user_email;
    $prefill_phone_raw = ($page_mode == 'edit' && isset($form_data['phone'])) ? htmlspecialchars($form_data['phone']) : $user_phone_raw;

    $prefill_salary = ($page_mode == 'edit' && isset($form_data['salary'])) ? htmlspecialchars($form_data['salary']) : '';
    $prefill_work_date = ($page_mode == 'edit' && isset($form_data['work_date']) && $form_data['work_date'] instanceof DateTimeInterface) ? $form_data['work_date']->format('Y-m-d') : '';
    $prefill_sex = ($page_mode == 'edit' && isset($form_data['sex'])) ? htmlspecialchars($form_data['sex']) : '';

    $prefill_birthday = ($page_mode == 'edit' && isset($form_data['birthday']) && $form_data['birthday'] instanceof DateTimeInterface) ? $form_data['birthday']->format('Y-m-d') : '';
    $prefill_age = ($page_mode == 'edit' && isset($form_data['age'])) ? htmlspecialchars($form_data['age']) : '';
    $prefill_status = ($page_mode == 'edit' && isset($form_data['status'])) ? htmlspecialchars($form_data['status']) : '';
    $prefill_idcard = ($page_mode == 'edit' && isset($form_data['idcard'])) ? htmlspecialchars($form_data['idcard']) : '';
    $prefill_create_date = ($page_mode == 'edit' && isset($form_data['create_date']) && $form_data['create_date'] instanceof DateTimeInterface) ? $form_data['create_date']->format('Y-m-d') : '';
    $prefill_expiry_date = ($page_mode == 'edit' && isset($form_data['expiry_date']) && $form_data['expiry_date'] instanceof DateTimeInterface) ? $form_data['expiry_date']->format('Y-m-d') : '';
    $prefill_nationality = ($page_mode == 'edit' && isset($form_data['nationality'])) ? htmlspecialchars($form_data['nationality']) : 'ไทย'; // Default to ไทย
    $prefill_religion = ($page_mode == 'edit' && isset($form_data['religion'])) ? htmlspecialchars($form_data['religion']) : '';
    $prefill_height = ($page_mode == 'edit' && isset($form_data['height'])) ? htmlspecialchars($form_data['height']) : '';
    $prefill_weight = ($page_mode == 'edit' && isset($form_data['weight'])) ? htmlspecialchars($form_data['weight']) : '';
    $prefill_line_id = ($page_mode == 'edit' && isset($form_data['line_id'])) ? htmlspecialchars($form_data['line_id']) : '';
    $prefill_address = ($page_mode == 'edit' && isset($form_data['address'])) ? htmlspecialchars($form_data['address']) : ''; // The main address part
    // For province, district, tumbon, zipcode - their prefill will primarily be handled by setting the value attribute
    // and then JS might need to trigger dependent loads.

    $prefill_military = ($page_mode == 'edit' && isset($form_data['military'])) ? htmlspecialchars($form_data['military']) : '';
    // For radio groups with conditional inputs (like congenital_disease and disabled)
    $prefill_radiocongenital_disease = ($page_mode == 'edit' && isset($form_data['congenital_disease'])) ? htmlspecialchars($form_data['congenital_disease']) : 'ไม่มี'; // Default to "ไม่มี" for radio
    $prefill_disease_input = ($page_mode == 'edit' && $prefill_radiocongenital_disease !== 'ไม่มี' && isset($form_data['congenital_disease'])) ? htmlspecialchars($form_data['congenital_disease']) : ''; // If 'มี', this might hold the actual disease name

    $prefill_radiodisabled = ($page_mode == 'edit' && isset($form_data['disabled'])) ? htmlspecialchars($form_data['disabled']) : 'ปกติ'; // Default to "ปกติ" for radio
    $prefill_disabled_input = ($page_mode == 'edit' && $prefill_radiodisabled !== 'ปกติ' && isset($form_data['disabled'])) ? htmlspecialchars($form_data['disabled']) : '';




    // Format phone number for display
    $prefill_phone_display = $prefill_phone_raw;
    if (strlen($prefill_phone_raw) == 10 && ctype_digit($prefill_phone_raw)) {
        $prefill_phone_display = substr($prefill_phone_raw, 0, 3) . '-' .
                                substr($prefill_phone_raw, 3, 3) . '-' .
                                substr($prefill_phone_raw, 6, 4);
    }


    $job_no_for_dropdowns = $prefill_job_no_db ? $prefill_job_no_db : $job_no_from_url;
// ... END. ...   

// --- Prefill for Session 2: Education History ---
    $prefill_u_school = ($page_mode == 'edit' && isset($form_data['u_school'])) ? htmlspecialchars($form_data['u_school']) : '';
    $prefill_u_year = ($page_mode == 'edit' && isset($form_data['u_year'])) ? htmlspecialchars($form_data['u_year']) : '';
    $prefill_u_gpa = ($page_mode == 'edit' && isset($form_data['u_gpa'])) ? htmlspecialchars($form_data['u_gpa']) : '';
    $prefill_u_educational = ($page_mode == 'edit' && isset($form_data['u_educational'])) ? htmlspecialchars($form_data['u_educational']) : '';
    $prefill_u_major = ($page_mode == 'edit' && isset($form_data['u_major'])) ? htmlspecialchars($form_data['u_major']) : '';
    $prefill_v_school = ($page_mode == 'edit' && isset($form_data['v_school'])) ? htmlspecialchars($form_data['v_school']) : '';
    $prefill_v_year = ($page_mode == 'edit' && isset($form_data['v_year'])) ? htmlspecialchars($form_data['v_year']) : '';
    $prefill_v_gpa = ($page_mode == 'edit' && isset($form_data['v_gpa'])) ? htmlspecialchars($form_data['v_gpa']) : '';
    $prefill_v_educational = ($page_mode == 'edit' && isset($form_data['v_educational'])) ? htmlspecialchars($form_data['v_educational']) : '';
    $prefill_v_major = ($page_mode == 'edit' && isset($form_data['v_major'])) ? htmlspecialchars($form_data['v_major']) : '';
    $prefill_company_1 = ($page_mode == 'edit' && isset($form_data['company_1'])) ? htmlspecialchars($form_data['company_1']) : '';
    $prefill_position_1 = ($page_mode == 'edit' && isset($form_data['position_1'])) ? htmlspecialchars($form_data['position_1']) : '';
    $prefill_datestart_1 = ($page_mode == 'edit' && isset($form_data['datestart_1']) && $form_data['datestart_1'] instanceof DateTimeInterface) ? $form_data['datestart_1']->format('Y-m-d') : '';
    $prefill_dateend_1 = ($page_mode == 'edit' && isset($form_data['dateend_1']) && $form_data['dateend_1'] instanceof DateTimeInterface) ? $form_data['dateend_1']->format('Y-m-d') : '';
    $prefill_salary_1 = ($page_mode == 'edit' && isset($form_data['salary_1'])) ? htmlspecialchars($form_data['salary_1']) : '';
    $prefill_detail_work_1 = ($page_mode == 'edit' && isset($form_data['detail_work_1'])) ? htmlspecialchars($form_data['detail_work_1']) : '';
    $prefill_remark_leave_1 = ($page_mode == 'edit' && isset($form_data['remark_leave_1'])) ? htmlspecialchars($form_data['remark_leave_1']) : '';
    $prefill_company_2 = ($page_mode == 'edit' && isset($form_data['company_2'])) ? htmlspecialchars($form_data['company_2']) : '';
    $prefill_position_2 = ($page_mode == 'edit' && isset($form_data['position_2'])) ? htmlspecialchars($form_data['position_2']) : '';
    $prefill_datestart_2 = ($page_mode == 'edit' && isset($form_data['datestart_2']) && $form_data['datestart_2'] instanceof DateTimeInterface) ? $form_data['datestart_2']->format('Y-m-d') : '';
    $prefill_dateend_2 = ($page_mode == 'edit' && isset($form_data['dateend_2']) && $form_data['dateend_2'] instanceof DateTimeInterface) ? $form_data['dateend_2']->format('Y-m-d') : '';
    $prefill_salary_2 = ($page_mode == 'edit' && isset($form_data['salary_2'])) ? htmlspecialchars($form_data['salary_2']) : '';
    $prefill_detail_work_2 = ($page_mode == 'edit' && isset($form_data['detail_work_2'])) ? htmlspecialchars($form_data['detail_work_2']) : '';
    $prefill_remark_leave_2 = ($page_mode == 'edit' && isset($form_data['remark_leave_2'])) ? htmlspecialchars($form_data['remark_leave_2']) : '';
    $prefill_company_3 = ($page_mode == 'edit' && isset($form_data['company_3'])) ? htmlspecialchars($form_data['company_3']) : '';
    $prefill_position_3 = ($page_mode == 'edit' && isset($form_data['position_3'])) ? htmlspecialchars($form_data['position_3']) : '';
    $prefill_datestart_3 = ($page_mode == 'edit' && isset($form_data['datestart_3']) && $form_data['datestart_3'] instanceof DateTimeInterface) ? $form_data['datestart_3']->format('Y-m-d') : '';
    $prefill_dateend_3 = ($page_mode == 'edit' && isset($form_data['dateend_3']) && $form_data['dateend_3'] instanceof DateTimeInterface) ? $form_data['dateend_3']->format('Y-m-d') : '';
    $prefill_salary_3 = ($page_mode == 'edit' && isset($form_data['salary_3'])) ? htmlspecialchars($form_data['salary_3']) : '';
    $prefill_detail_work_3 = ($page_mode == 'edit' && isset($form_data['detail_work_3'])) ? htmlspecialchars($form_data['detail_work_3']) : '';
    $prefill_remark_leave_3 = ($page_mode == 'edit' && isset($form_data['remark_leave_3'])) ? htmlspecialchars($form_data['remark_leave_3']) : '';
    $prefill_company_4 = ($page_mode == 'edit' && isset($form_data['company_4'])) ? htmlspecialchars($form_data['company_4']) : '';
    $prefill_position_4 = ($page_mode == 'edit' && isset($form_data['position_4'])) ? htmlspecialchars($form_data['position_4']) : '';
    $prefill_datestart_4 = ($page_mode == 'edit' && isset($form_data['datestart_4']) && $form_data['datestart_4'] instanceof DateTimeInterface) ? $form_data['datestart_4']->format('Y-m-d') : '';
    $prefill_dateend_4 = ($page_mode == 'edit' && isset($form_data['dateend_4']) && $form_data['dateend_4'] instanceof DateTimeInterface) ? $form_data['dateend_4']->format('Y-m-d') : '';
    $prefill_salary_4 = ($page_mode == 'edit' && isset($form_data['salary_4'])) ? htmlspecialchars($form_data['salary_4']) : '';
    $prefill_detail_work_4 = ($page_mode == 'edit' && isset($form_data['detail_work_4'])) ? htmlspecialchars($form_data['detail_work_4']) : '';
    $prefill_remark_leave_4 = ($page_mode == 'edit' && isset($form_data['remark_leave_4'])) ? htmlspecialchars($form_data['remark_leave_4']) : '';
// ... END. ...


// --- Prefill for Session 3: Family Information ---
    // Father's Info
    $prefill_father_name = ($page_mode == 'edit' && isset($form_data['father_name'])) ? htmlspecialchars($form_data['father_name']) : '';
    $prefill_father_age = ($page_mode == 'edit' && isset($form_data['father_age'])) ? htmlspecialchars($form_data['father_age']) : '';
    $prefill_father_occupation = ($page_mode == 'edit' && isset($form_data['father_occupation'])) ? htmlspecialchars($form_data['father_occupation']) : '';
    $prefill_father_Place_work = ($page_mode == 'edit' && isset($form_data['father_Place_work'])) ? htmlspecialchars($form_data['father_Place_work']) : '';
    $prefill_father_talephone = ($page_mode == 'edit' && isset($form_data['father_talephone'])) ? htmlspecialchars($form_data['father_talephone']) : '';
    $prefill_father_status = ($page_mode == 'edit' && isset($form_data['father_status'])) ? htmlspecialchars($form_data['father_status']) : '';

    // Mother's Info
    $prefill_mother_name = ($page_mode == 'edit' && isset($form_data['mother_name'])) ? htmlspecialchars($form_data['mother_name']) : '';
    $prefill_mother_age = ($page_mode == 'edit' && isset($form_data['mother_age'])) ? htmlspecialchars($form_data['mother_age']) : '';
    $prefill_mother_occupation = ($page_mode == 'edit' && isset($form_data['mother_occupation'])) ? htmlspecialchars($form_data['mother_occupation']) : '';
    $prefill_mother_Place_work = ($page_mode == 'edit' && isset($form_data['mother_Place_work'])) ? htmlspecialchars($form_data['mother_Place_work']) : '';
    $prefill_mother_talephone = ($page_mode == 'edit' && isset($form_data['mother_talephone'])) ? htmlspecialchars($form_data['mother_talephone']) : '';
    $prefill_mother_status = ($page_mode == 'edit' && isset($form_data['mother_status'])) ? htmlspecialchars($form_data['mother_status']) : '';

    // Siblings
    $prefill_num_bro_sis = ($page_mode == 'edit' && isset($form_data['num_bro_sis'])) ? htmlspecialchars($form_data['num_bro_sis']) : '';
    $prefill_num_sir = ($page_mode == 'edit' && isset($form_data['num_sir'])) ? htmlspecialchars($form_data['num_sir']) : '';

    // Spouse & Children
    $prefill_spouse_name = ($page_mode == 'edit' && isset($form_data['spouse_name'])) ? htmlspecialchars($form_data['spouse_name']) : '';
    $prefill_spouse_age = ($page_mode == 'edit' && isset($form_data['spouse_age'])) ? htmlspecialchars($form_data['spouse_age']) : '';
    $prefill_spouse_occupation = ($page_mode == 'edit' && isset($form_data['spouse_occupation'])) ? htmlspecialchars($form_data['spouse_occupation']) : '';
    $prefill_spouse_Place_work = ($page_mode == 'edit' && isset($form_data['spouse_Place_work'])) ? htmlspecialchars($form_data['spouse_Place_work']) : '';
    $prefill_spouse_talephone = ($page_mode == 'edit' && isset($form_data['spouse_talephone'])) ? htmlspecialchars($form_data['spouse_talephone']) : '';
    $prefill_children = ($page_mode == 'edit' && isset($form_data['children'])) ? htmlspecialchars($form_data['children']) : '';

// --- END ---


// --- Prefill for Session 4: Skills and Other Information ---
    // Language Skills
    $prefill_thai_listen = ($page_mode == 'edit' && isset($form_data['thai_listen'])) ? htmlspecialchars($form_data['thai_listen']) : '';
    $prefill_thai_speak = ($page_mode == 'edit' && isset($form_data['thai_speak'])) ? htmlspecialchars($form_data['thai_speak']) : '';
    $prefill_thai_read = ($page_mode == 'edit' && isset($form_data['thai_read'])) ? htmlspecialchars($form_data['thai_read']) : '';
    $prefill_thai_write = ($page_mode == 'edit' && isset($form_data['thai_write'])) ? htmlspecialchars($form_data['thai_write']) : '';
    $prefill_eng_listen = ($page_mode == 'edit' && isset($form_data['eng_listen'])) ? htmlspecialchars($form_data['eng_listen']) : '';
    $prefill_eng_speak = ($page_mode == 'edit' && isset($form_data['eng_speak'])) ? htmlspecialchars($form_data['eng_speak']) : '';
    $prefill_eng_read = ($page_mode == 'edit' && isset($form_data['eng_read'])) ? htmlspecialchars($form_data['eng_read']) : '';
    $prefill_eng_write = ($page_mode == 'edit' && isset($form_data['eng_write'])) ? htmlspecialchars($form_data['eng_write']) : '';

    // Other Skills
    $prefill_other_languages = ($page_mode == 'edit' && isset($form_data['other_languages'])) ? htmlspecialchars($form_data['other_languages']) : '';
    $prefill_computer_skill = ($page_mode == 'edit' && isset($form_data['computer_skill'])) ? htmlspecialchars($form_data['computer_skill']) : '';
    $prefill_talent_skill = ($page_mode == 'edit' && isset($form_data['talent_skill'])) ? htmlspecialchars($form_data['talent_skill']) : '';

    // Checkbox Data (converted to arrays)
    $prefill_news_array = ($page_mode == 'edit' && isset($form_data['news'])) ? explode(',', $form_data['news']) : [];
    $prefill_driver_array = ($page_mode == 'edit' && isset($form_data['driver'])) ? explode(',', $form_data['driver']) : [];
    $prefill_drive_license_array = ($page_mode == 'edit' && isset($form_data['drive_license'])) ? explode(',', $form_data['drive_license']) : [];

    // Known Person in Company
    $prefill_person_name = ($page_mode == 'edit' && isset($form_data['person_name'])) ? htmlspecialchars($form_data['person_name']) : '';
    $prefill_person_position = ($page_mode == 'edit' && isset($form_data['person_position'])) ? htmlspecialchars($form_data['person_position']) : '';
    $prefill_person_relations = ($page_mode == 'edit' && isset($form_data['person_relations'])) ? htmlspecialchars($form_data['person_relations']) : '';

    // Reference Person
    $prefill_person_referen_name = ($page_mode == 'edit' && isset($form_data['person_referen_name'])) ? htmlspecialchars($form_data['person_referen_name']) : '';
    $prefill_person_referen_position = ($page_mode == 'edit' && isset($form_data['person_referen_position'])) ? htmlspecialchars($form_data['person_referen_position']) : '';
    $prefill_person_referen_phone = ($page_mode == 'edit' && isset($form_data['person_referen_phone'])) ? htmlspecialchars($form_data['person_referen_phone']) : '';
    $prefill_person_referen_address = ($page_mode == 'edit' && isset($form_data['person_referen_address'])) ? htmlspecialchars($form_data['person_referen_address']) : '';
    $prefill_person_referen_relations = ($page_mode == 'edit' && isset($form_data['person_referen_relations'])) ? htmlspecialchars($form_data['person_referen_relations']) : '';

    // Radio Button Questions
    $prefill_imformation = ($page_mode == 'edit' && isset($form_data['imformation'])) ? htmlspecialchars($form_data['imformation']) : '';
    $prefill_penalize = ($page_mode == 'edit' && isset($form_data['penalize'])) ? htmlspecialchars($form_data['penalize']) : '';
    $prefill_dismiss = ($page_mode == 'edit' && isset($form_data['dismiss'])) ? htmlspecialchars($form_data['dismiss']) : '';
    $prefill_income_other = ($page_mode == 'edit' && isset($form_data['income_other'])) ? htmlspecialchars($form_data['income_other']) : '';
    $prefill_health = ($page_mode == 'edit' && isset($form_data['health'])) ? htmlspecialchars($form_data['health']) : '';
    $prefill_move_job = ($page_mode == 'edit' && isset($form_data['move_job'])) ? htmlspecialchars($form_data['move_job']) : '';
// --- END ---

// --- Prefill for Session 5: Additional Questions ---
    $prefill_more_infor1 = ($page_mode == 'edit' && isset($form_data['more_infor1'])) ? htmlspecialchars($form_data['more_infor1']) : '';
    $prefill_more_infor2 = ($page_mode == 'edit' && isset($form_data['more_infor2'])) ? htmlspecialchars($form_data['more_infor2']) : '';
    $prefill_more_infor3 = ($page_mode == 'edit' && isset($form_data['more_infor3'])) ? htmlspecialchars($form_data['more_infor3']) : '';
    $prefill_more_infor4 = ($page_mode == 'edit' && isset($form_data['more_infor4'])) ? htmlspecialchars($form_data['more_infor4']) : '';
    $prefill_more_infor5 = ($page_mode == 'edit' && isset($form_data['more_infor5'])) ? htmlspecialchars($form_data['more_infor5']) : '';
    $prefill_more_infor6 = ($page_mode == 'edit' && isset($form_data['more_infor6'])) ? htmlspecialchars($form_data['more_infor6']) : '';
// --- END ---


//ปิดประกาศตัวแปรเพื่อดึงข้อมูลมาใส่ใน form ตามที่ login

//รับค่า job_no มากจากปุ่มสมัครงาน   
if(isset($_GET['job_no'])){
    $job_no = $_GET['job_no'];
}else{
    $job_no = "";
}

// --- Image Path Logic ---
// $actual_image_to_display_src = ''; 
// $show_actual_image = false;       

// if ($page_mode == 'edit' && !empty($form_data['picture_upload']) && !empty($form_data['branch_job']) && !empty($load_guest_no)) {
//     $potential_image_path = 'File_Upload_' . htmlspecialchars($form_data['branch_job']) . '/' . htmlspecialchars($load_guest_no) .'/' . htmlspecialchars($form_data['picture_upload']);
//     $actual_image_to_display_src = $potential_image_path;
//     $show_actual_image = true;
// }
// $placeholder_image_css_path = "images/avatar.png"; // Path for CSS background


?>

    <style>
        /* The Modal Background */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1050; /* Sit on top, higher than most content */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0,0,0,0.7);
        }

        /* Modal Content */
        .modal-content {
            position: relative;
            background-color: #fefefe;
            margin: auto;
            padding: 0; /* Padding is removed as the image will fill it */
            border: 1px solid #888;
            width: 90%;
            max-width: 800px;
            border-radius: 0.3rem;
            animation-name: zoom;
            animation-duration: 0.3s;
        }

        @keyframes zoom {
            from {transform: scale(0.9)}
            to {transform: scale(1)}
        }
        
        /* The Image Itself */
        .modal-image {
            width: 100%;
            display: block;
        }

        /* The Close Button */
        .close-button {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #f1f1f1;
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            width: 35px;
            height: 35px;
            line-height: 35px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            z-index: 10; /* Ensures the button is on top of the image */
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .close-button:hover,
        .close-button:focus {
            background-color: rgba(255,0,0,0.7);
            color: white;
            text-decoration: none;
        }
    </style>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB ORNATE SDO <?php if ($page_mode == 'edit' && $load_guest_no) echo "- แก้ไข: " . htmlspecialchars($load_guest_no); ?></title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/resume_ornategroup.css" rel="stylesheet">
    <!-- <link href="assets/css/resume_ornategroup_extracted.css" rel="stylesheet"> -->
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="js/script.js"></script>
    
    <!-- Favicon -->
    <link rel="icon" href="images/LOGO_ORNATE1.png" type="image/png">
</head>

<body>
    <!-- include  -->
    <div class="m-0">
        <?php
        include 'header.php';     
       
        ?>
    </div>
    <!-- content -->
    <div class="container mt-5"> 
       <div class="pd"><h3><i class="fas fa-clipboard-list"></i> แบบฟอร์มใบสมัครงาน <?php if ($page_mode == 'edit' && $load_guest_no) echo "(กำลังแก้ไขใบสมัคร: " . htmlspecialchars($load_guest_no) . ")"; ?> </h3></div>
        
        <!-- Step Indicator -->
        <div class="step">
            <div class="step-item active" id="step1">
                <div class="step-circle">1</div>
                <div class="step-label">ข้อมูลส่วนตัว</div>
            </div>
            <div class="step-item" id="step2">
                <div class="step-circle">2</div>
                <div class="step-label">ประวัติการศึกษา</div>
            </div>
            <div class="step-item" id="step3">
                <div class="step-circle">3</div>
                <div class="step-label">ประวัติครอบครัว</div>
            </div>  
            <div class="step-item" id="step4">
                <div class="step-circle">4</div>
                <div class="step-label">ทักษะความสามารถ</div>
            </div>
            <div class="step-item" id="step5">
                <div class="step-circle">5</div>
                <div class="step-label">แบบสอบถามเพิ่มเติม</div>
            </div>
            <div class="step-item" id="step6">
                <div class="step-circle">6</div>
                <div class="step-label">แนบเอกสาร</div>
            </div>
        </div>
        <!-- Form -->
        <form  id="applicationForm" action="submit.php" method="post" class="needs-validation" novalidate  enctype="multipart/form-data">
            
            <?php if ($page_mode == 'edit' && $load_guest_no): ?>
                <input type="hidden" name="existing_guest_no" value="<?php echo htmlspecialchars($load_guest_no); ?>">
                <input type="hidden" name="fk_user_id_on_edit" value="<?php echo htmlspecialchars($loggedInUserId); ?>">
            <?php endif; ?>


            <!-- Session 1: ประวัติส่วนตัว -->
            <div class="form-session " id="session1">
                <div class="section-header"><i class="fa-solid fa-user-pen fa-lg"></i> ประวัติส่วนตัว</div>
                    <div class="section-body">
                        <div class="row">
                            <!--upload รูปถ่าย-->
                            <div class="col-md-2 upload-photo-section" align="center">
                                <div class="col-md-2 imgUp d-flex flex-column align-items-center">
                                    <div class="imagePreview mb-0" 
                                        style="background-image: url('images/avatar.png');"> 
                                        <img id="previewImg" 
                                            src="#" 
                                            alt="รูปถ่ายผู้สมัคร" 
                                            style="width:100%; height:100%; object-fit: cover; display: none;"> 
                                    </div>
                                    <label class="btn btn-primary-pic btn-primary mt-2"> 
                                        <i class="fa-solid fa-cloud-arrow-up"></i> อัพโหลดรูปถ่าย
                                        <input id="img_upload" name="img_upload" type="file" class="uploadFile img" onchange="previewImage(event);" accept="image/png, image/jpg, image/jpeg" required />  
                                    </label>
                                    <small class="mt-1" id="currentFileNameInfo" style="display:none;"></small>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <label class="form-label mt-2"><b><u>โปรดกรอกข้อมูลช่องที่มีเครื่องหมาย <span style="color: red;">*</span> ให้ครบทุกช่อง</u></b></label>
                                <div class="row mb-1 mt-1"> <!-- JOB SELECTION ROW -->
                                    <div class="col-md-4 mb-2">
                                        <label for="sub_branch" class="form-label"><b>เลือกบริษัท</b> <span style="color: red;">*</span></label>
                                        <select class="form-select font_option" id="sub_branch" name="sub_branch" required>
                                            <option value="" disabled <?php if(empty($prefill_sub_branch)) echo 'selected';?>>--เลือก--</option>
                                            <?php
                                            // Logic to populate all available sub_branches from jobs_require
                                            // This ensures all options are present for selection even when editing.
                                            $strSQL_all_sub_branches = "SELECT branch_name, rtrim(sub_branch) as sub_branch_val FROM jobs_require WHERE job_status ='Y' GROUP BY sub_branch, branch_name ORDER BY branch_name DESC";
                                            $query_all_sub_branches = sqlsrv_query($conn, $strSQL_all_sub_branches, array(), array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                                            if($query_all_sub_branches) {
                                                while($row_sb = sqlsrv_fetch_array($query_all_sub_branches, SQLSRV_FETCH_ASSOC)){ ?>
                                                    <option value="<?php echo htmlspecialchars($row_sb['sub_branch_val']); ?>" 
                                                            <?php if($prefill_sub_branch == $row_sb['sub_branch_val']) echo 'selected'; ?>>
                                                        <?php echo htmlspecialchars($row_sb['branch_name']); ?>
                                                    </option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback">กรุณาเลือก บริษัทที่ต้องการสมัคร</div>
                                    </div> 
                                    <div class="col-md-4 mb-2">
                                        <label for="branch" class="form-label"><b>สาขาที่ต้องการสมัคร</b> <span style="color: red;">*</span></label>
                                        <select class="form-select font_option" id="branch" name="branch" required>
                                            <option value="" selected disabled>--เลือก--</option>
                                            <?php
                                            if ($page_mode == 'edit' && !empty($prefill_sub_branch)) {
                                                $sql_branches = "SELECT DISTINCT rtrim(branch) as branch_val FROM jobs_require WHERE sub_branch = ? AND job_status = 'Y'";
                                                $params_branches = array($prefill_sub_branch);
                                                $query_branches = sqlsrv_query($conn, $sql_branches, $params_branches, array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                                                if ($query_branches) {
                                                    while ($row_b = sqlsrv_fetch_array($query_branches, SQLSRV_FETCH_ASSOC)) {
                                                        $branch_display_text = "";
                                                        if($row_b['branch_val']=="SRT"){ $branch_display_text = "สาขาสุราษฎร์ธานี (สำนักงานใหญ่)";}
                                                        else if($row_b['branch_val']=="HDY"){ $branch_display_text = "สาขาหาดใหญ่";}
                                                        else if($row_b['branch_val']=="CHM"){ $branch_display_text = "สาขาเชียงใหม่";}
                                                        else { $branch_display_text = htmlspecialchars($row_b['branch_val']);}
                                                        echo '<option value="' . htmlspecialchars($row_b['branch_val']) . '" ' . ($prefill_branch_job == $row_b['branch_val'] ? 'selected' : '') . '>' . $branch_display_text . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">กรุณาเลือก สาขาที่ต้องการสมัคร</div>
                                    </div>
                                    <div class="col-md-4 mb-2">
                                        <label for="job_name" class="form-label"><b>ตำแหน่งงานที่ต้องการสมัคร</b> <span style="color: red;">*</span></label>
                                        <select class="form-select font_option" id="job_name" name="job_name" required> 
                                            <option value="" selected disabled>--เลือก--</option>
                                             <?php
                                            if ($page_mode == 'edit' && !empty($prefill_sub_branch) && !empty($prefill_branch_job)) {
                                                $sql_jobs = "SELECT job_no, position FROM jobs_require WHERE sub_branch = ? AND branch = ? AND job_status = 'Y'";
                                                $params_jobs = array($prefill_sub_branch, $prefill_branch_job);
                                                $query_jobs = sqlsrv_query($conn, $sql_jobs, $params_jobs, array("Scrollable" => SQLSRV_CURSOR_KEYSET));
                                                if ($query_jobs) {
                                                    while ($row_j = sqlsrv_fetch_array($query_jobs, SQLSRV_FETCH_ASSOC)) {
                                                        echo '<option value="' . htmlspecialchars(trim($row_j['job_no'])) . '" ' . ($prefill_job_no_db == trim($row_j['job_no']) ? 'selected' : '') . '>' . htmlspecialchars($row_j['position']) . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <div class="invalid-feedback">กรุณาเลือก ตำแหน่งงานที่ต้องการสมัคร</div>
                                    </div>
                                </div>
                                <div class="row"> <!-- Salary and Work Date -->
                                    <div class="col-md-6 mb-2" >
                                        <label for="salary" class="form-label"><b>เงินเดือนที่ต้องการ</b> <span style="color: red;">*</span></label>
                                        <input type="text" class="form-control" id="salary" name="salary" value="<?php echo $prefill_salary; ?>" onChange="numberWithCommas();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" placeholder="กรอกเงินเดือนที่ต้องการ" required>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label for="work_date" class="form-label"><b>วันที่พร้อมเริ่มงาน</b> <span style="color: red;">*</span></label>
                                        <input type="date" class="form-control" id="work_date" name="work_date" value="<?php echo $prefill_work_date; ?>" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
						<hr class="style-eight">

                        <div class="row mb-1">
                            <!-- คำนำหน้า -->
                            <div class="col-md-3 margin_bt">
                                <label for="title" class="form-label"><b>คำนำหน้า</b> <span style="color: red;">*</span></label>
                                <select class="form-select font_option" id="title" name="title" required onChange="auto_sex()">
                                    <option value="" disabled <?php if(empty($prefill_title)) echo 'selected'; ?>>เลือกคำนำหน้า</option>
                                    <option value="นาย" <?php if($prefill_title == 'นาย') echo 'selected'; ?>>นาย</option>
                                    <option value="นาง" <?php if($prefill_title == 'นาง') echo 'selected'; ?>>นาง</option>
                                    <option value="นางสาว" <?php if($prefill_title == 'นางสาว') echo 'selected'; ?>>นางสาว</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกคำนำหน้า</div>
                            </div>

                            <!-- ชื่อ-นามสกุล -->
                            <div class="col-md-6 margin_bt">
                                <label for="name" class="form-label"><b>ชื่อ-นามสกุล</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $prefill_name; ?>" required>
                                <div class="invalid-feedback">กรุณากรอกชื่อและนามสกุล</div>
                            </div>

                            <!-- ชื่อเล่น -->
                            <div class="col-md-3 margin_bt">
                                <label for="nickname" class="form-label"><b>ชื่อเล่น</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="nickname" name="nickname" value="<?php echo $prefill_nickname; ?>" required>
                            </div>
                        </div>

                        <div class="row mb-1">
                            <!-- เพศ -->
                            <div class="col-md-3 margin_bt">
                                <label for="sex" class="form-label"><b>เพศ</b> <span style="color: red;">*</span></label>
                                <select class="form-select font_option" id="sex" name="sex" required onChange="auto_sexs()">
                                    <option selected disabled <?php if(empty($prefill_sex)) echo 'selected'; ?> value="">เลือกเพศ</option>
                                    <option value="ชาย"<?php if($prefill_sex == 'ชาย') echo 'selected'; ?>>ชาย</option>
                                    <option value="หญิง"<?php if($prefill_sex == 'หญิง') echo 'selected'; ?>>หญิง</option>
                                    <option value="เพศทางเลือก"<?php if($prefill_sex == 'เพศทางเลือก') echo 'selected'; ?>>เพศทางเลือก</option>
                                    <option value="ไม่ระบุ"<?php if($prefill_sex == 'ไม่ระบุ') echo 'selected'; ?>>ไม่ระบุ</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกเพศ</div>
                            </div>

                            <!-- วันเดือนปีเกิด -->
                            <div class="col-md-3 margin_bt">
                                <label for="birthday" class="form-label"><b>วันเดือนปีเกิด</b> <span style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="birthday" name="birthday" value="<?php echo $prefill_birthday; ?>" required onChange="calAge(this)">
                                <div class="invalid-feedback">กรุณาเลือกวันเดือนปีเกิด</div>
                            </div>

                            <!-- อายุ -->
                            <div class="col-md-2 margin_bt">
                                <label for="age" class="form-label"><b>อายุ</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="age" name="age" value="<?php echo $prefill_age; ?>" required>
                                <div class="invalid-feedback">กรุณากรอกอายุ</div>
                            </div>
                            <!-- สถานะภาพสมรส -->
                            <div class="col-md-4 margin_bt">
                                <label for="status" class="form-label"><b>สถานภาพ</b> <span style="color: red;">*</span></label>
                                <select class="form-select font_option" id="status" name="status" required onChange="status_div()">
                                    <option selected disabled <?php if(empty($prefill_status)) echo 'selected'; ?> value="">เลือกสถานภาพ</option>
                                    <option value="โสด"  <?php if($prefill_status == 'โสด') echo 'selected'; ?> >โสด</option>
                                    <option value="สมรส"  <?php if($prefill_status == 'สมรส') echo 'selected'; ?> >สมรส</option>
                                    <option value="สมรสไม่จดทะเบียน"  <?php if($prefill_status == 'สมรสไม่จดทะเบียน') echo 'selected'; ?> >สมรสไม่จดทะเบียน</option>
                                    <option value="หย่าร้าง" <?php if($prefill_status == 'หย่าร้าง') echo 'selected'; ?>>หย่าร้าง</option>
                                    <option value="หม้าย"<?php if($prefill_status == 'หม้าย') echo 'selected'; ?> >หม้าย</option>
                                    <option value="แยกกันอยู่"<?php if($prefill_status == 'แยกกันอยู่') echo 'selected'; ?>>แยกกันอยู่</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือกสถานะภาพ</div>
                            </div>

                        </div>

                        <div class="row mb-1">
                            <!-- เลขบัตรประชาชน -->
                            <div class="col-md-4 margin_bt">
                                <label for="idCard" class="form-label"><b>เลขบัตรประชาชน</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="idCard" name="idCard" value="<?php echo $prefill_idcard; ?>" onkeyup="autoTab(this,1)" maxlength="17" placeholder="กรอกเลขบัตรประชาชน 13 หลัก" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback">กรุณากรอกเลขบัตรประชาชน 13 หลัก</div>
                            </div>
                            <!-- วันที่ออกบัตรประชาชน -->
                            <div class="col-md-4 margin_bt">
                                <label for="create_date" class="form-label"><b>วันที่ออกบัตรประชาชน</b> <span style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="create_date" name="create_date" value="<?php echo $prefill_create_date; ?>" required>
                                <div class="invalid-feedback">กรุณาเลือกวันที่ออกบัตรประชาชน</div>
                            </div>
                            <!-- วันที่หมดอายุบัตรประชาชน -->
                            <div class="col-md-4 margin_bt">
                                <label for="expiry_date" class="form-label"><b>วันที่หมดอายุบัตรประชาชน</b> <span style="color: red;">*</span></label>
                                <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="<?php echo $prefill_expiry_date; ?>" required>
                                <div class="invalid-feedback">วันที่หมดอายุบัตรประชาชน</div>
                            </div>
                            
                        </div>

                        <div class="row mb-1">
                            <!-- สัญชาติ -->
                            <div class="col-md-3 margin_bt">
                                <label for="nationality" class="form-label"><b>สัญชาติ</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="nationality" name="nationality" placeholder="กรอกสัญชาติ" value="<?php echo $prefill_nationality; ?>" required>
                                <div class="invalid-feedback">กรุณากรอก สัญชาติ</div>
                            </div>
                            <!-- ศาสนา -->
                            <div class="col-md-3 margin_bt">
                                <label for="religion" class="form-label"><b>ศาสนา</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="religion" name="religion" placeholder="กรอกศาสนา" value="<?php echo $prefill_religion; ?>" required>
                                <div class="invalid-feedback">กรุณากรอก ศาสนา</div>
                            </div>
                            <!-- ส่วนสูง -->
                            <div class="col-md-3 margin_bt">
                                <label for="height" class="form-label"><b>ส่วนสูง</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="height" name="height" value="<?php echo $prefill_height; ?>" placeholder="กรอกส่วนสูง (ซม.)" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback">กรุณากรอก ส่วนสูง (ซม.)</div>
                            </div>                            
                            <!-- น้ำหนัก -->
                            <div class="col-md-3 margin_bt">
                                <label for="weight" class="form-label"><b>น้ำหนัก</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="weight" name="weight" value="<?php echo $prefill_weight; ?>" placeholder="กรอกน้ำหนัก (กก.)" maxlength="3" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required>
                                <div class="invalid-feedback">กรุณากรอก น้ำหนัก (กก.)</div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <!-- โทรศัพท์มือถือ -->
                            <div class="col-md-4 margin_bt">
                                <label for="phone" class="form-label"><b>โทรศัพท์มือถือ</b> <span style="color: red;">*</span></label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $prefill_phone_display; ?>" onkeyup="autoTab(this,3)" placeholder="0XX-XXX-XXXX" oninput="this.value = this.value.replace(/[^0-9-]/g, '');" maxlength="12" required/>
                                <div class="invalid-feedback">กรุณากรอก โทรศัพท์มือถือ</div>
                            </div>
                            <!-- อีเมล -->
                            <div class="col-md-4 margin_bt">
                                <label for="email" class="form-label"><b>อีเมล</b></label>
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $prefill_email; ?>"placeholder="กรอกอีเมล (ถ้ามี)"/>
                                <div class="invalid-feedback"></div>
                            </div>
                            <!-- Line ID -->
                            <div class="col-md-4 margin_bt">
                                <label for="line_id" class="form-label"><b>LINE ID</b></label>
                                <input type="text" class="form-control" id="line_id" name="line_id" value="<?php echo $prefill_line_id; ?>" placeholder="กรอก LINE ID (ถ้ามี)"/>
                                <div class="invalid-feedback"></div>
                            </div>                        
                        </div>
                        <!-- ที่อยู่ปัจจุบัน  -->
                        <div class="row mb-1">
                            <div class="col-md-12 margin_bt">
                                <label for="" class="form-label"><b>ที่อยู่ปัจจุบัน</b> <span style="color: red;">*</span></label> <small class="form-text text-secondary">(บ้านเลขที่, หมู่ที่, ถนน, ซอย , ตำบล , อำเภอ , จังหวัด ,ไปรษณีย์)</small>
                                <input type="text" class="form-control" id="address" name="address" value="<?php echo $prefill_address; ?>" placeholder="กรอกที่อยู่ เช่น เลขที่-หมู่ที่-ถนน-ซอย" required/>
                                <div class="invalid-feedback">กรุณากรอก ที่อยู่</div>
                            </div>
                        </div>

                        <div id="military" class="col-md-12 margin_bt">
                            <label for="military" class="form-label"><b>สถานภาพทางทหาร</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-2">
                                    <input class="form-check-input" type="radio" name="radiomilitary" id="not_military" value="ยังไม่เกณฑ์ทหาร" <?php if($prefill_military == 'ยังไม่เกณฑ์ทหาร') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="not_military">ยังไม่เกณฑ์ทหาร</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="radio" name="radiomilitary" id="discharged" value="เกณฑ์ทหารแล้ว" <?php if($prefill_military == 'เกณฑ์ทหารแล้ว') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="discharged">เกณฑ์ทหารแล้ว</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="radio" name="radiomilitary" id="studied_military" value="จบ นศท. ชั้นปีที่ 3" <?php if($prefill_military == 'จบ นศท. ชั้นปีที่ 3') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="studied_military">จบ นศท. ชั้นปีที่ 3</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="radio" name="radiomilitary" id="exempted" value="ได้รับการยกเว้น" <?php if($prefill_military == 'ได้รับการยกเว้น') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="exempted">ได้รับการยกเว้น</label>
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                        </div>
						<div class="col-md-12 margin_bt">
							<label for="congenital_disease" class="form-label"><b>ท่านมีโรคประจำตัวหรือไม่</b> <span style="color: red;">*</span></label>
							<div class="row">
								<div class="col-md-1">
								  <input class="form-check-input" type="radio" name="radiocongenital_disease" id="no_disease" value="ไม่มี" <?php if ($prefill_radiocongenital_disease == 'ไม่มี') echo 'checked'; ?> required>
								  <label class="form-check-label" for="no_disease">ไม่มี</label>
								</div>
								<div class="col-md-11" >
								    <div class="row">
									    <div class="col-md-2">
									        <input class="form-check-input" type="radio" name="radiocongenital_disease" id="yes_disease" value="<?php echo ($prefill_radiocongenital_disease !== 'ไม่มี' && $prefill_radiocongenital_disease !== '') ? htmlspecialchars($prefill_radiocongenital_disease) : 'มี'; ?>" <?php if ($prefill_radiocongenital_disease !== 'ไม่มี' && $prefill_radiocongenital_disease !== '') echo 'checked'; ?> required>
									        <label class="form-check-label" for="yes_disease">มี (โปรดระบุ)</label>
									    </div>
									    <div class="col-md-10" style="margin-top: -3px;">
									        <input type="text" class="form-control" id="disease_input" name="disease_input" value="<?php echo ($prefill_radiocongenital_disease !== 'ไม่มี' && $prefill_radiocongenital_disease !== '') ? htmlspecialchars($prefill_radiocongenital_disease) : ''; ?>" placeholder="กรอกโรคประจำตัว" <?php if ($prefill_radiocongenital_disease == 'ไม่มี' || $prefill_radiocongenital_disease == '') echo 'disabled'; ?>  >
                                            <div class="invalid-feedback">กรุณากรอก โรคประจำตัว</div>
									    </div>
								    </div>
								</div>
							</div>
						</div>
                        <div class="col-md-12 margin_bt">
                            <label for="disabled" class="form-label"><b>ท่านมีอวัยวะส่วนไหนที่พิการบ้าง</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                  <input class="form-check-input" type="radio" name="radiodisabled" id="normal" value="ปกติ" <?php if($prefill_radiodisabled == 'ปกติ') echo 'checked'; ?> required>
                                  <label class="form-check-label" for="normal">ปกติ</label>
                                </div>
                                <div class="col-md-11" >
                                  <div class="row">
                                    <div class="col-md-2">
                                      <input class="form-check-input" type="radio" name="radiodisabled" id="disabled" value="<?php echo ($prefill_radiodisabled !== 'ปกติ' && $prefill_radiodisabled !== '') ? htmlspecialchars($prefill_radiodisabled) : 'พิการ'; ?>" <?php if ($prefill_radiodisabled !== 'ปกติ' && $prefill_radiodisabled !== '') echo 'checked'; ?>required>
                                      <label class="form-check-label" for="disabled">พิการ (โปรดระบุ)</label>
                                    </div>
                                    <div class="col-md-10" style="margin-top: -3px;">
                                      <input type="text" class="form-control" id="disabled_input" name="disabled_input" value="<?php echo ($prefill_radiodisabled !== 'ปกติ' && $prefill_radiodisabled !== '') ? htmlspecialchars($prefill_radiodisabled) : ''; ?>" placeholder="กรอกอวัยวะที่พิการ" <?php if ($prefill_radiodisabled == 'ปกติ' || $prefill_radiodisabled == '') echo 'disabled'; ?>  >
                                      <div class="invalid-feedback">กรุณากรอก อวัยวะที่พิการ</div>
                                    </div>
                                  </div>
                               </div>
                            </div>
                        </div>
                    </div>
                    <br>    
                <div class="text-end">
                    <input type="hidden" name="currentSession" id="currentSession" value="session1">
                    <button type="button" class="btn btn-primary" id="nextToSession2">ถัดไป <i class="fa-solid fa-angles-right fa-lg"></i></button>
                </div>
            </div><!--ปิด Session 1-->

             <!-- Session 2: ประวัติการศึกษาและการทำงาน -->
            <div class="form-session d-none" id="session2">
                <div class="section-header"><i class="fa-solid fa-user-graduate fa-lg"></i> ประวัติการศึกษา</div>
                <div class="section-body">
                    <h5><b>การศึกษาสูงสุด</b></h5>
                    <div class="row mb-1">
                        <div class="col-md-6 margin_bt">
                            <label for="u_school" class="form-label"><b>ชื่อสถาบัน</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="u_school" name="u_school" placeholder="กรอกชื่อสถาบัน" value="<?php echo $prefill_u_school; ?>"  required>
                            <div class="invalid-feedback">กรุณากรอก ชื่อสถาบัน</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="u_year" class="form-label"><b>ปีการศึกษาที่จบ</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="u_year" name="u_year" placeholder="กรอกปีการศึกษาที่จบ" value="<?php echo $prefill_u_year; ?>"  required>
                            <div class="invalid-feedback">กรุณากรอก ปีการศึกษาที่จบ</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="u_gpa" class="form-label"><b>เกรดเฉลี่ย</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="u_gpa" name="u_gpa" placeholder="กรอกเกรดเฉลี่ย" value="<?php echo $prefill_u_gpa; ?>"  required>
                            <div class="invalid-feedback">กรุณากรอก เกรดเฉลี่ย</div>
                        </div>
                    </div>
                    <div class="row mb-1">                                            
                        <div class="col-md-6 margin_bt">
                            <label for="u_educational" class="form-label"><b>วุฒิการศึกษาที่ได้รับ</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="u_educational" name="u_educational" required>
                                <option selected disabled value="">กรุณาเลือกวุฒิการศึกษาที่ได้รับ</option>
                                <option value="ปริญญาโท"<?php if($prefill_u_educational == 'ปริญญาโท') echo 'selected'; ?>>ปริญญาโท</option>
                                <option value="ปริญญาตรี"<?php if($prefill_u_educational == 'ปริญญาตรี') echo 'selected'; ?>>ปริญญาตรี</option>
                                <option value="ปวส."<?php if($prefill_u_educational == 'ปวส.') echo 'selected'; ?>>ปวส.</option>
                                <option value="ปวช."<?php if($prefill_u_educational == 'ปวช.') echo 'selected'; ?>>ปวช.</option>
                                <option value="มัธยมศึกษาตอนปลาย"<?php if($prefill_u_educational == 'มัธยมศึกษาตอนปลาย') echo 'selected'; ?>>มัธยมศึกษาตอนปลาย</option>
                                <option value="มัธยมศึกษาตอนต้น"<?php if($prefill_u_educational == 'มัธยมศึกษาตอนต้น') echo 'selected'; ?>>มัธยมศึกษาตอนต้น</option>
                                <option value="ประถมศึกษา"<?php if($prefill_u_educational == 'ประถมศึกษา') echo 'selected'; ?>>ประถมศึกษา</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือก วุฒิการศึกษาที่ได้รับ</div>
                        </div>

                        <div class="col-md-6 margin_bt">
                            <label for="u_major" class="form-label"><b>สาขา/วิชาเอก</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="u_major" name="u_major" placeholder="กรอกสาขา/วิชาเอก" value="<?php echo $prefill_u_major; ?>" required>
                            <div class="invalid-feedback">กรุณากรอก สาขา/วิชาเอก</div>
                        </div>
                    </div>
                    <!-- Button to show the second topic (การศึกษาก่อนหน้า) -->
                    <button type="button" id="addPrevEducationBtn" class="btn btn-primary btn-sm font_bt mt-1"><i class="fa-solid fa-plus"></i> เพิ่มข้อมูลการศึกษาก่อนหน้า</button>
                    <div id="prevEducationSection" class="hidden mt-1">
                        <div><hr class="style-one"></div>
                        <h5><b>การศึกษาก่อนหน้า</b></h5>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="v_school" class="form-label"><b>ชื่อสถาบัน</b></label>
                                <input type="text" class="form-control" id="v_school" name="v_school" value="<?php echo $prefill_v_school; ?>" placeholder="กรอกชื่อสถาบัน">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 margin_bt">
                                <label for="v_year" class="form-label"><b>ปีการศึกษาที่จบ</b></label>
                                <input type="text" class="form-control" id="v_year" name="v_year" value="<?php echo $prefill_v_year; ?>" placeholder="กรอกปีการศึกษาที่จบ">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 margin_bt">
                                <label for="v_gpa" class="form-label"><b>เกรดเฉลี่ย</b></label>
                                <input type="text" class="form-control" id="v_gpa" name="v_gpa" value="<?php echo $prefill_v_gpa; ?>" placeholder="กรอกเกรดเฉลี่ย">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="v_educational" class="form-label"><b>วุฒิการศึกษาที่ได้รับ</b></label>
                                <select class="form-select font_option" id="v_educational" name="v_educational" >
                                    <option selected disabled value="">กรุณาเลือกวุฒิการศึกษาที่ได้รับ</option>
                                    <option value="ปริญญาโท"<?php if($prefill_u_educational == 'ปริญญาโท') echo 'selected'; ?>>ปริญญาโท</option>
                                    <option value="ปริญญาตรี"<?php if($prefill_u_educational == 'ปริญญาตรี') echo 'selected'; ?>>ปริญญาตรี</option>
                                    <option value="ปวส."<?php if($prefill_u_educational == 'ปวส.') echo 'selected'; ?>>ปวส.</option>
                                    <option value="ปวช."<?php if($prefill_u_educational == 'ปวช.') echo 'selected'; ?>>ปวช.</option>
                                    <option value="มัธยมศึกษาตอนปลาย"<?php if($prefill_u_educational == 'มัธยมศึกษาตอนปลาย') echo 'selected'; ?>>มัธยมศึกษาตอนปลาย</option>
                                    <option value="มัธยมศึกษาตอนต้น"<?php if($prefill_u_educational == 'มัธยมศึกษาตอนต้น') echo 'selected'; ?>>มัธยมศึกษาตอนต้น</option>
                                    <option value="ประถมศึกษา"<?php if($prefill_u_educational == 'ประถมศึกษา') echo 'selected'; ?>>ประถมศึกษา</option>
                                </select>
                                <div class="invalid-feedback">กรุณาเลือก วุฒิการศึกษาที่ได้รับ</div>
                            </div>

                            <div class="col-md-6 margin_bt">
                                <label for="v_major" class="form-label"><b>สาขา/วิชาเอก</b></label>
                                <input type="text" class="form-control" id="v_major" name="v_major" value="<?php echo $prefill_v_major; ?>" placeholder="กรอกสาขา/วิชาเอก">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div> <!-- Missing closing div added here -->
                </div>
                <br>
                <!--ประวัติการทำงาน-->
                <div class="section-header"><i class="fa-solid fa-user-tie fa-lg"></i> ประวัติการทำงาน</div>
                <div class="section-body">
                    <h5><b>การทำงานล่าสุด</b></h5>
                    <div id="work_1"><!--div สถานที่ทำงานล่าสุด-->
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="company_1" class="form-label"><b>ชื่อสถานที่ทำงาน</b></label>
                                <input type="text" class="form-control" id="company_1" name="company_1" value="<?php echo $prefill_company_1; ?>" placeholder="กรอกชื่อสถานที่ทำงาน">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="position_1" class="form-label"><b>ตำแหน่ง</b></label>
                                <input type="text" class="form-control" id="position_1" name="position_1" value="<?php echo $prefill_position_1; ?>" placeholder="กรอกตำแหน่ง">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 margin_bt">
                                <label for="datestart_1" class="form-label"><b>วันที่เริ่มงาน</b></label>
                                <input type="date" class="form-control" id="datestart_1" name="datestart_1" value="<?php echo $prefill_datestart_1; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="dateend_1" class="form-label"><b>วันที่สิ้นสุดงาน</b></label>
                                <input type="date" class="form-control" id="dateend_1" name="dateend_1" value="<?php echo $prefill_dateend_1; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="salary_1" class="form-label"><b>เงินเดือนที่ได้รับ</b></label>
                                <input type="text" class="form-control" id="salary_1" name="salary_1" value="<?php echo $prefill_salary_1; ?>" placeholder="กรอกเงินเดือนที่ได้รับ" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="detail_work_1" class="form-label"><b>ลักษณะงานโดยย่อ</b></label>
                                <input type="text" class="form-control" id="detail_work_1" name="detail_work_1" value="<?php echo $prefill_detail_work_1; ?>" placeholder="กรอกลักษณะงานโดยย่อ" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="remark_leave_1" class="form-label"><b>สาเหตุที่ลาออก</b></label>
                                <input type="text" class="form-control" id="remark_leave_1" name="remark_leave_1" value="<?php echo $prefill_remark_leave_1; ?>" placeholder="กรอกสาเหตุที่ลาออก" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div> 
                    <!-- Hidden additional work forms -->
                    <div class="row mb-1 hidden" id="work_2">
                        <div><hr class="style-one"></div>
                        <h5><b>การทำงานที่ 2</b></h5>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="company_2" class="form-label"><b>ชื่อสถานที่ทำงาน</b></label>
                                <input type="text" class="form-control" id="company_2" name="company_2" value="<?php echo $prefill_company_2; ?>" placeholder="กรอกชื่อสถานที่ทำงาน">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="position_2" class="form-label"><b>ตำแหน่ง</b></label>
                                <input type="text" class="form-control" id="position_2" name="position_2" value="<?php echo $prefill_position_2; ?>" placeholder="กรอกตำแหน่ง">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 margin_bt">
                                <label for="datestart_2" class="form-label"><b>วันที่เริ่มงาน</b></label>
                                <input type="date" class="form-control" id="datestart_2" name="datestart_2" value="<?php echo $prefill_datestart_2; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="dateend_2" class="form-label"><b>วันที่สิ้นสุดงาน</b></label>
                                <input type="date" class="form-control" id="dateend_2" name="dateend_2" value="<?php echo $prefill_dateend_2; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="salary_2" class="form-label"><b>เงินเดือนที่ได้รับ</b></label>
                                <input type="text" class="form-control" id="salary_2" name="salary_2" value="<?php echo $prefill_salary_2; ?>" placeholder="กรอกเงินเดือนที่ได้รับ">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="detail_work_2" class="form-label"><b>ลักษณะงานโดยย่อ</b></label>
                                <input type="text" class="form-control" id="detail_work_2" name="detail_work_2" value="<?php echo $prefill_detail_work_2; ?>" placeholder="กรอกลักษณะงานโดยย่อ" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="remark_leave_2" class="form-label"><b>สาเหตุที่ลาออก</b></label>
                                <input type="text" class="form-control" id="remark_leave_2" name="remark_leave_2" value="<?php echo $prefill_remark_leave_2; ?>" placeholder="กรอกสาเหตุที่ลาออก" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1 hidden" id="work_3">
                        <div><hr class="style-one"></div>
                        <h5><b>การทำงานที่ 3</b></h5>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="company_3" class="form-label"><b>ชื่อสถานที่ทำงาน</b></label>
                                <input type="text" class="form-control" id="company_3" name="company_3" value="<?php echo $prefill_company_3; ?>" placeholder="กรอกชื่อสถานที่ทำงาน">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="position_3" class="form-label"><b>ตำแหน่ง</b></label>
                                <input type="text" class="form-control" id="position_3" name="position_3" value="<?php echo $prefill_position_3; ?>" placeholder="กรอกตำแหน่ง">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 margin_bt">
                                <label for="datestart_3" class="form-label"><b>วันที่เริ่มงาน</b></label>
                                <input type="date" class="form-control" id="datestart_3" name="datestart_3" value="<?php echo $prefill_datestart_3; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="dateend_3" class="form-label"><b>วันที่สิ้นสุดงาน</b></label>
                                <input type="date" class="form-control" id="dateend_3" name="dateend_3" value="<?php echo $prefill_dateend_3; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="salary_3" class="form-label"><b>เงินเดือนที่ได้รับ</b></label>
                                <input type="text" class="form-control" id="salary_3" name="salary_3" value="<?php echo $prefill_salary_3; ?>" placeholder="กรอกเงินเดือนที่ได้รับ">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="detail_work_3" class="form-label"><b>ลักษณะงานโดยย่อ</b></label>
                                <input type="text" class="form-control" id="detail_work_3" name="detail_work_3" value="<?php echo $prefill_detail_work_3; ?>" placeholder="กรอกลักษณะงานโดยย่อ" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="remark_leave_3" class="form-label"><b>สาเหตุที่ลาออก</b></label>
                                <input type="text" class="form-control" id="remark_leave_3" name="remark_leave_3" value="<?php echo $prefill_remark_leave_3; ?>" placeholder="กรอกสาเหตุที่ลาออก" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>   
                    <div class="row mb-1 hidden" id="work_4">
                        <div><hr class="style-one"></div>
                        <h5><b>การทำงานที่ 4</b></h5>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="company_4" class="form-label"><b>ชื่อสถานที่ทำงาน</b></label>
                                <input type="text" class="form-control" id="company_4" name="company_4" value="<?php echo $prefill_company_4; ?>" placeholder="กรอกชื่อสถานที่ทำงาน">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="position_4" class="form-label"><b>ตำแหน่ง</b></label>
                                <input type="text" class="form-control" id="position_4" name="position_4" value="<?php echo $prefill_position_4; ?>" placeholder="กรอกตำแหน่ง">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 margin_bt">
                                <label for="datestart_4" class="form-label"><b>วันที่เริ่มงาน</b></label>
                                <input type="date" class="form-control" id="datestart_4" name="datestart_4" value="<?php echo $prefill_datestart_4; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="dateend_4" class="form-label"><b>วันที่สิ้นสุดงาน</b></label>
                                <input type="date" class="form-control" id="dateend_4" name="dateend_4" value="<?php echo $prefill_dateend_4; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="salary_4" class="form-label"><b>เงินเดือนที่ได้รับ</b></label>
                                <input type="text" class="form-control" id="salary_4" name="salary_4" value="<?php echo $prefill_salary_4; ?>" placeholder="กรอกเงินเดือนที่ได้รับ">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="detail_work_4" class="form-label"><b>ลักษณะงานโดยย่อ</b></label>
                                <input type="text" class="form-control" id="detail_work_4" name="detail_work_4" value="<?php echo $prefill_detail_work_4; ?>" placeholder="กรอกลักษณะงานโดยย่อ" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 margin_bt">
                                <label for="remark_leave_4" class="form-label"><b>สาเหตุที่ลาออก</b></label>
                                <input type="text" class="form-control" id="remark_leave_4" name="remark_leave_4" value="<?php echo $prefill_remark_leave_4; ?>" placeholder="กรอกสาเหตุที่ลาออก" maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>   
                    <!-- Button to add another work experience -->
                    <button type="button" class="btn btn-primary btn-sm font_bt mt-1" id="addMoreWorkBtn"><i class="fa-solid fa-plus"></i> เพิ่มการทำงาน</button>
                </div>
                <br>
                <!-- Add more fields as needed -->
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="prevToSession1"><i class="fa-solid fa-angles-left fa-lg"></i> ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="nextToSession3">ถัดไป <i class="fa-solid fa-angles-right fa-lg"></i></button>
                </div>
            </div><!--ปิด Session 2-->

            <!-- Session 3: ประวัติครอบครัว -->
            <div class="form-session d-none" id="session3">
                <div class="section-header"><i class="fa-solid fa-users fa-lg"></i> ข้อมูลครอบครัว</div>
                <div class="section-body">
                    <!--ข้อมูล บิดา-->
                    <div class="row mb-1">
                        <div class="col-md-5 margin_bt">
                            <label for="father_name" class="form-label"><b>ชื่อ-นามสกุล บิดา</b> <span style="color: red;">*</span> </label>
                            <input type="text" class="form-control" id="father_name" name="father_name" placeholder="กรอกชื่อ-นามสกุล บิดา" value="<?php echo $prefill_father_name; ?>"  required>
                            <div class="invalid-feedback">กรุณากรอก ชื่อ-นามสกุล บิดา</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="father_age" class="form-label"><b>อายุ</b></label>
                            <input type="text" class="form-control" id="father_age" name="father_age" placeholder="กรอกอายุ" value="<?php echo $prefill_father_age; ?>"  oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4 margin_bt">
                            <label for="father_occupation" class="form-label"><b>อาชีพ</b></label>
                                <input type="text" class="form-control" id="father_occupation" name="father_occupation" placeholder="กรอกอาชีพ" value="<?php echo $prefill_father_occupation; ?>">
                                <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-6 margin_bt">
                            <label for="father_Place_work" class="form-label"><b>ที่อยู่/สถานที่ทำงาน</b></label>
                            <input type="text" class="form-control" id="father_Place_work" name="father_Place_work" placeholder="กรอกที่อยู่/สถานที่ทำงาน" value="<?php echo $prefill_father_Place_work; ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="father_talephone" class="form-label"><b>โทรศัพท์มือถือ</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="father_talephone" name="father_talephone" onkeyup="autoTab(this,3)" placeholder="กรอกโทรศัพท์มือถือ" value="<?php echo $prefill_father_talephone; ?>" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required/>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="father_status" class="form-label"><b>สถานภาพชีวิต</B> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <input class="form-check-input" type="radio" name="radiofather_status" id="father_live" value="ยังมีชีวิต" <?php if($prefill_father_status == 'ยังมีชีวิต') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="father_live">ยังมีชีวิต</label>
                                </div>
                                <div class="col-md-6 mt-1" >
                                    <input class="form-check-input" type="radio" name="radiofather_status" id="father_died" value="เสียชีวิต" <?php if($prefill_father_status == 'เสียชีวิต') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="father_died">เสียชีวิต</label>
                                </div>
                            </div>
                        </div>
                    </div> <!-- ปิด Div ข้อมูลบิดา -->             
                    <div><hr class="style-one"></div>
                    <!--ข้อมูลมารดา-->
                    <div class="row mb-1">
                        <div class="col-md-5 margin_bt">
                            <label for="mother_name" class="form-label"><b>ชื่อ-นามสกุล มารดา</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name" placeholder="กรอกชื่อ-นามสกุล มารดา" value="<?php echo $prefill_mother_name; ?>"  required>
                            <div class="invalid-feedback">กรุณากรอก ชื่อ-นามสกุล มารดา</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="mother_age" class="form-label"><b>อายุ</b></label>
                            <input type="text" class="form-control" id="mother_age" name="mother_age" placeholder="กรอกอายุ" value="<?php echo $prefill_mother_age; ?>" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4 margin_bt">
                            <label for="mother_occupation" class="form-label"><b>อาชีพ</b></label>
                            <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" placeholder="กรอกอาชีพ" value="<?php echo $prefill_mother_occupation; ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-6 margin_bt">
                            <label for="mother_Place_work" class="form-label"><b>ที่อยู่/สถานที่ทำงาน</b></label>
                            <input type="text" class="form-control" id="mother_Place_work" name="mother_Place_work" placeholder="กรอกที่อยู่/สถานที่ทำงาน" value="<?php echo $prefill_mother_Place_work; ?>">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="mother_talephone" class="form-label"><b>โทรศัพท์มือถือ</b><span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="mother_talephone" name="mother_talephone" onkeyup="autoTab(this,3)" placeholder="กรอกโทรศัพท์มือถือ" value="<?php echo $prefill_mother_talephone; ?>"  oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required/>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="mother_status" class="form-label"><b>สถานภาพชีวิต</b> <span style="color: red;">*</span></span></label>
                            <div class="row">
                                <div class="col-md-6 mt-1">
                                    <input class="form-check-input" type="radio" name="radiomother_status" id="mother_live" value="ยังมีชีวิต" <?php if($prefill_mother_status == 'ยังมีชีวิต') echo 'checked'; ?>  required>
                                    <label class="form-check-label" for="father_live">ยังมีชีวิต</label>
                                </div>
                                <div class="col-md-6 mt-1" >
                                    <input class="form-check-input" type="radio" name="radiomother_status" id="mother_died" value="เสียชีวิต"  <?php if($prefill_mother_status == 'เสียชีวิต') echo 'checked'; ?>  required>
                                    <label class="form-check-label" for="father_died">เสียชีวิต</label>
                                </div>
                            </div>
                        </div> 
                    </div>  <!-- ปิด Div มารดา -->
                    <div><hr class="style-one"></div>
                    <!--จำนวนพี่น้องและเป็นบุตรคนที่เท่าไร-->
                    <div class="row mb-1">
                        <div class="col-md-6 margin_bt">
                            <label for="num_brother_sisters" class="form-label"><b>จำนวนพี่น้องทั้งหมดรวมตัวท่าน</b>  <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="num_brother_sisters" name="num_brother_sisters" placeholder="กรอกจำนวนพี่น้องของท่าน" value="<?php echo $prefill_num_bro_sis; ?>"  oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required>
                            <div class="invalid-feedback">กรุณากรอก จำนวนพี่น้องของท่าน</div>
                        </div>
                        <div class="col-md-6 margin_bt">
                            <label for="num_sir" class="form-label"><b>ท่านเป็นบุตรคนที่</b> <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="num_sir" name="num_sir" placeholder="กรอกท่านเป็นบุตรคนที่" value="<?php echo $prefill_num_sir; ?>"  oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required>
                            <div class="invalid-feedback">กรุณากรอก ท่านเป็นบุตรคนที่</div>
                        </div>
                    </div>
                    <!--ข้อมูล คู่สมรส-->      
                    <div id="spouse">
                        <div><hr class="style-one"></div>
                        <div class="row mb-1">
                            <div class="col-md-5 margin_bt">
                                <label for="spouse_name" class="form-label"><b>ชื่อ-นามสกุล คู่สมรส</b> (ถ้ามี)</label>
                                <input type="text" class="form-control" id="spouse_name" name="spouse_name" placeholder="กรอกชื่อ-นามสกุล คู่สมรส" value="<?php echo $prefill_spouse_name; ?>" >
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 margin_bt">
                                <label for="spouse_age" class="form-label"><b>อายุ</b></label>
                                <input type="text" class="form-control" id="spouse_age" name="spouse_age" placeholder="กรอกอายุ" value="<?php echo $prefill_spouse_age; ?>"  oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4 margin_bt">
                                <label for="spouse_occupation" class="form-label"><b>อาชีพ</b></label>
                                <input type="text" class="form-control" id="spouse_occupation" name="spouse_occupation" placeholder="กรอกอาชีพ" value="<?php echo $prefill_spouse_occupation; ?>" >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6 margin_bt">
                                <label for="spouse_Place_work" class="form-label"><b>ที่อยู่/สถานที่ทำงาน</b></label>
                                <input type="text" class="form-control" id="spouse_Place_work" name="spouse_Place_work" placeholder="กรอกที่อยู่/สถานที่ทำงาน" value="<?php echo $prefill_spouse_Place_work; ?>">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 margin_bt">
                                <label for="spouse_talephone" class="form-label"><b>โทรศัพท์มือถือ</b></label>
                                <input type="text" class="form-control" id="spouse_talephone" name="spouse_talephone"  onkeyup="autoTab(this,3)" placeholder="กรอกโทรศัพท์มือถือ" value="<?php echo $prefill_spouse_talephone; ?>" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" />
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-3 margin_bt">
                                <label for="children" class="form-label"><b>จำนวนบุตร</b></label>
                                <input type="text" class="form-control" id="children" name="children" placeholder="กรอกจำนวนบุตร " value="<?php echo $prefill_children; ?>" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');"/>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        
                    </div>  <!-- ปิด Div คู่สมรส -->
                </div>    <!-- ปิด session body -->
                <br>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="prevToSession2"><i class="fa-solid fa-angles-left fa-lg"></i> ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="nextToSession4">ถัดไป <i class="fa-solid fa-angles-right fa-lg"></i></button>
                </div> 
			</div><!--ปิด Session 3-->

			 <!-- Session 4: ความสามารถอื่นๆ -->
            <div class="form-session d-none" id="session4">
                <div class="section-header"><i class="fa-solid fa-user-check fa-lg"></i> ความชำนาญ และ ข้อมูลอื่นๆ </div>
                <div class="section-body">
                    <div class="row mb-1">
                        <h5><b>ทักษะด้านภาษาไทย</b></h5>
                        <div class="col-md-3 margin_bt">
                            <label for="thai_listen" class="form-label"><b>การฟัง</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="thai_listen" name="thai_listen" required>
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก"<?php if($prefill_thai_listen == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี"<?php if($prefill_thai_listen == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง"<?php if($prefill_thai_listen == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้"<?php if($prefill_thai_listen == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาไทย</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="thai_speak" class="form-label"><b>การพูด</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="thai_speak" name="thai_speak" required >
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก"<?php if($prefill_thai_speak == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี"<?php if($prefill_thai_speak == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง"<?php if($prefill_thai_speak == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้"<?php if($prefill_thai_speak == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาไทย</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="thai_read" class="form-label"><b>การอ่าน</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="thai_read" name="thai_read" required>
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_thai_read == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_thai_read == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_thai_read == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_thai_read == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาไทย</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="thai_write" class="form-label"><b>การเขียน</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="thai_write" name="thai_write" required >
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_thai_write == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_thai_write == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_thai_write == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_thai_write == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาไทย</div>
                        </div>
                    </div> <!--  ปิด div ด้านภาษาไทย -->
                    <div><hr class="style-one"></div>
                    <div class="row mb-1">
                        <h5><b>ทักษะด้านภาษาอังกฤษ</b></h5>
                        <div class="col-md-3 margin_bt">
                            <label for="eng_listen" class="form-label"><b>การฟัง</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="eng_listen" name="eng_listen" required>
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_eng_listen == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_eng_listen == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_eng_listen == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_eng_listen == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาอังกฤษ</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="eng_speak" class="form-label"><b>การพูด</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="eng_speak" name="eng_speak" required >
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_eng_speak == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_eng_speak == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_eng_speak == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_eng_speak == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาอังกฤษ</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="eng_read" class="form-label"><b>การอ่าน</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="eng_read" name="eng_read" required>
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_eng_read == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_eng_read == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_eng_read == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_eng_read == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาอังกฤษ</div>
                        </div>
                        <div class="col-md-3 margin_bt">
                            <label for="eng_write" class="form-label"><b>การเขียน</b> <span style="color: red;">*</span></label>
                            <select class="form-select font_option" id="eng_write" name="eng_write" required >
                                <option selected disabled value="">--เลือก--</option>
                                <option value="ดีมาก" <?php if($prefill_eng_write == 'ดีมาก') echo 'selected'; ?>>ดีมาก</option>
                                <option value="ดี" <?php if($prefill_eng_write == 'ดี') echo 'selected'; ?>>ดี</option>
                                <option value="ปานกลาง" <?php if($prefill_eng_write == 'ปานกลาง') echo 'selected'; ?>>ปานกลาง</option>
                                <option value="ไม่ได้" <?php if($prefill_eng_write == 'ไม่ได้') echo 'selected'; ?>>ไม่ได้</option>
                            </select>
                            <div class="invalid-feedback">กรุณาเลือกทักษะภาษาอังกฤษ</div>
                        </div>
                    </div> <!--  ปิด div ด้านภาษาอังกฤษ -->
                    <div><hr class="style-one"></div>
                    <!--ถาษาอื่นๆ ทักษะ ความสามารถพิเศษ--> 
                    <div class="row mb-1">
                        <div class="col-md-2 margin_bt">
                            <label for="other_languages" class="form-label"><b>ภาษาอื่นๆ</b></label>
                            <input type="text" class="form-control" id="other_languages" name="other_languages" value="<?php echo $prefill_other_languages; ?>"  placeholder="กรอกภาษาอื่นๆ (ถ้ามี)" maxlength="40">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-5 margin_bt">
                            <label for="computer_skill" class="form-label"><b>การใช้โปรแกรมคอมพิวเตอร์</b></label>
                            <input type="text" class="form-control" id="computer_skill" name="computer_skill" value="<?php echo $prefill_computer_skill; ?>"  placeholder="กรอกทักษะการใช้โปรแกรมคอมฯ (ถ้ามี)" maxlength="50">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-5 margin_bt">
                            <label for="talent_skill" class="form-label"><b>ความสามารถพิเศษ/งานอดิเรก</b></label>
                            <input type="text" class="form-control" id="talent_skill" name="talent_skill" value="<?php echo $prefill_talent_skill; ?>"  placeholder="กรอกความสามารถพิเศษ/งานอดิเรก(ถ้ามี)" maxlength="50">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>  
                    <!--ช่องทางการสมัคร -->
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt">
                            <label for="news" class="form-label"><b>ท่านทราบข้อมูลสมัครงานทางช่องทางไหน</b> <small>(เลือกได้มากกว่า 1 ช่องทาง)</small><span style="color: red;">*</span> </label>
                            <div class="row">
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxnews[]" id="website_company" value="เว็บไซต์บริษัท" <?php if(in_array('เว็บไซต์บริษัท', $prefill_news_array)) echo 'checked'; ?> >
                                    <label class="form-check-label" for="website_company">เว็บไซต์บริษัทฯ</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxnews[]" id="fb_company" value="Facebook บริษัท" <?php if(in_array('Facebook บริษัท', $prefill_news_array)) echo 'checked'; ?> >
                                    <label class="form-check-label" for="fb_company">Facebook บริษัทฯ</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxnews[]" id="employment" value="กรมจัดหางาน" <?php if(in_array('กรมจัดหางาน', $prefill_news_array)) echo 'checked'; ?> >
                                    <label class="form-check-label" for="employment">กรมจัดหางาน</label>
                                </div>
                                <div class="col-md-4">
                                    <input class="form-check-input" type="checkbox" name="checkboxnews[]" id="relative"  value="ญาติพี่น้องหรือเพื่อน ที่ทำงานในบริษัท" <?php if(in_array('ญาติพี่น้องหรือเพื่อน ที่ทำงานในบริษัท', $prefill_news_array)) echo 'checked'; ?> >
                                    <label class="form-check-label" for="relative">ญาติพี่น้องหรือเพื่อน ที่ทำงานในบริษัทฯ</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxnews[]" id="website_local" value="เว็บไซต์ท้องถิ่น"  <?php if(in_array('เว็บไซต์ท้องถิ่น', $prefill_news_array)) echo 'checked'; ?>  >
                                    <label class="form-check-label" for="website_local">เว็บไซต์ท้องถิ่น</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ปิด session ช่องทางรับสมัคร --> 
                    <!--ความสามารถในการขับขี่รถ -->
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt">
                            <label for="driver" class="form-label"><b>ท่านสามารถขับขี่รถ</b> <small>(เลือกได้มากกว่า 1 ช่องทาง)</small></label>
                            <div class="row">
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdriver[]" id="motorcycle" value="รถจักรยานยนต์" <?php if(in_array('รถจักรยานยนต์', $prefill_driver_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="motorcycle">รถจักรยานยนต์</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdriver[]" id="car" value="รถยนต์" <?php if(in_array('รถยนต์', $prefill_driver_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="car">รถยนต์</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdriver[]" id="forklift" value="รถโพล์คลิฟท์" <?php if(in_array('รถโพล์คลิฟท์', $prefill_driver_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="forklift">รถโพล์คลิฟท์</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdriver[]" id="6_wheels" value="รถ 6 ล้อ" <?php if(in_array('รถ 6 ล้อ', $prefill_driver_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="6_wheels">รถบรรทุก 6 ล้อ</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdriver[]" id="10_wheels" value="รถ 10 ล้อ" <?php if(in_array('รถ 10 ล้อ', $prefill_driver_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="10_wheels">รถบรรทุก 10 ล้อ</label>
                                </div>
                            </div>
                        </div>     
                    </div>  <!-- ปิด session ขับรถ --> 
                    <!--มีใบขับขี่ -->
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt">
                            <label for="drive_license" class="form-label"><b>ท่านมีใบอนุญาตขับขี่</b> <small>(เลือกได้มากกว่า 1 ช่องทาง)</small></label>
                            <div class="row">
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdrive_license[]" id="motorcycle_license" value="รถจักรยานยนต์" <?php if(in_array('รถจักรยานยนต์', $prefill_drive_license_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="motorcycle_license">รถจักรยานยนต์</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="form-check-input" type="checkbox" name="checkboxdrive_license[]" id="car_license" value="รถยนต์" <?php if(in_array('รถยนต์', $prefill_drive_license_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="car_license">รถยนต์</label>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-check-input" type="checkbox" name="checkboxdrive_license[]" id="other_license" value="รถ 6 ล้อ (ประเภท 2)" <?php if(in_array('รถ 6 ล้อ (ประเภท 2)', $prefill_drive_license_array)) echo 'checked'; ?>>
                                    <label class="form-check-label" for="other_license">รถบรรทุก 6 ล้อ (ประเภท 2)</label>
                                </div>
                            </div>
                        </div>
                    </div><!-- ปิด session ใบขับขี่ -->
                    <div><hr class="style-one"></div>
                    <!--บุคคลที่ท่านรู้จักที่ทำงานในบริษัทนี้-->
                    <div class="row mb-1">                    
                        <label for="person" class="form-label"><b>บุคคลที่ท่านรู้จักที่ทำงานในบริษัทนี้</b></label>
                        <div class="col-md-4 margin_bt">
                            <label for="person_name" class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" id="person_name" name="person_name" value="<?php echo $prefill_person_name; ?>"  placeholder="กรอกชื่อ-นามสกุล">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4 margin_bt" >
                            <label for="person_position" class="form-label">ตำแหน่งงาน</label>
                            <input type="text" class="form-control" id="person_position" name="person_position" value="<?php echo $prefill_person_position; ?>"  placeholder="กรอกตำแหน่งงาน">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4 margin_bt" >
                            <label for="person_relations" class="form-label">ความสัมพันธ์</label>
                            <input type="text" class="form-control" id="person_relations" name="person_relations" value="<?php echo $prefill_person_relations; ?>"  placeholder="กรอกความสัมพันธ์">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>     <!-- ปิด session คนรู้จักที่ทำงานบริษัทนี้ -->  
                    <div><hr class="style-one"></div>
                    <!--บุคคลอ้างอิงการทำงาน-->
                    <div class="row mb-1">
                        <label for="person_referen" class="form-label"><b>บุคคลอ้างอิงรับรองการทำงาน/ความสามารถ</b> <small>(รายชื่อนั้นต้องไม่ใช่ญาติพี่น้องหรือพนักงานของบริษัทนี้)</small></label>
                        <div class="col-md-4 margin_bt" >
                            <label for="person_referen_name" class="form-label">ชื่อ-นามสกุล <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="person_referen_name" name="person_referen_name" value="<?php echo $prefill_person_referen_name; ?>" placeholder="กรอกชื่อ-นามสกุล" required>
                            <div class="invalid-feedback">กรุณากรอก ชื่อ-นามสกุล </div>
                        </div>
                        <div class="col-md-4 margin_bt" >
                            <label for="person_referen_position" class="form-label">ตำแหน่งงาน <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="person_referen_position" name="person_referen_position" value="<?php echo $prefill_person_referen_position; ?>" placeholder="กรอกตำแหน่งงาน" required>
                            <div class="invalid-feedback">กรุณากรอก ตำแหน่งงาน</div>
                        </div>
                        <div class="col-md-4 margin_bt" >
                            <label for="person_referen_phone" class="form-label">เบอร์ติดต่อ <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="person_referen_phone" name="person_referen_phone" placeholder="กรอกเบอร์โทรศัพท์" value="<?php echo $prefill_person_referen_phone; ?>" onkeyup="autoTab(this,3)" required>
                            <div class="invalid-feedback">กรุณากรอก เบอร์ติดต่อ</div>
                        </div>                   
                    </div>    <!-- ปิด session บุคคลอ้างอิง1-->
                    <div class="row mb-1">
                        <div class="col-md-8 margin_bt" >
                            <label for="person_referen_address" class="form-label">ที่อยู่ หรือ สถานที่ทำงาน <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="person_referen_address" name="person_referen_address" value="<?php echo $prefill_person_referen_address; ?>" placeholder="กรอกที่อยู่ หรือ สถานที่ทำงาน" required>
                            <div class="invalid-feedback">กรุณากรอก ที่อยู่ หรือ สถานที่ทำงาน</div>
                        </div>              
                        <div class="col-md-4 margin_bt" >
                            <label for="person_referen_relations" class="form-label">ความสัมพันธ์ <span style="color: red;">*</span></label>
                            <input type="text" class="form-control" id="person_referen_relations" name="person_referen_relations" value="<?php echo $prefill_person_referen_relations; ?>" placeholder="กรอกความสัมพันธ์" required>
                            <div class="invalid-feedback">กรุณากรอก ความสัมพันธ์</div>
                        </div>                
                    </div>    <!-- ปิด session บุคคลอ้างอิง2 -->
                    <div><hr class="style-one"></div> 
                    <!-- ข้อตกลง -->
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                            <label for="imformation" class="form-label"><b>ท่านยินดีให้เราติดต่อสอบถามกับบริษัทที่ท่านทำงานอยู่หรือเคยทำงาน รวมถึงตรวจสอบวุฒิการศึกษาและคุณสมบัติของท่านหรือไม่</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radioimformation" id="yes_imformation" value="ได้" <?php if($prefill_imformation == 'ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_imformation">ได้</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radioimformation" id="no_imformation" value="ไม่ได้" <?php if($prefill_imformation == 'ไม่ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_imformation">ไม่ได้</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                            <label for="penalize" class="form-label"><b>ท่านเคยถูกจับหรือได้รับโทษในคดีอาญาโดยมีคำพิพากษาให้จำคุกหรือไม่ </b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radiopenalize" id="yes_penalize" value="เคย" <?php if($prefill_penalize == 'เคย') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_penalize">เคย</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radiopenalize" id="no_penalize" value="ไม่เคย" <?php if($prefill_penalize == 'ไม่เคย') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="no_penalize">ไม่เคย</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                            <label for="dismiss" class="form-label"><b>ท่านเคยถูกให้ออกจากงานเนื่องจากปัญหาด้านความประพฤติหรือประสิทธิภาพการทำงานหรือไม่</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radiodismiss" id="yes_dismiss" value="เคย" <?php if($prefill_dismiss == 'เคย') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_dismiss">เคย</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radiodismiss" id="no_dismiss" value="ไม่เคย" <?php if($prefill_dismiss == 'ไม่เคย') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="no_dismiss">ไม่เคย</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                            <label for="income_other" class="form-label"><b>ท่านมีรายได้จากแหล่งอื่น เช่น เบี้ยหวัด บำเหน็จ หรือค่าตอบแทนจากการเจ็บป่วยหรือไม่</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radioincome_other" id="yes_income" value="ได้" <?php if($prefill_income_other == 'ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_income">ได้</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radioincome_other" id="no_income" value="ไม่ได้" <?php if($prefill_income_other == 'ไม่ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="no_income">ไม่ได้</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                            <label for="health" class="form-label"><b>ขณะนี้ท่านมีอาการเจ็บป่วย โรคเรื้อรัง หรือภาวะทางร่างกายอื่น ๆ ที่อยู่ในความดูแลของแพทย์หรือไม่</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radiohealth" id="yes_health" value="ใช่" <?php if($prefill_health == 'ใช่') echo 'checked'; ?>  required>
                                    <label class="form-check-label" for="yes_health">ใช่</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radiohealth" id="no_health" value="ไม่ใช่" <?php if($prefill_health == 'ไม่ใช่') echo 'checked'; ?>  required>
                                    <label class="form-check-label" for="no_health">ไม่ใช่</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-md-12 margin_bt" >
                        <label for="move" class="form-label"><b>หากท่านได้รับเข้าเป็นพนักงาน ท่านยินยอมให้บริษัทฯ ปรับเปลี่ยนตำแหน่งหน้าที่ตามความเหมาะสม โดยไม่ลดค่าจ้างหรือผลประโยชน์หรือไม่</b> <span style="color: red;">*</span></label>
                            <div class="row">
                                <div class="col-md-1">
                                    <input class="form-check-input" type="radio" name="radiomove" id="yes_move" value="ได้" <?php if($prefill_move_job == 'ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="yes_move">ได้</label>
                                </div>
                                <div class="col-md-11" >
                                    <input class="form-check-input" type="radio" name="radiomove" id="no_move" value="ไม่ได้" <?php if($prefill_move_job == 'ไม่ได้') echo 'checked'; ?> required>
                                    <label class="form-check-label" for="no_move">ไม่ได้</label>
                                </div>
                            </div>
                        </div>
                    </div><!-- ปิดข้อตกลง -->                        
                </div><!-- ปิด session body -->        
                <br>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="prevToSession3"><i class="fa-solid fa-angles-left fa-lg"></i> ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="nextToSession5">ถัดไป <i class="fa-solid fa-angles-right fa-lg"></i></button>
                </div>     
            </div> <!-- ปิดsession4 -->

            <!-- Session 5: คำถามชี้วัดเพิ่มเติม -->

            <div class="form-session d-none" id="session5">    
                <div class="section-header"><i class="fa-solid fa-circle-question fa-lg"></i> แบบสอบถามเพิ่มเติม</div>
                <div class="section-body">
                    <div class ="row">		
                        <h5><b>โปรดตอบคำถามเพื่อเพิ่มโอกาสในการพิจารณารับเข้าทำงานของท่านมากขึ้น</b></h5><br>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor1" class="form-label"><b>1.กรุณายกตัวอย่างการตั้งเป้าหมายที่ท้าทายของท่าน และอธิบายถึงวิธีการฝ่าฟันอุปสรรคต่าง ๆ จนกระทั่งประสบความสำเร็จ</b> <span id="span1" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor1" name="more_infor1"  rows="2" placeholder="กรอกคำตอบของท่าน" required><?php echo $prefill_more_infor1; ?></textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor2" class="form-label"><b>2.กรุณาสรุปสถานการณ์ที่ท่านได้เสนอความคิดริเริ่มในการทำงานสำคัญ พร้อมทั้งเป็นผู้นำให้ทีมทำงานจนสำเร็จตามเป้าหมายที่วางไว้</b> <span id="span2" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor2" name="more_infor2"  rows="2" placeholder="กรอกคำตอบของท่าน" required>  <?php echo $prefill_more_infor2; ?>  </textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor3" class="form-label"><b>3.กรุณาอธิบายสถานการณ์การแก้ปัญหาที่ท่านต้องค้นคว้าข้อมูลที่เกี่ยวข้อง แยกแยะประเด็นสำคัญ และตัดสินใจแนวทางดำเนินการเพื่อให้บรรลุผลตามที่ต้องการ</b> <span id="span3" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor3" name="more_infor3" rows="2" placeholder="กรอกคำตอบของท่าน" required> <?php echo $prefill_more_infor3; ?>  </textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor4" class="form-label"><b>4.กรุณาอธิบายสถานการณ์ที่ท่านนำเสนอหรือชี้แจงประเด็น โดยใช้ข้อมูลที่ถูกต้องและน่าเชื่อถือ เพื่อสร้างความเห็นพ้องในการทำงานร่วมกับผู้อื่นอย่างมีประสิทธิภาพ</b> <span id="span4" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor4" name="more_infor4" rows="2" placeholder="กรอกคำตอบของท่าน" required> <?php echo $prefill_more_infor4; ?>  </textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor5" class="form-label"><b>5.กรุณายกตัวอย่างสถานการณ์ที่ท่านทำงานร่วมกับผู้อื่นอย่างมีประสิทธิภาพ เพื่อให้งานสำคัญประสบความสำเร็จตามเป้าหมาย</b> <span id="span5" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor5" name="more_infor5" rows="2" placeholder="กรอกคำตอบของท่าน" required>  <?php echo $prefill_more_infor5; ?>  </textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <label for="more_infor6" class="form-label"><b>6.กรุณาอธิบายถึงความคิดสร้างสรรค์ที่ท่านได้ริเริ่มขึ้น และมีบทบาทสำคัญในการสนับสนุนให้กิจกรรมหรือโครงการประสบความสำเร็จ</b> <span id="span6" style="color: red;">*</span></label>
                            <textarea class="form-control" id="more_infor6" name="more_infor6" rows="2" placeholder="กรอกคำตอบของท่าน" required>  <?php echo $prefill_more_infor6; ?>  </textarea>
                            <div class="invalid-feedback">กรุณากรอกคำตอบในช่องนี้</div>
                        </div>   
                    </div>      
                </div><!-- ปิด session body -->
                    
                <br>
                    
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="prevToSession4"><i class="fa-solid fa-angles-left fa-lg"></i> ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="nextToSession6">ถัดไป <i class="fa-solid fa-angles-right fa-lg"></i></button>
                </div> 
		    </div><!--ปิด Session 5-->

            <!-- Session 6: แนบเอกสาร -->
            <div class="form-session d-none" id="session6">  
                <div class="section-header"><i class="fa fa-paperclip fa-lg"></i> แนบหลักฐาน</div>
                <div class="section-body">
                    <div class="row">  
                        <div class="col-md-12">
		                    <label for="idcard_copy" class="form-label"><b>สำเนาบัตรประจำตัวประชาชน</b> <span style="color: red;">*</span> <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="idcard_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa-solid fa-cloud-arrow-up"></i> อัพโหลดไฟล์</label>
			                    <input id="idcard_upload" name ="idcard_upload" type="file" onchange="idcardsize(this)" accept="image/png, image/jpg, image/jpeg, .pdf" required/>
					            <span id="idcard_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div> 
                        <div class="col-md-12 bt_attact">
		                    <label for="address_copy" class="form-label"><b>สำเนาทะเบียนบ้าน</b> <span style="color: red;">*</span> <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="address_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="address_upload" name="address_upload" type="file" onchange="addresssize(this)" accept="image/png, image/jpg, image/jpeg, .pdf" required/>
					            <span id="address_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div>
                        <div class="col-md-12 bt_attact">
		                    <label for="transcript_copy" class="form-label"><b>สำเนาหลักฐานการศึกษา</b> <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="transcript_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="transcript_upload" name="transcript_upload" type="file" onchange="transcriptsize(this)" accept="image/png, image/jpg, image/jpeg, .pdf"/>
					            <span id="transcript_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div>
				        <div class="col-md-12 bt_attact">
		                    <label for="malitary_copy" class="form-label"><b>สำเนาหลักฐานทางทหาร</b> (ถ้ามี) <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="malitary_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="malitary_upload" name="malitary_upload" type="file" onchange="malitarysize(this)" accept="image/png, image/jpg, image/jpeg, .pdf"/>
					            <span id="malitary_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div>
				        <div class="col-md-12 bt_attact">
		                    <label for="driver_copy" class="form-label"><b>สำเนาใบอนุญาตขับขี่</b> (ถ้ามี) <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="driver_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="driver_upload" name="driver_upload" type="file" onchange="driversize(this)" accept="image/png, image/jpg, image/jpeg, .pdf"/>
					            <span id="driver_name" style="color: green; font-size: 14px;"></span>
				            </div>
				            <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">หมายเหตุ : พนักงานขับรถส่งของ, พนักงานขายเงินสด,พนักงานขายเครดิต ต้องแนบใบอนุญาติขับขี่ด้วย</small>
			            </div>
                        <div><hr class="style-one"></div>
				        <div class="col-md-12 bt_attact">
		                    <label for="certify_copy" class="form-label"><b>เอกสารรับรองการผ่านงาน</b> (ถ้ามี) <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="certify_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="certify_upload" name="certify_upload" type="file" onchange="certifysize(this)" accept="image/png, image/jpg, image/jpeg, .pdf"/>
					            <span id="certify_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div>
				        <!-- <div class="col-md-12 bt_attact">
		                    <label for="vaccinerecord_copy" class="form-label"><b>เอกสารรับรองการฉีดวัคซีน Covid-19</b> (ถ้ามี) <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.jpg, .jpeg, .png, .pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="vaccinerecord_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="vaccinerecord_upload" name="vaccinerecord_upload" type="file" onchange="vaccinesize(this)" accept="image/png, image/jpg, image/jpeg, .pdf"/>
					            <span id="vaccine_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                        <div><hr class="style-one"></div> -->
                        <div class="col-md-12 bt_attact mb-1">
		                    <label for="portfolio_copy" class="form-label"><b>Portfolio หรือ Resume</b> (ถ้ามี) <small style="font-size: 13px;" id="uploadfileHelp" class="form-text text-secondary">(*.pdf และขนาดไฟล์ต้องไม่เกิน 10 Mb)</small></label>
		                    <div>
			                    <label for="portfolio_upload" class="btn btn-primary-file btn-primary btn-sm"><i class="fa fa-cloud-upload"></i> อัพโหลดไฟล์</label>
			                    <input id="portfolio_upload" name="portfolio_upload" type="file" onchange="portfoliosize(this)" accept=".pdf"/>
					            <span id="portfolio_name" style="color: green; font-size: 14px;"></span>
				            </div>
			            </div>
                    </div>  <!-- ปิด Div row -->                 
                </div>    <!-- ปิด Div ประวัติครอบครัว -->           
                <br>
                <div class="section-header"><i class="fa-solid fa-bullhorn fa-lg"></i> ข้อตกลงในเรื่องการขอข้อมูลส่วนตัว </div>
                <div class="section-body"> 
                    <div class ="row">   
                        <div>
                            <p class="data">ข้าพเจ้าขอให้คำรับรองว่า ข้อความที่ข้าพเจ้าได้กรอกไว้ในใบสมัครงานนี้ ถูกต้องและตรงต่อความเป็นจริงทุกประการ ถ้าเมื่อใดหรือเวลาใดๆก็ตามความปรากฎว่าไม่ถูกต้อง หรือไม่ตรงต่อความเป็นจริงตามที่ข้าพเจ้าได้ให้ไว้ในใบสมัครนี้ ข้าพเจ้ายินดีให้ทางบริษัทฯ เลิกจ้างได้ทันที หรือจัดการได้ตามแต่จะเห็นสมควรโดยข้าพเจ้าจะไม่เรียกร้องสิทธิใดๆ จากบริษัทฯ</p> 
                            <p class="data">ทางบริษัทฯ มีการจัดเก็บข้อมูลการสมัครงานของท่านผ่านทางเว็บไซต์ของบริษัทฯ ที่ชอบด้วยกฎหมาย โดยจัดเก็บข้อมูลเท่าที่จำเป็นตามวัตถุประสงค์ของบริษัทฯ จึงต้องแจ้งให้ท่านทราบและขอความยินยอมก่อนเก็บรวบรวมข้อมูลส่วนบุคคลดังกล่าว <a href="pdpa/ornate_privacy_notice.png" id="privacyLink"  class="text-sky-600 hover:text-sky-500 underline">อ่านข้อมูลเพิ่มเติม</a></p>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="row">
                                <div class="col-md-2 margin_bt">
                                    <input class="form-check-input" type="radio" name="radioagree" id="agree" value="ยินยอม" required>
                                    <label class="form-check-label" for="agree"><b>ยินยอม</b> <span style="color: red;">*</span></label>
                                </div>
                                <div class="col-md-10 margin_bt" >
                                    <input class="form-check-input" type="radio" name="radioagree" id="not_agree" value="ไม่ยินยอม" required>
                                    <label class="form-check-label" for="not_agree"><b>ไม่ยินยอม</b></label>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>   
                <!-- ปิด Div ข้อตกลง -->         
                <br>
                <div class="text-end">
                    <button type="button" class="btn btn-secondary" id="prevToSession5"><i class="fa-solid fa-angles-left fa-lg"></i> ย้อนกลับ</button>
                    <button type="button" class="btn btn-primary" id="session_end"><i class="fa-solid fa-share-from-square fa-lg"></i> ส่งข้อมูลสมัครงาน</button>
                </div> 
			</div><!--ปิด Session 6-->
            

        </form>
    </div>  

        <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            
            <img class="modal-image" id="img01">
        </div>
        </div>



    <script>
        $(document).ready(function() {
            // console.log("$(document).ready() is executing. Page Mode:", <?php echo json_encode($page_mode); ?>);
            
                        // Global vars for script.js to access
            window.isEditMode = <?php echo json_encode($page_mode == 'edit'); ?>;
            window.prefillBranchJob = <?php echo json_encode($prefill_branch_job); ?>; // This holds 'SRT' from your DB
            window.prefillJobNoDb = <?php echo json_encode($prefill_job_no_db); ?>;   // This holds 'SRT0064    ' from your DB

            // console.log("Global isEditMode defined in resume_ornategroup.php:", window.isEditMode);
            // console.log("Global prefillBranchJob defined in resume_ornategroup.php:", window.prefillBranchJob);
            // console.log("Global prefillJobNoDb defined in resume_ornategroup.php:", window.prefillJobNoDb);



            // console.log("Image Vars: showActualImage =", showActualImage, ", actualImgSrc =", actualImgSrc, ", placeholder=", placeholderImgSrcForJs);

            
            // This timeout ensures that the DOM is fully rendered and script.js functions are available
            setTimeout(function() {
                // console.log("Timeout for auto_sex and job dropdowns is executing."); 
                // Call auto_sex() if title is pre-filled by PHP
                if (typeof auto_sex === 'function') {
                    var initialTitle = $('#title').val(); 
                    // console.log("Initial title value for auto_sex (inside timeout):", initialTitle); 
                    if (initialTitle && initialTitle !== "") {
                        // console.log("Calling auto_sex() from ready (inside timeout) because title is:", initialTitle); 
                        auto_sex();
                    }
                } else {
                    // console.log("auto_sex function is STILL not defined when timeout executes."); 
                }

                // Trigger change on sub_branch if in edit mode and prefillSubBranch is set
                <?php if ($page_mode == 'edit' && !empty($prefill_sub_branch)): ?>
                    var prefillSubBranch = <?php echo json_encode($prefill_sub_branch); ?>;
                    // console.log("Attempting to trigger change on sub_branch for value:", prefillSubBranch);
                    if (prefillSubBranch && $('#sub_branch').val() === prefillSubBranch) { // Ensure it's actually selected
                         $('#sub_branch').trigger('change');
                    }
                <?php endif; ?>

            }, 350); // Slightly longer delay to ensure everything is ready, adjust if necessary


            <?php if ($page_mode == 'edit' && !empty($form_data)): ?>
                // console.log("PHP confirms edit mode. Initializing other edit-mode JS."); 
                
                <?php if (isset($form_data['birthday']) && $form_data['birthday'] instanceof DateTimeInterface): ?>
                    if (typeof calAge === 'function' && document.getElementById('birthday').value) {
                        calAge(document.getElementById('birthday')); 
                    }
                <?php endif; ?>
                <?php if (isset($form_data['status'])): ?>
                    if (typeof status_div === 'function') {
                         if ($('#status').val() && $('#status').val() !== "") { 
                            status_div(); 
                        }
                    }
                <?php endif; ?>
                 if (typeof $('input[name="radiocongenital_disease"]').trigger === 'function') {
                    $('input[name="radiocongenital_disease"]:checked').trigger('click'); 
                 }
                 if (typeof $('input[name="radiodisabled"]').trigger === 'function') {
                    $('input[name="radiodisabled"]:checked').trigger('click'); 
                 }
            <?php else: ?>
                // console.log("PHP confirms page_mode is NOT 'edit' or form_data IS empty. Skipping edit-mode JS initializations."); 
            <?php endif; ?>
            // console.log("$(document).ready() has finished all initial setup attempts."); 
        });

            
        $(document).ready(function() {
            var modal = document.getElementById("myModal");
            var modalImg = document.getElementById("img01");
            var privacyLink = document.getElementById("privacyLink");
            var closeBtn = document.querySelector("#myModal .close-button");

            // When the user clicks the link, open the modal 
            privacyLink.onclick = function(e){
                e.preventDefault();
                modal.style.display = "block";
                modalImg.src = this.href;
            }

            // When the user clicks on <span> (x), close the modal
            closeBtn.onclick = function() { 
                modal.style.display = "none";
            }

            // Also close the modal if the user clicks anywhere on the dark background
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        });

        
    </script>
</body>
</html>

