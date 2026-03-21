<?php
/**
 * Palime Archive — single-route.php
 * Шаблон одиночного маршрута (CPT: route)
 *
 * @package Palime_Archive
 */

get_header();

while ( have_posts() ) : the_post();

    $post_id      = get_the_ID();
    $section_terms = get_the_terms( $post_id, 'section' );
    $diff_terms    = get_the_terms( $post_id, 'difficulty' );
    $diff_label    = ( $diff_terms && ! is_wp_error( $diff_terms ) ) ? $diff_terms[0]->name : '';

?>

<div class="section" style="padding-top:var(--spacing-xl);">
    <div class="container container--narrow">

        <!-- Назад -->
        <div class="mb-lg">
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--ghost btn--sm">
                ← Архив
            </a>
        </div>

        <article <?php post_class(); ?>>

            <!-- Мета-строка -->
            <div class="flex flex--gap flex--wrap mb-lg">
                <span class="tag tag--filled">Маршрут</span>
                <?php
                if ( $section_terms && ! is_wp_error( $section_terms ) ) :
                    foreach ( $section_terms as $st ) : ?>
                        <a href="<?php echo esc_url( home_url( '/' . $st->slug . '/' ) ); ?>" class="tag">
                            <?php echo esc_html( $st->name ); ?>
                        </a>
                    <?php endforeach;
                endif;
                ?>
                <?php if ( $diff_label ) : ?>
                    <span class="tag"><?php echo esc_html( $diff_label ); ?></span>
                <?php endif; ?>
            </div>

            <!-- Заголовок -->
            <h1 class="mb-xl" style="font-family:var(--font-display); font-size:clamp(1.6rem,3.5vw,2.8rem); line-height:1.2;">
                <?php the_title(); ?>
            </h1>

            <!-- Контент -->
            <div class="entry-content" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.85;">
                <?php the_content(); ?>
            </div>

            <!-- Нижняя мета -->
            <div class="flex flex--gap flex--wrap mt-xl" style="border-top:1px solid rgba(0,0,0,.08); padding-top:var(--spacing-lg);">
                <span class="text-mono text-xs text-muted">Опубликовано: <?php echo esc_html( palime_get_date() ); ?></span>
            </div>

        </article>

        <!-- Навигация -->
        <nav class="flex flex--between mt-2xl">
            <?php
            $prev = get_previous_post();
            $next = get_next_post();
            ?>
            <?php if ( $prev ) : ?>
                <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="btn btn--ghost btn--sm">
                    ← <?php echo esc_html( wp_trim_words( get_the_title( $prev ), 5, '…' ) ); ?>
                </a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
            <?php if ( $next ) : ?>
                <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="btn btn--ghost btn--sm">
                    <?php echo esc_html( wp_trim_words( get_the_title( $next ), 5, '…' ) ); ?> →
                </a>
            <?php endif; ?>
        </nav>

    </div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
