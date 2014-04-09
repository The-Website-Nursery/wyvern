$(document).ready(function() {
    $(".warnings p").each(function() {
        $(this).prepend('<span class="glyphicon glyphicon-warning-sign form-control-feedback"></span>&nbsp;');
    });
});