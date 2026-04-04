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
	'section_intro'   => '',
	'section_code'    => '',
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
$section_intro  = $cfg['section_intro'];
$section_code   = $cfg['section_code'] ?: strtoupper( substr( $section_slug, 0, 3 ) );
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

$section_count_q = new WP_Query( array_merge( [
	'post_type'      => 'article',
	'post_status'    => 'publish',
	'posts_per_page' => 1,
	'no_found_rows'  => false,
], ! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : [] ) );
$section_article_count = $section_count_q->found_posts;
wp_reset_postdata();

// Latest article date for this section
$section_latest_date = '—';
$latest_q = new WP_Query( array_merge( [
	'post_type'      => 'article',
	'post_status'    => 'publish',
	'posts_per_page' => 1,
	'orderby'        => 'date',
	'order'          => 'DESC',
	'no_found_rows'  => true,
], ! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : [] ) );
if ( $latest_q->have_posts() ) {
	$latest_q->the_post();
	$section_latest_date = get_the_date( 'd.m.Y' );
	wp_reset_postdata();
}

?>

<!-- 1. HERO — SECTION ENTRY -->
<section
	class="sp-hero<?php echo $section_slug ? ' sp-hero--' . esc_attr( $section_slug ) : ''; ?>"
	style="--sp-accent:<?php echo esc_attr( $accent_color ); ?>;--sp-btn-fg:<?php echo esc_attr( $hero_btn_fg ); ?>"
	aria-labelledby="sp-title-<?php echo esc_attr( $section_slug ?: 'default' ); ?>">

	<!-- Vertical accent edge strip -->
	<div class="sp-hero__edge" aria-hidden="true"></div>

	<div class="sp-hero__grid">

		<!-- LEFT: content column -->
		<div class="sp-hero__left">

			<!-- Service pretitle / scanner label -->
			<div class="sp-hero__pretitle">
				<span class="sp-hero__pretitle-mark" aria-hidden="true">◈</span>
				<span><?php
					/* translators: 1: section code e.g. CIN */
					printf( 'PALIME ARCHIVE · %s · SECTION INDEX', esc_html( $section_code ) );
				?></span>
			</div>

			<!-- Main editorial block -->
			<div class="sp-hero__main">
				<h1 id="sp-title-<?php echo esc_attr( $section_slug ?: 'default' ); ?>" class="sp-hero__title">
					<?php echo esc_html( strtoupper( $section_name ) ); ?>
				</h1>

				<p class="sp-hero__manifesto"><?php echo esc_html( $section_slogan ); ?></p>

				<?php if ( $section_intro ) : ?>
					<p class="sp-hero__body"><?php echo esc_html( $section_intro ); ?></p>
				<?php endif; ?>
			</div>

			<!-- CTA row: primary / secondary / tertiary -->
			<div class="sp-hero__actions">
				<a href="<?php echo esc_url( $archive_url ); ?>" class="sp-hero__btn sp-hero__btn--primary">
					Открыть каталог
				</a>
				<a href="<?php echo esc_url( $rankings_url ); ?>" class="sp-hero__btn sp-hero__btn--secondary">
					Рейтинги
				</a>
				<a href="<?php echo esc_url( $news_url ); ?>" class="sp-hero__link">Новости раздела →</a>
			</div>

			<!-- Section nav rail -->
			<nav class="sp-hero__nav" aria-label="Навигация по разделу">
				<a href="#fresh"   class="sp-hero__nav-item" data-idx="01">
					<span class="sp-hero__nav-label">Материалы</span>
				</a>
				<a href="#ratings" class="sp-hero__nav-item" data-idx="02">
					<span class="sp-hero__nav-label">Рейтинги</span>
				</a>
				<a href="<?php echo esc_url( $news_url ); ?>" class="sp-hero__nav-item" data-idx="03">
					<span class="sp-hero__nav-label">Новости</span>
				</a>
				<a href="#about"   class="sp-hero__nav-item" data-idx="04">
					<span class="sp-hero__nav-label">О разделе</span>
				</a>
			</nav>

		</div>

		<!-- RIGHT: exhibit panel -->
		<div class="sp-hero__right">
			<div class="sp-hero__exhibit">

				<!-- Panel service strip -->
				<div class="sp-hero__exhibit-strip">
					<span class="sp-hero__exhibit-id"><?php
						printf( '%s–EXHIBIT–%s', esc_html( $section_code ), esc_html( gmdate( 'Y' ) ) );
					?></span>
					<span class="sp-hero__exhibit-badge">
						<span class="sp-hero__exhibit-dot" aria-hidden="true"></span>
						ACTIVE
					</span>
				</div>

				<!-- Panel media area -->
				<div class="sp-hero__exhibit-media">
					<?php if ( $hero_thumb_url ) : ?>
						<img
							src="<?php echo esc_url( $hero_thumb_url ); ?>"
							alt="<?php echo esc_attr( $section_name ); ?>"
							class="sp-hero__exhibit-img"
							loading="eager">
					<?php else : ?>
						<div class="sp-hero__exhibit-void">
							<span class="sp-hero__exhibit-void-label"><?php echo esc_html( strtoupper( $section_name ) ); ?></span>
						</div>
					<?php endif; ?>
					<!-- Scan-line texture -->
					<div class="sp-hero__exhibit-scan"  aria-hidden="true"></div>
					<!-- Corner frame brackets -->
					<div class="sp-hero__exhibit-frame" aria-hidden="true"></div>
				</div>

				<!-- Panel index data -->
				<div class="sp-hero__exhibit-index">
					<div class="sp-hero__exhibit-row">
						<span class="sp-hero__exhibit-key">SECTION</span>
						<span class="sp-hero__exhibit-val"><?php echo esc_html( strtoupper( $section_name ) ); ?></span>
					</div>
					<div class="sp-hero__exhibit-row">
						<span class="sp-hero__exhibit-key">ENTRIES</span>
						<span class="sp-hero__exhibit-val sp-hero__exhibit-val--accent"><?php echo esc_html( number_format_i18n( $section_article_count ) ); ?></span>
					</div>
					<div class="sp-hero__exhibit-row">
						<span class="sp-hero__exhibit-key">LAST UPDATE</span>
						<span class="sp-hero__exhibit-val"><?php echo esc_html( $section_latest_date ); ?></span>
					</div>
					<div class="sp-hero__exhibit-row">
						<span class="sp-hero__exhibit-key">INDEX STATUS</span>
						<span class="sp-hero__exhibit-val sp-hero__exhibit-val--accent">LIVE ◦</span>
					</div>
				</div>

			</div>

			<?php if ( $status_line ) : ?>
				<p class="sp-hero__status-line"><?php echo esc_html( $status_line ); ?></p>
			<?php endif; ?>
		</div>

	</div>
