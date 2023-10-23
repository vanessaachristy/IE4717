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

$today = date("Y-m-d");


$report = mysqli_query($conn, "SELECT * FROM report WHERE DATE='$today'");


mysqli_close($conn);


?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>JavaJam Coffee House</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="index.css" />
        <link rel="stylesheet" href="report.css" />
    </head>

    <body>
        <div class="container">
            <header>
                <img src="assets/javalogo.png">
            </header>
            <div class="main">
                <nav>
                    <a href="index.html">Home</a>
                    <a href="menu.php">Menu</a>
                    <a href="music.html">Music</a>
                    <a href="jobs.html">Jobs</a>
                    <a href="update.php">Price</a>
                </nav>
                <div class="report-content">
                    <h3>Click to generate daily sales report</h3>
                    <form id="reportForm" class="report-options" action="report-content.php" method="POST">
                        <span class="date"><label for="datepicker">Select a Date:</label>
                            <input type="date" id="datepicker" name="datepicker" max="<?=$today ?>" value="<?=$today?>"
                                required></span>
                        <div class="option">
                            <input id="revenueCheckbox" type="checkbox" onchange="onCheckboxClicked('revenueCheckbox')"
                                name="revenue" />Total dollar sales by
                            product items
                        </div>
                        <div class="option">
                            <input id="quantityCheckbox" type="checkbox"
                                onchange="onCheckboxClicked('quantityCheckbox')" name="quantity" />Sales quantities by
                            product
                            categories
                        </div>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
        </script>
        <script type="text/javascript" src="report.js"></script>

    </body>

</html>