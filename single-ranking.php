<?php
/**
 * Palime Archive — single-ranking.php
 * Одиночный рейтинг (CPT ranking)
 *
 * @package Palime_Archive
 */

get_header();

while ( have_posts() ) :
	the_post();

	$ranking_id = get_the_ID();
	$items      = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
	$terms      = get_the_terms( $ranking_id, 'section' );
	$sec_name   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
	?>

	<div class="section" style="padding-top:var(--spacing-xl);">
		<div class="container container--narrow">

			<p class="text-mono text-xs text-muted text-upper mb-md" style="letter-spacing:.12em;">
				<?php esc_html_e( 'Рейтинг', 'palime-archive' ); ?>
				<?php if ( $sec_name ) : ?>
					· <?php echo esc_html( $sec_name ); ?>
				<?php endif; ?>
			</p>

			<h1 class="text-display mb-xl" style="font-family:var(--font-display);font-size:clamp(1.75rem,4vw,2.5rem);line-height:1.15;">
				<?php the_title(); ?>
			</h1>

			<?php if ( $items && is_array( $items ) ) : ?>
				<ol class="section-page__ranking-list" style="list-style:none;display:flex;flex-direction:column;gap:var(--spacing-sm);max-width:42rem;">
					<?php foreach ( $items as $i => $item ) : ?>
						<li class="flex flex--gap" style="padding:var(--spacing-sm) 0;border-bottom:1px solid rgba(0,0,0,.08);">
							<span class="text-mono" style="color:var(--accent);min-width:1.5rem;font-size:.85rem;">
								<?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?>
							</span>
							<span class="text-serif" style="font-size:1.05rem;">
								<?php echo esc_html( is_array( $item ) ? ( $item['name'] ?? $item[0] ?? '' ) : $item ); ?>
							</span>
						</li>
					<?php endforeach; ?>
				</ol>
			<?php else : ?>
				<p class="text-muted text-mono text-xs">
					<?php esc_html_e( 'Список позиций задаётся в поле ranking_items (ACF).', 'palime-archive' ); ?>
				</p>
			<?php endif; ?>

			<p class="mt-2xl">
				<a href="<?php echo esc_url( function_exists( 'palime_get_rankings_archive_url' ) ? palime_get_rankings_archive_url() : home_url( '/rankings/' ) ); ?>" class="btn btn--outline btn--sm">
					<?php esc_html_e( '← К рейтингам', 'palime-archive' ); ?>
				</a>
			</p>

		</div>
	</div>

	<?php
endwhile;

get_footer();
