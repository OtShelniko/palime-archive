<?php
/**
 * Palime Archive — template-parts/sections/section-page.php
 * Универсальная вёрстка страниц разделов (Кино / Литература / Музыка / ИЗО).
 *
 * Ожидает массив $args (передаётся в get_template_part):
 *   section_slug    string  slug таксономии section: cinema | lit | music | art
 *   section_name    string  человекочитаемое имя раздела
 *   section_slogan  string  слоган под заголовком
 *   status_line     string  моно-строка статуса в шапке
 *   bg_color        string  HEX фона героя
 *   accent_color    string  HEX акцента
 *   section_about   string  HTML/текст блока «О разделе» (вывод через wp_kses_post)
 *   rating_authors  string  подпись колонки рейтинга авторов
 *   rating_works    string  подпись колонки рейтинга произведений
 *   monthly_cats           array   [ meta_field_suffix => label ] для полей ACF monthly_{suffix}
 *   hero_image_url         string  опционально — URL фонового изображения колонки героя (иначе — обложка последней статьи раздела)
 *   hero_button_text_color string  опционально — цвет текста у primary-кнопки в герое (если пусто — #fff; для светлого accent задайте тёмный цвет)
 *
 * Routing spec v1.1: статьи CPT article → URL %section%/%postname%/ (см. inc/setup.php).
 * Таксономия section зарегистрирована для quote_of_day (фильтр по tax_query).
 * Для monthly_best используется meta_key palime_section.
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = isset( $args ) && is_array( $args ) ? $args : [];

$defaults = [
	'section_slug'    => '',
	'section_name'    => '',
	'section_slogan'  => '',
	'status_line'     => '',
	'bg_color'        => '#0A0A0A',
	'accent_color'    => '#D91515',
	'section_about'   => '',
	'rating_authors'  => __( 'Лучшие авторы', 'palime-archive' ),
	'rating_works'    => __( 'Лучшие произведения', 'palime-archive' ),
	'monthly_cats'           => [],
	'hero_image_url'         => '',
	'hero_button_text_color' => '',
];

$cfg = wp_parse_args( $args, $defaults );

$section_slug   = sanitize_title( $cfg['section_slug'] );
$section_name   = $cfg['section_name'];
$section_slogan = $cfg['section_slogan'];
$status_line    = $cfg['status_line'];
$bg_color       = $cfg['bg_color'];
$accent_color   = $cfg['accent_color'];
$section_about  = $cfg['section_about'];
$rating_authors = $cfg['rating_authors'];
$rating_works   = $cfg['rating_works'];
$monthly_cats   = is_array( $cfg['monthly_cats'] ) ? $cfg['monthly_cats'] : [];
$hero_image_url         = $cfg['hero_image_url'];
$hero_button_text_color = isset( $cfg['hero_button_text_color'] ) && is_string( $cfg['hero_button_text_color'] ) ? $cfg['hero_button_text_color'] : '';
$hero_btn_fg            = $hero_button_text_color !== '' ? $hero_button_text_color : '#ffffff';
$hero_has_media         = false;

$tax_query_section = [];
if ( $section_slug ) {
	$tax_query_section = [
		[
			'taxonomy' => 'section',
			'field'    => 'slug',
			'terms'    => $section_slug,
		],
	];
}

// CPT monthly_best не привязан к таксономии section — фильтр по meta palime_section.
if ( $section_slug ) {
	$meta_section = [
		[
			'key'     => 'palime_section',
			'value'   => $section_slug,
			'compare' => '=',
		],
	];
} else {
	$meta_section = [
		[
			'key'     => 'palime_section',
			'value'   => '__palime_no_section__',
			'compare' => '=',
		],
	];
}

$archive_url  = $section_slug ? home_url( '/archive/?section=' . rawurlencode( $section_slug ) ) : home_url( '/archive/' );
$news_url     = $section_slug ? home_url( '/news/?section=' . rawurlencode( $section_slug ) ) : home_url( '/news/' );
$rankings_url = function_exists( 'palime_get_rankings_archive_url' )
	? palime_get_rankings_archive_url( $section_slug )
	: ( $section_slug ? home_url( '/rankings/?section=' . rawurlencode( $section_slug ) ) : home_url( '/rankings/' ) );

// --- Hero: фоновое изображение справа (последняя статья раздела или заглушка) ---
$hero_thumb_url = is_string( $hero_image_url ) ? esc_url_raw( $hero_image_url ) : '';
if ( ! $hero_thumb_url && $section_slug ) {
	$hero_q = new WP_Query(
		array_merge(
			[
				'post_type'              => 'article',
				'posts_per_page'         => 1,
				'post_status'            => 'publish',
				'orderby'                => 'date',
				'order'                  => 'DESC',
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => true,
			],
			! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
		)
	);
	if ( $hero_q->have_posts() ) {
		$hero_q->the_post();
		$tid = get_post_thumbnail_id();
		if ( $tid ) {
			$hero_thumb_url = esc_url_raw( wp_get_attachment_image_url( $tid, 'card-lg' ) );
		}
		wp_reset_postdata();
	}
}

$hero_has_media = (bool) $hero_thumb_url;

?>

<!-- 1. Заставка -->
<section
	class="section-page__hero grid grid--2<?php echo $section_slug ? ' section-page--' . esc_attr( $section_slug ) : ''; ?>"
	style="<?php echo esc_attr( 'background:' . $bg_color . ';color:#fff;position:relative;overflow:hidden;min-height:min(70vh,920px);align-items:stretch;' ); ?>"
	aria-labelledby="section-page-title-<?php echo esc_attr( $section_slug ? $section_slug : 'default' ); ?>">

	<div class="section-page__hero-text" style="position:relative;z-index:1;padding:var(--spacing-2xl) var(--gutter);display:flex;flex-direction:column;justify-content:center;">
		<?php if ( $status_line ) : ?>
			<p class="section-page__hero-meta text-mono text-xs mb-lg" style="opacity:.45;letter-spacing:.18em;text-transform:uppercase;">
				<?php echo esc_html( $status_line ); ?>
			</p>
		<?php endif; ?>

		<h1 id="section-page-title-<?php echo esc_attr( $section_slug ? $section_slug : 'default' ); ?>"
			class="section-page__hero-title text-display"
			style="font-family:var(--font-display);font-size:clamp(2.5rem,7vw,5rem);line-height:1;color:<?php echo esc_attr( $accent_color ); ?>;margin-bottom:var(--spacing-md);">
			<?php echo esc_html( strtoupper( $section_name ) ); ?>
		</h1>

		<p class="section-page__hero-slogan mb-xl" style="font-family:var(--font-serif);font-size:clamp(1rem,2vw,1.35rem);opacity:.78;max-width:36rem;line-height:1.55;">
			<?php echo esc_html( $section_slogan ); ?>
		</p>

		<div class="section-page__hero-actions flex flex--gap flex--wrap mb-lg">
			<a href="<?php echo esc_url( $archive_url ); ?>"
				class="btn btn--primary"
				style="<?php echo esc_attr( 'background:' . $accent_color . ';border-color:' . $accent_color . ';color:' . $hero_btn_fg ); ?>;">
				<?php esc_html_e( 'Открыть каталог', 'palime-archive' ); ?>
			</a>
			<a href="<?php echo esc_url( $rankings_url ); ?>"
				class="btn btn--outline"
				style="color:#fff;border-color:rgba(255,255,255,.38);">
				<?php esc_html_e( 'Смотреть рейтинги', 'palime-archive' ); ?>
			</a>
		</div>
	</div>

	<div class="section-page__hero-visual<?php echo $hero_has_media ? ' section-page__hero-visual--media' : ' section-page__hero-visual--empty'; ?> hide-mobile" style="position:relative;min-height:280px;">
		<div class="section-page__hero-visual-inner">
		<?php if ( $hero_thumb_url ) : ?>
			<div class="section-page__hero-img" style="height:100%;min-height:320px;background-image:url(<?php echo esc_url( $hero_thumb_url ); ?>);background-size:cover;background-position:center;border-left:1px solid rgba(255,255,255,.08);"></div>
		<?php else : ?>
			<div class="section-page__hero-placeholder flex" style="height:100%;min-height:320px;align-items:center;justify-content:center;flex-direction:column;gap:var(--spacing-md);border-left:1px solid rgba(255,255,255,.08);background:rgba(0,0,0,.25);">
				<span class="section-page__hero-placeholder-meta text-mono text-xs" style="opacity:.35;letter-spacing:.2em;text-transform:uppercase;">
					<?php esc_html_e( 'Hero · изображение раздела', 'palime-archive' ); ?>
				</span>
				<span class="section-page__hero-placeholder-mark" style="font-size:2rem;opacity:.4;" aria-hidden="true">◼</span>
				<span class="section-page__hero-placeholder-name text-display" aria-hidden="true">
					<?php echo esc_html( strtoupper( $section_name ) ); ?>
				</span>
			</div>
		<?php endif; ?>
		</div>
	</div>
</section>


<!-- 2. Свежие материалы -->
<section class="section section-page__fresh" id="fresh">
	<div class="container">
		<div class="flex flex--between mb-xl flex--wrap" style="gap:var(--spacing-md);">
			<div>
				<span class="section-page__eyebrow text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— <?php esc_html_e( 'Свежие материалы', 'palime-archive' ); ?> —</span>
				<h2 class="section-page__heading mt-sm" style="font-family:var(--font-display);font-size:clamp(1.35rem,3vw,1.85rem);">
					<?php esc_html_e( 'Последние статьи', 'palime-archive' ); ?>
				</h2>
			</div>
			<a href="<?php echo esc_url( $archive_url ); ?>" class="btn btn--outline btn--sm hide-mobile">
				<?php esc_html_e( 'Весь архив →', 'palime-archive' ); ?>
			</a>
		</div>

		<?php
		$fresh_query = new WP_Query(
			array_merge(
				[
					'post_type'      => 'article',
					'posts_per_page' => 6,
					'orderby'        => 'date',
					'order'          => 'DESC',
				],
				! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
			)
		);
		?>

		<?php if ( $fresh_query->have_posts() ) : ?>
			<div class="grid grid--cards section-page__fresh-grid<?php echo 1 === (int) $fresh_query->post_count ? ' section-page__fresh-grid--single' : ''; ?>">
				<?php
				while ( $fresh_query->have_posts() ) :
					$fresh_query->the_post();
					get_template_part( 'template-parts/cards/card', 'article' );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="grid grid--cards section-page__stub-cards" style="opacity:.35;">
				<?php for ( $s = 0; $s < 3; $s++ ) : ?>
					<article class="card section-page__stub-card" style="padding:var(--spacing-lg);border:1px dashed rgba(0,0,0,.2);">
						<p class="section-page__stub-index text-mono text-xs mb-sm"><?php echo esc_html( sprintf( '%02d', $s + 1 ) ); ?></p>
						<h3 class="section-page__stub-title text-serif" style="font-size:1rem;">—</h3>
						<p class="section-page__stub-copy text-mono text-xs text-muted mt-md"><?php esc_html_e( 'Материалы появятся здесь', 'palime-archive' ); ?></p>
					</article>
				<?php endfor; ?>
			</div>
			<p class="text-muted text-mono text-xs mt-md section-page__stub-caption" style="letter-spacing:.08em;">
				<?php esc_html_e( '— Материалы появятся здесь —', 'palime-archive' ); ?>
			</p>
		<?php endif; ?>

		<div class="mt-xl show-mobile">
			<a href="<?php echo esc_url( $archive_url ); ?>" class="btn btn--outline">
				<?php esc_html_e( 'Весь архив →', 'palime-archive' ); ?>
			</a>
		</div>
	</div>
</section>


<!-- 3. Рейтинги -->
<section class="section section-page__ratings" id="ratings" style="background:var(--color-second);">
	<div class="container">
		<div class="mb-xl">
			<span class="section-page__eyebrow text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— <?php esc_html_e( 'Рейтинги', 'palime-archive' ); ?> —</span>
			<h2 class="section-page__heading mt-sm" style="font-family:var(--font-display);font-size:clamp(1.35rem,3vw,1.85rem);">
				<?php echo esc_html( $section_name ); ?> · <?php esc_html_e( 'Топ', 'palime-archive' ); ?>
			</h2>
			<p class="section-page__section-note text-mono text-xs text-muted mt-xs"><?php esc_html_e( 'Некоторые записи остаются спорными', 'palime-archive' ); ?></p>
		</div>

		<div class="grid grid--2">
			<div class="section-page__ratings-column">
				<h3 class="section-page__column-title text-mono text-xs text-upper mb-lg" style="letter-spacing:.12em;color:var(--accent);">
					<?php echo esc_html( $rating_authors ); ?>
				</h3>
				<?php
				$authors_ranking = new WP_Query(
					array_merge(
						[
							'post_type'      => 'ranking',
							'posts_per_page' => 1,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'meta_query'     => [
								[
									'key'     => 'ranking_category',
									'value'   => 'authors',
									'compare' => '=',
								],
							],
						],
						! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
					)
				);
				if ( $authors_ranking->have_posts() ) :
					while ( $authors_ranking->have_posts() ) :
						$authors_ranking->the_post();
						$items = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
						if ( $items ) :
							?>
							<ol class="section-page__ranking-list" style="list-style:none;display:flex;flex-direction:column;gap:var(--spacing-sm);">
								<?php foreach ( $items as $i => $item ) : ?>
									<li class="section-page__ranking-item flex flex--gap" style="padding:var(--spacing-sm) 0;border-bottom:1px solid rgba(0,0,0,.06);">
										<span class="section-page__ranking-index text-mono" style="color:var(--accent);min-width:1.5rem;font-size:.8rem;"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
										<span class="section-page__ranking-name text-serif">
											<?php echo esc_html( is_array( $item ) ? ( $item['name'] ?? $item[0] ?? '' ) : $item ); ?>
										</span>
									</li>
								<?php endforeach; ?>
							</ol>
							<?php
						else :
							?>
							<p class="text-muted text-mono text-xs section-page__stub-caption"><?php esc_html_e( '— Рейтинг формируется —', 'palime-archive' ); ?></p>
							<?php
						endif;
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<ul class="section-page__stub-ranking text-mono text-xs" style="opacity:.3;list-style:none;">
						<?php for ( $r = 1; $r <= 5; $r++ ) : ?>
							<li class="section-page__stub-ranking-item" style="padding:.5rem 0;border-bottom:1px solid rgba(0,0,0,.06);">—</li>
						<?php endfor; ?>
					</ul>
					<p class="text-muted text-mono text-xs mt-sm section-page__stub-caption"><?php esc_html_e( '— Рейтинг формируется —', 'palime-archive' ); ?></p>
					<?php
				endif;
				?>
			</div>

			<div class="section-page__ratings-column">
				<h3 class="section-page__column-title text-mono text-xs text-upper mb-lg" style="letter-spacing:.12em;color:var(--accent);">
					<?php echo esc_html( $rating_works ); ?>
				</h3>
				<?php
				$works_ranking = new WP_Query(
					array_merge(
						[
							'post_type'      => 'ranking',
							'posts_per_page' => 1,
							'orderby'        => 'date',
							'order'          => 'DESC',
							'meta_query'     => [
								[
									'key'     => 'ranking_category',
									'value'   => 'works',
									'compare' => '=',
								],
							],
						],
						! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
					)
				);
				if ( $works_ranking->have_posts() ) :
					while ( $works_ranking->have_posts() ) :
						$works_ranking->the_post();
						$items = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
						if ( $items ) :
							?>
							<ol class="section-page__ranking-list" style="list-style:none;display:flex;flex-direction:column;gap:var(--spacing-sm);">
								<?php foreach ( $items as $i => $item ) : ?>
									<li class="section-page__ranking-item flex flex--gap" style="padding:var(--spacing-sm) 0;border-bottom:1px solid rgba(0,0,0,.06);">
										<span class="section-page__ranking-index text-mono" style="color:var(--accent);min-width:1.5rem;font-size:.8rem;"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
										<span class="section-page__ranking-name text-serif">
											<?php echo esc_html( is_array( $item ) ? ( $item['name'] ?? $item[0] ?? '' ) : $item ); ?>
										</span>
									</li>
								<?php endforeach; ?>
							</ol>
							<?php
						else :
							?>
							<p class="text-muted text-mono text-xs section-page__stub-caption"><?php esc_html_e( '— Рейтинг формируется —', 'palime-archive' ); ?></p>
							<?php
						endif;
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<ul class="section-page__stub-ranking text-mono text-xs" style="opacity:.3;list-style:none;">
						<?php for ( $r = 1; $r <= 5; $r++ ) : ?>
							<li class="section-page__stub-ranking-item" style="padding:.5rem 0;border-bottom:1px solid rgba(0,0,0,.06);">—</li>
						<?php endfor; ?>
					</ul>
					<p class="text-muted text-mono text-xs mt-sm section-page__stub-caption"><?php esc_html_e( '— Рейтинг формируется —', 'palime-archive' ); ?></p>
					<?php
				endif;
				?>
			</div>
		</div>
	</div>
</section>


<!-- 4. Новости -->
<section class="section section-page__news">
	<div class="container">
		<div class="flex flex--between mb-xl flex--wrap" style="gap:var(--spacing-md);">
			<div>
				<span class="section-page__eyebrow text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— <?php esc_html_e( 'Новости', 'palime-archive' ); ?> —</span>
				<h2 class="section-page__heading mt-sm" style="font-family:var(--font-display);font-size:clamp(1.35rem,3vw,1.85rem);">
					<?php echo esc_html( $section_name ); ?> · <?php esc_html_e( 'Лента', 'palime-archive' ); ?>
				</h2>
			</div>
			<a href="<?php echo esc_url( $news_url ); ?>" class="btn btn--outline btn--sm hide-mobile">
				<?php esc_html_e( 'Все новости →', 'palime-archive' ); ?>
			</a>
		</div>

		<?php
		$news_query = new WP_Query(
			array_merge(
				[
					'post_type'      => 'news',
					'posts_per_page' => 5,
					'orderby'        => 'date',
					'order'          => 'DESC',
				],
				! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
			)
		);
		?>

		<?php if ( $news_query->have_posts() ) : ?>
			<ul class="section-page__news-list" style="list-style:none;">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					get_template_part( 'template-parts/cards/card', 'news' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php else : ?>
			<p class="text-muted text-mono text-xs section-page__stub-news" style="letter-spacing:.08em;opacity:.45;">
				<?php esc_html_e( '— Новости появятся здесь —', 'palime-archive' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>


<!-- 5. Цитата дня (таксономия section) -->
<?php
$today     = current_time( 'Y-m-d' );
$quote_tax = ! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : [];
$quote_args = array_merge( [
	'post_type'      => 'quote_of_day',
	'posts_per_page' => 1,
	'date_query'     => [
		[
			'year'  => (int) gmdate( 'Y', strtotime( $today ) ),
			'month' => (int) gmdate( 'm', strtotime( $today ) ),
			'day'   => (int) gmdate( 'd', strtotime( $today ) ),
		],
	],
], $quote_tax );
$quote_query = new WP_Query( $quote_args );
if ( ! $quote_query->have_posts() ) {
	$quote_query = new WP_Query(
		array_merge( [
			'post_type'      => 'quote_of_day',
			'posts_per_page' => 1,
			'orderby'        => 'date',
			'order'          => 'DESC',
		], $quote_tax )
	);
}
?>

<section class="section section-page__quote" style="<?php echo esc_attr( 'background:' . $bg_color . ';color:#fff;' ); ?>">
	<div class="container text-center">
		<?php if ( $quote_query->have_posts() ) : ?>
			<?php
			$quote_query->the_post();
			$q_id     = get_the_ID();
			$q_text   = function_exists( 'get_field' ) ? get_field( 'quote_text', $q_id ) : '';
			$q_author = function_exists( 'get_field' ) ? get_field( 'quote_author', $q_id ) : '';
			$q_work   = function_exists( 'get_field' ) ? get_field( 'quote_work', $q_id ) : '';
			$q_link   = function_exists( 'get_field' ) ? get_field( 'quote_link', $q_id ) : '';
			if ( ! $q_text ) {
				$q_text = get_the_title();
			}
			wp_reset_postdata();
			?>

			<p class="section-page__quote-eyebrow text-mono text-xs mb-lg" style="opacity:.4;letter-spacing:.2em;text-transform:uppercase;">
				<?php esc_html_e( '— Цитата дня —', 'palime-archive' ); ?>
			</p>

			<blockquote class="section-page__quote-text text-serif" style="font-size:clamp(1.15rem,2.5vw,1.85rem);line-height:1.55;max-width:48rem;margin:0 auto;font-style:italic;">
				&laquo;<?php echo esc_html( $q_text ); ?>&raquo;
			</blockquote>

			<?php if ( $q_author || $q_work ) : ?>
				<div class="section-page__quote-meta mt-lg text-mono text-xs" style="opacity:.55;letter-spacing:.08em;">
					<?php if ( $q_author ) : ?>
						<p class="section-page__quote-meta-line" style="margin:0 0 .25em;">Автор: <?php echo esc_html( $q_author ); ?></p>
					<?php endif; ?>
					<?php if ( $q_work ) : ?>
						<p class="section-page__quote-meta-line" style="margin:0;">Из произведения: &laquo;<?php echo esc_html( $q_work ); ?>&raquo;</p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php
			$q_link_url = '';
			if ( $q_link ) {
				$q_link_url = is_numeric( $q_link ) ? get_permalink( (int) $q_link ) : $q_link;
			}
			?>
			<?php if ( $q_link_url ) : ?>
				<div class="mt-lg">
					<a href="<?php echo esc_url( $q_link_url ); ?>" class="btn btn--outline" style="color:#fff;border-color:rgba(255,255,255,.3);">
						<?php esc_html_e( 'Открыть дело →', 'palime-archive' ); ?>
					</a>
				</div>
			<?php endif; ?>

		<?php else : ?>
			<p class="text-mono text-xs section-page__stub-quote" style="opacity:.4;letter-spacing:.12em;">
				<?php esc_html_e( '— Цитата дня · блок зарезервирован (таксономия section + ACF) —', 'palime-archive' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>


<!-- 6. Лучшее за месяц -->
<?php
$monthly_query = new WP_Query(
	[
		'post_type'      => 'monthly_best',
		'posts_per_page' => 1,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'meta_query'     => $meta_section,
	]
);
?>

<section class="section section-page__monthly section--accent">
	<div class="container">
		<div class="mb-xl">
			<span class="section-page__eyebrow text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— <?php esc_html_e( 'Итог', 'palime-archive' ); ?> —</span>
			<h2 class="section-page__heading mt-sm" style="font-family:var(--font-display);font-size:clamp(1.35rem,3vw,1.85rem);">
				<?php esc_html_e( 'Лучшее за месяц', 'palime-archive' ); ?>
			</h2>
		</div>

		<?php if ( $monthly_query->have_posts() ) : ?>
			<?php
			$monthly_query->the_post();
			$monthly_post_id = get_the_ID();
			?>

			<?php if ( ! empty( $monthly_cats ) ) : ?>
				<div class="grid grid--3">
					<?php foreach ( $monthly_cats as $cat_key => $cat_label ) : ?>
						<?php
						$cat_items = function_exists( 'get_field' ) ? get_field( 'monthly_' . sanitize_key( (string) $cat_key ), $monthly_post_id ) : [];
						?>
						<div class="card section-page__monthly-card" style="padding:var(--spacing-lg);">
							<h3 class="section-page__column-title text-mono text-xs text-upper mb-lg" style="letter-spacing:.1em;color:var(--accent);">
								<?php echo esc_html( $cat_label ); ?>
							</h3>
							<?php if ( $cat_items ) : ?>
								<ol class="section-page__monthly-list" style="list-style:none;display:flex;flex-direction:column;gap:var(--spacing-sm);">
									<?php foreach ( $cat_items as $i => $entry ) : ?>
										<li class="section-page__monthly-item flex flex--gap" style="padding-bottom:var(--spacing-xs);border-bottom:1px solid rgba(0,0,0,.05);">
											<span class="section-page__ranking-index text-mono text-xs" style="color:var(--accent);min-width:1.25rem;"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
											<span class="section-page__monthly-copy text-serif" style="font-size:.95rem;line-height:1.4;">
												<?php echo esc_html( is_array( $entry ) ? ( $entry['title'] ?? $entry[0] ?? '' ) : $entry ); ?>
											</span>
										</li>
									<?php endforeach; ?>
								</ol>
							<?php else : ?>
								<p class="text-muted text-mono text-xs section-page__monthly-empty"><?php esc_html_e( '— Пусто —', 'palime-archive' ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php else : ?>
				<p class="text-muted text-mono text-xs section-page__stub-caption"><?php esc_html_e( '— Задайте monthly_cats в $args страницы раздела —', 'palime-archive' ); ?></p>
			<?php endif; ?>

			<?php wp_reset_postdata(); ?>

		<?php else : ?>
			<?php wp_reset_postdata(); ?>
			<p class="text-muted text-mono text-xs section-page__stub-monthly" style="letter-spacing:.08em;">
				<?php esc_html_e( '— Итог месяца появится здесь (meta palime_section + CPT monthly_best) —', 'palime-archive' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>


<!-- 7. О разделе -->
<section class="section section-page__about">
	<div class="container">
		<div class="grid grid--sidebar">
			<div>
				<span class="section-page__eyebrow text-mono text-xs text-muted text-upper mb-lg" style="letter-spacing:.12em;display:block;">— <?php esc_html_e( 'О разделе', 'palime-archive' ); ?> —</span>
				<?php if ( $section_about ) : ?>
					<div class="section-page__about-body text-serif" style="font-size:1.1rem;line-height:1.75;max-width:40rem;">
						<?php echo wp_kses_post( $section_about ); ?>
					</div>
				<?php else : ?>
					<p class="text-muted text-serif section-page__stub-about" style="font-size:1.05rem;line-height:1.7;max-width:40rem;opacity:.55;">
						<?php esc_html_e( 'Текст о разделе задаётся в $args[\'section_about\'] (разрешённый HTML через wp_kses_post).', 'palime-archive' ); ?>
					</p>
				<?php endif; ?>
			</div>
			<div class="section-page__about-side" style="align-self:center;text-align:center;">
				<p class="section-page__about-word text-display" style="font-family:var(--font-display);font-size:clamp(2rem,4vw,3.5rem);line-height:1.1;color:var(--accent);">
					<?php echo esc_html( strtoupper( $section_name ) ); ?>
				</p>
			</div>
		</div>
	</div>
</section>


<!-- 8. Превью магазина -->
<section class="section section-page__shop" style="background:var(--color-ui);color:#fff;">
	<div class="container">
		<div class="flex flex--between flex--wrap section-page__shop-inner" style="gap:var(--spacing-xl);align-items:center;">
			<div>
				<p class="text-mono text-xs mb-md" style="opacity:.65;letter-spacing:.2em;text-transform:uppercase;">
					— <?php esc_html_e( 'Текущий дроп', 'palime-archive' ); ?> —
				</p>
				<?php
				$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
				$drop     = null;
				if ( function_exists( 'wc_get_products' ) && $section_slug ) {
					$drop_products = wc_get_products(
						[
							'limit'      => 1,
							'status'     => 'publish',
							'orderby'    => 'date',
							'order'      => 'DESC',
							'meta_query' => [
								[
									'key'     => '_palime_section',
									'value'   => $section_slug,
									'compare' => '=',
								],
							],
						]
					);
					if ( ! empty( $drop_products ) ) {
						$drop = $drop_products[0];
					}
				}
				?>

				<?php if ( $drop ) : ?>
					<h2 style="font-family:var(--font-display);font-size:clamp(1.4rem,3vw,2.25rem);margin-bottom:var(--spacing-md);">
						<?php echo esc_html( $drop->get_name() ); ?>
					</h2>
					<p class="text-serif mb-xl" style="opacity:.78;">
						<?php echo esc_html( wp_trim_words( $drop->get_short_description(), 20, '…' ) ); ?>
					</p>
				<?php else : ?>
					<h2 class="section-page__stub-shop-title" style="font-family:var(--font-display);font-size:clamp(1.4rem,3vw,2.25rem);margin-bottom:var(--spacing-xl);opacity:.85;">
						<?php esc_html_e( 'Магазин Palime', 'palime-archive' ); ?>
					</h2>
					<p class="text-mono text-xs mb-lg" style="opacity:.45;">
						<?php esc_html_e( 'Товар с разделом (ACF section → _palime_section) или WooCommerce не подключён.', 'palime-archive' ); ?>
					</p>
				<?php endif; ?>

				<a href="<?php echo esc_url( $shop_url ); ?>"
					class="btn btn--outline"
					style="color:#fff;border-color:#fff;">
					<?php esc_html_e( 'Перейти в магазин →', 'palime-archive' ); ?>
				</a>
			</div>

			<div class="section-page__shop-media hide-mobile">
				<?php if ( $drop && function_exists( 'wc_get_products' ) ) : ?>
					<?php
					$img_html = $drop->get_image(
						'card',
						[
							'class' => 'section-page__shop-img',
							'alt'   => esc_attr( $drop->get_name() ),
						]
					);
					echo wp_kses_post( $img_html );
					?>
				<?php else : ?>
					<div class="section-page__stub-shop-visual text-mono text-xs" style="min-height:12rem;min-width:12rem;border:1px dashed rgba(255,255,255,.25);display:flex;align-items:center;justify-content:center;opacity:.4;">
						<?php esc_html_e( 'Обложка дропа', 'palime-archive' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>