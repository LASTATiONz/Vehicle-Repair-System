<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JOB ORNATE SDO</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="styles.css" rel="stylesheet">
	<script src="js/script.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

	<link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">
 
</head>

<style>
    .badge {
    display: inline-block;
    font-weight: bold;
    text-align: center;
    border: 1px solid transparent;
    font-size: 12px;  /* ลดขนาดฟอนต์เล็กลง */
    padding: 3px 6px; /* ลดระยะขอบด้านใน */
    border-radius: 3px; /* โค้งมนเล็กน้อย */

    }

    .badge-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }

    .badge-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }

    .badge-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    }

    .badge i {
        margin-right: 5px;
    }

    .status-badge {
        display: inline-block;
        width: 120px;  /* ทำให้ขนาดทุกป้ายเท่ากัน */
        text-align: left;
        font-size: 12px;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: bold;
        white-space: nowrap;  /* ป้องกันข้อความขึ้นบรรทัดใหม่ */
    }



</style>


<body>
<div class="m-0">
<?php
//include connect database
    session_start();
    ob_start();
    include 'db_connect.php';
    include 'include/header.php';

    if (!isset($_SESSION['ses_username']) || empty($_SESSION['ses_username'])) {
        // แสดงข้อความแจ้งเตือน
        echo "
        <!DOCTYPE html>
        <html lang='th'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>กรุณา Login</title>
            <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css' rel='stylesheet'>
            <link href='styles.css' rel='stylesheet'>
            <script>
                // ฟังก์ชันสำหรับการนับถอยหลัง
                let countdown = 10;
                function startCountdown() {
                    const countdownElement = document.getElementById('countdown');
                    const interval = setInterval(() => {
                        countdown--;
                        countdownElement.textContent = countdown;
                        if (countdown <= 0) {
                            clearInterval(interval);
                            window.location.href = 'index.php';
                        }
                    }, 1000);
                }
            </script>
        </head>
        <body onload='startCountdown()'>
            <div class='container mt-5'>
                <div class='alert alert-warning text-center' role='alert'>
                    <h4 class='alert-heading'>คุณยังไม่ได้ เข้าสู่ระบบ !!</h4>
                    <p>ระบบจะพาคุณกลับไปหน้า เข้าสู่ระบบ ใน <span id='countdown'>10</span> วินาที</p>
                    <hr>
                    <a href='index.php' class='btn btn-primary btn-sm'><i class='fa-solid fa-arrow-rotate-left fa-lg'></i> กลับไปหน้า เข้าสู่ระบบ</a>
                </div>
            </div>
        </body>
        </html>
        ";
        exit();
    }
