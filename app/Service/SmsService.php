<?php

namespace App\Service;

use Exception;

class SmsService
{
    protected $api_token;
    protected $sid;
    protected $domain;

    /**
    * Create a new class instance.
    *
    * @param string $api_token
    * @param string $sid
    * @param string $domain
    */
    public function __construct()
    {
        $this->api_token = '6mpxigx9-r9qtczun-dhepssss-gdpbdn02-miqbe0wz';
        $this->sid = 'DELTAHCCTGAPI';
        $this->domain = rtrim('https://smsplus.sslwireless.com', '/');
    }

    /**
    * Send a single SMS
    *
    * @param string $msisdn The recipient's phone number
    * @param string $messageBody The body of the SMS message
    * @param string $csmsId Unique CSMS ID (must be unique per day)
    * @return string The response from the API
    */
    public function sendSingleSms($msisdn, $messageBody)
    {
        $params = [
            "api_token" => $this->api_token,
            "sid" => $this->sid,
            "msisdn" => $msisdn,
            "sms" => $messageBody,
            "csms_id" => uniqid()
        ];

        $url = $this->domain . "/api/v3/send-sms";
        $params = json_encode($params);

        try {
            $response = json_decode($this->callApi($url, $params),true);
            if($response['status'] === 'FAILED'){
                dd($response['error_message']);
            }
        } catch (Exception $e) {
            // Handle the exception or log the error
            dd('SMS sending failed: ' . $e->getMessage());
        }
    }

    /**
    * Make a cURL call to the API
    *
    * @param string $url The API URL
    * @param string $params The JSON encoded request body
    * @return string The response from the cURL request
    * @throws Exception If cURL request fails
    */
    protected function callApi($url, $params)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for local testing, should be true in production
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($params),
            'accept:application/json'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            // Throw an exception in case of a cURL error
            throw new Exception(curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }
    public function composeMessage($patientName, $doctorName, $departmentName, $appointmentDate, $scheduleTime,$sl_no,$room_no) {
        $message = "Dear $patientName,\n\n";
        $message .= "Your appointment has been successfully booked!\n";
        $message .= "Here are your appointment details:\n\n";
        $message .= "Serial No.: $sl_no\n";
        $message .= "Doctor: Dr. $doctorName\n";
        $message .= "Department: $departmentName\n";
        $message .= "Room no: $room_no\n";
        $message .= "Appointment Date: $appointmentDate\n";
        $message .= "Time: $scheduleTime\n\n";
        $message .= "Please arrive 10 minutes early and bring any necessary documents with you.\n";
        $message .= "If you need to reschedule or have any questions, feel free to contact us.\n\n";
        $message .= "Thank you for choosing our healthcare services!\n";
        $message .= "We look forward to serving you.\n\n";
        $message .= "Best regards,\n";
        $message .= env('WEB_NAME');

        return $message;
    }
}
