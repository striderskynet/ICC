<?php
    require_once("config.php");

    $theme_path = "./themes/script/";
    
    if ( isset($_GET['js'])){
        $script = null;

        switch ($_GET['js']){
            default:
                $script .= file_get_contents( "../assets/js/main.js" );
                break;
            case "default":
            case "voucher":
            case "login":
            case "panel":
            case "logs":
            case "users":
            case "prices":
                $script = file_get_contents( $theme_path . $_GET['js'] . ".exec.js" );
                break;
        }

        if ( !_DEBUG ){
        
            require_once ("./minifier.php");
            $script = \JShrink\Minifier::minify($script);

        }
        echo $script;
    }
?>