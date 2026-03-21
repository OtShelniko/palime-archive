<?php
/**
 * Palime Archive — search.php
 * Результаты поиска
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section--sm" style="background:var(--color-second);">
    <div class="container">
        <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Поиск —</span>
        <h1 class="mt-sm" style="font-family:var(--font-display); font-size:1.8rem;">
            <?php
            printf(
                'Результаты: <span style="color:var(--accent);">%s</span>',
                esc_html( get_search_query() )
            );
            ?>
        </h1>
        <?php if ( have_posts() ) : ?>
            <p class="mt-sm text-mono text-xs text-muted">
                Найдено записей: <?php echo esc_html( $wp_query->found_posts ); ?>
            </p>
        <?php endif; ?>

        <!-- Повторный поиск -->
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="subscribe-form mt-lg" style="max-width:480px;">
            <input
                type="search"
                class="form-input"
                placeholder="Новый запрос…"
                value="<?php echo esc_attr( get_search_query() ); ?>"
                name="s"
            >
            <button type="submit" class="btn btn--primary">→</button>
        </form>
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
                                    <span class="tag tag--filled text-xs"><?php echo esc_html( get_post_type_object( $pt )->labels->singular_name ); ?></span>
                                    <span><?php echo esc_html( palime_get_date() ); ?></span>
                                </div>
                                <h2 class="card__title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h2>
                                <?php if ( has_excerpt() ) : ?>
                                    <p class="card__excerpt"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                                <div class="card__footer">
                                    <a href="<?php the_permalink(); ?>" class="btn btn--ghost btn--sm">Открыть →</a>
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
                <p class="text-mono text-muted mb-lg" style="letter-spacing:.06em;">
                    По запросу «<?php echo esc_html( get_search_query() ); ?>» ничего не найдено.
                </p>
                <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--outline">Открыть архив</a>
            </div>

        <?php endif; ?>

    </div>
</div>

<?php get_footer(); ?>
