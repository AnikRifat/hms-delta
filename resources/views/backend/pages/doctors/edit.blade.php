@extends('backend.layouts.master')

@section('title')
Edit Doctor - Admin Panel
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .form-check-label {
        text-transform: capitalize;
    }
    .remove-schedule {
        cursor: pointer;
        color: red;
    }
</style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Edit Doctor</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}">All Doctors</a></li>
                    <li><span>Edit Doctor</span></li>
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
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Edit Doctor</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Doctor Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $doctor->name }}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Doctor Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $doctor->email }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $doctor->phone }}" maxlength="15">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" value="{{ $doctor->specialization }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="department_id">Select Department</label>
                                <select name="department_id" id="department_id" class="form-control select2" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $doctor->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h5>Appointment Schedules</h5>
                        <div id="schedules-container">
                            @foreach ($schedules as $index => $schedule)
                                <div class="schedule-entry mb-3" data-index="{{ $index }}">
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="day_of_week">Day of Week</label>
                                            <select name="day_of_week[]" class="form-control" required>
                                                <option value="">Select Day</option>
                                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                                    <option value="{{ $day }}" {{ $day == $schedule->day_of_week ? 'selected' : '' }}>{{ $day }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="start_time">Start Time</label>
                                            <input type="time" class="form-control" name="start_time[]" value="{{ $schedule->start_time }}" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="end_time">End Time</label>
                                            <input type="time" class="form-control" name="end_time[]" value="{{ $schedule->end_time }}" required>
                                        </div>
                                    </div>
                                    <span class="remove-schedule" onclick="removeSchedule(this)">Remove</span>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-secondary" id="add-schedule">Add Schedule</button>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Update Doctor</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // $('.select2').select2();

        // Add new schedule entry
        $('#add-schedule').click(function() {
            var newIndex = $('.schedule-entry').length;
            var newScheduleHtml = `
                <div class="schedule-entry mb-3" data-index="${newIndex}">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="day_of_week">Day of Week</label>
                            <select name="day_of_week[]" class="form-control" required>
                                <option value="">Select Day</option>
                                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                    <option value="{{ $day }}">{{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="start_time">Start Time</label>
                            <input type="time" class="form-control" name="start_time[]" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="end_time">End Time</label>
                            <input type="time" class="form-control" name="end_time[]" required>
                        </div>
                    </div>
                    <span class="remove-schedule" onclick="removeSchedule(this)">Remove</span>
                </div>
            `;
            $('#schedules-container').append(newScheduleHtml);
        });
    });

    function removeSchedule(element) {
        $(element).closest('.schedule-entry').remove();
    }
</script>
@endsection
