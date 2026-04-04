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
 * Структура панели:
 *   panel-head → leader area (позиция #1) → list (позиции #2–5) → panel-foot
 *   Если данных нет — показывается sp-rank__empty вместо leader + list.
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

$ranking_permalink = '';

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
		$ranking_permalink = get_permalink();
		$raw = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
		if ( $raw && is_array( $raw ) ) {
			foreach ( array_slice( $raw, 0, 5 ) as $entry ) {
				$items[] = is_array( $entry ) ? ( $entry['name'] ?? $entry[0] ?? '' ) : (string) $entry;
			}
		}
	}
	wp_reset_postdata();
}

// List placeholders for positions 2–5 when data is sparse.
$placeholder_texts = [
	1 => 'Место откроется после первых голосов',
	2 => 'Архив собирает кандидатов',
	3 => 'Список формируется',
	4 => 'Ожидает данных',
];

$is_active  = ! empty( $items );
$item_count = count( $items );
$leader     = $is_active ? ( $items[0] ?? null ) : null;
?>

<div class="sp-rank__panel">

	<!-- Panel header -->
	<div class="sp-rank__panel-head">
		<div class="sp-rank__panel-meta">
			<h3 class="sp-rank__panel-title"><?php echo esc_html( $column_title ); ?></h3>
			<span class="sp-rank__panel-cat"><?php echo esc_html( strtoupper( $ranking_category ) ); ?></span>
		</div>
		<span class="sp-rank__panel-badge<?php echo $is_active ? ' sp-rank__panel-badge--active' : ''; ?>">
			<?php echo $is_active ? 'ACTIVE' : 'PENDING'; ?>
		</span>
	</div>

	<?php if ( $leader !== null ) : ?>

		<!-- Leader area — position #1 -->
		<div class="sp-rank__leader">
			<span class="sp-rank__leader-num" aria-label="Позиция 1">01</span>
			<div class="sp-rank__leader-body">
				<span class="sp-rank__leader-name"><?php echo esc_html( $leader ); ?></span>
				<span class="sp-rank__leader-tag">Лидер рейтинга</span>
			</div>
		</div>

		<!-- List — positions 2–5 -->
		<div class="sp-rank__list">
			<?php for ( $i = 1; $i < 5; $i++ ) :
				$has_item   = isset( $items[ $i ] ) && $items[ $i ] !== '';
				$num        = str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT );
				$placeholder = $placeholder_texts[ $i ] ?? 'Ожидает данных';
			?>
				<div class="sp-rank__item<?php echo ! $has_item ? ' sp-rank__item--empty' : ''; ?>">
					<span class="sp-rank__num"><?php echo esc_html( $num ); ?></span>
					<?php if ( $has_item ) : ?>
						<span class="sp-rank__name"><?php echo esc_html( $items[ $i ] ); ?></span>
					<?php else : ?>
						<span class="sp-rank__placeholder"><?php echo esc_html( $placeholder ); ?></span>
					<?php endif; ?>
				</div>
			<?php endfor; ?>
		</div>

	<?php else : ?>

		<!-- Full empty state — no data yet -->
		<div class="sp-rank__empty">
			<span class="sp-rank__empty-mark" aria-hidden="true">——</span>
			<p class="sp-rank__empty-line">Рейтинг собирает первые голоса</p>
			<p class="sp-rank__empty-hint">Позиции появятся после первой активности в разделе</p>
		</div>

	<?php endif; ?>

	<!-- Panel footer -->
	<div class="sp-rank__panel-foot">
		<span class="sp-rank__panel-count"><?php echo esc_html( $item_count ); ?>/5</span>
		<?php if ( $ranking_permalink ) : ?>
			<a href="<?php echo esc_url( $ranking_permalink ); ?>" class="sp-rank__panel-link">
				Голосовать →
			</a>
		<?php else : ?>
			<span class="sp-rank__panel-period">Обновляется</span>
		<?php endif; ?>
	</div>

</div>
