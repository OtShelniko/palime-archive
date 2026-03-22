<?php

// Palime Archive — inc/auth.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// АВТОРИЗАЦИЯ — Email / Telegram / VK
// =========================================================

// ---------------------------------------------------------
// EMAIL — стандартная WordPress авторизация
// Кастомизируем только внешний вид страницы /wp-login.php
// ---------------------------------------------------------

// Заменяем логотип на странице логина
add_filter( 'login_headerurl', function() {
    return home_url();
} );

add_action( 'login_enqueue_scripts', function() {
    // TODO: подключить кастомный CSS для страницы логина
} );

// ---------------------------------------------------------
// TELEGRAM LOGIN
// Документация: https://core.telegram.org/widgets/login
// ---------------------------------------------------------

/**
 * Обработчик AJAX-авторизации через Telegram.
 * Telegram присылает данные пользователя + hash для проверки.
 */
add_action( 'wp_ajax_nopriv_palime_telegram_auth', 'palime_handle_telegram_auth' );

function palime_handle_telegram_auth() {
    check_ajax_referer( 'palime_auth_nonce', 'nonce' );

    $data = $_POST['telegram_data'] ?? [];

    if ( empty( $data['id'] ) || empty( $data['hash'] ) ) {
        wp_send_json_error( [ 'message' => 'Неверные данные Telegram' ] );
    }

    // Проверка подписи Telegram
    $bot_token  = defined( 'PALIME_TELEGRAM_BOT_TOKEN' ) ? PALIME_TELEGRAM_BOT_TOKEN : '';
    $check_hash = $data['hash'];
    unset( $data['hash'] );

    ksort( $data );
    $data_check_string = implode( "\n", array_map(
        fn( $k, $v ) => "$k=$v",
        array_keys( $data ),
        array_values( $data )
    ) );

    $secret_key    = hash( 'sha256', $bot_token, true );
    $expected_hash = hash_hmac( 'sha256', $data_check_string, $secret_key );

    if ( ! hash_equals( $expected_hash, $check_hash ) ) {
        wp_send_json_error( [ 'message' => 'Подпись не прошла проверку' ] );
    }

    // Проверка свежести данных (не старше 24 часов)
    if ( ( time() - (int) $data['auth_date'] ) > 86400 ) {
        wp_send_json_error( [ 'message' => 'Данные устарели' ] );
    }

    $telegram_id = (int) $data['id'];

    // Ищем существующего пользователя по telegram_id
    $users = get_users( [
        'meta_key'   => 'telegram_id',
        'meta_value' => $telegram_id,
        'number'     => 1,
    ] );

    if ( $users ) {
        $user = $users[0];
    } else {
        // Создаём нового пользователя
        $username = sanitize_user( $data['username'] ?? 'tg_' . $telegram_id );
        if ( username_exists( $username ) ) {
            $username .= '_' . $telegram_id;
        }

        $user_id = wp_create_user(
            $username,
            wp_generate_password(),
            $telegram_id . '@telegram.palimearchive.com'
        );

        if ( is_wp_error( $user_id ) ) {
            wp_send_json_error( [ 'message' => 'Ошибка создания пользователя' ] );
        }

        update_user_meta( $user_id, 'telegram_id', $telegram_id );
        update_user_meta( $user_id, 'first_name', sanitize_text_field( $data['first_name'] ?? '' ) );
        update_user_meta( $user_id, 'last_name',  sanitize_text_field( $data['last_name']  ?? '' ) );

        // Стартовые очки
        palime_add_points( $user_id, PALIME_XP_TELEGRAM, 'Подписка Telegram', 'social' );

        $user = get_user_by( 'id', $user_id );
    }

    wp_set_auth_cookie( $user->ID, true );
    wp_send_json_success( [ 'redirect' => home_url( '/profile/' ) ] );
}

// ---------------------------------------------------------
// VK LOGIN
// TODO: реализовать через VK OAuth API
// Документация: https://id.vk.com/about/business/go
// ---------------------------------------------------------

add_action( 'wp_ajax_nopriv_palime_vk_auth', function() {
    // Заглушка — реализовать в Этапе 9
    wp_send_json_error( [ 'message' => 'VK авторизация в разработке' ] );
} );
