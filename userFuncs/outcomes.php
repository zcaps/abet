<?php
require_once(__DIR__.'/../DB/db.php');
class Outcomes extends dbh {
    public function FindOutcomes($major, $course){
    
        $sql = "SELECT o.outcome 
        FROM courseOutcomes co, courses c, outcomes o
        WHERE c.courseId=co.courseId AND co.outcomeId=o.outcomeId AND c.major='$major' AND c.course='$course'";
    
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