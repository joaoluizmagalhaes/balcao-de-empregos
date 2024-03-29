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
            if($the_query->query['post_type'] === 'curriculo') {            
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
                                <div class="curriculo__name-wrapper">
                                    <h1 class="curriculo__name"><?php echo esc_html($acf['nome']);?></h1>
                                </div>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content">Endereço: <?php echo esc_html($endereco['rua']) . ', ' . esc_html($endereco['numero']) . ' - ' . esc_html($endereco['bairro']) . ' - ' . esc_html($endereco['cidade']) . ', ' . esc_html($endereco['estado']) . ' - CEP ' . esc_html($endereco['cep']);?></p>
                                </div>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content">Nascimento: <?php echo esc_html($birthday->format('d/m/Y')); ?> | Idade: <?php echo esc_html($interval->y); ?> anos | Filhos: <?php echo esc_html($acf['filhos']); ?></p>
                                </div>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content">CPF: <?php echo esc_html($cpf); ?> | RG: <?php echo esc_html($acf['rg']); ?> | CTPS: <?php echo esc_html($acf['ctps']); ?></p>
                                </div>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content"><?php echo $acf['telefone']['telefone_2'] !== '' ? 'Telefones: ' . esc_html($acf['telefone']['telefone_1']) . ' e '. esc_html($acf['telefone']['telefone_2']) : 'Telefone: ' . esc_html($acf['telefone']['telefone_1']) ;?> | Email: <?php echo esc_html($acf['email']); ?></p>
                                </div>
                                <div class="curriculo__endereco">
                                    <p class="curriculo__endereco-content">Estado Civil: <?php echo esc_html($acf['estado_civil']); ?> | Habilitação: <?php echo esc_html($acf['categoria_cnh']); ?></p>
                                </div>
                            </div>
                            <div class="curriculo__section">
                                <div class="curriculo__section-header">
                                    <h1 class="curriculo__title">Formação</h1>
                                </div>
                                <div class="curriculo__section-wrapper pdf">

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
                            </div>
                            <div class="curriculo__section">
                                <div class="curriculo__section-header">
                                    <h1 class="curriculo__title">Experiências</h1>
                                </div>
                                <?php if($acf['experiencias']) { 
                                    foreach($acf['experiencias'] as $value) { ?>
                                        <div class="curriculo__section-wrapper pdf">
                                            <p class="curriculo__text">Empresa: <?php echo esc_html($value['experiencia']['empresa']);?></p>
                                            <p class="curriculo__text">Função: <?php echo esc_html($value['experiencia']['funcao']);?></p>
                                            <p class="curriculo__text">Periodo: <?php echo esc_html($value['experiencia']['periodo']['inicio']);?> a <?php echo esc_html($value['experiencia']['periodo']['atual'] !== '') ? 'Atual' : esc_html($value['experiencia']['periodo']['termino']);?></p>
                                            <div class="curriculo__section-exp-desc desc-pdf">
                                                <p class="curriculo__text">Descrição: </p>
                                                <p class="curriculo__text desc-pdf"><?php echo esc_html($value['experiencia']['descricao_da_experiencia']);?></p>
                                            </div>
                                        </div>
                                    <?php }
                                } ?>
                            </div>
                            <div class="curriculo__section">
                                <div class="curriculo__section-header">
                                    <h1 class="curriculo__title">Informações Complementares</h1>
                                </div>
                                <div class="curriculo__section-wrapper">
                                    <p class="curriculo__text"><?php echo $acf['cursos_complementares'] !== '' ? 'Cursos: ' . esc_html($acf['cursos_complementares']) : '' ; ?></p>
                                </div>
                                <div class="curriculo__section-wrapper">	
                                    <p class="curriculo__text"><?php echo $acf['interesses'] !== '' ? 'Interesses: ' . esc_html($acf['interesses']) : ''; ?></p>
                                </div>
                            </div>
                        </div> 
                    <?php }
                }
                wp_reset_postdata();
            } else {
                if ( $the_query->have_posts() ) {             
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        global $post; 

                        $acf = get_fields(); ?>

                        <div class="processos" style="border: 1px solid #000000; padding: 15px">
                            <div class="processos__header">
                                <div class="processos__header-wrapper">
                                    <h1 class="processos__header-name processo" style="font-size: 20px;">Processo: <?php echo esc_html(str_replace('-', '/',get_the_title()));?></h1>
                                </div>
                                <div class="processos__contratante">
                                    <p class="processos__contratante-content">Contratante: <?php echo esc_html($acf['contratante'][0]->post_title);?> | Cargo:  <?php echo esc_html($acf['cargo']);?>  | Vagas: <?php echo esc_html($acf['numero_de_vagas']); ?></p>
                                </div>
                                <div class="processos__contratante">       
                                    <p class="processos__contratante-content">Idade mínima: <?php echo esc_html($acf['faixa_etaria']['idade_minima']); ?> | Idade máxima: <?php echo esc_html($acf['faixa_etaria']['idade_maxima']); ?> | Experiência: <?php echo $acf['com_experiencia'] === 'Não' ? esc_html($acf['com_experiencia']) : $acf['experiencias'] ;?>  | Formação:  <?php echo $acf['com_formacao'] === 'Não' ? esc_html($acf['com_formacao']) : $acf['formacao'] ;?> </p>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                wp_reset_postdata();

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
                        
                        <table style="border: 1px solid #000000; padding: 15px">
                            <tr>
                                <th>Nome</th>
                                <th>Contato</th>
                            </tr>
                            <?php while($query->have_posts()) : $query->the_post(); ?>
                                <tr>
                                    <td align="center">
                                        <p class="processos__header-text">
                                            <a href="<?php echo get_permalink();?>" target="blank">
                                                <?php echo esc_html(get_post_meta(get_the_ID(), 'nome', true)) ?>
                                            </a>
                                        </p>
                                    </td>
                                    <td align="center">
                                        <p class="processos__header-text">
                                            <?php echo esc_html(get_post_meta(get_the_ID(), 'telefone_telefone_1', true)); ?>
                                        </p>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </table>
                    <?php }
                    wp_reset_postdata();
                }
            } 
        ?>
    </body>
</html>