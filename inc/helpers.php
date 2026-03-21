<?php

// Palime Archive — inc/helpers.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// =========================================================

/**
 * Получить текущий раздел (section) для data-section на body.
 * Используется в header.php.
 */
function palime_get_current_section() {
    $section = '';

    if ( is_page_template( 'page-cinema.php' ) )     $section = 'cinema';
    if ( is_page_template( 'page-literature.php' ) ) $section = 'lit';
    if ( is_page_template( 'page-music.php' ) )      $section = 'music';
    if ( is_page_template( 'page-art.php' ) )        $section = 'art';

    // Для одиночной статьи — берём таксономию section
    if ( is_singular( 'article' ) ) {
        $terms = get_the_terms( get_the_ID(), 'section' );
        if ( $terms && ! is_wp_error( $terms ) ) {
            $section = $terms[0]->slug;
        }
    }

    return $section;
}

/**
 * Получить миниатюру с фолбэком на placeholder.
 *
 * @param int    $post_id
 * @param string $size   Registered image size (card, card-lg, hero)
 * @param string $alt
 * @return string HTML <img>
 */
function palime_get_thumbnail( $post_id, $size = 'card', $alt = '' ) {
    if ( has_post_thumbnail( $post_id ) ) {
        return get_the_post_thumbnail( $post_id, $size, [ 'alt' => esc_attr( $alt ) ] );
    }
    $w = $size === 'hero' ? 1920 : ( $size === 'card-lg' ? 1200 : 600 );
    $h = $size === 'hero' ? 1080 : ( $size === 'card-lg' ? 800  : 400 );
    return '<img src="https://placehold.co/' . $w . 'x' . $h . '/1a1a1a/444?text=Palime" alt="' . esc_attr( $alt ) . '" width="' . $w . '" height="' . $h . '">';
}

/**
 * Вывести список тегов таксономии для поста.
 *
 * @param int    $post_id
 * @param string $taxonomy
 * @param string $class   CSS-класс для каждого тега
 */
function palime_the_terms( $post_id, $taxonomy, $class = 'tag' ) {
    $terms = get_the_terms( $post_id, $taxonomy );
    if ( ! $terms || is_wp_error( $terms ) ) return;

    foreach ( $terms as $term ) {
        printf(
            '<a href="%s" class="%s">%s</a>',
            esc_url( get_term_link( $term ) ),
            esc_attr( $class ),
            esc_html( $term->name )
        );
    }
}

/**
 * Форматировать дату публикации на русском.
 *
 * @param int|null $post_id
 * @return string  Например: «15 марта 2026»
 */
function palime_get_date( $post_id = null ) {
    return get_the_date( 'j F Y', $post_id );
}

/**
 * Получить количество очков пользователя.
 *
 * @param int $user_id
 * @return int
 */
function palime_get_points( $user_id ) {
    return (int) get_user_meta( $user_id, 'palime_points', true );
}

/**
 * Truncate текст до нужного количества слов.
 *
 * @param string $text
 * @param int    $words
 * @return string
 */
function palime_excerpt( $text, $words = 20 ) {
    return wp_trim_words( $text, $words, '…' );
}