</section>


<!-- 2. Свежие материалы -->
<?php
$fresh_query = new WP_Query(
	array_merge(
		[
			'post_type'      => 'article',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		],
		! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
	)
);

$fresh_posts = [];
if ( $fresh_query->have_posts() ) {
	while ( $fresh_query->have_posts() ) {
		$fresh_query->the_post();
		$fp_id  = get_the_ID();
		$fp_sec = get_the_terms( $fp_id, 'section' );
		$fp_at  = get_the_terms( $fp_id, 'article-type' );
		$fp_type_labels = [ 'author' => 'Про автора', 'work' => 'Про произведение', 'selection' => 'Подборка' ];
		$fp_acf_type = function_exists( 'get_field' ) ? get_field( 'article_type', $fp_id ) : '';
		$fp_lead     = function_exists( 'get_field' ) ? get_field( 'article_lead', $fp_id ) : '';
		$fp_rtime    = function_exists( 'get_field' ) ? get_field( 'reading_time', $fp_id ) : '';

		$fresh_posts[] = [
			'id'        => $fp_id,
			'title'     => get_the_title(),
			'url'       => get_permalink(),
			'date'      => get_the_date( 'd.m.Y' ),
			'section'   => ( $fp_sec && ! is_wp_error( $fp_sec ) ) ? $fp_sec[0]->name : '',
			'form'      => ( $fp_at && ! is_wp_error( $fp_at ) ) ? $fp_at[0]->name : ( isset( $fp_type_labels[ $fp_acf_type ] ) ? $fp_type_labels[ $fp_acf_type ] : '' ),
			'excerpt'   => $fp_lead ? wp_trim_words( $fp_lead, 22, '...' ) : ( has_excerpt( $fp_id ) ? wp_trim_words( get_the_excerpt( $fp_id ), 22, '...' ) : '' ),
			'min'       => $fp_rtime ?: '',
			'has_thumb' => has_post_thumbnail( $fp_id ),
		];
	}
	wp_reset_postdata();
}
?>

<section class="section sp-fresh" id="fresh">
	<div class="container">

		<div class="sp-fresh__head">
			<div>
				<p class="sp-fresh__label">LATEST ENTRIES</p>
				<h2 class="sp-fresh__title">Свежие материалы</h2>
			</div>
			<a href="<?php echo esc_url( $archive_url ); ?>" class="sp-fresh__all">Весь архив →</a>
		</div>

		<div class="sp-fresh__grid">

			<?php if ( ! empty( $fresh_posts ) ) :
				$featured = $fresh_posts[0];
			?>
				<!-- Featured -->
				<a href="<?php echo esc_url( $featured['url'] ); ?>" class="sp-fresh__featured">
					<?php if ( $featured['has_thumb'] ) : ?>
						<div class="sp-fresh__featured-img">
							<?php echo get_the_post_thumbnail( $featured['id'], 'card-lg', [ 'loading' => 'lazy' ] ); ?>
						</div>
					<?php endif; ?>
					<div class="sp-fresh__featured-body">
						<div class="sp-fresh__meta">
							<?php if ( $featured['section'] ) : ?>
								<span class="sp-fresh__meta-accent"><?php echo esc_html( $featured['section'] ); ?></span>
							<?php endif; ?>
							<?php if ( $featured['form'] ) : ?>
								<span><?php echo esc_html( $featured['form'] ); ?></span>
							<?php endif; ?>
							<span><?php echo esc_html( $featured['date'] ); ?></span>
							<?php if ( $featured['min'] ) : ?>
								<span><?php echo esc_html( $featured['min'] ); ?> мин</span>
							<?php endif; ?>
						</div>
						<h3 class="sp-fresh__featured-title"><?php echo esc_html( $featured['title'] ); ?></h3>
						<?php if ( $featured['excerpt'] ) : ?>
							<p class="sp-fresh__featured-excerpt"><?php echo esc_html( $featured['excerpt'] ); ?></p>
						<?php endif; ?>
						<span class="sp-fresh__arrow">Открыть →</span>
					</div>
				</a>
			<?php else : ?>
				<div class="sp-fresh__featured sp-fresh__empty">
					<div class="sp-fresh__featured-body">
						<p class="sp-fresh__empty-num">01</p>
						<h3 class="sp-fresh__featured-title">Материалы появятся здесь</h3>
						<p class="sp-fresh__empty-note">Свежие статьи раздела займут это место.</p>
					</div>
				</div>
			<?php endif; ?>

			<!-- Secondary -->
			<div class="sp-fresh__sidebar">
				<?php for ( $si = 1; $si <= 2; $si++ ) :
					$has_post = isset( $fresh_posts[ $si ] );
					$sp = $has_post ? $fresh_posts[ $si ] : null;
				?>
					<?php if ( $has_post ) : ?>
						<a href="<?php echo esc_url( $sp['url'] ); ?>" class="sp-fresh__compact">
							<div class="sp-fresh__meta">
								<?php if ( $sp['form'] ) : ?>
									<span><?php echo esc_html( $sp['form'] ); ?></span>
								<?php endif; ?>
								<span><?php echo esc_html( $sp['date'] ); ?></span>
								<?php if ( $sp['min'] ) : ?>
									<span><?php echo esc_html( $sp['min'] ); ?> мин</span>
								<?php endif; ?>
							</div>
							<h4 class="sp-fresh__compact-title"><?php echo esc_html( $sp['title'] ); ?></h4>
							<span class="sp-fresh__compact-arrow">→</span>
						</a>
					<?php else : ?>
						<div class="sp-fresh__compact sp-fresh__compact--empty">
							<p class="sp-fresh__empty-num"><?php echo esc_html( sprintf( '%02d', $si + 1 ) ); ?></p>
							<h4 class="sp-fresh__compact-title">Ожидает публикации</h4>
							<p class="sp-fresh__empty-note">Следующий материал раздела появится здесь.</p>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

		</div>

	</div>
