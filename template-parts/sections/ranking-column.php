<?php
/**
 * Palime Archive — template-parts/sections/ranking-column.php
 * Одна панель рейтинга (авторы или произведения).
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

$items = [];
if ( $ranking_query->have_posts() ) {
	while ( $ranking_query->have_posts() ) {
		$ranking_query->the_post();
		$raw = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
		if ( $raw && is_array( $raw ) ) {
			foreach ( array_slice( $raw, 0, 5 ) as $entry ) {
				$items[] = is_array( $entry ) ? ( $entry['name'] ?? $entry[0] ?? '' ) : (string) $entry;
			}
		}
	}
	wp_reset_postdata();
}

$placeholder_texts = [
	'Место откроется после первых голосов',
	'Архив собирает кандидатов',
	'Список формируется',
	'Ожидает данных',
	'Позиция резервирована',
];
?>

<div class="sp-rank__panel">
	<div class="sp-rank__panel-head">
		<h3 class="sp-rank__panel-title"><?php echo esc_html( $column_title ); ?></h3>
		<span class="sp-rank__panel-cat"><?php echo esc_html( strtoupper( $ranking_category ) ); ?></span>
	</div>

	<div class="sp-rank__list">
		<?php for ( $i = 0; $i < 5; $i++ ) :
			$has_item = isset( $items[ $i ] ) && $items[ $i ] !== '';
			$num = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
		?>
			<div class="sp-rank__item <?php echo ! $has_item ? 'sp-rank__item--empty' : ''; ?>">
				<span class="sp-rank__num"><?php echo esc_html( $num ); ?></span>
				<?php if ( $has_item ) : ?>
					<span class="sp-rank__name"><?php echo esc_html( $items[ $i ] ); ?></span>
				<?php else : ?>
					<span class="sp-rank__placeholder"><?php echo esc_html( $placeholder_texts[ $i ] ?? $placeholder_texts[0] ); ?></span>
				<?php endif; ?>
			</div>
		<?php endfor; ?>
	</div>

	<div class="sp-rank__panel-foot">
		<span><?php echo ! empty( $items ) ? 'ACTIVE' : 'PENDING'; ?></span>
		<span><?php echo esc_html( count( $items ) ); ?>/5 позиций</span>
	</div>
</div>
