$(document).ready(function(){
    $courseBtn = $('.course1')
    $courseBtn.click(function(){
        //substr[0] is the courseId
        let substr =  $(this).html().split(" ")
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
    function appendOutcomes(result){
        $('.outcomes-container').find("div").detach()
        result = JSON.parse(result.message)
        for(var i=0;i<result.length;i++){
            $div = $('<div class="outcome1-container clearfix">')
            $p = $('<p class="text">').html(result[i].outcome)
            $div.append($p)
            $('.outcomes-container').append($div)
        }
    }

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