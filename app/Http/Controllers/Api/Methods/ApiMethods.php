<?php

namespace App\Http\Controllers\Api\Methods;

use App\Http\Controllers\Controller;

class ApiMethods extends Controller
{
    public static function apiResponse($responseType, $responseBodyData)
    {
        if ($responseType == 'success') {
            return response([
                'status' => true,
                'statusCode' => 200,
                'message' => $responseBodyData['message'],
                'data' => (empty($responseBodyData['data'])) ? null : $responseBodyData['data']
            ], 200);
        } elseif ($responseType == 'error') {

            return response([
                'status' => false,
                'statusCode' => $responseBodyData['statusCode'],
                'error' => $responseBodyData['error'],
                'message' => $responseBodyData['message']
            ], $responseBodyData['statusCode']);
        } else {
            return response([
                'status' => false,
                'statusCode' => 500,
                'error' => 'unknown_request',
                'message' => 'Something Went Wrong'
            ], 500);
        }
    }

    public static function userInGeoLocation($userLatLong, $geoLocationCoordinate)
    {
        $isUserInCoordinate = false;
        $geoLocationCoordinate = preg_split('(,\s*)', $geoLocationCoordinate);
        $userCoordinate = ApiMethods::stringToCoordinateArray($userLatLong);
        $vertices = array();
        foreach ($geoLocationCoordinate as $vertex) {
            $vertices[] = ApiMethods::stringToCoordinateArray($vertex);
        }


        if (ApiMethods::locationOnVertex($userCoordinate, $vertices) == true) {
            $isUserInCoordinate = true;
        }

        $intersections = 0;
        $vertices_count = count($vertices);

        for ($i = 1; $i < $vertices_count; $i++) {
            $vertex1 = $vertices[$i - 1];
            $vertex2 = $vertices[$i];
            if ($vertex1['y'] == $vertex2['y'] and $vertex1['y'] == $userCoordinate['y'] and $userCoordinate['x'] > min($vertex1['x'], $vertex2['x']) and $userCoordinate['x'] < max($vertex1['x'], $vertex2['x'])) {
                $isUserInCoordinate = true;
            }
            if ($userCoordinate['y'] > min($vertex1['y'], $vertex2['y']) and $userCoordinate['y'] <= max($vertex1['y'], $vertex2['y']) and $userCoordinate['x'] <= max($vertex1['x'], $vertex2['x']) and $vertex1['y'] != $vertex2['y']) {
                $xinters = ($userCoordinate['y'] - $vertex1['y']) * ($vertex2['x'] - $vertex1['x']) / ($vertex2['y'] - $vertex1['y']) + $vertex1['x'];
                if ($xinters == $userCoordinate['x']) {
                    $isUserInCoordinate = true;
                }
                if ($vertex1['x'] == $vertex2['x'] || $userCoordinate['x'] <= $xinters) {
                    $intersections++;
                }
            }
        }
        if ($intersections % 2 != 0) {
            $isUserInCoordinate = true;
        }

        // return if user is in coordinate
        return $isUserInCoordinate;
    }

    public static function locationOnVertex($coordinate, $vertices)
    {
        foreach ($vertices as $vertex) {
            if ($coordinate == $vertex) {
                return true;
            }
        }
        return false;
    }

    public static function stringToCoordinateArray($coordinateString)
    {
        $coordinates = preg_split('/[\s]+/', $coordinateString);
        return array("x" => $coordinates[0], "y" => $coordinates[1]);
    }

    /**
     * Store in range
     * @param $hotelLat
     * @param $hotelLong
     * @param $userLat
     * @param $userLong
     * @param int $distance
     * @return bool
     */
    public static function isHotelInRange($hotelLat, $hotelLong, $userLat, $userLong, $distance = 40)
    {
        $earth_radius = 6371;

        $dLat = deg2rad($userLat - $hotelLat);
        $dLon = deg2rad($userLong - $hotelLong);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($hotelLat)) * cos(deg2rad($userLat)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * asin(sqrt($a));
        $d = $earth_radius * $c;

        if ($d <= $distance) {
            return true;
        }
        return false;
    }

    /**
     * Store distance
     * @param $hotelLat
     * @param $hotelLong
     * @param $userLat
     * @param $userLong
     * @param string $unit
     * @return float|int
     */

    public static function hotelDistanceToUser($hotelLat, $hotelLong, $userLat, $userLong, $unit = 'K')
    {
        if (($hotelLat == $userLat) && ($hotelLong == $userLong)) {
            return 0;
        } else {
            $theta = $hotelLong - $userLong;
            $dist = sin(deg2rad($hotelLat)) * sin(deg2rad($userLat)) + cos(deg2rad($hotelLat)) * cos(deg2rad($userLat)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }

    /**
     * Convert null to empty string on user model
     * @param $data
     * @return mixed
     */
    public static function convertNullToEmptyOnUser($data)
    {
        if ($data->profile_picture_path == null) {
            $data['profile_picture_path'] = "";
        }
        if ($data->lat == null) {
            $data['lat'] = "";
        }
        if ($data->lat == null) {
            $data['long'] = "";
        }

        return $data;
    }

    /**
     * Convert null to empty string on user model
     * @param $data
     * @return mixed
     */
    public static function convertNullToEmptyOnHotel($data)
    {
        if ($data->hotel_image == null) {
            $data['hotel_image'] = "";
        }
        return $data;
    }
}
