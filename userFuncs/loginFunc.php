<?php
require_once(__DIR__.'/../DB/db.php');
class Login extends dbh {
    public function CheckInfo($email, $password){
        $sql = "SELECT * FROM Instructors WHERE email='$email' AND password='$password'";
        $result = $this->connect()->query($sql);
        if(mysqli_num_rows($result) > 0){
            
            $_SESSION['email'] = $email;
            return "<p style='text-align:center;'>Login Succeeded</p>";
        }
        $sql = "SELECT * FROM Instructors WHERE instructorId='$email' AND password='$password'";
        $result = $this->connect()->query($sql);
        if(mysqli_num_rows($result) > 0){
            
            $_SESSION['id'] = $email;
            return "<p style='text-align:center;'>Login Succeeded</p>";
        }
        return "<p style='text-align:center;'>Login Failed</p>";
    }
}
?>