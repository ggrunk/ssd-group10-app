
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

$(window).on('beforeunload', function(){
    var survey = {};
    $('input.survey-form[type=number]').each(function() {
        survey[$( this ).attr('id')] = $( this ).val();
    });
    // console.log(survey);
    console.log('saved survey to cookie');
    setCookie('survey_data', JSON.stringify(survey), 365)
    // return 'Are you sure you want to leave?';
});

$( document ).ready(function() {
    (function () {
        'use strict'
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
});