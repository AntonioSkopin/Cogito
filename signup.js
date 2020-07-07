$(document).ready(function () {
    // trigger when registration form is submitted here
    $(document).on('submit', '#sign_up_form', function () {
        // get form data
        var sign_up_form = $(this);
        var form_data = JSON.stringify(sign_up_form.serializeObject());

        // Get input value of passwords
        var password = $('#password').val();
        var password2 = $('#password2').val();

        // Check if passwords match
        if (password == password2) {
            // Response message when succeeded
            var successhtml = `<div style='color: green;'>Account is aangemaakt.</div>`;

            // submit form data to api
            $.ajax({
                url: "api/create_user.php",
                type: "POST",
                contentType: 'application/json',
                data: form_data,
                success: function (result) {
                    // if response is a success, tell the user it was a successful sign up & empty the input boxes
                    $('#signup_response').html(successhtml);
                    sign_up_form.find('input').val('');
                },
                error: function (xhr, resp, text) {
                    // on error, tell the user sign up failed
                    $('#signup_response').html("<div>Er is iets fouts gegaan.</div>");
                }
            });
        } else {
            $('#signup_response').html("<div style='color: red;'>Wachtwoorden komen niet overeen</div>");
        }

        return false;
    });

    // serializeObject will be here
    $.fn.serializeObject = function () {

        var o = {};
        var a = this.serializeArray();
        $.each(a, function () {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
});