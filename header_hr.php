<?php
session_start();
ob_start();
?>
<!-- TOP NAVIGATOR BAR -->
<nav class="navbar navbar-expand-sm navbar-custom fixed-top" >
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
	    <img src="images/LOGO_ORNATE1.png" alt="ornlogi" style="width:60px;" class="rounded-pill"><span style="color: white; font-weight: bold; text-shadow: 1px 1px 1px #000033;"> JOB ORNATE</span>
	</a>
    <?php 
      if(isset($_SESSION['ses_username'])){
    ?>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center" id="mynavbar" >
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="dashboard.php"><button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-chart-pie fa-lg" style="color: #7FFFD4;"></i> Dashboard</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="post_ornjobs.php"><button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-folder-open fa-lg" style="color: #7FFFD4;"></i> ตำแหน่งงานว่าง</button></a>
        </li>
		    <li class="nav-item">
          <a class="nav-link" href="resume_receives.php"><button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-address-book fa-lg" style="color: #7FFFD4;"></i> ดูใบสมัครงาน</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="interview_receives.php"><button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-user-tag fa-lg" style="color: #7FFFD4;"></i> เรียกสัมภาษณ์งาน</button></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="logout.php"><button class="btn btn-primary btn-sm" type="button"><i class="fa-solid fa-right-from-bracket fa-lg" style="color: #FF3333;"></i> ออกจากระบบ</button></a>
        </li>
      </ul>
    </div> 
    <?php }else{} ?>
  </div>
</nav>
<!--END TOP NAVIGATOR BAR -->