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
    <div class="section-header"><i class="fa-solid fa-address-book fa-lg txt_icon"></i><span class="txt_toppic"> ดูใบสมัครงาน</span></div>
    <div class="section-body">
        <div class="box-div">
            <form class="needs-validation" novalidate id="resume" name="resume" action="resume_receives.php" method="post">
                <div class="row">
                    <div class="col-lg-1"></div>
                    <div class="col-md-4" style="margin-bottom: 5px; position: relative;">
                        <label for="from_date" class="form-label"><b>จากวันที่</b></label>
                        <input  type="date" class="form-control bd_input" id="from_date"name="from_date"placeholder="เลือก จากวันที่" required/>
                        <div class="invalid-feedback">กรุณาเลือก จากวันที่</div>
                    </div>
                    <div class="col-md-4" style="margin-bottom: 5px; position: relative;">
                        <label for="to_date" class="form-label"><b>ถึงวันที่</b></label>
                        <input type="date" class="form-control bd_input" id="to_date" name="to_date" placeholder="เลือก ถึงวันที่" required/>
                        <div class="invalid-feedback">กรุณาเลือก ถึงวันที่</div>
                    </div>
                    <div class="col-lg-2" style="margin-bottom: 5px; position: relative;">
                        <div class="form-group" style="padding-top: 28px; padding-bottom: 0px;">
                        <button type="submit" name="search" id="search" class="btn btn-primary" >
                            <i class="fa fa-search" aria-hidden="true"></i>&nbsp;ค้นหาข้อมูล
                        </button>
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    </div>
            </form>
        </div>
        <br>
        <div class="table-responsive" style="border: none;">
        <?php
		if(isset($_POST["from_date"])){
		 $from_date = $_POST["from_date"];
		}else{
		 $from_date = " ";
		}

		if(isset($_POST["to_date"])){
		 $to_date = $_POST["to_date"];
		}else{
		 $to_date = " ";
		}

         if($from_date ==" " && $to_date = " "){
		  $strSQL="select guest_no,title+name as name,nickname,age,sex,job_name,salary,format(rec_time_stamp,'dd/MM/')+convert(varchar,year(rec_time_stamp)+543) as create_date ,
                                CASE 
                                    WHEN agree_data IS NULL THEN 'ไม่เรียบร้อย' 
                                    WHEN agree_data = 'ยินยอม' THEN 'กรอกเรียบร้อย' 
                                    ELSE 'ไม่ระบุ' 
                                END AS status 
		         from resume_job where branch_job = ? and month(rec_time_stamp) = month(getdate()) and year(rec_time_stamp) = year(getdate()) 
				 order by guest_no desc";
		 }else{
		  $strSQL="select guest_no,title+name as name,nickname,age,sex,job_name,salary,format(rec_time_stamp,'dd/MM/')+convert(varchar,year(rec_time_stamp)+543) as create_date,
                                CASE 
                                    WHEN agree_data IS NULL THEN 'ไม่เรียบร้อย' 
                                    WHEN agree_data = 'ยินยอม' THEN 'กรอกเรียบร้อย' 
                                    ELSE 'ไม่ระบุ' 
                                END AS status 
		         from resume_job where branch_job = ? and convert(date,rec_time_stamp,111) between ? and ? order by guest_no desc";
		 }
		 $branch = 	$_SESSION['ses_branch'];
		 $params = array($branch,$from_date,$to_date);
		 $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		 $Query = sqlsrv_query($conn,$strSQL, $params, $options);
		 $numRows = sqlsrv_num_rows( $Query );
		 $i=0;

		?>
            <table id="table">
		        <thead>
                    <tr>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">#</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">เลขที่ผู้สมัคร</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">ชื่อ-นามสกุล</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">ชื่อเล่น</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">อายุ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">เพศ</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">ตำแหน่งสมัคร</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">เงินเดือน</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">วันที่ส่งใบสมัคร</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">ดูเพิ่ม</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1" style="text-align: center;">แก้ไข</th>
                        <th class="text-nowrap bg-primary bg-gradient py-1">สถานะใบสมัคร</th>
                    </tr>

		        </thead>
		        <tbody>
                <?php
                while($row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC)){
                $i=$i+1;

                if (stripos($row['age'], 'ปี')) {$age = $row['age'];}else{$age = $row['age']." ปี";}
                if (stripos($row['salary'], ',')) {$salary = $row['salary'];}else{$salary = number_format($row['salary']);}

                $button_view = "<button type=\"button"."\" class=\"button_view button_h"."\" onclick=\"popWin('".$row["guest_no"]."')"."\"><i class='fa-solid fa-eye'></i>&nbsp;ดูข้อมูล</button>";
                $edit_data = "<button type=\"button"."\" class=\"button_edit button_h"."\" onclick=\"popWinEdit('".$row["guest_no"]."')"."\"><i class='fa-solid fa-edit'></i>&nbsp;แก้ไข</button>";    
                    echo "<tr class=\"tr"."\" >";
                    echo "<td align='center'>".$i."</td>";
                    echo "<td>".$row["guest_no"]."</td>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["nickname"]."</td>";	
                    echo "<td>".$age."</td>";
                    echo "<td>".$row["sex"]."</td>";
                    echo "<td>".$row["job_name"]."</td>";
                    echo "<td>".$salary."</td>";
                    echo "<td>".$row["create_date"]."</td>";
                    echo "<td align='center'>".$button_view."</td>"; 
                    echo "<td align='center'>".$edit_data."</td>"; 
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
        <br>
    </div>       
<!--------------------------->
</div>

</body>
</html>