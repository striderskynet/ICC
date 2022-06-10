<?php
     switch(array_keys($_GET)[1]){
        case "total":
            echo clients_total();
            break;
        case "show":
            echo clients_show();
            break;
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
        case "upload":
            echo clients_upload();
            break;
    }

    function clients_total()
    {
        global $db;
        return $db->query('SELECT * FROM main_clients')->numRows();
    }

    function prices_list_min(){
        global $db;
        $where = null;

        if ( @isset($_GET['data']) ) {
            $where  = "WHERE `code` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `name` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `type` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `place` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `details` LIKE '%" . $_GET['data'] . "%'";
        }

        $query = 'SELECT id as `value`, CONCAT("<span>", code, "</span><span>", name, "</span><span>", type, "</span><span>", place, "</span>") as `text` FROM price_list ' . $where . ' ORDER BY `id` DESC;';

        $res = $db->query($query);
        $accounts = $res->fetchAll();

        foreach ($accounts as $account) {
            $data[] = $account;
        }
       
        if (!isset($data))
             $data = "";

       echo json_encode($data, JSON_PRETTY_PRINT);
    }

    function price_add(){
        global $db;

        //print_r ( $_POST );
        $query = "INSERT INTO `price_list` (
         `code`,
         `name`, 
         `type`, 
         `place`, 
         `from_date`, 
         `to_date`, 
         `price`, 
         `price_double`, 
         `price_tripled`, 
         `occupancy`, 
         `coin`, 
         `coin_symbol`,
         `details`) VALUES (
        '{$_POST['apf_code']}', 
        '{$_POST['apf_name']}', 
        '{$_POST['apf_type']}', 
        '{$_POST['apf_place']}', 
        '{$_POST['apf_from_date']}', 
        '{$_POST['apf_to_date']}', 
        '{$_POST['apf_price']}', 
        '{$_POST['apf_price_doubled']}', 
        '{$_POST['apf_price_tripled']}', 
        '{$_POST['apf_occupancy']}', 
        '{$_POST['apf_coin']}', 
        '{$_POST['apf_coin_symbol']}', 
        '{$_POST['apf_details']}');";

        $query = str_replace("\n", "", $query);
        debug(4, $query);
        
        if ( $db->query($query) ) return true;
        else return false;
    }

    function prices_delete(){
        global $db;
        
        $where = "WHERE `id` IN (";
        for ($q = 0; $q < count($_POST['info']); $q++){
        //foreach ( $_POST['info'] as $i ){
            if ( $q == count($_POST['info']) - 1)
                $where .= $_POST['info'][$q];
            else
            $where .= $_POST['info'][$q] . ", ";
        }
        $where .= ")";

        $query = "DELETE FROM price_list {$where};";
        debug(4, $query);

        try{
            $db->query($query);
        } catch (Exception $e) {
            return $e->getMessage();
        }
       return "Se ha eliminado el listado con ID: " . $where;
    }

    function prices_duplicate(){
        global $db;

        $query = null;
        
        foreach ( $_POST['info'] as $id){
            $query = "INSERT INTO `price_list` (`code`, `name`, `type`, `place`, `price`, `from_date`, `to_date`, `price_double`, `price_tripled`, `occupancy`, `coin`, `coin_symbol`, `details`) SELECT `code`, `name`, `type`, `place`, `price`, `from_date`, `to_date`, `price_double`, `price_tripled`, `occupancy`, `coin`, `coin_symbol`, `details` FROM `price_list` WHERE `id`={$id};";
            $db->query($query);
        }

        return $query;
    }


    function prices_list(){
        global $db, $config;

        $where = null;
        $order = "ORDER BY `id`";
        $dir = "DESC";
        $limit = null;
        $offset = null;

        if ( @isset($_GET['data']) ) {
            $where  = "WHERE `code` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `name` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `type` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `place` LIKE '%" . $_GET['data'] . "%'";
            $where .= " OR `details` LIKE '%" . $_GET['data'] . "%'";
        }

        if ( @isset($_GET['wh']))
            $where = $_GET['wh'];

        if ( @isset ($_GET['orderBy'])) {
            $order = "ORDER by `" . $_GET['orderBy'] . "`";
        }

        if ( @isset ($_GET['dir'] ) ){
            $dir = $_GET['dir'];
        }

        $limit = "LIMIT 50";

        if ( @isset($_GET['offset']) )
            $offset = "OFFSET " . ( ($_GET['offset'] - 1) * $config['misc']['pagination']);

       
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

    function clients_show(){
        global $db;

        $query = 'SELECT * FROM `main_clients` WHERE `id` = ' . $_GET['id'] . ' LIMIT 1';

        $data = $db->query($query)->fetchArray();
         // debug(4, $query);

        $profile_picture = md5 (  $data['passport'] .  $data['name'] .  $data['lastname'] );
                
        if ( file_exists ('../uploaded/'.  $profile_picture . ".jpg") )
            $data['profile_picture'] = "<img style='width: 100px;' class='rounded-circle my-5' src='./uploaded/" . $profile_picture . ".jpg' />";
        else
            $data['profile_picture'] = "<i class='fas fa-user-alt fa-6x my-5'></i>";
        
    
        return json_encode($data) ;
    }
    
    function clients_upload(){
        global $db;

        $file_name = md5 ( $_POST['passport'] . $_POST['name'] . $_POST['lastname'] );
        if ( $_FILES['file']['size'] > 0 ) 
          move_uploaded_file( $_FILES['file']['tmp_name'] , '../uploaded/'.  $file_name . ".jpg" );
          
    }
