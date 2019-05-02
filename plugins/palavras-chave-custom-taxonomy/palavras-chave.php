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
			'meta_box_cb' => 'palavras_chave_tax_in_post_type_meta_box'
		));

	}

	//Getting Palavras Chave terms into post edit
    function palavras_chave_tax_in_post_type_meta_box($post) {
        $terms = get_terms('palavras_chave', array('hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC'));
        $post = get_post();
        $brand_term = get_the_terms($post->ID, 'palavras_chave');
        $name = '';
        if (!is_wp_error($brand_term) || $brand_term) {
            if (isset($brand_term[0]) && isset($brand_term[0]->name)) {
                $name = $brand_term[0]->name;
            }
        }
        wp_nonce_field('save_palavras_chave_tax_meta_box', 'custom_taxonomy_palavras_chave_tax_data_entry_nonce');
        ?>

        <select name="brand">
            <option value="">Selecione as Palavras Chave</option>
            <?php if(is_array($terms)) { ?>
                <?php foreach ($terms as $term): ?>
                    <option
                        value='<?php esc_attr_e($term->name); ?>' <?php selected($term->name, $name); ?> ><?php esc_attr_e($term->name); ?></option>
                <?php endforeach; ?>
            <?php } ?>
        </select>

        <?php
    }

    // Saving palavras_chave term to post save
    function save_palavras_chave_tax_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['brand'])) {
            return;
        }

        if (
            !isset($_POST['custom_taxonomy_palavras_chave_tax_data_entry_nonce'])
            || !wp_verify_nonce($_POST['custom_taxonomy_palavras_chave_tax_data_entry_nonce'], 'save_palavras_chave_tax_meta_box')
        ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }

        $brand = sanitize_text_field($_POST['brand']);
        if (empty($brand)) {
            wp_set_object_terms($post_id, null, 'palavras_chave', false);
        } else {
            $keyWordTerm = get_term_by('name', $brand, 'palavras_chave');
            if (!empty($keyWordTerm) && !is_wp_error($keyWordTerm)) {
                wp_set_object_terms($post_id, $keyWordTerm->term_id, 'palavras_chave', false);
            }
        }
    }

	//enqueue scripts for admin if is palavras_chave taxonomy
	add_action('current_screen', 'myScreen_ybuy_palavras_chave_taxonomy');
	function myScreen_ybuy_palavras_chave_taxonomy() {

		 if ('edit-palavras_chave' ===  get_current_screen()->id) {

			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_palavras_chave_taxonomy_js',get_stylesheet_directory_uri().'/plugins/palavras_chave_taxonomy/js/palavras_chave_taxonomy.js',array('jquery'));
		        wp_enqueue_script('ybuy_palavras_chave_taxonomy_js');

		        wp_register_style('ybuy_palavras_chave_taxonomy_css',get_stylesheet_directory_uri().'/plugins/palavras_chave_taxonomy/css/palavras_chave_taxonomy.css');
		        wp_enqueue_style('ybuy_palavras_chave_taxonomy_css');
		    }
		    add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
		}
	}
	

	// A callback function to add a custom field to our "marcas" taxonomy  
	add_action( 'palavras_chave_add_form_fields', 'palavras_chave_taxonomy_custom_fields', 10, 2 );  
	function palavras_chave_taxonomy_custom_fields($taxonomy) {  

		//nonce for the custom fields of palavras_chave taxonomy
		wp_nonce_field('save_palavras_chave_taxonomy', 'palavras_chave_taxonomy_fields_nonce');
	} 
	
	// Save the changes made on the "palavras_chave" taxonomy, using our callback function
	add_action( 'create_palavras_chave', 'save_palavras_chave_taxonomy', 10, 2 ); 
	function save_palavras_chave_taxonomy($term_id, $tt_id) {

		if (!isset($_POST['palavras_chave_taxonomy_fields_nonce']) || !wp_verify_nonce($_POST['palavras_chave_taxonomy_fields_nonce'], 'save_palavras_chave_taxonomy')) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            if ('' !== $_POST) {
            	$subtitle = sanitize_text_field($_POST['term_meta']['subtitle']);
                add_term_meta($term_id, 'subtitle', $subtitle, true);

                $palavras_chave_logo = sanitize_text_field($_POST['term_meta']['palavras_chave_logo']);
                add_term_meta($term_id, 'palavras_chave_logo', $palavras_chave_logo, true);

                $hint = sanitize_text_field($_POST['term_meta']['hint']);
                add_term_meta($term_id, 'hint', $hint, true);

                $palavras_chave_image = sanitize_text_field($_POST['term_meta']['palavras_chave_image']);
                add_term_meta($term_id, 'palavras_chave_image', $palavras_chave_image, true);
            }
        }
	}
	
	//Edit the mostra Taxonomy
	add_action('palavras_chave_edit_form_fields', 'edit_palavras_chave_taxonomy_fields', 10, 2);
    function edit_palavras_chave_taxonomy_fields($term, $taxonomy) {

        $palavras_chave_subtitle = get_term_meta($term->term_id, 'subtitle', true);
        $palavras_chave_palavras_chave_logo = get_term_meta($term->term_id, 'palavras_chave_logo', true);
        $palavras_chave_hint = get_term_meta($term->term_id, 'hint', true);
        $palavras_chave_palavras_chave_image = get_term_meta($term->term_id, 'palavras_chave_image', true);
        
        wp_nonce_field('save_palavras_chave_taxonomy', 'palavras_chave_taxonomy_fields_nonce');
        ?>
		
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="subtitle"><?php _e('Sub título'); ?></label></th>
			<td>
		        <div class="form-field term-group">
			        <input type="text" name="term_meta[subtitle]" id="term_meta[subtitle]" value="<?php echo esc_html($palavras_chave_subtitle); ?>"><br />  
			        <p class="description"><?php _e('Texto que aparece em baixo do nome da marca.'); ?></p>  
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="palavras_chave_logo"><?php _e( 'Marca Logo:', 'journey' ); ?></label></th>
			<td>
		        <div class="form-field term-group">
					<img src="<?php echo esc_html($palavras_chave_palavras_chave_logo); ?>" alt="" class="palavras_chave_image_src palavras_chave_logo">
					<input type="hidden" name="term_meta[palavras_chave_logo]" id="term_meta[palavras_chave_logo]" class="palavras_chave_thumb" value="<?php echo esc_html($palavras_chave_palavras_chave_logo); ?>">
					<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="hint"><?php _e('Curiosidade'); ?></label></th>
			<td>
		        <div class="form-field term-group">
		        	<input type="text" name="term_meta[hint]" id="term_meta[hint]" value="<?php echo esc_html($palavras_chave_hint); ?>"><br />  
		        	<p class="description"><?php _e('Curiosidade sobre a marca'); ?></p>
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="palavras_chave_image"><?php _e( 'Imagem destacada da Marca:', 'journey' ); ?></label></th>
			<td>
		        <div class="form-field term-group">
		        	<img src="<?php echo esc_html($palavras_chave_palavras_chave_image);?>" alt="" class="palavras_chave_image_src palavras_chave_image">
		        	<input type="hidden" name="term_meta[palavras_chave_image]" id="term_meta[palavras_chave_image]" class="palavras_chave_thumb" value="<?php echo esc_html($palavras_chave_palavras_chave_image); ?>">
					<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
				</div>
			</td>
		</tr>

        <?php
    }

    // Edit Save the mostra Taxonomy
    add_action('edited_palavras_chave', 'update_palavras_chave_taxonomy_meta', 10, 2);
    function update_palavras_chave_taxonomy_meta($term_id, $tt_id) {

        if (!isset($_POST['palavras_chave_taxonomy_fields_nonce']) || !wp_verify_nonce($_POST['palavras_chave_taxonomy_fields_nonce'], 'save_palavras_chave_taxonomy')) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            if ('' !== $_POST) {

            	$subtitle = sanitize_text_field($_POST['term_meta']['subtitle']);
                update_term_meta($term_id, 'subtitle', $subtitle);

                $palavras_chave_logo = sanitize_text_field($_POST['term_meta']['palavras_chave_logo']);
                update_term_meta($term_id, 'palavras_chave_logo', $palavras_chave_logo);

                $hint = sanitize_text_field($_POST['term_meta']['hint']);
                update_term_meta($term_id, 'hint', $hint);

                $palavras_chave_image = sanitize_text_field($_POST['term_meta']['palavras_chave_image']);
                update_term_meta($term_id, 'palavras_chave_image', $palavras_chave_image);
            }
        }
    }