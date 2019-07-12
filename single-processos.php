<?php
/**
 * The template for displaying the single post type curriculo
 *
 * @package balcao-de-empregos
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main processos">
        
            <?php while ( have_posts() ) :the_post(); 
                the_content();
                $acf = get_fields(); ?>

                <div class="processos">
                    <div class="processos__print">
                        <button onClick="printFunction()">Imprimir</button>
                        <button class="mailFunction">Enviar Email</button>
                    </div>
                    <div class="processos__header">
                        <div class="processos__header-wrapper">
                            <h1 class="processos__header-name processo">Processo: <?php echo esc_html(str_replace('-', '/',get_the_title()));?></h1>
                        </div>
                        <div class="processos__contratante">
                            <p class="processos__contratante-content">Contratante: <?php echo esc_html($acf['contratante'][0]->post_title);?></p>
                            <p class="processos__contratante-content">Cargo:  <?php echo esc_html($acf['cargo']);?> </p>
                            <p class="processos__contratante-content">Vagas: <?php echo esc_html($acf['numero_de_vagas']); ?></p>
                            <input type="hidden" name="contratante" class="contratante" value="<?php echo esc_html($acf['contratante'][0]->ID); ?>">
                        </div>
                        <div class="processos__contratante">       
                            <p class="processos__contratante-content">Idade mínima: <?php echo esc_html($acf['faixa_etaria']['idade_minima']); ?></p>
                            <p class="processos__contratante-content">Idade máxima: <?php echo esc_html($acf['faixa_etaria']['idade_maxima']); ?></p>
                            <p class="processos__contratante-content">Experiência:  <?php echo $acf['com_experiencia'] === 'Não' ? esc_html($acf['com_experiencia']) : $acf['experiencias'] ;?> </p>
                            <p class="processos__contratante-content">Formação:  <?php echo $acf['com_formacao'] === 'Não' ? esc_html($acf['com_formacao']) : $acf['formacao'] ;?> </p>
                        </div>
                    </div>
                </div>
            <?php endwhile; // End of the loop.
            wp_reset_postdata(); ?>
            <?php if( $_GET['palavras_chave']) {
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
                if($query->have_posts()) { 
                    ?>
                    <div class="processos">
                        <table class="processos__table" cellspacing="0" cellpadding="0">
                            <tr border="1px">
                                <th>Nome</th>
                                <th>Contato</th>
                            </tr>
                            <?php while($query->have_posts()) : $query->the_post(); ?>
                                <tr>
                                    <td>
                                        <p class="processos__header-text">
                                            <a href="<?php echo get_permalink();?>" target="blank">
                                                <?php echo esc_html(get_post_meta(get_the_ID(), 'nome', true)) ?>
                                            </a>
                                        </p>
                                    </td>
                                    <td>
                                        <p class="processos__header-text">
                                            <?php echo esc_html(get_post_meta(get_the_ID(), 'telefone_telefone_1', true)); ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    </div>
                <?php wp_reset_postdata();
                }
            } ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
