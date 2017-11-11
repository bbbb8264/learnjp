<?php
$servername = "localhost";
//$username = "id3281060_user";
//$password = "12345";
$username = "root";
$password = "1234";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//$sql = "SELECT * FROM id3281060_db.sentences";
$sql = "DELETE FROM learnjp.foodorder WHERE orderserial=" . $_POST['orderserial'];
$conn->query($sql);
echo $conn->error;
echo 'Success';
$conn->close();
?>