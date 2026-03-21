<?php

// Palime Archive — inc/woocommerce.php

if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'WooCommerce' ) ) return;

// =========================================================
// КАСТОМИЗАЦИЯ WOOCOMMERCE
// =========================================================

// Убираем стандартные стили WooCommerce — используем свои
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

// Убираем хлебные крошки WooCommerce
add_filter( 'woocommerce_breadcrumb_defaults', '__return_empty_array' );

// Отключаем стандартный сайдбар WooCommerce
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

// Количество товаров на странице магазина
add_filter( 'loop_shop_per_page', function() {
    return 12;
} );

// Количество колонок в сетке товаров
add_filter( 'loop_shop_columns', function() {
    return 3;
} );

// =========================================================
// КАСТОМНЫЕ ПОЛЯ ПРОДУКТОВ (через ACF)
// =========================================================
// Поля добавляются через ACF UI и сохраняются в acf-json/:
// — section (раздел: cinema / lit / music / art)
// — monthly_theme (тема месяца)
// — issue_number (номер выпуска)
// — is_archived (bool — товар архивирован после распродажи)

/**
 * Архивировать товар после полной распродажи.
 * Вешается на хук завершения заказа.
 */
add_action( 'woocommerce_order_status_completed', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;

    foreach ( $order->get_items() as $item ) {
        $product_id = $item->get_product_id();
        $product    = wc_get_product( $product_id );

        if ( $product && ! $product->is_in_stock() ) {
            update_post_meta( $product_id, 'is_archived', true );
        }
    }
} );

// =========================================================
// ШАБЛОНЫ
// =========================================================

/**
 * Подключать шаблоны WooCommerce из папки темы woocommerce/
 * (создать папку при необходимости кастомизации вёрстки).
 */
add_filter( 'woocommerce_locate_template', function( $template, $template_name ) {
    $theme_template = get_template_directory() . '/woocommerce/' . $template_name;
    if ( file_exists( $theme_template ) ) {
        return $theme_template;
    }
    return $template;
}, 10, 2 );
