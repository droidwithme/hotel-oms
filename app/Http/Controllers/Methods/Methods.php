<?php

namespace App\Http\Controllers\Methods;

use App\Models\User;
use DateTime;
use Exception;

class Methods
{

    /**
     * Create New log
     *
     * @param $userId
     * @param $petitionId
     * @param $logDetail
     * @param $logType
     */
    public static function addLog($userId, $petitionId, $logDetail, $logType)
    {
        $newLog = new Log();
        $newLog->desk_id = $userId;
        $newLog->petition_id = $petitionId;
        $newLog->type = $logType;
        $newLog->details = $logDetail;
        $newLog->save();
    }

    /**
     * Format the supplied date into proper MYSQL date
     * @param $dateString
     * @return false|string
     * @throws Exception
     */
    public static function formatDate($dateString)
    {
        $pre_formatted_date = new DateTime($dateString);
        $formatted_date = date_format($pre_formatted_date, 'Y-m-d');
        return $formatted_date;
    }

    /**
     * Get the user name (used only for log writing)
     * @param $userId
     * @return
     */
    public static function getUserName($userId)
    {
        $user = User::find($userId);
        return $user->name;
    }

    public static function sendOtpVerificationSMS($mobileNumber, $verificationCode)
    {
        // Account details
        $apiKey = urlencode(env('SMSVERIFICATION_APIKEY', 'unknown'));
        $appHash = urlencode(env('SMSVERIFICATION_APPHASH', 'unknown'));

        // Message details
        $numbers = array($mobileNumber);
        $sender = urlencode('TXTLCL');
        $message = rawurlencode('<#> Store App: Your verification code is ' . $verificationCode . '
' . $appHash);

        $numbers = implode(',', $numbers);

        // Prepare data for POST request
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

        // Send the POST request with cURL
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        dd($response);
        curl_close($ch);
    }
}
