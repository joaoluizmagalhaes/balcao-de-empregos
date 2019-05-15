<?php

	add_action('init', 'init_processos_post_type');
	function init_processos_post_type() {

		$labels = array(
		    'add_new'            => _x('Novo Processo', 'processos'),
	        'add_new_item'       => _x('Adicionar novo Processo', 'processos'),
	        'edit_item'          => _x('Editar Processo', 'processos'),
	        'menu_name'          => _x('Processos', 'processos'),
	        'name'               => _x('Processos', 'processos'),
	        'new_item'           => _x('Novo Processo', 'processos'),
	        'not_found'          => _x('Processo não encontrado', 'processos'),
	        'not_found_in_trash' => _x('Processo não encontrado na lixeira', 'processos'),
	        'search_items'       => _x('Pesquisar Processos', 'processos'),
	        'singular_name'      => _x('Processos', 'processos'),
	        'view_item'          => _x('Ver Processos', 'processos')
	    );

	    $args = array(
	        'labels'             => $labels,
	        'menu_icon'          => 'dashicons-list-view',
	        'menu_position'      => 6,
	        'public'             => true,
	        'has_archive'        => true,
	        'supports'           => array('custom-fields'),
	        'show_ui'            => true,
	    );
		register_post_type('processos', $args);
	}


	// Add the Meta Box for custom fields
	add_action('add_meta_boxes', 'numero_processo_custom_meta_box');
	function numero_processo_custom_meta_box() {
		
	    add_meta_box(
	        'process', // $id
	        'Número do Processo', // $title
	        'process_custom_meta_box', // $callback
	        'processos', // $page
	        'advanced', // $context
			'high'); // $priority
	 
	}

	// The Callback for gallery
	function process_custom_meta_box($post) { ?>
		<table class="form-table">
			<tr>
				<td>
					<?php
						$process_number = get_post_meta($post->ID, 'process_number'); 

						if($process_number[0] === NULL) {
							$getdate = getdate();
							$args = array(
								'post_type' => 'processos',
								'date_query' => array(
									array(
										'year'  => $getdate["year"]
									),
								),
							);
							$query = new WP_Query( $args );
							$processNumber = $query->post_count === 0 ? 1 : ($query->post_count + 1);
							echo str_pad($processNumber, 4, "0", STR_PAD_LEFT) . '/' . $getdate['year']; ?>
							<input name="process_number" type="hidden" value="<?php echo esc_html(str_pad($processNumber, 4, "0", STR_PAD_LEFT) . '/' . $getdate['year']);?>">
						<?php } else { ?>
							<?php echo esc_html($process_number[0]); ?>
							<input name="process_number" type="hidden" value="<?php echo esc_html($process_number[0]); ?>">
						<?php } 
					?>
				</td>
			</tr>
		</table>
	<?php }

	//saving the custom fields for product
	add_action('save_post_processos', 'process_save_custom_fields_items');
	function process_save_custom_fields_items($postid) {

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return '';
	    }

	    if ('' !== $_POST) {
	    	if($_POST['process_number']) {
				$_POST['process_number'] = sanitize_text_field($_POST['process_number']);
			}
			
	    	update_post_meta($postid, 'process_number', $_POST['process_number']);
	    }
	}



	add_filter( 'wp_insert_post_data' , 'modify_processos_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.
	function modify_processos_post_title( $data ) {
		if($_POST['process_number']) {
			$postName = sanitize_text_field($_POST['process_number']);
		}
		if($data && $postName){
			$data['post_title'] = $postName;
			$data['post_name'] = str_replace(' ', '-', sanitizeString(strtolower($postName)));
		}
		return $data; // Returns the modified data.
	}