?>
</div>
<div class="container mt-5"> 
<!--Div Content-->
    <div class="section-header"><i class="fa-solid fa-chart-pie fa-lg txt_icon"></i><span class="txt_toppic"> Dashboard</span></div>
    <div class="section-body">

        <?php
        function getCount($conn, $branch, $condition = '') {
            $baseSQL = "SELECT COUNT(guest_no) AS total FROM resume_job 
                        WHERE branch_job = ? AND YEAR(rec_time_stamp) = YEAR(GETDATE()) $condition";
            $params = [$branch];
            $options = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
            $query = sqlsrv_query($conn, $baseSQL, $params, $options);

            if ($query && $row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
                return $row['total'];
            }
            return 0;
        }

        $branch = $_SESSION['ses_branch'];
        $totalResume = getCount($conn, $branch);
        $totalInterviews = getCount($conn, $branch, "AND interviews = 'เรียกสัมภาษณ์'");
        $totalYesSelected = getCount($conn, $branch, "AND selected = 'ผ่าน'");
        $totalNoSelected = getCount($conn, $branch, "AND selected = 'ไม่ผ่าน'");
        ?>

        <div class="row">
            <div class="col-lg-3">
                <label for="" class="mb-1">&nbsp;<b> <i class="fa-solid fa-window-restore"></i> ใบสมัครทั้งหมด</b></label>
                <div class="div_tot">
                    <?= $totalResume; ?><p style="font-size: 15px;">ใบ</p>
                </div>
                <br>
            </div>
            <div class="col-lg-3">
                <label for="" class="mb-1">&nbsp;<b><i class="fa-solid fa-user-tag"></i> เรียกสัมภาษณ์</b></label>
                <div class="div_interview">
                    <?= $totalInterviews; ?><p style="font-size: 15px;">คน</p>
                </div>
                <br>
            </div>
            <div class="col-lg-3">
                <label for="" class="mb-1">&nbsp;<b><i class="fa-solid fa-user-check"></i> ผ่านสัมภาษณ์</b></label>
                <div class="div_yes">
                    <?= $totalYesSelected; ?><p style="font-size: 15px;">คน</p>
                </div>
                <br>
            </div>
            <div class="col-lg-3">
                <label for="" class="mb-1">&nbsp;<b><i class="fa-solid fa-user-xmark"></i> ไม่ผ่านสัมภาษณ์</b></label>
                <div class="div_no">
                    <?= $totalNoSelected; ?><p style="font-size: 15px;">คน</p>
                </div>
                <br>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header bg-primary text-white">
                <h7 class="mb-0"><i class="fa-solid fa-chart-column fa-lg"></i> กราฟแสดงจำนวนใบสมัครต่อเดือน ในปี <?php echo date("Y")+543; ?></h7>
            </div>
            <div class="card-body">
                <div id="resume_chart" style="width: 100%; height: 320px;"></div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header bg-primary text-white">
                <h7 class="mb-0"><i class="fa-solid fa-chart-column fa-lg"></i> กราฟแสดงจำนวนเรียกสัมภาษณ์ ผ่านสัมภาษณ์ และ รับเข้าทำงานในแต่ละเดือน ในปี <?php echo date("Y")+543; ?></h7>
            </div>
            <div class="card-body">
                <div id="interview_chart" style="width: 100%; height: 320px;"></div>
            </div>
        </div>

        <div class="card shadow mt-3">
            <div class="card-header bg-primary text-white">
                <h7 class="mb-0"><i class="fa-solid fa-chart-column fa-lg"></i> กราฟแสดงช่องทางการรับสมัครงานในแต่ละเดือน ในปี <?php echo date("Y")+543; ?></h7>
            </div>
            <div class="card-body">
                <div id="new_chart" style="width: 100%; height: 320px;"></div>
            </div>
        </div>

        <?php
        $_month_name = array(
            "01" => "มกราคม", "02" => "กุมภาพันธ์", "03" => "มีนาคม", "04" => "เมษายน", 
            "05" => "พฤษภาคม", "06" => "มิถุนายน", "07" => "กรกฎาคม", "08" => "สิงหาคม", 
            "09" => "กันยายน", "10" => "ตุลาคม", "11" => "พฤศจิกายน", "12" => "ธันวาคม"
        );

        $vardate = date('Y-m-d');
        $yy = date('Y')+543;
        $mm = date('m');
        $month = $_month_name[$mm];
        ?>
        <div class="card shadow mt-3 mb-2">
            <div class="card-header bg-primary text-white">
                <h7><i class="fa-solid fa-window-restore fa-lg"></i> ข้อมูลใบสมัคร เดือน<?php echo $month . " " . $yy; ?> </h7>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    $strSQL = "SELECT guest_no, title + name AS name, nickname, age, sex, job_name, salary,
                                CASE 
                                    WHEN agree_data IS NULL THEN 'ไม่เรียบร้อย' 
                                    WHEN agree_data = 'ยินยอม' THEN 'กรอกเรียบร้อย' 
                                    ELSE 'ไม่ระบุ' 
                                END AS status 
                                FROM resume_job
                                WHERE branch_job = ? 
                                AND MONTH(rec_time_stamp) = MONTH(GETDATE()) 
                                AND YEAR(rec_time_stamp) = YEAR(GETDATE()) 
                                -- AND   picture_upload is not null
                                ORDER BY guest_no DESC";

                    $params = [$_SESSION['ses_branch']];
                    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                    $Query = sqlsrv_query($conn, $strSQL, $params, $options);
                    $numRows = sqlsrv_num_rows($Query);
                    ?>

                    <table id="table" class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">#</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">เลขที่ผู้สมัคร</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">ชื่อ-นามสกุล</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">ชื่อเล่น</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">อายุ</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">เพศ</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">ตำแหน่งสมัคร</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">เงินเดือน</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">ข้อมูลเพิ่มเติม</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1">สถานะใบสมัคร</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        while ($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)) {
                            $i++;
                            $button_view = "<button type='button' class='button_view button_h' onclick='popWin(\"" . $row["guest_no"] . "\")'><i class='fa-solid fa-eye'></i>&nbsp;ดูข้อมูล</button>";
                            echo "<tr>";
                            echo "<td align='center'>$i</td>";
                            echo "<td>" . $row["guest_no"] . "</td>";
                            echo "<td>" . $row["name"] . "</td>";
                            echo "<td>" . $row["nickname"] . "</td>";
                            echo "<td>" . $row["age"] . "</td>";
                            echo "<td>" . $row["sex"] . "</td>";
                            echo "<td>" . $row["job_name"] . "</td>";
                            echo "<td>" . $row["salary"] . "</td>";
                            echo "<td align='center'>$button_view</td>";

                            // สถานะใบสมัคร
                            $status = $row["status"];
                            $statusColor = "";
                            $bgColor = "";
                            $icon = "";
                            
                            // กำหนดสีและไอคอนตามสถานะ
                            $status = $row["status"];
                            $statusClass = "";
                            $icon = "";
                            
                            // กำหนด class และไอคอนให้ดูเรียบง่าย แต่โดดเด่น
                            if ($status == "กรอกเรียบร้อย") {
                                $statusClass = "badge-success"; // สีเขียวอ่อน
                                $icon = '<i class="fa-solid fa-check-circle"></i>'; // ✔ ไอคอน
                            } elseif ($status == "ไม่เรียบร้อย") {
                                $statusClass = "badge-danger"; // สีแดงอ่อน
                                $icon = '<i class="fa-solid fa-times-circle"></i>'; // ❌ ไอคอน
                            } else {
                                $statusClass = "badge-warning"; // สีส้มอ่อน
                                $icon = '<i class="fa-solid fa-exclamation-circle"></i>'; // ⚠ ไอคอน
                            }
                            

                            echo '<td class="td-status">
                            <span class="badge ' . $statusClass . ' status-badge">
                                ' . $icon . ' ' . $status . '
                            </span>
                            </td>';

                            
                            
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>      
    </div>       
<!--------------------------->
</div> <br>

</body>
</html>

<?php
// ส่วน SQL Query และ PHP เหมือนเดิม
$strSQL4 = "
    SELECT branch_job, 
        ISNULL([Jan], 0) AS [Jan], ISNULL([Feb], 0) AS [Feb], ISNULL([Mar], 0) AS [Mar],
        ISNULL([Apr], 0) AS [Apr], ISNULL([May], 0) AS [May], ISNULL([Jun], 0) AS [Jun],
        ISNULL([Jul], 0) AS [Jul], ISNULL([Aug], 0) AS [Aug], ISNULL([Sep], 0) AS [Sep],
        ISNULL([Oct], 0) AS [Oct], ISNULL([Nov], 0) AS [Nov], ISNULL([Dec], 0) AS [Dec]
    FROM (
        SELECT branch_job, guest_no,
            CASE MONTH(rec_time_stamp)
                WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar'
                WHEN 4 THEN 'Apr' WHEN 5 THEN 'May' WHEN 6 THEN 'Jun'
                WHEN 7 THEN 'Jul' WHEN 8 THEN 'Aug' WHEN 9 THEN 'Sep'
                WHEN 10 THEN 'Oct' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Dec'
            END AS month
        FROM resume_job 
        WHERE branch_job = ? AND YEAR(rec_time_stamp) = YEAR(GETDATE())
    ) AS src
    PIVOT (
        COUNT(guest_no)
        FOR month IN ([Jan], [Feb], [Mar], [Apr], [May], [Jun], [Jul], [Aug], [Sep], [Oct], [Nov], [Dec])
    ) AS pvt";

$params4 = [$_SESSION['ses_branch']];
$options4 = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
$query4 = sqlsrv_query($conn, $strSQL4, $params4, $options4);

$row4 = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);

