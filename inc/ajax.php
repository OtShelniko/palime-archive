<?php

// Palime Archive — inc/ajax.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// AJAX ОБРАБОТЧИКИ
// =========================================================

// ---------------------------------------------------------
// ГОЛОСОВАНИЕ В РЕЙТИНГЕ
// ---------------------------------------------------------

add_action( 'wp_ajax_palime_vote', 'palime_handle_vote' );

function palime_handle_vote() {
    check_ajax_referer( 'palime_vote_nonce', 'nonce' );

    $user_id    = get_current_user_id();
    $ranking_id = (int) $_POST['ranking_id'];
    $item_id    = sanitize_text_field( $_POST['item_id'] );

    if ( ! $user_id ) {
        wp_send_json_error( [ 'message' => 'Требуется авторизация' ] );
    }

    // Проверяем — не голосовал ли уже
    $voted_key = 'voted_ranking_' . $ranking_id;
    if ( get_user_meta( $user_id, $voted_key, true ) ) {
        wp_send_json_error( [ 'message' => 'Вы уже проголосовали' ] );
    }

    // Сохраняем голос
    update_user_meta( $user_id, $voted_key, $item_id );

    // Увеличиваем счётчик
    $votes_key = 'votes_' . $item_id;
    $current   = (int) get_post_meta( $ranking_id, $votes_key, true );
    update_post_meta( $ranking_id, $votes_key, $current + 1 );

    // Начисляем очки
    palime_add_points( $user_id, PALIME_POINTS_VOTE, 'Голосование в рейтинге' );

    wp_send_json_success( [ 'votes' => $current + 1 ] );
}

// ---------------------------------------------------------
// AJAX ФИЛЬТРЫ АРХИВА
// ---------------------------------------------------------

add_action( 'wp_ajax_palime_filter_archive',        'palime_handle_filter_archive' );
add_action( 'wp_ajax_nopriv_palime_filter_archive', 'palime_handle_filter_archive' );

function palime_handle_filter_archive() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $section  = sanitize_text_field( $_POST['section']  ?? '' );
    $person   = sanitize_text_field( $_POST['person']   ?? '' );
    $era      = sanitize_text_field( $_POST['era']      ?? '' );
    $genre    = sanitize_text_field( $_POST['genre']    ?? '' );
    $paged    = max( 1, (int) ( $_POST['paged'] ?? 1 ) );

    $args = [
        'post_type'      => 'article',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'tax_query'      => [ 'relation' => 'AND' ],
    ];

    if ( $section ) {
        $args['tax_query'][] = [ 'taxonomy' => 'section', 'field' => 'slug', 'terms' => $section ];
    }
    if ( $person ) {
        $args['tax_query'][] = [ 'taxonomy' => 'person', 'field' => 'slug', 'terms' => $person ];
    }
    if ( $era ) {
        $args['tax_query'][] = [ 'taxonomy' => 'era', 'field' => 'slug', 'terms' => $era ];
    }
    if ( $genre ) {
        $args['tax_query'][] = [ 'taxonomy' => 'genre', 'field' => 'slug', 'terms' => $genre ];
    }

    $query = new WP_Query( $args );
    $posts = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $posts[] = [
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => get_the_excerpt(),
                'thumbnail' => get_the_post_thumbnail_url( get_the_ID(), 'card' ),
                'date'      => get_the_date( 'j F Y' ),
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success( [
        'posts'      => $posts,
        'max_pages'  => $query->max_num_pages,
        'total'      => $query->found_posts,
    ] );
}

// ---------------------------------------------------------
// ПОДПИСКА НА РАССЫЛКУ (Unisender)
// ---------------------------------------------------------

add_action( 'wp_ajax_palime_subscribe',        'palime_handle_subscribe' );
add_action( 'wp_ajax_nopriv_palime_subscribe', 'palime_handle_subscribe' );

function palime_handle_subscribe() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $email = sanitize_email( $_POST['email'] ?? '' );

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Некорректный email' ] );
    }

    $api_key = get_option( 'palime_unisender_key' );

    if ( ! $api_key ) {
        wp_send_json_error( [ 'message' => 'Рассылка не настроена' ] );
    }

    $response = wp_remote_post( 'https://api.unisender.com/ru/api/subscribe', [
        'body' => [
            'format'         => 'json',
            'api_key'        => $api_key,
            'list_ids'       => '1',
            'fields[email]'  => $email,
            'double_optin'   => '3',
        ],
    ] );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( [ 'message' => 'Ошибка подписки' ] );
    }

    wp_send_json_success( [ 'message' => 'Вы подписаны!' ] );
}
