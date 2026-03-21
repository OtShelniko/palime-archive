<?php
// Palime Archive — functions.php
// Только подключение. Вся логика в inc/

if ( ! defined( 'ABSPATH' ) ) exit;

// Базовые настройки
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';

// Контент
require_once get_template_directory() . '/inc/post-types.php';
require_once get_template_directory() . '/inc/taxonomies.php';
require_once get_template_directory() . '/inc/taxonomy-seed.php';
require_once get_template_directory() . '/inc/acf.php';

// Утилиты
require_once get_template_directory() . '/inc/helpers.php';
require_once get_template_directory() . '/inc/theme-options.php';

// Магазин
require_once get_template_directory() . '/inc/woocommerce.php';

// Пользователи
require_once get_template_directory() . '/inc/auth.php';
require_once get_template_directory() . '/inc/user-profile.php';
require_once get_template_directory() . '/inc/user-points.php';

// API и AJAX
require_once get_template_directory() . '/inc/ajax.php';
require_once get_template_directory() . '/inc/api.php';