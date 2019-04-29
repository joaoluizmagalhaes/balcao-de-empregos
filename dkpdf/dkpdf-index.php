<html>
    <head>
    	<link type="text/css" rel="stylesheet" href="<?php echo get_bloginfo( 'stylesheet_url' ); ?>" media="all" />
    	<?php 
    		$wp_head = get_option( 'dkpdf_print_wp_head', '' );
    		if( $wp_head == 'on' ) {
    			wp_head();
    		} 
    	?>
      	<style type="text/css">
      		body {
      			background:#FFF;
      			font-size: 100%;
      		}	
        </style>
        <style>
            <?php 
				$css = get_option( 'dkpdf_pdf_custom_css', '' );
				echo $css; 
			?>		
        </style>
   	</head>

    <body>
	    <?php
            global $post;
            $pdf  = get_query_var( 'pdf' );
            $post_type = get_post_type( $pdf );
            $args = array(
                'p' => $pdf,
                'post_type' => $post_type,
                'post_status' => 'publish'
            );               
            $the_query = new WP_Query( apply_filters( 'dkpdf_query_args', $args ) );              
                if ( $the_query->have_posts() ) {             
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        global $post; 

                        $acf = get_fields();
                        if($acf['data_de_nascimento']) {
                            $birthday = new DateTime($acf['data_de_nascimento']);
                            $interval = $birthday->diff(new DateTime);
                        }
                        $endereco = $acf['endereco'];
                        $cpf = mask($acf['cpf'], '###.###.###-##');
                        ?>
                        <div class="curriculo">
                            <div class="curriculo__header">
                                <h1 class="curriculo__name"><?php echo esc_html($acf['nome']);?></h1>
                                <p class="curriculo__header-text">Data de Nascimento: <?php echo esc_html($birthday->format('d/m/Y')); ?></p>
                                <p class="curriculo__header-text">Idade: <?php echo esc_html($interval->y); ?> anos</p>
                                <p class="curriculo__header-text">Email: <?php echo esc_html($acf['email']); ?></p>
                                <p class="curriculo__header-text">CPF: <?php echo esc_html($cpf); ?></p>
                                <p class="curriculo__header-text">RG: <?php echo esc_html($acf['rg']); ?></p>
                                <p class="curriculo__header-text">CTPS: <?php echo esc_html($acf['ctps']); ?></p>
                                <p class="curriculo__header-text">Filhos: <?php echo esc_html($acf['filhos']); ?></p>
                                <p class="curriculo__header-text"><?php echo $acf['telefone']['telefone_2'] !== '' ? 'Telefones: ' . esc_html($acf['telefone']['telefone_1']) . ' e '. esc_html($acf['telefone']['telefone_2']) : 'Telefone: ' . esc_html($acf['telefone']['telefone_1']) ;?></p>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content">Endereço: <?php echo esc_html($endereco['rua']) . ', ' . esc_html($endereco['numero']) . ' - ' . esc_html($endereco['bairro']) . ' - ' . esc_html($endereco['cidade']) . ', ' . esc_html($endereco['estado']) . ' - CEP ' . esc_html($endereco['cep']);?></p>
                                </div>
                            </div>
                            <div class="curriculo__experience">
                                <h1 class="curriculo__title">Experiências</h1>
                                <?php if($acf['experiencias']) { 
                                    foreach($acf['experiencias'] as $value) { ?>
                                        <div class="curriculo__experience">
                                            <p class="curriculo__text">Empresa: <?php echo esc_html($value['experiencia']['empresa']);?></p>
                                            <p class="curriculo__text">Função: <?php echo esc_html($value['experiencia']['funcao']);?></p>
                                            <p class="curriculo__text">Periodo: <?php echo esc_html($value['experiencia']['periodo']['inicio']);?> / <?php echo esc_html($value['experiencia']['periodo']['atual'] !== '') ? 'Atual' : esc_html($value['experiencia']['periodo']['termino']);?></p>
                                            <p class="curriculo__text">Descrição: <?php echo esc_html($value['experiencia']['descricao_da_experiencia']);?></p>
                                    <?php }
                                } ?>
                            </div>
                            <div class="curriculo__degree">
                                <h1 class="curriculo__title">Formação</h1>
                                <?php
                                    if($acf['formacao'] !== 'SUPERIOR COMPLETO') { ?>
                                        <p class="curriculo__text">Formação: <?php echo esc_html($acf['formacao']); ?></p>
                                    <?php } else { ?>
                                        <p class="curriculo__text">Formação: <?php echo esc_html($acf['formacao']); ?></p>
                                        <p class="curriculo__text">Instituição: <?php echo esc_html($acf['formacao_superior']['instituicao']); ?></p>
                                        <p class="curriculo__text">Curso: <?php echo esc_html($acf['formacao_superior']['curso']); ?></p>
                                        <p class="curriculo__text">Conclusão: <?php echo esc_html($acf['formacao_superior']['ano_de_conclusao']); ?></p>
                                    <?php }
                                ?>
                            </div>
                            <div class="curriculo__extras">
                                <h1 class="curriculo__title">Informações Complementares</h1>
                                <p class="curriculo__text">Cursos: <?php echo esc_html($acf['cursos_complementares']); ?></p>
                                <p class="curriculo__text">Interesses: <?php echo esc_html($acf['interesses']); ?></p>
                                <p class="curriculo__text">Palavras Chave: <?php echo esc_html($acf['palavras_chave']); ?></p>
                            </div>
                        </div>
                    <?php }
                }    
            wp_reset_postdata();
        ?>
    </body>
</html>