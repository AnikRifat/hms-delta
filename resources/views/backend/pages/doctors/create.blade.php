@extends('backend.layouts.master')

@section('title')
Create Doctor - Admin Panel
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
                <h4 class="page-title pull-left">Create Doctor</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}">All Doctors</a></li>
                    <li><span>Create Doctor</span></li>
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
                    <h4 class="header-title">Create New Doctor</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.doctors.store') }}" method="POST" id="doctorForm">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Doctor Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Doctor Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" maxlength="15">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" placeholder="Enter Specialization">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="department_id">Select Department</label>
                                <select name="department_id" id="department_id" class="form-control select2" required>
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h4 class="header-title mt-4">Appointment Schedule</h4>
                        <div id="appointmentSchedules">
                            <div class="form-row schedule-item mb-3">
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="day_of_week">Day of the Week</label>
                                    <select name="day_of_week[]" class="form-control" required>
                                        <option value="">Select Day</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="start_time">Start Time</label>
                                    <input type="time" class="form-control" name="start_time[]" required>
                                </div>
                                <div class="form-group col-md-4 col-sm-12">
                                    <label for="end_time">End Time</label>
                                    <input type="time" class="form-control" name="end_time[]" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="addSchedule" class="btn btn-secondary mt-2">Add Another Schedule</button>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Doctor</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Add another schedule
        $('#addSchedule').click(function() {
            const scheduleItem = `
                <div class="form-row schedule-item mb-3">
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="day_of_week">Day of the Week</label>
                        <select name="day_of_week[]" class="form-control" required>
                            <option value="">Select Day</option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                            <option value="Saturday">Saturday</option>
                            <option value="Sunday">Sunday</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="start_time">Start Time</label>
                        <input type="time" class="form-control" name="start_time[]" required>
                    </div>
                    <div class="form-group col-md-4 col-sm-12">
                        <label for="end_time">End Time</label>
                        <input type="time" class="form-control" name="end_time[]" required>
                    </div>
                </div>
            `;
            $('#appointmentSchedules').append(scheduleItem);
        });
    });
</script>
@endsection
