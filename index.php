<?php
/**
 * Palime Archive — index.php
 * Fallback-шаблон: список записей (если нет более специфичного шаблона)
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section">
    <div class="container">

        <?php if ( have_posts() ) : ?>

            <header class="mb-xl">
                <?php if ( is_home() && ! is_front_page() ) : ?>
                    <h1 class="text-display" style="font-family:var(--font-display); font-size:2rem;">
                        <?php single_post_title(); ?>
                    </h1>
                <?php else : ?>
                    <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">
                        — Материалы
                    </span>
                <?php endif; ?>
            </header>

            <div class="grid grid--cards">
                <?php while ( have_posts() ) : the_post(); ?>
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
                            <?php if ( has_excerpt() ) : ?>
                                <p class="card__excerpt"><?php the_excerpt(); ?></p>
                            <?php endif; ?>
                            <div class="card__footer">
                                <a href="<?php the_permalink(); ?>" class="btn btn--ghost btn--sm">Читать →</a>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <div class="pagination mt-xl">
                <?php
                the_posts_pagination( [
                    'prev_text' => '← Новее',
                    'next_text' => 'Старше →',
                    'class'     => 'pagination',
                ] );
                ?>
            </div>

        <?php else : ?>

            <div class="text-center" style="padding: var(--spacing-2xl) 0;">
                <p class="text-mono text-muted text-upper" style="letter-spacing:.1em;">
                    — Записей не найдено —
                </p>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--outline mt-lg">На главную</a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
