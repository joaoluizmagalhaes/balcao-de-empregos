<?php
    require_once get_template_directory() . '/plugins/plugins.php';
    
    function mask($val, $mask){
        $maskared = '';
        $k = 0;
        for($i = 0; $i<=strlen($mask)-1; $i++) {
            if($mask[$i] == '#') {
                if(isset($val[$k]))
                $maskared .= $val[$k++];
            } else {
                if(isset($mask[$i]))
                $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    add_action('wp_enqueue_scripts','balcao_enqueue_styles',7);
    function balcao_enqueue_styles(){
        wp_enqueue_style('main-style',get_template_directory_uri().'/_assets/css/style.css');
    }