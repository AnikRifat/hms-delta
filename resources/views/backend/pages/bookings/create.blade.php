@extends('backend.layouts.master')

@section('title')
Booking Create - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Booking Create</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.bookings.index') }}">All Bookings</a></li>
                    <li><span>Create Booking</span></li>
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
                    <h4 class="header-title">Create New Booking</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.bookings.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="patient_name">Patient Name</label>
                                <input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Enter Patient Name" required>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="patient_email">Patient Email</label>
                                <input type="email" class="form-control" id="patient_email" name="patient_email" placeholder="Enter Patient Email">
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="patient_phone">Patient Phone</label>
                                <input type="text" class="form-control" id="patient_phone" name="patient_phone" placeholder="Enter Patient Phone">
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="department_id">Select Department</label>
                                <select class="form-control select2" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="doctor_id">Select Doctor</label>
                                <select class="form-control select2" id="doctor_id" name="doctor_id" required disabled>
                                    <option value="">Select Doctor</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="appointment_schedule_id">Appointment Schedule</label>
                                <select class="form-control select2" id="appointment_schedule_id" name="appointment_schedule_id" required disabled>
                                    <option value="">Select Appointment Schedule</option>
                                    <!-- Options will be dynamically loaded here based on the selected doctor -->
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-sm-12">
                                <label for="booking_date">Booking Date</label>
                                <input type="date" class="form-control" id="booking_date" name="booking_date" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Booking</button>
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
<script>
    $(document).ready(function() {
        // $('.select2').select2();

        // When a department is selected, load doctors
        $('#department_id').change(function() {
            var departmentId = $(this).val();
            $('#doctor_id').prop('disabled', true).empty().append('<option value="">Select Doctor</option>');
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');

            if (departmentId) {
                $.ajax({
                    url: "{{ route('doctors.byDepartment') }}", // Adjust this route to match your setup
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(data) {
                        $('#doctor_id').prop('disabled', false);
                        $.each(data, function(key, doctor) {
                            $('#doctor_id').append('<option value="' + doctor.id + '">' + doctor.name + '</option>');
                        });
                    }
                });
            } else {
                $('#doctor_id').html('<option value="">Select Doctor</option>').prop('disabled', true);
            }
        });

        // When a doctor is selected, load appointment schedules
        $('#doctor_id').change(function() {
            var doctorId = $(this).val();
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');

            if (doctorId) {
                $.ajax({
                    url: "{{ route('appointmentSchedules.byDoctor') }}", // Adjust this route to match your setup
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function(data) {
                        $('#appointment_schedule_id').prop('disabled', false);
                        $.each(data, function(key, schedule) {
                            $('#appointment_schedule_id').append('<option value="'+ schedule.id +'">' + schedule.day_of_week + ' (' + schedule.start_time + ' - ' + schedule.end_time + ')</option>');
                        });
                    }
                });
            } else {
                $('#appointment_schedule_id').html('<option value="">Select Appointment Schedule</option>').prop('disabled', true);
            }
        });
    });
</script>
@endsection
