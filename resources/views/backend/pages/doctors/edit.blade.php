@extends('backend.layouts.master')

@section('title')
Doctor Edit - Admin Panel
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
                <h4 class="page-title pull-left">Doctor Edit</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.doctors.index') }}">All Doctors</a></li>
                    <li><span>Edit Doctor - {{ $doctor->name }}</span></li>
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
                    <h4 class="header-title">Edit Doctor - {{ $doctor->name }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="name">Doctor Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $doctor->name }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="email">Doctor Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $doctor->email }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="phone">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ $doctor->phone }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="specialization">Specialization</label>
                                <input type="text" class="form-control" id="specialization" name="specialization" value="{{ $doctor->specialization }}">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="department_id">Department</label>
                                <select name="department_id" id="department_id" class="form-control select2">
                                    <option value="">Select Department</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ $doctor->department_id == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Save Doctor</button>
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
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection
