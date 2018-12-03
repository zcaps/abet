<?php
require_once(__DIR__.'/../DB/db.php');
class AssessmentPlans extends dbh {


    ////
    //  Allows an Instructor to click on a past course
    //  And update his current course with all past assessments
    //  Needs a past sectionId and the current sectionId
    ////
    public function UpdateAssessments($pastSectionId, $major, $course){
        $id = $_SESSION['id'];
        
        $sql1 = "SELECT assessmentId, assessmentDescription, assessmentWeight, majorId, rubricId, outcomeId FROM assessments WHERE sectionId='$pastSectionId'";
        $result1 = $this->connect()->query($sql1);
        //Find current sectionId
        $sql = "SELECT sc.sectionId FROM sectionsCurrent sc WHERE sc.courseId='$course' AND sc.instructorId='$id'";
        $result = $this->connect()->query($sql);
        $row = mysqli_fetch_assoc($result);
        $currentSectionId = $row['sectionId'];

        $sql = "DELETE FROM assessments WHERE sectionId='$currentSectionId'";
        $result = $this->connect()->query($sql);
        
        
        
        $rows = [];
        while($row = mysqli_fetch_assoc($result1)){
            $rows[] = $row;
            $sql = "INSERT INTO assessments";
            $sql .= " VALUES ('$currentSectionId','".implode("', '", $row)."') ";
            $result = $this->connect()->query($sql);
        }
        return json_encode($rows);
    }
    ////
    //  Allows the Instructor to click on a past assessment
    //  returns the past assessment data
    //  Instructor has the option to save or cancel the addition
    ////
    public function UpdateOneAssessment($pastSectionId, $assessmentDesc, $major, $outcomeId){
        $sql = "SELECT ast.type, a.assessmentDescription, a.assessmentWeight, r.rubricName FROM assessments a, majors m, assessmentTypes ast, rubricNames r WHERE a.sectionId='$pastSectionId' AND a.assessmentDescription='$assessmentDesc'
        AND a.outcomeId='$outcomeId' AND a.majorId=m.majorId AND m.major='$major' AND ast.assessmentId=a.assessmentId 
        AND r.rubricId=a.rubricId";
        $result = $this->connect()->query($sql);
        
        
        $row = mysqli_fetch_assoc($result);
            
        return json_encode($row);
    }
    
    ////
    //  I'm changing this query to allow for an Outcome
    //  Instead of only loading past and current courses to choose from
    //  This query will load all past and current courses + assessments for this outcome
    //  It will not load sections that don't have any assessments because why would an instructor want to preload their course with no assessments?
    //  Does not include the course we are currently viewing
    //  Because loading data into the course with data from this course makes no sense
    //
    //  For Use in "Load Assessments From a Previous Course"
    ////
    public function GetAllSectionsWithAssessments($major, $course, $outcomeId){
        $id = $_SESSION['id'];
        $sql = "SELECT sc.sectionId, sc.courseId, a.assessmentDescription FROM sectionsCurrent sc, assessments a, majors m
        WHERE sc.sectionId=a.sectionId AND sc.courseId='$course' AND a.majorId=m.majorId AND m.major='$major'
        AND a.outcomeId='$outcomeId' AND sc.instructorId !='$id'";

        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
        $rows[] = $row;
        }
        $sql = "SELECT sh.sectionId, sh.courseId, a.assessmentDescription FROM sectionsHistory sh, assessments a, majors m
        WHERE sh.sectionId=a.sectionId AND sh.courseId='$course' AND a.majorId=m.majorId AND m.major='$major'
        AND a.outcomeId='$outcomeId'";
        $result = $this->connect()->query($sql);
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }

    ////
    //  Gets all assessment types for display
    ////
    public function AssessmentTypes(){
        $sql = "SELECT * FROM assessmentTypes";
        $result = $this->connect()->query($sql);

        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }
}