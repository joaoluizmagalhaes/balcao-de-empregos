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

		 	add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('curriculo_post_type_js',get_stylesheet_directory_uri().'/plugins/curriculos-post-type/js/curriculos.js',array('jquery'));
		        wp_enqueue_script('curriculo_post_type_js');

		       // wp_register_style('download_post_type_css',get_stylesheet_directory_uri().'/plugins/download-post-type/css/download.css');
		       // wp_enqueue_style('download_post_type_css');
		    }
		}
	}

	// Add the Meta Box for custom fields
	add_action('add_meta_boxes', 'curriculo_birth_custom_meta_box');
	function curriculo_birth_custom_meta_box() {
		
	    add_meta_box(
	        'age', // $id
	        'Idade', // $title
	        'age_custom_meta_box', // $callback
	        'curriculo', // $page
	        'side', // $context
			'high'); // $priority
	 
	}

	// The Callback for gallery
	function age_custom_meta_box($post) { ?>
		<table class="form-table">
			<tr>
				<td>
					<?php
						$date = get_field('data_de_nascimento');
						if($date) {
							$birthday = new DateTime($date);
							$interval = $birthday->diff(new DateTime);
							?>
							<span class='idade'><?php echo $interval->y.' Anos'; ?></span>
							<?php
						}
					?>
				</td>
			</tr>
		</table>
	<?php }

	//saving the custom fields for product
	// add_action('save_post_curriculo', 'curriculo_save_custom_fields_items');
	// function curriculo_save_custom_fields_items($postid) {

	// 	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	//         return '';
	// 	}

	//      if ('' !== $_POST) {
	//     	if($_POST['acf']['field_5cc44d8181a56']) {
	// 			$_POST['post_title'] = $_POST['acf']['field_5cc44d8181a56'];
	// 		}
	//     }
	// }

	add_filter( 'wp_insert_post_data' , 'modify_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.
	function modify_post_title( $data ) {
		
		if($data && $_POST['acf']['field_5cc44d8181a56']){
			$data['post_title'] = $_POST['acf']['field_5cc44d8181a56'];
			$data['post_name'] = str_replace(' ', '-', sanitizeString(strtolower($_POST['acf']['field_5cc44d8181a56'])));
		}
		return $data; // Returns the modified data.
	}

	function sanitizeString($str) {
		$str = preg_replace('/[áàãâä]/ui', 'a', $str);
		$str = preg_replace('/[éèêë]/ui', 'e', $str);
		$str = preg_replace('/[íìîï]/ui', 'i', $str);
		$str = preg_replace('/[óòõôö]/ui', 'o', $str);
		$str = preg_replace('/[úùûü]/ui', 'u', $str);
		$str = preg_replace('/[ç]/ui', 'c', $str);
		// $str = preg_replace('/[,(),;:|!"#$%&/=?~^><ªº-]/', '_', $str);
		$str = preg_replace('/[^a-z0-9]/i', '_', $str);
		$str = preg_replace('/_+/', '_', $str); // ideia do Bacco :)
		return $str;
	}
	