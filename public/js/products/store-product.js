$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $("#addProduct").click(function (e) {
        e.preventDefault();
        $("#createProductModal").modal("show");
    });

    // Event listener for submit button
    $("#submitBtn").click(function (e) {
        e.preventDefault();

        // Product Form Data
        let productForm = $("#productForm")[0];
        let productFormData = new FormData(productForm);

        // Get the route endpoint from the data attribute
        const endpoint = $("script[src$='js/store-product.js']").data("store");

        // Product AJAX Request
        $.ajax({
            type: "post",
            url: endpoint,
            data: productFormData,
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $("#showMessage").html(""); // Reset Message
                resetErrors(); // Reset Error messages
                showLoader(); // Show Loader
            },
            complete: function () {
                resetErrors(); // Reset Error messages
                hideLoader(); // Hide Loader
            },
            success: function (response) {
                productForm.reset(); // Reset Form
                showSuccessMessage(response.message); // Show Success Message
                if (productsTable !== undefined) {
                    productsTable.ajax.reload(); // Reload Products Table
                    $("#createProductModal").modal("hide");
                } else {
                    location.reload(); // Reload Page
                }
            },
            error: function (error) {
                handleErrors(error); // Handle Validation and Server Errors
            },
        });
    });

    // Event listener for quantity input
    $("#qty").on("input", function () {
        calculateTotal();
    });

    // Event listener for price input
    $("#price").on("input", function () {
        calculateTotal();
    });

    // Function to reset error messages
    function resetErrors() {
        $("#nameError, #priceError, #qtyError, #totalError").html("");
    }

    // Function to show loader
    function showLoader() {
        $("#submitBtn").html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        );

        $("#submitBtn").prop("disabled", true);
    }

    // Function to hide loader
    function hideLoader() {
        $("#submitBtn").html("Submit");
        $("#submitBtn").prop("disabled", false);
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

    // Function to handle server error
    function handleServerError(message) {
        Swal.fire({ title: "Error", text: message, icon: "error" });
    }

    // Function to calculate total
    function calculateTotal() {
        let quantity = parseFloat($("#qty").val());
        let price = parseFloat($("#price").val());

        if (!isNaN(quantity) && !isNaN(price)) {
            let total = quantity * price;
            $("#total").val(total.toFixed(2));
        }
    }
});
