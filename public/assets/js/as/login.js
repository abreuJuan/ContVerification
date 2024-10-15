var valid = false;

$("#login-form").submit(function (e) {
    var $form = $(this);

    if (! $form.valid()) {
        return false;
    }

    as.btn.loading($("#btn-login"));

    return true;
});

$(document).ready(function(){
    document.getElementsByTagName("body")[0].setAttribute("style","background: url('assets/img/fondo.bmp');background-size:cover;");
});