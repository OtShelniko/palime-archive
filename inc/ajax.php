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
    palime_add_points( $user_id, PALIME_XP_VOTE, 'Голосование в рейтинге', 'base' );
    palime_check_achievements( $user_id );

    wp_send_json_success( [ 'votes' => $current + 1 ] );
}

// ---------------------------------------------------------
// AJAX ФИЛЬТРЫ АРХИВА
// ---------------------------------------------------------

add_action( 'wp_ajax_palime_filter_archive',        'palime_handle_filter_archive' );
add_action( 'wp_ajax_nopriv_palime_filter_archive', 'palime_handle_filter_archive' );

function palime_handle_filter_archive() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    // Поддерживаем как POST (filters.js), так и GET (page-blog/news inline JS)
    $req = array_merge( $_GET, $_POST );

    // Тип записи — только разрешённые значения
    $allowed_post_types = [ 'article', 'news' ];
    $post_type = sanitize_key( $req['post_type'] ?? 'article' );
    if ( ! in_array( $post_type, $allowed_post_types, true ) ) {
        $post_type = 'article';
    }

    $section         = sanitize_text_field( $req['section'] ?? '' );
    $person          = sanitize_text_field( $req['person'] ?? '' );
    $era             = sanitize_text_field( $req['era'] ?? '' );
    $type            = sanitize_text_field( $req['type'] ?? '' );
    $status          = sanitize_text_field( $req['status'] ?? '' );
    $theme           = sanitize_text_field( $req['theme'] ?? '' );
    $genre           = sanitize_text_field( $req['genre'] ?? '' );
    $editorial_flag  = sanitize_text_field( $req['editorial_flag'] ?? '' );
    $search          = sanitize_text_field( $req['search'] ?? $req['q'] ?? '' );
    $sort            = sanitize_text_field( $req['sort'] ?? 'date' );
    $paged           = max( 1, (int) ( $req['paged'] ?? 1 ) );

    // Сортировка
    $orderby = 'date';
    $order   = 'DESC';
    if ( $sort === 'popular' ) {
        $orderby = 'comment_count';
    }
    if ( $sort === 'relevance' && $search ) {
        $orderby = 'relevance';
    }

    $args = [
        'post_type'              => $post_type,
        'posts_per_page'         => 12,
        'paged'                  => $paged,
        'post_status'            => 'publish',
        'orderby'                => $orderby,
        'order'                  => $order,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];

    if ( $search ) {
        $args['s'] = $search;
    }

    $tax_query = [ 'relation' => 'AND' ];

    if ( $section ) {
        $tax_query[] = [ 'taxonomy' => 'section', 'field' => 'slug', 'terms' => $section ];
    }
    if ( $status ) {
        $tax_query[] = [ 'taxonomy' => 'status', 'field' => 'slug', 'terms' => $status ];
    }

    // Таксономии только для статей (theme, editorial-flag, era, article-type, person).
    if ( $post_type === 'article' ) {
        if ( $person ) {
            $tax_query[] = [ 'taxonomy' => 'person', 'field' => 'slug', 'terms' => $person ];
        }
        if ( $type ) {
            $tax_query[] = [ 'taxonomy' => 'article-type', 'field' => 'slug', 'terms' => $type ];
        }
        if ( $theme ) {
            $tax_query[] = [ 'taxonomy' => 'theme', 'field' => 'slug', 'terms' => $theme, 'include_children' => false ];
        }
        if ( $editorial_flag ) {
            $tax_query[] = [ 'taxonomy' => 'editorial-flag', 'field' => 'slug', 'terms' => $editorial_flag ];
        }
        if ( $genre ) {
            $tax_query[] = [ 'taxonomy' => 'genre', 'field' => 'slug', 'terms' => $genre ];
        }
        if ( $era ) {
            $tax_query[] = [ 'taxonomy' => 'era', 'field' => 'slug', 'terms' => $era ];
        }
    }

    if ( count( $tax_query ) > 1 ) {
        $args['tax_query'] = $tax_query;
    }

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

    // Налоговые запросы требуют кеша терминов — включаем обратно
    $args['update_post_term_cache'] = true;

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

            // Тип материала (только для статей)
            $t_terms    = get_the_terms( $post_id, 'article-type' );
            $type_slug  = ( $t_terms && ! is_wp_error( $t_terms ) ) ? $t_terms[0]->slug : '';
            $type_label = isset( $type_labels[ $type_slug ] )
                ? $type_labels[ $type_slug ]
                : ( ( $t_terms && ! is_wp_error( $t_terms ) ) ? $t_terms[0]->name : '' );

            // Статус
            $st_terms     = get_the_terms( $post_id, 'status' );
            $status_slug  = ( $st_terms && ! is_wp_error( $st_terms ) ) ? $st_terms[0]->slug : '';
            $status_label = isset( $status_labels[ $status_slug ] )
                ? $status_labels[ $status_slug ]
                : ( ( $st_terms && ! is_wp_error( $st_terms ) ) ? $st_terms[0]->name : '' );

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

            $post_data = [
                'id'           => $post_id,
                'title'        => get_the_title(),
                'url'          => get_permalink(),
                'lead'         => wp_trim_words( $lead, 18, '…' ),
                'date'         => get_the_date( 'd.m.Y' ),
                'date_raw'     => get_the_date( 'Y-m-d H:i:s' ),
                'section_slug' => $section_slug,
                'section_name' => $section_name,
                'type_slug'    => $type_slug,
                'type_label'   => $type_label,
                'status_slug'  => $status_slug,
                'status_label' => $status_label,
                'reading_time' => $reading_time ?: '',
                'persons'      => $persons,
            ];

            // Дополнительные поля для новостей (ACF)
            if ( $post_type === 'news' ) {
                $post_data['is_urgent'] = function_exists( 'get_field' ) ? (bool) get_field( 'is_urgent',   $post_id ) : false;
                $post_data['source']    = function_exists( 'get_field' ) ? (string) get_field( 'news_source', $post_id ) : '';
                $post_data['editor']    = function_exists( 'get_field' ) ? (string) get_field( 'news_editor', $post_id ) : '';
                $post_data['verified']  = function_exists( 'get_field' ) ? (bool) get_field( 'is_verified',  $post_id ) : false;
                $post_data['time']      = get_the_time( 'H:i' );
                $post_data['date_key']  = get_the_date( 'Y-m-d' );
            }

            $posts[] = $post_data;
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
// ЖИВОЙ ИНДЕКС — ФИЛЬТРАЦИЯ ВКЛАДОК
// ---------------------------------------------------------

