<?php

// Palime Archive — inc/api.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// REST API — кастомные endpoints
// =========================================================

add_action( 'rest_api_init', 'palime_register_rest_routes' );

function palime_register_rest_routes() {

    $namespace = 'palime/v1';

    // GET /palime/v1/articles — статьи с фильтрами
    register_rest_route( $namespace, '/articles', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_articles',
        'permission_callback' => '__return_true',
        'args' => [
            'section'         => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'person'          => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'era'             => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'theme'           => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'type'            => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'status'          => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'editorial_flag'  => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'search'          => [ 'sanitize_callback' => 'sanitize_text_field' ],
            'sort'            => [ 'default' => 'date', 'sanitize_callback' => 'sanitize_text_field' ],
            'per_page'        => [ 'default' => 12, 'sanitize_callback' => 'absint' ],
            'page'            => [ 'default' => 1,  'sanitize_callback' => 'absint' ],
        ],
    ] );

    // GET /palime/v1/rankings/{id} — рейтинг с результатами голосования
    register_rest_route( $namespace, '/rankings/(?P<id>\d+)', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_ranking',
        'permission_callback' => '__return_true',
    ] );

    // GET /palime/v1/profile — данные профиля текущего пользователя
    register_rest_route( $namespace, '/profile', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_profile',
        'permission_callback' => function() {
            return is_user_logged_in();
        },
    ] );

    // GET /palime/v1/persons — автодополнение персон для фильтра архива
    register_rest_route( $namespace, '/persons', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_persons',
        'permission_callback' => '__return_true',
        'args' => [
            'search' => [ 'sanitize_callback' => 'sanitize_text_field' ],
        ],
    ] );
}

// --- Callbacks ---

function palime_api_get_articles( WP_REST_Request $request ) {
    $search = $request['search'] ?? '';
    $sort   = $request['sort'] ?? 'date';

    $orderby = 'date';
    $order   = 'DESC';
    if ( $sort === 'popular' ) {
        $orderby = 'comment_count';
    }
    if ( $sort === 'relevance' && $search ) {
        $orderby = 'relevance';
    }

    $args = [
        'post_type'      => 'article',
        'posts_per_page' => $request['per_page'],
        'paged'          => $request['page'],
        'post_status'    => 'publish',
        'orderby'        => $orderby,
        'order'          => $order,
    ];

    if ( $search ) {
        $args['s'] = $search;
    }

    $tax_query = [ 'relation' => 'AND' ];

    $param_to_taxonomy = [
        'section'        => 'section',
        'person'         => 'person',
        'era'            => 'era',
        'theme'          => 'theme',
        'type'           => 'article-type',
        'status'         => 'status',
        'editorial_flag' => 'editorial-flag',
    ];

    foreach ( $param_to_taxonomy as $param => $taxonomy ) {
        if ( ! empty( $request[ $param ] ) ) {
            $tax_query[] = [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $request[ $param ],
            ];
        }
    }

    if ( count( $tax_query ) > 1 ) {
        $args['tax_query'] = $tax_query;
    }

    $query = new WP_Query( $args );
    $posts = [];

    foreach ( $query->posts as $post ) {
        $posts[] = [
            'id'        => $post->ID,
            'title'     => get_the_title( $post ),
            'url'       => get_permalink( $post ),
            'excerpt'   => get_the_excerpt( $post ),
            'thumbnail' => get_the_post_thumbnail_url( $post->ID, 'card' ),
            'date'      => get_the_date( 'j F Y', $post ),
            'section'   => wp_get_post_terms( $post->ID, 'section', [ 'fields' => 'slugs' ] ),
        ];
    }

    return rest_ensure_response( [
        'posts'     => $posts,
        'total'     => (int) $query->found_posts,
        'max_pages' => (int) $query->max_num_pages,
    ] );
}

function palime_api_get_ranking( WP_REST_Request $request ) {
    $id   = (int) $request['id'];
    $post = get_post( $id );

    if ( ! $post || $post->post_type !== 'ranking' ) {
        return new WP_Error( 'not_found', 'Рейтинг не найден', [ 'status' => 404 ] );
    }

    $items     = get_post_meta( $id, 'ranking_items', true ) ?: [];
    $user_vote = is_user_logged_in()
        ? get_user_meta( get_current_user_id(), 'voted_ranking_' . $id, true )
        : null;

    return rest_ensure_response( [
        'id'        => $id,
        'title'     => get_the_title( $post ),
        'items'     => $items,
        'user_vote' => $user_vote,
    ] );
}

function palime_api_get_profile( WP_REST_Request $request ) {
    $user_id = get_current_user_id();
    $data    = palime_get_profile_data( $user_id );

    return rest_ensure_response( [
        'display_name' => $data['user']->display_name,
        'email'        => $data['user']->user_email,
        'points'       => $data['points'],
        'level'        => $data['level'],
        'progress'     => palime_get_level_progress( $user_id ),
        'saved_count'  => count( $data['saved'] ),
        'log'          => $data['log'],
    ] );
}

function palime_api_get_persons( WP_REST_Request $request ) {
    $search = $request['search'] ?? '';

    $terms = get_terms( [
        'taxonomy'   => 'person',
        'hide_empty' => true,
        'search'     => $search,
        'number'     => 10,
    ] );

    if ( is_wp_error( $terms ) ) return rest_ensure_response( [] );

    $result = array_map( fn( $t ) => [
        'id'   => $t->term_id,
        'name' => $t->name,
        'slug' => $t->slug,
    ], $terms );

    return rest_ensure_response( $result );
}
