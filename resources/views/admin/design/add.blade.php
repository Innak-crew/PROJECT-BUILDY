@extends('layout.admin-app')
@section('adminContent')

@push('style')

@endpush



<div class="row align-items-center">
  <div class="col-sm-10 col-md-10 col-lg-8 mx-auto">
    <div class="card">
      <div class="card-body">
        
        <h5 class="mb-3"> <a href="{{url()->previous()}}"><span class="th-arrow-left"></span></a> {{$title}}</h5>
        <form action="{{route('design.store')}}" method="post" enctype="multipart/form-data">
            @csrf
          <div class="row">

            <div class="col-md-6 mb-3">
                <div class="form-floating">
                        <select class="form-select mr-sm-2" id="type" name="type" required>
                            <option value="" disabled selected>Choose...</option>
                            <option value="Interior" selected>Interior</option>
                            <option value="Exterior" >Exterior</option>
                            <option value="Both" >Both</option>
                        </select>
                    <label for="type">Type *</label>
                    @error('type')
                    <div class="invalid-feedback">
                        <p class="error">{{ $message }}</p>
                    </div>
                    @enderror
                </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="form-floating">
              <input type="text" name="category" id="category1" class="form-control " placeholder="Enter category here" required/>
                <label for="category1">Design Category *</label>
                <small id="textHelp" class="form-text text-muted">(e.g., Kitchen)</small>
                
                @error('category')
                  <div class="invalid-feedback">
                    <p class="error">{{ $message }}</p>
                  </div>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
              <div class="form-floating">
              <input type="text" name="sub_category" id="sub-category1" class="form-control typeahead" placeholder="Enter sub-category here" required/>
                <label for="sub-category1">Design Sub Category *</label>
                <small id="textHelp" class="form-text text-muted">(e.g., Kitchen Cupboard)</small>
                @error('sub_category')
                  <div class="invalid-feedback">
                    <p class="error">{{ $message }}</p>
                  </div>
                @enderror
              </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="form-floating">
                    <input class="form-control" type="file" id="image" name="image">
                    <label for="image"> Image </label>
                    @error('image')
                    <div class="invalid-feedback">
                        <p class="error">{{ $message }}</p>
                    </div>
                    @enderror
                </div>
            </div>
 
            <div class="col-md-6 mb-3">
                <div class="form-floating">
                        <select class="form-select mr-sm-2" id="unit_id" name="unit_id" required>
                            <option value="" disabled selected>Choose...</option>
                            @if (count($pageData->QuantityUnits) != 0)
                                @foreach ( $pageData->QuantityUnits as $QuantityUnit)
                                <option value="{{$QuantityUnit->id}}">{{$QuantityUnit->name}} @if ( $QuantityUnit->description != null ) ({{$QuantityUnit->description}}) @endif </option>
                                @endforeach
                            @endif
                        </select>
                    <label for="unit_id">Quantity Unit *</label>
                    <small class="textHelp"><a href="{{ route('admin.quantity-units.add') }}" >Create New Quantity Unit</a></small>
            
                    @error('unit_id')
                    <div class="invalid-feedback">
                        <p class="error">{{ $message }}</p>
                    </div>
                    @enderror
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

            <div class="d-flex justify-content-end mt-3">
              <button type="submit" class="btn btn-info font-medium rounded-pill px-4">
                <div class="d-flex align-items-center">
                  <i class="ti ti-send me-2 fs-4"></i>
                  Add
                </div>
              </button>
            </div>

        </form>
      </div>
    </div>
  </div>
</div>


@endsection

@push('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>

<script>
    $(document).ready(function () {
      $('#category1').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        source: function (query, process) {
          $.ajax({
            url: `/api/search/{{ base64_encode($userId) }}/categories/${query}`,
            method: 'GET',
            success: function (data) {
              process(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error('Error fetching categories:', textStatus, errorThrown);
            }
          });
        }
      });

      $('#sub-category1').typeahead({
        hint: true,
        highlight: true,
        minLength: 1,
        source: function (query, process) {
          $.ajax({
            url: `/api/search/{{ base64_encode($userId) }}/subcategories/${query}`,
            method: 'GET',
            success: function (data) {
              process(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
              console.error('Error fetching subcategories:', textStatus, errorThrown);
            }
          });
        }
      });
    });
  </script>

</script>

@endpush