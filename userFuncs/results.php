<?php
require_once(__DIR__.'/../DB/db.php');
class Results extends dbh {
    public function outcomeResults($major, $course, $outcomeId){
        
    
        //$result = $this->connect()->query($sql);
        $rows = [];
        $rows["results"] = $this->getOutcomeResults($major, $course, $outcomeId);
        $rows["assessmentPlans"] = $this->getAssessmentPlans($major, $course, $outcomeId);
        $rows["narrativeSummaries"] = $this->getNarrativeSummaries($major, $course, $outcomeId);
        return json_encode($rows);
        
    }
    ////
    //  gets the number of students who exceed, meet or do not meet expectations
    ////
    private function getOutcomeResults($major, $course, $outcomeId){
        $sql = "SELECT ores.numberOfStudents FROM outcomeResults ores,
        majors m, sectionsCurrent s, outcomes o
        WHERE ores.majorId = m.majorId AND m.major='$major'
        AND s.sectionId=ores.sectionId AND s.courseId='$course' AND ores.outcomeId=o.outcomeId
        AND ores.outcomeId=$outcomeId";
        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
    //getAssessmentPlans is the same function as asssessmentPlans.php
    //Except it only shows assessments for a Course, major, and OUTCOME
    //I think the idea is to show all Assessments at the bottom of the page
    private function getAssessmentPlans($major, $course, $outcomeId){
        $sql = "SELECT a.assessmentDescription, a.assessmentWeight
        FROM assessments a, sectionsCurrent s, majors m
        WHERE a.majorId=m.majorId AND m.major='$major'
        AND a.sectionId=s.sectionId AND s.courseId='$course'
        AND a.outcomeId=$outcomeId";
        

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
        $sql = "SELECT n.strengths, n.weaknesses, n.actions
        FROM narratives n, sectionsCurrent s, majors m
        WHERE n.majorId=m.majorId AND m.major='$major'
        AND n.sectionId=s.sectionId AND s.courseId='$course'
        AND n.outcomeId=$outcomeId";
        
        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
}
?>