$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
    });

    $(document).on("click", ".delete-product", function (e) {
        e.preventDefault();
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                // Get the route endpoint from the data attribute
                const id = $(this).data("val");

                $.ajax({
                    type: "delete",
                    url: "/products/" + id,
                    dataType: "json",
                    beforeSend: function () {
                        // Show the loading button
                        $(".delete-product").addClass("disabled");
                        $(".delete-product").html(
                            '<i class="fa fa-spinner fa-spin"></i> Deleting'
                        );
                    },
                    complete: function () {
                        // Hide the loading button
                        $(".delete-product").removeClass("disabled");
                        $(".delete-product").html(
                            '<i class="fa fa-trash"></i>'
                        );
                    },
                    success: function (response) {
                        // Reload the DataTable
                        if (productsTable !== undefined) {
                            productsTable.ajax.reload();
                        } else {
                            location.reload();
                        }

                        // Show the success alert
                        Swal.fire({
                            title: "Deleted!",
                            text: "Product has been deleted.",
                            icon: "success",
                        });
                    },
                    error: function (error) {
                        if (error.status === 500) {
                            Swal.fire({
                                title: "Error!",
                                text: "Product cannot be deleted!",
                                icon: "error",
                            });
                        }
                    },
                });
            }
        });
    });
});