</section>


<!-- 3. Рейтинги -->
<section class="section sp-rank" id="ratings">
	<div class="container">

		<div class="sp-rank__head">
			<div>
				<p class="sp-rank__label">RATING SYSTEM</p>
				<h2 class="sp-rank__title"><?php echo esc_html( $section_name ); ?> · Рейтинг</h2>
				<p class="sp-rank__sub">Не окончательный канон, а подвижная система выбора.</p>
			</div>
			<a href="<?php echo esc_url( $rankings_url ); ?>" class="sp-rank__all">Все рейтинги →</a>
		</div>

		<p class="sp-rank__note">Формируется на основе голосов, сохранений и редакционной динамики.</p>

		<div class="sp-rank__panels">
			<?php
			get_template_part( 'template-parts/sections/ranking-column', null, [
				'column_title'     => $rating_authors,
				'ranking_category' => 'authors',
				'tax_query'        => $tax_query_section,
			] );

			get_template_part( 'template-parts/sections/ranking-column', null, [
				'column_title'     => $rating_works,
				'ranking_category' => 'works',
				'tax_query'        => $tax_query_section,
			] );
			?>
		</div>

	</div>
</section>


<!-- 4. Новости -->
<?php
$news_query = new WP_Query(
	array_merge(
		[
			'post_type'      => 'news',
			'posts_per_page' => 3,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		],
		! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : []
	)
);

$news_items = [];
if ( $news_query->have_posts() ) {
	while ( $news_query->have_posts() ) {
		$news_query->the_post();
		$n_id = get_the_ID();
		$news_items[] = [
			'title'   => get_the_title(),
			'url'     => get_permalink(),
			'date'    => get_the_date( 'd.m.Y' ),
			'ago'     => human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ),
			'excerpt' => has_excerpt( $n_id ) ? wp_trim_words( get_the_excerpt( $n_id ), 16, '...' ) : '',
		];
	}
	wp_reset_postdata();
}
?>

<section class="section sp-news" id="news">
	<div class="container">

		<div class="sp-news__head">
			<div>
				<p class="sp-news__label">SIGNALS</p>
				<h2 class="sp-news__title"><?php echo esc_html( $section_name ); ?> · Новости</h2>
			</div>
			<a href="<?php echo esc_url( $news_url ); ?>" class="sp-news__all">Все новости →</a>
		</div>

		<div class="sp-news__grid">

			<?php if ( ! empty( $news_items ) ) :
				$nf = $news_items[0];
			?>
				<a href="<?php echo esc_url( $nf['url'] ); ?>" class="sp-news__featured">
					<div class="sp-news__meta">
						<span class="sp-news__meta-accent"><?php echo esc_html( $nf['ago'] ); ?> назад</span>
						<span><?php echo esc_html( $nf['date'] ); ?></span>
					</div>
					<h3 class="sp-news__featured-title"><?php echo esc_html( $nf['title'] ); ?></h3>
					<?php if ( $nf['excerpt'] ) : ?>
						<p class="sp-news__featured-excerpt"><?php echo esc_html( $nf['excerpt'] ); ?></p>
					<?php endif; ?>
					<span class="sp-news__cta">Читать →</span>
				</a>
			<?php else : ?>
				<div class="sp-news__featured sp-news__placeholder">
					<p class="sp-news__placeholder-num">01</p>
					<h3 class="sp-news__featured-title">Новости раздела появятся здесь</h3>
					<p class="sp-news__placeholder-note">Новые записи добавляются по мере движения архива.</p>
				</div>
			<?php endif; ?>

			<div class="sp-news__side">
				<?php for ( $ni = 1; $ni <= 2; $ni++ ) :
					$has_news = isset( $news_items[ $ni ] );
					$nn = $has_news ? $news_items[ $ni ] : null;
				?>
					<?php if ( $has_news ) : ?>
						<a href="<?php echo esc_url( $nn['url'] ); ?>" class="sp-news__compact">
							<div class="sp-news__meta">
								<span><?php echo esc_html( $nn['ago'] ); ?> назад</span>
							</div>
							<h4 class="sp-news__compact-title"><?php echo esc_html( $nn['title'] ); ?></h4>
							<span class="sp-news__compact-arrow">→</span>
						</a>
					<?php else : ?>
						<div class="sp-news__compact sp-news__compact--empty">
							<p class="sp-news__placeholder-num"><?php echo esc_html( sprintf( '%02d', $ni + 1 ) ); ?></p>
							<h4 class="sp-news__compact-title">Следующий сигнал раздела</h4>
							<p class="sp-news__placeholder-note">Появится здесь.</p>
						</div>
					<?php endif; ?>
				<?php endfor; ?>
			</div>

		</div>

	</div>
</section>


<!-- 5. Цитата дня (таксономия section) -->
<?php
$today_ts  = current_time( 'timestamp' );
$quote_tax = ! empty( $tax_query_section ) ? [ 'tax_query' => $tax_query_section ] : [];
$quote_args = array_merge( [
	'post_type'      => 'quote_of_day',
	'posts_per_page' => 1,
	'no_found_rows'  => true,
	'date_query'     => [
		[
			'year'  => (int) gmdate( 'Y', $today_ts ),
			'month' => (int) gmdate( 'm', $today_ts ),
			'day'   => (int) gmdate( 'd', $today_ts ),
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
			'no_found_rows'  => true,
		], $quote_tax )
	);
}
?>

<section class="sp-quote" style="--sp-quote-bg:<?php echo esc_attr( $bg_color ); ?>">
	<div class="sp-quote__inner">
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

			$q_link_url = '';
			if ( $q_link ) {
				$q_link_url = is_numeric( $q_link ) ? get_permalink( (int) $q_link ) : $q_link;
			}
			?>

			<p class="sp-quote__label">QUOTE OF THE DAY</p>

			<div class="sp-quote__frame">
				<span class="sp-quote__mark" aria-hidden="true">&laquo;</span>

				<blockquote class="sp-quote__text">
					<?php echo esc_html( $q_text ); ?>
				</blockquote>

				<?php if ( $q_author || $q_work ) : ?>
					<div class="sp-quote__meta">
						<?php if ( $q_author ) : ?>
							<span class="sp-quote__author"><?php echo esc_html( $q_author ); ?></span>
						<?php endif; ?>
						<?php if ( $q_work ) : ?>
							<span class="sp-quote__work">&laquo;<?php echo esc_html( $q_work ); ?>&raquo;</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $q_link_url ) : ?>
				<a href="<?php echo esc_url( $q_link_url ); ?>" class="sp-quote__btn">Открыть дело →</a>
			<?php endif; ?>

		<?php else : ?>
			<p class="sp-quote__label">QUOTE OF THE DAY</p>
			<div class="sp-quote__frame sp-quote__frame--empty">
				<p class="sp-quote__stub">Цитата дня появится здесь</p>
			</div>
		<?php endif; ?>
	</div>
</section>


<!-- 6. Лучшее за месяц -->
<?php
$monthly_query = new WP_Query(
	[
		'post_type'      => 'monthly_best',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
		'no_found_rows'  => true,
		'meta_query'     => $meta_section,
	]
);
?>

<section class="sp-monthly" id="monthly" style="--sp-monthly-bg:<?php echo esc_attr( $bg_color ); ?>;--sp-monthly-accent:<?php echo esc_attr( $accent_color ); ?>">
	<div class="sp-monthly__inner">

		<div class="sp-monthly__head">
			<p class="sp-monthly__label">MONTHLY FEATURE</p>
			<h2 class="sp-monthly__title"><?php esc_html_e( 'Лучшее за месяц', 'palime-archive' ); ?></h2>
			<p class="sp-monthly__sub"><?php esc_html_e( 'Один материал, который удержал на себе внимание раздела.', 'palime-archive' ); ?></p>
		</div>

		<?php if ( $monthly_query->have_posts() ) :
			$monthly_query->the_post();
			$mb_id      = get_the_ID();
			$mb_title   = get_the_title();
			$mb_url     = get_permalink();
			$mb_month   = strtoupper( get_the_date( 'F Y' ) );
			$mb_excerpt = has_excerpt( $mb_id )
				? wp_trim_words( get_the_excerpt( $mb_id ), 26, '...' )
				: '';
			wp_reset_postdata();
		?>

			<a href="<?php echo esc_url( $mb_url ); ?>" class="sp-monthly__frame sp-monthly__frame--full">

				<div class="sp-monthly__frame-head">
					<span class="sp-monthly__frame-month"><?php echo esc_html( $mb_month ); ?></span>
					<span class="sp-monthly__frame-section"><?php echo esc_html( strtoupper( $section_name ) ); ?></span>
					<span class="sp-monthly__frame-badge"><?php esc_html_e( 'SELECTED', 'palime-archive' ); ?></span>
				</div>

				<div class="sp-monthly__frame-body">
					<h3 class="sp-monthly__frame-title"><?php echo esc_html( $mb_title ); ?></h3>
					<?php if ( $mb_excerpt ) : ?>
						<p class="sp-monthly__frame-excerpt"><?php echo esc_html( $mb_excerpt ); ?></p>
					<?php endif; ?>
				</div>

				<div class="sp-monthly__frame-foot">
					<span class="sp-monthly__frame-cta"><?php esc_html_e( 'Открыть материал →', 'palime-archive' ); ?></span>
					<span class="sp-monthly__frame-index"><?php echo esc_html( $section_code . ' · ' ); ?><?php esc_html_e( 'ИТОГ МЕСЯЦА', 'palime-archive' ); ?></span>
				</div>

			</a>

		<?php else :
			wp_reset_postdata();
		?>

			<div class="sp-monthly__frame sp-monthly__frame--empty">
				<div class="sp-monthly__frame-body">
					<h3 class="sp-monthly__empty-title"><?php esc_html_e( 'Итог месяца ещё формируется', 'palime-archive' ); ?></h3>
					<p class="sp-monthly__empty-text"><?php esc_html_e( 'Когда в разделе накопится достаточное количество материалов, здесь будет появляться один текст, к которому стоит вернуться в конце месяца.', 'palime-archive' ); ?></p>
				</div>
				<div class="sp-monthly__frame-foot">
					<span class="sp-monthly__empty-status">STATUS: WAITING FOR FIRST MONTHLY SELECTION</span>
				</div>
			</div>

		<?php endif; ?>

	</div>
</section>


<!-- 7. О разделе -->
<section class="sp-about" id="about">
	<div class="sp-about__inner">
		<div class="sp-about__grid">

			<!-- LEFT: text -->
			<div class="sp-about__content">

				<p class="sp-about__label">— <?php esc_html_e( 'О разделе', 'palime-archive' ); ?> —</p>

				<?php if ( $section_slogan ) : ?>
					<p class="sp-about__lead"><?php echo esc_html( $section_slogan ); ?></p>
				<?php endif; ?>

				<?php if ( $section_about ) : ?>
					<div class="sp-about__body">
						<?php echo wp_kses_post( $section_about ); ?>
					</div>
				<?php else : ?>
					<div class="sp-about__body sp-about__body--stub">
						<p><?php esc_html_e( 'Текст о разделе задаётся в аргументе section_about.', 'palime-archive' ); ?></p>
					</div>
				<?php endif; ?>

			</div>

			<!-- RIGHT: identity card -->
			<div class="sp-about__card" style="--sp-about-accent:<?php echo esc_attr( $accent_color ); ?>">

				<div class="sp-about__card-head">
					<span class="sp-about__card-tag">ARCHIVE IDENTIFIER</span>
					<span class="sp-about__card-code"><?php echo esc_html( $section_code ); ?></span>
				</div>

				<div class="sp-about__card-center">
					<span class="sp-about__card-geo" aria-hidden="true"></span>
					<p class="sp-about__card-name"><?php echo esc_html( strtoupper( $section_name ) ); ?></p>
				</div>

				<div class="sp-about__card-foot">
					<span class="sp-about__card-archive">PALIME ARCHIVE</span>
					<div class="sp-about__card-stats">
						<span class="sp-about__card-stat-val"><?php echo esc_html( number_format_i18n( $section_article_count ) ); ?></span>
						<span class="sp-about__card-stat-label">ENTRIES</span>
					</div>
				</div>

			</div>

		</div>
	</div>
</section>


<!-- 8. Дроп / коллекция -->
<?php
$shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$drop_products = [];

