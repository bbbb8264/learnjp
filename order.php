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
	$sql = "SELECT * FROM learnjp.shop ORDER BY serial DESC LIMIT 1";
	$result = $conn->query($sql);
	$store = $result->fetch_assoc();
	$currentstore = $store['serial'];
?>
<html>
	<head>
		<title>弁当オーダーシステム</title>
		<link rel="stylesheet" type="text/css" href="UI-Icon-master/icon.min.css">
		<link rel="stylesheet" type="text/css" href="UI-Loader-master/loader.min.css">
		<link rel="stylesheet" type="text/css" href="UI-Input-master/input.min.css">
		<link rel="stylesheet" type="text/css" href="order.css">
		<script src="jquery-3.2.1.min.js"></script>
		<script>
			$(document).ready(function(){
				$("#order-submit div").click(submitOrder);
				$("#amount-input").on('input',trimNotNumberChar);
				$("#totalprice-input").on('input',trimNotNumberChar);
			});
			function trimNotNumberChar(){
				this.value = this.value.toString().replace("e", "");
				this.value = this.value.toString().replace(".", "");
			}
			function submitOrder(){
				var button = this;
				$(button).unbind("click");
				this.innerHTML = "<div class=\"ui tiny active centered inline loader\"></div>";
				$(button).addClass("disabled");
				var shopserial = $(this).data("shop");
				var name = $("#name-input")[0].value;
				var food = $("#food-input")[0].value;
				var amount = $("#amount-input")[0].value;
				var totalprice = $("#totalprice-input")[0].value;
				$(button).removeClass("disabled");
				$(button).click(submitOrder);
				button.innerHTML = "Submit Order";
				$.post( "addorder.php", {shopserial: shopserial, name: name, food: food , amount: amount, totalprice: totalprice}).done(function(data) {
					if(data.length > 7){
						if(data.substring(0, 7) == "Success"){
							var orderserial = parseInt(data.substring(8));
							var noorder = $(".noorder");
							if(noorder.length != 0){
								noorder.remove();
							}
							var orders = $(".order");
							var orderdiv = document.createElement("div");
							orderdiv.className = "order removeable";
							var orderNamediv = document.createElement("div");
							orderNamediv.className = "order-name";
							orderNamediv.innerHTML = name;
							var orderFooddiv = document.createElement("div");
							orderFooddiv.className = "order-food";
							orderFooddiv.innerHTML = food;
							var orderAmountdiv = document.createElement("div");
							orderAmountdiv.className = "order-amount";
							orderAmountdiv.innerHTML = amount;
							var orderTotalpricediv = document.createElement("div");
							orderTotalpricediv.className = "order-totalprice";
							orderTotalpricediv.innerHTML = totalprice;
							var orderRemoveButton = document.createElement("div");
							orderRemoveButton.className = "order-removebutton";
							var removeicon = document.createElement("i");
							removeicon.className = "remove icon";
							$(removeicon).data("orderserial", orderserial);
							$(removeicon).click(function(){
								var removebutton = this;
								if (confirm("Cancel this order?") == true) {
									$.post( "removeorder.php", {orderserial: orderserial}).done(function(data) {
										if(data.length == 7 && data == "Success"){
											$(removebutton).parent().parent().remove();	
											refreshTotalPrice();									
										}
									});
								}
							});
							orderRemoveButton.append(removeicon);
							var orderRemoveHint = document.createElement("div");
							orderRemoveHint.innerHTML = "(You can cancel order before leaving webpage)";
							orderdiv.append(orderRemoveButton);
							orderdiv.append(orderNamediv);
							orderdiv.append(orderFooddiv);
							orderdiv.append(orderAmountdiv);
							orderdiv.append(orderTotalpricediv);
							orderdiv.append(orderRemoveHint);
							orders[orders.length-1].after(orderdiv);
							refreshTotalPrice();
							$("#name-input")[0].value = "";
							$("#food-input")[0].value = "";
							$("#amount-input")[0].value = "";
							$("#totalprice-input")[0].value = "";
						}
					}
				});
			}
			function refreshTotalPrice(){
				var prices = $(".order-totalprice");
				var total = 0;
				for(var i = 1;i < prices.length;i++){
					total += parseInt(prices[i].innerHTML);
				}
				$("#totalprice").html("Total price: " + total);
			}
		</script>
	</head>
	<body>
		<div id="main">
			<div id="menu">
				<div class="menu-description" style="text-align: center;">
					Today's Store
				</div>
				<div class="menu-description">
					<?php
						echo "Store Name:<span>" . $store['name'] . "</span>";
					?>
				</div>
				<div class="menu-description">
					<?php
						echo "Meal Date: <span>" . $store['eatDate'] . "</span>";
					?>
				</div>
				<div class="menu-description">
					<?php
						echo "Order Deadline: <span>" . substr($store['deadline'], 0, -3) . "</span>";
					?>
				</div>
				<div class="menu-description">
					<?php
						echo "Person in charge: <span>" . $store['PersonInCharge'] . "</span>";
					?>
				</div>
				<?php
					echo '<a href="menus/' . $store['imagename'] . '" style="text-decoration:none;" target="_blank">';
				?>
				<div id="menu-title">
					Today's Menu
				</div>
				<div id="menuimg">
					<?php
						echo '<img src="menus/' . $store['imagename'] . '">';
					?>
					<br>(Click to enlarge image in another page)
				</div>
				<?php
					echo '</a>';
				?>
			</div>
			<div id="orderview">
				<div id="order-list">
					<div id="order-title">
						Current orders
					</div>
					<div class="order">
						<div class="order-name">
							Name
						</div>
		    			<div class="order-food">
		    				Food
		    			</div>
		    			<div class="order-amount">
		    				Amount
		    			</div>
		    			<div class="order-totalprice">
		    				Total Price
		    			</div>
					</div>
					<?php
						$sql = "SELECT * FROM learnjp.foodorder WHERE shopserial=".$currentstore." ORDER BY time ASC";
						$result = $conn->query($sql);
						$totalprice = 0;
						if ($result->num_rows > 0) {
						    while($row = $result->fetch_assoc()) {
						    	$totalprice += $row['totalprice'];
						    	echo '	<div class="order">
							    			<div class="order-name">
							    				'. $row['name'] .'
							    			</div>
							    			<div class="order-food">
							    				'. $row['dish'] .'
							    			</div>
							    			<div class="order-amount">
							    				'. $row['amount'] .'
							    			</div>
							    			<div class="order-totalprice">
							    				'. $row['totalprice'] .'
							    			</div>
						    			</div>';
						    }
						}else{
							echo '<div class="order noorder">There is no order now.</div>';
						}
					?>
					<div id="totalprice">
						<?php echo 'Total price: ' . $totalprice; ?>
					</div>
				</div>
				<div id="order-input">
					<div id="order-input-title">
						Order Form
					</div>
					<div id="order-input-name">
						<div class="order-input-caption">
							Name:
						</div>
						<div class="ui input order-input-input">
							<input id="name-input">
						</div>
					</div>
					<div id="order-input-food">
						<div class="order-input-caption">
							Food:
						</div>
						<div class="ui input order-input-input">
							<input id="food-input">
						</div>
					</div>
					<div id="order-input-amount">
						<div class="order-input-caption">
							Amount:
						</div>
						<div class="ui input order-input-input">
							<input id="amount-input" type="number">
						</div>
					</div>
					<div id="order-input-totalprice">
						<div class="order-input-caption">
							Total Price:
						</div>
						<div class="ui input order-input-input">
							<input id="totalprice-input" type="number">
						</div>
					</div>
					<div id="order-submit">
						<div id="order-submit-text" data-shop="<?php echo $store['serial']; ?>">
							Submit Order
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
	$conn->close();
?>