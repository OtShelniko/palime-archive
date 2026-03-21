<?php
/**
 * Palime Archive — front-page.php
 * Главная страница: секции по ТЗ
 *
 * @package Palime_Archive
 */

get_header();

// ─── Данные разделов ───
$sections_data = [
    'lit'    => [
        'label'  => 'Литература',
        'slug'   => 'lit',
        'url'    => '/literature/',
        'slogan' => 'Тексты, которые верят форме и смыслу. Без литературного шума.',
        'tags'   => [ 'Эссе', 'Рецензия', 'Досье' ],
    ],
    'cinema' => [
        'label'  => 'Кино',
        'slug'   => 'cinema',
        'url'    => '/cinema/',
        'slogan' => 'Кино как мышление: кадр, ритм, и смысл. Всё «поверхностное».',
        'tags'   => [ 'Эссе', 'Рецензия', 'Досье' ],
    ],
    'music'  => [
        'label'  => 'Музыка',
        'slug'   => 'music',
        'url'    => '/music/',
        'slogan' => 'Звук, который не просит внимания. Он требует его.',
        'tags'   => [ 'Эссе', 'Рецензия', 'Досье' ],
    ],
    'art'    => [
        'label'  => 'Визуальное',
        'slug'   => 'art',
        'url'    => '/art/',
        'slogan' => 'Работы, которые остаются сложны. Но контекст и память.',
        'tags'   => [ 'Эссе', 'Рецензия', 'Досье' ],
    ],
];

// ─── Последняя статья для героя ───
$hero_query = new WP_Query( [
    'post_type'      => 'article',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'no_found_rows'  => true,
] );

$hero_post    = $hero_query->have_posts() ? $hero_query->posts[0] : null;
$hero_post_id = $hero_post ? $hero_post->ID : 0;
wp_reset_postdata();

// Мета героя
$hero_section_terms = $hero_post_id ? get_the_terms( $hero_post_id, 'section' ) : false;
$hero_section_name  = ( $hero_section_terms && ! is_wp_error( $hero_section_terms ) ) ? $hero_section_terms[0]->name : '—';
$hero_section_slug  = ( $hero_section_terms && ! is_wp_error( $hero_section_terms ) ) ? $hero_section_terms[0]->slug : '';

$hero_type_terms = $hero_post_id ? get_the_terms( $hero_post_id, 'article-type' ) : false;
$hero_type_name  = ( $hero_type_terms && ! is_wp_error( $hero_type_terms ) ) ? $hero_type_terms[0]->name : '—';

$hero_status_terms = $hero_post_id ? get_the_terms( $hero_post_id, 'status' ) : false;
$hero_status_name  = ( $hero_status_terms && ! is_wp_error( $hero_status_terms ) ) ? $hero_status_terms[0]->name : 'АКТИВНОЕ';

$hero_person_terms = $hero_post_id ? get_the_terms( $hero_post_id, 'person' ) : false;
$hero_connected    = ( $hero_person_terms && ! is_wp_error( $hero_person_terms ) ) ? count( $hero_person_terms ) : 0;

$today_date = wp_date( 'd.m.Y' );
?>

<main id="main" role="main">

<!-- ============================================================
     1. ГЕРОЙ
     ============================================================ -->
