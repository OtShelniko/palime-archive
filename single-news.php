<?php
/**
 * Palime Archive — single-news.php
 * Шаблон одиночной новости (CPT: news)
 *
 * @package Palime_Archive
 */

get_header();

while ( have_posts() ) : the_post();

    $post_id      = get_the_ID();
    $section_terms = get_the_terms( $post_id, 'section' );
    $section_slug  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->slug : '';

?>

<div class="section">
    <div class="container container--narrow">

        <!-- Хлебные крошки / назад -->
        <div class="mb-lg">
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn btn--ghost btn--sm">
                ← Новости
            </a>
        </div>

        <article <?php post_class(); ?>>

            <!-- Мета-строка -->
            <div class="flex flex--gap flex--wrap mb-lg">
                <span class="tag tag--filled">Новость</span>
                <?php
                // Раздел → /news/?section={slug}
                if ( $section_terms && ! is_wp_error( $section_terms ) ) :
                    foreach ( $section_terms as $st ) : ?>
                        <a href="<?php echo esc_url( home_url( '/news/?section=' . $st->slug ) ); ?>" class="tag">
                            <?php echo esc_html( $st->name ); ?>
                        </a>
                    <?php endforeach;
                endif;
                ?>
                <span class="text-mono text-xs text-muted" style="align-self:center;">
                    <?php echo esc_html( palime_get_date() ); ?>
                </span>
            </div>

            <!-- Заголовок -->
            <h1 class="mb-xl" style="font-family:var(--font-display); font-size:clamp(1.6rem,3.5vw,2.8rem); line-height:1.2;">
                <?php the_title(); ?>
            </h1>

            <!-- Обложка -->
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="mb-xl overflow-hidden" style="border-radius:var(--radius-md);">
                    <?php the_post_thumbnail( 'large', [ 'style' => 'width:100%;height:auto;display:block;', 'alt' => get_the_title() ] ); ?>
                </div>
            <?php endif; ?>

            <!-- Контент -->
            <div class="entry-content" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.85;">
                <?php the_content(); ?>
            </div>

            <!-- Нижняя мета -->
            <div class="flex flex--gap flex--wrap mt-xl" style="border-top:1px solid rgba(0,0,0,.08); padding-top:var(--spacing-lg);">
                <span class="text-mono text-xs text-muted">Опубликовано: <?php echo esc_html( palime_get_date() ); ?></span>
                <?php
                $status_terms = get_the_terms( $post_id, 'status' );
                if ( $status_terms && ! is_wp_error( $status_terms ) ) :
                    foreach ( $status_terms as $st ) : ?>
                        <span class="tag"><?php echo esc_html( $st->name ); ?></span>
                    <?php endforeach;
                endif;
                ?>
            </div>

        </article>

        <!-- Навигация между новостями -->
        <nav class="flex flex--between mt-2xl">
            <?php
            $prev_news = get_previous_post();
            $next_news = get_next_post();
            ?>
            <?php if ( $prev_news ) : ?>
                <a href="<?php echo esc_url( get_permalink( $prev_news ) ); ?>" class="btn btn--ghost btn--sm">
                    ← <?php echo esc_html( wp_trim_words( get_the_title( $prev_news ), 6, '…' ) ); ?>
                </a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
            <?php if ( $next_news ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next_news ) ); ?>" class="btn btn--ghost btn--sm">
                    <?php echo esc_html( wp_trim_words( get_the_title( $next_news ), 6, '…' ) ); ?> →
                </a>
            <?php endif; ?>
        </nav>

    </div>
</div>

<!-- Последние новости раздела -->
<?php
$recent_args = [
    'post_type'      => 'news',
    'posts_per_page' => 5,
    'post__not_in'   => [ $post_id ],
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ( $section_slug ) {
    $recent_args['tax_query'] = [ [
        'taxonomy' => 'section',
        'field'    => 'slug',
        'terms'    => $section_slug,
    ] ];
}
$recent_news = new WP_Query( $recent_args );
if ( $recent_news->have_posts() ) : ?>

<div class="section section--accent">
    <div class="container container--narrow">

        <h2 class="mb-xl" style="font-family:var(--font-display); font-size:1.4rem;">
            Последние новости
        </h2>

        <ul style="list-style:none;">
            <?php while ( $recent_news->have_posts() ) : $recent_news->the_post(); ?>
                <?php get_template_part( 'template-parts/cards/card', 'news' ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </ul>

        <div class="mt-xl">
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn btn--outline">
                Все новости →
            </a>
        </div>

    </div>
</div>

<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
