<?php
// Palime Archive — template-parts/content-page.php

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="page-thumbnail">
            <?php the_post_thumbnail( 'card-lg' ); ?>
        </div>
    <?php endif; ?>

    <div class="page-content container container--narrow">
        <?php the_content(); ?>
    </div>

</article>
