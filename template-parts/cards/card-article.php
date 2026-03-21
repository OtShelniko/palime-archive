<?php
/**
 * Palime Archive — template-parts/cards/card-article.php
 * Карточка статьи (CPT: article)
 *
 * Использование:
 *   get_template_part( 'template-parts/cards/card', 'article' );
 *   // Должна вызываться внутри WP_Query loop
 *
 * @package Palime_Archive
 */

$post_id     = get_the_ID();
$permalink   = get_permalink();
$title       = get_the_title();

// ACF поля
$article_type = function_exists( 'get_field' ) ? get_field( 'article_type', $post_id ) : '';
$reading_time = function_exists( 'get_field' ) ? get_field( 'reading_time', $post_id )  : '';
$article_lead = function_exists( 'get_field' ) ? get_field( 'article_lead', $post_id )  : '';

$type_labels = [
    'author'    => 'Про автора',
    'work'      => 'Про произведение',
    'selection' => 'Подборка',
];
$type_label = isset( $type_labels[ $article_type ] ) ? $type_labels[ $article_type ] : '';

// Раздел
$section_terms = get_the_terms( $post_id, 'section' );
$section_name  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->name : '';
$section_slug  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->slug : '';
?>

<article class="card fade-in <?php echo $section_slug ? 'section-' . esc_attr( $section_slug ) : ''; ?>">

    <!-- Обложка -->
    <?php if ( has_post_thumbnail( $post_id ) ) : ?>
        <div class="card__image">
            <a href="<?php echo esc_url( $permalink ); ?>" tabindex="-1" aria-hidden="true">
                <?php echo palime_get_thumbnail( $post_id, 'card', $title ); ?>
            </a>
            <?php if ( $type_label ) : ?>
                <span class="tag tag--filled" style="position:absolute; top:var(--spacing-sm); left:var(--spacing-sm); z-index:1;">
                    <?php echo esc_html( $type_label ); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="card__body">

        <!-- Мета -->
        <div class="card__meta">
            <?php if ( $section_name ) : ?>
                <span class="text-accent"><?php echo esc_html( $section_name ); ?></span>
                <span>·</span>
            <?php endif; ?>
            <span><?php echo esc_html( palime_get_date( $post_id ) ); ?></span>
            <?php if ( $reading_time ) : ?>
                <span>· <?php echo esc_html( $reading_time ); ?> мин</span>
            <?php endif; ?>
        </div>

        <!-- Заголовок -->
        <h3 class="card__title">
            <a href="<?php echo esc_url( $permalink ); ?>"><?php echo esc_html( $title ); ?></a>
        </h3>

        <!-- Лид или excerpt -->
        <?php
        $excerpt_text = $article_lead
            ? palime_excerpt( $article_lead, 18 )
            : ( has_excerpt( $post_id ) ? get_the_excerpt( $post_id ) : '' );
        if ( $excerpt_text ) : ?>
            <p class="card__excerpt"><?php echo esc_html( $excerpt_text ); ?></p>
        <?php endif; ?>

        <!-- Теги персон -->
        <?php
        $person_terms = get_the_terms( $post_id, 'person' );
        if ( $person_terms && ! is_wp_error( $person_terms ) ) : ?>
            <div class="flex flex--gap flex--wrap" style="gap:4px;">
                <?php foreach ( array_slice( $person_terms, 0, 3 ) as $person ) : ?>
                    <a href="<?php echo esc_url( get_term_link( $person ) ); ?>" class="tag" style="font-size:.65rem; padding:2px 8px;">
                        <?php echo esc_html( $person->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Футер карточки -->
        <div class="card__footer">
            <a href="<?php echo esc_url( $permalink ); ?>" class="btn btn--ghost btn--sm">
                Открыть дело →
            </a>
        </div>

    </div>
</article>
