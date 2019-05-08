<?php

	add_action('init', 'init_empregador_post_type');
	function init_empregador_post_type() {

		$labels = array(
		    'add_new'            => _x('Novo Empregador', 'empregador'),
	        'add_new_item'       => _x('Adicionar novo Empregador', 'empregador'),
	        'edit_item'          => _x('Editar Empregador', 'empregador'),
	        'menu_name'          => _x('Empregadores', 'empregador'),
	        'name'               => _x('Empregadores', 'empregador'),
	        'new_item'           => _x('Novo Empregador', 'empregador'),
	        'not_found'          => _x('Empregador não encontrado', 'empregador'),
	        'not_found_in_trash' => _x('Empregador não encontrado na lixeira', 'empregador'),
	        'search_items'       => _x('Pesquisar Empregadores', 'empregador'),
	        'singular_name'      => _x('Empregador', 'empregador'),
	        'view_item'          => _x('Ver Empregador', 'empregador')
	    );

	    $args = array(
	        'labels'             => $labels,
	        'menu_icon'          => 'dashicons-businessman',
	        'menu_position'      => 4,
	        'public'             => true,
	        'has_archive'        => true,
	        'supports'           => array('custom-fields'),
	        'show_ui'            => true,
	    );
		register_post_type('empregador', $args);
	}



	add_filter( 'wp_insert_post_data' , 'modify_empregadores_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.
	function modify_empregadores_post_title( $data ) {
		$postName = $_POST['acf']['field_5cc852822d488']['field_5cc852af2d489'];
		if($data && $postName){
			$data['post_title'] = $postName;
			$data['post_name'] = str_replace(' ', '-', sanitizeString(strtolower($postName)));
		}
		return $data; // Returns the modified data.
	}