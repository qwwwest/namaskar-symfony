<?php

namespace Namaskar;

$method = $_SERVER['REQUEST_METHOD']; // get update post delete

$page = $_GET['page'] ?? '/';

$urlParts = explode('/', $url);

$action = $urlParts[1] ?? 'plop';


$msg = '';

$m = new MemPad(MEMPAD_FILE);



$status = '{"status":"ok"}';

// create a hash for  /api?encode="LOGIN::PASSWORD"
// if (isset($_GET['encode'])) {
//     $encode =   trim($_GET['encode']);

//     if (strlen($encode) < 10) die("too short");


//     echo password_hash($encode, PASSWORD_BCRYPT);
//     exit;
// }

$isAuthed = isset($_SESSION['valid']) ? $_SESSION['valid'] : false;
header('Content-type:application/json;charset=utf-8');
// Create main USER on first time connection
if ($action === 'login' && $method === 'POST' && $USER === 1) {
    $data =  file_get_contents("php://input");
    $json = json_decode($data, true);
    $login = $json['glop'] ?? '';
    $pwd = $json['plop'] ?? '';

    $encode = "$login::$pwd";
    if (strlen($encode) < 10)
        die(json_encode(
            [
                "status" => "error",
                "message" => 'To short'
            ]
        ));
    $pwd = password_hash($encode, PASSWORD_BCRYPT);

    $file = file_get_contents('index.php');

    $info = "\$USER =  [
        'name' => '$login',
        'login' => '$login',
        'pwd' => '$pwd',
        'adminPage' => 'admin', 
     ];";


    $data = str_replace("\$USER = 1;", $info, "$file");
    file_put_contents('index.php', $data);
    session_destroy();
    $_SESSION = [];
    echo json_encode(["status" => "error"]);;
    exit();
}

// Authentication
if ($isAuthed !== true && $action === 'login' && $method === 'POST') {
    $data =  file_get_contents("php://input");
    $json = json_decode($data, true);
    $login = $json['glop'] ?? '';
    $pwd = $json['plop'] ?? '';

    if (

        $login === $USER['login']
        && password_verify($login . '::' . $pwd, $USER['pwd'])
    ) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['login'] = $login;
        // $_SESSION['readOnly'] = $USER["readOnly"] ?? false;
        $_SESSION["readOnly"] =  ($login === "demo");
        echo $status;
        exit();
    }

    if ($SUPERADMIN['login'] ?? false && $login === $SUPERADMIN['login'] && password_verify($login . '::' . $pwd, $SUPERADMIN['pwd'])) {
        $_SESSION['valid'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['login'] = $login;
        $_SESSION['readOnly'] = false;

        echo $status;
        exit();
    }
    //  header('HTTP/1.1 401 Unauthorized');
    echo json_encode(["status" => "error", "message" => 'Wrong username or password']);
    exit();
}

if ($action === "logout") {
    session_destroy();
    $_SESSION = [];

    echo json_encode(["status" => "ok"]);;
    exit();
}

// no session, no API. 
// Pas de bras, pas de chocolat.
if ($isAuthed !== true) {
    //header('HTTP/1.1 401 Unauthorized');
    $newUser = ($USER === 1) ? 1 : 0;
    echo json_encode(["status" => "no_auth", "new_user" => $newUser]);;
    exit();
}


switch ($method) {
    case 'POST':
        if ($action === "save") {

            $data =  file_get_contents("php://input");
            //file_put_contents(MEMPAD_FILE . ".out.data.json", $data);
            if ($_SESSION['login'] === "demo") {
                echo json_encode(["status" => "error", "message" => "could not saved data"]);
                exit();
            }
            echo $m->reactSortableTreeSave($data);

            exit();
        }

    case 'GET':

        if ($action === "check") {
            echo $status;
            exit();
        }
        if ($action === "tree") {
            echo $m->getStructureAsJson();
            exit();
        }
        if ($action === "page") {
            $id = (int) ($urlParts[2] ?? 0);
            $page = $m->getElementById($id) ??  (object) ['id' => -1];
            $page->content = $m->getContentById($id) ?? "";
            echo json_encode($page);
            exit();
        }
}

echo json_encode(["status" => "error", "message" => "ERROR: $method $url"]);
exit();