add_action( 'wp_ajax_palime_live_index',        'palime_handle_live_index' );
add_action( 'wp_ajax_nopriv_palime_live_index', 'palime_handle_live_index' );

function palime_handle_live_index() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $tab = sanitize_key( $_POST['tab'] ?? 'newest' );

    $args = [
        'post_type'              => 'article',
        'posts_per_page'         => 10,
        'post_status'            => 'publish',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true,
    ];

    switch ( $tab ) {
        case 'popular':
            $args['orderby']  = 'comment_count';
            $args['order']    = 'DESC';
            break;

        case 'best':
            $args['meta_key'] = 'palime_likes';
            $args['orderby']  = 'meta_value_num';
            $args['order']    = 'DESC';
            break;

        case 'editor':
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            $args['tax_query'] = [ [
                'taxonomy' => 'editorial-flag',
                'field'    => 'slug',
                'terms'    => 'editors-choice',
            ] ];
            break;

        default: // newest
            $args['orderby'] = 'date';
            $args['order']   = 'DESC';
            break;
    }

    $query = new WP_Query( $args );
    $rows  = [];

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $pid = get_the_ID();

            $s_terms     = get_the_terms( $pid, 'section' );
            $medium      = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';
            $medium_slug = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->slug : '';

            $at   = get_the_terms( $pid, 'article-type' );
            $form = ( $at && ! is_wp_error( $at ) ) ? $at[0]->name : 'Статья';

            $min = function_exists( 'get_field' ) ? get_field( 'reading_time', $pid ) : '';

            $rows[] = [
                'id'          => 'PA-' . get_the_date( 'Y' ) . '-' . str_pad( $pid, 3, '0', STR_PAD_LEFT ),
                'title'       => get_the_title(),
                'url'         => get_permalink(),
                'medium'      => $medium,
                'medium_slug' => $medium_slug,
                'form'        => $form,
                'min'         => $min ? $min : '—',
                'date'        => get_the_date( 'd.m.Y' ),
                'status'      => 'NEW',
            ];
        }
        wp_reset_postdata();
    }

    while ( count( $rows ) < 10 ) {
        $rows[] = [
            'id'          => 'PA-——-———',
            'title'       => '———————————————',
            'url'         => '#',
            'medium'      => '——————',
            'medium_slug' => '',
            'form'        => '————',
            'min'         => '——',
            'date'        => '——.——.————',
            'status'      => '',
        ];
    }

    wp_send_json_success( [ 'rows' => $rows ] );
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