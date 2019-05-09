<?php 

    add_action( 'admin_menu', 'processos_curriculos_list' );

    function processos_curriculos_list() {
        add_menu_page( 
            'Lista de Currículos', 
            'Lista de Currículos', 
            'manage_options', 
            'processos-curriclos', 
            'curriculos_list_page',
            'dashicons-media-text', 8  );
    }

    function curriculos_list_page() {

        if( $_GET['palavras_chave']) {
            $args2 = array(
                'post_type' => 'curriculo',
                'tax_query' => array( 
                                array( 'taxonomy' => 'palavras_chave', 
                                        'field'=> 'term_id',
                                        'terms' => $_GET['palavras_chave']
                                    )
                                )
                );
            $query = new WP_Query($args2); 
            if($query->have_posts()) { ?>
                <table>
                    <?php while($query->have_posts()) : $query->the_post(); ?>
                        <?php $acf = get_fields();?>
                        <tr>
                            <td>
                                <a href="<?php echo the_permalink();?>" target="blank"><?php the_title(); ?></a>
                            </td>
                            <td>
                                <p>Telefone: <?php echo $acf['telefone']['telefone_1']?></p>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            <?php wp_reset_postdata();
            }
        } else {
            $args = array(
                'post_type' => 'processos',
                'post_status' => 'publish',
                'post_per_page' => -1
            );

            $query = new WP_Query( $args );

            if($query->have_posts()) { ?>
                <table>
                    <?php while($query->have_posts()) : $query->the_post(); 
                        $terms = get_the_terms(get_the_id(), 'palavras_chave');
                        
                        $array = [];
                        foreach($terms as $key => $term) {
                            $array[$key] = $term->term_id;
                        }
                        $palavras_chave = http_build_query(array('palavras_chave' => $array));
                        ?>
                            <tr>
                                <td>
                                    Processo: <a href="<?php echo admin_url() . 'admin.php?page=processos-curriclos&'.$palavras_chave ?>"><?php the_title(); ?></a>
                                </td>
                            </tr>
                        <?php 
                
                    endwhile;
                    wp_reset_postdata(); ?>
                </table>
            <?php }
        }
    ?>

        
    <?php }
