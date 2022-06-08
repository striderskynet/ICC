<?php
$position = array('Reservas', 'Listado de Reservas', 'voucher');
$theme = file_get_contents(_LOCAL . "/core/themes/html/" . $position[2] . ".theme.html");

$theme_script = "voucher";
//$clients_data = api("clients", "list");
?>
<script defer>
    let pagination = <?php echo $config['misc']['pagination'] ?>;
    let position = [];

    position['sub_title'] = '<?php echo $position[0] ?>';
    position['title'] = '<?php echo $position[1] ?>';
    position['var'] = '<?php echo $position[2] ?>';

    var clients_data_api = null;
</script>
<?php echo $theme; ?>