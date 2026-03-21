<?php
/**
 * Template Name: Новости
 *
 * Страница СОБЫТИЯ — список новостей, сгруппированных по дате,
 * с фильтрами разделов и сортировкой.
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

// ------------------------------------------------------------------
// Параметры из GET
// ------------------------------------------------------------------
$current_section = sanitize_key( $_GET['section'] ?? '' );
$current_sort    = in_array( $_GET['sort'] ?? '', [ 'fresh', 'section' ], true )
                   ? sanitize_key( $_GET['sort'] )
                   : 'fresh';

// ------------------------------------------------------------------
// WP_Query — новости
// ------------------------------------------------------------------
$tax_query = [];
if ( $current_section ) {
    $tax_query[] = [
        'taxonomy' => 'section',
        'field'    => 'slug',
        'terms'    => $current_section,
    ];
}

$orderby = 'date';
$order   = 'DESC';
if ( $current_sort === 'section' ) {
    $orderby = 'meta_value';
}

$query = new WP_Query( [
    'post_type'              => 'news',
    'posts_per_page'         => 30,
    'orderby'                => $orderby,
    'order'                  => $order,
    'tax_query'              => $tax_query ?: [],
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
] );

// Группируем по дате (Y-m-d)
$news_by_date = [];
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $date_key = get_the_date( 'Y-m-d' );
        $news_by_date[ $date_key ][] = [
            'id'        => get_the_ID(),
            'title'     => get_the_title(),
            'permalink' => get_permalink(),
            'time'      => get_the_time( 'H:i' ),
            'sections'  => get_the_terms( get_the_ID(), 'section' ),
            'is_urgent' => function_exists( 'get_field' ) ? (bool) get_field( 'is_urgent' ) : false,
            'source'    => function_exists( 'get_field' ) ? get_field( 'news_source' )    : '',
            'editor'    => function_exists( 'get_field' ) ? get_field( 'news_editor' )    : '',
            'verified'  => function_exists( 'get_field' ) ? (bool) get_field( 'is_verified' ) : false,
        ];
    }
    wp_reset_postdata();
}

// Заголовок даты на русском
function palime_news_date_label( string $date_key ): string {
    $ru_months = [
        1  => 'января', 2  => 'февраля', 3  => 'марта',    4  => 'апреля',
        5  => 'мая',    6  => 'июня',    7  => 'июля',     8  => 'августа',
        9  => 'сентября', 10 => 'октября', 11 => 'ноября', 12 => 'декабря',
    ];
    $ts    = strtotime( $date_key );
    $today = date( 'Y-m-d' );
    $yest  = date( 'Y-m-d', strtotime( '-1 day' ) );

    if ( $date_key === $today ) {
        $prefix = 'Сегодня';
    } elseif ( $date_key === $yest ) {
        $prefix = 'Вчера';
    } else {
        $day_of_week = [
            'Monday'    => 'Понедельник',
            'Tuesday'   => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday'  => 'Четверг',
            'Friday'    => 'Пятница',
            'Saturday'  => 'Суббота',
            'Sunday'    => 'Воскресенье',
        ];
        $prefix = $day_of_week[ date( 'l', $ts ) ] ?? '';
    }

    $d = (int) date( 'j', $ts );
    $m = (int) date( 'n', $ts );
    $y = (int) date( 'Y', $ts );

    return $prefix . ', ' . $d . ' ' . $ru_months[ $m ] . ' ' . $y;
}

$section_slugs = [
    'cinema'     => 'cinema',
    'literature' => 'lit',
    'music'      => 'music',
    'art'        => 'art',
];

$section_labels = [
    'cinema'     => 'Кино',
    'literature' => 'Литература',
    'music'      => 'Музыка',
    'art'        => 'ИЗО',
];
?>

<main class="pa-news" id="main" data-section="news">

    <div class="container">

        <?php /* ---- Герой: заголовок слева + фильтры разделов справа ---- */ ?>
        <div class="pa-news-hero">
            <div class="pa-news-hero__left">
                <p class="pa-news-hero__label">Новости</p>
                <h1 class="pa-news-hero__title">События</h1>
            </div>

            <div class="pa-news-hero__right">
                <div class="pa-news-section-filters" role="group" aria-label="Фильтр по разделу">
                    <?php foreach ( $section_labels as $slug => $label ) :
                        $mod       = $section_slugs[ $slug ] ?? '';
                        $is_active = $current_section === $slug;
                    ?>
                        <button
                            class="pa-news-section-filter<?php echo $mod ? " pa-news-section-filter--{$mod}" : ''; ?><?php echo $is_active ? ' is-active' : ''; ?>"
                            data-section="<?php echo esc_attr( $slug ); ?>"
                            aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                        ><?php echo esc_html( $label ); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div><!-- /.pa-news-hero -->

        <?php /* ---- Подзаголовок-навигация ---- */ ?>
        <nav class="pa-news-subnav" aria-label="Навигация по разделам">
            <?php foreach ( $section_labels as $slug => $label ) :
                $is_active = $current_section === $slug;
            ?>
                <button
                    class="pa-news-subnav__item<?php echo $is_active ? ' is-active' : ''; ?>"
                    data-section="<?php echo esc_attr( $slug ); ?>"
                    aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                ><?php echo esc_html( $label ); ?></button>
                <?php if ( $slug !== 'art' ) : ?>
                    <span class="pa-news-subnav__sep" aria-hidden="true">·</span>
                <?php endif; ?>
            <?php endforeach; ?>
        </nav>

        <?php /* ---- Список новостей, сгруппированных по дате ---- */ ?>
        <div class="pa-news-body" id="pa-news-results">

            <?php if ( ! empty( $news_by_date ) ) :
                $first_group = true;
                foreach ( $news_by_date as $date_key => $items ) : ?>

                    <div class="pa-date-group">

                        <div class="pa-date-group__header">
                            <span class="pa-date-group__label">
                                <?php echo esc_html( palime_news_date_label( $date_key ) ); ?>
                            </span>

                            <?php if ( $first_group ) : ?>
                                <div class="pa-date-group__sort" role="group" aria-label="Сортировка новостей">
                                    <button
                                        class="pa-date-group__sort-btn<?php echo $current_sort === 'fresh'   ? ' is-active' : ''; ?>"
                                        data-sort="fresh">Свежие</button>
                                    <button
                                        class="pa-date-group__sort-btn<?php echo $current_sort === 'section' ? ' is-active' : ''; ?>"
                                        data-sort="section">По разделу</button>
                                </div>
                            <?php endif; $first_group = false; ?>
                        </div>

                        <ul class="pa-news-list" role="list">
                            <?php foreach ( $items as $item ) :
                                $sections    = ! is_wp_error( $item['sections'] ) && $item['sections'] ? $item['sections'] : [];
                                $sec_obj     = $sections ? $sections[0] : null;
                                $sec_slug    = $sec_obj ? $sec_obj->slug : '';
                                $sec_name    = $sec_obj ? $sec_obj->name : '';
                                $sec_mod     = $section_slugs[ $sec_slug ] ?? '';
                            ?>
                                <li role="listitem">
                                    <a class="pa-news-item" href="<?php echo esc_url( $item['permalink'] ); ?>">

                                        <div class="pa-news-item__left">
                                            <div class="pa-news-item__badges">
                                                <?php if ( $sec_name ) : ?>
                                                    <span class="pa-news-item__section<?php echo $sec_mod ? " pa-news-item__section--{$sec_mod}" : ''; ?>">
                                                        <?php echo esc_html( $sec_name ); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ( $item['is_urgent'] ) : ?>
                                                    <span class="pa-news-item__urgent">Срочно</span>
                                                <?php endif; ?>
                                            </div>

                                            <h2 class="pa-news-item__title">
                                                <?php echo esc_html( $item['title'] ); ?>
                                            </h2>

                                            <p class="pa-news-item__source">
                                                <?php if ( $item['source'] ) : ?>
                                                    <?php echo esc_html( $item['source'] ); ?>
                                                    <span aria-hidden="true">·</span>
                                                <?php endif; ?>
                                                <?php if ( $item['editor'] ) : ?>
                                                    <?php echo esc_html( $item['editor'] ); ?>
                                                    <span aria-hidden="true">·</span>
                                                <?php endif; ?>
                                                <?php if ( $item['verified'] ) : ?>
                                                    <span>Подтверждено</span>
                                                <?php else : ?>
                                                    <span>Не подтверждено</span>
                                                <?php endif; ?>
                                            </p>
                                        </div><!-- /.pa-news-item__left -->

                                        <div class="pa-news-item__right">
                                            <time class="pa-news-item__time" datetime="<?php echo esc_attr( $date_key . 'T' . $item['time'] ); ?>">
                                                <?php echo esc_html( $item['time'] ); ?>
                                            </time>
                                            <span class="pa-news-item__arrow" aria-hidden="true">→</span>
                                        </div>

                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul><!-- /.pa-news-list -->

                    </div><!-- /.pa-date-group -->

                <?php endforeach;
            else : ?>
                <p style="padding:48px 0;text-align:center;font-family:var(--font-mono);font-size:12px;color:#888;">
                    Новостей не найдено
                </p>
            <?php endif; ?>

        </div><!-- /#pa-news-results -->

    </div><!-- /.container -->

