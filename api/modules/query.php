<?php
    $query = $_GET['query'];

    try{
        $db->query($query);
    } catch (Exception $e) {
        return $e->getMessage();
    }

?>
