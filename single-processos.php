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
        
        <?php while ( have_posts() ) :
            the_post(); 
            the_content();
            $acf = get_fields();
        ?>

            <div class="curriculo">
                <div class="curriculo__print">
                    <button onClick="printFunction()">Imprimir</button>
                </div>
				<div class="curriculo__header">
					<div class="curriculo__name-wrapper">
						<h1 class="curriculo__name">Processo: <?php echo esc_html(str_replace('-', '/',get_the_title()));?></h1>
                    </div>
                    <div class="curriculo__endereco">
						<p class="curriculo__endereco-content">Contratante: <?php echo esc_html($acf['contratante'][0]->post_title);?></p>
                    </div>
                    <div class="curriculo__endereco">
                        <p class="curriculo__endereco-content">Cargo:  <?php echo esc_html($acf['cargo']);?> </p>
                    </div>
                    <div class="curriculo__header-wrapper">
						<div class="curriculo__wrapper">
                            <p class="curriculo__header-text">Vagas: <?php echo esc_html($acf['numero_de_vagas']); ?></p>
                            <p class="curriculo__header-text">Idade mínima: <?php echo esc_html($acf['faixa_etaria']['idade_minima']); ?></p>
                            <p class="curriculo__header-text">Idade máxima: <?php echo esc_html($acf['faixa_etaria']['idade_maxima']); ?></p>
                        </div>
                    </div>
                    <div class="curriculo__endereco">
                        <p class="curriculo__endereco-content">Experiência:  <?php echo $acf['com_experiencia'] === 'Não' ? esc_html($acf['com_experiencia']) : $acf['experiencias'] ;?> </p>
                    </div>
                    <div class="curriculo__endereco">
                        <p class="curriculo__endereco-content">Formação:  <?php echo $acf['com_formacao'] === 'Não' ? esc_html($acf['com_formacao']) : $acf['formacao'] ;?> </p>
                    </div>
                </div>
            </div>
        <?php endwhile; // End of the loop.
        wp_reset_postdata();
        ?>
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
               <div class="curriculo">
                    <?php while($query->have_posts()) : $query->the_post(); ?>
                        <div class="curriculo__header-wrapper">
                            <div class="curriculo__wrapper">
                                <p class="curriculo__header-text">Nome: <a href="<?php echo get_permalink();?>" target="blank"><?php echo esc_html(get_post_meta(get_the_ID(), 'nome', true)) ?></a></p>
                                <p class="curriculo__header-text">Telefone: <?php echo esc_html(get_post_meta(get_the_ID(), 'telefone_telefone_1', true)); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </table>
            <?php wp_reset_postdata();
            }
        } ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
