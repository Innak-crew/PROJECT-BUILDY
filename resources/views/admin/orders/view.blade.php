@extends('layout.admin-app')
@section('adminContent')

@push('style')

<style>

.badge-status {
    padding: 0.5em 1em;
    border-radius: 1em;
    font-weight: bold;
    display: inline-block;
    text-transform: capitalize;
}

.badge-status.pending {
    background-color: #e0f7fa;
    color: #00796b;
}

.badge-status.cancelled {
    background-color: #ffebee;
    color: #d32f2f;
}

.badge-status.paid {
    background-color: #e8f5e9;
    color: #388e3c;
}

.badge-status.partially_paid {
    background-color: #fff9c4;
    color: #f57f17;
}

.badge-status.late {
    background-color: #fbe9e7;
    color: #d84315;
}

.badge-status.overdue {
    background-color: #fbe9e7;
    color: #d84315;
}



</style>
@endpush


<section class="h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-11 col-xl-10  col-12">
                <div class="card">
                    <div class="card-header px-lg-4 pb-3 invoice-header">
                        <div class="d-flex justify-content-center pt-2">
                            <h5
                                class="mb-1 badge @if($pageData->status == 'ongoing') bg-light-info text-info @elseif($pageData->status == 'cancelled') bg-light-danger text-danger @else bg-light-success text-success  @endif">
                                {{ ucfirst($pageData->status) }} Order</h5>
                        </div>
                        <div class="d-flex justify-content-between pt-2">
                            <h5 class=" mb-0">Order Details</h5>
                            <h5 class=" mb-0">#ORD-000007</h5>
                        </div>
                    </div>
                    <div class="card-body invoice-body">
                        <div class="row mb-3">
                            <div class="col-6 col-md-6 col-12">
                                <p class="text-muted mb-0">
                                    <b class="me-4">Start Date</b>
                                    <br>{{ \Carbon\Carbon::parse($pageData->start_date)->format('jS F Y') }}
                                </p>
                            </div>
                            <div class="col-6 col-md-6 col-12 d-flex justify-content-end">
                                <p class="text-muted mb-0">
                                    <b class="d-flex justify-content-end">End Date</b>
                                    {{ \Carbon\Carbon::parse($pageData->end_date)->format('jS F Y') }}
                                </p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 col-12">
                                <b>Creator By</b>
                                <p class="text-muted mb-0">Name: {{$pageData->user()->first()->name}}</p>
                                <p class="text-muted mb-0">Role: {{$pageData->user()->first()->role}}</p>
                                <p class="text-muted mb-0">Email: {{$pageData->user()->first()->email}}</p>
                            </div>
                            <div class="col-md-6 col-12  ">
                                <b class="d-flex justify-content-end">Customer Details</b>
                                <p class="text-muted mb-0 d-flex justify-content-end">{{
                                    $pageData->Customer()->first()->name }}</p>
                                <p class="text-muted mb-0 d-flex justify-content-end">{{
                                    $pageData->Customer()->first()->phone }}</p>
                                <span class="text-muted mb-0 d-flex justify-content-end">{{
                                    $pageData->Customer()->first()->address }}</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <p class="lead fw-normal mb-0">Order Items</p>
                            <p class="lead text-muted mb-0">#{{count($pageData->orderItems()->get())}} Item(s)</p>
                        </div>

                        <div class="px-2 py-4">
                          <div class="table-responsive rounded-2">
                            <table class="table border text-nowrap customize-table mb-0 align-middle">
                              <thead class="text-dark">
                                <tr>
                                  <th>
                                    <p class="lead fw-normal mb-0">Items</p>
                                  </th>
                                  <th>
                                    <p class="lead fw-normal mb-0">Quantity</p>
                                  </th>
                                  <th>
                                    <p class="lead fw-normal mb-0">Rate per Unit</p>
                                  </th>
                                  <th>
                                    <p class="lead fw-normal mb-0">Total</p>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php $sub_total = 0; ?>
                                @if (count($pageData->orderItems()->get()) != 0)
                                @foreach ($pageData->orderItems()->get() as $orderItem)
                                <tr>
                                  <td>
                                    <div class="d-flex align-items-center">
                                      <img src="{{$orderItem->product()->first()->image_url}}" class="" alt="..." width="56" height="56">
                                      <div class="ms-3">
                                        <h6 class="lead fw-semibold mb-0 fs-4">
                                          {{$orderItem->catagories()->first()->name}}
                                          ({{$orderItem->product()->first()->type}})
                                        </h6>
                                        <p class="text-muted mb-0">{{ucwords(strtolower($orderItem->product->name))}}</p>
                                      </div>
                                    </div>
                                  </td>
                                  <td>
                                    <p class="text-muted mb-0 fs-4">{{ rtrim(rtrim(number_format($orderItem->quantity, 2), '0'), '.') }} ({{$orderItem->product()->first()->unit()->first()->name}})</p>
                                  </td>
                                  <td>
                                    <p class="text-muted mb-0 fs-4">₹ {{ rtrim(rtrim(number_format($orderItem->product()->first()->rate_per, 2), '0'), '.') }} </p>
                                    <!-- <p class="text-muted mb-0 fs-4">₹ {{ rtrim(rtrim(number_format( $orderItem->total / $orderItem->quantity , 2), '0'), '.') }} </p>  -->
                                  </td>
                                  <td>
                                    <p class="text-muted mb-0 fs-4">₹ {{$orderItem->total}}</p>
                                  </td>
                                </tr>
                                <?php $sub_total += $orderItem->total; ?>
                                @endforeach
                                @else
                                <tr>
                                  <td>
                                    <p class="mb-0 fw-normal fs-4">No Order items Found</p>
                                  </td>
                                </tr>
                                @endif
                              </tbody>
                            </table>
                          </div>
                        </div>

                        <div class="row mb-5">
                          <div class="col-12 col-md-6">
                          <p class="lead fw-semibold">Payment Details</p>
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                  <th>Description</th>
                                  <th>Amount</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td>Subtotal</td>
                                  <td>₹ {{number_format($sub_total, 2)}}</td>
                                </tr>
                                <tr>
                                  <td>Discount Percentage</td>
                                  <td> 
                                    <?php $discountPercentage = 0; ?> 
                                    @if ($pageData->invoice()->first() != null) 
                                      <?php $discountPercentage = $pageData->invoice()->first()->discount_percentage; ?> 
                                      {{ $discountPercentage }}% 
                                    @else 
                                      {{$discountPercentage}}% 
                                    @endif 
                                  </td>
                                </tr>
                                <tr>
                                  <td>Discount Amount</td>
                                  <td>
                                    <?php $discountAmount = 0; ?> 
                                    @if ($pageData->invoice()->first() != null) 
                                      <?php $discountAmount = $sub_total * ($discountPercentage / 100); ?> 
                                      ₹ {{number_format($discountAmount, 2)}} 
                                    @else 
                                      ₹ {{$discountAmount}} 
                                    @endif 
                                  </td>
                                </tr>
                                <tr>
                                  <td>Total Amount</td>
                                  <?php $totalAfterDiscount = $sub_total - $discountAmount; ?> 
                                  <td>₹ {{number_format($totalAfterDiscount, 2)}}</td>
                                </tr>
                                <tr>
                                  <td>Advance Payment</td>
                                  <?php  $advancePayAmount = $pageData->invoice()->first() != null ?  ($pageData->invoice()->first()->advance_pay_amount != null ? $pageData->invoice()->first()->advance_pay_amount : 0 ) : 0; ?> 
                                  <td>₹ {{ number_format($advancePayAmount,2) }}</td>
                                </tr>
                                <tr>
                                    <td>Total Paid Amount</td>
                                    <td>₹ {{ number_format($pageData->paymentHistory()->sum('amount'), 2) }}</td>
                                </tr>

                                <tr>
                                  <td>Balance Amount</td>
                                  <?php $balanceAmount = $totalAfterDiscount -  $advancePayAmount?>
                                  <td>₹ {{number_format($balanceAmount, 2)}}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>

                          <div class="col-12 col-md-6 ">
                            <div class="row mb-4">
                              <div class="col-md-12 col-12">
                                <p class="lead fw-semibold">Payment Status</p>
                                <p class="badge badge-status {{$pageData->invoice()->first()->payment_status}}">
                                    {{ ucFirst($pageData->invoice()->first()->payment_status) }}
                                </p>
                              </div>
                            </div>

                            <div class="row mb-4">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <p class="lead fw-semibold">Payment History</p>
                                    @if ($pageData->paymentHistory()->count() > 0)
                                        <div id="payment_history">
                                            @foreach ($pageData->paymentHistory as $index => $paymentHistory)
                                                <p class="mb-2">
                                                    Paid ₹{{ number_format($paymentHistory->amount, 2) }} via {{ ucwords(str_replace('_', ' ', $paymentHistory->payment_method)) }} on {{ \Carbon\Carbon::parse($paymentHistory->payment_date)->format('F j, Y') }}.
                                                </p>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>No payment history available.</p>
                                    @endif
                                </div>
                            </div>




                            <div class="row mb-4">
                                <div class="col-12">
                                    <p class="lead fw-semibold">Terms and Conditions</p>
                                    <p id="terms_and_conditions">
                                        {!! nl2br(e($pageData->invoice()->first()->terms_and_conditions)) !!}
                                    </p>
                                </div>
                            </div>

                          </div>
                        </div>

                        
                        <div class="card-footer invoice-footer">
                            <h5 class="d-flex align-items-center justify-content-end text-dark text-uppercase mb-0">
                                Balance: <span class="h2 mb-0 ms-2">₹ {{number_format($balanceAmount, 2)}}</span></h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


@endsection

@push('script')

@endpush