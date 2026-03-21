<?php
/**
 * Palime Archive — single-dossier.php
 * Шаблон одиночного досье (CPT: dossier)
 *
 * @package Palime_Archive
 */

get_header();

while ( have_posts() ) : the_post();

    $post_id      = get_the_ID();
    $section_terms = get_the_terms( $post_id, 'section' );
    $section_slug  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->slug : '';

?>

<div class="section" style="padding-top:var(--spacing-xl);">
    <div class="container container--narrow">

        <!-- Назад -->
        <div class="mb-lg">
            <a href="<?php echo esc_url( home_url( '/dossiers/' ) ); ?>" class="btn btn--ghost btn--sm">
                ← Досье
            </a>
        </div>

        <article <?php post_class(); ?>>

            <!-- Мета-строка -->
            <div class="flex flex--gap flex--wrap mb-lg">
                <span class="tag tag--filled">Досье</span>
                <?php
                if ( $section_terms && ! is_wp_error( $section_terms ) ) :
                    foreach ( $section_terms as $st ) : ?>
                        <a href="<?php echo esc_url( home_url( '/' . $st->slug . '/' ) ); ?>" class="tag">
                            <?php echo esc_html( $st->name ); ?>
                        </a>
                    <?php endforeach;
                endif;
                ?>
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

            <!-- Таксономии -->
            <?php
            $person_terms = get_the_terms( $post_id, 'person' );
            if ( $person_terms && ! is_wp_error( $person_terms ) ) : ?>
                <div class="mb-lg">
                    <p class="text-mono text-xs text-muted text-upper mb-sm" style="letter-spacing:.1em;">Персоны</p>
                    <div class="flex flex--gap flex--wrap">
                        <?php foreach ( $person_terms as $person ) : ?>
                            <a href="<?php echo esc_url( home_url( '/archive/?person=' . $person->slug ) ); ?>" class="tag">
                                <?php echo esc_html( $person->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            $era_terms = get_the_terms( $post_id, 'era' );
            if ( $era_terms && ! is_wp_error( $era_terms ) ) : ?>
                <div class="mb-lg">
                    <p class="text-mono text-xs text-muted text-upper mb-sm" style="letter-spacing:.1em;">Эпоха</p>
                    <div class="flex flex--gap flex--wrap">
                        <?php foreach ( $era_terms as $era ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $era ) ); ?>" class="tag">
                                <?php echo esc_html( $era->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Контент -->
            <div class="entry-content" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.85;">
                <?php the_content(); ?>
            </div>

            <!-- Нижняя мета -->
            <div class="flex flex--gap flex--wrap mt-xl" style="border-top:1px solid rgba(0,0,0,.08); padding-top:var(--spacing-lg);">
                <span class="text-mono text-xs text-muted">Обновлено: <?php echo esc_html( palime_get_date() ); ?></span>
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
