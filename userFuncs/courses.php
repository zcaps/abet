<?php
require_once(__DIR__.'/../DB/db.php');
class Courses extends dbh {
    //FindCoursesByInstructorId
    //Finds all courses that an instructor is teaching or has taught in the past
    //Might want to include a time query in the future
    public function FindCoursesByInstructorId($id){
        $sql = "SELECT major, course FROM courses WHERE userId=$id";
        $result = $this->connect()->query($sql);
        $rows = mysqli_fetch_all($result);
        return json_encode($rows);
    }
}
?>