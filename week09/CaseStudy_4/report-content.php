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
echo '<script>console.log("Connected Successfully.")</script>';


$isRevenue = false;
$isQuantity = false;
$todayDate = date("Y-m-d");
$currentDate = date("Y-m-d");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["revenue"])) {
        $isRevenue = true;
    }
    if (isset($_POST["quantity"])) {
        $isQuantity = true;
    }
    if (isset($_POST["datepicker"])) {
        $currentDate = $_POST["datepicker"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $currentDate = $_GET["datepicker"];
    $reportCategory = $_GET["reportCategory"];
    $isRevenue = $reportCategory === 'revenue';
    $isQuantity = $reportCategory === 'quantity';
    echo '<script>console.log("' . $reportCategory . '")</script>';
}

$fetchDailyReportDataQuery = "SELECT
    pv.ID AS ProductVariantID,
    pv.ProductID,
    p.Name AS ProductName,
    pv.Variant,
    SUM(COALESCE(r.QuantitySold, 0)) AS TotalQuantitySold,
    SUM(COALESCE(r.Revenue, 0)) AS TotalRevenue
FROM
    ProductVariant pv
JOIN
    Product p ON pv.ProductID = p.ID
LEFT JOIN
    Report r ON pv.ID = r.ProductVariantID
AND r.Date = '" . $currentDate . "'
GROUP BY
    pv.ID, pv.ProductID, p.Name, pv.Variant";

$dailyReportData = $conn->query($fetchDailyReportDataQuery);

$fetchSummaryQuery = "SELECT
    pv.ProductID,
    p.Name AS ProductName,
    SUM(COALESCE(r.QuantitySold, 0)) AS TotalQuantitySold,
    SUM(COALESCE(r.Revenue, 0)) AS TotalRevenue
FROM
    ProductVariant pv
JOIN
    Product p ON pv.ProductID = p.ID
LEFT JOIN
    Report r ON pv.ID = r.ProductVariantID
AND r.Date = '" . $currentDate . "'
GROUP BY
    pv.ProductID, p.Name";

$summaryData = $conn->query($fetchSummaryQuery);

// mysqli_close($conn);

$mostPopularQuery = $fetchDailyReportDataQuery . ' ORDER BY TotalRevenue DESC LIMIT 1';
$mostPopular = $conn->query($mostPopularQuery);
$mostPopularVariantID = -1;
$mostPopularRevenue = 0;
while ($row = mysqli_fetch_assoc($mostPopular)) {
    if ($row['TotalRevenue'] != 0) {
        $mostPopularVariantID = $row['ProductVariantID'];
        $mostPopularRevenue = $row['TotalRevenue'];
    }

}

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Report Content</title>
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
                    <a href="report.php">Report</a>
                </nav>
                <div class="report-content">
                    <form id="reportContentForm" method="GET" action="report-content.php">
                        <?php
                        if ($isRevenue) {
                            echo '<h1>Total dollar sales by products</h1>';
                        }
                        if ($isQuantity) {
                            echo '<h1>Sales quantities by product categories</h1>';
                        }
                        ?>

                        <div class="report-container">
                            <span class="date">
                                <input type="date" id="datepicker" name="datepicker" max="<?= $todayDate ?>"
                                    value="<?= $currentDate ?>" onchange="onDateChange()"></span>
                            <div class="data-container">
                                <?php

                                if ($isRevenue) {
                                    echo '<input type="hidden" name="reportCategory" value="revenue"/>';
                                    $summaryData = $conn->query($fetchSummaryQuery);
                                    $totalAllRevenue = 0;

                                    if ($dailyReportData && $summaryData) { // Check if the query was successful
                                        echo '<table class="revenueTable">';
                                        echo '<tr>';
                                        echo '<th>Product</th>';
                                        echo '<th colspan="2">Product Items</th>'; // "Product Items" spans two cells
                                        echo '<th>Subtotal</th>';
                                        echo '</tr>';

                                        while ($summaryRow = mysqli_fetch_assoc($summaryData)) {
                                            if ($summaryRow["ProductID"] == 2 || $summaryRow["ProductID"] == 3) {
                                                echo '<tr>';
                                                echo '<td rowspan="2" class="product-name">' . $summaryRow["ProductName"] . '</td>';
                                                $dailyReportData = $conn->query($fetchDailyReportDataQuery);
                                                while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                                    if ($row["ProductID"] === $summaryRow["ProductID"] && ($row["ProductVariantID"] == 2 || $row["ProductVariantID"] == 4)) {
                                                        echo '<td class="variant ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . '">' . $row["Variant"] . '</td>';

                                                        if (isset($row["TotalRevenue"])) {
                                                            echo '<td class="revenue ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . '">$' . $row["TotalRevenue"] . '</td>';
                                                        } else {
                                                            echo '<td class="revenue">$0</td>';
                                                        }
                                                        ;
                                                    }
                                                    ;
                                                }
                                                ;
                                                echo '<td rowspan="2" class="subtotal">$' . $summaryRow["TotalRevenue"] . '</td>';
                                                $totalAllRevenue += $summaryRow["TotalRevenue"];
                                                echo '</tr>';
                                                echo '<tr>';
                                                $dailyReportData = $conn->query($fetchDailyReportDataQuery);
                                                while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                                    if ($row["ProductID"] === $summaryRow["ProductID"] && ($row["ProductVariantID"] == 3 || $row["ProductVariantID"] == 5)) {
                                                        echo '<td class="variant ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . '">' . $row["Variant"] . '</td>';
                                                        if (isset($row["TotalRevenue"])) {
                                                            echo '<td class="revenue ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . ' ">$' . $row["TotalRevenue"] . '</td>';
                                                        } else {
                                                            echo '<td class="revenue">$0</td>';
                                                        }
                                                    }
                                                }
                                                ;
                                                echo '</tr>';
                                            } else {
                                                echo '<tr>';
                                                echo '<td class="product-name">' . $summaryRow["ProductName"] . '</td>';
                                                $dailyReportData = $conn->query($fetchDailyReportDataQuery);
                                                while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                                    if ($row["ProductID"] === $summaryRow["ProductID"]) {
                                                        echo '<td class="variant ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . ' ">' . $row["Variant"] . '</td>';
                                                        if (isset($row["TotalRevenue"])) {
                                                            echo '<td class="revenue ' . (($row["ProductVariantID"] == $mostPopularVariantID) ? "popular" : '') . ' ">$' . $row["TotalRevenue"] . '</td>';
                                                        } else {
                                                            echo '<td class="revenue">$0</td>';
                                                        }
                                                    }
                                                }
                                                echo '<td class="subtotal">$' . $summaryRow["TotalRevenue"] . '</td>';
                                                $totalAllRevenue += $summaryRow["TotalRevenue"];
                                                echo '</tr>';
                                            }
                                            ;
                                        }
                                        echo '<tfoot>';
                                        echo '<tr>';
                                        echo '<td colspan=3 class="total">Total</td>';
                                        echo '<td class="total">$' . $totalAllRevenue . '</td>';
                                        echo '</tr>';
                                        echo '</table>';
                                    } else {
                                        echo "Error: " . $conn->error;
                                    }
                                } else if ($isQuantity) {
                                    echo '<input type="hidden" name="reportCategory" value="quantity"/>';
                                    $summaryData = $conn->query($fetchSummaryQuery);
                                    if ($summaryData) { // Check if the query was successful
                                
                                        echo '<table class="quantityTable">';
                                        echo '<tr>';
                                        echo '<th>Category</th>';
                                        echo '<th class="variant">Endless Cup</th>'; // "Product Items" spans two cells
                                        echo '<th class="variant">Single</th>';
                                        echo '<th class="variant">Double</th>';
                                        echo '<th>Subtotal</th>';
                                        echo '</tr>';

                                        $totalQtyEach = [0, 0, 0];

                                        while ($summaryRow = mysqli_fetch_assoc($summaryData)) {
                                            echo '<tr>';
                                            echo '<td class="product-name">' . $summaryRow["ProductName"] . '</td>';
                                            $dailyReportData = $conn->query($fetchDailyReportDataQuery);
                                            $ii = 1;
                                            while ($ii <= 3) {
                                                $dailyReportData = $conn->query($fetchDailyReportDataQuery);
                                                while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                                    if ($row["ProductID"] == $summaryRow["ProductID"]) {
                                                        if ($row["ProductVariantID"] == $ii) {
                                                            $totalQtyEach[$summaryRow["ProductID"] - 1] += $row["TotalQuantitySold"];
                                                            echo '<td class="quantity">' . $row["TotalQuantitySold"] . '</td>';
                                                            $ii++;
                                                            // echo '<script>console.log('.$ii.''.$row["ProductVariantID"].')</script>';
                                                        } else if (($ii >= 2 && $row["ProductVariantID"] == $ii + 2)) {
                                                            $totalQtyEach[$summaryRow["ProductID"] - 1] += $row["TotalQuantitySold"];
                                                            echo '<td class="quantity">' . $row["TotalQuantitySold"] . '</td>';
                                                            $ii++;
                                                        }
                                                    }
                                                }
                                                if ($ii <= 3) {
                                                    echo '<td>/</td>';
                                                }
                                                $ii++;
                                            }
                                            echo '<td class="subtotal">' . $totalQtyEach[$summaryRow["ProductID"] - 1] . '</td>';
                                            echo '</tr>';
                                        }
                                        echo '<tr>';
                                        echo '<td class="total">Total</td>';
                                        for ($jj = 0; $jj < count($totalQtyEach); $jj++) {
                                            echo '<td>' . $totalQtyEach[$jj] . '</td>';
                                        }
                                        ;
                                        $totalSubtotal = array_sum($totalQtyEach);
                                        echo '<td class="total">' . $totalSubtotal . '</td>';
                                        echo '</tr>';
                                        echo '</table>';
                                    }
                                } else {
                                    echo 'No data.';
                                }
                                ?>
                            </div>
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
        <script type="text/javascript" src="report-content.js"></script>

    </body>

</html>