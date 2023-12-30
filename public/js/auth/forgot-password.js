$(document).ready(function () {
    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Send password reset link
    $("#sendPasswordResetBtn").click(function (e) {
        e.preventDefault();

        let sendPasswordResetForm = $("#sendPasswordResetForm")[0];
        let sendPasswordResetFormData = new FormData(sendPasswordResetForm);

        let endpoint = "/forgot-password/submit";

        $.ajax({
            type: "post",
            url: endpoint,
            data: sendPasswordResetFormData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                // Show loader
                $("#sendPasswordResetBtn").html(
                    '<i class="fas fa-spinner fa-spin"></i> Loading...'
                );
                $("#sendPasswordResetBtn").prop("disabled", true);
            },
            complete: function () {
                // Dismiss loader
                $("#sendPasswordResetBtn").html("Request");
                $("#sendPasswordResetBtn").prop("disabled", false);
            },
            success: function (response) {
                // clear error messages
                $("#showMessage").html("");

                // reset form
                sendPasswordResetForm.reset();

                // Show success message
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                });
            },
            error: function (error) {
                // if account is not verified
                if (error.status == 401) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.responseJSON.message,
                    });
                }

                // if account is not found
                if (error.status == 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: error.responseJSON.message,
                    });
                }

                // if there are validation errors
                if (error.status == 422) {
                    handleValidationErrors(error.responseJSON.errors);
                }

                if (error.status == 429) {
                    // Clear error messages
                    $("#showMessage").html("");

                    // Get remaining time from header
                    let remainingTime = error.getResponseHeader("Retry-After");

                    // Show too many login attempts alert
                    $("#tooManyAttemptsMessage").html(
                        `<div class="alert alert-danger alert-dismissible fade show">
                            <p><strong>Whoops!</strong> Too many request password attempts. Please try again later in <span id="timerThrottle">1</span></p>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
                        </div>`
                    );

                    // Show timer
                    showThrottleTime(remainingTime);
                }

                // if there are server errors
                if (error.status == 500) {
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
