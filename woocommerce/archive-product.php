<?php
/**
 * Palime Archive — woocommerce/archive-product.php
 * Кастомный шаблон страницы магазина /shop
 *
 * Переопределяет стандартный WooCommerce archive-product.php.
 * Подключается через фильтр woocommerce_locate_template в inc/woocommerce.php.
 *
 * Структура:
 *   1. Хедер магазина (локальная навигация)
 *   2. Текущий дроп: айдентика выпуска + метаданные
 *   3. Сетка товаров текущего выпуска (4 колонки)
 *   4. Архив прошлых выпусков
 *
 * ACF поля продукта:
 *   section        — cinema | lit | music | art
 *   monthly_theme  — тема месяца
 *   issue_number   — номер выпуска
 *   is_archived    — bool
 *
 * @package Palime_Archive
 */

defined( 'ABSPATH' ) || exit;

get_header();

// =========================================================
// ДАННЫЕ ТЕКУЩЕГО ВЫПУСКА
// берём из первого НЕ-архивного продукта
// =========================================================

$current_products_query = new WP_Query( [
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'meta_query'     => [ [
        'key'     => 'is_archived',
        'compare' => 'NOT EXISTS',
    ] ],
] );

// Если первый запрос вернул 0 — берём всё и фильтруем на лету
if ( ! $current_products_query->have_posts() ) {
    $current_products_query = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'meta_query'     => [ [
            'key'     => 'is_archived',
            'value'   => '1',
            'compare' => '!=',
        ] ],
    ] );
}

// ACF-данные из первого товара
$first_product_id = 0;
$issue_number     = '';
$monthly_theme    = '';
$issue_date       = '';
$avail_until      = '';
$total_items      = 50; // дефолт тираж
$theme_tag        = '';

$current_ids = [];

if ( $current_products_query->have_posts() ) {
    foreach ( $current_products_query->posts as $p ) {
        $current_ids[] = $p->ID;
        if ( ! $first_product_id ) {
            $first_product_id = $p->ID;
            if ( function_exists( 'get_field' ) ) {
                $issue_number  = get_field( 'issue_number', $p->ID );
                $monthly_theme = get_field( 'monthly_theme', $p->ID );
                $avail_until   = get_field( 'available_until', $p->ID ); // ACF date field
                $total_items   = (int) ( get_field( 'issue_total_qty', $p->ID ) ?: 50 );
                $theme_tag     = get_field( 'theme_tag', $p->ID );
            }
            $issue_date = get_the_date( 'F Y', $p->ID );
        }
    }
}

// Архивные продукты
$archived_query = new WP_Query( [
    'post_type'      => 'product',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [ [
        'key'     => 'is_archived',
        'value'   => '1',
        'compare' => '=',
    ] ],
] );

// Форматирование номера выпуска
$issue_label = $issue_number
    ? '# ' . str_pad( $issue_number, 2, '0', STR_PAD_LEFT ) . ( $issue_date ? ' · ' . mb_strtoupper( $issue_date ) : '' )
    : 'Текущий выпуск';

// Название коллекции (из заголовка страницы или theme option)
$collection_title = get_option( 'palime_current_collection_title' );
if ( ! $collection_title ) {
    $collection_title = $monthly_theme
        ? 'Коллекция: ' . mb_strtoupper( $monthly_theme )
        : 'Текущая коллекция';
}

$collection_subtitle = get_option( 'palime_current_collection_subtitle' )
    ?: 'Лимитированные артефакты. Одна тема — четыре языка.';

$avail_until_fmt = '';
if ( $avail_until ) {
    $ts = strtotime( $avail_until );
    $avail_until_fmt = $ts ? date_i18n( 'j F Y', $ts ) : $avail_until;
}
?>

