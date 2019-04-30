<?php
/**
 * The template for displaying the single post type curriculo
 *
 * @package balcao-de-empregos
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main test">

		<?php
		while ( have_posts() ) :
			the_post();
			the_content();
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
					<div class="curriculo__header-wrapper">
						<div class="curriculo__wrapper">
							<p class="curriculo__header-text">Nascimento: <?php echo esc_html($birthday->format('d/m/Y')); ?></p>
							<p class="curriculo__header-text">Idade: <?php echo esc_html($interval->y); ?> anos</p>
							<p class="curriculo__header-text">Filhos: <?php echo esc_html($acf['filhos']); ?></p>
						</div>
						<div class="curriculo__wrapper">
							<p class="curriculo__header-text">CPF: <?php echo esc_html($cpf); ?></p>
							<p class="curriculo__header-text">RG: <?php echo esc_html($acf['rg']); ?></p>
							<p class="curriculo__header-text">CTPS: <?php echo esc_html($acf['ctps']); ?></p>
						</div>
						<div class="curriculo__wrapper">
							<p class="curriculo__header-text"><?php echo $acf['telefone']['telefone_2'] !== '' ? 'Telefones: ' . esc_html($acf['telefone']['telefone_1']) . ' e '. esc_html($acf['telefone']['telefone_2']) : 'Telefone: ' . esc_html($acf['telefone']['telefone_1']) ;?></p>
							<p class="curriculo__header-text">Email: <?php echo esc_html($acf['email']); ?></p>
						</div>
					</div>
				</div>
				<div class="curriculo__section">
					<div class="curriculo__section-header">
						<h1 class="curriculo__title">Formação</h1>
					</div>
					<div class="curriculo__section-wrapper">
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
							<div class="curriculo__section-wrapper">
								<p class="curriculo__text">Empresa: <?php echo esc_html($value['experiencia']['empresa']);?></p>
								<p class="curriculo__text">Função: <?php echo esc_html($value['experiencia']['funcao']);?></p>
								<p class="curriculo__text">Periodo: <?php echo esc_html($value['experiencia']['periodo']['inicio']);?> a <?php echo esc_html($value['experiencia']['periodo']['atual'] !== '') ? 'Atual' : esc_html($value['experiencia']['periodo']['termino']);?></p>
								<div class="curriculo__section-exp-desc">
									<p class="curriculo__text">Descrição: </p>
									<p class="curriculo__text"><?php echo esc_html($value['experiencia']['descricao_da_experiencia']);?></p>
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
						<p class="curriculo__text">Cursos: <?php echo esc_html($acf['cursos_complementares']); ?></p>
						<p class="curriculo__text">Interesses: <?php echo esc_html($acf['interesses']); ?></p>
						<p class="curriculo__text">Palavras Chave: <?php echo esc_html($acf['palavras_chave']); ?></p>
					</div>
				</div>
			</div>
			
		<?php endwhile; // End of the loop.
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php


