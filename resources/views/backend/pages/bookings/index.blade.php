@extends('backend.layouts.master')

@section('title')
Bookings - Admin Panel
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        @media print {
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th, td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        }
    </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Bookings</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><span>All Bookings</span></li>
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
                    <h4 class="header-title float-left">Booking List</h4>
                    <p class="float-right mb-2">
                        <a class="btn btn-primary text-white" href="{{ route('admin.bookings.create') }}">Create New Booking</a>
                    </p>
                    <div class="clearfix"></div>

                    <!-- Filters -->
                    <div class="mb-4">
                        <label for="doctorFilter">Filter by Doctor:</label>
                        <select id="doctorFilter" class="form-control">
                            <option value="">All Doctors</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="dateRange">Filter by Date Range:</label>
                        <input type="text" id="dateRange" class="form-control" placeholder="Select date range" />
                    </div>

                    <button class="btn btn-secondary mb-3" id="printButton">Print</button>

                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th width="5%">index</th>
                                    <th width="15%">Serial Number</th>
                                    <th width="15%">Patient Name</th>
                                    <th width="15%">Patient Phone</th>
                                    <th width="20%">Doctor Name</th> <!-- Added Doctor Name -->
                                    <th width="20%">Appointment Schedule</th>
                                    <th width="10%">Booking Date</th>
                                    <th width="20%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($bookings as $booking)
                               <tr data-doctor-id="{{ $booking->appointmentSchedule->doctor->id }}" data-booking-date="{{ $booking->booking_date }}">
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>{{ $booking->sl_no }}</td>
                                    <td>{{ $booking->patient_name }}</td>
                                    <td>{{ $booking->patient_phone }}</td>
                                    <td>{{ $booking->appointmentSchedule->doctor->name }}</td> <!-- Displaying Doctor Name -->
                                    <td>{{ $booking->appointmentSchedule->day_of_week }} {{ $booking->appointmentSchedule->start_time }} - {{ $booking->appointmentSchedule->end_time }}</td>
                                    <td>{{ $booking->booking_date }}</td>
                                    <td>
                                        <a class="btn btn-success text-white" href="{{ route('admin.bookings.edit', $booking->id) }}">Edit</a>

                                        <a class="btn btn-danger text-white" href="{{ route('admin.bookings.destroy', $booking->id) }}"
                                           onclick="event.preventDefault(); document.getElementById('delete-form-{{ $booking->id }}').submit();">
                                            Delete
                                        </a>

                                        <form id="delete-form-{{ $booking->id }}" action="{{ route('admin.bookings.destroy', $booking->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')
    <!-- Start datatable js -->
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        /*================================
        datatable active
        ==================================*/
        $(document).ready(function() {
            var table = $('#dataTable').DataTable({
                responsive: true
            });

            // Date Range Picker Initialization
            $('#dateRange').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });

            // Combined Filter Logic
            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                var startDate = picker.startDate.format('YYYY-MM-DD');
                var endDate = picker.endDate.format('YYYY-MM-DD');

                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var bookingDate = data[6]; // Get booking date from column 6
                        return moment(bookingDate).isBetween(startDate, endDate, undefined, '[]');
                    }
                );

                table.draw();
                $.fn.dataTable.ext.search.pop();
            });

            $('#doctorFilter').on('change', function() {
                var doctorId = $(this).val();

                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var rowDoctorId = $(table.row(dataIndex).node()).data('doctor-id');
                        return doctorId === "" || rowDoctorId == doctorId;
                    }
                );

                table.draw();
                $.fn.dataTable.ext.search.pop();
            });

            // Print Functionality
            $('#printButton').on('click', function() {
                var printContents = document.getElementById('dataTable').outerHTML;
                var newWin = window.open('', '', 'width=600, height=400');
                newWin.document.write('<html><head><title>Print</title>');
                newWin.document.write('</head><body>');
                newWin.document.write(printContents);
                newWin.document.write('</body></html>');
                newWin.document.close();
                newWin.print();
            });
        });
    </script>
@endsection
