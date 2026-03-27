<?php

// Palime Archive — inc/setup.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// БАЗОВЫЕ НАСТРОЙКИ ТЕМЫ
// =========================================================

function palime_setup() {

    // Переводы
    load_theme_textdomain( 'palime-archive', get_template_directory() . '/languages' );

    // Поддержка WordPress-функций
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ] );
    add_theme_support( 'woocommerce' );

    // Размеры изображений
    add_image_size( 'card',    600,  400,  true ); // карточки в сетке
    add_image_size( 'card-lg', 1200, 800,  true ); // большие карточки / герои разделов
    add_image_size( 'hero',    1920, 1080, true ); // full-width hero

    // Навигационные меню
    register_nav_menus( [
        'primary' => 'Основное меню',
        'footer'  => 'Меню подвала',
    ] );
}

add_action( 'after_setup_theme', 'palime_setup' );


// =========================================================
// КАСТОМНЫЕ PERMALINK ДЛЯ СТАТЕЙ (routing spec v1.1)
// URL: /{section}/{postname}/
//
// После каждого деплоя или изменения register_post_type / rewrite / add_permastruct:
//   Настройки → Постоянные ссылки → «Сохранить» (flush rewrite rules).
// Иначе новые правила (статьи, /rankings/, CPT) могут отдавать 404.
// =========================================================

add_action( 'init', function () {
    add_rewrite_tag( '%section%', '([^/]+)' );
    add_permastruct( 'article', '%section%/%postname%/', [ 'with_front' => false ] );
} );

add_filter( 'post_type_link', function ( $url, $post ) {
    if ( $post->post_type !== 'article' ) {
        return $url;
    }
    $terms = get_the_terms( $post->ID, 'section' );
    if ( $terms && ! is_wp_error( $terms ) ) {
        $section = $terms[0]->slug;
    } else {
        $section = 'archive'; // безопасный фолбэк
    }
    return str_replace( '%section%', $section, $url );
}, 10, 2 );


// =========================================================
// LEGACY REDIRECT: /blog/ → /archive/ (301)
// Страница «Блог» убрана из навигации. Сохраняем GET-параметры.
// =========================================================

add_action( 'template_redirect', function () {
    if ( is_page( 'blog' ) ) {
        $query_string = $_SERVER['QUERY_STRING'] ?? '';
        $target       = home_url( '/archive/' );
        if ( $query_string ) {
            $target .= '?' . $query_string;
        }
        wp_redirect( $target, 301 );
        exit;
    }
} );


// =========================================================
// ACF JSON — автосохранение и загрузка полей
// =========================================================

// Куда сохранять при редактировании в ACF UI
add_filter( 'acf/settings/save_json', function() {
    return get_template_directory() . '/acf-json';
} );

// Откуда загружать (добавляем нашу папку к стандартным путям ACF)
add_filter( 'acf/settings/load_json', function( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );
