<?php

// Palime Archive — inc/setup.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// БАЗОВЫЕ НАСТРОЙКИ ТЕМЫ
// =========================================================

function palime_setup() {

    // Переводы
    load_theme_textdomain( 'palime-theme', get_template_directory() . '/languages' );

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
