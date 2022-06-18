<?php
switch (array_keys($_GET)[1]) {
    case "add":
        echo price_add();
        break;
    case "list":
        echo prices_list();
        break;
    case "list_min":
        echo prices_list_min();
        break;
    case "duplicate":
        echo prices_duplicate();
        break;
    case "delete":
        echo prices_delete();
        break;
}


function prices_list_min()
{
    global $db;
    $where = null;

    if (@isset($_GET['data'])) {
        $where  = "WHERE `code` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `name` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `type` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `place` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `details` LIKE '%" . $_GET['data'] . "%'";
    }

    $query = 'SELECT id as `value`, CONCAT(`code`, "<br>", `name`, "<br>", `type`, "\t\t\t", `place`) as `text` FROM price_list ' . $where . ' ORDER BY `id` DESC;';

    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {
        $data[] = $account;
    }

    if (!isset($data))
        $data = "";

    echo json_encode($data, JSON_PRETTY_PRINT);
}

function price_add()
{
    global $db;

    //print_r ( $_POST );
    $query = "INSERT INTO `price_list` (
        `code`,
        `name`,
        `type`,
        `place`,
        `from_date`,
        `to_date`,
        `season`,
        `plan`,
        `price_pax_double`,
        `price_simple`,
        `price_tripled`,
        `price_dinner`,
        `hab_doble`,
        `hab_simple`,
        `hab_tripled`,
        `offert`,
        `offert_validity`,
        `offert_from`,
        `offert_to`,
        `provider`,
        `kids_policy`,
        `room_vacancy`) VALUES (
        '{$_POST['apf_code']}',
        '{$_POST['apf_name']}',
        '{$_POST['apf_type']}',
        '{$_POST['apf_place']}',
        '{$_POST['apf_from_date']}',
        '{$_POST['apf_to_date']}',
        '{$_POST['apf_season']}',
        '{$_POST['apf_plan']}',
        '{$_POST['apf_price_pax_double']}',
        '{$_POST['apf_price_simple']}',
        '{$_POST['apf_price_tripled']}',
        '{$_POST['apf_price_dinner']}',
        '{$_POST['apf_hab_doble']}',
        '{$_POST['apf_hab_simple']}',
        '{$_POST['apf_hab_tripled']}',
        '{$_POST['apf_offert']}',
        '{$_POST['apf_offert_validity']}',
        '{$_POST['apf_offert_from']}',
        '{$_POST['apf_offert_to']}',
        '{$_POST['apf_provider']}',
        '{$_POST['apf_kids_policy']}',
        '{$_POST['apf_room_vacancy']}');";

    $query = str_replace("\n", "", $query);
    debug(4, $query);

    if ($db->query($query)) return true;
    else return false;
}

function prices_delete()
{
    global $db;

    $where = "WHERE `id` IN (";
    for ($q = 0; $q < count($_POST['info']); $q++) {
        //foreach ( $_POST['info'] as $i ){
        if ($q == count($_POST['info']) - 1)
            $where .= $_POST['info'][$q];
        else
            $where .= $_POST['info'][$q] . ", ";
    }
    $where .= ")";

    $query = "DELETE FROM price_list {$where};";
    debug(4, $query);

    try {
        $db->query($query);
    } catch (Exception $e) {
        return $e->getMessage();
    }
    return "Se ha eliminado el listado con ID: " . $where;
}

function prices_duplicate()
{
    global $db;

    $query = null;

    foreach ($_POST['info'] as $id) {
        $query = "INSERT INTO `price_list` (`code`, `name`, `type`, `place`, `from_date`, `to_date`, `season`, `plan`, `price_pax_double`, `price_simple`, `price_tripled`, `price_dinner`, `hab_doble`, `hab_simple`, `hab_tripled`, `offert`, `offert_validity`, `offert_from`, `offert_to`, `provider`, `kids_policy`, `room_vacancy`) 
        SELECT `code`, `name`, `type`, `place`, `from_date`, `to_date`, `season`, `plan`, `price_pax_double`, `price_simple`, `price_tripled`, `price_dinner`, `hab_doble`, `hab_simple`, `hab_tripled`, `offert`, `offert_validity`, `offert_from`, `offert_to`, `provider`, `kids_policy`, `room_vacancy` FROM `price_list` WHERE `id`={$id};";
        $db->query($query);
    }

    return $query;
}


function prices_list()
{
    global $db, $config;

    $where = null;
    $order = "ORDER BY `id`";
    $dir = "DESC";
    $limit = null;
    $offset = null;

    if (@isset($_GET['data'])) {
        $where  = "WHERE `code` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `name` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `type` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `place` LIKE '%" . $_GET['data'] . "%'";
        $where .= " OR `provider` LIKE '%" . $_GET['data'] . "%'";
    }

    if (@isset($_GET['wh']))
        $where = $_GET['wh'];

    if (@isset($_GET['orderBy'])) {
        $order = "ORDER by `" . $_GET['orderBy'] . "`";
    }

    if (@isset($_GET['dir'])) {
        $dir = $_GET['dir'];
    }

    $limit = "LIMIT 50";

    if (@isset($_GET['offset']))
        $offset = "OFFSET " . (($_GET['offset'] - 1) * $config['misc']['pagination']);


    $query = "SELECT * FROM `price_list` {$where} {$order} {$dir} {$limit} {$offset};";
    //$query = 'SELECT * FROM main_clients ' . $where . ' ' . $order . ' DESC ' . $limit .' ' . $offset .';';
    $query_no_limit = 'SELECT count(*) as `total` FROM price_list ' . $where . ' ORDER BY `id` DESC;';

    //print_r ( $query );
    //debug(4, $query);
    $res = $db->query($query);
    $accounts = $res->fetchAll();

    foreach ($accounts as $account) {

        $data[] = $account;
    }

    $data['info'] = $db->query($query_no_limit)->fetchAll();

    if (!isset($data))
        return json_encode("");

    //return json_encode($data, JSON_PRETTY_PRINT);
    return json_encode($data);
}
