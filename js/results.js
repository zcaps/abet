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
    var outcomeId = $($outcomeBtn[0]).attr("value")
    ////
    //  Outcomes Click Event to append Outcome Results and Assessments
    ////
    $('.outcomes-container').on('click', '.outcome1-container', function(){
        $outcomeBtn = $('.outcome1-container')
        $outcomeBtn.css("background-color", "rgb(247, 247, 247)")
        $(this).css("background-color", "rgb(230, 230, 230)")
        outcomeId = $(this).attr("value")
        let data = {
            "major":substr[1],
            "course": substr[0],
            "outcomeId": outcomeId
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
        let narrativeSummaries = JSON.parse(res.narrativeSummaries)

        //Displays number of students to outcome results
        outcomeResults.reverse()
        $inputs = $('.outcome-results-input')
        for(var i=0; i<$inputs.length; i++){
            if(outcomeResults.length > 0){
                $($inputs[i]).attr("value", outcomeResults[i].numberOfStudents)
            }else{
                $($inputs[i]).attr("value", "")
            }
            
        }

        //Displays Narrative Summaries inside the textareas
        $narrativeContainer = $('.narrative-summaries-container')
        $summaries = $('.narrative-summary')
        $textareas = $narrativeContainer.find('textarea')
        if(narrativeSummaries.length > 0){
            $($textareas[0]).val(narrativeSummaries[0].strengths)
            $($textareas[1]).val(narrativeSummaries[0].weaknesses)
            $($textareas[2]).val(narrativeSummaries[0].actions)
            $($textareas[0]).html(narrativeSummaries[0].strengths)
            $($textareas[1]).html(narrativeSummaries[0].weaknesses)
            $($textareas[2]).html(narrativeSummaries[0].actions)
            $summaries.addClass("is-dirty")
            
        }else{
            for(var i=0;i<$textareas.length;i++){
                $($textareas[i]).val("")
                $($textareas[i]).html("")
            }
            $summaries.removeClass("is-dirty")
            
        }
        
        //Displays all assessments for the Outcome, Course, Major Combo
        $assessmentsContainer = $('.assessments-container')
        $('.assessment1').detach()
        
        
        let data = {
            "major":substr[1],
            "course": substr[0],
            "outcomeId": outcomeId
        }
        ////
        //  This post request gets all current rubrics for the Assessments dropdown menu
        ////
        $.post(
            "get-current-rubrics.php",
            data,
            function(res){
                res = JSON.parse(res.message)
                var offset = 0
                for(var i=0;i<assessmentResults.length;i++){
                    $div = $('<div class="assessment1 clearfix">')
                    $p = $('<p class="text text-7">').html(assessmentResults[i].assessmentDescription+': '+assessmentResults[i].assessmentWeight*100+'%')
                    $span = $('<span class="down-caret">')
                    $innerdiv = $('<div class="assessmentRubric" style="display:none;">')
                    $div.append($p)
                    $div.append($span)
                    $div.append($innerdiv)
                    //display all rubrics
                    if(res.length > 0){
                    for(var j=0;j<3;j++){
                        if(j==0){
                            $p=$('<p class="text text-8">').html(res[j+offset].rubricName+':')
                            $innerdiv.append($p)
                        }
                        $p = $('<p class="text text-8">').html(res[j+offset].performanceLevel+': '+res[j+offset].description)
                        $innerdiv.append($p)
                    }
                    }
                    offset+=3
                    $assessmentsContainer.append($div)
                }
            },
            "json"
        )
        
    }

    ////
    //  Click Event for + button in the Assessments Container
    //  Appends the Past Assessment Rubrics and Input Boxes
    ////
    $addAssessment = $('.addAssessment')
    $addAssessment.click(function(){
        var $div = $("<div>", {"class": "assessment1 clearfix mdl-textfield mdl-js-textfield is-dirty is-upgraded"})

        var $input1 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Type", "list": "types"}).html("Assessment Type")
        var $datalist = $("<datalist id='types'>")
        var $input2 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Description"})
        var $input3 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Assessment Weight"})
        var $p = $("<p>",{"class": "assessments-container-title", "style": "margin-top:10%;font-size:2em;"}).html("Load Assessments From a Previous Course")
        $.get(
            "get-assessment-types.php",
            function(data){
                data = JSON.parse(data.message)
                for(var i=0; i<data.length;i++){
                    $option = $('<option>').html(data[i].type)
                    $datalist.append($option)
                    
                }
            }
        )
        let data = {
            "major":substr[1],
            "course": substr[0],
            "outcomeId": outcomeId
        }
        var $input4 = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Rubric List", "list": "rubrics"}).html("Rubric List")
        var $rubriclist = $("<datalist id='rubrics'>")
        var $newRubricBtn = $("<button class='create-rubric btn-filled-grey'>").html("Create Rubric")
        $.post(
            "get-all-rubrics.php",
            data,
            function(res){
                
                let data = JSON.parse(res.message)
                
                for(var i=0; i<data.length;i++){
                    $option = $('<option>').html(data[i].rubricName)
                    $rubriclist.append($option)
                    
                }
            },
            'json'
        )
        $div.append($input1)
        $div.append($datalist)
        $div.append($input2)
        $div.append($input3)
        
        $div.append($input4)
        $div.append($rubriclist)
        $div.append($newRubricBtn)
        $div.append($p)

        $('.assessments-container').append($div)

        var $div1 = $("<div>", {"class": "assessment1 clearfix mdl-textfield mdl-js-textfield is-dirty is-upgraded", "style": "cursor:pointer;"})
        
        $.post(
            "get-courses-assessments.php",
            data,
            function(res){
                res = JSON.parse(res.message)
                let $hiddenDiv = $('<div class="assessmentRubric" style="display:none;">')
                for(var i=0;i<res.length;i++){
                    
                    
                    if(i==0){
                        let $p1 = $('<p class="text text-7">').html(res[i].courseId)
                        $p1.attr("value", res[i].sectionId)
                        $p1.click(LoadCourseWithAssessments)
                        let $span1 = $('<span class="down-caret" style="margin:1%;">')
                        let $p2 = $('<p class="text text-8">').html(res[i].assessmentDescription)
                        $p2.click(AddAssessmentInfo)
                        $div1.append($p1)
                        $div1.append($span1)

                        $hiddenDiv.append($p2)
                        
                    }else if(res[i].sectionId == res[i-1].sectionId){
                        let $p1 = $('<p class="text text-8">').html(res[i].assessmentDescription)
                        $p1.click(AddAssessmentInfo)
                        $hiddenDiv.append($p1)
                    }else{
                        $div1.append($hiddenDiv)
                        $('.assessments-container').append($div1)

                        $div1 = $("<div>", {"class": "assessment1 clearfix mdl-textfield mdl-js-textfield is-dirty is-upgraded", "style": "cursor:pointer;"})
                        $hiddenDiv = $('<div class="assessmentRubric" style="display:none;">')
                        let $p1 = $('<p class="text text-7">').html(res[i].courseId)
                        $p1.attr("value", res[i].sectionId)
                        $p1.click(LoadCourseWithAssessments)
                        let $span1 = $('<span class="down-caret" style="margin:1%;">')
                        let $p2 = $('<p class="text text-8">').html(res[i].assessmentDescription)
                        $div1.append($p1)
                        $div1.append($span1)

                        $hiddenDiv.append($p2)
                    }
                }
                $div1.append($hiddenDiv)
                $('.assessments-container').append($div1)
            },
            'json'
        )
        
        
        //$div.append($label)
        
    })

    $('.assessments-container').on('click', '.create-rubric', function(){
        var $div = $("<div>", {"class": "assessment1 clearfix mdl-textfield mdl-js-textfield is-dirty is-upgraded"})
        var $input = $("<input>", {"class": "mdl-textfield__input assessment-input", "type": "text", "placeholder": "Rubric Name"})
        var $input1 = $('<textarea class="not-meets mdl-textfield__input" placeholder="Does not meet expectations" style="overflow: hidden; overflow-wrap: break-word; height: 75px;">')
        var $input2 = $('<textarea class="meets mdl-textfield__input" placeholder="Meets expectations" style="overflow: hidden; overflow-wrap: break-word; height: 75px;">')
        var $input3 = $('<textarea class="exceeds mdl-textfield__input" placeholder="Exceeds expectations" style="overflow: hidden; overflow-wrap: break-word; height: 75px;">')
        $div.append($input)
        $div.append($input1)
        $div.append($input2)
        $div.append($input3)
        $(this).after($div)
        $(this).prev().detach()
        $(this).prev().detach()
    })
    $('.assessments-container').on('click', '.down-caret', function(){
        $(this).addClass("open-caret")
        $(this).parent().find('.assessmentRubric').fadeIn(200)
    })
    $('.assessments-container').on('click', '.open-caret', function(){
        $(this).removeClass("open-caret")
        $(this).parent().find('.assessmentRubric').fadeOut(100)
    })
    $('.close-popup').click(function(){
        $('.dark-bg').fadeOut(300);
        $('.are-you-sure-popup').fadeOut(300)
    })
    $('.dont-replace-assessments').click(function(){
        $('.dark-bg').fadeOut(300);
        $('.are-you-sure-popup').fadeOut(300)
    })
    
    function LoadCourseWithAssessments(){
        $('.dark-bg').fadeIn(500);
        $('.are-you-sure-popup').fadeIn(500)
        let data = {
            "pastSectionId": $(this).attr("value"),
            "major": substr[1],
            "course": substr[0]
        }
        $('.yes-replace-assessments').unbind( "click" );
        $('.yes-replace-assessments').click(function(){
            $.post(
                "update-all-assessments.php",
                data,
                function(res){
                    res = JSON.parse(res.message)
                    console.log(res)
                    window.location.reload()
                },
                'json'
            )
            
        })
        
        
    }
    function AddAssessmentInfo(){
        let sectionId = $(this).parent().prev().prev().attr("value")
        let assessmentDesc = $(this).html()
        var data = {
            "pastSectionId": sectionId,
            "assessmentDescription": assessmentDesc,
            "major": substr[1],
            "course": substr[0],
            "outcomeId": outcomeId
        }
        alert(data)
    }
});