if ( function_exists( 'wc_get_products' ) && $section_slug ) {
	$drop_products = wc_get_products(
		[
			'limit'      => 4,
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
}

$featured_drop = ! empty( $drop_products ) ? $drop_products[0] : null;
$has_drops     = ! empty( $drop_products );

// ACF editorial columns (добавляются через ACF UI к товару)
$drop_source   = ( $featured_drop && function_exists( 'get_field' ) ) ? get_field( 'drop_source', $featured_drop->get_id() ) : '';
$drop_angle    = ( $featured_drop && function_exists( 'get_field' ) ) ? get_field( 'drop_angle',  $featured_drop->get_id() ) : '';
$drop_signal   = ( $featured_drop && function_exists( 'get_field' ) ) ? get_field( 'drop_signal', $featured_drop->get_id() ) : '';
$has_editorial = $drop_source || $drop_angle || $drop_signal;

// System line
$drop_issue = ( $featured_drop && function_exists( 'get_field' ) ) ? get_field( 'issue_number', $featured_drop->get_id() ) : '';
$drop_date  = '';
if ( $featured_drop ) {
	$created = $featured_drop->get_date_created();
	if ( $created ) {
		$drop_date = date_i18n( 'd.m.Y', $created->getTimestamp() );
	}
}
?>

<section class="sp-drop" style="--sp-drop-accent:<?php echo esc_attr( $accent_color ); ?>">
	<div class="sp-drop__inner">

		<!-- Header -->
		<div class="sp-drop__header">
			<div class="sp-drop__header-top">
				<p class="sp-drop__label"><?php esc_html_e( 'Текущий дроп', 'palime-archive' ); ?></p>

				<?php if ( $featured_drop ) : ?>
					<h2 class="sp-drop__title"><?php echo esc_html( $featured_drop->get_name() ); ?></h2>
					<?php $drop_intro = wp_trim_words( $featured_drop->get_short_description(), 22, '…' ); ?>
					<?php if ( $drop_intro ) : ?>
						<p class="sp-drop__desc"><?php echo esc_html( $drop_intro ); ?></p>
					<?php endif; ?>
				<?php else : ?>
					<h2 class="sp-drop__title sp-drop__title--stub"><?php esc_html_e( 'Коллекция формируется', 'palime-archive' ); ?></h2>
					<p class="sp-drop__desc sp-drop__desc--stub"><?php esc_html_e( 'Новая коллекция появится здесь после запуска товарной линии раздела.', 'palime-archive' ); ?></p>
				<?php endif; ?>
			</div>

			<div class="sp-drop__system-line">
				<span>DROP</span>
				<span class="sp-drop__sys-sep">·</span>
				<span><?php echo $drop_date ? esc_html( $drop_date ) : '—'; ?></span>
				<span class="sp-drop__sys-sep">·</span>
				<span class="sp-drop__sys-status"><?php echo $has_drops ? 'ACTIVE' : 'PENDING'; ?></span>
				<?php if ( $drop_issue ) : ?>
					<span class="sp-drop__sys-sep">·</span>
					<span>EDITION <?php echo esc_html( $drop_issue ); ?></span>
				<?php endif; ?>
				<span class="sp-drop__sys-sep">·</span>
				<span><?php echo esc_html( strtoupper( $section_name ) ); ?></span>
			</div>
		</div>

		<!-- Editorial columns (рендерятся только если заполнены ACF поля: drop_source / drop_angle / drop_signal) -->
		<?php if ( $has_editorial ) : ?>
		<div class="sp-drop__editorial">
			<?php
			$ed_cols = [
				[ 'label' => __( 'ИСТОЧНИК', 'palime-archive' ),          'text' => $drop_source ],
				[ 'label' => __( 'ПЕРЕОСМЫСЛЕНИЕ', 'palime-archive' ),    'text' => $drop_angle  ],
				[ 'label' => __( 'СИГНАЛ', 'palime-archive' ),            'text' => $drop_signal ],
			];
			foreach ( $ed_cols as $col ) :
				if ( ! $col['text'] ) continue;
			?>
			<div class="sp-drop__ed-col">
				<p class="sp-drop__ed-label"><?php echo esc_html( $col['label'] ); ?></p>
				<p class="sp-drop__ed-text"><?php echo esc_html( $col['text'] ); ?></p>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>

		<?php if ( $has_drops ) : ?>

		<!-- Product grid -->
		<div class="sp-drop__grid">
			<?php foreach ( $drop_products as $product ) :
				$pid         = $product->get_id();
				$p_name      = $product->get_name();
				$p_url       = get_permalink( $pid );
				$p_short     = wp_trim_words( $product->get_short_description(), 14, '…' );
				$p_in_stock  = $product->is_in_stock();
				$p_qty       = $product->get_stock_quantity();
				$p_img       = $product->get_image( 'card', [ 'class' => 'sp-drop__card-img', 'alt' => esc_attr( $p_name ), 'loading' => 'lazy' ] );
				$p_price     = $product->get_price_html();
				$p_file_code = $section_code . '-' . str_pad( (string) $pid, 4, '0', STR_PAD_LEFT );
			?>
			<div class="sp-drop__card">

				<a href="<?php echo esc_url( $p_url ); ?>" class="sp-drop__card-media" tabindex="-1" aria-hidden="true">
					<?php if ( $p_img ) : ?>
						<?php echo wp_kses_post( $p_img ); ?>
					<?php else : ?>
						<div class="sp-drop__card-placeholder">
							<span><?php echo esc_html( $p_file_code ); ?></span>
						</div>
					<?php endif; ?>
				</a>

				<div class="sp-drop__card-body">
					<div class="sp-drop__card-meta">
						<span class="sp-drop__card-file"><?php echo esc_html( $p_file_code ); ?></span>
						<?php if ( ! $p_in_stock ) : ?>
							<span class="sp-drop__card-stock sp-drop__card-stock--out"><?php esc_html_e( 'ARCHIVED', 'palime-archive' ); ?></span>
						<?php elseif ( $p_qty !== null ) : ?>
							<span class="sp-drop__card-stock"><?php echo esc_html( $p_qty ); ?> <?php esc_html_e( 'ост.', 'palime-archive' ); ?></span>
						<?php endif; ?>
					</div>

					<h3 class="sp-drop__card-title">
						<a href="<?php echo esc_url( $p_url ); ?>"><?php echo esc_html( $p_name ); ?></a>
					</h3>

					<?php if ( $p_short ) : ?>
						<p class="sp-drop__card-desc"><?php echo esc_html( $p_short ); ?></p>
					<?php endif; ?>

					<div class="sp-drop__card-foot">
						<?php if ( $p_price ) : ?>
							<div class="sp-drop__card-price"><?php echo wp_kses_post( $p_price ); ?></div>
						<?php endif; ?>

						<div class="sp-drop__card-actions">
							<a href="<?php echo esc_url( $p_url ); ?>" class="sp-drop__card-btn sp-drop__card-btn--ghost"><?php esc_html_e( 'Смотреть →', 'palime-archive' ); ?></a>
							<?php if ( $p_in_stock ) : ?>
								<a href="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="sp-drop__card-btn sp-drop__card-btn--primary" data-quantity="1" data-product_id="<?php echo esc_attr( $pid ); ?>"><?php esc_html_e( 'В архив', 'palime-archive' ); ?></a>
							<?php endif; ?>
						</div>
					</div>
				</div>

			</div>
			<?php endforeach; ?>
		</div>

		<div class="sp-drop__footer">
			<a href="<?php echo esc_url( $shop_url ); ?>" class="sp-drop__all-link"><?php esc_html_e( 'Все выпуски →', 'palime-archive' ); ?></a>
		</div>

		<?php else : ?>

		<!-- Empty state -->
		<div class="sp-drop__empty">
			<div class="sp-drop__empty-frame">
				<p class="sp-drop__empty-code"><?php echo esc_html( $section_code ); ?>-SERIES</p>
				<h3 class="sp-drop__empty-title"><?php esc_html_e( 'Текущий дроп формируется', 'palime-archive' ); ?></h3>
				<p class="sp-drop__empty-text"><?php esc_html_e( 'Когда товарная линия раздела будет запущена, здесь появится коллекция — ограниченный тираж объектов с контекстом.', 'palime-archive' ); ?></p>
				<p class="sp-drop__empty-status">STATUS: PENDING · <?php echo esc_html( strtoupper( $section_name ) ); ?></p>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="sp-drop__empty-btn"><?php esc_html_e( 'Перейти в магазин →', 'palime-archive' ); ?></a>
		</div>

		<?php endif; ?>

	</div>
</section>