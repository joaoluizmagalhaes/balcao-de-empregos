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

    //Troca os caracteres especias do nome do Empregador
	function sanitizeString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
		//$str = preg_replace('/[^a-z0-9]/i', '_', $str);
		//$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
		return $str;
	}