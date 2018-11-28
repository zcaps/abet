<?php
require_once(__DIR__.'/../DB/db.php');
class Results extends dbh {
    public function outcomeResults($major, $course, $outcomeId){
        
    
        //$result = $this->connect()->query($sql);
        $rows = [];
        $rows["outcomeResults"] = $this->getStudentAssessmentResults($major, $course, $outcomeId);
        $rows["assessmentPlans"] = $this->getAssessmentPlans($major, $course, $outcomeId);
        $rows["narrativeSummaries"] = $this->getNarrativeSummaries($major, $course, $outcomeId);
        return json_encode($rows);
    }
    //getAssessmentPlans is the same function as asssessmentPlans.php
    //Except it only shows assessments for a Course, major, and OUTCOME
    //I think the idea is to show all Assessments at the bottom of the page
    private function getAssessmentPlans($major, $course, $outcomeId){
        $sql = "SELECT a.assessmentPlan
        FROM assessments a, courseOutcomes co, courses c, outcomes o
        WHERE c.major='$major' AND c.course='$course' AND o.outcomeId=$outcomeId
        AND c.courseId=co.courseId AND o.outcomeId=co.outcomeId
        AND a.courseOutcomeId=co.courseOutcomeId";

        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
    //getStudentAssessmentResults
    //Gets all pre-existing outcome results
    //Meaning that the professor can make an assessment for each student
    private function getStudentAssessmentResults($major, $course, $outcomeId){
        $sql = $sql = "SELECT a.assessmentPlan, sar.results
        FROM studentAssessmentResults sar,
        assessments a, courseOutcomes co, courses c, outcomes o
        WHERE c.major='$major' AND c.course='$course' AND o.outcomeId=$outcomeId
        AND c.courseId=co.courseId AND o.outcomeId=co.outcomeId
        AND a.courseOutcomeId=co.courseOutcomeId
        AND sar.assessmentId=a.assessmentId";
    
        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
    ////
    //getNarrativeSummaries
    //Shows Narrative Summaries for all Students
    //The professor rates each student on their
    //Strengths/Weaknesses, and Suggests Ways to Improve
    ////
    private function getNarrativeSummaries($major, $course, $outcomeId){
        $sql = "SELECT sons.strengths, sons.weaknesses, sons.suggestions
        FROM studentOutcomeNarrativeSummary sons,
        courseOutcomes co, courses c, outcomes o
        WHERE c.major='$major' AND c.course='$course' AND o.outcomeId=$outcomeId
        AND c.courseId=co.courseId AND o.outcomeId=co.outcomeId
        AND sons.courseOutcomeId=co.courseOutcomeId";
        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
}
?>