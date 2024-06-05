@extends('layout.admin-app')
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
                <h5 class="modal-title" id="scheduleModalLabel">Add / Edit Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('schedule.store')}}" method="post" id="myForm">
                @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Schedule Title<span class="text-danger">*</span></label>
                            <input id="schedule-title" type="text" name="title" class="form-control" required />
                        </div>
                        <div class="col-md-12 mt-4">
                            <label class="form-label">Schedule Description<span class="text-danger">*</span></label>
                            <textarea id="schedule-description" name="description" class="form-control" required></textarea>
                        </div>
                        <div class="col-md-6 mt-4">
                            <label class="form-label">Schedule Start Date<span class="text-danger">*</span></label>
                            <input id="schedule-start-date" name="start" type="datetime-local" class="form-control" required />
                        </div>
                        <div class="col-md-6 mt-4">
                            <label class="form-label">Schedule End Date</label>
                            <input id="schedule-end-date" name="end" type="datetime-local" class="form-control" />
                        </div>
                        <div class="col-md-6 mt-4">
                            <label class="form-label">Schedule Visibility<span class="text-danger">*</span></label>
                            <select id="schedule-visibility" class="form-control" name="visibility">
                                <option value="private">Private</option>
                                <option value="public">Public</option>
                                <option value="admin">For Admins</option>
                                <option value="manager">For Managers</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mt-4">
                            <div class="form-check form-check-primary form-check-inline">
                            <input class="form-check-input" type="checkbox" name="is_editable" id="foreditable"  >

                                <label class="form-check-label" for="foreditable">Editable</label>
                            </div>
                        </div>

                        
                        <div class="col-md-12 mt-4">
                            <div><label class="form-label">Schedule Color</label></div>
                            <div class="d-flex">
                            <div class="n-chk">
                                <div class="form-check form-check-primary form-check-inline">
                                <input class="form-check-input" type="radio" name="schedule_level" value="Danger" id="modalDanger" />
                                <label class="form-check-label" for="modalDanger">Danger</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-warning form-check-inline">
                                <input class="form-check-input" type="radio" name="schedule_level" value="Success" id="modalSuccess" />
                                <label class="form-check-label" for="modalSuccess">Success</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-success form-check-inline">
                                <input class="form-check-input" type="radio" name="schedule_level" value="Primary" id="modalPrimary" />
                                <label class="form-check-label" for="modalPrimary">Primary</label>
                                </div>
                            </div>
                            <div class="n-chk">
                                <div class="form-check form-check-danger form-check-inline">
                                <input class="form-check-input" type="radio" name="schedule_level" value="Warning" id="modalWarning" />
                                <label class="form-check-label" for="modalWarning">Warning</label>
                                </div>
                            </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success btn-update-event" data-fc-event-public-id="">Update changes</button>
                        <button type="submit" class="btn btn-primary btn-add-event">Add Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script src="/libs/fullcalendar/index.global.min.js"></script>
