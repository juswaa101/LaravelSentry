$(document).ready(function () {
    $(document).on("click", ".edit-product", function (e) {
        e.preventDefault();
        $("#editProductModal").modal("show");

        $.ajax({
            type: "get",
            url: `/products/${$(this).data("val")}/edit`,
            dataType: "json",
            beforeSend: function () {
                $("#editName").prop("disabled", true);
                $("#editPrice").prop("disabled", true);
                $("#editQty").prop("disabled", true);
                $("#editTotal").prop("disabled", true);
            },
            complete: function () {
                $("#editName").prop("disabled", false);
                $("#editPrice").prop("disabled", false);
                $("#editQty").prop("disabled", false);
                $("#editTotal").prop("disabled", false);
            },
            success: function (response) {
                $('#editId').val(response.data.id);
                $("#editName").val(response.data.name);
                $("#editPrice").val(response.data.price);
                $("#editQty").val(response.data.qty);
                $("#editTotal").val(response.data.total.toFixed(2));
                $("#editProductForm").attr(
                    "action",
                    `/products/${response.id}`
                );
            },
            error: function (error) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                })
            },
        });
    });

    // Event listener for quantity input
    $("#editQty").on("input", function () {
        calculateTotal();
    });

    // Event listener for price input
    $("#editPrice").on("input", function () {
        calculateTotal();
    });

    // Function to calculate total
    function calculateTotal() {
        let quantity = parseFloat($("#editQty").val());
        let price = parseFloat($("#editPrice").val());

        if (!isNaN(quantity) && !isNaN(price)) {
            let total = quantity * price;
            $("#editTotal").val(total.toFixed(2));
        }
    }
});
