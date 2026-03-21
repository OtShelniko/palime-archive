<?php
/**
 * Palime Archive — template-parts/cards/card-news.php
 * Карточка новости (CPT: news) — горизонтальный формат без изображения
 *
 * Использование:
 *   get_template_part( 'template-parts/cards/card', 'news' );
 *   // Должна вызываться внутри WP_Query loop
 *
 * @package Palime_Archive
 */

$post_id   = get_the_ID();
$permalink = get_permalink();
$title     = get_the_title();

// Раздел
$section_terms = get_the_terms( $post_id, 'section' );
$section_name  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->name : '';
$section_slug  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->slug : '';
?>

<li class="card card--news <?php echo $section_slug ? 'section-' . esc_attr( $section_slug ) : ''; ?>" style="display:block;">
    <div class="flex flex--between flex--gap flex--wrap" style="gap:var(--spacing-md);">

        <!-- Левая часть: мета + заголовок -->
        <div style="flex:1; min-width:0;">
            <div class="card__meta mb-xs">
                <?php if ( $section_name ) : ?>
                    <span class="text-accent"><?php echo esc_html( $section_name ); ?></span>
                    <span>·</span>
                <?php endif; ?>
                <span><?php echo esc_html( palime_get_date( $post_id ) ); ?></span>
            </div>

            <h3 style="font-family:var(--font-serif); font-size:1.05rem; line-height:1.4; font-weight:400;">
                <a href="<?php echo esc_url( $permalink ); ?>" style="transition:color var(--transition);">
                    <?php echo esc_html( $title ); ?>
                </a>
            </h3>

            <?php if ( has_excerpt( $post_id ) ) : ?>
                <p class="text-sm text-muted mt-xs" style="line-height:1.5;">
                    <?php echo esc_html( palime_excerpt( get_the_excerpt( $post_id ), 15 ) ); ?>
                </p>
            <?php endif; ?>
        </div>

        <!-- Правая часть: кнопка -->
        <div style="flex-shrink:0; align-self:center;">
            <a href="<?php echo esc_url( $permalink ); ?>" class="btn btn--ghost btn--sm">→</a>
        </div>

    </div>
</li>
