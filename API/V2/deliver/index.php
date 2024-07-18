<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Content-Type: application/json");

$okFile = '../active.txt';
$okLoadFile = 'actload.txt';
$deliveredFile = 'delivered.txt';

// Función para verificar el formato del token
function isValidTokenFormat($token) {
    return preg_match('/^[A-Za-z]\d{2}[A-Za-z]{2}$/', $token);
}

if (isset($_GET['loadtokens']) && $_GET['loadtokens'] === '!') {
    // Leer tokens desde active.txt
    $tokens = file($okFile, FILE_IGNORE_NEW_LINES);

    if (!empty($tokens)) {
        $validTokens = [];
        $invalidTokens = [];

        // Filtrar tokens válidos
        foreach ($tokens as $tok) {
            if (isValidTokenFormat(trim($tok))) {
                $validTokens[] = trim($tok);
            } else {
                $invalidTokens[] = trim($tok);
            }
        }

        // Copiar solo los tokens válidos a actload.txt
        file_put_contents($okLoadFile, implode(PHP_EOL, $validTokens));

        // Preparar respuesta
        $response = [
            'message' => 'Tokens loaded successfully',
            'status' => '200 OK',
            'loaded' => count($validTokens),
            'invalid' => count($invalidTokens),
            'invalid_tokens' => $invalidTokens
        ];

        http_response_code(200);
        echo json_encode($response);
        exit();
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No tokens available']);
        exit();
    }
}

if (isset($_GET['newtoken']) && $_GET['newtoken'] === '!') {
    $tokens = file($okLoadFile, FILE_IGNORE_NEW_LINES);

    if (!empty($tokens)) {
        // Find the first valid token
        $token = null;
        foreach ($tokens as $index => $tok) {
            if (isValidTokenFormat(trim($tok))) {
                $token = trim($tok);
                unset($tokens[$index]);
                break;
            }
        }

        if ($token !== null) {
            // Move token to delivered.txt
            file_put_contents($deliveredFile, $token . PHP_EOL, FILE_APPEND);
            // Save remaining tokens back to actload.txt
            file_put_contents($okLoadFile, implode(PHP_EOL, $tokens));

            // Count remaining tokens
            $remainingTokens = count($tokens);

            // Prepare response with token and remaining count renamed to "disp"
            $response = [
                'token' => $token,
                'disp' => $remainingTokens
            ];

            echo json_encode($response);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'No valid tokens available']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'No tokens available']);
    }
}

if (isset($_GET['verify'])) {
    $tokenToVerify = $_GET['verify'];
    $tokens = file($okFile, FILE_IGNORE_NEW_LINES);

    if (in_array(trim($tokenToVerify), $tokens)) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>

