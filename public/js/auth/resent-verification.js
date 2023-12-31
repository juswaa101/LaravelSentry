$(document).ready(function () {
    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Resend verification email
    $("#resendBtn").click(function (e) {
        e.preventDefault();

        $.ajax({
            type: "post",
            url: "/resent-verification",
            dataType: "json",
            beforeSend: function () {
                // Show loading spinner
                $("#resendBtn").attr("disabled", true);
                $("#resendBtn").html(
                    '<i class="fas fa-spinner fa-spin"></i> Please wait'
                );
            },
            complete: function () {
                // Hide loading spinner
                $("#resendBtn").attr("disabled", false);
                $("#resendBtn").html("Resend Verification Link");
            },
            success: function (response) {
                // Show success alert
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    timer: 2000,
                    timerProgressBar: true,
                });
            },
            error: function (error) {
                // Too many requests
                if (error.status === 429) {
                    // Get remaining time from header
                    let remainingTime = error.getResponseHeader("Retry-After");

                    // Set remaining time
                    let timeLeft = remainingTime;

                    // Start countdown
                    let timer = setInterval(() => {
                        if (timeLeft === 0) {
                            $("#resendBtn").attr("disabled", false);
                            $("#resendBtn").html("Resend Verification Link");
                            clearInterval(timer);
                            return;
                        }

                        $("#resendBtn").attr("disabled", true);

                        // set time in button
                        $("#resendBtn").html(
                            `Resend Verification Link (${timeLeft})`
                        );
                        timeLeft--;
                    }, 1000);
                }

                // Server error
                if (error.status === 500) {
                    // Show alert
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong!",
                    });
                }
            },
        });
    });
});

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
