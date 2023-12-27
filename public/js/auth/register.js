$(document).ready(function () {
    // Password strength meter
    let passwordField = $("#password");

    // Password strength meter options
    let options = {
        enterPass: "Type your password",
        shortPass: "The password is too short",
        containsField: "The password contains your username",
        steps: {
            // Easily change the steps' expected score here
            13: "Really insecure password",
            33: "Weak; try combining letters & numbers",
            67: "Medium; try using special characters",
            94: "Strong password",
        },
        showPercent: false,
        showText: true, // shows the text tips
        animate: true, // whether or not to animate the progress bar on input blur/focus
        animateSpeed: "fast", // the above animation speed
        field: false, // select the match field (selector or jQuery instance) for better password checks
        fieldPartialMatch: true, // whether to check for partials in field
        minimumLength: 8, // minimum password length (below this threshold, the score is 0)
        useColorBarImage: true, // use the (old) colorbar image
        customColorBarRGB: {
            red: [0, 240],
            green: [0, 240],
            blue: 10,
        }, // set custom rgb color ranges for colorbar.
    };

    passwordField.password(options);

    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Confirm password validation
    $("#confirm_password").keyup(function (e) {
        // Clear password confirmation if password is empty
        if ($(this).val().length == 0) {
            $(this).removeClass("is-invalid");
            $(this).removeClass("is-valid");
            $(this).next().html("");
        }

        // Check if password confirmation match
        if ($(this).val().length > 0 && $(this).val() == $("#password").val()) {
            $(this).removeClass("is-invalid");
            $(this).addClass("is-valid");

            $("#confirm_password")
                .next()
                .html(
                    `<span class="text text-success">Password confirmation match</span>`
                );
        }

        // Check if password confirmation does not match
        if ($(this).val().length > 0 && $(this).val() != $("#password").val()) {
            $(this).removeClass("is-valid");
            $(this).addClass("is-invalid");

            $("#confirm_password")
                .next()
                .html(
                    `<span class="text text-danger">Password confirmation does not match</span>`
                );
        }
    });

    // Register form submit
    $("#registerBtn").click(function (e) {
        e.preventDefault();

        // Get data from form
        let registerForm = $("#registerForm")[0];
        let registerFormData = new FormData(registerForm);

        // Get the route endpoint from the data attribute
        const endpoint = $("script[src$='js/auth/register.js']").data("store");

        // Call ajax register
        $.ajax({
            type: "post",
            url: endpoint,
            data: registerFormData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // Clear error messages
                $("#showMessage").html("");

                // Show loading
                $("#registerBtn").html(
                    '<i class="fa fa-spinner fa-spin"></i> Please wait'
                );
                $("#registerBtn").attr("disabled", true);
            },
            complete: function () {
                // Hide loading
                $("#registerBtn").html("Register");
                $("#registerBtn").attr("disabled", false);
            },
            success: function (response) {
                registerForm.reset();
                $("#showMessage").html("");

                // clear password strength meter
                passwordField.val("");
                passwordField.trigger("input");

                // clear password
                $(".pass-text").html("");
                $(".pass-colorbar").removeAttr("style");

                // unfocus password
                passwordField.blur();

                // clear password confirmation
                $("#confirm_password").removeClass("is-invalid");
                $("#confirm_password").removeClass("is-valid");
                $("#confirm_password").next().html("");

                // Show alert
                showAlert(
                    "success",
                    "Sign Up Successfully",
                    response.message,
                    1500
                );
            },
            error: function (error) {
                if (error.status == 422) {
                    // Validation error
                    handleValidationErrors(error.responseJSON.errors);
                }

                if (error.status === 500) {
                    // Show alert
                    showAlert(
                        "error",
                        "Sign Up Failed",
                        "Something went wrong. Please try again later",
                        1500
                    );
                }
            },
        });
    });

    // Function to handle validation errors
    function handleValidationErrors(errors) {
        $("#showMessage").html("");
        $("#showMessage").append(
            `<div class="alert alert-danger alert-dismissible fade show">
                <p><strong>Whoops!</strong> Your input is invalid</p>
                <ul id="errorList"></ul>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
            </div>`
        );

        $.each(errors, function (key, value) {
            $(`#errorList`).append("<li>" + value + "</li>");
        });
    }

    // Function to show alert
    function showAlert(icon, title, text, duration) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: duration,
        });
    }
});
