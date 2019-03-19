<html>
<head><title>Message board Login</title></head>
<body>
  <center><h3>Message Board</h3></center>
<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','On');

if (isset($_GET['login'])) {
  $user_name = $_POST['Username'];
  $password = $_POST['password'];
  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $dbh->beginTransaction();
  $stmt = $dbh->prepare('select * from users where username="'.$user_name.'" and password="' . md5($password) . '"');
  $stmt->execute();
  $user_response = $stmt->fetch()[0];
  $pass_response = $stmt->fetch()[1];
  if($user_response == $user_name ){
    $_SESSION["username"] = $user_name;
    header("Location:board.php?username='".$user_name."'");
  }
  else{
    print ("enter again");
  }
}
  
?>

<form action = 'login.php?login=True' method='post'>
    <label>Enter Username:</label>
    <input type="text" name="Username" required></input>
    <label>Password:</label>
    <input type="Password" name="password" required></input>
    <input type="submit" name="submit" value="Login">
  </form>
</body>
</html>
