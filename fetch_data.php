<?php
include 'db_connect.php'; // Include your database connection
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_POST['action'])){
    $action = $_POST['action'];

    if($action == 'fetch_districts' && isset($_POST['province'])){
        $province = $_POST['province'];
        
        // Fetch districts based on selected province
        $strSQL = "SELECT DISTINCT districtthai FROM Province_Detail WHERE provincethai = ? ORDER BY districtthai";
        $params = array($province);
        $query = sqlsrv_query($conn, $strSQL, $params);

        echo '<option selected disabled value="">--เลือก--</option>';
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
            echo '<option value="'.$row['districtthai'].'">'.$row['districtthai'].'</option>';
        }
    }
    
    if($action == 'fetch_tumbons' && isset($_POST['province']) && isset($_POST['district'])){
        $province = $_POST['province'];
        $district = $_POST['district'];
        
        // Fetch tumbons based on selected province and district
        $strSQL = "SELECT DISTINCT tumbonthai FROM Province_Detail WHERE provincethai = ? AND districtthai = ? ORDER BY tumbonthai";
        $params = array($province, $district);
        $query = sqlsrv_query($conn, $strSQL, $params);

        echo '<option selected disabled value="">--เลือก--</option>';
        while($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
            echo '<option value="'.$row['tumbonthai'].'">'.$row['tumbonthai'].'</option>';
        }
    }

    if($action == 'fetch_zipcode' && isset($_POST['province']) && isset($_POST['district']) && isset($_POST['tumbon'])){
        $province = $_POST['province'];
        $district = $_POST['district'];
        $tumbon = $_POST['tumbon'];
        
        // Fetch zipcode based on selected province, district, and tumbon
        $strSQL = "SELECT zipcode FROM Province_Detail WHERE provincethai = ? AND districtthai = ? AND tumbonthai = ?";
        $params = array($province, $district, $tumbon);
        $query = sqlsrv_query($conn, $strSQL, $params);

        if($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)){
            echo $row['zipcode'];
        } else {
            echo ''; // Return empty if no zipcode found
        }
    }
    

    

}
?>
