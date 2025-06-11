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
    <div class="section-header"><i class="fa-solid fa-user-tag fa-lg txt_icon"></i><span class="txt_toppic"> เรียกสัมภาษณ์งาน</span></div>
    <div class="section-body">

      <div class="card shadow mt-0 mb-2">
            <div class="card-header bg-primary text-white">
                <h7><i class="fa-solid fa-file-circle-exclamation fa-lg"></i> ใบสมัครที่เรียกสัมภาษณ์</h7>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    $strSQL = "select * from resume_job where branch_job = ? and interviews = 'เรียกสัมภาษณ์' and 
                               (selected  is null or selected ='') and year(rec_time_stamp) = year(getdate()) order by guest_no desc";

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
                                <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">สถานะ</th>
                                <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">บันทึกผล</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        while ($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)) {
                            $i++;

                            if (stripos($row['age'], 'ปี')) {$age = $row['age'];}else{$age = $row['age']." ปี";}
                            if (stripos($row['salary'], ',')) {$salary = $row['salary'];}else{$salary = number_format($row['salary']);}
                            
                            $button_view = "<button type='button' class='button_view button_h' onclick='popsave(\"" . $row["guest_no"] . "\")'><i class=\"fa-solid fa-floppy-disk"."\"></i>&nbsp;บันทึก</button>";
                            $button_status = "<button type=\"button"."\" class=\"button_status"."\" disabled>เรียกสัมภาษณ์</button>";

                            echo "<tr>";
                            echo "<td align='center'>$i</td>";
                            echo "<td>" . $row["guest_no"] . "</td>";
                            echo "<td>" . $row["title"].$row["name"] . "</td>";
                            echo "<td>" . $row["nickname"] . "</td>";
                            echo "<td>" . $age . "</td>";
                            echo "<td>" . $row["sex"] . "</td>";
                            echo "<td>" . $row["job_name"] . "</td>";
                            echo "<td>" . $salary . "</td>";
                            echo "<td align='center'>$button_status</td>";
                            echo "<td align='center'>$button_view</td>";
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
        
        <div class="card shadow mt-3 mb-2">
            <div class="card-header bg-primary text-white">
                <h7><i class="fa-solid fa-file-circle-check fa-lg"></i> ใบสมัครที่ผ่านสัมภาษณ์</h7>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <?php
                    $strSQL = "select * from resume_job where branch_job = ? and interviews = 'เรียกสัมภาษณ์' and selected ='ผ่าน' 
                    and year(rec_time_stamp) = year(getdate()) order by guest_no desc";

                    $params = [$_SESSION['ses_branch']];
                    $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
                    $Query = sqlsrv_query($conn, $strSQL, $params, $options);
                    $numRows = sqlsrv_num_rows($Query);
                    ?>

                    <table id="table1" class="table table-bordered table-striped">
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
                                <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        while ($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)) {
                            $i++;

                            if (stripos($row['age'], 'ปี')) {$age = $row['age'];}else{$age = $row['age']." ปี";}
                            if (stripos($row['salary'], ',')) {$salary = $row['salary'];}else{$salary = number_format($row['salary']);}
                          
                            $button_status = "<button type=\"button"."\" class=\"button_pass"."\" disabled>ผ่านสัมภาษณ์</button>";

                            echo "<tr>";
                            echo "<td align='center'>$i</td>";
                            echo "<td>" . $row["guest_no"] . "</td>";
                            echo "<td>" . $row["title"].$row["name"] . "</td>";
                            echo "<td>" . $row["nickname"] . "</td>";
                            echo "<td>" . $age . "</td>";
                            echo "<td>" . $row["sex"] . "</td>";
                            echo "<td>" . $row["job_name"] . "</td>";
                            echo "<td>" . $salary . "</td>";
                            echo "<td align='center'>$button_status</td>";
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
</div>

</body>
</html>