<?php

// Palime Archive — inc/acf.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// ACF — подключение и настройки
// =========================================================

// Если ACF Pro не установлен — не падаем
if ( ! class_exists( 'ACF' ) ) return;

// ACF JSON: сохранение и загрузка — дублируем из setup.php для надёжности
// (setup.php регистрирует пути через after_setup_theme,
//  этот файл страхует если порядок подключения изменится)

add_filter( 'acf/settings/save_json', function() {
    return get_template_directory() . '/acf-json';
} );

add_filter( 'acf/settings/load_json', function( $paths ) {
    $paths[] = get_template_directory() . '/acf-json';
    return $paths;
} );

// Скрываем меню ACF на продакшне (раскомментировать после разработки)
// add_filter( 'acf/settings/show_admin', '__return_false' );
