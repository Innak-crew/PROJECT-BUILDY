
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Smart Construction And Interiors</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
</head>

<body class="body">
    
    <!-- Preloader -->
    <div class="preloader">
        <img src="{{ asset('images\logo\logo-2.png') }}" alt="loader" class="lds-ripple img-fluid" />
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <h2 class="d-flex">View Logs <form action="{{route('logout')}}" class="ms-auto" method="post">
                    @csrf
                    <button class="border-0 bg-transparent text-primary " type="submit" class="btn btn-outline-primary" aria-label="logout" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout" >   
                        <i class="ti ti-power fs-6"></i>
                    </button>
                </form></h2>
                <div class="card-body">
                    <div class="table-responsive table-sm rounded-2 py-5 mb-4">
                        <table
                            class="table border text-nowrap customize-table mb-0 align-middle @if ($logs && count($logs) != 0) datatable-select-inputs @endif">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="sorting sorting_desc">
                                        <h6 class="fs-4 fw-semibold mb-0">#</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Message</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Type</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Source</h6>
                                    </th>
                                    <th>
                                        <h6 class="fs-4 fw-semibold mb-0">Action</h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($logs && $logs->count() != 0)
                                @for ($i = 0; $i < count($logs); $i++)
                                    <tr>
                                        <td>
                                            <p class="mb-0 fw-normal fs-4">{{ $logs[$i]->id }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-normal fs-4">{{ $logs[$i]->message }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-normal fs-4">{{ $logs[$i]->type }}</p>
                                        </td>
                                        <td>
                                            <p class="mb-0 fw-normal fs-4">{{ $logs[$i]->source }}</p>
                                        </td>
                                        <td class="">
                                        <a href="{{route('dev.log.view',['id' => $logs[$i]->id])}}" class="text-success"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-eye"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg></a>
                                        <form action="{{route('dev.log.delete', ['id' => $logs[$i]->id])}}" method="post" class="delete-form" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="delete-btn" onclick="return confirmDelete()"
                                                style="background:none; border:none; padding:0; margin:0; color:red; cursor:pointer;">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-trash">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </button>
                                        </form>
                                        </td>
                                    </tr>
                                    @endfor
                                    @else
                                    <tr>
                                        <td>
                                            <p class="mb-0 fw-normal fs-4">No Customers Found</p>
                                        </td>
                                    </tr>
                                    @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
     <!--  Import Js Files -->
     <script src="{{ asset('libs/jquery/dist/jquery.min.js') }}"></script>
     <script src="{{ asset('libs/simplebar/dist/simplebar.min.js') }}"></script>
     <script src="{{ asset('libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <!--  core files -->
    <script src="{{ asset('js/app.min.js') }}"></script>
    <script src="{{ asset('js/app.init.js') }}"></script>
    <script src="{{ asset('js/app-style-switcher.js') }}"></script>
    <script src="{{ asset('js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/notify.js') }}"></script>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this log?');
        }
    </script>

    <script src="/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="/js/datatable/datatable-api.init.js"></script>

</body>
</html>