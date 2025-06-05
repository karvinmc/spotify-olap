<?php
$db_server= "localhost";
$db_user= "root";
$db_pass= "root"; // your password
$db_name= "spotify";

$conn = null;

try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    echo "❌ Could not connect! " . $e->getMessage();
}
?>
