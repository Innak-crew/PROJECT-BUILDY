@extends('layout.admin-app')
@section('adminContent')
@use('Carbon\Carbon')

<div class="card overflow-hidden invoice-application">
    <div class="d-flex align-items-center justify-content-between gap-3 m-3 d-lg-none">
        <button class="btn btn-primary d-flex" type="button" data-bs-toggle="offcanvas" data-bs-target="#chat-sidebar"
            aria-controls="chat-sidebar">
            <i class="ti ti-menu-2 fs-5"></i>
        </button>
        <form class="position-relative w-100">
            <input type="text" class="form-control search-chat py-2 ps-5" id="text-srh" placeholder="Search Contact">
            <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
        </form>
    </div>

    <div class="d-flex">
        <div class="w-25 d-none d-lg-block border-end user-chat-box">
            <div class="p-3 border-bottom">
                <form class="position-relative">
                    <input type="search" class="form-control search-invoice ps-5" id="text-srh"
                        placeholder="Search Invoice" />
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="app-invoice">
                <ul class="overflow-auto invoice-users" style="height: calc(100vh - 262px)" data-simplebar>

                    @forelse ( $pageData as $order)
                    <li>
                        <a href="javascript:void(0)"
                            class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user bg-light"
                            id="invoice-{{substr($order->invoice()->first()->invoice_number, 5)}}"
                            data-invoice-id="{{substr($order->invoice()->first()->invoice_number, 5)}}">
                            <div class="ms-3 d-inline-block w-75">
                                <h6 class="mb-0 invoice-customer">{{$order->customer()->first()->name}}</h6>
                                <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">Id:
                                    {{$order->invoice()->first()->invoice_number}}</span>
                                <span class="fs-3 invoice-date text-nowrap text-body-color d-block">{{
                                    Carbon::parse($order->invoice()->first()->created_date)->format('jS M Y') }}</span>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li>
                        <div class="p-3 text-center text-muted">
                            No Invoice found.
                        </div>
                    </li>
                    @endforelse


                </ul>
            </div>
        </div>
        <div class="w-75 w-xs-100 chat-container">
            <div class="invoice-inner-part h-100">
                <div class="invoiceing-box">
                    <div class="invoice-header d-flex align-items-center border-bottom p-3">
                        <h4 class="font-medium text-uppercase mb-0">Invoice</h4>
                        <div class="ms-auto">
                            <h4 class="invoice-number">{{$pageData->count() != 0 ?
                                $pageData[0]->invoice()->first()->invoice_number : ''}}</h4>
                        </div>
                    </div>
                    <div class="px-3" id="custom-invoice">

                        @forelse ( $pageData as $order)
                        <div class="invoice-{{substr($order->invoice()->first()->invoice_number, 5)}}" id="printableArea">
                          <div class="row pb-5">
                              <div class="row border-bottom border-warning border-5 align-items-center p-3 mx-3 bg-light shadow-sm">
                                  <div class="col-3 text-center text-md-left mb-3 mb-md-0">
                                      <img src="{{ asset('images/logo/logo-2.png') }}" alt="Company Logo" class="img-fluid" width="100px">
                                  </div>
                                  <div class="col-9 text-md-left text-center">
                                      <h4 class="h4 font-weight-bold mb-1" style="color:#EB7D1E;">Smart Construction And Interiors</h4>
                                      <p class="mb-0">Aarthi theatre road, Dindigul, Tamil Nadu, 624001</p>
                                      <p class="mb-0"><b>Mobile:</b> 8825979705</p>
                                      <p class="mb-0"><b>Email:</b> smartinteriors2020@gmail.com</p>
                                  </div>
                              </div>
                              <div class="col-md-12 d-flex justify-content-between flex-wrap">
                                  <p class="mt-2 mb-2">
                                      <span>Invoice Date :</span>
                                      <i class="ti ti-calendar"></i>
                                      {{ Carbon::parse($order->invoice()->first()->created_date)->format('jS M Y') }}
                                  </p>
                                  <p class="mt-2 mb-2">{{$order->invoice()->first()->invoice_number}}</p>
                                  <p class="mt-2 mb-2">
                                      <span>Due Date :</span>
                                      <i class="ti ti-calendar"></i>
                                      {{ Carbon::parse($order->invoice()->first()->due_date)->format('jS M Y') }}
                                  </p>
                              </div>
                              <hr>
                              <div class="col-md-12">
                                  <div class="text-start">
                                      <p class="fs-3 pb-0 mb-0 fw-semibold">BILL TO,</p>
                                      <p class="px-4 ">
                                          {{$order->customer()->first()->name}},
                                      </p>
                                  </div>
                              </div>
                              <div class="col-md-12">
                                  <div class="table-responsive">
                                      <table class="table table-hover">
                                          <thead>
                                              <tr>
                                                  <th class="text-center">#</th>
                                                  <th>Description</th>
                                                  <th class="text-end">Quantity</th>
                                                  <th class="text-end">Unit Cost</th>
                                                  <th class="text-end">Total</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach($order->orderItems as $i => $item)
                                              <tr>
                                                  <td class="text-center">{{$i + 1}}</td>
                                                  <td>{{$item->catagories->name}} <br> {{$item->design->name}}</td>
                                                  <td >{{ rtrim(rtrim(number_format($item->quantity, 2), '0'), '.')}} ({{$item->design->unit->name}})</td>
                                                  <td class="text-end">{{$item->rate_per}}</td>
                                                  <td class="text-end">{{$item->total}}</td>
                                              </tr>
                                              @endforeach
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                              <div class="col-md-12">
                                  <div class="pull-right mt-4 text-end">
                                      <p>Sub - Total amount: $20,858</p>
                                      <p>vat (10%) : $2,085</p>
                                      <hr />
                                      <h3><b>Total :</b> $22,943</h3>
                                  </div>
                                  <div class="clearfix"></div>
                                  <hr />
                              </div>
                          </div>
                      </div>

                      <style>
                      @media (max-width: 768px) {
                          .invoice-customer {
                              font-size: 1rem;
                          }

                          .table thead th {
                              font-size: 0.85rem;
                          }

                          .table tbody td {
                              font-size: 0.85rem;
                          }

                          .text-end {
                              text-align: right;
                          }

                          .text-center {
                              text-align: center;
                          }

                          .text-md-left {
                              text-align: left;
                          }
                      }
                      </style>

                        <div class="text-end">
                            <button class="btn btn-default print-page" type="button">
                                <span><i class="ti ti-printer fs-5"></i>
                                    Print</span>
                            </button>
                        </div>

                        @empty
                        <div class="p-3 text-center text-muted">
                            No Invoice found.
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-start user-chat-box" tabindex="-1" id="chat-sidebar"
            aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">
                    Invoice
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="p-3 border-bottom">
                <form class="position-relative">
                    <input type="search" class="form-control search-invoice ps-5" id="text-srh"
                        placeholder="Search Invoice">
                    <i class="ti ti-search position-absolute top-50 start-0 translate-middle-y fs-6 text-dark ms-3"></i>
                </form>
            </div>
            <div class="app-invoice overflow-auto">
                <ul class="invoice-users">

                    @forelse ( $pageData as $order)
                    <li>
                        <a href="javascript:void(0)"
                            class="p-3 bg-hover-light-black border-bottom d-flex align-items-start invoice-user listing-user bg-light"
                            id="invoice-{{substr($order->invoice()->first()->invoice_number, 5)}}"
                            data-invoice-id="{{substr($order->invoice()->first()->invoice_number, 5)}}">
                            <!-- <div
                            class="btn btn-primary round rounded-circle d-flex align-items-center justify-content-center">
                            <i class="ti ti-user fs-6"></i>
                        </div> -->
                            <div class="ms-3 d-inline-block w-75">
                                <h6 class="mb-0 invoice-customer">{{$order->customer()->first()->name}}</h6>

                                <span class="fs-3 invoice-id text-truncate text-body-color d-block w-85">Id:
                                    {{$order->invoice()->first()->invoice_number}}</span>
                                <span class="fs-3 invoice-date text-nowrap text-body-color d-block">{{
                                    Carbon::parse($order->invoice()->first()->created_date)->format('jS M Y') }}</span>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li>
                        <div class="p-3 text-center text-muted">
                            No Invoice found.
                        </div>
                    </li>
                    @endforelse



                </ul>
            </div>
        </div>


    </div>
