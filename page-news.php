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
$current_sort    = in_array( $_GET['sort'] ?? '', [ 'fresh' ], true )
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

    <!-- ── HEADER ── -->
    <div class="pa-news-hero">
        <div class="pa-news-hero__inner container">

            <div class="pa-news-hero__left">
                <p class="pa-news-hero__tag">PALIME ARCHIVE · SIGNALS</p>
                <h1 class="pa-news-hero__title"><?php esc_html_e( 'События', 'palime-archive' ); ?></h1>
                <p class="pa-news-hero__sub"><?php esc_html_e( 'Актуальное из архива: новости разделов, обновления, сигналы.', 'palime-archive' ); ?></p>
            </div>

            <div class="pa-news-hero__right">
                <span class="pa-news-hero__filter-label"><?php esc_html_e( 'Раздел', 'palime-archive' ); ?></span>
                <div class="pa-news-section-filters" role="group" aria-label="<?php esc_attr_e( 'Фильтр по разделу', 'palime-archive' ); ?>">
                    <?php foreach ( $section_labels as $slug => $label ) :
                        $mod       = $section_slugs[ $slug ] ?? '';
                        $is_active = $current_section === $slug;
                    ?>
                        <button
                            type="button"
                            class="pa-news-section-filter<?php echo $mod ? " pa-news-section-filter--{$mod}" : ''; ?><?php echo $is_active ? ' is-active' : ''; ?>"
                            data-section="<?php echo esc_attr( $slug ); ?>"
                            aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                        ><?php echo esc_html( $label ); ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div><!-- /.pa-news-hero -->

    <div class="container">

        <!-- ── ЛЕНТА ── -->
        <div class="pa-news-body" id="pa-news-results">

            <?php if ( ! empty( $news_by_date ) ) :
                $first_group  = true;
                $global_index = 0;
                foreach ( $news_by_date as $date_key => $items ) : ?>

                    <div class="pa-date-group">

                        <div class="pa-date-group__header">
                            <span class="pa-date-group__label">
                                <?php echo esc_html( palime_news_date_label( $date_key ) ); ?>
                            </span>
                            <span class="pa-date-group__count"><?php echo esc_html( count( $items ) ); ?></span>

                            <?php if ( $first_group ) : ?>
                                <div class="pa-date-group__sort" role="group" aria-label="<?php esc_attr_e( 'Сортировка новостей', 'palime-archive' ); ?>">
                                    <button
                                        class="pa-date-group__sort-btn<?php echo $current_sort === 'fresh' ? ' is-active' : ''; ?>"
                                        data-sort="fresh"><?php esc_html_e( 'Свежие', 'palime-archive' ); ?></button>
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
                                $is_lead     = ( $global_index === 0 );
                                $global_index++;
                            ?>
                                <li role="listitem">
                                    <a class="pa-news-item<?php echo $is_lead ? ' pa-news-item--lead' : ''; ?>" href="<?php echo esc_url( $item['permalink'] ); ?>">

                                        <div class="pa-news-item__left">
                                            <div class="pa-news-item__badges">
                                                <?php if ( $sec_name ) : ?>
                                                    <span class="pa-news-item__section<?php echo $sec_mod ? " pa-news-item__section--{$sec_mod}" : ''; ?>">
                                                        <?php echo esc_html( $sec_name ); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if ( $item['is_urgent'] ) : ?>
                                                    <span class="pa-news-item__urgent"><?php esc_html_e( 'Срочно', 'palime-archive' ); ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <h2 class="pa-news-item__title">
                                                <?php echo esc_html( $item['title'] ); ?>
                                            </h2>

                                            <p class="pa-news-item__source">
                                                <?php if ( $item['source'] ) : ?>
                                                    <span><?php echo esc_html( $item['source'] ); ?></span>
                                                <?php endif; ?>
                                                <?php if ( $item['editor'] ) : ?>
                                                    <span><?php echo esc_html( $item['editor'] ); ?></span>
                                                <?php endif; ?>
                                                <?php if ( $item['verified'] ) : ?>
                                                    <span class="pa-news-item__verified">✓</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>

                                        <div class="pa-news-item__right">
                                            <time class="pa-news-item__time" datetime="<?php echo esc_attr( $date_key . 'T' . $item['time'] ); ?>">
                                                <?php echo esc_html( $item['time'] ); ?>
                                            </time>
                                            <span class="pa-news-item__arrow" aria-hidden="true">→</span>
                                        </div>

                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>

                <?php endforeach;
            else : ?>
                <div class="pa-news-empty">
                    <p class="pa-news-empty__text"><?php esc_html_e( 'Новостей не найдено', 'palime-archive' ); ?></p>
                </div>
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
            container.innerHTML = '<div class="pa-news-empty"><p class="pa-news-empty__text">Новостей не найдено</p></div>';
            return;
        }

        var byDate = {};
        posts.forEach(function (p) {
            var dk = p.date_key || (p.date_raw || p.date || '').substring(0, 10);
            if (!byDate[dk]) byDate[dk] = [];
            byDate[dk].push(p);
        });

        var firstGroup   = true;
        var globalIndex  = 0;
        var html = '';

        Object.keys(byDate).sort().reverse().forEach(function (dk) {
            var items = byDate[dk];
            var sortHtml = '';
            if (firstGroup) {
                sortHtml = '<div class="pa-date-group__sort" role="group">'
                    + '<button class="pa-date-group__sort-btn' + (currentSort==='fresh' ? ' is-active' : '') + '" data-sort="fresh">Свежие</button>'
                    + '</div>';
                firstGroup = false;
            }

            html += '<div class="pa-date-group">'
                + '<div class="pa-date-group__header">'
                +   '<span class="pa-date-group__label">' + formatDateLabel(dk) + '</span>'
                +   '<span class="pa-date-group__count">' + items.length + '</span>'
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
                var sourceHtml = p.source ? '<span>' + p.source + '</span>' : '';
                var editorHtml = p.editor ? '<span>' + p.editor + '</span>' : '';
                var verifiedHtml = p.verified ? '<span class="pa-news-item__verified">\u2713</span>' : '';
                var time = p.time || '';
                var leadClass = globalIndex === 0 ? ' pa-news-item--lead' : '';
                globalIndex++;

                html += '<li role="listitem">'
                    + '<a class="pa-news-item' + leadClass + '" href="' + p.url + '">'
                    +   '<div class="pa-news-item__left">'
                    +     '<div class="pa-news-item__badges">' + secBadge + urgentBadge + '</div>'
                    +     '<h2 class="pa-news-item__title">' + p.title + '</h2>'
                    +     '<p class="pa-news-item__source">' + sourceHtml + editorHtml + verifiedHtml + '</p>'
                    +   '</div>'
                    +   '<div class="pa-news-item__right">'
                    +     '<time class="pa-news-item__time">' + time + '</time>'
                    +     '<span class="pa-news-item__arrow" aria-hidden="true">\u2192</span>'
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
        document.querySelectorAll('.pa-news-section-filter[data-section]').forEach(function (btn) {
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

    // Кнопки разделов
    document.querySelectorAll('.pa-news-section-filter[data-section]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.dataset.section;
            if (slug === currentSection) {
                currentSection = '';
                document.querySelectorAll('.pa-news-section-filter[data-section]').forEach(function (b) {
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