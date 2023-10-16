<?php
$servername = "localhost";
$username = "root";
$dbname = "javajam";

// Create connection
$conn = new mysqli($servername, $username, '', $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";


if($_SERVER["REQUEST_METHOD"] == "POST") {
			for ($ii = 0; $ii < count($_POST["price"]); $ii++) {
				$prices[$ii] = sanitize($_POST["price"][$ii]);
				echo '<script>console.log(' . $prices[$ii] . ')</script>';
				$conn->query("UPDATE `ProductVariant` SET `Price` = " . $prices[$ii] . " WHERE . `ProductVariant`.`ID` = " . ($ii+1));
			}
			echo '<script>console.log("Database update success.")</script>';
		} else {
		echo '<script>console.log("Database update failed.")</script>';
		}

/**
 * Sanitize
 */
function sanitize($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

mysqli_close($conn);

?>

<!DOCTYPE html>
<html lang="en">

	<head>
		<title>JavaJam Coffee House</title>
		<meta charset="UTF-8" />
		<link rel="stylesheet" href="index.css" />
		<link rel="stylesheet" href="update.css" />
	</head>

	<body>
		<div class="container">
			<header>
				<img src="assets/javalogo.png">
			</header>
			<div class="main">
				<nav>
					<a href="index.html">Home</a>
					<a href="menu.html">Menu</a>
					<a href="music.html">Music</a>
					<a href="jobs.html">Jobs</a>
				</nav>
				<div class="menu-content">
					<h1>Coffee at JavaJam</h1>
					<form id="product-update" method="POST" action="update.php">
						<h3>Select to update product prices</h3>
						<?php
								$servername = "localhost";
								$username = "root";
								$dbname = "javajam";

								// Create connection
								$conn = new mysqli($servername, $username, '', $dbname);

								// Check connection
								if ($conn->connect_error) {
								die("Connection failed: " . $conn->connect_error);
								}
								// echo "Connected successfully <br>";
								// $products = mysqli_query($conn, "SELECT * FROM product");

								// while($row = mysqli_fetch_assoc($products)) {
								// 	echo '
								// 	<tr>
								// 		<td>
								// 			<b>'.$row['Name'].'</b>
								// 		</td>
								// 	</tr>';
								// };
								
							?>
						<table>
							<?php
								$products = mysqli_query($conn, "SELECT * FROM product");

								while($row = mysqli_fetch_assoc($products)) {
									$variants = mysqli_query($conn, "SELECT * FROM ProductVariant WHERE ProductID=" . $row["ID"]);
									echo '
									<tr id=product-'.$row['ID'].'>
										<td>
											<input type="checkbox" name="update-toggle" autocomplete="off" onclick="toggleInput('.$row['ID'].')">
										</td>
										<td>
											<b>'.$row['Name'].'</b>
										</td>
										<td>
													'.$row['Description'].'<br />';
												
										while ($variant_row = mysqli_fetch_assoc($variants)) {
											echo '
											<span class="variants">
												'.$variant_row['Variant'].'
												<b id="price-static"> '.$variant_row['Price'].'</b>
												<input id="price-input" size="" type="text" min="0" name="price[]" autocomplete="off" onkeyup="handlePriceChange('.$row['ID'].')" value='.$variant_row['Price'].'>
												<p>Invalid input!</p>
											</span><br/>';
										}
										echo '
										</td>
									</tr>';
								};
								
							?>
						</table>
						<button type='submit' onclick="return handleSubmit()">Update Prices</button>
					</form>

				</div>
			</div>

			<footer>
				<small><i>Copyright &copy2014 JavaJam Coffee House</i></small>
				<br />
				<small>
					<a href="mailto:vanessa.chandra2002@gmail.com"><i>vanessachristy@chandra.com</i>
					</a>
				</small>
			</footer>
		</div>
		  <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
    </script>
		<script type="text/javascript" src="update.js"></script>

	</body>

</html>