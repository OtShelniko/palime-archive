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

    $section = sanitize_text_field( $_POST['section'] ?? '' );
    $person  = sanitize_text_field( $_POST['person']  ?? '' );
    $era     = sanitize_text_field( $_POST['era']     ?? '' );
    $genre   = sanitize_text_field( $_POST['genre']   ?? '' );
    $type    = sanitize_text_field( $_POST['type']    ?? '' );
    $status  = sanitize_text_field( $_POST['status']  ?? '' );
    $search  = sanitize_text_field( $_POST['search']  ?? '' );
    $sort    = sanitize_text_field( $_POST['sort']    ?? 'date' );
    $paged   = max( 1, (int) ( $_POST['paged'] ?? 1 ) );

    // Сортировка
    $orderby = 'date';
    $order   = 'DESC';
    if ( $sort === 'popular' )              $orderby = 'comment_count';
    if ( $sort === 'relevance' && $search ) $orderby = 'relevance';

    $args = [
        'post_type'      => 'article',
        'posts_per_page' => 12,
        'paged'          => $paged,
        'post_status'    => 'publish',
        'orderby'        => $orderby,
        'order'          => $order,
        'tax_query'      => [ 'relation' => 'AND' ],
    ];

    if ( $search )  $args['s'] = $search;
    if ( $section ) $args['tax_query'][] = [ 'taxonomy' => 'section',      'field' => 'slug', 'terms' => $section ];
    if ( $person )  $args['tax_query'][] = [ 'taxonomy' => 'person',       'field' => 'slug', 'terms' => $person ];
    if ( $era )     $args['tax_query'][] = [ 'taxonomy' => 'era',          'field' => 'slug', 'terms' => $era ];
    if ( $genre )   $args['tax_query'][] = [ 'taxonomy' => 'genre',        'field' => 'slug', 'terms' => $genre ];
    if ( $type )    $args['tax_query'][] = [ 'taxonomy' => 'article-type', 'field' => 'slug', 'terms' => $type ];
    if ( $status )  $args['tax_query'][] = [ 'taxonomy' => 'status',       'field' => 'slug', 'terms' => $status ];

    $type_labels = [
        'author'    => 'про автора',
        'work'      => 'про произведение',
        'selection' => 'подборка',
    ];
    $status_labels = [
        'verified' => 'подтверждено',
        'disputed' => 'спорно',
        'archived' => 'в архиве',
    ];

    $query = new WP_Query( $args );
    $posts = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();

            // Раздел
            $s_terms      = get_the_terms( $post_id, 'section' );
            $section_slug = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->slug : '';
            $section_name = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '';

            // Тип материала
            $t_terms    = get_the_terms( $post_id, 'article-type' );
            $type_slug  = ( $t_terms && ! is_wp_error( $t_terms ) ) ? $t_terms[0]->slug : '';
            $type_label = isset( $type_labels[ $type_slug ] ) ? $type_labels[ $type_slug ] : ( ( $t_terms && ! is_wp_error( $t_terms ) ) ? $t_terms[0]->name : '' );

            // Статус
            $st_terms     = get_the_terms( $post_id, 'status' );
            $status_slug  = ( $st_terms && ! is_wp_error( $st_terms ) ) ? $st_terms[0]->slug : '';
            $status_label = isset( $status_labels[ $status_slug ] ) ? $status_labels[ $status_slug ] : ( ( $st_terms && ! is_wp_error( $st_terms ) ) ? $st_terms[0]->name : '' );

            // ACF: время чтения и лид
            $reading_time = function_exists( 'get_field' ) ? (int) get_field( 'reading_time', $post_id ) : 0;
            $lead         = function_exists( 'get_field' ) ? get_field( 'article_lead', $post_id ) : '';
            if ( ! $lead ) $lead = get_the_excerpt();

            // Персоны
            $p_terms = get_the_terms( $post_id, 'person' );
            $persons = [];
            if ( $p_terms && ! is_wp_error( $p_terms ) ) {
                foreach ( array_slice( $p_terms, 0, 4 ) as $pt ) {
                    $persons[] = [
                        'name' => $pt->name,
                        'slug' => $pt->slug,
                        'url'  => (string) get_term_link( $pt ),
                    ];
                }
            }

            $posts[] = [
                'id'           => $post_id,
                'title'        => get_the_title(),
                'url'          => get_permalink(),
                'lead'         => wp_trim_words( $lead, 18, '…' ),
                'excerpt'      => get_the_excerpt(),
                'date'         => get_the_date( 'd.m.Y' ),
                'section_slug' => $section_slug,
                'section_name' => $section_name,
                'type_slug'    => $type_slug,
                'type_label'   => $type_label,
                'status_slug'  => $status_slug,
                'status_label' => $status_label,
                'reading_time' => $reading_time ?: '',
                'persons'      => $persons,
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success( [
        'posts'     => $posts,
        'max_pages' => $query->max_num_pages,
        'total'     => $query->found_posts,
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
