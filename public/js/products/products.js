let productsTable;

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();

    productsTable = $("#products-table").DataTable({
        responsive: true,
        processing: true,
        ajax: {
            url: "/api/products",
            dataType: "json",
            dataSrc: "data",
        },
        initComplete: function () {
            $('[data-toggle="tooltip"]').tooltip();
        },
        columns: [
            {
                data: null,
                title: "#",
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                },
            },
            { data: "name", title: "Name" },
            {
                data: "price",
                title: "Price",
                render: function (data) {
                    const currencyFormatter = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "PHP",
                    });
                    const formattedCurrency = currencyFormatter.format(data);

                    // Return the formatted currency
                    return formattedCurrency;
                },
            },
            { data: "qty", title: "Quantity" },
            {
                data: null,
                title: "Total",
                render: function (data) {
                    // Convert the number to currency format
                    const currencyFormatter = new Intl.NumberFormat("en-US", {
                        style: "currency",
                        currency: "PHP",
                    });
                    const formattedCurrency = currencyFormatter.format(
                        data.total
                    );

                    // Return the formatted currency
                    return formattedCurrency;
                },
            },
            {
                data: null,
                render: function (data) {
                    return `
                    <button class="btn btn-primary btn-md edit-product" data-toggle="tooltip"
                            data-placement="top" title="Edit Product" data-val="${data.id}"><i class="fa fa-pencil">
                                </i></button>
                        <button class="btn btn-danger btn-md delete-product" data-toggle="tooltip"
                            data-placement="top" title="Delete Product" data-val="${data.id}"><i class="fa fa-trash">
                                </i></button>
                    `;
                },
                title: "Action",
            },
        ],
    });

    productsTable.on("draw", function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
});
