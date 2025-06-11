<?php
session_start();
ob_start();

// clear session
unset($_SESSION['ses_username']); 
unset($_SESSION['ses_password']);
unset($_SESSION['ses_name']); 
unset($_SESSION['ses_branch']);

session_destroy(); // ทำลาย session
session_unset();

echo "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=index.php\">";

?>