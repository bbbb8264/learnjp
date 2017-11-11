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
$sql = "INSERT INTO learnjp.foodorder (shopserial, name, dish, amount, totalprice) VALUES (". $_POST['shopserial'] .", '". $_POST['name'] ."', '". $_POST['food'] ."', ". $_POST['amount'] .", ". $_POST['totalprice'] .")";
$conn->query($sql);
echo 'Success '. $conn->insert_id;
$conn->close();
?>