<script>

    document.addEventListener("DOMContentLoaded", function () {

    //  Calender Date variable
    var newDate = new Date();
    function getDynamicMonth() {
    getMonthValue = newDate.getMonth();
    _getUpdatedMonthValue = getMonthValue + 1;
    if (_getUpdatedMonthValue < 10) {
        return `0${_getUpdatedMonthValue}`;
    } else {
        return `${_getUpdatedMonthValue}`;
    }
    }

    function formatDateTime(date) {
        const pad = num => num.toString().padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`;
    }

    // Calender Modal Elements
    var getModalTitleEl = document.querySelector("#schedule-title");
    var getModalDescriptionEl = document.querySelector("#schedule-description");
    var getModalStartDateEl = document.querySelector("#schedule-start-date");
    var getModalEndDateEl = document.querySelector("#schedule-end-date");
    var getModalAddBtnEl = document.querySelector(".btn-add-event");
    var getModalUpdateBtnEl = document.querySelector(".btn-update-event");
    var getModalVisibilityEl = document.querySelector("#schedule-visibility");
    var getModalForeditableEl = document.querySelector("#foreditable");
    var getModalIsEditableDivEl = document.querySelector(".is_editable");
    var getModalFormEl = document.querySelector("#myForm");

    var storeURL = "{{route('schedule.store')}}";

    var calendarsEvents = {
    Danger: "danger",
    Success: "success",
    Primary: "primary",
    Warning: "warning",
    };

    // Calendar Elements and options
    var calendarEl = document.querySelector("#calendar");

    

    var calendarHeaderToolbar = {
        left: "prev,next addEventButton",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
    };



    // Calendar Select fn.
    var calendarSelect = function (info) {
        getModalFormEl.setAttribute("action",storeURL);
        var date = new Date(info.startStr);
        var formattedDate = date.toISOString().slice(0, 16); 
        getModalStartDateEl.value = formattedDate;
        var date = new Date(info.endStr);
        var formattedDate = date.toISOString().slice(0, 16); 
        getModalEndDateEl.value = formattedDate;
        getModalForeditableEl.setAttribute("checked", true);
        getModalAddBtnEl.style.display = "block";
        getModalUpdateBtnEl.style.display = "none";
        myModal.show();
    };

    // Calendar AddEvent fn.
    var calendarAddEvent = function () {
        getModalFormEl.setAttribute("action",storeURL);
        removeModelData();
        var currentDate = new Date();
        var dd = String(currentDate.getDate()).padStart(2, "0");
        var mm = String(currentDate.getMonth() + 1).padStart(2, "0"); 
        var yyyy = currentDate.getFullYear();
        var combineDate = `${yyyy}-${mm}-${dd}T00:00:00`;
        var endDate = new Date(currentDate);
        endDate.setDate(currentDate.getDate() + 1);
        var endDd = String(endDate.getDate()).padStart(2, "0");
        var endMm = String(endDate.getMonth() + 1).padStart(2, "0"); // January is 0!
        var endYyyy = endDate.getFullYear();
        var combineEndDate = `${endYyyy}-${endMm}-${endDd}T00:00:00`;
        getModalForeditableEl.setAttribute("checked", true);
        getModalAddBtnEl.style.display = "block";
        getModalUpdateBtnEl.style.display = "none";
        myModal.show();
        getModalStartDateEl.value = combineDate;
        getModalEndDateEl.value = combineEndDate;
    };

    // Calender Event Function
    var calendarEventClick = function (info) {
        removeModelData();
        var eventObj = info.event;
        if (eventObj.url) {
            window.open(eventObj.url);
            info.jsEvent.preventDefault(); 
        } else {
            var getModalEventId = eventObj._def.publicId;
            var getModalEventLevel = eventObj._def.extendedProps["calendar"];
            var getModalCheckedRadioBtnEl = document.querySelector(
            `input[value="${getModalEventLevel}"]`
            );
            
            getModalCheckedRadioBtnEl.checked = true;

            getModalTitleEl.value = eventObj.title;
            getModalDescriptionEl.value = eventObj.extendedProps.description;
            getModalUpdateBtnEl.setAttribute(
            "data-fc-event-public-id",
            getModalEventId
            );

            getModalFormEl.setAttribute("action", `/admin/schedule/${getModalEventId}/update`);
            getModalStartDateEl.value = eventObj.extendedProps.start_time;
            getModalEndDateEl.value = eventObj.extendedProps.end_time;
            if(eventObj.extendedProps.foreditable){
                getModalForeditableEl.setAttribute("checked", eventObj.extendedProps.foreditable);
            }

            getModalAddBtnEl.style.display = "none";
            getModalUpdateBtnEl.style.display = "block";
            myModal.show();
        }
    };


    var schedulesData = {!! json_encode($pageData->Schedules->map(function($schedule) {
        return [
            'id' => $schedule->id,
            'title' => $schedule->title,
            'description' => $schedule->description,
            'start' => str_replace(' ', 'T', $schedule->start),
            'start_time' => $schedule->start,
            'end' => $schedule->end !== null ? str_replace(' ', 'T', $schedule->end) : null,
            'end_time' => $schedule->end !== null ? $schedule->end : null,
            'foreditable' => $schedule->is_editable,
            'extendedProps' => ['calendar' => $schedule->level ]
        ];
    })) !!};


    var checkWidowWidth = () => {
        if (window.innerWidth <= 1199) {
            return true;
        } else {
            return false;
        }
    };

    // Initialize FullCalendar
    var calendar = new FullCalendar.Calendar(calendarEl, {
        selectable: true,
        height: window.innerWidth <= 1199 ? 900 : 1052,
        initialView: window.innerWidth <= 1199 ? "listWeek" : "dayGridMonth",
        initialDate: `${newDate.getFullYear()}-${getDynamicMonth()}-07`,
        headerToolbar: calendarHeaderToolbar,
        events: schedulesData,
        select: calendarSelect,
        unselect: function () {
            console.log("unselected");
        },
        customButtons: {
            addEventButton: {
                text: "Add Event",
                click: calendarAddEvent,
            }
        },
        eventClassNames: function ({ event: calendarEvent }) {
            const getColorValue = calendarsEvents[calendarEvent._def.extendedProps.calendar];
            return [
                "event-fc-color fc-bg-" + getColorValue,
            ];
        },
        eventClick: calendarEventClick,
        windowResize: function (arg) {
            if (window.innerWidth <= 1199) {
                calendar.changeView("listWeek");
                calendar.setOption("height", 900);
            } else {
                calendar.changeView("dayGridMonth");
                calendar.setOption("height", 1052);
            }
        },
    });

    calendar.render();

    // Update Calender Event
    getModalUpdateBtnEl.addEventListener("click", function () {
    var getPublicID = this.dataset.fcEventPublicId;
    var getTitleUpdatedValue = getModalTitleEl.value;
    var getEvent = calendar.getEventById(getPublicID);
    var getModalUpdatedCheckedRadioBtnEl = document.querySelector(
        'input[name="schedule_level"]:checked'
    );

    var getModalUpdatedCheckedRadioBtnValue =
        getModalUpdatedCheckedRadioBtnEl !== null
        ? getModalUpdatedCheckedRadioBtnEl.value
        : "";

    getEvent.setProp("title", getTitleUpdatedValue);
    getEvent.setExtendedProp("calendar", getModalUpdatedCheckedRadioBtnValue);
    myModal.hide();
    });

    // Add Calender Event
    getModalAddBtnEl.addEventListener("click", function () {
    var getModalCheckedRadioBtnEl = document.querySelector(
        'input[name="schedule_level"]:checked'
    );

    var getTitleValue = getModalTitleEl.value;
    var setModalStartDateValue = getModalStartDateEl.value;
    var setModalEndDateValue = getModalEndDateEl.value;
    var getModalCheckedRadioBtnValue =
        getModalCheckedRadioBtnEl !== null ? getModalCheckedRadioBtnEl.value : "";

    calendar.addEvent({
        id: 12,
        title: getTitleValue,
        start: setModalStartDateValue,
        end: setModalEndDateValue,
        allDay: true,
        extendedProps: { calendar: getModalCheckedRadioBtnValue },
    });
    
    });

    const removeModelData = () =>{
        getModalTitleEl.value = "";
        getModalStartDateEl.value = "";
        getModalEndDateEl.value = "";
        getModalDescriptionEl.value = "";
        getModalForeditableEl.removeAttribute("checked");
    }

    // Calendar Init
    calendar.render();
    var myModal = new bootstrap.Modal(document.getElementById("scheduleModal"));
    var modalToggle = document.querySelector(".fc-addEventButton-button ");
    document.getElementById("scheduleModal").addEventListener("hidden.bs.modal", function (event) {
       removeModelData();
    });
    });

</script>
@endpush