if ($query4 && sqlsrv_has_rows($query4)) {
    $row4 = sqlsrv_fetch_array($query4, SQLSRV_FETCH_ASSOC);
}

$strSQL5 = "
    SELECT branch_job, 
        ISNULL([Jan], 0) AS [Jan], ISNULL([Feb], 0) AS [Feb], ISNULL([Mar], 0) AS [Mar],
        ISNULL([Apr], 0) AS [Apr], ISNULL([May], 0) AS [May], ISNULL([Jun], 0) AS [Jun],
        ISNULL([Jul], 0) AS [Jul], ISNULL([Aug], 0) AS [Aug], ISNULL([Sep], 0) AS [Sep],
        ISNULL([Oct], 0) AS [Oct], ISNULL([Nov], 0) AS [Nov], ISNULL([Dec], 0) AS [Dec]
    FROM (
        SELECT branch_job, guest_no,
            CASE MONTH(rec_time_stamp)
                WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar'
                WHEN 4 THEN 'Apr' WHEN 5 THEN 'May' WHEN 6 THEN 'Jun'
                WHEN 7 THEN 'Jul' WHEN 8 THEN 'Aug' WHEN 9 THEN 'Sep'
                WHEN 10 THEN 'Oct' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Dec'
            END AS month
        FROM resume_job 
        WHERE branch_job = ? AND YEAR(rec_time_stamp) = YEAR(GETDATE()) AND interviews = 'เรียกสัมภาษณ์'
    ) AS src
    PIVOT (
        COUNT(guest_no)
        FOR month IN ([Jan], [Feb], [Mar], [Apr], [May], [Jun], [Jul], [Aug], [Sep], [Oct], [Nov], [Dec])
    ) AS pvt";

