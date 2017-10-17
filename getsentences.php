<?php
$servername = "localhost";
$username = "id3281060_user";
$password = "12345";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$sql = "SELECT * FROM id3281060_db.sentences";
$result = $conn->query($sql);
$arr = array();
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
    	array_push($arr, $row);
    }
}
$conn->close();
$arr = json_encode($arr);
echo $arr;

?>