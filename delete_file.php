<?php
// delete_file.php
include('db_connect.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$guest_no = $_POST['guest_no'] ?? null;
$field = $_POST['field'] ?? null;

if (!$guest_no || !$field) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing guest_no or field']);
    exit;
}

// Get file path
$sql = "SELECT $field FROM resume_job WHERE guest_no = ?";
$stmt = sqlsrv_query($conn, $sql, [$guest_no]);

if (!$stmt || !($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))) {
    http_response_code(404);
    echo json_encode(['error' => 'Data not found']);
    exit;
}

$filename = $row[$field];
if (empty($filename)) {
    echo json_encode(['message' => 'No file to delete']);
    exit;
}

$branch = strtolower(substr($guest_no, 0, 3));
$uploadPath = __DIR__ . "/file_upload_{$branch}/{$guest_no}/$filename";

// Delete file from server
if (file_exists($uploadPath)) {
    unlink($uploadPath);
}

// Remove filename from DB
$update = "UPDATE resume_job SET $field = NULL WHERE guest_no = ?";
$updateStmt = sqlsrv_query($conn, $update, [$guest_no]);

if ($updateStmt === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to update database']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'File deleted']);