$params5 = [$_SESSION['ses_branch']];
$options5 = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
$query5 = sqlsrv_query($conn, $strSQL5, $params5, $options5);

$row5 = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);

if ($query5 && sqlsrv_has_rows($query5)) {
    $row5 = sqlsrv_fetch_array($query5, SQLSRV_FETCH_ASSOC);
}

$strSQL6 = "
    SELECT branch_job, 
        ISNULL([Jan], 0) AS [Jan], ISNULL([Feb], 0) AS [Feb], ISNULL([Mar], 0) AS [Mar],
        ISNULL([Apr], 0) AS [Apr], ISNULL([May], 0) AS [May], ISNULL([Jun], 0) AS [Jun],
        ISNULL([Jul], 0) AS [Jul], ISNULL([Aug], 0) AS [Aug], ISNULL([Sep], 0) AS [Sep],
        ISNULL([Oct], 0) AS [Oct], ISNULL([Nov], 0) AS [Nov], ISNULL([Dec], 0) AS [Dec]
    FROM (
        SELECT branch_job, guest_no,
            CASE MONTH(rec_time_stamp)
                WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar'
                WHEN 4 THEN 'Apr' WHEN 5 THEN 'May' WHEN 6 THEN 'Jun'
                WHEN 7 THEN 'Jul' WHEN 8 THEN 'Aug' WHEN 9 THEN 'Sep'
                WHEN 10 THEN 'Oct' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Dec'
            END AS month
        FROM resume_job 
        WHERE branch_job = ? AND YEAR(rec_time_stamp) = YEAR(GETDATE()) AND interviews = 'ผ่าน'
    ) AS src
    PIVOT (
        COUNT(guest_no)
        FOR month IN ([Jan], [Feb], [Mar], [Apr], [May], [Jun], [Jul], [Aug], [Sep], [Oct], [Nov], [Dec])
    ) AS pvt";

$params6 = [$_SESSION['ses_branch']];
$options6 = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
$query6 = sqlsrv_query($conn, $strSQL6, $params6, $options6);

$row6 = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);

if ($query6 && sqlsrv_has_rows($query6)) {
    $row6 = sqlsrv_fetch_array($query6, SQLSRV_FETCH_ASSOC);
}

$strSQL7 = "
    SELECT branch_job, 
        ISNULL([Jan], 0) AS [Jan], ISNULL([Feb], 0) AS [Feb], ISNULL([Mar], 0) AS [Mar],
        ISNULL([Apr], 0) AS [Apr], ISNULL([May], 0) AS [May], ISNULL([Jun], 0) AS [Jun],
        ISNULL([Jul], 0) AS [Jul], ISNULL([Aug], 0) AS [Aug], ISNULL([Sep], 0) AS [Sep],
        ISNULL([Oct], 0) AS [Oct], ISNULL([Nov], 0) AS [Nov], ISNULL([Dec], 0) AS [Dec]
    FROM (
        SELECT branch_job, guest_no,
            CASE MONTH(rec_time_stamp)
                WHEN 1 THEN 'Jan' WHEN 2 THEN 'Feb' WHEN 3 THEN 'Mar'
                WHEN 4 THEN 'Apr' WHEN 5 THEN 'May' WHEN 6 THEN 'Jun'
                WHEN 7 THEN 'Jul' WHEN 8 THEN 'Aug' WHEN 9 THEN 'Sep'
                WHEN 10 THEN 'Oct' WHEN 11 THEN 'Nov' WHEN 12 THEN 'Dec'
            END AS month
        FROM resume_job 
        WHERE branch_job = ? AND YEAR(rec_time_stamp) = YEAR(GETDATE()) AND interviews = 'รับเข้าทำงาน'
    ) AS src
    PIVOT (
        COUNT(guest_no)
        FOR month IN ([Jan], [Feb], [Mar], [Apr], [May], [Jun], [Jul], [Aug], [Sep], [Oct], [Nov], [Dec])
    ) AS pvt";

$params7 = [$_SESSION['ses_branch']];
$options7 = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
$query7 = sqlsrv_query($conn, $strSQL7, $params7, $options7);

