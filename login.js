$(document).ready(function () {
    // Trigger when login form is submitted
    $(document).on('submit', '#login_form', function () {
        // Get form data
        var login_form = $(this);
        var form_data = JSON.stringify(login_form.serializeObject());

        // Submit form data to API
        $.ajax({
            url: "api/login.php",
            type: "POST",
            contentType: "application/json",
            data: form_data,
            success: function (result) {
                // Store JWT to cookie
                setCookie("jwt", result.jwt, 1);

                // Show home page & tell user it was successful
                showHomePage();
            },
            error: function (xhr, resp, text) {
                // On error, tell the user login has failed & empty input boxes
                $('#login_response').html("<div style='color: red;'>Login failed. Email or password is incorrect.</div>");
                login_form.find('input').val('');
            }
        });

        return false;
    });

    // show home page
    function showHomePage() {

        // validate jwt to verify access
        var jwt = getCookie('jwt');
        $.post("api/validate_token.php", JSON.stringify({ jwt: jwt })).done(function (result) {
            window.location.replace("http://stackoverflow.com");
        })

        // show login page on error will be here
    }

    // function to set cookie
    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    // get or read cookie
    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }

            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

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