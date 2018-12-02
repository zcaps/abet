<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>ABET | Results</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.indigo-pink.min.css">
  <link rel="stylesheet" href="https://rawgit.com/MEYVN-digital/mdl-selectfield/master/mdl-selectfield.min.css">
  <script defer src="https://code.getmdl.io/1.1.3/material.min.js"></script>
  <script defer src="https://rawgit.com/MEYVN-digital/mdl-selectfield/master/mdl-selectfield.min.js"></script>
  <script src='js/autosize.js'></script>
  <link type="text/css" rel="stylesheet" href="css/standardize.css">
  <link type="text/css" rel="stylesheet" href="css/results-grid.css">
  <link type="text/css" rel="stylesheet" href="css/results.css">
  <script type="application/javascript">
    window.onload = function() {
        materializeControls();
    }
        
    function materializeControls() {
        materializeTextInputs();
    }
    
    function materializeTextInputs() {
        var label, parentEl;
        document.querySelectorAll('input[type="text"], textarea').forEach(function(control) {
            parentEl = control.parentElement;
            control.classList.add('mdl-textfield__input');
            if (parentEl.tagName !== 'DIV') {
                return;
            }
            parentEl.classList.add('mdl-textfield', 'mdl-js-textfield');
            label = parentEl.querySelector('label');
            if (label) {
                label.setAttribute('for', control.id || control.name)
                label.classList.add('mdl-textfield__label');
            }
        });
    }
        
</script>
</head>
<?php
  session_start();
  ini_set('display_errors', 1);
  error_reporting(E_ALL|E_STRICT);
  include 'userFuncs/rubrics.php';
  include 'userFuncs/assessmentPlans.php';
  include 'userFuncs/results.php';
  include 'userFuncs/outcomes.php';
  include 'userFuncs/courses.php';
  echo $_SESSION['id'];
  ////
  //  Displays All Outcomes for a specific course, major combo
  //  Will be displayed every time the professor clicks on a new course
  ////
  $outcomes= new Outcomes();
  $result = $outcomes->FindOutcomes("CS", "COSC 465");
  echo "<p><br>All Outcomes For This Course<p><br>";
  echo $result;
  ////
  //  Gets all plans,
  //  All Results and All Narrative Summaries
  //  For a specific major, course, and outcome
  ////
  $result = new Results();
  $outcomeResults = $result->outComeResults("CS", "CS312", 1);
  echo "<p><br>Course Outcome Results<p><br>";
  $outcomeResults = json_decode($outcomeResults);
  $numberOfStudents = json_decode($outcomeResults->results);
  $assessmentPlans = json_decode($outcomeResults->assessmentPlans);
  $narrativeSummaries = json_decode($outcomeResults->narrativeSummaries);
  print_r($outcomeResults);
  ////
  //  Returns All Assessment Plans Past, Present and (maybe)Future
  //  Of all Courses with Major and Course ID
  ////
  $assessmentPlan = new AssessmentPlans();
  $result = $assessmentPlan->ReturnAllPlans("CS", "COSC 465");
  echo "<p><br>Assessment Plans<p><br>";
  echo $result;
  echo $assessmentPlan->GetAllSectionsWithAssessments('CS', 'CS312', 1)
  

?>

