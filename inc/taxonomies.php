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
        'label'        => 'Тип статьи',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'type' ],
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
        'label'        => 'Эпоха',
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'era' ],
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
