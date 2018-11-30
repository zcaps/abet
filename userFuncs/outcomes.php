<?php
require_once(__DIR__.'/../DB/db.php');
class Outcomes extends dbh {
    public function FindOutcomes($major, $course){
    
        $sql = "SELECT o.outcome 
        FROM courseMapping cm, outcomes o, majors m
        WHERE cm.courseId='$course' AND cm.outcomeId=o.outcomeId
        AND cm.majorId=m.majorId AND m.major='$major'";

        $result = $this->connect()->query($sql);
        $rows = [];
        while($row = mysqli_fetch_assoc($result)){
            $row['outcome'] = htmlentities(trim($row['outcome']), ENT_QUOTES, 'UTF-8');
            $rows[] = $row;
        }
        
        return json_encode($rows);
    }
}
?>