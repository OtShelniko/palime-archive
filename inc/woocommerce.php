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
// — section (раздел: cinema / lit / music / art) — канонический источник для темы
// — monthly_theme (тема месяца)
// — issue_number (номер выпуска)
// — is_archived (bool — товар архивирован после распродажи)
//
// Значение ACF `section` дублируется в post meta `_palime_section` для запросов WC API / блоков разделов.

/**
 * Синхронизация ACF section → meta _palime_section (для wc_get_products и единообразия).
 *
 * @param int $post_id ID товара.
 */
function palime_sync_product_section_meta( $post_id ) {
    if ( (int) $post_id <= 0 ) {
        return;
    }
    if ( get_post_type( $post_id ) !== 'product' ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! function_exists( 'get_field' ) ) {
        return;
    }
    $section = get_field( 'section', $post_id );
    if ( $section !== null && $section !== '' && false !== $section ) {
        update_post_meta( $post_id, '_palime_section', sanitize_text_field( (string) $section ) );
    } else {
        delete_post_meta( $post_id, '_palime_section' );
    }
}

// Синхронизируем только через acf/save_post — достаточно одного хука.
// save_post_product дублировал вызов и мог читать устаревшее значение ACF.
add_action( 'acf/save_post', function ( $post_id ) {
    palime_sync_product_section_meta( (int) $post_id );
}, 20 );

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
            // ACF хранит bool-поля под именем поля (is_archived).
            // Используем update_field, если ACF доступен, иначе fallback на post_meta.
            if ( function_exists( 'update_field' ) ) {
                update_field( 'is_archived', true, $product_id );
            } else {
                update_post_meta( $product_id, 'is_archived', 1 );
            }
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
