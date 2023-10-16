<html>
<head>
  <title>Book-O-Rama Search Results</title>
</head>
<body>
<h1>Book-O-Rama Search Results</h1>
<?php
  // create short variable names
  $searchtype=$_POST['searchtype'];
  $searchterm=trim($_POST['searchterm']);

  if (!$searchtype || !$searchterm) {
     echo 'You have not entered search details.  Please go back and try again.';
     exit;
  }
// $magic = get_magic_quotes_gpc();
// var_dump($magic);
// echo "<br>magic: ".$magic."<br>";
//   if (!get_magic_quotes_gpc()){
//     $searchtype = addslashes($searchtype);
//     $searchterm = addslashes($searchterm);
//   }

  $servername = "localhost";  
  $username = "root";

  // Create connection
  $conn = new mysqli($servername, $username, '', 'myuser');

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $query = "SELECT * FROM books WHERE ".$searchtype." LIKE '".$searchterm."%'";
  $result = mysqli_query($conn, $query);
  $num_results = mysqli_num_rows($result);

  echo "<p>Number of books found: ".$num_results."</p>";

  if (mysqli_num_rows($result) > 0) {
    // output data of each row
    while($row = mysqli_fetch_assoc($result)) {
      echo "isbn: " . $row["isbn"]. " - Author: " . $row["author"].  " - Title: " . $row["title"]. "<br>";
    }
  } else {
    echo "0 results";
  }


  $result->free();
  $db->close();

?>
</body>
</html>
