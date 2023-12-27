<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CRUD Products Using AJAX</title>
    @include('partials.header')
</head>

<body>
    @include('partials.navbar')

    <div class="container p-5">
        <h1 class="d-inline">Products</h1>
        <button type="button" class="btn btn-success d-inline float-end" fdprocessedid="rv54cp" data-toggle="tooltip"
            data-placement="top" title="Click here to add" id="addProduct">Add Product</button>
        <div class="row my-5">
            <div class="col-md-12">
                <table id="products-table" class="table table-striped table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/products/products.js') }}"></script>
    <script src="{{ asset('js/products/delete-product.js') }}"></script>
    <script src="{{ asset('js/products/store-product.js') }}" data-store="{{ route('products.store') }}"></script>
    <script src="{{ asset('js/products/edit-product.js') }}"></script>
    <script src="{{ asset('js/products/update-product.js') }}"></script>

    @include('components.modals.products.create-product-modal')
    @include('components.modals.products.edit-product-modal')

</body>

</html>
