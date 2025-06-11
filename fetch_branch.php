<?php
include 'db_connect.php'; // Include your database connection

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Fetch branches based on sub_branch
    if ($action == 'fetch_branch' && isset($_POST['sub_branch'])) {
        $sub_branch = $_POST['sub_branch'];

        // Query to fetch branches based on selected sub_branch
        $sql = "SELECT DISTINCT rtrim(branch) as branch, case when branch = 'SRT' then 'สาขาสุราษฎร์ธานี'
                    when branch = 'HDY' then 'สาขาหาดใหญ่'
                    when branch = 'CHM' then 'สาขาเชียงใหม่'
                    else '' end as branchname  
                FROM jobs_require WHERE sub_branch = ? AND job_status = 'Y' ORDER BY branch ASC";
        $params = array($sub_branch);
        $query = sqlsrv_query($conn, $sql, $params);

        if ($query === false) {
            echo 'Error in SQL query';
            die(print_r(sqlsrv_errors(), true));
        }

        // Generate the branch dropdown options dynamically
        echo '<option selected disabled value="">--เลือก--</option>'; // Default option
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            echo '<option value="' . $row['branch'] . '">' . $row['branchname'] . '</option>';
        }
    }

    // Fetch job names (positions) and job_no based on sub_branch and branch
    elseif ($action == 'fetch_job_name' && isset($_POST['sub_branch']) && isset($_POST['branch'])) {
        $sub_branch = $_POST['sub_branch'];
        $branch = $_POST['branch'];

        // Query to fetch job positions and job_no based on selected sub_branch and branch
        $sql = "SELECT job_no, position FROM jobs_require WHERE sub_branch = ? AND branch = ? AND job_status = 'Y' ORDER BY position ASC";
        $params = array($sub_branch, $branch);
        $query = sqlsrv_query($conn, $sql, $params);

        if ($query === false) {
            echo 'Error in SQL query: ';
            die(print_r(sqlsrv_errors(), true)); // Print SQL error if query fails
        }

        // Generate the job_name dropdown options dynamically
        echo '<option selected disabled value="">--เลือก--</option>'; // Default option
        while ($row = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {
            // Set the value as 'job_no' and display the 'position' as the job_name in the dropdown
            // echo '<option value="' . $row['job_no'] . '">' . $row['position'] . '</option>';
            echo "<option value='" . htmlspecialchars(trim($row['job_no'])) . "'>" . htmlspecialchars($row['position']) . "</option>";

        }
        
    }
}
?>
