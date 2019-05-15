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

	// Salva o nome do trabalhador do curriculo como nome do post e também cria o permalink do curriculo com o nome
	add_filter( 'wp_insert_post_data' , 'modify_curriculo_post_title' , '99', 1 ); // Grabs the inserted post data so you can modify it.
	function modify_curriculo_post_title( $data ) {
		
		if($data && $_POST['acf']['field_5cc44d8181a56']){
			$data['post_title'] = $_POST['acf']['field_5cc44d8181a56'];
			$data['post_name'] = str_replace(' ', '-', sanitizeString(strtolower($_POST['acf']['field_5cc44d8181a56'])));
		}
		return $data; // Returns the modified data.
	}
  
  	add_filter('acf/validate_value/name=cpf', 'acf_unique_value_field', 10, 4);
  	function acf_unique_value_field($valid, $value, $field, $input) {
    	
    	if (!$valid || (!isset($_POST['post_ID']) && !isset($_POST['post_id']))) {
     		return $valid;
    	}
    	
    	if (isset($_POST['post_ID'])) {
      		$post_id = intval($_POST['post_ID']);
    	} else {
      		$post_id = intval($_POST['post_id']);
    	}
    	
    	if (!$post_id) {
      		return $valid;
    	}
    
    	$post_type = get_post_type($post_id);
    	$field_name = $field['name'];
    	
    	$args = array(
      		'post_type' => $post_type,
      		'post_status' => 'publish, draft, trash',
      		'post__not_in' => array($post_id),
      		'meta_query' => array(
        		array(
          			'key' => $field_name,
          			'value' => $value
        		)
      		)
    	);
    
    	$query = new WP_Query($args);
    
    	if (count($query->posts)){
      		return 'Este CPF já está cadastrado.';
    	}
    
    	return true;
  	}