<section class="pa-hero">
    <!-- Левый столбец -->
    <div class="pa-hero__left">
        <div class="pa-hero__inner">
            <p class="pa-hero__eyebrow text-mono text-xs text-upper">
                ARCHIVE · INDEXING: ON
            </p>

            <h1 class="pa-hero__title text-display text-upper">
                Современное<br>искусство<br>и&nbsp;культура
            </h1>

            <p class="pa-hero__subtitle text-mono">
                / Архив
            </p>

            <p class="pa-hero__meta text-mono text-xs">
                CASE_ID: PA-2026-021 · INDEX_STATUS: LIVE · LAST_UPDATE: <?php echo esc_html( $today_date ); ?>
            </p>

            <p class="pa-hero__desc text-serif">
                Кино. Музыка. Литература. Визуальная культура. Эссе, критика, теория. Никакого PR. Только контекст.
            </p>

            <div class="pa-hero__actions">
                <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--primary">
                    Открыть архив
                </a>
                <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="btn btn--outline" style="color:#fff; border-color:rgba(255,255,255,0.4);">
                    Читать журнал
                </a>
            </div>

            <p class="pa-hero__footer text-mono text-xs">
                ACCESS LEVEL: PUBLIC · SOME RECORDS ARE DISPUTED · THIS ARCHIVE IS NOT NEUTRAL
            </p>
        </div>
    </div>

    <!-- Правый столбец -->
    <div class="pa-hero__right">
        <?php if ( $hero_post_id && has_post_thumbnail( $hero_post_id ) ) : ?>
            <a href="<?php echo esc_url( get_permalink( $hero_post_id ) ); ?>" class="pa-hero__image-link">
                <?php echo get_the_post_thumbnail( $hero_post_id, 'hero', [
                    'alt'   => esc_attr( get_the_title( $hero_post_id ) ),
                    'class' => 'pa-hero__image img-cover',
                ] ); ?>
            </a>

            <!-- Мета-карточка поверх изображения -->
            <div class="pa-hero__exhibit">
                <table class="pa-hero__exhibit-table">
                    <tr>
                        <td class="text-mono text-xs" style="opacity:0.5;">EXHIBIT ID</td>
                        <td class="text-mono text-xs"><?php echo esc_html( 'PA-' . get_the_date( 'Y', $hero_post_id ) . '-' . str_pad( $hero_post_id, 3, '0', STR_PAD_LEFT ) ); ?></td>
                    </tr>
                    <tr>
                        <td class="text-mono text-xs" style="opacity:0.5;">МЕДИУМ</td>
                        <td class="text-mono text-xs"><?php echo esc_html( $hero_section_name ); ?></td>
                    </tr>
                    <tr>
                        <td class="text-mono text-xs" style="opacity:0.5;">СТАТУС</td>
                        <td class="text-mono text-xs"><?php echo esc_html( mb_strtoupper( $hero_status_name ) ); ?></td>
                    </tr>
                    <tr>
                        <td class="text-mono text-xs" style="opacity:0.5;">СВЯЗИ</td>
                        <td class="text-mono text-xs"><?php echo esc_html( $hero_connected ); ?></td>
                    </tr>
                </table>
            </div>
        <?php else : ?>
            <!-- Заглушка без изображения -->
            <div class="pa-hero__placeholder">
                <span class="text-mono text-upper" style="color:var(--color-ui); font-size:1.2rem; letter-spacing:0.2em;">DISPUTED</span>
            </div>
        <?php endif; ?>
    </div>
</section>


<!-- ============================================================
     2. ИНДЕКС ПО МЕДИУМУ
     ============================================================ -->
