$(document).ready(function () {
    console.log("browser-session.js loaded");

    $("#logoutAllBtn").click(function (e) {
        e.preventDefault();

        // Show confirm alert with type confirmation
        Swal.fire({
            title: "Are you sure?",
            text: `To confirm logging out all devices, type "confirm" `,
            input: "text",
            inputAttributes: {
                autocapitalize: "off",
            },
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, logout all!",
            cancelButtonText: "No, cancel!",
            preConfirm: (value) => {
                if (value == "confirm") {
                    $.ajax({
                        type: "POST",
                        url: "/logout-other-browser-sessions",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                        },
                        beforeSend: function () {
                            // Show loading
                            $("#logoutAllBtn").attr("disabled", true);
                            $("#logoutAllBtn").html(
                                '<i class="fas fa-spinner fa-spin"></i>'
                            );
                        },
                        complete: function () {
                            // Hide loading
                            $("#logoutAllBtn").attr("disabled", false);
                            $("#logoutAllBtn").html(
                                '<i class="fas fa-sign-out-alt"></i> Logout From All Devices'
                            );
                        },
                        success: function (response) {
                            // Show success message
                            Swal.fire({
                                title: "Success",
                                text: "Logged all device successfully, please login again",
                                icon: "success",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 2000,
                            });

                            // Redirect to login
                            setTimeout(() => {
                                location.href = "/login";
                            }, 1000);
                        },
                        error: function (error) {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong",
                                icon: "error",
                            });
                        },
                    });
                } else {
                    Swal.showValidationMessage(
                        `Please type "confirm" to logout all devices`
                    );
                }
            },
        });
    });

    $(".currentLogoutBtn").click(function (e) {
        // Show confirm alert with type confirmation
        Swal.fire({
            title: "Are you sure?",
            text: `To confirm logging out this device, type "confirm" `,
            input: "text",
            inputAttributes: {
                autocapitalize: "off",
            },
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, logout this device!",
            cancelButtonText: "No, cancel!",
            preConfirm: (value) => {
                if (value == "confirm") {
                    $.ajax({
                        type: "POST",
                        url: "/logout-browser-session",
                        data: {
                            _token: $('meta[name="csrf-token"]').attr(
                                "content"
                            ),
                            session_id: $(this).data('val')
                        },
                        beforeSend: function () {
                            // Show loading
                            $(".currentLogoutBtn").attr("disabled", true);
                            $(".currentLogoutBtn").html(
                                '<i class="fas fa-spinner fa-spin"></i>'
                            );
                        },
                        complete: function () {
                            // Hide loading
                            $(".currentLogoutBtn").attr("disabled", false);
                            $(".currentLogoutBtn").html(
                                '<i class="fas fa-sign-out-alt"></i> Logout All'
                            );
                        },
                        success: function (response) {
                            // Show success message
                            Swal.fire({
                                title: "Success",
                                text: "Device Logged Out Successfully!",
                                icon: "success",
                                allowEscapeKey: false,
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 2000,
                            });

                            // if current user is being logged out
                            if (response.data.isCurrentUser && response.data) {
                                // Redirect to login
                                setTimeout(() => {
                                    location.href = "/login";
                                }, 1000);
                            } else {
                                // Refresh Page
                                setTimeout(() => {
                                    location.reload();
                                }, 1000);
                            }
                        },
                        error: function (error) {
                            Swal.fire({
                                title: "Error",
                                text: "Something went wrong",
                                icon: "error",
                            });
                        },
                    });
                } else {
                    Swal.showValidationMessage(
                        `Please type "confirm" to logout this device`
                    );
                }
            },
        });
    });
});
