// Assuming you have a button or some trigger to initiate the copy
function copyToClipboard(inputId) {
    const input = $("#keyCombination" + inputId);

    // Check if the input element exists
    if (input.length === 0) {
        console.error("Element with id '" + inputId + "' not found");
        return;
    }

    input[0].select();

    try {
        document.execCommand("copy");
        console.log("Text copied to clipboard");

        // Show success message
        Swal.fire({
            icon: "success",
            title: "Copied!",
            text: "Key combination copied to clipboard",
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1000,
        });
    } catch (err) {
        // Show error message
        console.error(err);
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Something went wrong!",
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1000,
        });
    }
}

// Generate a random key combination
function generateKeyCombination() {
    let generateKeyCombination = "";
    const charactersPerGroup = 4;
    const totalCharacters = 20;
    const groups = totalCharacters / charactersPerGroup;

    for (let i = 0; i < groups; i++) {
        // Generate a random group of characters
        const randomGroup = Math.random()
            .toString(36)
            .substring(2, 2 + charactersPerGroup);

        // Add the group to the key combination
        generateKeyCombination += randomGroup;

        // Add a dash after each group except the last one
        if (i < groups - 1) {
            generateKeyCombination += "-";
        }
    }

    return generateKeyCombination;
}

// Fetch the user's 2FA status
function fetchTwoFactorAuthStatus() {
    $.ajax({
        url: "/profile/two-factor-auth-status",
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Clear key combinations
            $("#keyCombinations").empty("");

            // Clear QR Code
            $("#qrcode").empty("");

            // Check if 2FA is enabled
            if (response.data.is_two_factor_enabled === 1) {
                // Show 2FA Section
                $("#twoFactorAuthSection").removeClass("d-none");

                // Set checkbox to checked
                $("#enable2fa").prop("checked", true);

                let two_factor_codes = response.data.two_factor_codes;

                let parse = JSON.parse(two_factor_codes);

                $("#qrcode").qrcode({
                    width: 300,
                    height: 300,
                    text: parse[0],
                });

                if (parse) {
                    // Generate 5 Key Combinations
                    for (let i = 0; i < parse.length; i++) {
                        // Append input field for each key combination
                        $("#keyCombinations").append(`
                            <div class="input-group mt-3">
                                <input type="text" class="form-control" id="keyCombination${i}" value="${parse[i]}" readonly>
                                <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('${i}')">
                                    Copy
                                </button>
                            </div>
                        `);
                    }
                }
            } else {
                $("#enable2fa").prop("checked", false);

                // Hide 2FA Section
                $("#twoFactorAuthSection").addClass("d-none");
            }
        },
        error: function (error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.responseJSON.message,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1000,
            });
        },
    });
}

// Update the user's 2FA status
function updateTwoFactorAuthStatus(status, two_factor_codes) {
    $.ajax({
        url: "/profile/two-factor-auth-status",
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            status: status,
            two_factor_codes: two_factor_codes,
        },
        dataType: "json",
        success: function (data) {
            // Show success message
            Swal.fire({
                icon: "success",
                title: "Success!",
                text: data.message,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1000,
            });

            // Fetch the user's 2FA status
            fetchTwoFactorAuthStatus();
        },
        error: function (error) {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.responseJSON.message,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                timerProgressBar: true,
                timer: 1000,
            });
        },
    });
}

$(document).ready(function () {
    // Enable/Disable 2FA Event
    $("#enable2fa").click(function () {
        // Clear existing element
        $("#keyCombinations").empty();

        // Check if 2FA is enabled
        if ($(this).is(":checked")) {
            // Show 2FA Section
            $("#twoFactorAuthSection").removeClass("d-none");

            // Array to store key combinations
            let keys = [];

            // Generate 5 Key Combinations
            for (let i = 0; i < 5; i++) {
                // Generate a random key combination
                let key = generateKeyCombination();

                // Add key to array
                keys.push(key);
            }

            // Update status
            updateTwoFactorAuthStatus(true, JSON.parse(JSON.stringify(keys)));
        } else {
            // Hide 2FA Section
            $("#twoFactorAuthSection").addClass("d-none");

            // Update status
            updateTwoFactorAuthStatus(false, null);
        }
    });

    fetchTwoFactorAuthStatus();
});
