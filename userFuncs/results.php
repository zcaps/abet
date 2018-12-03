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

    ///
    //  Insert Results into Database
    //  (Assessment Plans, Narrative Summaries, Outcome Results)
    ///
    
    public function InsertResults($results){
        
        foreach($results as $i => $result){
            if($i == "narrativeSummaries"){
                $results[$i] = $this->insertNarrativeSummaries($results['identification'], $result);
            }else if($i == "outcomeResults"){
                $results[$i] = $this->insertOutcomeResults($results['identification'], $result);
            }else if($i == "assessmentPlans"){
                $results[$i] = $this->insertAssessmentPlans($results['identification'], $result);
            }
        }
        
        return $results;
    }
    ////
    //  gets the number of students who exceed, meet or do not meet expectations
    ////
    private function getOutcomeResults($major, $course, $outcomeId){
        $id = $_SESSION['id'];
        $sql = "SELECT ores.numberOfStudents FROM outcomeResults ores,
        majors m, sectionsCurrent s, outcomes o
        WHERE ores.majorId = m.majorId AND m.major='$major'
        AND s.sectionId=ores.sectionId AND s.courseId='$course' AND ores.outcomeId=o.outcomeId
        AND ores.outcomeId=$outcomeId AND s.instructorId='$id'";
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
        $id = $_SESSION['id'];
        $sql = "SELECT a.assessmentDescription, a.assessmentWeight
        FROM assessments a, sectionsCurrent s, majors m
        WHERE a.majorId=m.majorId AND m.major='$major'
        AND a.sectionId=s.sectionId AND s.courseId='$course'
        AND a.outcomeId=$outcomeId AND s.instructorId='$id'";
        

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
        $id = $_SESSION['id'];
        $sql = "SELECT n.strengths, n.weaknesses, n.actions
        FROM narratives n, sectionsCurrent s, majors m
        WHERE n.majorId=m.majorId AND m.major='$major'
        AND n.sectionId=s.sectionId AND s.courseId='$course'
        AND n.outcomeId=$outcomeId AND s.instructorId='$id'";
        
        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $rows[] = $row;
        }
        return json_encode($rows);
    }

    private function getSectionMajorIds($major, $course){
        $con = $this->connect();
        $id = $_SESSION['id'];
        $sql = "SELECT m.majorId, sc.sectionId FROM majors m, sectionsCurrent sc WHERE m.major='$major' 
        AND sc.courseId='$course' AND sc.instructorId='$id'";
        $result = $con->query($sql);
        $result = mysqli_fetch_assoc($result);
        $con->close();
        return $result;
    }

    private function insertNarrativeSummaries($joindata, $insertiondata){
        $con = $this->connect();
        $id = $_SESSION['id'];
        $course = $joindata['course'];
        $major = $joindata['major'];
        $outcomeId = $joindata['outcomeId'];
        
        $result = $this->getSectionMajorIds($major, $course);
        
        $section = $result['sectionId'];
        $major = $result['majorId'];

        //check if anything is empty
        //dont want to insert empty strings
        foreach($insertiondata as $data){
            if(empty($data)){
                $sql = "DELETE FROM narratives WHERE sectionId=? AND majorId=? AND outcomeId=?";
                $stmt = $con->prepare($sql);
                $stmt->bind_param("iii", $section, $major, $outcomeId);
                $stmt->execute();
                $stmt->close();
                return "empty";
            }
        }

        $sql = "DELETE FROM narratives WHERE sectionId=? AND majorId=? AND outcomeId=?";
        
        $stmt = $con->prepare($sql);
        $stmt->bind_param("iii", $section, $major, $outcomeId);
        $stmt->execute();
        $stmt->close();
        
        $sql = "INSERT INTO narratives VALUES (?, ?, ?, ?, ?, ?)";
        
        
        $insert = $con->prepare($sql);
        $insert->bind_param("iiisss", $section, $major, $outcomeId, $insertiondata['strengths'], $insertiondata['weaknesses'], $insertiondata['actions']);
        $insert->execute();

        $con->close();
        return "inserted";
        
    }
    private function insertOutcomeResults($joindata, $insertiondata){
        $con = $this->connect();
        $id = $_SESSION['id'];
        $course = $joindata['course'];
        $major = $joindata['major'];
        $outcomeId = $joindata['outcomeId'];

        $result = $this->getSectionMajorIds($major, $course);
        
        $section = $result['sectionId'];
        $major = $result['majorId'];

        //check if anything is empty
        foreach($insertiondata as $data){
            if(empty($data)){
                $del = $con->prepare("DELETE FROM outcomeResults WHERE sectionId=? AND majorId=? AND outcomeId=?");
                $del->bind_param('iii', $section, $major, $outcomeId);
                $del->execute();
                $del->close();
                return "empty";
            }else if($data < 0){
                return "negative integer";
            }
        }

        $del = $con->prepare("DELETE FROM outcomeResults WHERE sectionId=? AND majorId=? AND outcomeId=?");
        $del->bind_param('iii', $section, $major, $outcomeId);
        $del->execute();
        $del->close();

        $j = 1;
        $insertiondata = array_reverse($insertiondata);
        foreach($insertiondata as $data){
            $exceeds = $con->prepare("INSERT INTO outcomeResults VALUES (?,?,?,?,?)");
            $exceeds->bind_param('iiiii', $section, $major, $outcomeId, $j, $data);
            $exceeds->execute();
            $exceeds->close();
            $j++;
        }
        

        $meets = $con->prepare("INSERT INTO outcomeResults VALUES (?,?,?,?,?)");
        return "inserted";
    }
    private function insertAssessmentPlans($joindata, $insertiondata){
        $con = $this->connect();
        $id = $_SESSION['id'];
        $course = $joindata['course'];
        $major = $joindata['major'];
        $outcomeId = $joindata['outcomeId'];

        $result = $this->getSectionMajorIds($major, $course);
        
        
        $section = $result['sectionId'];
        $major = $result['majorId'];

        //check if anything is empty
        foreach($insertiondata as $data){
            if(empty($data)){
                return "empty";
            }
        }

        //check weight
        $checkWeight = $this->checkAssessmentWeight($section, $major, $outcomeId, $insertiondata['weight']);
        if($checkWeight !== true){
            return $checkWeight;
        }
        
        //get rubricId
        $rubricId = null;
        if(count($insertiondata) == 7){
            //Have to create a rubric
            $rubricId = $this->insertRubric($insertiondata['exceeds'], $insertiondata['meets'], $insertiondata['notMeets'], $insertiondata['rubricName'], $outcomeId);
        }else if(count($insertiondata) == 4){
            //rubricName should exist
            $rubricId = $this->getRubricId($insertiondata['rubric'], $outcomeId);
            if(!isset($rubricId)){
                return "Invalid Rubric Name";
            }
        }else{
            return "refresh the page";
        }

        //get assessmentId
        $assessmentId = $this->getAssessmentId($insertiondata['type']);

        $stmt = $con->prepare("INSERT INTO assessments VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iisdiii', $section, $assessmentId, $insertiondata['description'], $insertiondata['weight'], $major, $rubricId, $outcomeId);
        $stmt->execute();
        $stmt->close();

        $con->close();

        return "inserted";
    }

    private function checkAssessmentWeight($section, $major, $outcome, $weight){
        if($weight < 0){
            return "Assignment Weight Cannot Be Negative";
        }
        $con = $this->connect();
        $sql = "SELECT a.assessmentWeight FROM assessments a WHERE a.sectionId=? AND a.majorId=? AND a.outcomeId=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('iii', $section, $major, $outcome);
        $stmt->execute();
        $res = $stmt->get_result();
        $stmt->close();
        $con->close();
        $totalWeight=0;
        while($row = $res->fetch_array(MYSQLI_ASSOC)){
            $totalWeight += $row['assessmentWeight'];
        }
        if($totalWeight + $weight > 1){
            return "Total Weights Must Add Up to 1";
        }
        return true;
    }

    private function insertRubric($exceeds, $meets, $notMeets, $rubricName, $outcomeId){
        $id = $_SESSION['id'];
        $con = $this->connect();
        $stmt = $con->prepare("SELECT rubricId FROM rubricNames ORDER BY rubricId DESC LIMIT 1");
        $stmt->execute();
        
        $row = $stmt->get_result();
        $row = $row->fetch_array(MYSQLI_ASSOC);
        $rubricId = $row['rubricId']+1;
        $stmt->close();
        
        $stmt = $con->prepare("INSERT INTO rubrics VALUES(?, 1, ?)");
        $stmt->bind_param('is', $rubricId, $notMeets);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $con->prepare("INSERT INTO rubrics VALUES(?, 2, ?)");
        $stmt->bind_param('is', $rubricId, $meets);
        $stmt->execute();
        $stmt->close();

        $stmt = $con->prepare("INSERT INTO rubrics VALUES(?, 3, ?)");
        $stmt->bind_param('is', $rubricId, $exceeds);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $con->prepare("INSERT INTO rubricNames VALUES(?, ?, ?, ?)");
        $stmt->bind_param('isis', $rubricId, $rubricName, $outcomeId, $id);
        $stmt->execute();
        $stmt->close();

        $con->close();
        return $rubricId;
    }

    ////
    //  Takes a rubricName and outcomeId returns the Id associated with it
    ////
    private function getRubricId($rubricName, $outcomeId){
        $id = $_SESSION['id'];
        $con = $this->connect();
        $sql = "SELECT rubricId FROM rubricNames WHERE rubricName=? AND outcomeId=? AND ( instructorId IS NULL OR instructorId=? )";
        $stmt = $con->prepare($sql);
        $stmt->bind_param('sis', $rubricName, $outcomeId, $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_array(MYSQLI_ASSOC);
        
        $stmt->close();
        $con->close();
        return $row;
    }
    ////
    //  Takes an assessment type
    //  Checks if it's in the DB
    //  Inserts it if it's not
    //  And returns the Id
    ////
    private function getAssessmentId($type){
        $con = $this->connect();
        $sql = "SELECT a.assessmentId FROM assessmentTypes a WHERE a.type=?";
        $assessmentId = $con->prepare($sql);
        $assessmentId->bind_param('s', $type);
        $assessmentId->execute();
        $result = $assessmentId->get_result();
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $assessmentId->close();
        //If an assessment Id is not found for the user input
        //then we want to insert the user input and return the id
        if(!isset($row['assessmentId'])){
            $insert = $con->prepare("INSERT INTO assessmentTypes VALUES (DEFAULT, ?)");
            $insert->bind_param('s', $type);
            $val = $insert->execute();
            
            $insert->close();
            $row['assessmentId'] = $con->insert_id;
        }
        
        $con->close();
        return $row;
    }
}
?>