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

    // GET /palime/v1/leaderboard — топ пользователей
    register_rest_route( $namespace, '/leaderboard', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_leaderboard',
        'permission_callback' => '__return_true',
        'args' => [
            'limit' => [ 'default' => 10, 'sanitize_callback' => 'absint' ],
        ],
    ] );

    // GET /palime/v1/achievements — достижения текущего пользователя
    register_rest_route( $namespace, '/achievements', [
        'methods'             => 'GET',
        'callback'            => 'palime_api_get_achievements',
        'permission_callback' => function() {
            return is_user_logged_in();
        },
    ] );

    // POST /palime/v1/profile/update — обновить профиль
    register_rest_route( $namespace, '/profile/update', [
        'methods'             => 'POST',
        'callback'            => 'palime_api_update_profile',
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
    $level   = palime_get_user_level( $user_id );

    return rest_ensure_response( [
        'display_name'    => $data['user']->display_name,
        'email'           => $data['user']->user_email,
        'points'          => $data['points'],
        'level'           => $level,
        'level_number'    => $level['number'] ?? 1,
        'progress'        => palime_get_level_progress( $user_id ),
        'streak'          => palime_get_streak( $user_id ),
        'daily_earned'    => palime_get_daily_points( $user_id ),
        'daily_cap'       => PALIME_DAILY_BASE_CAP,
        'saved_count'     => count( $data['saved'] ),
        'achievements'    => count( palime_get_user_achievements( $user_id ) ),
        'total_achievements' => count( palime_get_achievements() ),
        'log'             => $data['log'],
    ] );
}

function palime_api_get_leaderboard( WP_REST_Request $request ) {
    $limit = min( (int) $request['limit'], 50 );

    $users = get_users( [
        'meta_key' => 'palime_points',
        'orderby'  => 'meta_value_num',
        'order'    => 'DESC',
        'number'   => $limit,
    ] );

    $result = [];
    $rank   = 1;
    foreach ( $users as $user ) {
        $points = palime_get_points( $user->ID );
        if ( $points <= 0 ) continue;

        $level = palime_get_user_level( $user->ID );
        $result[] = [
            'rank'         => $rank++,
            'display_name' => $user->display_name,
            'points'       => $points,
            'level'        => $level['name'],
            'level_number' => $level['number'] ?? 1,
            'avatar'       => get_avatar_url( $user->ID, [ 'size' => 40 ] ),
        ];
    }

    return rest_ensure_response( $result );
}

function palime_api_get_achievements( WP_REST_Request $request ) {
    $user_id      = get_current_user_id();
    $achievements = palime_get_achievements();
    $unlocked     = palime_get_user_achievements( $user_id );

    $result = [];
    foreach ( $achievements as $key => $ach ) {
        $is_unlocked = in_array( $key, $unlocked, true );
        $is_hidden   = ! empty( $ach['hidden'] ) && ! $is_unlocked;

        $result[] = [
            'key'         => $key,
            'name'        => $is_hidden ? '???' : $ach['name'],
            'description' => $is_hidden ? 'Скрытое достижение' : $ach['description'],
            'icon'        => $is_unlocked ? $ach['icon'] : '??',
            'bonus'       => $ach['bonus'],
            'rarity'      => $ach['rarity'] ?? 'common',
            'hidden'      => ! empty( $ach['hidden'] ),
            'unlocked'    => $is_unlocked,
        ];
    }

    return rest_ensure_response( $result );
}

function palime_api_update_profile( WP_REST_Request $request ) {
    $user_id      = get_current_user_id();
    $display_name = sanitize_text_field( $request->get_param( 'display_name' ) );

    if ( $display_name ) {
        wp_update_user( [
            'ID'           => $user_id,
            'display_name' => $display_name,
        ] );
    }

    return rest_ensure_response( [ 'success' => true ] );
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
