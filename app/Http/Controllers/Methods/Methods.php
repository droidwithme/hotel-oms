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
}