</div>
@endsection



@push('script')

<script src="/js/apps/jquery.PrintArea.js"></script>


<script>
    $(function () {
        $(".search-invoice").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".invoice-users li").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });

    $("#custom-invoice > #printableArea:first").show();

    // Print
    $(".print-page").click(function () {
        var mode = "iframe"; //popup
        var close = mode == "popup";
        var options = {
            mode: mode,
            popClose: close,
        };
        $("div#printableArea:first").printArea(options);
    });


    var $btns = $(".listing-user").click(function () {
        var getDataInvoiceAttr = $(this).attr("data-invoice-id");
        var getParentDiv = $(this).parents(".invoice-application");
        var getParentInvListContainer = $(this).parents(".app-invoice");

        var $el = $("." + this.id).show();
        $("#custom-invoice > div").not($el).hide();
        // Set Invoice Number
        var setInvoiceNumber = getParentDiv
            .find(".invoice-inner-part .invoice-number")
            .text("#INV-" + getDataInvoiceAttr);

        var hideTheNonSelectedContent = $(this)
            .parents(".invoice-application")
            .find(".chat-not-selected")
            .hide()
            .siblings(".invoiceing-box")
            .show();
        var showInvContentSection = getParentDiv
            .find(".invoice-inner-part #custom-invoice")
            .css("display", "block");
        $btns.removeClass("bg-light");
        $(this).addClass("bg-light");

        if ($(".invoiceing-box").css("display") == "block") {
            $(".right-part.invoice-box").css("height", "100%");
        }


        // Print
        $(".print-page").click(function () {
            var mode = "iframe"; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close,
                header: '<div style="text-align: center; font-weight: bold; font-size: 1.5rem; margin-bottom: 10px;">Smart </div>'
            };
            $("div#printableArea").printArea(options);
        });

        var myDiv = document.getElementsByClassName("invoice-inner-part")[0];
        myDiv.scrollTop = 0;

    });

</script>
@endpush