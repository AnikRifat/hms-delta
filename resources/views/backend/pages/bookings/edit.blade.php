@extends('backend.layouts.master')

@section('title')
Booking Edit - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Booking Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.bookings.index') }}">All Bookings</a></li>
                    <li><span>Edit Booking</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- form start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Booking</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="patient_name" class="form-label">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name" value="{{ $booking->patient_name }}" placeholder="Enter Patient Name" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="patient_email" class="form-label">Patient Email</label>
                                <input type="email" class="form-control" id="patient_email" name="patient_email" value="{{ $booking->patient_email }}" placeholder="Enter Patient Email">
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="patient_phone" class="form-label">Patient Phone</label>
                                <input type="text" class="form-control" id="patient_phone" name="patient_phone" value="{{ $booking->patient_phone }}" placeholder="Enter Patient Phone">
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="department_id" class="form-label">Select Department</label>
                                <select class="form-control select2" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $department->id == $booking->appointmentSchedule->doctor->department_id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="doctor_id" class="form-label">Select Doctor</label>
                                <select class="form-control select2" id="doctor_id" name="doctor_id" required disabled>
                                    <option value="">Select Doctor</option>
                                    @foreach ($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ $doctor->id == $booking->doctor_id ? 'selected' : '' }}>{{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="appointment_schedule_id" class="form-label">Appointment Schedule</label>
                                <select class="form-control select2" id="appointment_schedule_id" name="appointment_schedule_id" required disabled>
                                    <option value="">Select Appointment Schedule</option>
                                    @foreach ($appointmentSchedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ $schedule->id == $booking->appointment_schedule_id ? 'selected' : '' }}>{{ $schedule->day_of_week }} ({{ $schedule->start_time }} - {{ $schedule->end_time }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12 mb-3">
                                <label for="booking_date" class="form-label">Booking Date</label>
                                <input type="text" class="form-control" id="booking_date" name="booking_date" value="{{ $booking->booking_date }}" placeholder="Select Booking Date" required disabled>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Booking</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- form end -->
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2

        // Load doctors based on selected department
        $('#department_id').change(function() {
            var departmentId = $(this).val();
            $('#doctor_id').prop('disabled', true).empty().append('<option value="">Select Doctor</option>');
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');
            $('#booking_date').prop('disabled', true);

            if (departmentId) {
                $.ajax({
                    url: "{{ route('doctors.byDepartment') }}",
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(data) {
                        $('#doctor_id').prop('disabled', false);
                        $.each(data, function(key, doctor) {
                            $('#doctor_id').append('<option value="' + doctor.id + '">' + doctor.name + '</option>');
                        });

                        // Pre-select the doctor
                        $('#doctor_id').val('{{ $booking->doctor_id }}').trigger('change');
                    }
                });
            }
        });

        // Load appointment schedules based on selected doctor
        $('#doctor_id').change(function() {
            var doctorId = $(this).val();
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');
            $('#booking_date').prop('disabled', true);

            if (doctorId) {
                $.ajax({
                    url: "{{ route('appointmentSchedules.byDoctor') }}",
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function(data) {
                        $('#appointment_schedule_id').prop('disabled', false);
                        $.each(data, function(key, schedule) {
                            $('#appointment_schedule_id').append('<option value="'+ schedule.id +'">' + schedule.day_of_week + ' (' + schedule.start_time + ' - ' + schedule.end_time + ')</option>');
                        });

                        // Pre-select the appointment schedule
                        $('#appointment_schedule_id').val('{{ $booking->appointment_schedule_id }}').trigger('change');

                        setupFlatpickr(data);
                    }
                });
            }
        });

        // Setup Flatpickr for booking date
        function setupFlatpickr(schedules) {
            var availableDays = schedules.map(function(schedule) {
                return schedule.day_of_week.toLowerCase();
            });

            $('#booking_date').flatpickr({
                dateFormat: 'Y-m-d',
                minDate: 'today',
                enable: [
                    function(date) {
                        var day = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
                        return availableDays.includes(day);
                    }
                ],
                onOpen: function(selectedDates, dateStr, instance) {
                    if (availableDays.length === 0) {
                        alert('Please select a doctor to see available dates.');
                        instance.close();
                    }
                }
            });

            $('#booking_date').prop('disabled', false);
        }

        // Trigger change event to populate data on page load
        $('#department_id').trigger('change');
    });
</script>
@endsection
