<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $education = $_POST['education'];
    $workHistory = $_POST['workHistory'];
    $familyInfo = $_POST['familyInfo'];
    $skills = $_POST['skills'];
    $abilities = $_POST['abilities'];

    // Validate and process the data here
    // For demonstration, we'll just echo the data
    echo "First Name: " . htmlspecialchars($firstName) . "<br>";
    echo "Last Name: " . htmlspecialchars($lastName) . "<br>";
    echo "Education: " . htmlspecialchars($education) . "<br>";
    echo "Work History: " . htmlspecialchars($workHistory) . "<br>";
    echo "Family Info: " . htmlspecialchars($familyInfo) . "<br>";
    echo "Skills: " . htmlspecialchars($skills) . "<br>";
    echo "Abilities: " . htmlspecialchars($abilities) . "<br>";
}
?>
