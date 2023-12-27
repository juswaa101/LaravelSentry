$(document).ready(function () {
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

    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#resetPasswordBtn").click(function (e) {
        e.preventDefault();

        // 1. Get form data
        let resetPasswordForm = $("#resetPasswordForm")[0];
        let resetPasswordFormData = new FormData(resetPasswordForm);

        let endpoint = "/reset-password";

        $.ajax({
            type: "post",
            url: endpoint,
            data: resetPasswordFormData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // Show loader
                $("#resetPasswordBtn").html(
                    '<i class="fas fa-spinner fa-spin"></i> Loading...'
                );
                $("#resetPasswordBtn").prop("disabled", true);
            },
            complete: function () {
                // Dismiss loader
                $("#resetPasswordBtn").html("Reset Password");
                $("#resetPasswordBtn").prop("disabled", false);
            },
            success: function (response) {
                // clear error messages
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

                // show success message and redirect to login page
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                }).then(function () {
                    window.location.href = "/login";
                });
            },
            error: function (error) {
                // if validation errors occur
                if (error.status === 422) {
                    handleValidationErrors(error.responseJSON.errors);
                }

                // if user is not found
                if (error.status === 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.responseJSON.message,
                    });
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
});
