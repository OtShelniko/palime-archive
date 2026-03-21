<?php
/**
 * Template Name: Блог
 *
 * Страница СТАТЬИ — сетка статей с featured первой карточкой,
 * фильтрами разделов и типа материала, сортировкой.
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) exit;

get_header();

// ------------------------------------------------------------------
// Параметры из GET (для начального рендера / SSR фильтрации)
// ------------------------------------------------------------------
$current_section = sanitize_key( $_GET['section'] ?? '' );
$current_type    = sanitize_key( $_GET['type'] ?? '' );
$current_sort    = in_array( $_GET['sort'] ?? '', [ 'new', 'popular', 'section' ], true )
                   ? sanitize_key( $_GET['sort'] )
                   : 'new';
$current_page    = max( 1, (int) ( $_GET['paged'] ?? 1 ) );
$per_page        = 10;

// ------------------------------------------------------------------
// WP_Query — статьи
// ------------------------------------------------------------------
$tax_query = [];

if ( $current_section ) {
    $tax_query[] = [
        'taxonomy' => 'section',
        'field'    => 'slug',
        'terms'    => $current_section,
    ];
}

if ( $current_type ) {
    $tax_query[] = [
        'taxonomy' => 'article-type',
        'field'    => 'slug',
        'terms'    => $current_type,
    ];
}

$orderby = 'date';
$order   = 'DESC';
if ( $current_sort === 'popular' ) {
    $orderby = 'comment_count';
} elseif ( $current_sort === 'section' ) {
    $orderby = 'meta_value';
}

$query = new WP_Query( [
    'post_type'              => 'article',
    'posts_per_page'         => $per_page,
    'paged'                  => $current_page,
    'orderby'                => $orderby,
    'order'                  => $order,
    'tax_query'              => $tax_query ?: [],
    'update_post_meta_cache' => false,
] );

$total_found = $query->found_posts;

// ------------------------------------------------------------------
// Таксономии для фильтров
// ------------------------------------------------------------------
$section_terms = get_terms( [ 'taxonomy' => 'section', 'hide_empty' => false ] );
$type_terms    = get_terms( [ 'taxonomy' => 'article-type', 'hide_empty' => false ] );

$section_slugs = [
    'cinema'     => 'cinema',
    'literature' => 'lit',
    'music'      => 'music',
    'art'        => 'art',
];
?>

<main class="pa-blog" id="main" data-section="blog">

    <div class="container">

        <?php /* ---- Вкладки БЛОГ / НОВОСТИ ---- */ ?>
        <nav class="pa-blog-tabs" aria-label="Переключение разделов">
            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
               class="pa-blog-tabs__link is-active"
               aria-current="page">
                Блог
            </a>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>"
               class="pa-blog-tabs__link">
                Новости
            </a>
        </nav>

        <?php /* ---- Фильтры ---- */ ?>
        <div class="pa-blog-filters" id="pa-blog-filters">
            <div class="pa-blog-filters__inner">

                <div class="pa-blog-filters__group">
                    <span class="pa-blog-filters__label">Раздел</span>
                    <div class="pa-blog-filters__tags" role="group" aria-label="Фильтр по разделу">
                        <?php if ( ! is_wp_error( $section_terms ) ) : foreach ( $section_terms as $term ) :
                            $css_mod = $section_slugs[ $term->slug ] ?? '';
                            $is_active = $current_section === $term->slug;
                        ?>
                            <button
                                class="pa-blog-tag<?php echo $css_mod ? " pa-blog-tag--{$css_mod}" : ''; ?><?php echo $is_active ? ' is-active' : ''; ?>"
                                data-filter="section"
                                data-value="<?php echo esc_attr( $term->slug ); ?>"
                                aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                            ><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; endif; ?>
                    </div>
                </div>

                <div class="pa-blog-filters__group">
                    <span class="pa-blog-filters__label">Тип материала</span>
                    <div class="pa-blog-filters__tags" role="group" aria-label="Фильтр по типу">
                        <?php if ( ! is_wp_error( $type_terms ) ) : foreach ( $type_terms as $term ) :
                            $is_active = $current_type === $term->slug;
                        ?>
                            <button
                                class="pa-blog-tag<?php echo $is_active ? ' is-active' : ''; ?>"
                                data-filter="type"
                                data-value="<?php echo esc_attr( $term->slug ); ?>"
                                aria-pressed="<?php echo $is_active ? 'true' : 'false'; ?>"
                            ><?php echo esc_html( $term->name ); ?></button>
                        <?php endforeach; endif; ?>
                    </div>
                </div>

            </div>
        </div><!-- /.pa-blog-filters -->

        <?php /* ---- Герой / Заголовок ---- */ ?>
        <div class="pa-blog-hero">
            <p class="pa-blog-hero__label">Блог</p>
            <h1 class="pa-blog-hero__title">Статьи</h1>
            <p class="pa-blog-hero__subtitle">
                Авторы<span>·</span>Произведения<span>·</span>Подборки
            </p>
        </div>

        <?php /* ---- Панель результатов ---- */ ?>
        <div class="pa-blog-bar">
            <p class="pa-blog-bar__count" id="pa-blog-count">
                Показано: <strong><?php echo esc_html( $total_found ); ?></strong> материалов
            </p>
            <div class="pa-sort-tabs" role="group" aria-label="Сортировка">
                <button
                    class="pa-sort-tab<?php echo $current_sort === 'new'     ? ' is-active' : ''; ?>"
                    data-sort="new">Новые</button>
                <button
                    class="pa-sort-tab<?php echo $current_sort === 'popular'  ? ' is-active' : ''; ?>"
                    data-sort="popular">Популярные</button>
                <button
                    class="pa-sort-tab<?php echo $current_sort === 'section'  ? ' is-active' : ''; ?>"
                    data-sort="section">По разделу</button>
            </div>
        </div>

        <?php /* ---- Сетка карточек ---- */ ?>
        <div class="pa-blog-grid" id="pa-blog-results">

            <?php if ( $query->have_posts() ) :
                $i = 0;
                while ( $query->have_posts() ) :
                    $query->the_post();
                    $i++;

                    // Метаданные
                    $post_sections = get_the_terms( get_the_ID(), 'section' );
                    $section_obj   = ! is_wp_error( $post_sections ) && $post_sections ? $post_sections[0] : null;
                    $section_slug  = $section_obj ? $section_obj->slug : '';
                    $section_name  = $section_obj ? $section_obj->name : '';
                    $section_mod   = $section_slugs[ $section_slug ] ?? '';

                    $post_types    = get_the_terms( get_the_ID(), 'article-type' );
                    $type_name     = ! is_wp_error( $post_types ) && $post_types ? $post_types[0]->name : '';

                    $reading_time  = function_exists( 'get_field' ) ? get_field( 'reading_time' ) : '';
                    $article_lead  = function_exists( 'get_field' ) ? get_field( 'article_lead' ) : get_the_excerpt();
                    $date_fmt      = get_the_date( 'd.m.Y' );
                    ?>

                    <a class="pa-blog-card" href="<?php the_permalink(); ?>">

                        <div class="pa-blog-card__meta">
                            <span class="pa-blog-card__section<?php echo $section_mod ? " pa-blog-card__section--{$section_mod}" : ''; ?>">
                                <?php echo esc_html( $section_name ); ?>
                            </span>
                            <?php if ( $type_name ) : ?>
                                <span class="pa-blog-card__type"><?php echo esc_html( $type_name ); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="pa-blog-card__image">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <?php the_post_thumbnail( 'large', [ 'alt' => esc_attr( get_the_title() ) ] ); ?>
                            <?php else : ?>
                                <div class="pa-blog-card__image-placeholder">Изображение</div>
                            <?php endif; ?>
                        </div>

                        <div class="pa-blog-card__body">
                            <h2 class="pa-blog-card__title"><?php the_title(); ?></h2>
                            <?php if ( $article_lead ) : ?>
                                <p class="pa-blog-card__lead"><?php echo esc_html( wp_trim_words( $article_lead, 20 ) ); ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="pa-blog-card__footer">
                            <div class="pa-blog-card__date-info">
                                <span class="pa-blog-card__date"><?php echo esc_html( $date_fmt ); ?></span>
                                <?php if ( $reading_time ) : ?>
                                    <span class="pa-blog-card__read-time"><?php echo esc_html( $reading_time ); ?> мин</span>
                                <?php endif; ?>
                            </div>
                            <span class="pa-blog-card__arrow" aria-hidden="true">→</span>
                        </div>

                    </a>

                <?php endwhile;
                wp_reset_postdata();
            else : ?>
                <p class="pa-blog-empty" style="grid-column:1/-1;padding:40px;text-align:center;font-family:var(--font-mono);font-size:12px;color:#888;">
                    Материалов не найдено
                </p>
            <?php endif; ?>

        </div><!-- /#pa-blog-results -->

        <?php /* ---- Пагинация ---- */ ?>
        <?php if ( $query->max_num_pages > 1 ) : ?>
            <div class="pa-blog-pagination">
                <?php
                echo paginate_links( [
                    'total'     => $query->max_num_pages,
                    'current'   => $current_page,
                    'format'    => '?paged=%#%',
                    'prev_text' => '←',
                    'next_text' => '→',
                ] );
                ?>
            </div>
        <?php endif; ?>

    </div><!-- /.container -->

