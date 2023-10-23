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
$currentDate = date("Y-m-d");

if($_SERVER["REQUEST_METHOD"] == "POST") {
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
    echo '<script>console.log("'.$reportCategory.'")</script>';
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
AND r.Date = '".$currentDate."'
GROUP BY
    pv.ID, pv.ProductID, p.Name, pv.Variant";

$dailyReportData = $conn -> query($fetchDailyReportDataQuery);

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
AND r.Date = '".$currentDate."'
GROUP BY
    pv.ProductID, p.Name";

$summaryData = $conn -> query($fetchSummaryQuery);

// mysqli_close($conn);


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
                    <form id="reportContentForm" method="GET" action="report-content.php">
                        <?php 
                    if ($isRevenue) {
                        echo '<h1>Total dollar sales by products</h1>';
                    }
                    if ($isQuantity) {
                        echo '<h1>Sales quantities by product categories</h1>';
                    }
                    ?>
                        <span class="date">
                            <input type="date" id="datepicker" name="datepicker" max="<?=$today ?>"
                                value="<?= $currentDate ?>" onchange="onDateChange()"></span>
                        <div>
                            <?php
                            $fetchQuery = "SELECT * FROM Report WHERE Date = '$currentDate'";
                            $reportData = $conn->query($fetchQuery);
                            if ($reportData) { // Check if the query was successful
                                while ($row = $reportData->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . $row["ProductVariantID"] . '</td>';
                                echo '<td>' . $row["QuantitySold"] . '</td>';
                                echo '<td>' . $row["Revenue"] . '</td>';
                                echo '</tr>';
                            }
                            };
                            ?>
                            <tr>
                                <td>
                                    Just Java
                                </td>
                                <td>
                                    Endless Cup
                                </td>
                                <td>
                                    $11.0
                                </td>
                                <td>
                                    $11.0
                                </td>
                            </tr>
                            </table> -->
                            <?php

                        if ($isRevenue) {
                            echo '<input type="hidden" name="reportCategory" value="revenue"/>';
                             $summaryData = $conn -> query($fetchSummaryQuery);
                             $totalAllRevenue = 0;

                        if ($dailyReportData && $summaryData) { // Check if the query was successful
                            echo '<table border="1">';
                            echo '<tr>';
                            echo '<th>Product</th>';
                            echo '<th colspan="2">Product Items</th>'; // "Product Items" spans two cells
                            echo '<th>Subtotal</th>';
                            echo '</tr>';
                            
                            while ($summaryRow = mysqli_fetch_assoc($summaryData)) {
                                if ($summaryRow["ProductID"] == 2 || $summaryRow["ProductID"] == 3) {
                                    echo '<tr>';
                                    echo '<td rowspan="2">' . $summaryRow["ProductName"] . '</td>';
                                     $dailyReportData = $conn -> query($fetchDailyReportDataQuery);
                                    while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                        if ($row["ProductID"] === $summaryRow["ProductID"] && ($row["ProductVariantID"] == 2 || $row["ProductVariantID"] == 4)) {
                                            echo '<td>' . $row["Variant"] . '</td>';
                                                if (isset($row["TotalRevenue"])) {
                                                        echo '<td>' . $row["TotalRevenue"] . '</td>'; 
                                                    } else {
                                                        echo '<td>$0</td>';
                                                    };
                                        };
                                    };
                                    echo '<td rowspan="2">' . $summaryRow["TotalRevenue"] . '</td>'; 
                                    $totalAllRevenue += $summaryRow["TotalRevenue"];
                                    echo '</tr>';
                                    echo '<tr>';
                                    $dailyReportData = $conn -> query($fetchDailyReportDataQuery);
                                    while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                    if ($row["ProductID"] === $summaryRow["ProductID"] && ($row["ProductVariantID"] == 3 || $row["ProductVariantID"] == 5)) {
                                        echo '<td>' . $row["Variant"] . '</td>';
                                            if (isset($row["TotalRevenue"])) {
                                                    echo '<td>' . $row["TotalRevenue"] . '</td>'; 
                                                } else {
                                                    echo '<td>$0</td>';
                                            }
                                        }
                                    };
                                    echo '</tr>';
                                } else {
                                    echo '<tr>';
                                    echo '<td>' . $summaryRow["ProductName"] . '</td>';
                                     $dailyReportData = $conn -> query($fetchDailyReportDataQuery);
                                    while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                    if ($row["ProductID"] === $summaryRow["ProductID"]) {
                                        echo '<td>' . $row["Variant"] . '</td>';
                                        if (isset($row["TotalRevenue"])) {
                                            echo '<td>' . $row["TotalRevenue"] . '</td>'; 
                                        } else {
                                            echo '<td>$0</td>';
                                        }
                                        }
                                    }
                                    echo '<td>' . $summaryRow["TotalRevenue"] . '</td>'; 
                                    $totalAllRevenue += $summaryRow["TotalRevenue"];
                                    echo '</tr>';
                                };
                            }
                            echo '<tfoot>';
                            echo '<tr>';
                            echo '<td colspan=3>Total</td>';
                            echo '<td>'. $totalAllRevenue .'</td>';
                            echo '</tr>';
                            echo '</table>';
                        }
                            else {
                            echo "Error: " . $conn->error;
                        }
                        } 
                        else if ($isQuantity) {
                            echo '<input type="hidden" name="reportCategory" value="quantity"/>';
                             $summaryData = $conn -> query($fetchSummaryQuery);
                            if ($summaryData) { // Check if the query was successful
                                
                                echo '<table border="1">';
                                echo '<tr>';
                                echo '<th>Category</th>';
                                echo '<th>Endless Cup</th>'; // "Product Items" spans two cells
                                echo '<th>Single</th>';
                                echo '<th>Double</th>';
                                echo '<th>Subtotal</th>';
                                echo '</tr>';

                                $totalQtyEach = [0, 0, 0];

                                 while ($summaryRow = mysqli_fetch_assoc($summaryData)) {
                                    echo '<tr>';
                                    echo '<td>'.$summaryRow["ProductName"].'</td>';
                                    $dailyReportData = $conn -> query($fetchDailyReportDataQuery);
                                    $ii = 1;
                                    while ($ii<=3) {
                                        $dailyReportData = $conn -> query($fetchDailyReportDataQuery);
                                        while ($row = mysqli_fetch_assoc($dailyReportData)) {
                                            if ($row["ProductID"] == $summaryRow["ProductID"]) {
                                                if ($row["ProductVariantID"] == $ii) {
                                                    $totalQtyEach[$summaryRow["ProductID"]-1] += $row["TotalQuantitySold"];
                                                    echo '<td>' . $row["TotalQuantitySold"] . '</td>';
                                                    echo '<script>console.log("'.$ii.'-'.$summaryRow["ProductName"].'")</script>';
                                                    $ii++;
                                                    // echo '<script>console.log('.$ii.''.$row["ProductVariantID"].')</script>';
                                                } else if (($ii >= 2 && $row["ProductVariantID"] == $ii+2)) {
                                                     $totalQtyEach[$summaryRow["ProductID"]-1] += $row["TotalQuantitySold"];
                                                    echo '<td>' . $row["TotalQuantitySold"] . '</td>';
                                                    echo '<script>console.log("'.$ii.'-'.$summaryRow["ProductName"].'")</script>';
                                                    $ii++;
                                                }
                                            }
                                        }
                                        if ($ii<=3) {
                                            echo '<td>/</td>';
                                        }                                        
                                        $ii++;
                                    }
                                    echo '<td>'.$totalQtyEach[$summaryRow["ProductID"]-1].'</td>';
                                    echo '</tr>';
                                 }
                                 echo '<tr>';
                                 echo '<td>Total</td>';
                                 echo '<script>console.log("'.count($totalQtyEach).'")</script>';
                                 for ($jj=0; $jj<count($totalQtyEach); $jj++) {
                                    echo '<td>'.$totalQtyEach[$jj].'</td>';
                                };
                                $totalSubtotal = array_sum($totalQtyEach);
                                echo '<td>'.$totalSubtotal.'</td>';
                                echo '</tr>';

                               

                                echo '</table>';
                                }
                            } else {
                                echo 'No data.';
                            }
                       
                        ?>
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