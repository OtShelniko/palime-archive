<?php

// Palime Archive — inc/post-types.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// РЕГИСТРАЦИЯ CUSTOM POST TYPES
// =========================================================

function palime_register_post_types() {

    // ---------------------------------------------------------
    // СТАТЬИ
    // ---------------------------------------------------------
    register_post_type( 'article', [
        'labels' => [
            'name'          => 'Статьи',
            'singular_name' => 'Статья',
            'add_new'       => 'Добавить статью',
            'add_new_item'  => 'Новая статья',
            'edit_item'     => 'Редактировать статью',
            'view_item'     => 'Смотреть статью',
            'search_items'  => 'Найти статью',
            'not_found'     => 'Статьи не найдены',
        ],
        'public'        => true,
        'show_in_rest'  => true, // REST API + Gutenberg
        'supports'      => [ 'title', 'editor', 'thumbnail', 'comments', 'excerpt' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'articles' ],
        'menu_icon'     => 'dashicons-text-page',
        'menu_position' => 5,
    ] );

    // ---------------------------------------------------------
    // НОВОСТИ
    // ---------------------------------------------------------
    register_post_type( 'news', [
        'labels' => [
            'name'          => 'Новости',
            'singular_name' => 'Новость',
            'add_new'       => 'Добавить новость',
            'add_new_item'  => 'Новая новость',
            'edit_item'     => 'Редактировать новость',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'news' ],
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 6,
    ] );

    // ---------------------------------------------------------
    // РЕЙТИНГИ
    // ---------------------------------------------------------
    register_post_type( 'ranking', [
        'labels' => [
            'name'          => 'Рейтинги',
            'singular_name' => 'Рейтинг',
            'add_new'       => 'Добавить рейтинг',
            'add_new_item'  => 'Новый рейтинг',
            'edit_item'     => 'Редактировать рейтинг',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'rankings' ],
        'menu_icon'     => 'dashicons-chart-bar',
        'menu_position' => 7,
    ] );

    // ---------------------------------------------------------
    // ЦИТАТА ДНЯ — меняется ежедневно по дате публикации
    // ---------------------------------------------------------
    register_post_type( 'quote_of_day', [
        'labels' => [
            'name'          => 'Цитаты дня',
            'singular_name' => 'Цитата дня',
            'add_new'       => 'Добавить цитату',
            'add_new_item'  => 'Новая цитата',
            'edit_item'     => 'Редактировать цитату',
        ],
        'public'              => true,
        'publicly_queryable'  => false,
        'show_in_rest'        => true,
        'supports'            => [ 'title' ],
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-format-quote',
        'menu_position'       => 8,
    ] );

    // ---------------------------------------------------------
    // ЛУЧШЕЕ ЗА МЕСЯЦ — ежемесячный итог по категориям
    // ---------------------------------------------------------
    register_post_type( 'monthly_best', [
        'labels' => [
            'name'          => 'Лучшее за месяц',
            'singular_name' => 'Итог месяца',
            'add_new'       => 'Добавить итог',
            'add_new_item'  => 'Новый итог месяца',
            'edit_item'     => 'Редактировать итог',
        ],
        'public'              => true,
        'publicly_queryable'  => false,
        'show_in_rest'        => true,
        'supports'            => [ 'title', 'editor' ],
        'has_archive'         => false,
        'rewrite'             => false,
        'menu_icon'           => 'dashicons-calendar-alt',
        'menu_position'       => 9,
    ] );

    // ---------------------------------------------------------
    // ЖИВОЙ ИНДЕКС — лента последних материалов на главной
    // ---------------------------------------------------------
    register_post_type( 'live_entry', [
        'labels' => [
            'name'          => 'Живой индекс',
            'singular_name' => 'Запись индекса',
            'add_new'       => 'Добавить запись',
            'add_new_item'  => 'Новая запись',
            'edit_item'     => 'Редактировать запись',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title' ],
        'has_archive'   => false,
        'rewrite'       => [ 'slug' => 'live' ],
        'menu_icon'     => 'dashicons-rss',
        'menu_position' => 10,
    ] );

    // ---------------------------------------------------------
    // ДОСЬЕ — авторы, режиссёры, художники, исполнители
    // ---------------------------------------------------------
    register_post_type( 'dossier', [
        'labels' => [
            'name'          => 'Досье',
            'singular_name' => 'Досье',
            'add_new'       => 'Добавить досье',
            'add_new_item'  => 'Новое досье',
            'edit_item'     => 'Редактировать досье',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor', 'thumbnail' ],
        'has_archive'   => true,
        'rewrite'       => [ 'slug' => 'dossiers' ],
        'menu_icon'     => 'dashicons-id',
        'menu_position' => 11,
    ] );

    // ---------------------------------------------------------
    // МАРШРУТЫ — кураторские пути по разделам
    // ---------------------------------------------------------
    register_post_type( 'route', [
        'labels' => [
            'name'          => 'Маршруты',
            'singular_name' => 'Маршрут',
            'add_new'       => 'Добавить маршрут',
            'add_new_item'  => 'Новый маршрут',
            'edit_item'     => 'Редактировать маршрут',
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'supports'      => [ 'title', 'editor' ],
        'has_archive'   => false,
        'rewrite'       => [ 'slug' => 'routes' ],
        'menu_icon'     => 'dashicons-location-alt',
        'menu_position' => 12,
    ] );
}

add_action( 'init', 'palime_register_post_types' );
