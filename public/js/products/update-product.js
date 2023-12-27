$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on("click", ".updateBtn", function (e) {
        e.preventDefault();

        let editForm = $("#editProductForm")[0];
        let editFormData = new FormData(editForm);

        editFormData.append("_method", "PUT");

        $.ajax({
            type: "POST",
            url: `/products/${$("#editId").val()}`,
            data: editFormData,
            dataType: "json",
            cacha: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#editShowMessage").html("");
                resetErrors();
                showLoader();
            },
            complete: function () {
                resetErrors(); // Reset Error messages
                hideLoader();
            },
            success: function (response) {
                showSuccessMessage(response.message); // Show Success Message
                productForm.reset(); // Reset Form

                if (productsTable !== undefined) {
                    productsTable.ajax.reload(); // Reload Products Table
                    $("#editProductModal").modal("hide");
                } else {
                    location.reload(); // Reload Page
                }
            },
            error: function (error) {
                handleErrors(error);
            },
        });
    });

    // Function to reset error messages
    function resetErrors() {
        $("#nameError, #priceError, #qtyError, #totalError").html("");
    }

    // Function to show loader
    function showLoader() {
        $(".updateBtn").html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        );

        $(".updateBtn").prop("disabled", true);
    }

    // Function to hide loader
    function hideLoader() {
        $(".updateBtn").html("Update");
        $(".updateBtn").prop("disabled", false);
    }

    // Function to show success message
    function showSuccessMessage(message) {
        Swal.fire({
            title: "Success",
            text: message,
            icon: "success",
        });
    }

    // Function to handle errors
    function handleErrors(error) {
        if (error.status === 422) {
            handleValidationErrors(error.responseJSON.errors);
        } else if (error.status === 500) {
            handleServerError(error.responseJSON.message);
        }
    }

    // Function to handle validation errors
    function handleValidationErrors(errors) {
        $("#editShowMessage").html("");
        $("#editShowMessage").append(
            `<div class="alert alert-danger alert-dismissible fade show">
                <p><strong>Whoops!</strong> Your input is invalid</p>
                <ul id="editErrorList"></ul>
                <a class="btn-close" data-bs-dismiss="alert" aria-label="Close"></a>
            </div>`
        );

        $.each(errors, function (key, value) {
            $(`#editErrorList`).append("<li>" + value + "</li>");
        });
    }

    // Function to handle server error
    function handleServerError(message) {
        Swal.fire({ title: "Error", text: message, icon: "error" });
    }
});
