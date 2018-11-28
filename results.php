<!doctype html>
<html>
<?php
  ini_set('display_errors', 1);
  error_reporting(E_ALL|E_STRICT);
  include 'userFuncs/rubrics.php';
  include 'userFuncs/assessmentPlans.php';
  include 'userFuncs/results.php';
  include 'userFuncs/outcomes.php';
  include 'userFuncs/courses.php';
  ////
  //  Gets all courses
  //  Allows Us to display the courses on the left sidebar
  //  Based on a Session ID or Cookie
  ////
  $courses = new Courses();
  $results = $courses->FindCoursesByInstructorId(1);
  echo "<p><br>All Courses Taught By This Professor<p><br>";
  echo $results;
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
  $result = $result->outComeResults("CS", "COSC 465", 1);
  echo "<p><br>Course Outcome Results<p><br>";
  echo $result;
  ////
  //  Returns All Assessment Plans Past, Present and (maybe)Future
  //  Of all Courses with Major and Course ID
  ////
  $assessmentPlan = new AssessmentPlans();
  $result = $assessmentPlan->ReturnAllPlans("CS", "COSC 465");
  echo "<p><br>Assessment Plans<p><br>";
  echo $result;
  ////
  //  Gets All Rubrics for a courseOutcomeId, major, and course
  //  Past Present and (maybe)Future
  ////
  $rubric = new Rubric();
  $result = $rubric->GetAllCourseRubrics("CS", "COSC 465", 1);
  echo "<p><br>All Rubrics For This Outcome</p><br>";
  echo $result;

?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="initial-scale=1.0">
  <title>ABET | Results</title>
  <link type="text/css" rel="stylesheet" href="css/standardize.css">
  <link type="text/css" rel="stylesheet" href="css/results-grid.css">
  <link type="text/css" rel="stylesheet" href="css/results.css">
</head>
<body class="body page-results clearfix">
  <header class="_container clearfix">
    <img class="abetlogo abetlogo-1" src="images/abet_logo.png">
  </header>
  <div class="courses-container clearfix">
    <div class="courses-container-title">Courses</div>
    <button class="course1">COSC 302</button>
    <button class="course2">COSC 311</button>
    <button class="course3">COSC 312</button>
    <button class="course4">COSC 340</button>
    <button class="course5">COSC 465</button>
  </div>
  <div class="outcomes-container clearfix">
    <p class="outcome-container-title">Outcomes</p>
    <div class="outcome1-container clearfix">
      <p class="text">Analyze a complex computing problem and to apply principles of computing and other relevant disciplines to identify solutions.<br></p>
    </div>
    <div class="outcome2-container clearfix">
      <p class="text">Design, implement, and evaluate a computing-based solution to meet a given set of computing requirements in the context of the program’s discipline.</p>
    </div>
    <div class="outcome3-container clearfix">
      <p class="text">Communicate effectively in a variety of professional contexts.</p>
    </div>
    <div class="outcome4-container clearfix">
      <p class="text">Recognize professional responsibilities and make informed judgments in computing practice based on legal and ethical principles.<br></p>
    </div>
    <div class="outcome5-container clearfix">
      <p class="text">Function effectively as a member or leader of a team engaged in activities appropriate to the program’s discipline.<br></p>
    </div>
    <div class="outcome6-container clearfix">
      <p class="text">Apply computer science theory and software development fundamentals to produce computing-based solutions.<br></p>
    </div>
  </div>
  <div class="assessments-container clearfix">
    <p class="assessments-container-title">Assessments</p>
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
  </div>
</body>
</html>