<?php 

    add_action( 'admin_menu', 'processos_curriculos_list' );

    function processos_curriculos_list() {
        add_menu_page( 
            'Lista de Processos', 
            'Lista de Processos', 
            'manage_options', 
            'processos-curriclos', 
            'curriculos_list_page',
            'dashicons-media-text', 8  );
    }

    function curriculos_list_page() {

        $getdate = getdate();
        $args = array(
            'post_type' => 'processos',
            'post_status' => 'publish',
            'post_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'date_query' => array(
                array(
                    'year'  => $getdate["year"]
                ),
            )
        );

        $query = new WP_Query( $args );

        if($query->have_posts()) { ?>
            <table>
                <?php while($query->have_posts()) : $query->the_post(); 
                    $terms = get_the_terms(get_the_id(), 'palavras_chave');
                    $processo = $terms;
                    $array = [];
                    foreach($terms as $key => $term) {
                        $array[$key] = $term->term_id;
                    }
                    $palavras_chave = http_build_query(array('palavras_chave' => $array));
                    ?>
                        <tr>
                            <td>
                                Processo: <a href="<?php echo get_the_permalink().'?id='.get_the_id().'&page=processos-curriclos&'.$palavras_chave ?>"><?php echo str_replace('-', '/', get_the_title()); ?></a>
                            </td>
                        </tr>
                    <?php 
            
                endwhile;
                wp_reset_postdata(); ?>
            </table>
        <?php }
    ?>

        
    <?php }