$row7 = array_fill_keys(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'], 0);

if ($query7 && sqlsrv_has_rows($query7)) {
    $row7 = sqlsrv_fetch_array($query7, SQLSRV_FETCH_ASSOC);
}

$strSQL8 = "
    WITH SplitNews AS (
    SELECT 
        DATENAME(MONTH, rec_time_stamp) AS MonthName, -- แปลงวันที่เป็นชื่อเดือน (ภาษาอังกฤษ)
        LTRIM(RTRIM(value)) AS news_source -- แยกค่าจาก news และตัดช่องว่าง
    FROM resume_job
    CROSS APPLY STRING_SPLIT(news, ',') -- แยกค่าด้วยเครื่องหมายคอมมา
    WHERE 
        DATEPART(YEAR, rec_time_stamp) = YEAR(GETDATE()) -- เลือกเฉพาะข้อมูลในปีปัจจุบัน
        AND LTRIM(RTRIM(value)) <> '' -- ตัดค่าที่เป็นช่องว่าง
        AND news IS NOT NULL -- ตัดค่าที่เป็น NULL
        AND branch_job = ? -- พารามิเตอร์สำหรับสาขางาน
)
SELECT 
    news_source AS ช่องทาง,
    ISNULL([January], 0) AS Jan,
    ISNULL([February], 0) AS Feb,
    ISNULL([March], 0) AS Mar,
    ISNULL([April], 0) AS Apr,
    ISNULL([May], 0) AS May,
    ISNULL([June], 0) AS Jun,
    ISNULL([July], 0) AS Jul,
    ISNULL([August], 0) AS Aug,
    ISNULL([September], 0) AS Sep,
    ISNULL([October], 0) AS Oct,
    ISNULL([November], 0) AS Nov,
    ISNULL([December], 0) AS Dec
FROM (
    SELECT 
        news_source,
        MonthName,
        COUNT(*) AS CountPeople -- นับจำนวนคนที่ใช้แต่ละช่องทาง
    FROM SplitNews
    GROUP BY news_source, MonthName -- จัดกลุ่มตามช่องทางและเดือน
) AS SourceData
PIVOT (
    SUM(CountPeople) -- สรุปผลรวมของจำนวนคนในแต่ละเดือน
    FOR MonthName IN ([January], [February], [March], [April], [May], [June], 
                      [July], [August], [September], [October], [November], [December])
) AS PivotTable
ORDER BY ช่องทาง; -- เรียงลำดับตามชื่อช่องทาง
";

$params8 = [$_SESSION['ses_branch']];
$options8 = ["Scrollable" => SQLSRV_CURSOR_KEYSET];
$query8 = sqlsrv_query($conn, $strSQL8, $params8, $options8);

$data = [];
if ($query8 && sqlsrv_has_rows($query8)) {
    while ($row = sqlsrv_fetch_array($query8, SQLSRV_FETCH_ASSOC)) {
        $data[] = $row;
    }
} else {
    $data = [];
}
?>

<!-- Google Chart -->
<!-- Chart1 -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let isChart1ListenerAdded = false;
    let chart1; // ตัวแปรเก็บ instance ของกราฟ

    function drawChart1() {
        var monthNamesTh = {
            "Jan": "มกราคม",
            "Feb": "กุมภาพันธ์",
            "Mar": "มีนาคม",
            "Apr": "เมษายน",
            "May": "พฤษภาคม",
            "Jun": "มิถุนายน",
            "Jul": "กรกฎาคม",
            "Aug": "สิงหาคม",
            "Sep": "กันยายน",
            "Oct": "ตุลาคม",
            "Nov": "พฤศจิกายน",
            "Dec": "ธันวาคม"
        };
        var data = google.visualization.arrayToDataTable([
            ["เดือน", "จำนวนใบสมัคร", {role: "style"}, {role: "annotation"}],
            [monthNamesTh["Jan"], <?php echo $row4["Jan"]; ?>, "stroke-color: #0066CC; stroke-width: 1; fill-color: #1E90FF", <?php echo $row4["Jan"]; ?>],
            [monthNamesTh["Feb"], <?php echo $row4["Feb"]; ?>, "stroke-color: #2E8B57; stroke-width: 1; fill-color: #3CB371", <?php echo $row4["Feb"]; ?>],
            [monthNamesTh["Mar"], <?php echo $row4["Mar"]; ?>, "stroke-color: #B8860B; stroke-width: 1; fill-color: #FFD700", <?php echo $row4["Mar"]; ?>],
            [monthNamesTh["Apr"], <?php echo $row4["Apr"]; ?>, "stroke-color: #A52A2A; stroke-width: 1; fill-color: #FF6347", <?php echo $row4["Apr"]; ?>],
            [monthNamesTh["May"], <?php echo $row4["May"]; ?>, "stroke-color: #9932CC; stroke-width: 1; fill-color: #DDA0DD", <?php echo $row4["May"]; ?>],
            [monthNamesTh["Jun"], <?php echo $row4["Jun"]; ?>, "stroke-color: #A0522D; stroke-width: 1; fill-color: #CD853F", <?php echo $row4["Jun"]; ?>],
            [monthNamesTh["Jul"], <?php echo $row4["Jul"]; ?>, "stroke-color: #CC3333; stroke-width: 1; fill-color: #CD5C5C", <?php echo $row4["Jul"]; ?>],
            [monthNamesTh["Aug"], <?php echo $row4["Aug"]; ?>, "stroke-color: #696969; stroke-width: 1; fill-color: #708090", <?php echo $row4["Aug"]; ?>],
            [monthNamesTh["Sep"], <?php echo $row4["Sep"]; ?>, "stroke-color: #66FF33; stroke-width: 1; fill-color: #33FF99", <?php echo $row4["Sep"]; ?>],
            [monthNamesTh["Oct"], <?php echo $row4["Oct"]; ?>, "stroke-color: #660000; stroke-width: 1; fill-color: #660033", <?php echo $row4["Oct"]; ?>],
            [monthNamesTh["Nov"], <?php echo $row4["Nov"]; ?>, "stroke-color: #00CED1; stroke-width: 1; fill-color: #48D1CC", <?php echo $row4["Nov"]; ?>],
            [monthNamesTh["Dec"], <?php echo $row4["Dec"]; ?>, "stroke-color: #9400D3; stroke-width: 1; fill-color: #9932CC", <?php echo $row4["Dec"]; ?>]
        ]);

        const date = new Date(); 
	    const title = date.getFullYear()+543 ;
        var options = {
            title: "แสดงจำนวนใบสมัครงานแต่ละเดือน ในปี "+title,
            titleTextStyle: {fontSize: 13, fontName: "Sarabun", color: "#000033"},
            bar: {groupWidth: "65%"},
            hAxis: {
                title: "เดือน",
                textStyle: {fontSize: 10, color: "#000033"},
                titleTextStyle: {italic: false, fontName: "Sarabun", color: "#000033"}
            },
            vAxis: {
                title: "จำนวนใบสมัคร (ใบ)",
                textStyle: {fontSize: 10, color: "#000033"},
                titleTextStyle: {italic: false, fontName: "Sarabun", color: "#000033"}
            },
            annotations: {textStyle: {color: "#000033", fontSize: 10}},
            backgroundColor: "#FFFAFA",
            chartArea: {width: "90%", height: "70%"},
            legend: {position: "none"}
        };


        chart1 = new google.visualization.ColumnChart(document.getElementById("resume_chart"));
        chart1.draw(data, options);

         // Redraw chart on window resize
         if (!isChart1ListenerAdded) {
        window.addEventListener("resize", debounce(() => {
            chart1.draw(data, options);
        }, 200));
        isChart1ListenerAdded = true;
        }



    }
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart1);
    


