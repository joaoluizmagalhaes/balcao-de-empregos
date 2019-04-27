var $ = jQuery.noConflict();
$(document).ready(function() {

    $("input[type=hidden]#acf-field_5cc453dc894d1").bind("change", function() {
        alert($(this).val()); 
    });

   
    $('#acf-field_5cc453dc894d1').change(function(){
        console.log(this.val());
    });
});