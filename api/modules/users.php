<?php
switch (array_keys($_GET)[1]) {
    case "total":
        echo users_total();
        break;
    case "add":
        echo users_add();
        break;
    case "verify":
        echo users_verify();
        break;
    case "logout":
        echo users_logout();
        break;
}

function users_total()
{
    global $db;
    return $db->query('SELECT * FROM general_users')->numRows();
}

function users_add()
{
    global $db;

    $val['user']    = $_POST['username'];
    $val['pass1']   = $_POST['password'];
    $val['pass2']   = $_POST['password2'];
    $val['pass'] = md5($val['pass1']);
    $val['role'] = "root";

    $query = "INSERT INTO general_users( `username`, `password`, `role`)
        VALUES ('{$val['user']}','{$val['pass']}','{$val['role']}');";

    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function users_logout()
{
    session_destroy();
}

function users_verify()
{
    global $db;

    $log_user = $_POST['username'];
    $log_pass = md5($_POST['password']);

    $query = "SELECT * FROM general_users WHERE `username`='{$log_user}' AND `password`='{$log_pass}'";

    debug(5, $query);
    $result = $db->query($query)->fetchArray();

    if ( count ($result) > 0 )
    {
        $_SESSION['USERID'] = $result['username'];
        $_SESSION['SSID'] = $result['username'] . date("dd/mm/yy/");
        $_SESSION['USER_ROLE'] = $result['role'];

        return "{\"login\":\"true\"}\n";
    } else { return "{\"login\":\"false\"}\n"; }

    //return json_encode($result);
}
