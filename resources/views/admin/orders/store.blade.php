@extends('layout.admin-app')
@section('adminContent')

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('css/select2.min.css') }}">

<style>
    .result {
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

            <form action="{{ route('order.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <!-- <div class="col-md-6 mb-4">
                        <label for="name">Order Name *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') is-invalid @enderror" placeholder="" required />
                        @error('name')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                        @enderror
                    </div> -->

                    <!-- <div class="col-md-6 mb-4">
                        <label for="description">Order Description</label>
                        <input type="text" name="description" id="description" value="{{ old('description') }}"
                            class="form-control typeahead @error('description') is-invalid @enderror" placeholder="" />
                        @error('description')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                        @enderror
                    </div> -->

                    <div class="col-md-6 mb-4">
                        <label for="customer">Customer *</label>
                        <select class="customer-details form-control mb-4" id="customer" name="customer" id="customer"
                            required></select>
                        <small class="form-control-feedback">If customer is not found , <a
                                href="{{route('admin.customer.add')}}" target="_blank">click here to add
                                customer</a></small>
                        @error('customer')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="location">Order location *</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            class="form-control @error('location') is-invalid @enderror" placeholder="" />
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
                            <option value="Interior">Interior</option>
                            <option value="Exterior">Exterior</option>
                            <option value="Both">Both</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="control-label">Order Starting Date *</label>
                        <input type="date" class="form-control" name="order_starting_date" required/>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="control-label">Order Ending Date </label>
                        <input type="date" class="form-control" name="order_ending_date"/>
                    </div>

                    <!-- <div class="col-md-6 mb-4">
                        <label for="estimated_cost">Estimated Cost</label>
                        <input type="number" step="0.01" id="estimated_cost" name="estimated_cost"
                            value="{{ old('estimated_cost') }}"
                            class="form-control @error('estimated_cost') is-invalid @enderror" placeholder="">
                        @error('estimated_cost')
                        <div class="invalid-feedback">
                            <p class="error">{{ $message }}</p>
                        </div>
                        @enderror
                    </div> -->

                    <div class="row my-1">
                        <div class="col-md-12">
                            <div class=" py-3 d-flex justify-content-between align-items-center">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Follow Up</h5>
                                <button onclick="follow_container();"
                                    class="btn btn-success font-weight-medium waves-effect waves-light rounded-pill py-auto px-2" type="button">
                                    <i class="ti ti-circle-plus fs-5 my-auto"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="follow-container">
                        <hr>

                    </div>


                    <div class="row my-1">
                        <div class="col-md-12">
                            <div class=" py-3 d-flex justify-content-between align-items-center">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Order items</h5>
                                <button onclick="order_item_container();"
                                    class="btn btn-success font-weight-medium waves-effect waves-light rounded-pill pt-2 px-2" type="button">
                                    <i class="ti ti-circle-plus fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="order-item-container">
                        <hr>
                    </div>
                </div>

                <!-- Invoice -->
                <div class="row my-1">
                    <div class="col-md-12">
                        <div class=" py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Invoice</h5>
                        </div>
                        <hr>
                    </div>
                </div>

                <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="control-label">Creating Date *</label>
                    <input type="date" class="form-control" name="created_date" value="{{old('created_date')}}" required/>
                </div>

                <div class="col-md-6 mb-4">
                    <label class="control-label">Due Date *</label>
                    <input type="date" class="form-control" name="due_date" value="{{old('due_date')}}"/>
                </div>
                </div>

                <!-- Payment Details -->
                <div class="row my-1">
                    <div class="col-md-12">
                        <div class=" py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title fw-semibold mb-0 lh-sm">Payments Details</h5>
                        </div>
                        <hr>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-4">
                        <label for="discount_percentage">Discount Percentage</label>
                        <input type="number" step="0.01" name="discount_percentage" id="discount_percentage" value="{{ old('discount_percentage') }}" 
                            class="form-control @error('discount_percentage') is-invalid @enderror" placeholder="" />
                        @error('discount_percentage')
                            <div class="invalid-feedback">
                                <p class="error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="advance_pay_amount">Advance Payment</label>
                        <input type="number" step="0.01" name="advance_pay_amount" id="advance_pay_amount" value="{{ old('advance_pay_amount') }}" 
                            class="form-control @error('advance_pay_amount') is-invalid @enderror" placeholder="" />
                        @error('advance_pay_amount')
                            <div class="invalid-feedback">
                                <p class="error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="payment_status">Payment Status *</label>
                        <select class="form-select mr-sm-2 @error('payment_status') is-invalid @enderror" id="payment_status" name="payment_status" required>
                            <option value="" disabled selected >Select--</option>
                            <option value="pending" @if(old('payment_status') == 'pending') selected @endif>Pending</option>
                            <option value="paid" @if(old('payment_status') == 'paid') selected @endif>Paid</option>
                            <option value="partially_paid" @if(old('payment_status') == 'partially_paid') selected @endif>Partially Paid</option>
                            <option value="late" @if(old('payment_status') == 'late') selected @endif>Late</option>
                            <option value="overdue" @if(old('payment_status') == 'overdue') selected @endif>Overdue</option>
                        </select>
                        @error('payment_status')
                            <div class="invalid-feedback">
                                <p class="error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="row my-1">
                            <div class="col-md-12">
                                <div class=" py-3 d-flex justify-content-between align-items-center">
                                    <h5 class="card-title fw-semibold mb-0 lh-sm">Payment History</h5>
                                    <button onclick="payment_history_container();" class="btn btn-success d-flex justify-content-center align-items-center rounded-circle p-0" type="button" style="width: 40px; height: 40px;">
                                        <i class="ti ti-circle-plus fs-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="payment-history"></div>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <label for="terms_and_conditions">Terms and Conditions</label>
                        <textarea name="terms_and_conditions" id="terms_and_conditions" class="form-control @error('terms_and_conditions') is-invalid @enderror" placeholder="">{{ old('terms_and_conditions', "1. In case of changes in design rate will be changed\r\n2.Extra works causes extra charges.") }}</textarea>
                        @error('terms_and_conditions')
                            <div class="invalid-feedback">
                                <p class="error">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>

                </div>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif


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
var roomID = 1;
function follow_container() {
        roomID++;
        var objTo = document.getElementById("follow-container");
        var divtest = document.createElement("div");
        divtest.setAttribute("class", `row remove-follow-class${roomID}`);
        divtest.innerHTML = `
            <div class="col-12 col-md-5 col-lg-5 mb-4 my-auto">
                <label for="note">Note *</label>
                <input type="text" name="note[]" id="note${roomID}" class="form-control " placeholder="" required/>
            </div>

            <div class="col-12 col-md-5 col-lg-5 mb-4 my-auto">
                <label class="control-label">Follow Date *</label>
                <input type="date" name="follow_date[]" id="follow-date${roomID}"class="form-control" required/>
            </div>

            <div class="col-sm-1 my-auto">
                <div class="form-group">
                    <button class="btn btn-danger remove-field rounded-pill py-2 px-2" type="button" data-room="${roomID}" onclick="remove_follow_container(${roomID})">
                        <i class="ti ti-minus"></i>
                    </button>
                </div>
            </div>

            <hr>
        `;
        objTo.appendChild(divtest);
    }

    document.getElementById("follow-container").addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("remove-field")) {
            var rid = e.target.getAttribute("data-room");
            document.querySelector(`.remove-follow-class${rid}`).remove();
        }
    });



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
                <button class="btn btn-danger remove-field rounded-pill py-2 px-2" type="button" data-room="${room}" onclick="remove_order_item_container(${room})">
                    <i class="ti ti-minus"></i>
                </button>
            </div>
        </div>

        <hr class="mt-4 mt-md-0">
        `;
        objTo.appendChild(divtest);
        refreshSerach(room);
    }


    function remove_follow_container(rid){
        document.querySelector(`.remove-follow-class${rid}`).remove();
    }    

    function remove_order_item_container(rid){
        document.querySelector(`.removeclass${rid}`).remove();
    }

 


    function refreshSerach (rid = 2){

        $(`#category${rid}`).typeahead({
            source: function (query, process) {
                return $.get('/api/search/{{ base64_encode($userId) }}/categories/' + query, function (data) {
                    return process(data);
                });
            }
        });

        $(`#sub-category${rid}`).typeahead({
            source: function (query, process) {
                return $.get('/api/search/{{ base64_encode($userId) }}/subcategories/' + query, function (data) {
                    return process(data);
                });
            }
        });

        $(`#order_item${rid}`).select2({
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
            return Product.text;
        }


        var $container = $(
            "<div class='container mb-1'>" +
            "<div class='row result'>" +
            "<div class='col-lg-3 col-md-4 col-4 d-flex justify-content-center align-items-center'>" +
            "<img src='" + Product.image_url + "' alt='" + Product.name + "' class='img-fluid img-thumbnail' style='min-width: 60px; width:auto; height: auto;' />" +
            "</div>" +
            "<div class='col-lg-9 col-md-8 col-8 product-details'>" +
            "<h6 class='product-name mb-1' >" + Product.name + "</h6>" +
            "<p class='text-muted mb-1' style='font-size: 0.7rem;'><strong>Type:</strong> " + Product.type + "</p>" +
            "<p class='text-muted' style='font-size: 0.7rem;'><strong>Rate Per:</strong> ₹ " + Product.rate_per + "</p>" +
            "</div>" +
            "</div>" +
            "</div>"
        );


        return $container;
    }

    function formatProductSelection(Product) {
            return Product.name || Product.text ;
    }


    $(".customer-details").select2({
        ajax: {
            url: function (params) {
                return '/api/search/{{base64_encode($userId)}}/customers/' + params.term;
            },
            dataType: 'json',
            delay: 250,
            processResults: function (data, params) {
                var results = [];

                for (var i = 0; i < data.length; i++) {
                    var customer = data[i];
                    results.push(customer);
                    if (customer.id === {{old('customer',0)}}) {
                        console.log(`${customer.id} selected`)
                        $(`.customer-details`).select2('data', customer);
                    }
                }
                return {
                    results: results
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
        templateSelection: formatCustomerSelection,
        
    });

     $(document).ready(function() {
        if ({{ old('customer', 0) }}) {
            
            console.log('done', {{ old('customer', 0) }});
            $.ajax({
                url: `/api/get/{{ base64_encode($userId) }}/customer-by-id/{{base64_encode(old('customer', 0))  }}`,
                dataType: 'json',
                success: function(data) {
                    if (data && data.id) {
                        var option = new Option(data.name, data.id, true, true);
                        $('.customer-details').append(option).trigger('change');
                        $('.customer-details').trigger({
                            type: 'select2:select',
                            params: {
                                data: data
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX request failed:', status, error);
                }
            });
        }else{
            console.log('not done');
        }
    });

    function formatCustomer(customer) {
        if (!customer || customer.length === 0) {
            return 'No customer found.';
        }

        if (customer.loading) {
            return customer.text;
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
            return customer.name || customer.text;
    }


    var phID = 1;
    function payment_history_container() {
    phID++;
        var objTo = document.getElementById("payment-history");
        var divtest = document.createElement("div");
        divtest.setAttribute("class", `row remove-payment-history-class${phID}`);
        divtest.innerHTML = `
            <div class="col-md-3 col-12">
                <label for="payment_history">Paid Amount *</label>
                <input type="number" step="0.01" name="paid_amount[]" id="paid_amount" value="" 
                    class="form-control @error('paid_amount') is-invalid @enderror" placeholder="" required/>
            </div>

            <div class="col-md-4 col-12">
                <label for="payment_date">Payment Date *</label>
                <input type="date" name="payment_date[]" id="payment_date" value="" 
                    class="form-control @error('payment_date') is-invalid @enderror" placeholder="" required/>
            </div>

            <div class="col-md-4 col-12 mb-4">
                <label for="payment_method">Payment Method *</label>
                <select class="form-select mr-sm-2 @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method[]" required>
                    <option value="" disabled selected>Select-- </option>
                    <option value="cash" @if(old('payment_method') == 'cash') selected @endif>Cash</option>
                    <option value="credit_card" @if(old('payment_method') == 'credit_card') selected @endif>Credit Card</option>
                    <option value="bank_transfer" @if(old('payment_method') == 'bank_transfer') selected @endif>Bank Transfer</option>
                    <option value="paypal" @if(old('payment_method') == 'paypal') selected @endif>Paypal</option>
                    <option value="UPI" @if(old('payment_method') == 'UPI') selected @endif>UPI</option>
                    <option value="other" @if(old('payment_method') == 'other') selected @endif>Other</option>
                </select>
            </div>

            <div class="col-md-1 my-auto d-flex justify-content-center align-items-center">
                <div class="form-group">
                    <button class="btn btn-danger d-flex justify-content-center align-items-center rounded-circle p-0 remove-field" type="button" data-room="${phID}"  onclick="remove_payment_container(${phID})" style="width: 40px; height: 40px;">
                        <i class="ti ti-minus fs-5"></i>
                    </button>
                </div>
            </div>

            <hr>
        `;
        objTo.appendChild(divtest);
    }


    function remove_payment_container(rid){
        document.querySelector(`.remove-payment-history-class${rid}`).remove();
    }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

<script>



</script>

@endpush