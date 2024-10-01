<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Booked Successfully</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-container {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
        }
        .btn-home {
            margin-top: 20px;
            padding: 10px 20px;
        }
    </style>
</head>
<body>

<div class="success-container">
    <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM7.363 11.818a.54.54 0 0 0 .747 0l4.08-4.08a.54.54 0 0 0-.747-.748l-3.356 3.356-1.512-1.513a.54.54 0 1 0-.748.748l1.536 1.537z"/>
        </svg>
    </div>
    <h3 class="mt-4">Appointment Booked Successfully!</h3>
    <p class="mt-3">Thank you for booking your appointment with us. We have successfully scheduled your appointment.</p>

    <div class="appointment-details mt-4">
        <h5>Your Appointment Details</h5>
        <p><strong>Doctor:</strong> {{ $doctorName }}</p>
        <p><strong>Date:</strong> {{ $bookingDate }}</p>
        <p><strong>Time:</strong> {{ $scheduleTime }}</p>
    </div>

    <a href="{{ route('home') }}" class="btn btn-primary btn-home">Back to Home</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
