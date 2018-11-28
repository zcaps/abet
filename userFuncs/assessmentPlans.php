<?php
require_once(__DIR__.'/../DB/db.php');
class AssessmentPlans extends dbh {
    //InsertAssessmentPlan allows a Professor to
    //Create An assignment for a specific courseOutcome
    //A courseOutcome is chosen from a professor's list of classes,
    //And His Outcomes for that specific Major and Class
    //This is combined into one variable courseOutcomeId
    public function InsertAssessmentPlan($data){
        $assessmentPlan = htmlentities($data['assessmentPlan']);
        $weight = htmlentities($data[assessmentWeight]);
        $rubricId = htmlentities($data['rubric']);
        $outcomeId = htmlentities($data['courseOutcomeId']);
        $sql = "INSERT INTO assessments (assessmentPlan, assessmentWeight, rubricId, 
        courseOutcomeId) VALUES ('$assessmentPlan', '$weight',
        $rubricId, $outcomeId)";
        $result = $this->connect()->query($sql);
        if($result === true){
            return true;
        }
        return false;
    }
    //ReturnAllPlans Returns all assessment plans,
    //For The $course and $major ever submitted
    //The Professor will be able to use this functionality
    //To help Create and improve assessment plans for future courses
    public function ReturnAllPlans($major, $course){
        
            $sql = "SELECT a.assessmentPlan
            FROM assessments a, courseOutcomes co, courses c, outcomes o
            WHERE c.major='$major' AND c.course='$course'
            AND c.courseId=co.courseId AND o.outcomeId=co.outcomeId
            AND a.courseOutcomeId=co.courseOutcomeId";
    
            $result = $this->connect()->query($sql);
            $rows = [];
            while($row = mysqli_fetch_assoc($result)){
                $rows[] = $row;
            }
            return json_encode($rows);
    }
}