<section class="section" style="background:var(--color-bg);">
    <div class="container">
        <h2 class="text-display text-upper mb-sm" style="font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em;">
            Индекс по медиуму
        </h2>
        <p class="text-mono text-sm mb-xl" style="opacity:0.5;">
            Четыре каталога. Один фильтр.
        </p>

        <div class="grid grid--4">
            <?php
            $medium_index = 0;
            foreach ( $sections_data as $sec_slug => $sec ) :
                $medium_index++;

                // Последняя статья раздела
                $sec_query = new WP_Query( [
                    'post_type'              => 'article',
                    'posts_per_page'         => 1,
                    'post_status'            => 'publish',
                    'no_found_rows'          => true,
                    'update_post_meta_cache' => false,
                    'tax_query'              => [ [
                        'taxonomy' => 'section',
                        'field'    => 'slug',
                        'terms'    => $sec_slug,
                    ] ],
                ] );

                $sec_post_id = $sec_query->have_posts() ? $sec_query->posts[0]->ID : 0;
                wp_reset_postdata();
            ?>
                <article class="pa-medium-card section-<?php echo esc_attr( $sec_slug ); ?>">
                    <!-- Обложка -->
                    <div class="pa-medium-card__image">
                        <?php if ( $sec_post_id && has_post_thumbnail( $sec_post_id ) ) : ?>
                            <?php echo palime_get_thumbnail( $sec_post_id, 'card', $sec['label'] ); ?>
                        <?php else : ?>
                            <div class="pa-medium-card__placeholder">
                                <span class="text-mono text-xs" style="opacity:0.3;">НЕТ ДАННЫХ</span>
                            </div>
                        <?php endif; ?>
                        <!-- Красная полоска слева -->
                        <span class="pa-medium-card__accent"></span>
                    </div>

                    <!-- Мета -->
                    <div class="pa-medium-card__body">
                        <p class="text-mono text-xs" style="opacity:0.4; margin-bottom:var(--spacing-sm);">
                            PA-2026-<?php echo esc_html( str_pad( $medium_index, 3, '0', STR_PAD_LEFT ) ); ?> · <?php echo esc_html( mb_strtoupper( $sec['label'] ) ); ?> · ACTIVE
                        </p>

                        <h3 class="text-display text-upper" style="font-size:1.3rem; letter-spacing:0.08em; margin-bottom:var(--spacing-sm);">
                            <?php echo esc_html( $sec['label'] ); ?>
                        </h3>

                        <p class="text-serif text-sm" style="opacity:0.7; line-height:1.5; margin-bottom:var(--spacing-md);">
                            <?php echo esc_html( $sec['slogan'] ); ?>
                        </p>

                        <!-- Теги -->
                        <div class="flex flex--gap flex--wrap" style="gap:6px; margin-bottom:var(--spacing-md);">
                            <?php foreach ( $sec['tags'] as $tag_name ) : ?>
                                <span class="tag tag--section" style="font-size:0.65rem; padding:2px 8px;">
                                    <?php echo esc_html( $tag_name ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>

                        <!-- Кнопка -->
                        <a href="<?php echo esc_url( home_url( $sec['url'] ) ); ?>" class="pa-medium-card__btn text-mono text-xs text-upper">
                            Открыть индекс →
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ============================================================
     3. АРХИВ — НЕ БЛОГ
     ============================================================ -->
<section class="section" style="background:var(--color-bg); border-top:1px solid rgba(0,0,0,0.06);">
    <div class="container">
        <div class="grid grid--2" style="gap:var(--spacing-2xl); align-items:start;">

            <!-- Левый блок: манифест -->
            <div>
                <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.8rem,3.5vw,2.8rem); letter-spacing:0.08em; line-height:1.1;">
                    Архив —<br>не блог
                </h2>
                <div class="text-serif" style="font-size:1.05rem; line-height:1.8; opacity:0.85;">
                    <p>Мы не гонимся за трендами. Мы не публикуем пресс-релизы под видом критики. Мы не путаем маркетинг со смыслом.</p>
                    <p style="margin-top:var(--spacing-md);">Каждый артефакт входит в систему: с&nbsp;тегами, индексом, перекрёстными ссылками. Кино связано с&nbsp;теорией. Музыка переплетена с&nbsp;литературой.</p>
                    <p style="margin-top:var(--spacing-md);">Это не контент. Это контекст.<br>Это живое досье.</p>
                </div>
            </div>

            <!-- Правый блок: таблица индексации -->
            <div>
                <table class="pa-index-table">
                    <tr>
                        <td class="pa-index-table__num text-mono">01</td>
                        <td class="pa-index-table__label text-mono text-xs text-upper">Медиум</td>
                        <td class="pa-index-table__values text-serif text-sm">Кино · Музыка · Литература · ИЗО · Теория</td>
                    </tr>
                    <tr>
                        <td class="pa-index-table__num text-mono">02</td>
                        <td class="pa-index-table__label text-mono text-xs text-upper">Форма</td>
                        <td class="pa-index-table__values text-serif text-sm">Эссе · Рецензия · Интервью · Досье · Индекс</td>
                    </tr>
                    <tr>
                        <td class="pa-index-table__num text-mono">03</td>
                        <td class="pa-index-table__label text-mono text-xs text-upper">Тема</td>
                        <td class="pa-index-table__values text-serif text-sm">Память · Власть · Идентичность · Место · Время</td>
                    </tr>
                    <tr>
                        <td class="pa-index-table__num text-mono">04</td>
                        <td class="pa-index-table__label text-mono text-xs text-upper">Связи</td>
                        <td class="pa-index-table__values text-serif text-sm">Кросс-реф · Контекст · Поток · Сеть</td>
                    </tr>
                </table>

                <!-- Статусы -->
                <div class="flex flex--gap flex--wrap mt-lg" style="gap:8px;">
                    <span class="tag" style="border-color:var(--color-ui); color:var(--color-ui);">Спорное</span>
                    <span class="tag" style="border-color:var(--color-text); color:var(--color-text);">Активное</span>
                    <span class="tag" style="border-color:rgba(0,0,0,0.3); color:rgba(0,0,0,0.5);">В архиве</span>
                    <span class="tag" style="border-color:var(--color-text); color:var(--color-text);">Подтверждено</span>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     4. ЖИВОЙ ИНДЕКС
     ============================================================ -->
