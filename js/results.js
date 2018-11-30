$(document).ready(function(){
    
    //initialize first course to clicked
    $courseBtn = $('.course1')
    $($courseBtn[0]).css("background-color", "rgb(230, 230, 230)")
    //initialize course and major data
    var substr = $($courseBtn[0]).html().split(" ")
    ////
    //  Click Event For Courses
    //  Appends Outcomes to the Outcomes container
    ////
    $courseBtn.click(function(){
        //substr[0] is the courseId
        $courseBtn.css("background-color", "rgb(247, 247, 247)")
        $(this).css("background-color", "rgb(230, 230, 230)")
        substr =  $(this).html().split(" ")
        let data = {
            "major": substr[1],
            "course": substr[0]
        }
        $.post(
            "get-outcomes.php",
            data,
            appendOutcomes,
            "json"
        )
        
    })
    ////
    //  Callback Function to append outcomes on response from get-outcomes.php
    ////
    function appendOutcomes(result){
        $od = $('.outcomes-container').find('div')
        $od.detach()
        result = JSON.parse(result.message)
        for(var i=0;i<result.length;i++){
            $div = $('<div class="outcome1-container clearfix">')
            $div.attr("value", result[i].outcomeId)
            $p = $('<p class="text">').html(result[i].outcome)
            $div.append($p)
            //initialize first outcome to clicked state
            if(i==0){
                $div.css("background-color", "rgb(230, 230, 230)")
            }
            $('.outcomes-container').append($div)
        }
    }

    //initialize first outcome to clicked state
    $outcomeBtn = $('.outcome1-container')
    $($outcomeBtn[0]).css("background-color", "rgb(230, 230, 230)")
    ////
    //  Outcomes Click Event to append Outcome Results and Assessments
    ////
    $('.outcomes-container').on('click', '.outcome1-container', function(){
        $outcomeBtn = $('.outcome1-container')
        $outcomeBtn.css("background-color", "rgb(247, 247, 247)")
        $(this).css("background-color", "rgb(230, 230, 230)")
        let data = {
            "major":substr[1],
            "course": substr[0],
            "outcomeId": $(this).attr("value")
        }
        $.post(
            "get-outcome-results.php",
            data,
            appendOutcomeClickResults,
            "json"
        )
    })
    function appendOutcomeClickResults(res){
        res = JSON.parse(res.message)
        let outcomeResults = JSON.parse(res.results)
        let assessmentResults = JSON.parse(res.assessmentPlans)
        
        outcomeResults.reverse()
        $inputs = $('.outcome-results-input')
        for(var i=0; i<$inputs.length; i++){
            if(outcomeResults.length > 0){
                $($inputs[i]).attr("value", outcomeResults[i].numberOfStudents)
            }else{
                $($inputs[i]).attr("value", "")
            }
            
        }
        $assessmentsContainer = $('.assessments-container')
        $('.assessment1').detach()
        for(var i=0;i<assessmentResults.length;i++){
            $div = $('<div class="assessment1 clearfix">')
            $p = $('<p class="text text-7">').html(assessmentResults[i].assessmentDescription+' '+assessmentResults[i].assessmentWeight*100+'%')
            $div.append($p)
            $assessmentsContainer.append($div)
        }
    }

    ////
    //  Click Event for + button in the Assessments Container
    //  Appends the Past Assessment Rubrics and Input Boxes
    ////
    $addAssessment = $('.addAssessment')
    $addAssessment.click(function(){
        var $div = $("<div>", {"class": "assessment1 clearfix mdl-textfield mdl-js-textfield is-dirty is-upgraded"})
        var $input1 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Type"}).html("Assessment Type")
        var $input2 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Description"})
        var $input3 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Weight"})
        var $p = $("<p>",{"class": "assessments-container-title", "style": "margin-top:10%;"}).html("Rubrics From Previous Assessments")
        $('.assessments-container').append($div)
        //$div.append($label)
        $div.append($input1)
        $div.append($input2)
        $div.append($input3)
        $div.append($p)
    })
});