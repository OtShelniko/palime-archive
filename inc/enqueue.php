<?php

// Palime Archive — inc/enqueue.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// СТИЛИ И СКРИПТЫ
// =========================================================

function palime_enqueue_assets() {

    $ver = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    // ---------------------------------------------------------
    // CSS — порядок важен: сначала переменные, потом остальное
    // ---------------------------------------------------------

    wp_enqueue_style(
        'palime-variables',
        $uri . '/assets/css/variables.css',
        [],
        $ver
    );

    wp_enqueue_style(
        'palime-base',
        $uri . '/assets/css/base.css',
        [ 'palime-variables' ],
        $ver
    );

    wp_enqueue_style(
        'palime-layout',
        $uri . '/assets/css/layout.css',
        [ 'palime-base' ],
        $ver
    );

    wp_enqueue_style(
        'palime-components',
        $uri . '/assets/css/components.css',
        [ 'palime-layout' ],
        $ver
    );

    wp_enqueue_style(
        'palime-utilities',
        $uri . '/assets/css/utilities.css',
        [ 'palime-components' ],
        $ver
    );

    // Стили конкретных страниц — подключаем только там где нужно
    if ( is_front_page() ) {
        wp_enqueue_style(
            'palime-page-home',
            $uri . '/assets/css/pages/home.css',
            [ 'palime-utilities' ],
            $ver
        );
    }

    if ( is_page_template( 'page-archive.php' ) ) {
        wp_enqueue_style(
            'palime-page-archive',
            $uri . '/assets/css/pages/archive.css',
            [ 'palime-utilities' ],
            $ver
        );
    }

    if ( is_page_template( 'page-profile.php' ) ) {
        wp_enqueue_style(
            'palime-page-profile',
            $uri . '/assets/css/pages/profile.css',
            [ 'palime-utilities' ],
            $ver
        );
    }

    if ( is_page_template( 'page-blog.php' ) || is_page_template( 'page-news.php' ) ) {
        wp_enqueue_style(
            'palime-page-blog',
            $uri . '/assets/css/pages/blog.css',
            [ 'palime-utilities' ],
            $ver
        );
    }

    if ( is_woocommerce() || is_cart() || is_checkout() ) {
        wp_enqueue_style(
            'palime-page-shop',
            $uri . '/assets/css/pages/shop.css',
            [ 'palime-utilities' ],
            $ver
        );
    }

    // Основной style.css (заголовок темы) — подключаем последним
    wp_enqueue_style(
        'palime-style',
        get_stylesheet_uri(),
        [ 'palime-utilities' ],
        $ver
    );

    // Патч навигации и футера
    wp_enqueue_style(
        'palime-nav-fix',
        $uri . '/assets/css/nav-fix.css',
        [ 'palime-style' ],
        $ver
    );

    // ---------------------------------------------------------
    // JS
    // ---------------------------------------------------------

    // main.js — общая логика, переключение разделов
    wp_enqueue_script(
        'palime-main',
        $uri . '/assets/js/main.js',
        [],
        $ver,
        true // в футере
    );

    // filters.js — AJAX-фильтры архива (только на странице архива)
    if ( is_page_template( 'page-archive.php' ) ) {
        wp_enqueue_script(
            'palime-filters',
            $uri . '/assets/js/filters.js',
            [ 'palime-main' ],
            $ver,
            true
        );
    }

    // ratings.js — голосование в рейтингах
    if ( is_singular( 'ranking' ) || is_page_template( 'page-cinema.php' )
        || is_page_template( 'page-literature.php' )
        || is_page_template( 'page-music.php' )
        || is_page_template( 'page-art.php' )
    ) {
        wp_enqueue_script(
            'palime-ratings',
            $uri . '/assets/js/ratings.js',
            [ 'palime-main' ],
            $ver,
            true
        );
    }

    // profile.js — логика профиля
    if ( is_page_template( 'page-profile.php' ) ) {
        wp_enqueue_script(
            'palime-profile',
            $uri . '/assets/js/profile.js',
            [ 'palime-main' ],
            $ver,
            true
        );
    }

    // ---------------------------------------------------------
    // Передаём данные в JS через wp_localize_script
    // ---------------------------------------------------------

    wp_localize_script( 'palime-main', 'palimeData', [
        'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
        'restUrl'   => esc_url_raw( rest_url( 'wp/v2/' ) ),
        'nonce'     => wp_create_nonce( 'wp_rest' ),
        'voteNonce' => wp_create_nonce( 'palime_vote_nonce' ),
        'userId'    => get_current_user_id(),
    ] );
}

add_action( 'wp_enqueue_scripts', 'palime_enqueue_assets' );


// =========================================================
// УБИРАЕМ ЛИШНЕЕ ИЗ <HEAD>
// =========================================================

// Версии WordPress из тегов стилей/скриптов
add_filter( 'style_loader_src',  'palime_remove_version_query', 10, 2 );
add_filter( 'script_loader_src', 'palime_remove_version_query', 10, 2 );

function palime_remove_version_query( $src ) {
    if ( strpos( $src, '?ver=' ) ) {
        $src = remove_query_arg( 'ver', $src );
    }
    return $src;
}

// Emoji — не нужны
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// RSD, wlwmanifest, shortlink — не нужны
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
