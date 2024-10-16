<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .appointment-form {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ced4da;
        }
        .form-control, .select2-selection--single {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            border-radius: 10px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container" id="form-booking">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="appointment-form">
                    <h3 class="form-title">Book an Appointment</h3>
                    <form action="{{ route('bookings.store') }}" method="POST" id="appointmentForm">
                        @csrf
                        <div class="mb-3">
                            <label for="patient_name" class="form-label">Patient Name</label>
                            <input type="text" class="form-control" id="patient_name" name="patient_name" placeholder="Enter your name" required>
                        </div>

                        <div class="mb-3">
                            <label for="patient_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="patient_email" name="patient_email" placeholder="Enter your email">
                        </div>

                        <div class="mb-3">
                            <label for="patient_phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="patient_phone" name="patient_phone" placeholder="Enter your phone number">
                        </div>

                        <div class="mb-3">
                            <label for="department_id" class="form-label">Select Department</label>
                            <select class="form-select select2" id="department_id" name="department_id" required>
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Select Doctor</label>
                            <select class="form-select select2" id="doctor_id" name="doctor_id" required disabled>
                                <option value="">Select Doctor</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="appointment_schedule_id" class="form-label">Select Appointment Schedule</label>
                            <select class="form-select select2" id="appointment_schedule_id" name="appointment_schedule_id" required disabled>
                                <option value="">Select Appointment Schedule</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="booking_date" class="form-label">Booking Date</label>
                            <input type="text" class="form-control" id="booking_date" name="booking_date" placeholder="Select Booking Date" required disabled>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Book Appointment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {

        // When a department is selected, load doctors
        $('#department_id').change(function() {
            var departmentId = $(this).val();
            $('#doctor_id').prop('disabled', true).empty().append('<option value="">Select Doctor</option>');
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');

            if (departmentId) {
                $.ajax({
                    url: "{{ route('doctors.byDepartment') }}",
                    type: 'GET',
                    data: { department_id: departmentId },
                    success: function(data) {
                        $('#doctor_id').prop('disabled', false);
                        $.each(data, function(key, doctor) {
                            $('#doctor_id').append('<option value="' + doctor.id + '">' + doctor.name + ' (' + doctor.specialization + ')</option>');
                        });
                    }
                });
            }
        });

        // When a doctor is selected, load their appointment schedules
        $('#doctor_id').change(function() {
            var doctorId = $(this).val();
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');

            if (doctorId) {
                $.ajax({
                    url: "{{ route('appointmentSchedules.byDoctor') }}",
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function(data) {
                        $('#appointment_schedule_id').prop('disabled', false);
                        $.each(data, function(key, value) {
                            $('#appointment_schedule_id').append('<option value="'+ value.id +'">' + value.day_of_week + ' (' + value.start_time + ' - ' + value.end_time + ')</option>');
                        });

                        setupFlatpickr(data);
                    }
                });
            }
        });

        function setupFlatpickr(schedules) {
    var availableDays = schedules.map(function(schedule) {
        return schedule.day_of_week.toLowerCase();
    });

    var today = new Date();
    var sevenDaysLater = new Date();
    sevenDaysLater.setDate(today.getDate() + 7); // Limit to next 7 days

    $('#booking_date').flatpickr({
        dateFormat: 'Y-m-d',
        minDate: 'today',
        maxDate: sevenDaysLater,  // Limit to next 7 days
        enable: [
            function(date) {
                var day = date.toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
                return availableDays.includes(day); // Enable only available days
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





    });
    $(document).ready(function() {
        // Your existing JavaScript for loading doctors and schedules...

        // Handle form submission
        $('#appointmentForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $.ajax({
                url: $(this).attr('action'), // The URL to submit the form to
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    // If successful, you can show a success message
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your appointment has been booked successfully.',
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });
                    // Optionally reset the form or redirect
                    $('#form-booking').html(response);

                },
                error: function(xhr) {
                    // If there's an error, display the error messages
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = '';

                    // Loop through error messages
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorMessages += errors[key].join('<br>') + '<br>'; // Add each error
                        }
                    }

                    Swal.fire({
                        title: 'Error!',
                        html: errorMessages, // Display errors
                        icon: 'error',
                        confirmButtonText: 'Okay'
                    });
                }
            });
        });
    });

</script>
</body>
</html>
