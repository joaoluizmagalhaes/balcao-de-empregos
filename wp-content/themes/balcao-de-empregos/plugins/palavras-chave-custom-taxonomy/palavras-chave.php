<?php 

	//hook into the init action and call create_book_taxonomies when it fires and create a custom taxonomy name it palavras_chave for your posts
	add_action( 'init', 'create_palavras_chave_taxonomy', 0 );
	function create_palavras_chave_taxonomy() {

		// Add new taxonomy, make it non hierarchical 
		//first do the translations part for GUI

		$labels = array(
			'name' => _x( 'Palavras Chave', 'taxonomy general name' ),
			'singular_name' => _x( 'Palavras Chave', 'taxonomy singular name' ),
			'search_items' =>  __( 'Procurar Palavras Chave' ),
			'all_items' => __( 'Todas Palavras Chave' ),
			'add_item' => __( 'Editar Palavras Chave' ), 
			'update_item' => __( 'Atualizar Palavras Chave' ),
			'add_new_item' => __( 'Adicionar Nova Palavras Chave' ),
			'new_item_name' => __( 'Nova Palavras Chave' ),
			'menu_name' => __( 'Palavras Chave' ),
		);    

		// Now register the taxonomy

		register_taxonomy('palavras_chave',array('processos', 'curriculo'), array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'palavras_chave' ),
			'field_type' => 'select_advanced',
		));

	}