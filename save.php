<!doctype html>
<html>
    <?php
        include(__DIR__.'/userFuncs/assessmentPlans.php');
    ?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>ABET | Results</title>
</head>
<body>
    <div>
        <?php
            if(isset($_POST['assessmentPlan']) && isset($_POST['assessmentWeight'])
            && isset($_POST['rubric']) && isset($_POST['courseOutcomeId'])){
                $plan = new AssessmentPlans();
                $plan->InsertAssessmentPlan($_POST);
            }
        ?>
        <form action="save.php" method="POST">
            <input name="assessmentPlan" placeholder="Assessment Name">
            <input name="assessmentWeight" placeholder="Assessment Weight">
            <select name="rubric">
                <option value="1">Rubric 1</option>
                <option value="2">Rubric 2</option>
                <option value="3">Rubric 3</option>
                <option value="4">Rubric 4</option>
            </select>
            <input name="courseOutcomeId" placeholder="CourseOutcomeId -- This will not be needed Once I use AJAX">
            <input type="submit" value="save">
        </form>
    </div>
</body>
</html>
