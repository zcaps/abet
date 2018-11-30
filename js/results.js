$(document).ready(function(){
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