<?php
// update_guest_data.php
include('db_connect.php');

// Enable error display for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

$guest_no = $_POST['guest_no'] ?? null;
if (!$guest_no) {
    http_response_code(400);
    echo "Missing guest_no";
    exit;
}

// Determine upload directory
$branch_prefix = substr($guest_no, 0, 3);
$uploadFolder = "file_upload_" . strtolower($branch_prefix) . "/" . $guest_no . "/";
$uploadDir = __DIR__ . "/" . $uploadFolder;
$savePath = $uploadFolder;

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}



// Fields to update
$fields = [
    'title', 'name', 'nickname', 'salary', 'sex', 'birthday', 'age', 'status', 'work_date',
    'idcard', 'create_date', 'expiry_date', 'nationality', 'religion',
    'height', 'weight', 'phone', 'email', 'line_id', 'address', 'military',
    'u_school', 'u_year', 'u_gpa', 'u_educational', 'u_major', 'v_school', 'v_year', 'v_gpa', 'v_educational', 'v_major',
    'father_name', 'father_age', 'father_occupation', 'father_Place_work', 'father_talephone', 'father_status',
    'mother_name', 'mother_age', 'mother_occupation', 'mother_Place_work', 'mother_talephone', 'mother_status',
    'num_bro_sis', 'num_sir',
    'spouse_name', 'spouse_age', 'spouse_occupation', 'spouse_Place_work', 'spouse_talephone', 'children',
    'thai_listen', 'thai_speak', 'thai_read', 'thai_write',
    'eng_listen', 'eng_speak', 'eng_read', 'eng_write',
    'other_languages', 'computer_skill', 'talent_skill',
    'person_name', 'person_position', 'person_relations',
    'person_referen_name', 'person_referen_position', 'person_referen_phone',
    'person_referen_address', 'person_referen_relations',
    'imformation', 'penalize', 'dismiss', 'income_other', 'health', 'move_job',
    'more_infor1', 'more_infor2', 'more_infor3',
    'more_infor4', 'more_infor5', 'more_infor6'



];

/// work_experience section
for ($i = 1; $i <= 4; $i++) {
    $fields[] = "company_$i";
    $fields[] = "position_$i";
    $fields[] = "datestart_$i";
    $fields[] = "dateend_$i";
    $fields[] = "salary_$i";
    $fields[] = "detail_work_$i";
    $fields[] = "remark_leave_$i";
}




$updateData = [];
$params = [];



// ช่องทางการสมัครและการขับขี่
$news = isset($_POST['checkboxnews']) ? implode(',', $_POST['checkboxnews']) : '';
$driver = isset($_POST['checkboxdriver']) ? implode(',', $_POST['checkboxdriver']) : '';
$drive_license = isset($_POST['checkboxdrive_license']) ? implode(',', $_POST['checkboxdrive_license']) : '';

$updateData[] = "news = ?";
$params[] = $news;

$updateData[] = "driver = ?";
$params[] = $driver;

$updateData[] = "drive_license = ?";
$params[] = $drive_license;


// ข้อมูลวันที่
foreach ($fields as $field) {
    $value = $_POST[$field] ?? '';

    // Convert empty date fields to NULL
    if (in_array($field, ['birthday', 'create_date', 'expiry_date', 'work_date']) && $value === '') {
        $updateData[] = "$field = ?";
        $params[] = null;
    } else {
        $updateData[] = "$field = ?";
        $params[] = $value;
    }
}







// Handle file uploads
$uploadFields = [
    'picture_upload', 'idcard_upload', 'address_upload', 'transcript_upload',
    'malitary_upload', 'driver_upload', 'certify_upload', 'portfolio_upload'
];

foreach ($uploadFields as $field) {
    if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
        $tmpName = $_FILES[$field]['tmp_name'];
        $filename = basename($_FILES[$field]['name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = $field . '_' . $guest_no . '.' . $ext;
        $destination = $uploadDir . $newFilename;
        $dbPath = $savePath . $newFilename;

        // Remove old file
        $query = "SELECT $field FROM resume_job WHERE guest_no = ?";
        $check = sqlsrv_query($conn, $query, [$guest_no]);
        if ($row = sqlsrv_fetch_array($check, SQLSRV_FETCH_ASSOC)) {
            $oldPattern = $uploadDir . $field . '_' . $guest_no . '.*';
            foreach (glob($oldPattern) as $oldFilePath) {
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        }

        // Save new file
        if (move_uploaded_file($tmpName, $destination)) {
            $updateData[] = "$field = ?";
            $params[] = $newFilename;
        } else {
            error_log("[UPLOAD ERROR] Failed to move $field to $destination");
        }
    } elseif (isset($_FILES[$field])) {
        error_log("[UPLOAD SKIPPED] $field error code: " . $_FILES[$field]['error']);
    }
}


// ✅ Now add congenital_disease
$congenital_choice = $_POST['congenital_disease_radio'] ?? 'ไม่มี';
$congenital_text = trim($_POST['congenital_disease_text'] ?? '');
$congenital_disease = ($congenital_choice === 'มี' && $congenital_text !== '') ? $congenital_text : 'ไม่มี';
$updateData[] = "congenital_disease = ?";
$params[] = $congenital_disease;

// ✅ And add disabled
$disabled_choice = $_POST['disabled_radio'] ?? 'ปกติ';
$disabled_text = trim($_POST['disabled_text'] ?? '');
$disabled = ($disabled_choice === 'พิการ' && $disabled_text !== '') ? $disabled_text : 'ปกติ';
$updateData[] = "disabled = ?";
$params[] = $disabled;



// ✅ Add agree_data
$updateData[] = "agree_data = ?";
$params[] = 'ยินยอม';





// Update date
$updateData[] = "date_update = GETDATE()";

// Prepare final SQL
$sql = "UPDATE resume_job SET " . implode(", ", $updateData) . " WHERE guest_no = ?";
$params[] = $guest_no;

// Debug log
error_log("[SQL] $sql");
error_log("[PARAMS] " . print_r($params, true));



// Execute
$stmt = sqlsrv_query($conn, $sql, $params);
if ($stmt === false) {
    http_response_code(500);
    $errors = sqlsrv_errors();
    foreach ($errors as $error) {
        error_log("[DB ERROR] SQLSTATE: {$error['SQLSTATE']} | Code: {$error['code']} | Message: {$error['message']}");
    }
    echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล";
    exit;
}

echo "บันทึกเรียบร้อย";
