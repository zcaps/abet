
<!doctype html>
<html>
<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL|E_STRICT);
require 'userFuncs/loginFunc.php';
?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>index</title>
  <link rel="stylesheet" href="css/standardize.css">
  <link rel="stylesheet" href="css/index-grid.css">
  <link rel="stylesheet" href="css/index.css">
</head>
<body class="body page-index clearfix">
  <header class="_container clearfix">
    <img class="abetlogo abetlogo-1" src="images/abet_logo.png">
  </header>
  <?php
      if(isset($_POST["username"]) && isset($_POST["password"])){
        $login = new Login();
        $result = $login->CheckInfo($_POST["username"], $_POST["password"]);
        echo $result;
        if($result == "<p style='text-align:center;'>Login Succeeded</p>"){
          echo '<script>window.location.href="results.php"</script>';
        }else{
          echo '<div class="login-container clearfix">
        <div class="facultylogin">Faculty Login</div>
        <form action="login.php" method="POST">
        <input name="username" class="username" placeholder="Username" type="text">
        <input name="password" class="password" placeholder="Password" type="text">
        <input type="submit" class="loginbtn" value="Login">
        </form>
      </div>';
        }
        
      }else{
        echo '<div class="login-container clearfix">
        <div class="facultylogin">Faculty Login</div>
        <form action="login.php" method="POST">
        <input name="username" class="username" placeholder="Username" type="text">
        <input name="password" class="password" placeholder="Password" type="text">
        <input type="submit" class="loginbtn" value="Login">
        </form>
      </div>';
      }
  ?>
  
</body>
</html>