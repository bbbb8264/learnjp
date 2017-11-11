<?php
	if(isset($_POST['name']) && isset($_POST['date']) && isset($_POST['deadline']) && isset($_POST['person']) && isset($_FILES['img'])){
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
		$sql = "insert into learnjp.shop (name, imagename, eatDate, deadline, PersonInCharge) values ('". $_POST['name'] ."', '', '". $_POST['date'] ."', '". $_POST['deadline'] ."', '". $_POST['person'] ."')";
		$conn->query($sql);
		echo $conn->error;
		$shopserial = $conn->insert_id;
		$filename = $shopserial . "." . pathinfo($_FILES["img"]["name"], PATHINFO_EXTENSION);
		move_uploaded_file($_FILES["img"]["tmp_name"], "menus/".$filename);
		$sql = "update learnjp.shop set imagename='". $filename ."' where serial=". $shopserial;
		$conn->query($sql);
		$conn->close();
		header('Location: order.php');
	}
?>
<html>
	<head>
		<title>お店を変える</title>
		<script src="jquery-3.2.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#imginput").change(function() {
					if (this.files && this.files[0]) {
						var reader = new FileReader();
						console.log(123);
						reader.onload = function(e) {
							console.log(123);
							$('#imgpreview').attr('src', e.target.result);
						}
						reader.readAsDataURL(this.files[0]);
					}
				});
			});
		</script>
		<style>
			#imgpreview{
				max-width: 500px;
				max-height: 500px;
			}
		</style>
	</head>
	<body>
		<form action="changestore.php" method="post" enctype="multipart/form-data">
			Store Name:<input name="name"><br>
			Meal Date:<input name="date" type="date"><br>
			Order Deadline:<input name="deadline" type="datetime-local"><br>
			Person in charge:<input name="person"><br>
			Menu picture:<input name="img" id="imginput" type="file"><br>
			<img id="imgpreview"><br>
			<input type="submit" value="submit">
		</form>
	</body>
</html>