<?php
require_once(__DIR__.'/../DB/db.php');
class Courses extends dbh {
    //FindCoursesByInstructorId
    //Finds all courses that an instructor is teaching or has taught in the past
    //Might want to include a time query in the future
    public function FindCoursesByInstructorId($id){
        $sql = "SELECT m.major, cm.courseId FROM sectionsCurrent s, courseMapping cm, majors m WHERE s.instructorId='$id' 
        AND s.courseId = cm.courseId AND cm.majorId = m.majorId
        GROUP BY cm.majorId, cm.courseId";
        $result = $this->connect()->query($sql);
        $rows = mysqli_fetch_all($result);
        return json_encode($rows);
    }

    
}
?>