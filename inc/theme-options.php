<?php

// Palime Archive — inc/theme-options.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// НАСТРОЙКИ ТЕМЫ В АДМИНКЕ
// =========================================================

/**
 * Добавляем страницу настроек темы в меню WordPress.
 */
function palime_add_theme_options_page() {
    add_menu_page(
        'Palime Archive — Настройки',
        'Palime',
        'manage_options',
        'palime-options',
        'palime_render_options_page',
        'dashicons-archive',
        3
    );
}
add_action( 'admin_menu', 'palime_add_theme_options_page' );

/**
 * Регистрируем поля настроек.
 */
function palime_register_settings() {
    register_setting( 'palime_options_group', 'palime_telegram_url' );
    register_setting( 'palime_options_group', 'palime_vk_url' );
    register_setting( 'palime_options_group', 'palime_unisender_key' );
    register_setting( 'palime_options_group', 'palime_yandex_metrika_id' );
    register_setting( 'palime_options_group', 'palime_ga_id' );
}
add_action( 'admin_init', 'palime_register_settings' );

/**
 * Шаблон страницы настроек.
 */
function palime_render_options_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;
    ?>
    <div class="wrap">
        <h1>Palime Archive — Настройки</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'palime_options_group' ); ?>
            <table class="form-table">
                <tr>
                    <th>Ссылка на Telegram-канал</th>
                    <td><input type="url" name="palime_telegram_url" value="<?php echo esc_attr( get_option( 'palime_telegram_url' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Ссылка на VK</th>
                    <td><input type="url" name="palime_vk_url" value="<?php echo esc_attr( get_option( 'palime_vk_url' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Unisender API ключ</th>
                    <td><input type="text" name="palime_unisender_key" value="<?php echo esc_attr( get_option( 'palime_unisender_key' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>ID Яндекс Метрики</th>
                    <td><input type="text" name="palime_yandex_metrika_id" value="<?php echo esc_attr( get_option( 'palime_yandex_metrika_id' ) ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th>Google Analytics ID</th>
                    <td><input type="text" name="palime_ga_id" value="<?php echo esc_attr( get_option( 'palime_ga_id' ) ); ?>" placeholder="G-XXXXXXXXXX" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button( 'Сохранить настройки' ); ?>
        </form>
    </div>
    <?php
}
