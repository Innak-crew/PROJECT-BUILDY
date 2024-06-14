@extends('layout.admin-app')
@section('adminContent')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">

<style>
    .result {
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        border-radius: 5px;
        background-color: #f9f9f9;
        padding: 10px;
        margin-bottom: 1px;
    }

    .product-details {
        padding-left: 15px;
    }

    .product-name {
        font-weight: bold;
    }

</style>
@endpush


<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">{{$title}}</h5>
    </div>




    <div class="card-body p-4">
        <div class="row">

        <form action="{{ route('order.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-6 mb-4">
                        <label for="name">Order Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="" required/>
                        @error('name')
                            <div class="invalid-feedback">
                                <p class="error">{{ $message }}</p>
                            </div>
                        @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="description">Order Description</label>
                    <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control typeahead @error('description') is-invalid @enderror" placeholder="" />
                    @error('description')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="customer">Customer *</label>
                    <select class="customer-details form-control mb-4" id="customer" name="customer" id="customer" required></select>
                    <small class="form-control-feedback">If customer is not found , <a href="{{route('admin.customer.add')}}" target="_blank">click here to add customer</a></small>
                    @error('customer')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="location">Order location *</label>
                    <input type="text" name="location" id="location" value="{{ old('location') }}" class="form-control @error('location') is-invalid @enderror" placeholder="" />
                    @error('location')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="type">Order Type *</label>
                    <select class="form-select mr-sm-2" id="type" name="type" required>
                        <option value="" disabled selected>Choose...</option>
                        <option value="Interior" >Interior</option>
                        <option value="Exterior" >Exterior</option>
                        <option value="Both" >Both</option>
                    </select>
                    @error('type')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label class="control-label">Order Starting Date *</label>
                    <input type="date" class="form-control" />
                </div>

                <div class="col-md-6 mb-4">
                    <label class="control-label">Order Ending Date </label>
                    <input type="date" class="form-control" />
                </div>

                <div class="col-md-6 mb-4">
                    <label for="estimated_cost">Estimated Cost</label>
                    <input type="number" step="0.01" id="estimated_cost" name="estimated_cost" value="{{ old('estimated_cost') }}" class="form-control @error('estimated_cost') is-invalid @enderror" placeholder="">
                    @error('estimated_cost')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

            
                <div class="row my-1">
                    <div class="col-md-12">
                        <div class="px-4 py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Order items</h5>
                            <button onclick="order_item_container();" class="btn btn-success font-weight-medium waves-effect waves-light " type="button">
                                <i class="ti ti-circle-plus fs-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div id="order-item-container">
                    <hr>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-info rounded-pill px-4">
                            <div class="d-flex align-items-center">
                                Create
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>





        </div>
    </div>
</div>


@endsection

@push('script')


<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<script>

var room = 1;

function order_item_container() {
    room++;
    var objTo = document.getElementById("order-item-container");
    var divtest = document.createElement("div");
    divtest.setAttribute("class", `row removeclass${room}`);
    divtest.innerHTML = `
        <div class="col-12 col-md-3 col-lg-2 mb-4 my-auto">
            <label for="category">Item Category</label>
            <input type="text" name="category[]" id="category${room}" class="form-control " placeholder="Enter category here" required/>
        </div>

        <div class="col-12 col-md-3 col-lg-3 mb-4 my-auto">
            <label for="sub-category">Item Sub Category</label>
            <input type="text" name="sub_category[]" id="sub-category${room}" class="form-control typeahead" placeholder="Enter sub-category here" required/>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-4 my-auto">
            <label for="order_item">Product *</label>
            <select class="Order-product form-control" id="order_item${room}" name="order_item[]" required></select>
            <small class="form-control-feedback"><a href="{{ route('admin.new.product') }}" target="_blank">Click here to add Product</a></small>
        </div>

        <div class="col-12 col-md-3 col-lg-2 mb-4 my-auto">
            <label for="order_item_quantity">Item Quantity *</label>
            <input type="number" step="0.01" id="order_item_quantity${room}" name="order_item_quantity[]" class="form-control" placeholder="Enter item quantity value" required/>
        </div>

        <div class="col-sm-1 my-auto">
            <div class="form-group">
                <button class="btn btn-danger remove-field" type="button" data-room="${room}">
                    <i class="ti ti-minus"></i>
                </button>
            </div>
        </div>

        <hr>
    `;
    objTo.appendChild(divtest);
    refreshProduct(`#order_item${room}`);
    categories(`#category${room}`);
    subcategories(`#sub-category${room}`);
}
document.getElementById("order-item-container").addEventListener("click", function(e) {
    if (e.target && e.target.classList.contains("remove-field")) {
        var rid = e.target.getAttribute("data-room");
        document.querySelector(`.removeclass${rid}`).remove();
    }
});


