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
$terms_section  = get_terms( [ 'taxonomy' => 'section',          'hide_empty' => true ] );
$terms_type     = get_terms( [ 'taxonomy' => 'article-type',   'hide_empty' => true ] );
$terms_theme    = get_terms( [ 'taxonomy' => 'theme',          'hide_empty' => true ] );
$terms_era      = get_terms( [ 'taxonomy' => 'era',            'hide_empty' => true ] );
$terms_editorial = get_terms( [ 'taxonomy' => 'editorial-flag', 'hide_empty' => true ] );
$terms_status   = get_terms( [ 'taxonomy' => 'status',         'hide_empty' => true ] );
$terms_genre    = get_terms( [ 'taxonomy' => 'genre',          'hide_empty' => true ] );

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

        <!-- Правая часть: поиск (?q=) -->
        <div class="pa-archive-hero__search">
            <span class="pa-archive-hero__search-label">Текстовый поиск</span>
            <input
                type="search"
                name="q"
                id="pa-archive-search"
                class="pa-archive-hero__search-input"
                placeholder="Тарковский, Кафка, модернизм…"
                autocomplete="off"
                aria-label="Поиск по архиву"
                value="<?php echo esc_attr( sanitize_text_field( $_GET['q'] ?? '' ) ); ?>"
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

        <!-- РАЗДЕЛ — главный фильтр -->
        <div class="pa-filter-group pa-filter-group--primary">
            <span class="pa-filter-group__label">Раздел</span>
            <div class="pa-filter-tags pa-filter-tags--section" data-filter-group="section">
                <?php if ( $terms_section && ! is_wp_error( $terms_section ) ) :
                    foreach ( $terms_section as $t ) :
                        $css = $section_css[ $t->slug ] ?? '';
                    ?>
                    <button
                        type="button"
                        class="pa-filter-tag pa-filter-tag--lg <?php echo $css ? 'pa-filter-tag--' . esc_attr( $css ) : ''; ?>"
                        data-filter="section"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach;
                else : ?>
                    <p class="pa-filter-group__empty">Разделы не найдены. Создайте термины section в админке: cinema, lit, music, art.</p>
                <?php endif; ?>
            </div>
        </div>

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

        <!-- ТЕМЫ / МОТИВЫ -->
        <?php if ( $terms_theme && ! is_wp_error( $terms_theme ) ) :
            // Сортируем по количеству записей (популярные первыми)
            usort( $terms_theme, function( $a, $b ) { return $b->count - $a->count; } );
            $themes_visible = array_slice( $terms_theme, 0, 8 );
            $themes_rest    = array_slice( $terms_theme, 8 );
        ?>
        <div class="pa-filter-group">
            <span class="pa-filter-group__label">Темы</span>
            <div class="pa-filter-tags pa-filter-tags--theme" data-filter-group="theme">
                <?php foreach ( $themes_visible as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="theme"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; ?>
            </div>
            <?php if ( $themes_rest ) : ?>
            <details class="pa-filter-details pa-filter-details--inline">
                <summary class="pa-filter-details__summary">Ещё <?php echo count( $themes_rest ); ?></summary>
                <div class="pa-filter-tags pa-filter-tags--nested" data-filter-group="theme">
                    <?php foreach ( $themes_rest as $t ) : ?>
                        <button
                            type="button"
                            class="pa-filter-tag"
                            data-filter="theme"
                            data-value="<?php echo esc_attr( $t->slug ); ?>"
                            aria-pressed="false"
                        ><?php echo esc_html( $t->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            </details>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- ЖАНР (расширенный фильтр) -->
        <?php if ( $terms_genre && ! is_wp_error( $terms_genre ) ) :
            usort( $terms_genre, function( $a, $b ) { return $b->count - $a->count; } );
            $genre_visible = array_slice( $terms_genre, 0, 8 );
            $genre_rest    = array_slice( $terms_genre, 8 );
        ?>
        <details class="pa-filter-details">
            <summary class="pa-filter-details__summary">Жанр</summary>
            <div class="pa-filter-tags pa-filter-tags--genre" data-filter-group="genre">
                <?php foreach ( $genre_visible as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="genre"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; ?>
            </div>
            <?php if ( $genre_rest ) : ?>
            <details class="pa-filter-details pa-filter-details--inline">
                <summary class="pa-filter-details__summary">Ещё <?php echo count( $genre_rest ); ?></summary>
                <div class="pa-filter-tags pa-filter-tags--nested" data-filter-group="genre">
                    <?php foreach ( $genre_rest as $t ) : ?>
                        <button
                            type="button"
                            class="pa-filter-tag"
                            data-filter="genre"
                            data-value="<?php echo esc_attr( $t->slug ); ?>"
                            aria-pressed="false"
                        ><?php echo esc_html( $t->name ); ?></button>
                    <?php endforeach; ?>
                </div>
            </details>
            <?php endif; ?>
        </details>
        <?php endif; ?>

        <!-- РЕДАКТОРСКИЕ МЕТКИ (вторичный блок) -->
        <?php if ( $terms_editorial && ! is_wp_error( $terms_editorial ) ) : ?>
        <details class="pa-filter-details">
            <summary class="pa-filter-details__summary">Редакторские метки</summary>
            <div class="pa-filter-tags pa-filter-tags--nested" data-filter-group="editorial_flag">
                <?php foreach ( $terms_editorial as $t ) : ?>
                    <button
                        type="button"
                        class="pa-filter-tag"
                        data-filter="editorial_flag"
                        data-value="<?php echo esc_attr( $t->slug ); ?>"
                        aria-pressed="false"
                    ><?php echo esc_html( $t->name ); ?></button>
                <?php endforeach; ?>
            </div>
        </details>
        <?php endif; ?>

        <!-- СТАТУС (вторичный) -->
        <details class="pa-filter-details">
            <summary class="pa-filter-details__summary">Статус</summary>
            <div class="pa-filter-tags pa-filter-tags--nested" data-filter-group="status">
                <?php
                // Публичные термины (без служебного «редакция»)
                $status_exclude = [ 'redakciya', 'редакция' ];
                if ( $terms_status && ! is_wp_error( $terms_status ) ) :
                    foreach ( $terms_status as $t ) :
                        if ( in_array( $t->slug, $status_exclude, true ) ) continue;
                ?>
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
        </details>

        <!-- ЭПОХА (вторичный / по данным) -->
        <?php if ( $terms_era && ! is_wp_error( $terms_era ) ) : ?>
        <details class="pa-filter-details">
            <summary class="pa-filter-details__summary">Эпоха</summary>
            <div class="pa-filter-tags pa-filter-tags--nested" data-filter-group="era">
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
        </details>
        <?php endif; ?>

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
