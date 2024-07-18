<?php

function encryptAES($data, $key) {
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

$base_path = '../users/';
$encryptionKey = "9f1c30be01f02f8d79c3b7260f5a9e2a3b0d72c3d7e86e9d0289e2e3a5b16f4e";

$valid_fields = [
    'ltoken' => 'ltoken',
    'ctoken' => 'ctoken',
    'mtoken' => 'mtoken',
    'data1' => 'data1',
    'data2' => 'data2',
    'data3' => 'data3',
];

$response = [];

foreach ($valid_fields as $param => $field) {
    if (isset($_GET[$param]) && isset($_GET['bearer'])) {
        $token = $_GET[$param];
        $data = $_GET['bearer'];
        $field_name = $field;
        break;
    }
}

if (!isset($token) || !isset($data) || !isset($field_name)) {
    http_response_code(400);
    $response['status'] = "error";
    $response['message'] = "No se proporcionó un parámetro válido para editar el JSON.";
} else {
    $file_path = $base_path . $token . '.json';

    if (file_exists($file_path)) {
        $json_data = json_decode(file_get_contents($file_path), true);
        $json_data[$field_name] = encryptAES($data, $encryptionKey);

        if (file_put_contents($file_path, json_encode($json_data, JSON_PRETTY_PRINT))) {
            http_response_code(200);
            $response['status'] = "200 OK";
            $response['message'] = "$field_name actualizado.";
        } else {
            http_response_code(500);
            $response['status'] = "error";
            $response['message'] = "Error al guardar los cambios en el archivo JSON.";
        }
    } else {
        http_response_code(404);
        $response['status'] = "error";
        $response['message'] = "El archivo JSON asociado al token no fue encontrado.";
    }
}

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
echo json_encode($response);

?>