</main>

<?php
// Inline JS для фильтрации без перезагрузки
?>
<script>
(function () {
    var filters = {
        section: <?php echo wp_json_encode( $current_section ); ?>,
        type:    <?php echo wp_json_encode( $current_type ); ?>,
        sort:    <?php echo wp_json_encode( $current_sort ); ?>,
        page:    1,
        loading: false,
    };

    var ajaxUrl = (typeof palimeData !== 'undefined') ? palimeData.ajaxUrl : '/wp-admin/admin-ajax.php';
    var nonce   = (typeof palimeData !== 'undefined') ? palimeData.nonce : '';

    function fetchResults() {
        if (filters.loading) return;
        filters.loading = true;

        var params = new URLSearchParams({
            action:    'palime_filter_archive',
            nonce:     nonce,
            post_type: 'article',
            section: filters.section,
            type:    filters.type,
            sort:    filters.sort,
            paged:   filters.page,
        });

        fetch(ajaxUrl + '?' + params.toString())
            .then(function (r) { return r.json(); })
            .then(function (data) {
                filters.loading = false;
                if (!data.success) return;
                renderResults(data.data);
                updateCount(data.data.total);
            })
            .catch(function () { filters.loading = false; });
    }

    function renderResults(data) {
        var grid = document.getElementById('pa-blog-results');
        if (!grid) return;

        if (!data.posts || data.posts.length === 0) {
            grid.innerHTML = '<p class="pa-blog-empty" style="grid-column:1/-1;padding:40px;text-align:center;font-family:var(--font-mono);font-size:12px;color:#888;">Материалов не найдено</p>';
            return;
        }

        var sectionMods = { cinema: 'cinema', literature: 'lit', music: 'music', art: 'art' };
        var html = '';

        data.posts.forEach(function (post) {
            var mod = sectionMods[post.section_slug] || '';
            var sectionClass = mod ? 'pa-blog-card__section--' + mod : '';
            var thumb = post.thumbnail
                ? '<img src="' + post.thumbnail + '" alt="' + post.title + '">'
                : '<div class="pa-blog-card__image-placeholder">Изображение</div>';
            var lead = post.lead ? '<p class="pa-blog-card__lead">' + post.lead + '</p>' : '';
            var time = post.reading_time ? '<span class="pa-blog-card__read-time">' + post.reading_time + ' мин</span>' : '';
            var type = post.type_label ? '<span class="pa-blog-card__type">' + post.type_label + '</span>' : '';

            html += '<a class="pa-blog-card" href="' + post.url + '">'
                + '<div class="pa-blog-card__meta">'
                +   '<span class="pa-blog-card__section ' + sectionClass + '">' + (post.section_name || '') + '</span>'
                +   type
                + '</div>'
                + '<div class="pa-blog-card__image">' + thumb + '</div>'
                + '<div class="pa-blog-card__body">'
                +   '<h2 class="pa-blog-card__title">' + post.title + '</h2>'
                +   lead
                + '</div>'
                + '<div class="pa-blog-card__footer">'
                +   '<div class="pa-blog-card__date-info">'
                +     '<span class="pa-blog-card__date">' + (post.date || '') + '</span>'
                +     time
                +   '</div>'
                +   '<span class="pa-blog-card__arrow" aria-hidden="true">→</span>'
                + '</div>'
                + '</a>';
        });

        grid.innerHTML = html;
    }

    function updateCount(total) {
        var el = document.getElementById('pa-blog-count');
        if (el) el.innerHTML = 'Показано: <strong>' + (total || 0) + '</strong> материалов';
    }

    // Фильтр-теги
    document.querySelectorAll('[data-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var filterKey = btn.dataset.filter;
            var value     = btn.dataset.value;

            // Переключение внутри группы
            var group = document.querySelectorAll('[data-filter="' + filterKey + '"]');
            var wasActive = btn.classList.contains('is-active');

            group.forEach(function (b) {
                b.classList.remove('is-active');
                b.setAttribute('aria-pressed', 'false');
            });

            if (!wasActive) {
                btn.classList.add('is-active');
                btn.setAttribute('aria-pressed', 'true');
                filters[filterKey] = value;
            } else {
                filters[filterKey] = '';
            }

            filters.page = 1;
            fetchResults();
        });
    });

    // Сортировка
    document.querySelectorAll('.pa-sort-tab').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.pa-sort-tab').forEach(function (b) {
                b.classList.remove('is-active');
            });
            btn.classList.add('is-active');
            filters.sort = btn.dataset.sort;
            filters.page = 1;
            fetchResults();
        });
    });
}());
</script>

<?php get_footer(); ?>