<?php
$live_query = new WP_Query( [
    'post_type'              => [ 'article', 'news' ],
    'posts_per_page'         => 10,
    'post_status'            => 'publish',
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
] );

// Общее количество записей в архиве
$total_articles = wp_count_posts( 'article' );
$total_news     = wp_count_posts( 'news' );
$total_count    = ( isset( $total_articles->publish ) ? $total_articles->publish : 0 )
                + ( isset( $total_news->publish ) ? $total_news->publish : 0 );
?>
<section class="section--dark" style="padding:var(--spacing-2xl) 0;">
    <div class="container">

        <!-- Заголовок -->
        <div class="live-index__header" style="border-bottom-color:rgba(255,255,255,0.12); margin-bottom:var(--spacing-lg);">
            <div class="flex" style="align-items:center; gap:var(--spacing-md);">
                <span class="live-index__label" style="color:var(--color-bg);">
                    <span class="live-index__dot"></span>
                    Живой индекс
                </span>
                <span class="text-mono text-xs" style="color:rgba(255,255,255,0.35);">
                    РАЗМЕР АРХИВА: <?php echo esc_html( $total_count ); ?> ЗАПИСЕЙ · ОБНОВЛЕНО: СЕГОДНЯ
                </span>
            </div>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--sm" style="color:var(--color-bg); border-color:rgba(255,255,255,0.25);">
                Полный реестр →
            </a>
        </div>

        <!-- Вкладки -->
        <div class="pa-live-tabs flex flex--gap mb-lg" style="gap:0;">
            <button class="pa-live-tab is-active text-mono text-xs text-upper" data-tab="newest">Новейшие</button>
            <button class="pa-live-tab text-mono text-xs text-upper" data-tab="popular">Популярные</button>
            <button class="pa-live-tab text-mono text-xs text-upper" data-tab="updated">Обновлено сегодня</button>
            <button class="pa-live-tab text-mono text-xs text-upper" data-tab="editors">Выбор редактора</button>
        </div>

        <!-- Таблица -->
        <?php if ( $live_query->have_posts() ) : ?>
            <div style="overflow-x:auto;">
                <table class="pa-live-table" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th class="text-mono text-xs" style="color:rgba(255,255,255,0.3); text-align:left; padding:8px 12px 8px 0; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">ID</th>
                            <th class="text-mono text-xs" style="color:rgba(255,255,255,0.3); text-align:left; padding:8px 12px; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">Заголовок</th>
                            <th class="text-mono text-xs hide-mobile" style="color:rgba(255,255,255,0.3); text-align:left; padding:8px 12px; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">Медиум</th>
                            <th class="text-mono text-xs hide-mobile" style="color:rgba(255,255,255,0.3); text-align:left; padding:8px 12px; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">Форма</th>
                            <th class="text-mono text-xs hide-mobile" style="color:rgba(255,255,255,0.3); text-align:right; padding:8px 12px; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">Мин</th>
                            <th class="text-mono text-xs hide-mobile" style="color:rgba(255,255,255,0.3); text-align:right; padding:8px 0 8px 12px; font-weight:400; border-bottom:1px solid rgba(255,255,255,0.1);">Связи</th>
                            <th style="border-bottom:1px solid rgba(255,255,255,0.1); width:30px;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ( $live_query->have_posts() ) : $live_query->the_post();
                            $pid      = get_the_ID();
                            $plink    = get_permalink();
                            $ptitle   = get_the_title();
                            $ptype    = get_post_type( $pid );

                            // Раздел (медиум)
                            $s_terms  = get_the_terms( $pid, 'section' );
                            $s_name   = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';
                            $s_slug   = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->slug : '';

                            // Форма (тип статьи / новость)
                            $form_label = 'Новость';
                            if ( $ptype === 'article' ) {
                                $at_terms   = get_the_terms( $pid, 'article-type' );
                                $form_label = ( $at_terms && ! is_wp_error( $at_terms ) ) ? $at_terms[0]->name : 'Статья';
                            }

                            // Время чтения (ACF)
                            $reading_time = function_exists( 'get_field' ) ? get_field( 'reading_time', $pid ) : '';

                            // Связи (персоны)
                            $person_terms  = get_the_terms( $pid, 'person' );
                            $person_count  = ( $person_terms && ! is_wp_error( $person_terms ) ) ? count( $person_terms ) : 0;

                            // ID в формате PA-YYYY-NNN
                            $exhibit_id = 'PA-' . get_the_date( 'Y', $pid ) . '-' . str_pad( $pid, 3, '0', STR_PAD_LEFT );
                        ?>
                            <tr class="pa-live-row" style="border-bottom:1px solid rgba(255,255,255,0.06); cursor:pointer;" onclick="window.location='<?php echo esc_url( $plink ); ?>'">
                                <td class="text-mono text-xs section-<?php echo esc_attr( $s_slug ); ?>" style="padding:10px 12px 10px 0; color:var(--accent); white-space:nowrap;">
                                    <?php echo esc_html( $exhibit_id ); ?>
                                </td>
                                <td style="padding:10px 12px;">
                                    <a href="<?php echo esc_url( $plink ); ?>" style="color:var(--color-bg); font-family:var(--font-serif); font-size:0.95rem; transition:color var(--transition);">
                                        <?php echo esc_html( $ptitle ); ?>
                                    </a>
                                </td>
                                <td class="text-mono text-xs hide-mobile section-<?php echo esc_attr( $s_slug ); ?>" style="padding:10px 12px; color:var(--accent);">
                                    <?php echo esc_html( $s_name ); ?>
                                </td>
                                <td class="text-mono text-xs hide-mobile" style="padding:10px 12px; color:rgba(255,255,255,0.5);">
                                    <?php echo esc_html( $form_label ); ?>
                                </td>
                                <td class="text-mono text-xs hide-mobile" style="padding:10px 12px; color:rgba(255,255,255,0.5); text-align:right;">
                                    <?php echo $reading_time ? esc_html( $reading_time ) : '—'; ?>
                                </td>
                                <td class="text-mono text-xs hide-mobile" style="padding:10px 0 10px 12px; color:rgba(255,255,255,0.5); text-align:right;">
                                    <?php echo esc_html( $person_count ); ?>
                                </td>
                                <td style="padding:10px 0; text-align:right;">
                                    <span style="color:rgba(255,255,255,0.3); font-size:0.9rem;">→</span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else : ?>
            <div style="padding:var(--spacing-xl) 0; text-align:center;">
                <p class="text-mono text-sm" style="color:rgba(255,255,255,0.4);">РЕЕСТР ПУСТ. ИНДЕКСАЦИЯ НАЧНЁТСЯ ПОСЛЕ ПЕРВОЙ ПУБЛИКАЦИИ.</p>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>

        <!-- Подпись -->
        <p class="text-serif mt-lg" style="font-style:italic; color:rgba(255,255,255,0.4); font-size:0.9rem;">
            Этот архив собирает то, что культура пытается забыть.
        </p>

    </div>
</section>


<!-- ============================================================
     5–9: ОСТАЛЬНЫЕ СЕКЦИИ (будут добавлены позже)
     ============================================================ -->


</main>

<!-- JS: вкладки живого индекса -->
<script>
(function(){
    var tabs = document.querySelectorAll('.pa-live-tab');
    if (!tabs.length) return;
    tabs.forEach(function(tab){
        tab.addEventListener('click', function(){
            tabs.forEach(function(t){ t.classList.remove('is-active'); });
            tab.classList.add('is-active');
        });
    });
})();
</script>

<?php get_footer(); ?>
