<?php
/**
 * Template Name: Архив / Поиск
 * Template Post Type: page
 *
 * Palime Archive — page-archive.php
 * Страница /archive — поиск и фильтрация по всему архиву.
 *
 * Фильтры работают через AJAX (filters.js → ajax.php → palime_filter_archive).
 * Начальный счётчик получаем из WP_Query.found_posts.
 *
 * @package Palime_Archive
 */

get_header();

// ── Общий счётчик всех статей ─────────────────────────────
$total_query = new WP_Query( [
    'post_type'              => 'article',
    'post_status'            => 'publish',
    'posts_per_page'         => 1,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
] );
$total_found = $total_query->found_posts;
wp_reset_postdata();

// ── Каталогизировано (статус VERIFIED) ───────────────────
$verified_query = new WP_Query( [
    'post_type'              => 'article',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'tax_query'              => [ [
        'taxonomy' => 'status',
        'field'    => 'slug',
        'terms'    => 'verified',
    ] ],
] );
$total_verified = $verified_query->found_posts;
wp_reset_postdata();

// ── Спорные (статус DISPUTED) ────────────────────────────
$disputed_query = new WP_Query( [
    'post_type'              => 'article',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
    'tax_query'              => [ [
        'taxonomy' => 'status',
        'field'    => 'slug',
        'terms'    => 'disputed',
    ] ],
] );
$total_disputed = $disputed_query->found_posts;
wp_reset_postdata();

// ── Термины для тег-фильтров ──────────────────────────────
$terms_section  = get_terms( [ 'taxonomy' => 'section',      'hide_empty' => true ] );
$terms_type     = get_terms( [ 'taxonomy' => 'article-type', 'hide_empty' => true ] );
$terms_era      = get_terms( [ 'taxonomy' => 'era',          'hide_empty' => true ] );
$terms_genre    = get_terms( [ 'taxonomy' => 'genre',        'hide_empty' => true ] );
$terms_status   = get_terms( [ 'taxonomy' => 'status',       'hide_empty' => true ] );

// Имена разделов для CSS-класса бейджа
$section_css = [
    'cinema' => 'cinema',
    'lit'    => 'lit',
    'music'  => 'music',
    'art'    => 'art',
];

// Текущий месяц на русском
$months_ru = [
    1=>'Январь',2=>'Февраль',3=>'Март',4=>'Апрель',5=>'Май',6=>'Июнь',
    7=>'Июль',8=>'Август',9=>'Сентябрь',10=>'Октябрь',11=>'Ноябрь',12=>'Декабрь',
];
$current_month = $months_ru[ (int) date( 'n' ) ] . ' ' . date( 'Y' );
?>

<!-- =====================================================
     HERO: большой счётчик + статистика + поиск
     ===================================================== -->
<div class="pa-archive-hero">
    <div class="pa-archive-hero__inner">

        <!-- Левая часть -->
        <div class="pa-archive-hero__left">
            <div class="pa-archive-hero__count">
                <span class="pa-archive-hero__number" id="pa-total-number">
                    <?php echo esc_html( number_format( $total_found, 0, '.', '&nbsp;' ) ); ?>
                </span>
                <span class="pa-archive-hero__label">ЗАПИСЕЙ</span>
            </div>
            <div class="pa-archive-hero__stats">
                Каталогизировано:&nbsp;<strong><?php echo esc_html( number_format( $total_verified, 0, '.', '&nbsp;' ) ); ?></strong>
                &nbsp;·&nbsp;
                Спорно:&nbsp;<strong><?php echo esc_html( $total_disputed ); ?></strong>
                &nbsp;·&nbsp;
                <strong><?php echo esc_html( $current_month ); ?></strong>
            </div>
        </div>

        <!-- Правая часть: поиск -->
        <div class="pa-archive-hero__search">
            <span class="pa-archive-hero__search-label">Текстовый поиск</span>
            <input
                type="search"
                id="pa-archive-search"
                class="pa-archive-hero__search-input"
                placeholder="Тарковский, Кафка, модернизм…"
                autocomplete="off"
                aria-label="Поиск по архиву"
            >
        </div>

    </div>
</div>
<!-- /HERO -->


<!-- =====================================================
     ОСНОВНОЙ ЛЕЙАУТ: САЙДБАР + РЕЗУЛЬТАТЫ
     ===================================================== -->
