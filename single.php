<?php
/**
 * Palime Archive — single.php
 * Базовый шаблон для отдельных записей (стандартные посты)
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section">
    <div class="container container--narrow">

        <?php while ( have_posts() ) : the_post(); ?>

            <article <?php post_class(); ?>>

                <!-- Мета -->
                <div class="flex flex--gap mb-lg flex--wrap">
                    <?php palime_the_terms( get_the_ID(), 'category', 'tag' ); ?>
                    <span class="text-mono text-xs text-muted"><?php echo esc_html( palime_get_date() ); ?></span>
                </div>

                <!-- Заголовок -->
                <header class="mb-xl">
                    <h1 class="text-display mb-md" style="font-family:var(--font-display); font-size:2.5rem; line-height:1.2;">
                        <?php the_title(); ?>
                    </h1>
                    <?php if ( has_excerpt() ) : ?>
                        <p class="text-lg text-muted" style="font-family:var(--font-serif); line-height:1.6;">
                            <?php the_excerpt(); ?>
                        </p>
                    <?php endif; ?>
                </header>

                <!-- Обложка -->
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="mb-xl overflow-hidden" style="border-radius:var(--radius-md);">
                        <?php the_post_thumbnail( 'large', [ 'style' => 'width:100%;height:auto;display:block;', 'alt' => esc_attr( get_the_title() ) ] ); ?>
                    </div>
                <?php endif; ?>

                <!-- Контент -->
                <div class="entry-content" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.85;">
                    <?php the_content(); ?>
                </div>

                <!-- Теги -->
                <?php if ( has_tag() ) : ?>
                    <div class="flex flex--gap flex--wrap mt-xl">
                        <?php the_tags( '', '', '' ); ?>
                    </div>
                <?php endif; ?>

            </article>

            <!-- Навигация по записям -->
            <nav class="flex flex--between mt-2xl" style="border-top:1px solid rgba(0,0,0,.08); padding-top:var(--spacing-xl);">
                <?php
                $prev = get_previous_post();
                $next = get_next_post();
                ?>
                <?php if ( $prev ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="btn btn--ghost btn--sm">
                        ← <?php echo esc_html( get_the_title( $prev ) ); ?>
                    </a>
                <?php else : ?>
                    <span></span>
                <?php endif; ?>
                <?php if ( $next ) : ?>
                    <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="btn btn--ghost btn--sm">
                        <?php echo esc_html( get_the_title( $next ) ); ?> →
                    </a>
                <?php endif; ?>
            </nav>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <hr class="divider">
                <?php comments_template(); ?>
            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer(); ?>
