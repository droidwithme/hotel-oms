<?php

namespace App\Http\Controllers\Methods\FireBase;

use App\Http\Controllers\Api\ApiMethods;
use App\Models\MapCoordinate;
use App\Models\PositionUserRelation;

class FireBaseHelper
{
    static function orderNotification($collapseKey, $orderStatus, $orderDetails, $fcmToken)
    {
        // Build the tokens array list
        $deviceTokenList = $fcmToken;

        if (sizeof($deviceTokenList) > 0) {
            $notificationPayload = [
                'orderStatus' => $orderStatus,
                'orderDetails' => $orderDetails
            ];
            self::SendFcmNotification($deviceTokenList, $collapseKey, $notificationPayload);
        }
    }

    static function SendFcmNotification($deviceTokens, $collapseKey, $notificationPayload = null)
    {

        $deviceTokenArray = array_values($deviceTokens);

        $apiKey = 'AAAA6NuEtYA:APA91bF1iykraEjWeoIH_zOs8UykbOr1D7sklclCVAwQrLgrdd_Jq5kASZsLIsCgeITQS0pq7agbna-Yp3I0zRi67rx8DuVzmfoBdACCYsCR0Sb1UFgiCVm8Tl0c8DE4Ac_roUCTGS5X';

        $headers = array('Authorization: key=' . $apiKey, 'Content-Type: application/json');


        $notificationBody = [
            'registration_ids' => $deviceTokenArray,
            'collapse_key' => $collapseKey,
            'data' => $notificationPayload
        ];

        if ($headers) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://fcm.googleapis.com/fcm/send");
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notificationBody));
            $response = curl_exec($ch);
        }
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch)) {
            return false; //probably you want to return false
        }
        if ($httpCode != 200) {
            return false; //probably you want to return false
        }
        curl_close($ch);
    }
}