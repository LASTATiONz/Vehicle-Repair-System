<?php
session_start();
?>

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

    <!-- Bootstrap CSS -->
    <!--<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">-->
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

	<link rel="icon" href="images/LOGO_ORNATE1.png" type="image/icon type">

</head>
<body>
<div class="m-0">
<?php
//include connect database
   include 'db_connect.php';
   include 'header.php';
   include 'modle_notify.php';
//อัปเดทการเข้ามาดูตำแหน่งงาน count_views
   if(isset($_GET['job_no'])){
    $sqlview ="update jobs_require set count_views = count_views+1 where job_no ='".$_GET['job_no']."'";
    $queryview = sqlsrv_query($conn, $sqlview);
   } 
//	 
    $strSQL="select *,format(annoudate,'dd/MM/')+convert(varchar,year(annoudate)+543) as post_date from jobs_require where job_no = ? ";

    $params = [$_GET['job_no']];
    $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
    $Query = sqlsrv_query($conn,$strSQL, $params, $options);
    $numRows = sqlsrv_num_rows( $Query );
    $row = sqlsrv_fetch_array($Query, SQLSRV_FETCH_ASSOC);
?>

</div>
<div class="container mt-5 mb-4"> 
<!--Div รายละเอียดตำแหน่งงานว่าง-->
    <div class="job_headers"><span class="span_header"><i class="fa-solid fa-user-tie"></i> <?php echo "ตำแหน่ง : ".$row['position'];?></span></div>
    <div class="section-body">
        <div class="row  mb-1">
            <div class="col-md-2 style_h"><i class="fa-solid fa-user"></i> จำนวนที่รับ :</div>
		    <div class="col-md-10 style_d"><?php echo $row["require_no"]." ตำแหน่ง";?></div>
		</div>
        <hr class="style-one mt-2"> 
        <div class="row mb-1 style_row">
		    <?php 
		    if($row["salary"] != ""){
		        $salary = $row["salary"];
	        }else{
		        $salary = "ไม่ระบุ";
		    } 
            ?>
            <div class="col-md-2 style_h"><i class="fa-solid fa-baht-sign"></i> เงินเดือน :</div>
		    <div class="col-md-10 style_d"><?php echo $salary;?></div>
		</div>
        <hr class="style-one mt-2">
        <div class="row mb-1 style_row">
		    <?php 
		    if($row["job_location"] != ""){
		        $job_location = $row["job_location"];
	        }else{
		        $job_location = "ไม่ระบุ";
		    } 
            ?>
            <div class="col-md-2 style_h"><i class="fa-solid fa-house"></i> สถานที่ปฎิบัติงาน :</div>
		    <div class="col-md-10 style_d"><?php echo $job_location;?></div>
		</div>
        <hr class="style-one mt-2">
		<div class="row mb-1 style_row">
            <div class="col-md-2 style_h"><i class="fa-solid fa-user-tag"></i> รายละเอียดงาน :</div>
		    <div class="col-md-10 style_d">
		        <ul>
			    <?php 
			    if($row['job_attribute1'] != ""){ ?>
		            <li><?php echo $row['job_attribute1'];?></li>
			    <?php }else{} 
		        if($row['job_attribute2'] != ""){ ?>
			        <li><?php echo $row['job_attribute2'];?></li>
			    <?php }else{}
			    if($row['job_attribute3'] != ""){ ?>
			        <li><?php echo $row['job_attribute3'];?></li>
			    <?php }else{}
			    if($row['job_attribute4'] != ""){ ?>
			        <li><?php echo $row['job_attribute4'];?></li>
			    <?php }else{}
			    if($row['job_attribute5'] != ""){ ?>
			        <li><?php echo $row['job_attribute5'];?></li>
			    <?php }else{} ?>
		        </ul>
		    </div>
		</div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
          	<div class="col-md-2 style_h"><i class="fa-solid fa-user-graduate"></i> คุณสมบัติ :</div>
		  	<div class="col-md-10 style_d">
		    	<ul>
			  	<?php 
			  		if(isset($row['gender']) && $row['gender'] != "NULL"){ ?>
		        		<li><?php echo "เพศ ".$row['gender'];?></li>
			  		<?php }else{} 
		      		if(isset($row['age']) && $row['age'] != "NULL"){ ?>
			    		<li>
						<?php 
				 		if (strpos($row['age'], 'อายุ') !== false) { 
				    		if(strpos($row['age'], 'ปี') !== false){
				      			echo $row['age'];
							}else{
					  		echo $row['age']." ปี";
							}
				 		}else{ 
							if(strpos($row['age'], 'ปี') !== false){
					  			echo "อายุ ".$row['age'];
							}else{
					  		echo "อายุ ".$row['age']." ปี";
						} 
				 	}?></li>
			  		<?php }else{}
			  		if(isset($row['job_certificate1']) && $row['job_certificate1'] != "NULL" && $row['job_certificate1'] != ""){ ?>
			    		<li>
						<?php 
				 		if($row['job_certificate1'] == "ไม่จำกัดวุฒิฯ"){
			       			if($row['major'] == ""){
				    			echo $row['job_certificate1'];
				   			}else{
				    			echo $row['job_certificate1']." ".$row['major'];
				   			}
			     		}else{
				   			if($row['major'] == ""){
				    			echo "วุฒิการศึกษา ".$row['job_certificate1'];
				   			}else{
				    			echo "วุฒิการศึกษา ".$row['job_certificate1']." สาขา".$row['major'];
				   			}
				 		}
			    		?></li>
			  		<?php }else{}
			  			if(isset($row['job_certificate2']) && $row['job_certificate2'] != "NULL" && $row['job_certificate2'] != ""){ ?>
			    			<li><?php echo $row['job_certificate2'];?></li>
			  			<?php }else{}
			  			if(isset($row['job_certificate3']) && $row['job_certificate3'] != "NULL" && $row['job_certificate3'] != ""){ ?>
			    			<li><?php echo $row['job_certificate3'];?></li>
			  			<?php }else{}
			  			if(isset($row['job_certificate4']) && $row['job_certificate4'] != "NULL" && $row['job_certificate4'] != ""){ ?>
			    			<li><?php echo $row['job_certificate4'];?></li>
			  			<?php }else{}
			  			if(isset($row['job_certificate5']) && $row['job_certificate5'] != "NULL" && $row['job_certificate5'] != ""){ ?>
			    			<li><?php echo $row['job_certificate5'];?></li>
			  			<?php }else{}
			  			if(isset($row['job_certificate6']) && $row['job_certificate6'] != "NULL" && $row['job_certificate6'] != ""){ ?>
			    		<li><?php echo $row['job_certificate6'];?></li>
			  			<?php }else{} 
			  		?>
		    	</ul>
		  	</div>
		</div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
          	<div class="col-md-2 style_h"><i class="fa-solid fa-user-check"></i> สวัสดิการพนักงาน :</div>
		  	<div class="col-md-10 style_d">
				<ul>
			  	<?php 
			  	if(isset($row['benefits1']) && $row['benefits1'] != "NULL" && $row['benefits1'] != ""){ ?>
		        	<li><?php echo $row['benefits1'];?></li>
			  	<?php }else{} 
		      	if(isset($row['benefits2']) && $row['benefits2'] != "NULL" && $row['benefits2'] != ""){ ?>
			    	<li><?php echo $row['benefits2'];?></li>
			  	<?php }else{}
			  	if(isset($row['benefits3']) && $row['benefits3'] != "NULL" && $row['benefits3'] != ""){ ?>
			    	<li><?php echo $row['benefits3'];?></li>
			  	<?php }else{}
			  	if(isset($row['benefits4']) && $row['benefits4'] != "NULL" && $row['benefits4'] != ""){ ?>
			    	<li><?php echo $row['benefits4'];?></li>
			  	<?php }else{}
			  	if(isset($row['benefits5']) && $row['benefits5'] != "NULL" && $row['benefits5'] != ""){ ?>
			    	<li><?php echo $row['benefits5'];?></li>
			  	<?php }else{} 
			  	if(isset($row['benefits6']) && $row['benefits6'] != "NULL" && $row['benefits6'] != ""){ ?>
			    	<li><?php echo $row['benefits6'];?></li>
			  	<?php }else{} 
			  	if(isset($row['benefits7']) && $row['benefits7'] != "NULL" && $row['benefits7'] != ""){ ?>
			    	<li><?php echo $row['benefits7'];?></li>
			  	<?php }else{} 
			  	if(isset($row['benefits8']) && $row['benefits8'] != "NULL" && $row['benefits8'] != ""){ ?>
			    	<li><?php echo $row['benefits8'];?></li>
			  	<?php }else{}
			  	if(isset($row['benefits9']) && $row['benefits9'] != "NULL" && $row['benefits9'] != ""){ ?>
			    	<li><?php echo $row['benefits9'];?></li>
			  	<?php }else{}
			  	?>
		    	</ul>
		  	</div>
		</div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
          	<div class="col-md-2 style_h"><i class="fa-brands fa-square-whatsapp"></i> ติดต่อสอบถาม :</div>
		  	<div class="col-md-10 style_d">
		    	<ul>
			  	<?php
			  	if(trim($row['branch']) == "SRT"){
		        	$address = "เลขที่ 31/237 กานจนวิธี 16 ถนนสุราษฎร์-นครศรีธรรมราช ตำบลบางกุ้ง อำเภอเมืองสุราษฎร์ธานี จังหวัดสุราษฎร์ธานี 84000";
					$sub_b = "(สำนักงานใหญ่)";
					$line_id ="<a href=\"https://line.me/ti/p/NKzv6seCSM"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
		      	}else if (trim($row['branch']) == "HDY"){
		        	$address = "เลขที่ 671 หมู่ที่ 3 ถนนเพชรเกษม ตำบลควนลัง อำเภอหาดใหญ่ จังหวัดสงขลา 90110";
					$sub_b = "(สาขาหาดใหญ่)";
					$line_id ="<a href=\"https://line.me/ti/p/~hrornhdy"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
		      	}else if (trim($row['branch']) == "CHM"){
		        	$address = "เลขที่ 323 หมู่ที่ 1 ตำบลหนองหอย อำเภอเมืองเชียงใหม่ จังหวัดเชียงใหม่ 50000";
					$sub_b = "(สาขาเชียงใหม่)";
					$line_id ="<a href=\"https://line.me/ti/p/~0813702662"."\" target=\"_blank"."\">เพิ่มเพื่อน</a>";
		      	}else{}
			  	?>
			  		<li><?php echo $row['branch_name']." ".$sub_b."<br>".$address;?></li>
			  	<?php
		      	if(isset($row['hrcontact']) && $row['hrcontact'] != "NULL"){?>
			    	<li><?php echo "ฝ่ายบุคคล ".$row['hrcontact'];?></li>
			  	<?php }else{}
			  	if(isset($row['hrcontact2']) && $row['hrcontact2'] != "NULL"){ ?>
			    	<li><?php echo "อีเมล ".$row['hrcontact2'];?></li>
			  	<?php }else{}
			  	?>
			  		<li><?php echo "Line ID : ".$line_id;?></li>
		    	</ul>
		  	</div>
		</div>
        <hr class="style-one style_mt">
        <div class="row mb-1 style_row">
          	<div class="col-md-2 style_h"><i class="fa-solid fa-calendar-days"></i> วันที่ประกาศ :</div>
		  	<div class="col-md-10 style_d"><?php echo $row["post_date"];?></div>
		</div>
        <hr class="style-one mt-2">
        <div class="row mt-1 style_row">
          	<div class="col-md-12 style_h">
		    	<button class="btn btn-primary btn-sm" type="button" onclick="location.href='resume_ornategroup.php?job_no=<?php echo $row['job_no'];?>';"><i class="fa-solid fa-file-pen"></i> สมัครงาน</button>&nbsp;
		    	<button class="btn btn-secondary btn-sm" type="button" style="color: white;" onclick="location.href='home_job.php'"><i class="fa-solid fa-reply-all"></i> กลับหน้าตำแหน่งงานว่าง</button>
		  	</div>
		</div>	
    </div>
<!--------------------------->
</div>  
</body>
</html>