function refreshProduct(selector = ".Order-product") {
    $(selector).select2({
        ajax: {
            url: function (params) {
                return '/api/search/{{ base64_encode($userId) }}/products/' + params.term;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true,
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        },
        placeholder: 'Search for a Product',
        minimumInputLength: 1,
        templateResult: formatProduct,
        templateSelection: formatProductSelection
    });
}

    // document.addEventListener('DOMContentLoaded', function() {
    //     refreshProduct();
    // });
    
    function formatProduct(Product) {
        if (!Product || Product.length === 0) {
            return 'No products found.';
        }

        if (Product.loading) {
            return Product.name;
        }


        var $container = $(
            "<div class='container mb-1'>" +
            "<div class='row result'>" +
            "<div class='col-lg-3 col-md-4 col-4 d-flex justify-content-center align-items-center'>" +
            "<img src='" + Product.image_url + "' alt='" + Product.name + "' class='img-fluid img-thumbnail' style='min-width: 60px; width:auto; height: auto;' />" +
            "</div>" +
            "<div class='col-lg-9 col-md-8 col-8 product-details'>" +
            "<h6 class='product-name mb-1' >" + Product.name + "</h6>" + // Changed to h6 for better semantics and added margin
            "<p class='text-muted mb-1' style='font-size: 0.7rem;'><strong>Type:</strong> " + Product.type + "</p>" +
            "<p class='text-muted' style='font-size: 0.7rem;'><strong>Rate Per:</strong> ₹ " + Product.rate_per + "</p>" +
            "</div>" +
            "</div>" +
            "</div>"
        );


        return $container;
    }

    function formatProductSelection(Product) {
        if (Product.id != "") {
            return `#${Product.id}\\${Product.type}\\${Product.name}`;
        } else {
            return Product.id;
        }
    }


    $(".customer-details").select2({
        ajax: {
            url: function (params) {
                return '/api/search/{{base64_encode($userId)}}/customers/' + params.term;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data, params) {
                return {
                    results: data
                };
            },
            cache: true,
            error: function (xhr, status, error) {
                console.error('AJAX request failed:', status, error);
            }
        },
        placeholder: 'Search for a Customer',
        minimumInputLength: 1,
        templateResult: formatCustomer,
        templateSelection: formatCustomerSelection
    });

    function formatCustomer(customer) {
        if (!customer || customer.length === 0) {
            return 'No customer found.';
        }

        if (customer.loading) {
            return customer.name;
        }

        var $container = $(
            "<div class='container'>" +
            "<div class='row result'>" +
            "<div class='col-12 customer-details'>" +
            "<h6 class='customer-name mb-1' >" + customer.name + "</h6>" + 
            "<p class='text-muted mb-1' style='font-size: 0.8rem;'><strong>Email:</strong> " + customer.email + "</p>" +
            "<p class='text-muted mb-0' style='font-size: 0.8rem;'><strong>Phone:</strong> " + customer.phone + "</p>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
        return $container;
    }

    function formatCustomerSelection(customer) {
        if (customer.id != "") {
            return `#${customer.id}\\${customer.name}`;
        } else {
            return customer.id;
        }
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

<script>

    function categories(selector = "#categories") {
        $(selector).typeahead({
            source: function (query, process) {
                return $.get('/api/search/{{ base64_encode($userId) }}/categories/' + query, function (data) {
                    return process(data);
                });
            }
        });
    }

    function subcategories(selector = "#subcategories") {
        $(selector).typeahead({
            source: function (query, process) {
                return $.get('/api/search/{{ base64_encode($userId) }}/subcategories/' + query, function (data) {
                    return process(data);
                });
            }
        });
    }


</script>

@endpush