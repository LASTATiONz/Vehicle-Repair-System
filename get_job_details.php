<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_POST['job_no'])) {
    echo json_encode(['error' => 'ไม่พบรหัสงาน']);
    exit;
}

$job_no = $_POST['job_no'];

try {
    $sql = "SELECT * FROM jobs_require WHERE job_no = ? AND job_status = 'Y'";
    
    $params = array($job_no);
    $stmt = sqlsrv_query($conn, $sql, $params);
    
    if ($stmt === false) {
        $errors = sqlsrv_errors();
        echo json_encode(['error' => 'เกิดข้อผิดพลาดในการดึงข้อมูล: ' . $errors[0]['message']]);
        exit;
    }
    
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    
    if ($row) {
        // แปลงข้อมูลวันที่เป็นรูปแบบที่ต้องการ
        if(isset($row['post_date'])) {
            $row['post_date'] = $row['post_date']->format('Y-m-d');
        }
        if(isset($row['expire_date'])) {
            $row['expire_date'] = $row['expire_date']->format('Y-m-d');
        }
        
        // แปลงรหัสสาขาเป็นชื่อสาขา
        $branch_names = array(
            'CHM' => 'เชียงใหม่',
            'HDY' => 'หาดใหญ่',
            'SRT' => 'สำนักงานใหญ่(สุราษฎร์ธานี)'
        );
        
        $row['branch_name'] = isset($branch_names[$row['branch']]) ? $branch_names[$row['branch']] : $row['branch'];

        // เพิ่มข้อมูลคุณสมบัติผู้สมัคร
        $requirements = array();
        if (!empty($row['gender'])) {
            $requirements[] = array(
                'icon' => 'fa-graduation-cap',
                'text' => 'เพศ: ' . $row['gender']
            );
        }

        if (!empty($row['major'])) {
            $requirements[] = array(
                'icon' => 'fa-graduation-cap',
                'text' => 'สายงานที่เรียน: ' . $row['major']
            );
        }
        
        // เพิ่มข้อมูลใบรับรอง
        for ($i = 1; $i <= 6; $i++) {
            $cert_field = 'job_certificate' . $i;
            if (!empty($row[$cert_field])) {
                $requirements[] = array(
                    'icon' => 'fa-certificate',
                    'text' => $row[$cert_field]
                );
            }
        }
        $row['requirements'] = $requirements;

        // เพิ่มข้อมูลสวัสดิการ
        $benefits = array();
        for ($i = 1; $i <= 9; $i++) {
            $benefit_field = 'benefits' . $i;
            if (!empty($row[$benefit_field])) {
                $benefits[] = array(
                    'icon' => 'fa-check-circle',
                    'text' => $row[$benefit_field]
                );
            }
        }
        $row['benefits'] = $benefits;

        // เพิ่มข้อมูลช่องทางติดต่อ
        $contact_info = array();
        if (!empty($row['hrcontact'])) {
            $contact_info[] = array(
                'icon' => 'fa-phone',
                'text' => 'เบอร์โทร: ' . $row['hrcontact']
            );
        }
        if (!empty($row['hrcontact2'])) {
            $contact_info[] = array(
                'icon' => 'fa-envelope',
                'text' => 'อีเมล: ' . $row['hrcontact2']
            );
        }

        // เพิ่ม Line ID ตามสาขา
        $line_links = array(
            'SRT' => array(
                'text' => 'Line ID: สำนักงานใหญ่(สุราษฎร์ธานี)',
                'link' => 'https://line.me/ti/p/NKzv6seCSM'
            ),
            'HDY' => array(
                'text' => 'Line ID: หาดใหญ่',
                'link' => 'https://line.me/ti/p/~hrornhdy'
            ),
            'CHM' => array(
                'text' => 'Line ID: เชียงใหม่',
                'link' => 'https://line.me/ti/p/~0813702662'
            )
        );

        if (isset($line_links[$row['branch']])) {
            $contact_info[] = array(
                'icon' => 'fab fa-line',
                'text' => $line_links[$row['branch']]['text'],
                'link' => $line_links[$row['branch']]['link']
            );
        }

        $row['contact_info'] = $contact_info;
        
        echo json_encode(['status' => 'success', 'data' => $row]);
    } else {
        echo json_encode(['error' => 'ไม่พบข้อมูลงาน']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
}
?> 