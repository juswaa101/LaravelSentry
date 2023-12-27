$(document).ready(function () {
    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // endpoint
    const endpoint = $("script[src$='js/auth/login.js']").data("login");

    // Login form submit
    $("#loginBtn").click(function (e) {
        e.preventDefault();

        let loginForm = $("#loginForm")[0];
        let loginFormData = new FormData(loginForm);

        $.ajax({
            type: "post",
            url: endpoint,
            data: loginFormData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // Clear error messages
                $("#showMessage").html("");

                // Clear too many login attempts alert
                $("#tooManyAttemptsMessage").html("");

                // Show loading
                $("#loginBtn").html(
                    '<i class="fa fa-spinner fa-spin"></i> Please wait'
                );
                $("#loginBtn").attr("disabled", true);
            },
            complete: function () {
                // Hide loading
                $("#loginBtn").html("Login");
                $("#loginBtn").attr("disabled", false);
            },
            success: function (response) {
                // Clear form and error messages
                loginForm.reset();
                $("#showMessage").html("");

                // Show alert
                showAlert(
                    "success",
                    "Login Successfully",
                    response.message,
                    1500
                );

                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = "/";
                }, 1000);
            },
            error: function (error) {
                // Validation error
                if (error.status == 422) {
                    // Show validation error messages
                    handleValidationErrors(error.responseJSON.errors);
                }

                // Too many login attempts
                if (error.status === 429) {
                    // Clear form
                    loginForm.reset();

                    // Clear error messages
                    $("#showMessage").html("");

                    // Get remaining time from header
                    let remainingTime =
                        error.getResponseHeader("Retry-After");

                    // Show too many login attempts alert
                    $("#tooManyAttemptsMessage").html(
                        `<div class="alert alert-danger alert-dismissible fade show">
                            <p><strong>Whoops!</strong> Too many login attempts. Please try again later in <span id="timerThrottle">1</span></p>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                        </div>`
                    );

                    // Show timer
                    showThrottleTime(remainingTime);
                }

                // Invalid credentials
                if (error.status == 401) {
                    // Show alert
                    showAlert(
                        "error",
                        "Login Failed",
                        error.responseJSON.message,
                        1500
                    );
                }

                // Server error
                if (error.status === 500) {
                    // Show alert
                    showAlert(
                        "error",
                        "Login Failed",
                        "Something went wrong. Please try again later"
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

    // Function to show alert with timer
    function showThrottleTime(remainingTime) {
        let timeLeft = remainingTime;
        let timerThrottle = $("#timerThrottle");
        let msgElement = $("#tooManyAttemptsMessage");

        // Set initial message
        updateTimerDisplay(timerThrottle, timeLeft);

        // Start countdown
        let timerId = setInterval(function () {
            if (timeLeft === 0) {
                clearInterval(timerId);
                msgElement.html("");
            } else {
                updateTimerDisplay(timerThrottle, timeLeft);
                timeLeft--;
            }
        }, 1000);
    }

    // Function to update timer display
    function updateTimerDisplay(timerThrottleElement, timeLeft) {
        timerThrottleElement.html(
            timeLeft + " second" + (timeLeft !== 1 ? "s" : "")
        );
    }
});
