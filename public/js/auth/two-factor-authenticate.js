$(document).ready(function () {
    // Initialize QR Scanner
    var html5QrcodeScanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 250,
    });

    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Verify Two Factor Authentication
    $("#verifyBtn").click(function (e) {
        e.preventDefault();

        // Get Form Data
        let twoFactorAuthForm = $("#twoFactorAuthForm")[0];
        let twoFactorAuthFormData = new FormData(twoFactorAuthForm);

        $.ajax({
            type: "post",
            url: "/two-factor-authentication",
            data: twoFactorAuthFormData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#showMessage").html("");

                // Show loading button
                $("#verifyBtn").attr("disabled", true);
                $("#verifyBtn").html(
                    '<i class="fa fa-spinner fa-spin"></i> Verifying...'
                );
            },
            complete: function () {
                // Hide loading button
                $("#verifyBtn").attr("disabled", false);
                $("#verifyBtn").html("Verify");
            },
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message,
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timerProgressBar: true,
                    timer: 1000,
                });

                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = "/";
                }, 1000);
            },
            error: function (error) {
                // Handle validation errors
                if (error.status === 422) {
                    handleValidationErrors(error.responseJSON.errors);
                }

                // Handle invalid token
                if (error.status === 404) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: error.responseJSON.message,
                    });
                }

                // Handle invalid code
                if (error.status === 500) {
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: error.responseJSON.message,
                    });
                }
            },
        });
    });

    // Show QR Scanner
    $("#showScanner").click(function (e) {
        e.preventDefault();

        // Start QR Scanner
        html5QrcodeScanner.render(onScanSuccess);
    });

    // Function to handle QR Code scan success
    function onScanSuccess(decodedText, decodedResult) {
        // Set QR Code value
        $('#code').val(decodedText);

        // Show success message
        Swal.fire({
            icon: "success",
            title: "Success!",
            text: "QR Code scanned successfully",
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1000,
        });

        // Stop QR Scanner
        html5QrcodeScanner.clear();
    }

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