<body class="body page-results clearfix">
  <header class="_container clearfix">
    <img class="abetlogo abetlogo-1" src="images/abet_logo.png">
  </header>
  <div class="courses-container clearfix">
    <div class="courses-container-title">Courses</div>
    <?php
      ////
      //  Gets all courses
      //  Allows Us to display the courses on the left sidebar
      //  Based on a Session ID or Cookie
      ////
      $courses = new Courses();
      $results = $courses->FindCoursesByInstructorId($_SESSION['id']);
      $results = json_decode($results);
      
      for($i=0; $i < sizeof($results); $i++){
        echo '<button class="course1">'.$results[$i][1].' '.$results[$i][0].'</button>';
      }
    ?>
    
  </div>
  <div class="outcomes-container clearfix">
    <p class="outcome-container-title">Outcomes</p>
    <?php
      
      ////
      //  Displays All Outcomes for a specific course, major combo
      //  Will be displayed every time the professor clicks on a new course
      ////
      $outcomes= new Outcomes();
      $result = $outcomes->FindOutcomes($results[0][0], $results[0][1]);
      $result = json_decode($result);
      
      foreach($result as $r){
        
        echo '<div value="'.$r->outcomeId.'" class="outcome1-container clearfix">
        <p class="text">'.$r->outcome.'<br></p>
        </div>';
      }
    ?>

  </div>
  <div class="outcome-results-container">
      <p class="outcome-results-title">Outcome Results</p>
      <div style="width:100%;">
        <div class="outcome-result">
          <p style="float:left;padding-right:2%;">Exceeds Expectations: </p>
          <label class="outcome-results-label"></label>
          <?php
            if(!empty($numberOfStudents)){
            echo '<input class="outcome-results-input" type="text" value='.$numberOfStudents[2]->numberOfStudents.'>';
            }else{
              echo '<input class="outcome-results-input" type="text">';
            }
          ?>
          
    
        </div>
        <div class="outcome-result">
        <p style="float:left;padding-right:2%;">Meets Expectations: </p>
          <label class="outcome-results-label"></label> 
          <?php
          if(!empty($numberOfStudents)){
            echo '<input class="outcome-results-input" type="text" value='.$numberOfStudents[1]->numberOfStudents.'>';
          }else{
            echo '<input class="outcome-results-input" type="text">';
          }
          ?>

        </div>
        <div class="outcome-result">
        <p style="float:left;padding-right:2%;">Does Not Meet Expectations: </p>
          <label class="outcome-results-label"></label>
          <?php
          if(!empty($numberOfStudents)){
            echo '<input class="outcome-results-input" type="text" value='.$numberOfStudents[0]->numberOfStudents.'>';
          }else{
            echo '<input class="outcome-results-input" type="text">';
          }
          ?>
        </div>
      </div>
  </div>
  <div class="narrative-summaries-container">
    <p class="outcome-results-title">Narrative Summaries</p>
    <?php
    if(!empty($narrativeSummaries)){
      echo '
      <div class="narrative-summary">
      <label>Strengths</label>
      <textarea class="strengthsInput">'.$narrativeSummaries[0]->strengths.'</textarea>
      </div>
      <div class="narrative-summary">
      <label>Weaknesses</label>
      <textarea class="weaknessesInput">'.$narrativeSummaries[0]->weaknesses.'</textarea>
      </div>
      <div class="narrative-summary">
      <label>Suggested Actions</label>
      <textarea class="actionsInput">'.$narrativeSummaries[0]->actions.'</textarea>
      </div>
      ';
    }else{
      echo '
      <div class="narrative-summary">
      <label>Strengths</label>
      <textarea class="strengthsInput"></textarea>
      </div>
      <div class="narrative-summary">
      <label>Weaknesses</label>
      <textarea class="weaknessesInput"></textarea>
      </div>
      <div class="narrative-summary">
      <label>Suggested Actions</label>
      <textarea class="actionsInput"></textarea>
      </div>
      ';
    }
    ?>
    
  </div>
  <div class="assessments-container clearfix">
    <button class="addAssessment">+</button>
    <p class="assessments-container-title">Assessments</p>
    <?php

    ////
    //  Gets All Rubrics for a courseOutcomeId, major, and course
    //  Past Present and (maybe)Future
    ////
    $rubric = new Rubric();
    
    $assessmentRubrics = $rubric->GetAllCourseRubrics($results[0][0], $results[0][1], $result[0]->outcomeId);
    $assessmentRubrics = json_decode($assessmentRubrics);
    

      $offset = 0;
      foreach($assessmentPlans as $plan){
        $plan->assessmentWeight = $plan->assessmentWeight*100;
        echo '<div class="assessment1 clearfix">
        <p class="text text-7">'.$plan->assessmentDescription.': '.$plan->assessmentWeight.'%</p>
        <span class="down-caret"></span>
        <div class="assessmentRubric" style="display:none;">';

        for($i=0;$i<3;$i++){
          
          if($i==0){
            echo '<p class="text text-8">'.$assessmentRubrics[$i + $offset]->rubricName.': </p>';
          }
          echo '<p class="text text-8">'.$assessmentRubrics[$i + $offset]->performanceLevel.': '.$assessmentRubrics[$i + $offset]->description.'</p>';
        }
        $offset+=3;
        echo '</div>
        </div>';
      }
    ?>
    <!--
    <div class="assessment1 clearfix">
      <p class="text text-7">Lab 1: 20%</p>
      <p class="text text-8">Exceeds Expectations: To exceed expectations means the student read the required materials, researched the online documentation, and emplemented a solution flawlessly.</p>
      <p class="text text-9">Meets Expectations: To meet expectations means the student read some required materials, researched the online documentation, and emplemented a solution that could have been better.</p>
      <p class="text text-10">Didn't Meet Expectations: To not meet expectations means the student did not read the required materials, did not research the online documentation, and emplemented a solution that did not work.</p>
    </div>
    <div class="assessment2 clearfix">
      <p class="text text-11">Lab 2: 20%</p>
      <p class="text text-12">Exceeds Expectations: To exceed expectations means the student read the required materials, researched the online documentation, and emplemented a solution flawlessly.</p>
      <p class="text text-13">Meets Expectations: To meet expectations means the student read some required materials, researched the online documentation, and emplemented a solution that could have been better.</p>
      <p class="text text-14">Didn't Meet Expectations: To not meet expectations means the student did not read the required materials, did not research the online documentation, and emplemented a solution that did not work.</p>
    </div>
    <div class="assessment3 clearfix">
      <p class="text text-15">Exam 1: 60%</p>
      <p class="text text-16">Exceeds Expectations: To exceed expectations means the student answered all questions on the exam and received above a 90%.</p>
      <p class="text text-17">Meets Expectations: To meet expectations means the student answered all questions and received a score between 70 and 90%.</p>
      <p class="text text-18">Didn't Meet Expectations: To not meet expectations means the student received a score below 70%.</p>
    </div>
    -->
  </div>
  <div class="dark-bg"></div>
  <div class="are-you-sure-popup">
    <img class="close-popup" style="float:right;width:3%;cursor:pointer;"src='images/close.svg'>
    <p style="margin-top:5%;text-align:center;">Are you sure that you want to replace all your assessments with the assessments from the course you chose?</p>
    <div style="text-align:center;margin-top:5%;margin-bottom:5%;">
    <button class="yes-replace-assessments btn-filled-grey">Yes</button>
    <button class="dont-replace-assessments btn-filled-grey">No</button>
    </div>
</div>
  <script src='js/jquery-min.js'></script>
  <script src='js/results.js'></script>
  <script>autosize($('.strengthsInput'));autosize($('.weaknessesInput'));autosize($('.actionsInput'));</script>
</body>

</html>