<div class="container pa-archive-body">

    <!-- ─────────────────────────────────────────────────
         ЛЕВЫЙ САЙДБАР — ФИЛЬТРЫ
         ───────────────────────────────────────────────── -->
    <aside class="pa-archive-sidebar archive-filters" id="archive-filters" aria-label="Фильтры архива">

        <!-- Активные фильтры-пилюли -->
        <div class="pa-active-filters" id="pa-active-filters" aria-live="polite"></div>

        <!-- ПЕРСОНА / АВТОР -->
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Персона / Автор</span>
            <div class="pa-filter-person">
                <input
                    type="text"
                    id="pa-person-input"
                    class="pa-filter-person__input archive-filters__person-input"
                    placeholder="Введите имя…"
                    autocomplete="off"
                    aria-autocomplete="list"
                    aria-controls="pa-person-suggestions"
                >
                <div class="pa-filter-person__suggestions archive-filters__suggestions"
                     id="pa-person-suggestions" role="listbox"></div>
            </div>
            <span class="pa-filter-person__hint">Кафка, Тарковский, Малер…</span>
        </div>

        <!-- РАЗДЕЛ -->
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Раздел</span>
            <div class="pa-filter-tags" data-filter-group="section">
                <?php if ( $terms_section && ! is_wp_error( $terms_section ) ) :
                    foreach ( $terms_section as $t ) :
                        $css = $section_css[ $t->slug ] ?? '';
                    ?>
                    <button
                        type="button"
                        class="pa-filter-tag <?php echo $css ? 'pa-filter-tag--' . esc_attr( $css ) : ''; ?>"
                        data-filter="section"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <!-- ТИП МАТЕРИАЛА -->
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Тип материала</span>
            <div class="pa-filter-tags" data-filter-group="type">
                <?php if ( $terms_type && ! is_wp_error( $terms_type ) ) :
                    foreach ( $terms_type as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="type"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; endif; ?>
                <?php if ( ! $terms_type || is_wp_error( $terms_type ) ) : ?>
                    <button type="button" class="pa-filter-tag" data-filter="type" data-value="author">Про автора</button>
                    <button type="button" class="pa-filter-tag" data-filter="type" data-value="work">Про произведение</button>
                    <button type="button" class="pa-filter-tag" data-filter="type" data-value="selection">Подборка</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- ЭПОХА -->
        <?php if ( $terms_era && ! is_wp_error( $terms_era ) ) : ?>
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Эпоха</span>
            <div class="pa-filter-tags" data-filter-group="era">
                <?php foreach ( $terms_era as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="era"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- ЖАНР -->
        <?php if ( $terms_genre && ! is_wp_error( $terms_genre ) ) : ?>
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Жанр</span>
            <div class="pa-filter-tags" data-filter-group="genre">
                <?php foreach ( $terms_genre as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="genre"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- СТАТУС -->
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Статус</span>
            <div class="pa-filter-tags" data-filter-group="status">
                <?php if ( $terms_status && ! is_wp_error( $terms_status ) ) :
                    foreach ( $terms_status as $t ) : ?>
                        <button
                            type="button"
                            class="pa-filter-tag"
                            data-filter="status"
                            data-value="<?php echo esc_attr( $t->slug ); ?>"
                            aria-pressed="false"
                        ><?php echo esc_html( $t->name ); ?></button>
                    <?php endforeach;
                else : ?>
                    <button type="button" class="pa-filter-tag" data-filter="status" data-value="verified">Подтверждено</button>
                    <button type="button" class="pa-filter-tag" data-filter="status" data-value="disputed">Спорно</button>
                    <button type="button" class="pa-filter-tag" data-filter="status" data-value="archived">В архиве</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Сброс всех фильтров -->
        <button type="button" class="pa-filter-reset" id="pa-filter-reset">
            — Сбросить фильтры —
        </button>

    </aside>
    <!-- /САЙДБАР -->


    <!-- ─────────────────────────────────────────────────
         ПРАВАЯ ЧАСТЬ — РЕЗУЛЬТАТЫ
         ───────────────────────────────────────────────── -->
    <section class="pa-archive-results" aria-label="Результаты поиска">

        <!-- Шапка: счётчик + сортировка -->
        <div class="pa-results-header">
            <p class="pa-results-found">
                Найдено:&nbsp;<strong id="pa-found-count"><?php echo esc_html( $total_found ); ?></strong>&nbsp;записей
            </p>
            <nav class="pa-sort-tabs" aria-label="Сортировка">
                <button type="button" class="pa-sort-tab is-active" data-sort="date">Новые</button>
                <button type="button" class="pa-sort-tab" data-sort="relevance">По теме</button>
                <button type="button" class="pa-sort-tab" data-sort="popular">Популярные</button>
            </nav>
        </div>

        <!-- Список результатов — заполняется через AJAX -->
        <div class="archive-results__grid" id="archive-results" aria-live="polite" aria-busy="true">
            <div class="pa-archive-empty">
                <span class="loader" style="display:inline-block; margin-right:8px;"></span>
                Загрузка…
            </div>
        </div>

    </section>
    <!-- /РЕЗУЛЬТАТЫ -->

</div>
<!-- /ОСНОВНОЙ ЛЕЙАУТ -->

<?php get_footer(); ?>
