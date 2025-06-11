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
    <div class="section-header"><i class="fa-solid fa-folder-open fa-lg txt_icon"></i><span class="txt_toppic"> ตำแหน่งงานว่าง</span></div>
    <div class="section-body">
        <div class="table-responsive" style="border: none;">
	    <?php
		 $strSQL="select *,format(annoudate,'dd/MM/')+convert(varchar,year(annoudate)+543) as post_date from jobs_require where branch = ? and job_status='Y' order by job_no desc";
			
		 $params = [$_SESSION['ses_branch']];
		 $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		 $Query = sqlsrv_query($conn,$strSQL, $params, $options);
		 $numRows = sqlsrv_num_rows( $Query );
		 $i=0;
		?>
            <table id="table">
		        <thead>
			        <tr>
			            <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">#</th>
			            <th class="text-nowrap bg-primary bg-gradient py-1">รหัส</th>
			            <th class="text-nowrap bg-primary bg-gradient py-1">ตำแหน่ง</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">จำนวนที่รับ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">เงินเดือน</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">เพศ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">อายุ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">วันที่ลงประกาศ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">ดูเพิ่ม</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">แก้ไข</th>
                    </tr>
		        </thead>
		        <tbody>
                <?php
                while($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)){
                $i=$i+1;

                if (stripos($row['age'], 'ปี')) {$age = $row['age'];}else{$age = $row['age']." ปี";}

                $button_view = "<button type=\"button"."\" class=\"button_view button_h"."\" onclick='popview(\"" . $row["job_no"] . "\")'><i class='fa-solid fa-eye'></i>&nbsp;ดูข้อมูล</button>";
                $button_edit = "<button type=\"button"."\" class=\"button_edit button_e"."\" onclick='popedit(\"" . $row["job_no"] . "\")'><i class='fa-solid fa-pen-to-square'></i>&nbsp;แก้ไข</button>";

                    echo "<tr class=\"tr"."\" >";
                    echo "<td align='center'>".$i."</td>";
                    echo "<td>".$row["job_no"]."</td>";
                    echo "<td>".$row["position"]."</td>";
                    echo "<td>".$row["require_no"]." ตำแหน่ง</td>";	
                    echo "<td>".$row["salary"]."</td>";
                    echo "<td>".$row["gender"]."</td>";
                    echo "<td>".$age."</td>";
                    echo "<td align='center'>".$row["post_date"]."</td>";
                    echo "<td align='center'>".$button_view."</td>"; 
                    echo "<td align='center'>".$button_edit."</td>";
                    echo "</tr>";
                }
                ?>
		        </tbody>	
	        </table>
        </div>
        <br>
        <div class="col-lg-12" align="center">
	        <div class="form-group style_mt">
		      <button type="button" class="btn btn-success" onclick="popadd()"><i class="fa-solid fa-folder-plus fa-lg"></i>&nbsp;เพิ่มตำแหน่งงานว่าง</button>
		    </div>
	    </div>
    </div>       
<!--------------------------->
</div>

</body>
</html>