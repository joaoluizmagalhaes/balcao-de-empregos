<?php

	add_action('init', 'init_curriculo_post_type');
	function init_curriculo_post_type() {

		$labels = array(
		    'add_new'            => _x('Novo Currículo', 'curriculo'),
	        'add_new_item'       => _x('Adicionar novo Currículo', 'curriculo'),
	        'edit_item'          => _x('Editar Currículo', 'curriculo'),
	        'menu_name'          => _x('Currículos', 'curriculo'),
	        'name'               => _x('Currículos', 'curriculo'),
	        'new_item'           => _x('Novo Currículo', 'curriculo'),
	        'not_found'          => _x('Currículo não encontrado', 'curriculo'),
	        'not_found_in_trash' => _x('Currículo não encontrado na lixeira', 'curriculo'),
	        'search_items'       => _x('Pesquisar Currículos', 'curriculo'),
	        'singular_name'      => _x('Currículo', 'curriculo'),
	        'view_item'          => _x('Ver Currículo', 'curriculo')
	    );

	    $args = array(
	        'labels'             => $labels,
	        'menu_icon'          => 'dashicons-id-alt',
	        'menu_position'      => 4,
	        'public'             => true,
	        'has_archive'        => true,
	        'supports'           => array('custom-fields'),
	        'show_ui'            => true,
	    );
	    register_post_type('curriculo', $args);
	}

	add_action('current_screen', 'myScreen_curriculo_post_type');
	function myScreen_curriculo_post_type() {

		 if ('curriculo' === get_current_screen()->id) {

		 	//add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('download_post_type_js',get_stylesheet_directory_uri().'/plugins/download-post-type/js/download.js',array('jquery'));
		        wp_enqueue_script('download_post_type_js');

		        wp_register_style('download_post_type_css',get_stylesheet_directory_uri().'/plugins/download-post-type/css/download.css');
		        wp_enqueue_style('download_post_type_css');
		    }
		}
	}