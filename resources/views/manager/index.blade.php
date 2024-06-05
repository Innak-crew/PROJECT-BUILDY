@extends('layout.manager-app')
@section('adminContent')


@push('style')
  <style>
    .app-calendar .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-frame {
        background-color: #d7dfff;
        border-radius: 8px;
    }
  </style>
@endpush

<div class="card bg-white-info shadow-none position-relative overflow-hidden">
  <div class="card-body pb-4 ">
    <div class="row ">
      <div class="col order-md-1 order-2">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb">
            <li class="breadcrumb-item text-muted"><a href="#">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Calendar</li>
          </ol>
        </nav>
      </div>
    </div>
  </div>
</div>

<div class="card">
  <div class="row gx-0">
    <div class="col-lg-12">
      <div class="px-4 calender-sidebar app-calendar">
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<!-- BEGIN MODAL -->
<div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scheduleModalLabel">
          Add / Edit Schedule
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            <div class="">
              <label class="form-label">Schedule Title</label>
              <input id="schedule-title" type="text" class="form-control" />
            </div>
          </div>
          <div class="col-md-12 mt-4">
            <div><label class="form-label">Schedule Color</label></div>
            <div class="d-flex">
              <div class="n-chk">
                <div class="form-check form-check-primary form-check-inline">
                  <input class="form-check-input" type="radio" name="event-level" value="Danger" id="modalDanger" />
                  <label class="form-check-label" for="modalDanger">Danger</label>
                </div>
              </div>
              <div class="n-chk">
                <div class="form-check form-check-warning form-check-inline">
                  <input class="form-check-input" type="radio" name="event-level" value="Success" id="modalSuccess" />
                  <label class="form-check-label" for="modalSuccess">Success</label>
                </div>
              </div>
              <div class="n-chk">
                <div class="form-check form-check-success form-check-inline">
                  <input class="form-check-input" type="radio" name="event-level" value="Primary" id="modalPrimary" />
                  <label class="form-check-label" for="modalPrimary">Primary</label>
                </div>
              </div>
              <div class="n-chk">
                <div class="form-check form-check-danger form-check-inline">
                  <input class="form-check-input" type="radio" name="event-level" value="Warning" id="modalWarning" />
                  <label class="form-check-label" for="modalWarning">Warning</label>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-12 d-none">
            <div class="">
              <label class="form-label">Enter Start Date</label>
              <input id="schedule-start-date" type="text" class="form-control" />
            </div>
          </div>

          <div class="col-md-12 d-none">
            <div class="">
              <label class="form-label">Enter End Date</label>
              <input id="schedule-end-date" type="text" class="form-control" />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-bs-dismiss="modal">
          Close
        </button>
        <button type="button" class="btn btn-success btn-update-event" data-fc-event-public-id="">
          Update changes
        </button>
        <button type="button" class="btn btn-primary btn-add-event">
          Add Event
        </button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('script')
    <script src="/libs/fullcalendar/index.global.min.js"></script>
    <script src="/js/apps/calendar-init.js"></script>
@endpush