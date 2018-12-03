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
        $id = $_SESSION['id'];
        $sql = "SELECT rn.rubricName, p.description as performanceLevel, r.description
        FROM assessments a, majors m, sectionsCurrent s, rubrics r, performanceLevels p, rubricNames rn
        WHERE a.outcomeId=$outcomeId AND a.majorId=m.majorId AND m.major='$major'
        AND a.sectionId=s.sectionId AND s.courseId='$course'
        AND a.rubricId=r.rubricId AND r.performanceLevel=p.performanceLevel
        AND rn.rubricId=a.rubricId AND rn.outcomeId=$outcomeId AND s.instructorId='$id'";

        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
        }

        ////
        //  Gets all rubrics ever used to assess this outcome
        //  Prevents duplicate rubrics
        ////
        public function GetPastAndCurrentRubrics($major, $course, $outcomeId){
        
            $sql = "SELECT rn.rubricId, rn.rubricName
            FROM rubricNames rn, sectionsCurrent sc, majors m, assessments a
            WHERE sc.courseId='$course' AND m.majorId=a.majorId AND m.major='$major'
            AND a.sectionId=sc.sectionId AND a.outcomeId=$outcomeId AND rn.outcomeId=$outcomeId
            AND a.rubricId=rn.rubricId
            GROUP BY rn.rubricName, rn.rubricId";

             $result = $this->connect()->query($sql);

             $rows = [];
             while($row = mysqli_fetch_assoc($result)){
                 $rows[] = $row;
             }
             $sql = "SELECT rn.rubricId, rn.rubricName
             FROM rubricNames rn, sectionsHistory sc, majors m, assessments a
             WHERE sc.courseId='$course' AND m.majorId=a.majorId AND m.major='$major'
             AND a.sectionId=sc.sectionId AND a.outcomeId=$outcomeId AND rn.outcomeId=$outcomeId
             AND a.rubricId=rn.rubricId
             GROUP BY rn.rubricName, rn.rubricId";
             
             $result = $this->connect()->query($sql);

             while($row = mysqli_fetch_assoc($result)){
                $b = true;
                foreach($rows as $r){
                    if($r['rubricName'] == $row['rubricName']){
                        $b = false;
                        break;
                    }
                 }
                if($b){
                    $rows[] = $row;
                }
            }
             
             return json_encode($rows);
    
        }
    }
?>