</main>

<?php
// Inline JS — фильтрация по разделу + сортировка
?>
<script>
(function () {
    var currentSection = <?php echo wp_json_encode( $current_section ); ?>;
    var currentSort    = <?php echo wp_json_encode( $current_sort ); ?>;
    var loading        = false;

    var ajaxUrl = (typeof palimeData !== 'undefined') ? palimeData.ajaxUrl : '/wp-admin/admin-ajax.php';
    var nonce   = (typeof palimeData !== 'undefined') ? palimeData.nonce  : '';

    function formatDateLabel(dateStr) {
        var ruMonths = ['января','февраля','марта','апреля','мая','июня',
                        'июля','августа','сентября','октября','ноября','декабря'];
        var ruDays = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];
        var d = new Date(dateStr);
        var today = new Date(); today.setHours(0,0,0,0);
        var yest  = new Date(today); yest.setDate(today.getDate() - 1);
        d.setHours(0,0,0,0);

        var prefix = d.getTime() === today.getTime() ? 'Сегодня'
                   : d.getTime() === yest.getTime()  ? 'Вчера'
                   : ruDays[d.getDay()];

        return prefix + ', ' + d.getDate() + ' ' + ruMonths[d.getMonth()] + ' ' + d.getFullYear();
    }

    function fetchResults() {
        if (loading) return;
        loading = true;

        var params = new URLSearchParams({
            action:    'palime_filter_archive',
            nonce:     nonce,
            post_type: 'news',
            section:   currentSection,
            sort:      currentSort,
            paged:     1,
        });

        fetch(ajaxUrl + '?' + params.toString())
            .then(function (r) { return r.json(); })
            .then(function (data) {
                loading = false;
                if (!data.success) return;
                renderNewsResults(data.data.posts || []);
            })
            .catch(function () { loading = false; });
    }

    var sectionMods = { cinema: 'cinema', literature: 'lit', music: 'music', art: 'art' };

    function renderNewsResults(posts) {
        var container = document.getElementById('pa-news-results');
        if (!container) return;

        if (!posts.length) {
            container.innerHTML = '<p style="padding:48px 0;text-align:center;font-family:var(--font-mono);font-size:12px;color:#888;">Новостей не найдено</p>';
            return;
        }

        // Группируем по дате
        var byDate = {};
        posts.forEach(function (p) {
            var dk = (p.date_raw || p.date || '').substring(0, 10);
            if (!byDate[dk]) byDate[dk] = [];
            byDate[dk].push(p);
        });

        var firstGroup = true;
        var html = '';

        Object.keys(byDate).sort().reverse().forEach(function (dk) {
            var items = byDate[dk];
            var sortHtml = '';
            if (firstGroup) {
                sortHtml = '<div class="pa-date-group__sort" role="group">'
                    + '<button class="pa-date-group__sort-btn' + (currentSort==='fresh'   ?' is-active':'') + '" data-sort="fresh">Свежие</button>'
                    + '<button class="pa-date-group__sort-btn' + (currentSort==='section' ?' is-active':'') + '" data-sort="section">По разделу</button>'
                    + '</div>';
                firstGroup = false;
            }

            html += '<div class="pa-date-group">'
                + '<div class="pa-date-group__header">'
                +   '<span class="pa-date-group__label">' + formatDateLabel(dk) + '</span>'
                +   sortHtml
                + '</div>'
                + '<ul class="pa-news-list" role="list">';

            items.forEach(function (p) {
                var mod = sectionMods[p.section_slug] || '';
                var secBadge = p.section_name
                    ? '<span class="pa-news-item__section' + (mod ? ' pa-news-item__section--'+mod : '') + '">' + p.section_name + '</span>'
                    : '';
                var urgentBadge = p.is_urgent
                    ? '<span class="pa-news-item__urgent">Срочно</span>'
                    : '';
                var source  = p.source  ? p.source  + ' <span aria-hidden="true">·</span> ' : '';
                var editor  = p.editor  ? p.editor  + ' <span aria-hidden="true">·</span> ' : '';
                var verified = p.verified ? 'Подтверждено' : 'Не подтверждено';
                var time    = (p.date || '').substring(11, 16) || '';

                html += '<li role="listitem">'
                    + '<a class="pa-news-item" href="' + p.url + '">'
                    +   '<div class="pa-news-item__left">'
                    +     '<div class="pa-news-item__badges">' + secBadge + urgentBadge + '</div>'
                    +     '<h2 class="pa-news-item__title">' + p.title + '</h2>'
                    +     '<p class="pa-news-item__source">' + source + editor + verified + '</p>'
                    +   '</div>'
                    +   '<div class="pa-news-item__right">'
                    +     '<time class="pa-news-item__time">' + time + '</time>'
                    +     '<span class="pa-news-item__arrow" aria-hidden="true">→</span>'
                    +   '</div>'
                    + '</a>'
                    + '</li>';
            });

            html += '</ul></div>';
        });

        container.innerHTML = html;
        bindSortBtns();
    }

    function setSection(slug) {
        currentSection = slug;

        // Обновить все кнопки разделов
        document.querySelectorAll('[data-section]').forEach(function (btn) {
            var active = btn.dataset.section === slug;
            btn.classList.toggle('is-active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });

        fetchResults();
    }

    function bindSortBtns() {
        document.querySelectorAll('.pa-date-group__sort-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentSort = btn.dataset.sort;
                document.querySelectorAll('.pa-date-group__sort-btn').forEach(function (b) {
                    b.classList.toggle('is-active', b.dataset.sort === currentSort);
                });
                fetchResults();
            });
        });
    }

    // Кнопки разделов в hero и subnav
    document.querySelectorAll('[data-section]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.dataset.section;
            if (slug === currentSection) {
                currentSection = '';
                document.querySelectorAll('[data-section]').forEach(function (b) {
                    b.classList.remove('is-active');
                    b.setAttribute('aria-pressed', 'false');
                });
                fetchResults();
            } else {
                setSection(slug);
            }
        });
    });

    bindSortBtns();
}());
</script>

<?php get_footer(); ?>
