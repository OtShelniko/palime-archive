<?php

// Palime Archive — inc/auth.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// АВТОРИЗАЦИЯ — Email / Telegram / VK
// =========================================================

// ---------------------------------------------------------
// РЕДИРЕКТ wp-login.php → /auth/
// Перехватываем стандартную страницу входа WordPress
// ---------------------------------------------------------

add_action( 'login_init', 'palime_redirect_wp_login' );

function palime_redirect_wp_login() {
    // Залогиненные администраторы — не трогаем, пусть WP обработает штатно
    if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
        return;
    }

    // Не трогаем AJAX, POST-запросы (обработка форм WP), logout, postpass
    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

    $allowed_actions = [
        'logout',
        'postpass',
        'lostpassword',
        'rp',
        'resetpass',
        'confirmaction',
    ];

    if ( in_array( $action, $allowed_actions, true ) ) {
        return;
    }

    // POST-запросы пропускаем (стандартная обработка WP)
    if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
        return;
    }

    // Страница авторизации
    $auth_url = home_url( '/auth/' );

    // Сохраняем redirect_to
    if ( ! empty( $_GET['redirect_to'] ) ) {
        $auth_url = add_query_arg( 'redirect_to', urlencode( $_GET['redirect_to'] ), $auth_url );
    }

    // Регистрация
    if ( $action === 'register' ) {
        $auth_url = add_query_arg( 'tab', 'register', $auth_url );
    }

    wp_safe_redirect( $auth_url );
    exit;
}

// ---------------------------------------------------------
// EMAIL — AJAX-вход
// ---------------------------------------------------------

add_action( 'wp_ajax_nopriv_palime_login', 'palime_handle_login' );

function palime_handle_login() {
    check_ajax_referer( 'palime_auth_nonce', 'nonce' );

    $log      = sanitize_text_field( $_POST['log'] ?? '' );
    $pwd      = $_POST['pwd'] ?? '';
    $remember = ! empty( $_POST['rememberme'] );
    $redirect = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url( '/profile/' );

    if ( empty( $log ) || empty( $pwd ) ) {
        wp_send_json_error( [ 'message' => 'Заполните все поля.' ] );
    }

    $user = wp_signon( [
        'user_login'    => $log,
        'user_password' => $pwd,
        'remember'      => $remember,
    ] );

    if ( is_wp_error( $user ) ) {
        $code = $user->get_error_code();
        if ( $code === 'invalid_username' || $code === 'invalid_email' ) {
            $message = 'Пользователь не найден.';
        } elseif ( $code === 'incorrect_password' ) {
            $message = 'Неверный пароль.';
        } else {
            $message = 'Ошибка входа. Попробуйте позже.';
        }
        wp_send_json_error( [ 'message' => $message ] );
    }

    wp_send_json_success( [ 'redirect' => $redirect ] );
}

// ---------------------------------------------------------
// EMAIL — AJAX-регистрация
// ---------------------------------------------------------

add_action( 'wp_ajax_nopriv_palime_register', 'palime_handle_register' );

function palime_handle_register() {
    check_ajax_referer( 'palime_auth_nonce', 'nonce' );

    if ( ! get_option( 'users_can_register' ) ) {
        wp_send_json_error( [ 'message' => 'Регистрация закрыта.' ] );
    }

    $login    = sanitize_user( $_POST['user_login'] ?? '' );
    $email    = sanitize_email( $_POST['user_email'] ?? '' );
    $pass     = $_POST['user_pass'] ?? '';
    $redirect = ! empty( $_POST['redirect_to'] ) ? esc_url_raw( $_POST['redirect_to'] ) : home_url( '/profile/' );

    if ( empty( $login ) || empty( $email ) || empty( $pass ) ) {
        wp_send_json_error( [ 'message' => 'Заполните все поля.' ] );
    }

    if ( strlen( $pass ) < 6 ) {
        wp_send_json_error( [ 'message' => 'Пароль должен быть не менее 6 символов.' ] );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Неверный формат email.' ] );
    }

    if ( username_exists( $login ) ) {
        wp_send_json_error( [ 'message' => 'Это имя пользователя уже занято.' ] );
    }

    if ( email_exists( $email ) ) {
        wp_send_json_error( [ 'message' => 'Этот email уже зарегистрирован.' ] );
    }

    $user_id = wp_create_user( $login, $pass, $email );

    if ( is_wp_error( $user_id ) ) {
        wp_send_json_error( [ 'message' => 'Ошибка регистрации: ' . $user_id->get_error_message() ] );
    }

    // Стартовые очки за регистрацию
    if ( function_exists( 'palime_add_points' ) && defined( 'PALIME_XP_REGISTER' ) ) {
        palime_add_points( $user_id, PALIME_XP_REGISTER, 'Регистрация', 'base' );
    }

    // Автологин после регистрации
    wp_set_auth_cookie( $user_id, true );

    wp_send_json_success( [ 'redirect' => $redirect ] );
}

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

// ---------------------------------------------------------
// TELEGRAM WIDGET — подключение скрипта на странице auth
// ---------------------------------------------------------

add_action( 'wp_footer', 'palime_render_telegram_widget_script' );

function palime_render_telegram_widget_script() {
    if ( ! is_page_template( 'page-auth.php' ) ) {
        return;
    }

    $bot_name = defined( 'PALIME_TELEGRAM_BOT_NAME' ) ? PALIME_TELEGRAM_BOT_NAME : '';
    if ( empty( $bot_name ) ) {
        return;
    }
    ?>
    <script>
    (function() {
        var wrap = document.getElementById('telegram-login-widget');
        if (!wrap) return;

        var script  = document.createElement('script');
        script.src  = 'https://telegram.org/js/telegram-widget.js?22';
        script.setAttribute('data-telegram-login', <?php echo wp_json_encode( $bot_name ); ?>);
        script.setAttribute('data-size', 'large');
        script.setAttribute('data-radius', '4');
        script.setAttribute('data-onauth', 'palimeTelegramAuth(user)');
        script.setAttribute('data-request-access', 'write');
        script.async = true;
        wrap.appendChild(script);
    })();
    </script>
    <?php
}
