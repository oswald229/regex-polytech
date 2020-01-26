function sortlist() {
    var lb = document.getElementById('matiere-select');
    arrTexts = new Array();

    for(i=0; i<lb.length; i++)  {
    arrTexts[i] = lb.options[i].text;
    }

    arrTexts.sort();

    for(i=0; i<lb.length; i++)  {
    lb.options[i].text = arrTexts[i];
    lb.options[i].value = arrTexts[i];
    }
}

$(document).ready(function(){
    $('#bouton').click(function(){
        $('#file').click();
    });

    $('#file').change(function(e){
        var fileName = e.target.files[0].name;
        $('#file_path').val(fileName);
    });

    $('#matiere-select').click(function(){
        sortlist();
    });

    $('#matiere-select').change(function(){
        $('#regex-submit').click();
    });
});