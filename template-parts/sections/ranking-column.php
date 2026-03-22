<?php
/**
 * Palime Archive — template-parts/sections/ranking-column.php
 * Одна колонка рейтинга (авторы или произведения).
 *
 * Ожидает $args:
 *   column_title     string  заголовок колонки
 *   ranking_category string  значение ACF ranking_category (authors | works)
 *   tax_query        array   tax_query для фильтра по секции
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = isset( $args ) && is_array( $args ) ? $args : [];

$column_title     = $args['column_title'] ?? '';
$ranking_category = $args['ranking_category'] ?? '';
$tax_query        = $args['tax_query'] ?? [];
?>

<div class="section-page__ratings-column">
	<h3 class="section-page__column-title text-mono text-xs text-upper mb-lg">
		<?php echo esc_html( $column_title ); ?>
	</h3>
	<?php
	$ranking_query = new WP_Query(
		array_merge(
			[
				'post_type'      => 'ranking',
				'posts_per_page' => 1,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'no_found_rows'  => true,
				'meta_query'     => [
					[
						'key'     => 'ranking_category',
						'value'   => $ranking_category,
						'compare' => '=',
					],
				],
			],
			! empty( $tax_query ) ? [ 'tax_query' => $tax_query ] : []
		)
	);
	if ( $ranking_query->have_posts() ) :
		while ( $ranking_query->have_posts() ) :
			$ranking_query->the_post();
			$items = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
			if ( $items ) :
				?>
				<ol class="section-page__ranking-list">
					<?php foreach ( $items as $i => $item ) : ?>
						<li class="section-page__ranking-item flex flex--gap">
							<span class="section-page__ranking-index text-mono"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
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
		<ul class="section-page__stub-ranking text-mono text-xs">
			<?php for ( $r = 1; $r <= 5; $r++ ) : ?>
				<li class="section-page__stub-ranking-item">—</li>
			<?php endfor; ?>
		</ul>
		<p class="text-muted text-mono text-xs mt-sm section-page__stub-caption"><?php esc_html_e( '— Рейтинг формируется —', 'palime-archive' ); ?></p>
		<?php
	endif;
	?>
</div>
