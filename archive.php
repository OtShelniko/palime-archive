<?php
/**
 * Palime Archive — archive.php
 * Базовый шаблон архивов (таксономии, даты, авторы)
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section--sm" style="background:var(--color-second);">
    <div class="container">
        <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Архив —</span>
        <h1 class="mt-sm" style="font-family:var(--font-display); font-size:2rem;">
            <?php the_archive_title(); ?>
        </h1>
        <?php
        $desc = get_the_archive_description();
        if ( $desc ) : ?>
            <p class="mt-md text-muted" style="font-family:var(--font-serif); max-width:600px; line-height:1.6;">
                <?php echo wp_kses_post( $desc ); ?>
            </p>
        <?php endif; ?>
    </div>
</div>

<div class="section">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <div class="grid grid--cards">
                <?php while ( have_posts() ) : the_post(); ?>

                    <?php
                    $pt = get_post_type();
                    if ( $pt === 'article' ) {
                        get_template_part( 'template-parts/cards/card', 'article' );
                    } elseif ( $pt === 'news' ) {
                        get_template_part( 'template-parts/cards/card', 'news' );
                    } else {
                        ?>
                        <article class="card fade-in">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="card__image">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail( 'card', [ 'alt' => get_the_title() ] ); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="card__body">
                                <div class="card__meta">
                                    <span><?php echo esc_html( palime_get_date() ); ?></span>
                                </div>
                                <h2 class="card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <div class="card__footer">
                                    <a href="<?php the_permalink(); ?>" class="btn btn--ghost btn--sm">Читать →</a>
                                </div>
                            </div>
                        </article>
                        <?php
                    }
                    ?>

                <?php endwhile; ?>
            </div>

            <div class="pagination mt-xl">
                <?php
                the_posts_pagination( [
                    'prev_text' => '← Новее',
                    'next_text' => 'Старше →',
                ] );
                ?>
            </div>

        <?php else : ?>

            <div class="text-center" style="padding:var(--spacing-2xl) 0;">
                <p class="text-mono text-muted text-upper" style="letter-spacing:.1em;">— Материалов не найдено —</p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline mt-lg">На главную</a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
