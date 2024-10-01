<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            background: #f8f9fa;
            padding: 20px;
        }
        .appointment-form {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
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
                        <input type="date" class="form-control" id="booking_date" name="booking_date" required>
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
<script>
    $(document).ready(function() {
        $('.select2').select2();

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
                            $('#doctor_id').append('<option value="' + doctor.id + '">' + doctor.name + ' (' + doctor.specialization + ')</option>');
                        });
                    }
                });
            } else {
                $('#doctor_id').html('<option value="">Select Doctor</option>').prop('disabled', true);
            }
        });

        // When a doctor is selected, load their appointment schedules
        $('#doctor_id').change(function() {
            var doctorId = $(this).val();
            $('#appointment_schedule_id').prop('disabled', true).empty().append('<option value="">Select Appointment Schedule</option>');

            if (doctorId) {
                $.ajax({
                    url: "{{ route('appointmentSchedules.byDoctor') }}", // Replace with your route
                    type: 'GET',
                    data: { doctor_id: doctorId },
                    success: function(data) {
                        $('#appointment_schedule_id').prop('disabled', false);
                        $.each(data, function(key, value) {
                            $('#appointment_schedule_id').append('<option value="'+ value.id +'">' + value.day_of_week + ' (' + value.start_time + ' - ' + value.end_time + ')</option>');
                        });
                    }
                });
            } else {
                $('#appointment_schedule_id').html('<option value="">Select Appointment Schedule</option>').prop('disabled', true);
            }
        });
    });
</script>
</body>
</html>
