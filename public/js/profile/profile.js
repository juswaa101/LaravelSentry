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

    getProfilePicture();

    // Set CSRF Token
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    // Preview profile picture event
    $("#profilePicture").change(function () {
        readURL(this);
    });

    // Update profile picture event
    $("#saveProfileBtn").click(function (e) {
        e.preventDefault();

        // Get profile info form data
        let profileInfoForm = $("#profileInfoForm")[0];
        let profileInfoFormData = new FormData(profileInfoForm);

        // endpoint
        const endpoint = $("script[src$='js/profile/profile.js']").data(
            "profile"
        );

        $.ajax({
            type: "post",
            url: endpoint,
            data: profileInfoFormData,
            enctype: "multipart/form-data",
            dataType: "json",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function () {
                // Clear error messages
                $("#showMessage").html("");

                // Show loading button
                $("#saveProfileBtn").html(
                    '<i class="fa fa-spinner fa-spin"></i> Saving...'
                );
                $("#saveProfileBtn").attr("disabled", true);
            },
            complete: function () {
                // Hide loading button
                $("#saveProfileBtn").html("Save Changes");
                $("#saveProfileBtn").attr("disabled", false);
            },
            success: function (response) {
                // Fetch profile picture
                getProfilePicture();

                // scroll up
                $("html, body").animate(
                    {
                        scrollTop: $("#showMessage").offset().top,
                    },
                    {
                        duration: 0,
                        easing: "linear",
                    }
                );

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

                $("#confirm_password").val("");

                // Show alert
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 1500,
                });
            },
            error: function (error) {
                // Validation error
                if (error.status == 422) {
                    // Show validation error messages
                    handleValidationErrors(error.responseJSON.errors);

                    // scroll up to show error messages
                    $("html, body").animate(
                        {
                            scrollTop: $("#showMessage").offset().top,
                        },
                        {
                            duration: 0,
                            easing: "linear",
                        }
                    );
                }

                // Server error
                if (error.status == 500) {
                    // Show alert
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: error.responseJSON.message,
                    });
                }
            },
        });
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
});

// Preview profile picture
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#preview").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
    }
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

// Function to fetch current profile picture
function getProfilePicture() {
    $.ajax({
        type: "get",
        url: "/profile-picture",
        dataType: "json",
        beforeSend: function () {
            // Show loading profile picture
            $("#preview").attr("loading", "lazy");
        },
        complete: function () {
            // Hide loading profile picture
            $("#preview").removeAttr("loading");

            // Trigger lazy loading after profile picture is loaded
            lazyLoadImages();
        },
        success: function (response) {
            // Set profile picture
            if (response.data.avatar) {
                $("#preview").attr("src", "data:image/png;base64," + response.data.avatar);
            }
        },
        error: function (error) {
            // Show alert
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: error.responseJSON.message,
            });
        },
    });
}

// Function to handle lazy loading with fade-in animation
function lazyLoadImages() {
    const lazyLoadObserver = new IntersectionObserver(function (
        entries,
        observer
    ) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                const lazyImage = $(entry.target);

                lazyImage.attr("src", lazyImage.data("src")).addClass("loaded");

                observer.unobserve(entry.target);
            }
        });
    });

    // Observe lazy-load images
    $(".lazy-load").each(function () {
        lazyLoadObserver.observe($(this)[0]);
    });
}