</script>
<!-- Chart2 -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">


  let isChart2ListenerAdded = false;  
  let chart2;
  
  function drawChart2() {
    // แปลงเดือนจากภาษาอังกฤษเป็นภาษาไทย
    var monthNamesTh = {
      "Jan": "มกราคม",
      "Feb": "กุมภาพันธ์",
      "Mar": "มีนาคม",
      "Apr": "เมษายน",
      "May": "พฤษภาคม",
      "Jun": "มิถุนายน",
      "Jul": "กรกฎาคม",
      "Aug": "สิงหาคม",
      "Sep": "กันยายน",
      "Oct": "ตุลาคม",
      "Nov": "พฤศจิกายน",
      "Dec": "ธันวาคม"
    };

    // ดึงข้อมูลจาก input หรือค่าใน PHP ที่ได้มา
    var jan1 = <?php echo $row5['Jan']; ?>;
    var feb1 = <?php echo $row5['Feb']; ?>;
    var mar1 = <?php echo $row5['Mar']; ?>;
    var apr1 = <?php echo $row5['Apr']; ?>;
    var may1 = <?php echo $row5['May']; ?>;
    var jun1 = <?php echo $row5['Jun']; ?>;
    var jul1 = <?php echo $row5['Jul']; ?>;
    var aug1 = <?php echo $row5['Aug']; ?>;
    var sep1 = <?php echo $row5['Sep']; ?>;
    var oct1 = <?php echo $row5['Oct']; ?>;
    var nov1 = <?php echo $row5['Nov']; ?>;
    var dec1 = <?php echo $row5['Dec']; ?>;

    var jan2 = <?php echo $row6['Jan']; ?>;
    var feb2 = <?php echo $row6['Feb']; ?>;
    var mar2 = <?php echo $row6['Mar']; ?>;
    var apr2 = <?php echo $row6['Apr']; ?>;
    var may2 = <?php echo $row6['May']; ?>;
    var jun2 = <?php echo $row6['Jun']; ?>;
    var jul2 = <?php echo $row6['Jul']; ?>;
    var aug2 = <?php echo $row6['Aug']; ?>;
    var sep2 = <?php echo $row6['Sep']; ?>;
    var oct2 = <?php echo $row6['Oct']; ?>;
    var nov2 = <?php echo $row6['Nov']; ?>;
    var dec2 = <?php echo $row6['Dec']; ?>;

    var jan3 = <?php echo $row7['Jan']; ?>;
    var feb3 = <?php echo $row7['Feb']; ?>;
    var mar3 = <?php echo $row7['Mar']; ?>;
    var apr3 = <?php echo $row7['Apr']; ?>;
    var may3 = <?php echo $row7['May']; ?>;
    var jun3 = <?php echo $row7['Jun']; ?>;
    var jul3 = <?php echo $row7['Jul']; ?>;
    var aug3 = <?php echo $row7['Aug']; ?>;
    var sep3 = <?php echo $row7['Sep']; ?>;
    var oct3 = <?php echo $row7['Oct']; ?>;
    var nov3 = <?php echo $row7['Nov']; ?>;
    var dec3 = <?php echo $row7['Dec']; ?>;

    // สร้างข้อมูลให้กับ Google Chart
    var data = google.visualization.arrayToDataTable([
      ['เดือน', 'เรียกสัมภาษณ์', 'ผ่านสัมภาษณ์', 'รับเข้าทำงาน'],
      [monthNamesTh["Jan"], jan1, jan2, jan3],
      [monthNamesTh["Feb"], feb1, feb2, feb3],
      [monthNamesTh["Mar"], mar1, mar2, mar3],
      [monthNamesTh["Apr"], apr1, apr2, apr3],
      [monthNamesTh["May"], may1, may2, may3],
      [monthNamesTh["Jun"], jun1, jun2, jun3],
      [monthNamesTh["Jul"], jul1, jul2, jul3],
      [monthNamesTh["Aug"], aug1, aug2, aug3],
      [monthNamesTh["Sep"], sep1, sep2, sep3],
      [monthNamesTh["Oct"], oct1, oct2, oct3],
      [monthNamesTh["Nov"], nov1, nov2, nov3],
      [monthNamesTh["Dec"], dec1, dec2, dec3],
    ]);

    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
                     { calc: "stringify",
                       sourceColumn: 1,
                       type: "string",
                       role: "annotation"},
                     2, { calc: "stringify",
                         sourceColumn: 2,
                         type: "string",
                         role: "annotation" },
                     3, { calc: "stringify",
                         sourceColumn: 3,
                         type: "string",
                         role: "annotation" },
    ]);

    const date = new Date(); 
    const title = date.getFullYear()+543;

    var options = {
      titleTextStyle: {fontSize: 13, fontName: 'Sarabun', color: "#000033"},
      title: "แสดงจำนวนเรียกสัมภาษณ์ ผ่านสัมภาษณ์ และ รับเข้าทำงาน แต่ละเดือน ในปี " + title,
      bar: {groupWidth: "70%"},
      legend: { 
        position: 'top', 
        textStyle: { 
          fontName: 'Sarabun', 
          color: "#000033", 
          fontSize: 12, 
        }
      },
      annotations: { textStyle: { fontSize: 10} },
      hAxis: {
        title: 'เดือน', 
        textStyle: {fontSize: 10, color: "#000033"}, 
        titleTextStyle: {italic: false, fontName: 'Sarabun', color: "#000033"}
      },
      vAxis: {
        title: 'จำนวน (คน)', 
        textStyle: {fontSize: 10, color: "#000033"}, 
        titleTextStyle: {italic: false, fontName: 'Sarabun', color: "#000033"}
      },
      backgroundColor: '#FFFAFA',
      width: '100%',
      height: '320',
      chartArea: {
        left: 35,
        top: 45,
        right: 10,
        width: '100%',
        height: '235',
      }
    };

    chart2 = new google.visualization.ColumnChart(document.getElementById("interview_chart"));
    chart2.draw(data, options);

    if (!isChart2ListenerAdded) {
        window.addEventListener("resize", debounce(() => {
            chart2.draw(data, options);
        }, 200));
        isChart2ListenerAdded = true;
    }



  };

    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart2);


    // google.charts.load('current', {'packages':['corechart', 'bar']});
    // google.charts.setOnLoadCallback(drawChart2);

