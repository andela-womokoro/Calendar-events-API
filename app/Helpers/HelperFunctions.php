<?php

/**
 * Create the structure of responses sent back to clients.
 *
 * @param  int $code
 * @param  string $message
 * @param  boolean $success
 * @param  array $data
 * @return array
 */
function formatResponse($code = 500, $message = 'Internal Server Error', $success = false, $data = [])
{
    
    if (!checkStatusCodeValidity($code)) {
        $code = 500;
    }

    return response()->json([
        'success' => $success,
        'status_code' => $code,
        'message' => $message,
        'data' => $data
    ], $code);
}

/**
 * Extract the http status code from an exception object if available.
 *
 * @param  Exception $error
 * @return int
 */
function fetchErrorCode($error)
{
    $errorCode = 0;

    if (method_exists($error, 'getStatusCode')) {
        $errorCode =  $error->getStatusCode();
    } else if (method_exists($error, 'getCode')) {
        $errorCode =  $error->getCode();
    }

    return $errorCode;
}

/**
 * Runs a select SQL query and returns the result
 *
 * @param  string $query
 * @return array
 */
function fetchData($query, $connection = null)
{
    $code = 0;
    $message = "";
    $success = false;
    $data = [];

    try {

        if (is_null($connection)) {
            $data = \DB::select($query);
        } else {
            $data = \DB::connection($connection)->select($query);
        }

        if ($data) {
            $message = "Ok";
        } else {
            $message = "Record(s) not found";
        }

        $code = 200;
        $success = true;
    } catch (\Exception $e) {
        $code = fetchErrorCode($e);
        $message = "Exception: " . $e->getMessage();
    }

    return [
        'code' => $code,
        'message' => $message,
        'success' => $success,
        'data' => $data
    ];
}

/**
 * Checks if code is a valid http status code
 *
 * @param  int $code
 * @return boolean
 */
function checkStatusCodeValidity($code)
{
    $httpStatusCodes = ['100','101','200','201','202','203','204','205','206','300','301','302','303','304','305','306','307','400','401','402','403','404','405','406','407','408','409','410','411','412','413','414','415','416','417', '422', '500','501','502','503','504','505'];

    if (in_array($code, $httpStatusCodes)) {
        return true;
    }

    return false;
}
