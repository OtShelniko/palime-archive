<?php

// Palime Archive — inc/user-profile.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// СТРАНИЦА ПРОФИЛЯ ПОЛЬЗОВАТЕЛЯ
// =========================================================

/**
 * Редирект на /profile если пользователь зашёл на /wp-admin
 * и не является администратором.
 */
add_action( 'admin_init', function() {
    if ( is_admin() && ! current_user_can( 'manage_options' ) && ! wp_doing_ajax() ) {
        wp_redirect( home_url( '/profile/' ) );
        exit;
    }
} );

/**
 * Редирект после логина — отправляем на /profile.
 */
add_filter( 'login_redirect', function( $redirect_to, $request, $user ) {
    if ( isset( $user->roles ) && ! in_array( 'administrator', $user->roles ) ) {
        return home_url( '/profile/' );
    }
    return $redirect_to;
}, 10, 3 );

/**
 * Редирект после WordPress-регистрации — на /profile.
 */
add_filter( 'registration_redirect', function() {
    return home_url( '/profile/' );
} );

/**
 * Получить данные профиля пользователя для отображения.
 *
 * @param int $user_id
 * @return array
 */
function palime_get_profile_data( $user_id ) {
    $user   = get_userdata( $user_id );
    $points = palime_get_points( $user_id );
    $level  = palime_get_user_level( $user_id );
    $log    = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];

    return [
        'user'        => $user,
        'points'      => $points,
        'level'       => $level,
        'log'         => array_reverse( array_slice( $log, -10 ) ), // последние 10 действий
        'saved'       => get_user_meta( $user_id, 'palime_saved_articles', true ) ?: [],
        'telegram_id' => get_user_meta( $user_id, 'telegram_id', true ),
    ];
}

/**
 * AJAX — сохранить / убрать статью из закладок.
 */
add_action( 'wp_ajax_palime_toggle_save', function() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $user_id    = get_current_user_id();
    $article_id = (int) $_POST['article_id'];

    if ( ! $user_id || ! $article_id ) {
        wp_send_json_error();
    }

    $saved = get_user_meta( $user_id, 'palime_saved_articles', true ) ?: [];

    if ( in_array( $article_id, $saved ) ) {
        $saved = array_values( array_diff( $saved, [ $article_id ] ) );
        $action = 'removed';
    } else {
        $saved[] = $article_id;
        $action  = 'saved';
        palime_add_points( $user_id, PALIME_XP_SAVE, 'Сохранение статьи', 'base' );
    }

    update_user_meta( $user_id, 'palime_saved_articles', $saved );
    wp_send_json_success( [ 'action' => $action, 'count' => count( $saved ) ] );
} );

/**
 * AJAX — начислить очки за шеринг (вызывается из JS после шера).
 */
add_action( 'wp_ajax_palime_track_share', function() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $user_id    = get_current_user_id();
    $article_id = (int) $_POST['article_id'];

    if ( ! $user_id ) {
        wp_send_json_error();
    }

    // Начисляем только один раз за статью
    $shared_key = 'shared_' . $article_id;
    if ( ! get_user_meta( $user_id, $shared_key, true ) ) {
        update_user_meta( $user_id, $shared_key, 1 );
        palime_add_points( $user_id, PALIME_XP_SHARE, 'Шеринг статьи', 'social' );
    }

    wp_send_json_success();
} );