<div class="pa-shop">

    <!-- ====================================================
         1. ХЕДЕР МАГАЗИНА
         ==================================================== -->
    <div class="pa-shop-nav">
        <div class="pa-shop-nav__inner">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pa-shop-nav__logo">
                Palime Archive
            </a>
            <ul class="pa-shop-nav__links" role="list">
                <li>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"
                       class="pa-shop-nav__link is-active">
                        Магазин
                    </a>
                </li>
                <li>
                    <a href="#pa-archive" class="pa-shop-nav__link">
                        Архив дропов
                    </a>
                </li>
                <li>
                    <a href="<?php echo esc_url( home_url( '/delivery/' ) ); ?>"
                       class="pa-shop-nav__link">
                        Доставка
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- /хедер магазина -->


    <!-- ====================================================
         2. ТЕКУЩИЙ ДРО П — АЙДЕНТИКА ВЫПУСКА
         ==================================================== -->
    <div class="pa-drop">

        <p class="pa-drop__eyebrow">
            Выпуск <?php echo esc_html( $issue_label ); ?>
        </p>

        <h1 class="pa-drop__title">
            <?php echo esc_html( $collection_title ); ?>
        </h1>

        <p class="pa-drop__subtitle">
            <?php echo esc_html( $collection_subtitle ); ?>
        </p>

        <!-- Метаданные: Тема / Тираж / Доступно до -->
        <div class="pa-drop__meta-grid">

            <div class="pa-drop__meta-cell">
                <p class="pa-drop__meta-label">Тема выпуска</p>
                <div class="pa-drop__meta-value">
                    <?php if ( $monthly_theme ) : ?>
                        <?php
                        $theme_desc = get_option( 'palime_current_theme_desc' );
                        if ( $theme_desc ) :
                            echo '<p>' . esc_html( $theme_desc ) . '</p>';
                        else :
                            echo '<p>' . esc_html( $monthly_theme ) . '</p>';
                        endif;
                        ?>
                    <?php else : ?>
                        <p style="opacity:.35;">— тема будет указана —</p>
                    <?php endif; ?>
                    <?php if ( $theme_tag ) : ?>
                        <span class="pa-drop__meta-tag"><?php echo esc_html( $theme_tag ); ?></span>
                    <?php elseif ( $monthly_theme ) : ?>
                        <span class="pa-drop__meta-tag"><?php echo esc_html( mb_strtoupper( $monthly_theme ) ); ?></span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="pa-drop__meta-cell">
                <p class="pa-drop__meta-label">Тираж</p>
                <div class="pa-drop__meta-value">
                    <strong><?php echo esc_html( $total_items ); ?> штук</strong>
                    каждая позиция
                </div>
            </div>

            <div class="pa-drop__meta-cell">
                <p class="pa-drop__meta-label">Доступно до</p>
                <div class="pa-drop__meta-value">
                    <?php if ( $avail_until_fmt ) : ?>
                        <strong><?php echo esc_html( $avail_until_fmt ); ?></strong>
                    <?php else : ?>
                        <strong style="opacity:.35;">— —</strong>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
    <!-- /текущий дроп -->


    <!-- ====================================================
         3. СЕТКА ТОВАРОВ ТЕКУЩЕГО ВЫПУСКА
         ==================================================== -->
    <div class="pa-products">

        <p class="pa-products__eyebrow">
            Текущий выпуск — <?php echo esc_html( count( $current_ids ) ); ?> раздела<?php echo count( $current_ids ) > 4 ? '…' : ' / Одна тема'; ?>
        </p>

        <?php if ( ! empty( $current_ids ) ) : ?>
            <div class="pa-products__grid">
                <?php
                foreach ( $current_ids as $pid ) {
                    $wc_product = wc_get_product( $pid );
                    if ( $wc_product ) {
                        get_template_part( 'template-parts/cards/card', 'product', [
                            'product' => $wc_product,
                        ] );
                    }
                }
                ?>
            </div>
        <?php else : ?>
            <p style="font-family:var(--font-mono); font-size:.72rem; color:rgba(255,255,255,.25); letter-spacing:.1em; padding:40px 0;">
                — Товары текущего выпуска появятся здесь —
            </p>
        <?php endif; ?>

    </div>
    <!-- /сетка товаров -->


    <!-- ====================================================
         4. АРХИВ ВЫПУСКОВ
         ==================================================== -->
    <div class="pa-shop-archive" id="pa-archive">

        <p class="pa-shop-archive__header">Архив выпусков</p>

        <?php if ( $archived_query->have_posts() ) : ?>

            <?php
            // Группируем по issue_number
            $archive_issues = [];
            foreach ( $archived_query->posts as $ap ) {
                $issue_n = function_exists( 'get_field' ) ? (int) get_field( 'issue_number', $ap->ID ) : 0;
                $key     = $issue_n ?: $ap->ID;
                if ( ! isset( $archive_issues[ $key ] ) ) {
                    $archive_issues[ $key ] = [
                        'issue_number'  => $issue_n,
                        'monthly_theme' => function_exists( 'get_field' ) ? get_field( 'monthly_theme', $ap->ID ) : '',
                        'date'          => get_the_date( 'F Y', $ap->ID ),
                        'thumbnail_id'  => get_post_thumbnail_id( $ap->ID ),
                        'products'      => [],
                    ];
                }
                $archive_issues[ $key ]['products'][] = $ap->ID;
            }
            krsort( $archive_issues ); // новые сначала
            ?>

            <div class="pa-shop-archive__grid">
                <?php foreach ( $archive_issues as $data ) :
                    $a_issue = $data['issue_number'];
                    $a_theme = $data['monthly_theme'];
                    $a_date  = $data['date'];
                    $a_sold  = count( $data['products'] );
                    $a_total = 0;
                    $a_stock_total = 0;
                    foreach ( $data['products'] as $apid ) {
                        $ap = wc_get_product( $apid );
                        if ( $ap ) {
                            $a_total       += (int) ( get_post_meta( $apid, '_stock', true ) ?: 0 );
                            $a_stock_total += (int) $ap->get_stock_quantity();
                        }
                    }
                    $is_fully_soldout = ( $a_stock_total <= 0 );
                ?>
                    <div class="pa-archive-card">

                        <div class="pa-archive-card__thumb">
                            <?php if ( $data['thumbnail_id'] ) : ?>
                                <?php echo wp_get_attachment_image( $data['thumbnail_id'], 'medium', false, [ 'alt' => esc_attr( $a_theme ) ] ); ?>
                            <?php else : ?>
                                <span style="font-family:var(--font-mono); font-size:.6rem; color:rgba(255,255,255,.15); letter-spacing:.1em;">
                                    #<?php echo $a_issue ? str_pad( $a_issue, 2, '0', STR_PAD_LEFT ) : '—'; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="pa-archive-card__name">
                            Коллекция: <?php echo esc_html( $a_theme ?: '—' ); ?>
                        </p>

                        <?php if ( $a_date ) : ?>
                            <p class="pa-archive-card__theme">
                                тема: <?php echo esc_html( strtolower( $a_theme ?: '—' ) ); ?>
                            </p>
                        <?php endif; ?>

                        <p class="pa-archive-card__status <?php echo $is_fully_soldout ? 'pa-archive-card__status--soldout' : ''; ?>">
                            <?php if ( $is_fully_soldout ) : ?>
                                Распродано — <?php echo esc_html( $a_sold * 50 ); ?>/<?php echo esc_html( $a_sold * 50 ); ?>
                            <?php else : ?>
                                Осталось — <?php echo esc_html( max( $a_stock_total, 0 ) ); ?>/<?php echo esc_html( $a_sold * 50 ); ?>
                            <?php endif; ?>
                        </p>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php else : ?>
            <p style="font-family:var(--font-mono); font-size:.7rem; color:rgba(255,255,255,.2); letter-spacing:.1em; padding:24px 0;">
                — Архив пуст. Прошлые выпуски появятся здесь после распродажи —
            </p>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
    <!-- /архив выпусков -->

</div><!-- /.pa-shop -->

<?php get_footer(); ?>
