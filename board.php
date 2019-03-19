<html>
<head><title>Message Board</title>
</head>
<body>
  <center><h3>Message Board</h3></center>

<form action = 'board.php' method='get'>
    <label>Enter The Post:</label>
    <textarea rows="4" cols="50" name="textarea" id="textarea"></textarea>
    <input type="submit" name="New Post" value="New_Post">
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');
$dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();

if(isset($_GET['textarea'])){
  try {
    $username = $_SESSION["username"];
    $messageId = uniqid();
    $postedBy = $username;
    $_SESSION['message'] = $_GET["textarea"];
    $replyto = "Null";
    if(isset(($_GET['replyto'])))
      $replyto = $_GET['replyto'];

      $stmt = $dbh->prepare('INSERT INTO POSTS VALUES(?, ?, ?, NOW(), ?)');
      $stmt->execute([$messageId, $replyto, $postedBy, $_SESSION['message']]);
      $dbh->commit();
      print "</pre>";
  }
 catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . "<br/>";
  die();
}
}
?>
  <?php 
  $stmt = $dbh->prepare('select * from posts inner join users where posts.postedby= users.username order by datetime DESC');
  $stmt->execute();
  echo "<table border=1 style='width:100%'>";
  echo "<tr>";
  echo "<th> Message Id </th>";
  echo "<th> Username </th>";
  echo "<th> Fullname </th>";
  echo "<th> Date and Time </th>";
  echo "<th> Reply to</th>";
  echo "<th> Message Text </th>";
  echo "<th> Reply </th>";
  echo "</tr>";
  while ($row = $stmt->fetch()) {
    echo "<tr>";
    echo "<td>";
    echo  $row[0];
    echo "</td>";
    echo "<td>";
    echo  $row[5];
    echo "</td>";
    echo "<td>";
    echo  $row[7];
    echo "</td>";
    echo "<td>";
    echo $row[3];
    echo "</td>";
    echo "<td>";
    echo $row[1];
    echo "</td>";
    echo "<td>";
    echo $row[4];
    echo "</td>";
    echo "<td>";
    echo "<button value='".$row[0]."' type='submit' name='replyto'>Reply</button></td></tr>";
  }
  echo "</table>";
  ?>
</form>
<form action = 'login.php' method='get'>

    <input type="submit" name="Logout" value="Logout">
</html>