<?php
/**
 * Palime Archive — template-parts/cards/card-product.php
 * Карточка товара WooCommerce для страницы магазина
 *
 * Использование:
 *   get_template_part( 'template-parts/cards/card', 'product', [
 *       'product' => $wc_product,   // WC_Product object
 *   ] );
 *
 * ACF поля продукта:
 *   section        — раздел: cinema / lit / music / art
 *   monthly_theme  — тема месяца (строка)
 *   issue_number   — номер выпуска (число)
 *   is_archived    — bool, архивирован ли товар
 *
 * @package Palime_Archive
 */

/** @var WC_Product $product */
$product = $args['product'] ?? null;
if ( ! $product instanceof WC_Product ) {
    // Попытка получить из глобального $post
    global $product;
    if ( ! $product instanceof WC_Product ) {
        $product = wc_get_product( get_the_ID() );
    }
}
if ( ! $product ) return;

$product_id  = $product->get_id();
$permalink   = $product->get_permalink();
$name        = $product->get_name();
$desc        = $product->get_short_description();
$price_html  = $product->get_price();
$in_stock    = $product->is_in_stock();
$max_qty     = (int) ( $product->get_meta( '_stock' ) ?: $product->get_stock_quantity() );
$stock_qty   = (int) $product->get_stock_quantity();
$sold        = $max_qty - max( $stock_qty, 0 );

// Раздел: ACF `section` — канон; meta `_palime_section` синхронизируется в inc/woocommerce.php
$section = function_exists( 'get_field' ) ? get_field( 'section', $product_id ) : '';
if ( ! $section ) {
    $section = get_post_meta( $product_id, '_palime_section', true );
}
$section = is_string( $section ) ? $section : '';
$is_archived = function_exists( 'get_field' ) ? get_field( 'is_archived', $product_id )  : false;
$issue_num   = function_exists( 'get_field' ) ? get_field( 'issue_number', $product_id ) : '';

// Раздел → метка + CSS-класс
$section_labels = [
    'cinema' => 'Кино',
    'lit'    => 'Литература',
    'music'  => 'Музыка',
    'art'    => 'ИЗО',
];
$section_label = $section_labels[ $section ] ?? strtoupper( $section );
$badge_class   = $section ? 'pa-product-card__section-badge--' . esc_attr( $section ) : '';

// Счётчик: 001 / 050
$max_display  = $max_qty  ? str_pad( $max_qty,  3, '0', STR_PAD_LEFT ) : '---';
$sold_display = str_pad( $sold, 3, '0', STR_PAD_LEFT );

// Цена
$price_formatted = $price_html
    ? number_format( (float) $price_html, 0, '.', ' ' ) . ' ₽'
    : '—';

// Состояние
$is_soldout  = ! $in_stock && ! $is_archived;
$card_class  = $is_archived ? 'pa-product-card pa-product-card--archived' : 'pa-product-card';
?>

<article class="<?php echo esc_attr( $card_class ); ?>">

    <!-- Изображение -->
    <div class="pa-product-card__img-wrap">

        <?php if ( $section_label ) : ?>
            <span class="pa-product-card__section-badge <?php echo esc_attr( $badge_class ); ?>">
                <?php echo esc_html( $section_label ); ?>
            </span>
        <?php endif; ?>

        <span class="pa-product-card__counter">
            <?php echo esc_html( $sold_display ); ?> / <?php echo esc_html( $max_display ); ?>
        </span>

        <a href="<?php echo esc_url( $permalink ); ?>" tabindex="<?php echo $is_archived ? '-1' : '0'; ?>">
            <?php if ( $product->get_image_id() ) : ?>
                <?php echo $product->get_image( 'medium', [ 'alt' => esc_attr( $name ) ] ); ?>
            <?php else : ?>
                <img src="https://placehold.co/300x300/111111/333?text=<?php echo urlencode( $section_label ?: 'PA' ); ?>"
                     alt="<?php echo esc_attr( $name ); ?>" width="300" height="300">
            <?php endif; ?>
        </a>

    </div>
    <!-- /Изображение -->

    <!-- Текст -->
    <div class="pa-product-card__body">

        <h3 class="pa-product-card__name">
            <a href="<?php echo esc_url( $permalink ); ?>" style="color:inherit;text-decoration:none;">
                <?php echo esc_html( $name ); ?>
            </a>
        </h3>

        <?php if ( $desc ) : ?>
            <p class="pa-product-card__desc">
                <?php echo esc_html( wp_trim_words( wp_strip_all_tags( $desc ), 14, '…' ) ); ?>
            </p>
        <?php endif; ?>

        <!-- Покупка -->
        <div class="pa-product-card__purchase">
            <div class="pa-product-card__price-row">
                <div class="pa-product-card__price-meta">
                    <?php if ( $is_soldout || $is_archived ) : ?>
                        <span class="pa-product-card__label" style="color:rgba(217,21,21,.6);">Распродано</span>
                    <?php else : ?>
                        <span class="pa-product-card__label">Осталось</span>
                    <?php endif; ?>
                    <span class="pa-product-card__stock-count">
                        <?php echo esc_html( max( $stock_qty, 0 ) ); ?> / <?php echo esc_html( $max_qty ); ?>
                    </span>
                </div>
                <div class="pa-product-card__price">
                    <?php echo esc_html( $price_formatted ); ?>
                    <span><?php echo esc_html( $section_label ); ?></span>
                </div>
            </div>

            <?php if ( $is_archived ) : ?>
                <!-- Архивирован — кнопка скрыта через CSS -->
            <?php elseif ( $is_soldout ) : ?>
                <span class="pa-product-card__btn pa-product-card__btn--soldout">
                    Распродано
                </span>
            <?php else : ?>
                <a href="<?php echo esc_url( $permalink ); ?>"
                   class="pa-product-card__btn"
                   data-product-id="<?php echo esc_attr( $product_id ); ?>">
                    Купить →
                </a>
            <?php endif; ?>
        </div>

    </div>
    <!-- /Текст -->

</article>
