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


$var1 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=1");
$var2 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=2");
$var3 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=3");
$var4 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=4");
$var5 = mysqli_query($conn, "SELECT * FROM productvariant WHERE ID=5");

while ($row = mysqli_fetch_assoc($var1)) {
    $price1 = $row['Price'];
}
;
while ($row = mysqli_fetch_assoc($var2)) {
    $price2 = $row['Price'];
}
;
while ($row = mysqli_fetch_assoc($var3)) {
    $price3 = $row['Price'];
}
;
while ($row = mysqli_fetch_assoc($var4)) {
    $price4 = $row['Price'];
}
;
while ($row = mysqli_fetch_assoc($var5)) {
    $price5 = $row['Price'];
}
;


$today = date("Y-m-d");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $qty = $_POST["qty"];
    $sub = $_POST["sub"];
    $chosenCafeAuLait = 2;
    $chosenCappuccino = 4;
    foreach ($_POST["cafeAuLait"] as $value) {
        $chosenCafeAuLait = $value;
    }
    foreach ($_POST["icedCappuccino"] as $value) {
        $chosenCappuccino = $value;
    }

    echo '<script>';
    for ($ii = 0; $ii < count($qty); $ii++) {
        $productVariantId = 0;
        if ($ii == 0) {
            $productVariantId = 1;
        } else if ($ii == 1) {
            $productVariantId = $chosenCafeAuLait;
        } else {
            $productVariantId = $chosenCappuccino;
        }
        $qty[$ii] = sanitize($qty[$ii]);
        $sub[$ii] = sanitize($sub[$ii]);
        echo 'console.log(' . $chosenCafeAuLait . ', ' . $chosenCappuccino . ');';
        echo 'console.log(' . $qty[$ii] . ', ' . $ii . ', ' . $sub[$ii] . ');';
        echo 'console.log(' . $productVariantId . ');';
        $query = "INSERT INTO Report (ProductVariantID, QuantitySold, Revenue, Date) VALUES (" . $productVariantId . ", " . $qty[$ii] . ", " . $sub[$ii] . ", '" . $today . "')";
        $conn->query($query);
    }
    echo 'console.log("Database update success.");';
    echo '</script>';
} else {
    echo '<script>console.log("Database update failed.")</script>';
}

/**
 * Sanitize
 */
function sanitize($data)
{
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
        <link rel="stylesheet" href="menu.css" />
        <script type="text/javascript" src="menu.js"></script>


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
                    <a href="report.php">Report</a>
                </nav>
                <div class="menu-content">
                    <h1>Coffee at JavaJam</h1>
                    <form id="order-form" method="POST" action="menu.php">
                        <table>
                            <tr>
                                <td>
                                    <b>Just Java</b>
                                </td>
                                <td>
                                    Regular house blend, decaffeinated coffee, or flavor of the day<br />
                                    <b>Endless Cup <span id="priceJustJava">$<?= $price1 ?></span></b>
                                </td>
                                <td class="qty">
                                    <span>Quantity</span>
                                    <input type="number" min="0" value="0" name="qty[]" id="qty-just-java"></input>
                                </td>
                                <td class="subtotal">
                                    <span>Subtotal</span>

                                    <input id="subtotal-just-java" value=0 readonly name="sub[]"></input>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Cafe au lait</b>
                                </td>
                                <td>
                                    House blended coffee infused to a smooth, steamed milk.<br />
                                    <b><input type="radio" name="cafeAuLait[]" id="cafeAuLait1" value="2" checked>
                                        Single <span id="price-cafeAuLait1">$<?= $price2 ?></span>
                                        <input type="radio" name="cafeAuLait[]" id="cafeAuLait2" value="3">
                                        Double <span id="price-cafeAuLait2">$<?= $price3 ?></span<>
                                </td>
                                <td class="qty">
                                    <span>Quantity</span>
                                    <input type="number" min="0" value="0" name="qty[]" id="qty-cafe-au-lait"></input>
                                </td>
                                <td class="subtotal">
                                    <span>Subtotal</span>
                                    <input id="subtotal-cafe-au-lait" value="0" readonly name="sub[]"></input>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>Iced Cappuccino</b>
                                </td>
                                <td>
                                    Sweetened espresso blended with icy-cold milk and served in a chilled glass.<br />
                                    <b><input type="radio" name="icedCappuccino[]" id="icedCappuccino1" value="4"
                                            checked>
                                        Single <span id="price-icedCappuccino1">$<?= $price4 ?></span>
                                        <input type="radio" name="icedCappuccino[]" id="icedCappuccino2" value="5">
                                        Double <span id="price-icedCappuccino2">$<?= $price5 ?></span>
                                    </b>
                                </td>
                                <td class="qty">
                                    <span>Quantity</span>
                                    <input type="number" min="0" value="0" name="qty[]" id="qty-iced-cap"></input>
                                </td>
                                <td class="subtotal">
                                    <span>Subtotal</span>
                                    <input id="subtotal-iced-cap" value="0" readonly name="sub[]"></input>
                                </td>
                            </tr>
                            <tfoot>
                                <td class="totalContainer" colspan="4">
                                    <span>Total</span>
                                    <input id="total-order" value="$0.00" readonly>

                                </td>
                            </tfoot>

                        </table>
                        <div class="checkoutBtn">
                            <input id="checkout-btn" type="submit" value="Check out">
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

    </body>

</html>