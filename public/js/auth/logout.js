$(document).ready(function () {
    // logout event
    $("#logoutBtn").click(function (e) {
        e.preventDefault();

        // Get logout endpoint
        const endpoint = $("script[src$='js/auth/logout.js']").data("logout");

        // Call ajax logout
        $.ajax({
            type: "get",
            url: endpoint,
            data: "data",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    // Redirect to login page
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: response.message,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                    }).then(() => {
                        location.href = "/login";
                    });
                }
            },
            error: function (error) {
                // Show alert
                Swal.fire({
                    icon: "error",
                    title: "Logout Failed",
                    text: "Something went wrong. Please try again later",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1500,
                });
            },
        });
    });
});
