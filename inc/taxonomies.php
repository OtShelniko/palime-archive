<?php

// Palime Archive — inc/taxonomies.php

if ( ! defined( 'ABSPATH' ) ) exit;

function palime_register_taxonomies() {

    $post_types_all = [ 'article', 'news', 'ranking', 'dossier' ];
    $post_types_art = [ 'article', 'dossier' ];

    register_taxonomy( 'section', array_merge( $post_types_all, [ 'route' ] ), [
        'label'        => 'Раздел',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'section' ],
    ] );

    register_taxonomy( 'article-type', [ 'article' ], [
        'labels'            => [
            'name'              => 'Типы статей',
            'singular_name'     => 'Тип статьи',
            'search_items'      => 'Найти тип',
            'all_items'         => 'Все типы',
            'parent_item'       => 'Родительский тип',
            'parent_item_colon' => 'Родительский тип:',
            'edit_item'         => 'Изменить тип',
            'update_item'       => 'Обновить тип',
            'add_new_item'      => 'Добавить тип',
            'new_item_name'     => 'Название типа',
            'menu_name'         => 'Тип статьи',
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'type' ],
    ] );

    // Темы / мотивы (смысловые оси) — не путать с monthly-theme (выпуск/магазин).
    register_taxonomy( 'theme', [ 'article' ], [
        'label'             => 'Темы / мотивы',
        'labels'            => [
            'name'          => 'Темы / мотивы',
            'singular_name' => 'Тема / мотив',
            'search_items'  => 'Найти темы',
            'all_items'     => 'Все темы',
            'edit_item'     => 'Изменить тему',
            'update_item'   => 'Обновить тему',
            'add_new_item'  => 'Добавить тему',
            'new_item_name' => 'Название темы',
            'menu_name'     => 'Темы / мотивы',
        ],
        'description'       => 'Смысловые оси материала (власть, память, война…). Отдельно от жанра и от темы месяца.',
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'theme' ],
    ] );

    // Редакторские подборочные метки — не путать со статусом верификации (status).
    register_taxonomy( 'editorial-flag', [ 'article' ], [
        'label'             => 'Редакторские метки',
        'labels'            => [
            'name'          => 'Редакторские метки',
            'singular_name' => 'Метка',
            'search_items'  => 'Найти метки',
            'all_items'     => 'Все метки',
            'edit_item'     => 'Изменить метку',
            'update_item'   => 'Обновить метку',
            'add_new_item'  => 'Добавить метку',
            'new_item_name' => 'Название метки',
            'menu_name'     => 'Редакторские метки',
        ],
        'description'       => 'Подборки редакции: канон, essential, спорное и т.д. Статус проверки материала — в таксономии «Статус».',
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rest_base'         => 'palime-editorial-flag',
        'rewrite'           => [ 'slug' => 'editorial' ],
    ] );

    register_taxonomy( 'monthly-theme', [ 'article', 'product' ], [
        'label'        => 'Тема месяца',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'monthly-theme' ],
    ] );

    // rest_base = 'palime-status' — чтобы не конфликтовать со встроенным REST полем 'status'
    register_taxonomy( 'status', [ 'article', 'news', 'dossier' ], [
        'label'        => 'Статус',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rest_base'    => 'palime-status',
        'rewrite'      => [ 'slug' => 'status' ],
    ] );

    register_taxonomy( 'person', $post_types_art, [
        'label'        => 'Персона',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'person' ],
    ] );

    register_taxonomy( 'era', $post_types_art, [
        'labels'            => [
            'name'              => 'Эпохи',
            'singular_name'     => 'Эпоха',
            'search_items'      => 'Найти эпоху',
            'all_items'         => 'Все эпохи',
            'parent_item'       => 'Родительская эпоха',
            'parent_item_colon' => 'Родительская эпоха:',
            'edit_item'         => 'Изменить эпоху',
            'update_item'       => 'Обновить эпоху',
            'add_new_item'      => 'Добавить эпоху',
            'new_item_name'     => 'Название эпохи',
            'menu_name'         => 'Эпоха',
        ],
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,
        'rewrite'           => [ 'slug' => 'era' ],
    ] );

    register_taxonomy( 'genre', [ 'article' ], [
        'label'        => 'Жанр',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'genre' ],
    ] );

    register_taxonomy( 'country', $post_types_art, [
        'label'        => 'Страна',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'country' ],
    ] );

    register_taxonomy( 'difficulty', [ 'route' ], [
        'label'        => 'Сложность',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'difficulty' ],
    ] );

    register_taxonomy( 'art-period', $post_types_art, [
        'label'        => 'Период ИЗО',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'art-period' ],
    ] );

    register_taxonomy( 'dispute-type', [ 'article' ], [
        'label'        => 'Тип спора',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'dispute-type' ],
    ] );

    register_taxonomy( 'music-scene', $post_types_art, [
        'label'        => 'Муз. сцена',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'music-scene' ],
    ] );

    register_taxonomy( 'film-movement', $post_types_art, [
        'label'        => 'Кинодвижение',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'film-movement' ],
    ] );
}

add_action( 'init', 'palime_register_taxonomies' );
