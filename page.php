<?php
/**
 * Palime Archive — page.php
 * Базовый шаблон для страниц WordPress
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section">
    <div class="container container--narrow">

        <?php while ( have_posts() ) : the_post(); ?>

            <article class="page-content">

                <header class="mb-xl">
                    <h1 class="text-display mb-md" style="font-family:var(--font-display); font-size:2.5rem; line-height:1.2;">
                        <?php the_title(); ?>
                    </h1>
                    <hr class="divider">
                </header>

                <div class="entry-content text-serif" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.8;">
                    <?php the_content(); ?>
                </div>

                <?php if ( get_edit_post_link() ) : ?>
                    <footer class="mt-xl">
                        <a href="<?php echo esc_url( get_edit_post_link() ); ?>" class="btn btn--outline btn--sm">Редактировать</a>
                    </footer>
                <?php endif; ?>

            </article>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <hr class="divider">
                <?php comments_template(); ?>
            <?php endif; ?>

        <?php endwhile; ?>

    </div>
</div>

<?php get_footer(); ?>
