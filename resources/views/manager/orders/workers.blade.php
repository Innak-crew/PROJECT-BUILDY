@extends('layout.admin-app')
@section('adminContent')

@push('style')

@endpush

<div class="card w-100 position-relative overflow-hidden">
    <div class="px-4 py-3 border-bottom d-flex justify-content-between align-items-center">
        <h5 class="card-title fw-semibold mb-0 lh-sm">{{$pageData->order->name}}</h5>
    </div>

    
    <div class="card-body p-4">
        <div class="row">
            @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
            @endif

            <form action="{{route('order.save.labours',["encodedOrderId" => base64_encode($pageData->order->id)])}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="py-3 d-flex justify-content-start align-items-center">
                            <h5 class="card-title fw-semibold mb-0 lh-sm me-3">Date *</h5>
                            <div class="col col-md-3">
                                <div class="form-group">
                                    <input type="date" name="date" class="form-control" value="{{ $pageData->date }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="labour-container">
                        <hr>

                        @if ($pageData->labours->count() != 0)
                            <div class="row">
                                @foreach ($pageData->labours as $index => $labour)
                                <input type="hidden" name="alt_labour_id[]" value="{{$labour->id}}" autocomplete="off">
                                @php
                                    $encodedId = str_replace('=', '', base64_encode($labour->id));
                                @endphp
                                <div class="row">
                                    <div class="col-12 col-md-11">
                                        <div class="row">
                                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                                <label for="alt_labor_category{{ $encodedId }}">Labour Category *</label>
                                                <input type="text" name="alt_labor_category[]" id="labor_category{{ $encodedId }}"
                                                    class="form-control" placeholder="Enter Labour category here" value="{{$labour->category->name}}" required />
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                                <label for="alt_number_of_labors">Number of labors *</label>
                                                <input type="number" id="number_of_labors{{ $encodedId }}" name="alt_number_of_labors[]"
                                                    class="form-control" placeholder="Enter number of labors" value="{{$labour->number_of_labors}}" required />
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                                <label for="alt_per_labor_amount">Per labor Amount *</label>
                                                <input type="number" id="per_labor_amount{{ $encodedId }}" name="alt_per_labor_amount[]"
                                                    class="form-control" placeholder="Enter per labor amount" value="{{$labour->per_labor_amount}}" required />
                                            </div>
                                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                                <label for="alt_total">Total *</label>
                                                <input type="number" id="total{{ $encodedId }}" name="alt_total[]"
                                                    class="form-control" value="{{$labour->total_amount}}" placeholder="Enter Total value" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-1 d-flex align-items-center justify-content-center">
                                        <div class="form-check form-check-inline mt-md-0">
                                            <input class="form-check-input danger check-outline outline-danger" type="checkbox"
                                                name="is_labour_delete[]" value="{{$labour->id}}">
                                            <label class="form-check-label" for="delete_labour">Delete</label>
                                        </div>
                                    </div>
                                </div>
                                    <hr class="mt-4 mt-md-0">
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function() {refreshSearch('{{ $encodedId }}');});
                                    </script>
                                @endforeach
                            </div>
                        @endif

                    
                    </div>

                    <div class="col-md-12">
                        <div class=" py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Add Labour </h5>
                            </div>
                            <button onclick="labour_container();"
                                class="btn btn-success font-weight-medium waves-effect waves-light rounded-pill pt-2 px-2"
                                type="button">
                                <i class="ti ti-circle-plus fs-5"></i>
                            </button>
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

                <button
                    class="btn btn-primary p-3 rounded-circle d-flex align-items-center justify-content-center customizer-btn"
                    type="submit" data-bs-toggle="tooltip" data-bs-placement="top" title="Save Changes">
                    <span class="th-check fs-5"></span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="/js/bootstrap3-typeahead.min.js"></script>

<script>

var room = 1;

function labour_container() {
    room++;
    var objTo = document.getElementById("labour-container");
    var rowDiv = document.createElement("div");
    rowDiv.setAttribute("class", `row removeClass${room}`);
    rowDiv.innerHTML = `
                        <div class="col-12 col-md-11 ">
                            <div class="row">
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <label for="labor_category${room}">Labour Category *</label>
                                    <input type="text" name="labor_category[]" id="labor_category${room}"
                                        class="form-control" placeholder="Enter Labour category here" required />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <label for="number_of_labors${room}">Number of labors *</label>
                                    <input type="number" id="number_of_labors${room}" name="number_of_labors[]"
                                        class="form-control" placeholder="Enter number of labors" required />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <label for="per_labor_amount${room}">Per labor Amount *</label>
                                    <input type="number" id="per_labor_amount${room}"
                                        name="per_labor_amount[]" class="form-control"
                                        placeholder="Enter per labor amount" required />
                                </div>
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <label for="total${room}">Total *</label>
                                    <input type="number" id="total${room}" name="total[]"
                                        class="form-control" value="0" placeholder="Enter Total value" required />
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-1 d-flex align-items-center justify-content-center">
                            <button class="btn btn-danger remove-field rounded-pill py-2 px-2" type="button"
                                data-room="${room}" onclick="remove_labour_item_container(${room})">
                                <i class="ti ti-minus"></i>
                            </button>
                        </div>
                        <div class="col-12">
                            <hr class="mt-4 mt-md-0">
                        </div>
    `;
    objTo.appendChild(rowDiv);
    refreshSearch(room);
}


function remove_labour_item_container(rid){
        document.querySelector(`.removeClass${rid}`).remove();
    }


    function refreshSearch(rid = 2) {

        $(`#labor_category${rid}`).typeahead({
            source: function (query, process) {
                return $.get('/api/search/{{ base64_encode($userId) }}/laborCategories/' + query, function (data) {
                    return process(data);
                });
            }
        });


        $(`#number_of_labors${rid}, #per_labor_amount${rid}`).on('input', function(e) {
            var numberOfLabors = parseFloat($(`#number_of_labors${rid}`).val());
            var perLaborAmount = parseFloat($(`#per_labor_amount${rid}`).val());

            if (!isNaN(numberOfLabors) && !isNaN(perLaborAmount)) {
                var total = numberOfLabors * perLaborAmount;
                $(`#total${rid}`).val(total);
            } else {
                $(`#total${rid}`).val('0');
            }
        });

    }
</script>

@endpush