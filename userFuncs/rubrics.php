<?php
    require_once(__DIR__.'/..//DB/db.php');
    class Rubric extends dbh {
        ////
        //  GetAllCourseRubrics returns all course rubrics
        //  Assessments Have a Rubric, That May Or May Not Be Unique
        //  Professor might want to use the same rubric for multiple assessments
        //  The output with outcomeId 1, uses the same rubric for all assessments with outcome 1
        ////
        public function GetAllCourseRubrics($major, $course, $outcomeId){
        $sql = "SELECT r.rubric
        FROM rubrics r, assessments a, courseOutcomes co, courses c, outcomes o
        WHERE c.major='$major' AND c.course='$course' AND o.outcomeId=$outcomeId
        AND c.courseId=co.courseId AND o.outcomeId=co.outcomeId
        AND a.courseOutcomeId=co.courseOutcomeId
        AND a.rubricId = r.rubricId";

        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
        }
    }
?>