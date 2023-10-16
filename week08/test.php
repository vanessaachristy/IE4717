<?php
$servername = "localhost";
$username = "root";

// Create connection
$conn = new mysqli($servername, $username, '', 'myuser');

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br>";

$sql = "SELECT * FROM books";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
  // output data of each row
  while($row = mysqli_fetch_assoc($result)) {
    echo "isbn: " . $row["isbn"]. " - Author: " . $row["author"].  " - Title: " . $row["title"]. "<br>";
  }
} else {
  echo "0 results";
}

mysqli_close($conn);
?>