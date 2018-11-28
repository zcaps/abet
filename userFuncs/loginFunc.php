<?php
require_once(__DIR__.'/../DB/db.php');
class Login extends dbh {
    public function CheckInfo($email, $password){
        $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = $this->connect()->query($sql);
        if(mysqli_num_rows($result) > 0){
            return "<p style='text-align:center;'>Login Succeeded</p>";
        }
        return "<p style='text-align:center;'>Login Failed</p>";
    }
}
?>