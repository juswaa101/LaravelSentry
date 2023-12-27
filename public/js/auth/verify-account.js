$(document).ready(function () {
    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // verify account
    $("#verifyBtn").click(function (e) {
        e.preventDefault();

        let verifyForm = $("#verifyForm")[0];
        let verifyFormData = new FormData(verifyForm);

        $.ajax({
            type: "post",
            url: "/verify/" + ($('#token').val() ?? "") + "/send",
            data: verifyFormData,
            cache: false,
            processData: false,
            contentType: false,
            dataType: "json",
            beforeSend: function () {
                // Show loading
                $("#verifyBtn").html(
                    '<i class="fa fa-spinner fa-spin"></i> Please wait'
                );
                $("#verifyBtn").prop("disabled", true);
            },
            complete: function () {
                // Hide loading
                $("#verifyBtn").html("Verify Account");
                $("#verifyBtn").prop("disabled", false);
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: response.message,
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/login";
                    }
                });
            },
            error: function (error) {
                if (error.status === 500) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Something went wrong! Please try again later.",
                    });
                }
            },
        });
    });
});