</script>
<!-- Chart3 -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

    let isChart3ListenerAdded = false;
    let chart3;


  function drawChart3() {
    // ดึงข้อมูล PHP ที่แปลงเป็น JSON
    const rawData = <?php echo json_encode($data); ?>;

    // แปลงชื่อเดือนภาษาอังกฤษเป็นภาษาไทย
    const monthMap = {
      January: 'มกราคม',
      February: 'กุมภาพันธ์',
      March: 'มีนาคม',
      April: 'เมษายน',
      May: 'พฤษภาคม',
      June: 'มิถุนายน',
      July: 'กรกฎาคม',
      August: 'สิงหาคม',
      September: 'กันยายน',
      October: 'ตุลาคม',
      November: 'พฤศจิกายน',
      December: 'ธันวาคม'
    };

    // จัดรูปแบบข้อมูลสำหรับ Google Chart
    const formattedData = [['เดือน', 'เว็บไซต์บริษัทฯ', { role: 'annotation' }, 
                            'Facebook บริษัทฯ', { role: 'annotation' }, 
                            'กรมจัดหางาน', { role: 'annotation' }, 
                            'ญาติพี่น้อง/เพื่อน', { role: 'annotation' }, 
                            'เว็บไซต์ท้องถิ่น', { role: 'annotation' }]];

    Object.keys(monthMap).forEach(month => {
      const thaiMonth = monthMap[month];
      const row = [thaiMonth];
      rawData.forEach(source => {
        const value = source[month.slice(0, 3)] || 0; // ใช้ Jan, Feb, ฯลฯ
        row.push(value, getAnnotation(value)); // ใช้ฟังก์ชัน getAnnotation
      });
      formattedData.push(row);
    });

    const data = google.visualization.arrayToDataTable(formattedData);

    // กำหนดปีปัจจุบันแบบ พ.ศ.
    const date = new Date();
    const title = date.getFullYear() + 543;

    const options = {
      title: 'ช่องทางการรับสมัครงานในแต่ละเดือน ในปี ' + title,
      titleTextStyle: {
        color: '#000033',
        fontName: 'Sarabun',
        fontSize: 13
      },
      isStacked: true,
      hAxis: {
        title: 'เดือน',
        textStyle: { fontSize: 10, color: "#000033" },
        titleTextStyle: { italic: false, fontName: 'Sarabun', color: "#000033" }
      },
      vAxis: {
        title: 'จำนวนช่องทางรับสมัครงาน',
        textStyle: { fontSize: 10, color: "#000033" },
        titleTextStyle: { italic: false, fontName: 'Sarabun', color: "#000033" }
      },
      legend: {
        position: 'top',
        maxLines: 3,
        textStyle: { fontSize: 12, fontName: 'Sarabun', color: '#000033' }
      },
      colors: ['#3366cc', '#dc3912', '#ff9900', '#109618', '#990099'],
      annotations: {
        alwaysOutside: false,
        textStyle: {
          fontSize: 10,
          color: '#ffffff',
          auraColor: 'none'
        }
      },
      backgroundColor: '#FFFAFA',
      width: '100%',
      height: '320',
      chartArea: {
        left: 35,
        top: 45,
        right: 10,
        width: '100%',
        height: '240',
      },
      bar: { groupWidth: '70%' }
    };

    const chart = new google.visualization.ColumnChart(
      document.getElementById('new_chart')
    );

    // chart.draw(data, options);

    // // // ปรับขนาดกราฟเมื่อหน้าต่างเปลี่ยนขนาด
    // // window.onresize = () => {
    // //   chart.draw(data, options);
    // // };

    chart3 = new google.visualization.ColumnChart(document.getElementById("new_chart"));
    chart3.draw(data, options);

    if (!isChart3ListenerAdded) {
        window.addEventListener("resize", debounce(() => {
            chart3.draw(data, options);
        }, 200));
        isChart3ListenerAdded = true;
    }

  }



  // Helper function to conditionally show annotations
  function getAnnotation(value) {
    return value > 1 ? value.toString() : null; // Return annotation only if value > 1
  }


    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart3);


</script>


<script type="text/javascript">
//   ลดความถี่ในการเรียก resize google chart
function debounce(func, delay) {
    let timeout;
    return function () {
        clearTimeout(timeout);
        timeout = setTimeout(func, delay);
    };
}


</script>