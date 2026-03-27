<?php
/**
 * Palime Archive — front-page.php
 * Главная страница
 *
 * @package Palime_Archive
 */

// Подключаем CSS главной страницы ДО get_header(),
// чтобы стиль попал в wp_head() гарантированно.
wp_enqueue_style(
    'palime-page-home',
    get_template_directory_uri() . '/assets/css/pages/front-page.css',
    [ 'palime-variables' ],
    filemtime( get_template_directory() . '/assets/css/pages/front-page.css' )
);

get_header();

// Данные для живого индекса
$live = new WP_Query( [
    'post_type'              => 'article',
    'posts_per_page'         => 10,
    'post_status'            => 'publish',
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
] );

$rows = [];
if ( $live->have_posts() ) {
    while ( $live->have_posts() ) {
        $live->the_post();
        $pid = get_the_ID();

        $s_terms    = get_the_terms( $pid, 'section' );
        $medium     = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';
        $medium_slug = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->slug : '';

        $at   = get_the_terms( $pid, 'article-type' );
        $form = ( $at && ! is_wp_error( $at ) ) ? $at[0]->name : 'Статья';

        $min = function_exists( 'get_field' ) ? get_field( 'reading_time', $pid ) : '';

        $rows[] = [
            'id'          => 'PA-' . get_the_date( 'Y' ) . '-' . str_pad( $pid, 3, '0', STR_PAD_LEFT ),
            'title'       => get_the_title(),
            'url'         => get_permalink(),
            'medium'      => $medium,
            'medium_slug' => $medium_slug,
            'form'        => $form,
            'min'         => $min ? $min : '—',
            'date'        => get_the_date( 'd.m.Y' ),
            'status'      => 'NEW',
        ];
    }
    wp_reset_postdata();
}

while ( count( $rows ) < 10 ) {
    $rows[] = [
        'id'          => 'PA-——-———',
        'title'       => '———————————————',
        'url'         => '#',
        'medium'      => '——————',
        'medium_slug' => '',
        'form'        => '————',
        'min'         => '——',
        'date'        => '——.——.————',
        'status'      => '',
    ];
}

$total_count = (int) wp_count_posts( 'article' )->publish;

// Секционные цвета
$section_colors = [
    'cinema' => '#4DB7FF',
    'lit'    => '#4A3428',
    'music'  => '#FF4FA3',
    'art'    => '#C6A25A',
];
?>


<!-- ============================================================
     1. ГЕРОЙ — стиль «архивный терминал»
     ============================================================ -->
<section class="pa-hero">

    <!-- Левая колонка -->
    <div class="pa-hero__left">
        <div class="pa-hero__inner">

            <h1 class="pa-hero__title">
                Современное<br>искусство<br>и&nbsp;культура
            </h1>

            <p class="pa-hero__slash">/<br>Архив</p>

            <div class="pa-hero__status">
                <p class="pa-hero__status-line">INDEX_STATUS: <span class="pa-hero__status-val">LIVE</span></p>
                <p class="pa-hero__status-line">LAST_UPDATE: <?php echo esc_html( date( 'd.m.Y_H:i' ) ); ?></p>
                <p class="pa-hero__status-line">FILE_PATH: /ARCHIVE/CULTURAL/CONTEMPORARY</p>
                <p class="pa-hero__status-line pa-hero__status-line--error">SYNC_ERROR_0X004</p>
            </div>

            <p class="pa-hero__desc">
                Кино. Музыка. Литература. Визуальная культура. Эссе, критика, теория. Никакого PR. Только контекст.
            </p>

            <div class="pa-hero__actions">
                <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-hero__btn pa-hero__btn--primary">
                    Открыть архив
                </a>
                <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="pa-hero__btn pa-hero__btn--outline">
                    Читать журнал
                </a>
            </div>

            <div class="pa-hero__access">
                <p>ACCESS LEVEL: PUBLIC (LIMITED)</p>
                <p class="pa-hero__access--warn">SOME RECORDS ARE DISPUTED</p>
                <p>THIS ARCHIVE IS NOT NEUTRAL</p>
            </div>

            <div class="pa-hero__session">
                SESSION: ACTIVE &nbsp;|&nbsp; INDEXING: ON &nbsp;|&nbsp; VISITOR TAGS: UNKNOWN
            </div>

        </div>
    </div>

    <!-- Правая колонка -->
    <div class="pa-hero__right">
        <div class="pa-hero__right-frame">

            <div class="pa-hero__img-wrap">
                <div class="pa-hero__img-meta">
                    <span>PA24S04S0073</span>
                    <span>COORDINATES: 55.76°N, 37.6°E</span>
                </div>
                <div class="pa-hero__img-date"><?php echo esc_html( mb_strtoupper( date_i18n( 'M d Y' ) ) ); ?></div>
                <span class="pa-hero__stamp">DISPUTED</span>
                <?php $hero_img = get_template_directory_uri() . '/assets/img/hero-main.jpg'; ?>
                <img src="<?php echo esc_url( $hero_img ); ?>" alt="Palime Archive — визуальная культура" class="pa-hero__image">
                <div class="pa-hero__img-bottom">
                    FIELD_NOTE: Visual culture<br>
                    artifact pending<br>
                    verification protocol
                </div>
            </div>

            <div class="pa-hero__exhibit">
                <table class="pa-hero__exhibit-table">
                    <tr><td>EXHIBIT ID</td><td>PA-2026-021</td></tr>
                    <tr><td>MEDIUM</td><td>VISUAL CULTURE</td></tr>
                    <tr><td>STATUS</td><td class="pa-hero__exhibit-disputed">DISPUTED</td></tr>
                    <tr><td>CONNECTED</td><td>12</td></tr>
                </table>
            </div>

        </div>
    </div>

</section>


<!-- ============================================================
     2. ИНДЕКС ПО МЕДИУМУ
     ============================================================ -->
<section class="pa-section">
    <div class="container">

        <h2 class="pa-section__title">Индекс по медиуму</h2>
        <p class="pa-section__sub">Четыре каталога. Один фильтр.</p>

        <div class="pa-medium-grid">

            <?php
            $mediums = [
                [ 'slug' => 'literature', 'name' => 'Литература', 'code' => 'PA-2026-001', 'color' => '#4A3428', 'img' => 'section-lit.jpg',
                  'desc' => 'Тексты, которые верят форме и&nbsp;смыслу. Без литературного шума.' ],
                [ 'slug' => 'cinema', 'name' => 'Кино', 'code' => 'PA-2026-002', 'color' => '#4DB7FF', 'img' => 'section-cinema.jpg',
                  'desc' => 'Кино как мышление: кадр, ритм и&nbsp;смысл. Всё «поверхностное».' ],
                [ 'slug' => 'music', 'name' => 'Музыка', 'code' => 'PA-2026-003', 'color' => '#FF4FA3', 'img' => 'section-music.jpg',
                  'desc' => 'Звук, который не просит внимания. Он требует его.' ],
                [ 'slug' => 'art', 'name' => 'Визуальное', 'code' => 'PA-2026-004', 'color' => '#C6A25A', 'img' => 'section-art.jpg',
                  'desc' => 'Работы, которые остаются сложны. Контекст и&nbsp;память.' ],
            ];

            foreach ( $mediums as $m ) :
                $img_url = get_template_directory_uri() . '/assets/img/' . $m['img'];
            ?>
                <a href="<?php echo esc_url( home_url( '/' . $m['slug'] . '/' ) ); ?>" class="pa-medium-card" style="--card-accent:<?php echo esc_attr( $m['color'] ); ?>">
                    <div class="pa-medium-card__image">
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>">
                        <span class="pa-medium-card__accent"></span>
                    </div>
                    <div class="pa-medium-card__body">
                        <p class="pa-medium-card__code"><?php echo esc_html( $m['code'] ); ?> · <?php echo esc_html( mb_strtoupper( $m['name'] ) ); ?> · ACTIVE</p>
                        <h3 class="pa-medium-card__name"><?php echo esc_html( $m['name'] ); ?></h3>
                        <p class="pa-medium-card__desc"><?php echo $m['desc']; ?></p>
                        <div class="pa-medium-card__tags">
                            <span class="pa-medium-card__tag">Эссе</span>
                            <span class="pa-medium-card__tag">Рецензия</span>
                            <span class="pa-medium-card__tag">Досье</span>
                        </div>
                        <span class="pa-medium-card__btn">Открыть индекс →</span>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>

    </div>
</section>


<!-- ============================================================
     3. НЕ ЛЕНТА, А АРХИВ
     ============================================================ -->
<section class="pa-section pa-manifest-section">
    <div class="container">
        <div class="pa-manifest">

            <h2 class="pa-manifest__title">Не&nbsp;лента,<br>а&nbsp;архив</h2>

            <div class="pa-manifest__body">
                <p>Поток стирает контекст. Palime собирает материалы обратно&nbsp;&mdash; в&nbsp;систему, где тексты, новости и&nbsp;досье можно не&nbsp;только читать, но&nbsp;и&nbsp;связывать между собой.</p>
                <p>Здесь материалы не&nbsp;растворяются в&nbsp;публикационном шуме, а&nbsp;входят в&nbsp;структуру: по&nbsp;медиумам, формам, темам и&nbsp;внутренним связям архива.</p>
            </div>

            <div class="pa-manifest__points">
                <div class="pa-manifest__point">
                    <span class="pa-manifest__point-label">Структура</span>
                    <p class="pa-manifest__point-desc">Материалы собраны по&nbsp;направлениям, а&nbsp;не&nbsp;исчезают в&nbsp;потоке.</p>
                </div>
                <div class="pa-manifest__point">
                    <span class="pa-manifest__point-label">Связи</span>
                    <p class="pa-manifest__point-desc">Каждый материал существует в&nbsp;контексте других текстов, новостей и&nbsp;досье.</p>
                </div>
                <div class="pa-manifest__point">
                    <span class="pa-manifest__point-label">Возврат</span>
                    <p class="pa-manifest__point-desc">К&nbsp;архиву можно возвращаться, перечитывать и&nbsp;открывать новые маршруты чтения.</p>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     4. ЖИВОЙ ИНДЕКС
     ============================================================ -->
<section class="pa-section pa-section--dark pa-live-section">
    <div class="container">

        <div class="pa-live__header">
            <div class="pa-live__header-left">
                <p class="pa-live__label"><span class="pa-live__dot"></span> COMPLETE REGISTRY</p>
                <h2 class="pa-live__heading">Live Index</h2>
                <p class="pa-live__desc">Живая карта материалов, связей и новых поступлений в&nbsp;Palime.</p>
                <p class="pa-live__meta">ARCHIVE SIZE: <?php echo esc_html( number_format_i18n( $total_count ) ); ?> ENTRIES &nbsp;|&nbsp; LAST UPDATE: <span class="pa-live__meta-accent">TODAY</span></p>
            </div>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-live__link">Полный реестр →</a>
        </div>

        <div class="pa-live__controls">
            <div class="pa-live__filters">
                <button class="pa-live-filter is-active" data-filter="all">Все</button>
                <button class="pa-live-filter" data-filter="lit">Литература</button>
                <button class="pa-live-filter" data-filter="cinema">Кино</button>
                <button class="pa-live-filter" data-filter="music">Музыка</button>
                <button class="pa-live-filter" data-filter="art">Искусство</button>
            </div>
            <div class="pa-live__sort">
                <button class="pa-live-tab is-active" data-tab="newest">Новое</button>
                <button class="pa-live-tab" data-tab="popular">Популярное</button>
                <button class="pa-live-tab" data-tab="best">Связанное</button>
            </div>
        </div>

        <div class="pa-live__list" id="pa-live-list">
            <?php foreach ( $rows as $i => $row ) :
                $is_real = $row['url'] !== '#';
                $accent  = isset( $section_colors[ $row['medium_slug'] ] ) ? $section_colors[ $row['medium_slug'] ] : 'rgba(255,255,255,0.15)';
            ?>
                <<?php echo $is_real ? 'a href="' . esc_url( $row['url'] ) . '"' : 'div'; ?> class="pa-live-entry <?php echo ! $is_real ? 'pa-live-entry--empty' : ''; ?>" style="--entry-accent:<?php echo esc_attr( $accent ); ?>" data-medium="<?php echo esc_attr( $row['medium_slug'] ); ?>">
                    <span class="pa-live-entry__accent"></span>
                    <div class="pa-live-entry__meta">
                        <span class="pa-live-entry__id"><?php echo esc_html( $row['id'] ); ?></span>
                        <?php if ( $is_real && $row['status'] ) : ?>
                            <span class="pa-live-entry__status"><?php echo esc_html( $row['status'] ); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="pa-live-entry__body">
                        <div class="pa-live-entry__tags">
                            <span class="pa-live-entry__medium"><?php echo esc_html( $row['medium'] ); ?></span>
                            <span class="pa-live-entry__form"><?php echo esc_html( $row['form'] ); ?></span>
                        </div>
                        <h3 class="pa-live-entry__title"><?php echo esc_html( $row['title'] ); ?></h3>
                    </div>
                    <div class="pa-live-entry__right">
                        <span class="pa-live-entry__min"><?php echo esc_html( $row['min'] ); ?> мин</span>
                        <span class="pa-live-entry__date"><?php echo esc_html( $row['date'] ); ?></span>
                    </div>
                    <?php if ( $is_real ) : ?>
                        <span class="pa-live-entry__arrow">→</span>
                    <?php endif; ?>
                </<?php echo $is_real ? 'a' : 'div'; ?>>
            <?php endforeach; ?>
        </div>

        <div class="pa-live__bottom">
            <p class="pa-live__footer-note">Live registry. Updated in real-time. Hover to reveal actions.</p>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-live__more">Показать весь архив →</a>
        </div>

    </div>
</section>


<!-- ============================================================
     5. ГОЛОСА АРХИВА
     ============================================================ -->
<section class="pa-section pa-section--dark">
    <div class="container">

        <div class="pa-voices__head">
            <p class="pa-voices__label">INTERNAL PUBLICATIONS</p>
            <h2 class="pa-voices__title">Голоса архива</h2>
            <p class="pa-voices__sub">Авторские тексты, заметки и разборы, которые рождаются внутри Palime. Часть материалов открыта всем, часть доступна по роли или подписке.</p>
        </div>

        <div class="pa-voices__grid">

            <div class="pa-voices__card">
                <div class="pa-voices__card-head">
                    <span class="pa-voices__card-status pa-voices__card-status--public">PUBLIC</span>
                    <span class="pa-voices__card-id">PA-V-001</span>
                </div>
                <h3 class="pa-voices__card-title">Открытый материал</h3>
                <p class="pa-voices__card-desc">Первый авторский материал сообщества появится здесь. Открытая публикация для всех читателей архива.</p>
                <div class="pa-voices__card-footer">
                    <span class="pa-voices__card-access">Доступ: все читатели</span>
                    <span class="pa-voices__card-cta">Скоро в архиве</span>
                </div>
            </div>

            <div class="pa-voices__card">
                <div class="pa-voices__card-head">
                    <span class="pa-voices__card-status pa-voices__card-status--role">ROLE ACCESS</span>
                    <span class="pa-voices__card-id">PA-V-002</span>
                </div>
                <h3 class="pa-voices__card-title">Материал по роли</h3>
                <p class="pa-voices__card-desc">Часть авторских материалов открывается через прогресс внутри системы Palime: роли, достижения и уровень участия.</p>
                <div class="pa-voices__card-footer">
                    <span class="pa-voices__card-access">Доступ: Архивист+</span>
                    <a href="<?php echo esc_url( home_url( '/rewards/' ) ); ?>" class="pa-voices__card-cta pa-voices__card-cta--link">О ролях и доступе →</a>
                </div>
            </div>

            <div class="pa-voices__card">
                <div class="pa-voices__card-head">
                    <span class="pa-voices__card-status pa-voices__card-status--supporter">SUPPORTER</span>
                    <span class="pa-voices__card-id">PA-V-003</span>
                </div>
                <h3 class="pa-voices__card-title">Материал поддержки</h3>
                <p class="pa-voices__card-desc">Отдельные тексты и специальные публикации будут доступны участникам поддержки и подписки.</p>
                <div class="pa-voices__card-footer">
                    <span class="pa-voices__card-access">Доступ: Меценат</span>
                    <a href="<?php echo esc_url( home_url( '/patron/' ) ); ?>" class="pa-voices__card-cta pa-voices__card-cta--link">Поддержать архив →</a>
                </div>
            </div>

        </div>

        <p class="pa-voices__note">Первые публикации займут это место по мере развития сообщества и запуска авторской линии Palime.</p>

    </div>
</section>


<!-- ============================================================
     6. НОВОСТИ
     ============================================================ -->
<?php
$news = new WP_Query( [
    'post_type'              => 'news',
    'posts_per_page'         => 4,
    'post_status'            => 'publish',
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
] );

$news_items = [];
if ( $news->have_posts() ) {
    while ( $news->have_posts() ) {
        $news->the_post();
        $n_id    = get_the_ID();
        $n_terms = get_the_terms( $n_id, 'section' );
        $n_slug  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->slug : '';
        $n_name  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->name : '—';
        $n_color = isset( $section_colors[ $n_slug ] ) ? $section_colors[ $n_slug ] : '#D91515';
        $news_items[] = [
            'id'      => $n_id,
            'title'   => get_the_title(),
            'url'     => get_permalink(),
            'excerpt' => wp_trim_words( get_the_excerpt(), 20, '...' ),
            'date'    => get_the_date( 'd.m.Y' ),
            'time'    => human_time_diff( get_the_time( 'U' ), current_time( 'timestamp' ) ),
            'section' => $n_name,
            'slug'    => $n_slug,
            'color'   => $n_color,
        ];
    }
    wp_reset_postdata();
}
?>
<section class="pa-section pa-section--border pa-news-section">
    <div class="container">

        <div class="pa-news__head">
            <div>
                <h2 class="pa-news__title">Новости</h2>
                <p class="pa-news__sub">То, что происходит в культуре прямо сейчас.</p>
            </div>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="pa-news__all">Все новости →</a>
        </div>

        <?php if ( ! empty( $news_items ) ) :
            $featured = $news_items[0];
            $rest     = array_slice( $news_items, 1, 3 );
        ?>
            <div class="pa-news__layout">

                <a href="<?php echo esc_url( $featured['url'] ); ?>" class="pa-news__featured" style="--news-accent:<?php echo esc_attr( $featured['color'] ); ?>">
                    <div class="pa-news__featured-top">
                        <span class="pa-news__tag" style="background:<?php echo esc_attr( $featured['color'] ); ?>"><?php echo esc_html( $featured['section'] ); ?></span>
                        <span class="pa-news__time"><?php echo esc_html( $featured['time'] ); ?> назад</span>
                    </div>
                    <h3 class="pa-news__featured-title"><?php echo esc_html( $featured['title'] ); ?></h3>
                    <p class="pa-news__featured-excerpt"><?php echo esc_html( $featured['excerpt'] ); ?></p>
                    <div class="pa-news__featured-bottom">
                        <span class="pa-news__date"><?php echo esc_html( $featured['date'] ); ?></span>
                        <span class="pa-news__read">Читать →</span>
                    </div>
                </a>

                <div class="pa-news__sidebar">
                    <?php foreach ( $rest as $item ) : ?>
                        <a href="<?php echo esc_url( $item['url'] ); ?>" class="pa-news__compact" style="--news-accent:<?php echo esc_attr( $item['color'] ); ?>">
                            <div class="pa-news__compact-top">
                                <span class="pa-news__tag pa-news__tag--sm" style="background:<?php echo esc_attr( $item['color'] ); ?>"><?php echo esc_html( $item['section'] ); ?></span>
                                <span class="pa-news__time"><?php echo esc_html( $item['date'] ); ?></span>
                            </div>
                            <h4 class="pa-news__compact-title"><?php echo esc_html( $item['title'] ); ?></h4>
                            <span class="pa-news__compact-arrow">→</span>
                        </a>
                    <?php endforeach;

                    for ( $i = count( $rest ); $i < 3; $i++ ) : ?>
                        <div class="pa-news__compact pa-news__compact--empty">
                            <span class="pa-news__tag pa-news__tag--sm" style="background:rgba(255,255,255,0.1)">——</span>
                            <h4 class="pa-news__compact-title">————————————————</h4>
                        </div>
                    <?php endfor; ?>
                </div>

            </div>

        <?php else : ?>
            <div class="pa-news__empty">
                <p>НОВОСТНАЯ ЛЕНТА ФОРМИРУЕТСЯ</p>
                <p>Актуальные события культуры появятся здесь по мере публикации.</p>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- ============================================================
     7. О ПРОЕКТЕ
     ============================================================ -->
<section class="pa-section pa-section--alt pa-about-section">
    <div class="container">

        <div class="pa-about">
            <div class="pa-about__main">
                <p class="pa-about__label">SYSTEM OVERVIEW</p>
                <h2 class="pa-about__title">О&nbsp;проекте</h2>

                <div class="pa-about__text">
                    <p>Большая часть культурного контента исчезает в&nbsp;потоке. Palime&nbsp;Archive собирает его обратно&mdash; в&nbsp;живую систему материалов, индексов и&nbsp;связей между литературой, кино, музыкой и&nbsp;искусством.</p>
                    <p>Тексты, новости, разборы и&nbsp;досье здесь не&nbsp;существуют поодиночке. Каждый материал становится частью более широкой структуры&nbsp;&mdash; связан с&nbsp;темами, формами и&nbsp;другими объектами архива.</p>
                    <p>Palime устроен не&nbsp;как блог, а&nbsp;как система навигации внутри культуры. Сюда приходят не&nbsp;только читать, но&nbsp;и&nbsp;двигаться по&nbsp;связям: через архив, Live&nbsp;Index, роли, доступ и&nbsp;авторские линии.</p>
                </div>

                <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="pa-about__btn">Подробнее о Palime →</a>
            </div>

            <div class="pa-about__pillars">
                <div class="pa-about__pillar">
                    <span class="pa-about__pillar-num">01</span>
                    <h3 class="pa-about__pillar-title">Архив</h3>
                    <p class="pa-about__pillar-desc">Не&nbsp;поток публикаций, а&nbsp;устойчивая система материалов.</p>
                </div>
                <div class="pa-about__pillar">
                    <span class="pa-about__pillar-num">02</span>
                    <h3 class="pa-about__pillar-title">Связи</h3>
                    <p class="pa-about__pillar-desc">Каждый материал существует в&nbsp;контексте тем, форм и&nbsp;других объектов архива.</p>
                </div>
                <div class="pa-about__pillar">
                    <span class="pa-about__pillar-num">03</span>
                    <h3 class="pa-about__pillar-title">Участие</h3>
                    <p class="pa-about__pillar-desc">Palime развивается как пространство чтения, прогресса и&nbsp;авторского присутствия.</p>
                </div>
            </div>
        </div>

    </div>
</section>


<!-- ============================================================
     8. МАГАЗИН — ТЕКУЩАЯ КОЛЛЕКЦИЯ
     ============================================================ -->
<?php
$shop_products = null;
if ( class_exists( 'WooCommerce' ) ) {
    $shop_query = new WP_Query( [
        'post_type'              => 'product',
        'posts_per_page'         => 4,
        'post_status'            => 'publish',
        'update_post_meta_cache' => true,
    ] );
    if ( $shop_query->have_posts() ) {
        $shop_products = $shop_query->posts;
    }
    wp_reset_postdata();
}
?>
<section class="pa-section pa-section--dark">
    <div class="container">

        <div class="pa-shop__head">
            <h2 class="pa-section__title pa-section__title--light">
                <?php
                if ( $shop_products ) {
                    echo esc_html( get_post_meta( $shop_products[0]->ID, 'collection_title', true ) ?: 'ТЕКУЩАЯ КОЛЛЕКЦИЯ' );
                } else {
                    echo 'ТЕКУЩАЯ КОЛЛЕКЦИЯ';
                }
                ?>
            </h2>
            <p class="pa-shop__sub">Лимитированные артефакты. Одна тема — одна идея.</p>
            <p class="pa-shop__meta">КАТАЛОГ: АКТИВЕН · ТИРАЖ: ОГРАНИЧЕН</p>
        </div>

        <div class="pa-shop__about">
            <div class="pa-shop__about-head">
                <span>О ВЫПУСКЕ</span>
                <span class="pa-shop__about-status">Активен</span>
            </div>
            <div class="pa-shop__about-grid">
                <div>
                    <p class="pa-shop__about-label">Тема</p>
                    <p class="pa-shop__about-val">
                        <?php
                        if ( $shop_products ) {
                            $coll_theme = function_exists( 'get_field' ) ? get_field( 'collection_theme', $shop_products[0]->ID ) : '';
                            echo esc_html( $coll_theme ?: 'Тема коллекции определяется' );
                        } else {
                            echo 'Тема коллекции определяется';
                        }
                        ?>
                    </p>
                </div>
                <div>
                    <p class="pa-shop__about-label">Формат</p>
                    <p class="pa-shop__about-val">Четыре товара. Четыре раздела.</p>
                </div>
                <div>
                    <p class="pa-shop__about-label">Смысл</p>
                    <p class="pa-shop__about-val">Не что купить. А что помнить.</p>
                </div>
            </div>
        </div>

        <?php if ( $shop_products ) : ?>
            <div class="pa-shop__grid">
                <?php foreach ( $shop_products as $product_post ) :
                    $p_id         = $product_post->ID;
                    $section_slug = get_post_meta( $p_id, 'section', true );
                    $accent       = isset( $section_colors[ $section_slug ] ) ? $section_colors[ $section_slug ] : '#D91515';
                    $price        = get_post_meta( $p_id, '_price', true );
                    $stock        = get_post_meta( $p_id, '_stock', true );
                    $stock_total  = get_post_meta( $p_id, '_stock_total', true );
                    $img          = get_the_post_thumbnail_url( $p_id, 'large' );
                    $p_section_t  = get_the_terms( $p_id, 'section' );
                    $p_section_nm = ( $p_section_t && ! is_wp_error( $p_section_t ) ) ? $p_section_t[0]->name : ( $section_slug ?: '—' );
                ?>
                    <div class="pa-product" style="--product-accent:<?php echo esc_attr( $accent ); ?>">
                        <span class="pa-product__badge"><?php echo esc_html( $p_section_nm ); ?></span>
                        <div class="pa-product__image">
                            <?php if ( $img ) : ?>
                                <img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title( $p_id ) ); ?>">
                            <?php else : ?>
                                <div class="pa-product__placeholder">ФОТО</div>
                            <?php endif; ?>
                        </div>
                        <div class="pa-product__info">
                            <p class="pa-product__code"><?php echo esc_html( $p_id ); ?> · <?php echo esc_html( $stock_total ?: '—' ); ?> / <?php echo esc_html( $stock ?: '—' ); ?></p>
                            <h4 class="pa-product__name"><?php echo esc_html( get_the_title( $p_id ) ); ?></h4>
                            <p class="pa-product__excerpt"><?php echo esc_html( wp_trim_words( $product_post->post_excerpt ?: $product_post->post_content, 8, '...' ) ); ?></p>
                            <div class="pa-product__footer">
                                <span class="pa-product__price"><?php echo $price ? esc_html( $price ) . ' ₽' : '—'; ?></span>
                                <span class="pa-product__stock">ДОСТУПНО</span>
                            </div>
                            <a href="<?php echo esc_url( get_permalink( $p_id ) ); ?>" class="pa-product__buy">Купить →</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div class="pa-empty pa-empty--dark">КОЛЛЕКЦИЯ ГОТОВИТСЯ К ЗАПУСКУ</div>
        <?php endif; ?>

        <div class="pa-shop__cta">
            <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="pa-hero__btn pa-hero__btn--outline">В магазин →</a>
        </div>

    </div>
</section>


<!-- ============================================================
     9. ПРИСОЕДИНИТЬСЯ К АРХИВУ
     ============================================================ -->
<section class="pa-section pa-section--dark pa-join-section">
    <div class="container">

        <div class="pa-join__head">
            <p class="pa-join__label">ACCESS PROTOCOL</p>
            <h2 class="pa-join__title">Присоединиться к&nbsp;архиву</h2>
            <p class="pa-join__sub">Войти в систему Palime можно двумя путями: через участие или через поддержку проекта.</p>
        </div>

        <div class="pa-join__paths">

            <!-- Путь 1: Участие -->
            <div class="pa-join__path pa-join__path--earn">
                <div class="pa-join__path-head">
                    <span class="pa-join__path-tag">БЕСПЛАТНО</span>
                    <span class="pa-join__path-num">01</span>
                </div>
                <h3 class="pa-join__path-title">Через участие</h3>
                <p class="pa-join__path-desc">Получай XP за чтение, комментарии, сохранения и другую активность. Повышай уровень. Открывай роли, достижения и доступ к закрытым материалам постепенно.</p>
                <ul class="pa-join__path-list">
                    <li>XP за действия</li>
                    <li>8 уровней прогресса</li>
                    <li>Достижения и награды</li>
                    <li>Доступ к закрытым материалам</li>
                </ul>
                <a href="<?php echo esc_url( home_url( '/join/' ) ); ?>" class="pa-join__path-btn">Как работает система →</a>
            </div>

            <!-- Путь 2: Поддержка -->
            <div class="pa-join__path pa-join__path--patron">
                <div class="pa-join__path-head">
                    <span class="pa-join__path-tag pa-join__path-tag--accent">ПОДДЕРЖКА</span>
                    <span class="pa-join__path-num">02</span>
                </div>
                <h3 class="pa-join__path-title">Через поддержку</h3>
                <p class="pa-join__path-desc">Для тех, кто хочет получить доступ сразу или поддержать развитие независимого архива. Отдельная роль Мецената с привилегиями.</p>
                <ul class="pa-join__path-list">
                    <li>Прямой доступ ко всему</li>
                    <li>Поддержка проекта</li>
                    <li>Привилегии и ранний доступ</li>
                    <li>Статус «Меценат»</li>
                </ul>
                <a href="<?php echo esc_url( home_url( '/patron/' ) ); ?>" class="pa-join__path-btn pa-join__path-btn--accent">Поддержать архив →</a>
            </div>

        </div>

        <!-- Цепочка прогресса -->
        <div class="pa-join__chain">
            <div class="pa-join__chain-row">
                <?php
                $roles = [ 'Читатель', 'Свидетель', 'Архивист', 'Куратор', 'Интерпретатор', 'Носитель', 'Апостол', 'Архонт' ];
                foreach ( $roles as $i => $role ) :
                ?>
                    <span class="pa-join__chain-role"><?php echo esc_html( $role ); ?></span>
                    <?php if ( $i < count( $roles ) - 1 ) : ?>
                        <span class="pa-join__chain-arrow">→</span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <p class="pa-join__chain-note">Роли открываются через участие, достижения и прогресс внутри архива.</p>
        </div>

        <!-- Подписка -->
        <div class="pa-join__form-wrap">
            <form class="pa-join__form" id="pa-subscribe-form">
                <input type="email" placeholder="Email для подписки" class="pa-join__input" required>
                <button type="submit" class="pa-join__submit">Подписаться</button>
            </form>

            <?php
            $tg = get_option( 'palime_telegram_url' );
            $vk = get_option( 'palime_vk_url' );
            if ( $tg || $vk ) :
            ?>
                <div class="pa-join__social">
                    <?php if ( $tg ) : ?><a href="<?php echo esc_url( $tg ); ?>" class="pa-join__social-link">TELEGRAM →</a><?php endif; ?>
                    <?php if ( $vk ) : ?><a href="<?php echo esc_url( $vk ); ?>" class="pa-join__social-link">VK →</a><?php endif; ?>
                </div>
            <?php endif; ?>

            <p class="pa-join__disclaimer">Никакого спама. Только контекст.</p>
        </div>

    </div>
</section>


<!-- JS: живой индекс — вкладки + фильтры -->
<script>
(function() {
    var list    = document.getElementById('pa-live-list');
    var tabs    = document.querySelectorAll('.pa-live-tab');
    var filters = document.querySelectorAll('.pa-live-filter');
    if (!list) return;

    var sectionColors = <?php echo wp_json_encode( $section_colors ); ?>;

    function escHtml(s) {
        var d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function renderEntries(rows) {
        var html = '';
        rows.forEach(function(row) {
            var isReal = row.url !== '#';
            var slug   = row.medium_slug || '';
            var accent = sectionColors[slug] || 'rgba(255,255,255,0.15)';
            var tag    = isReal ? 'a' : 'div';
            var href   = isReal ? ' href="' + escHtml(row.url) + '"' : '';

            html += '<' + tag + href + ' class="pa-live-entry' + (!isReal ? ' pa-live-entry--empty' : '') + '" style="--entry-accent:' + accent + '" data-medium="' + escHtml(slug) + '">';
            html += '<span class="pa-live-entry__accent"></span>';
            html += '<div class="pa-live-entry__meta">';
            html += '<span class="pa-live-entry__id">' + escHtml(row.id) + '</span>';
            if (isReal) html += '<span class="pa-live-entry__status">NEW</span>';
            html += '</div>';
            html += '<div class="pa-live-entry__body">';
            html += '<div class="pa-live-entry__tags">';
            html += '<span class="pa-live-entry__medium">' + escHtml(row.medium) + '</span>';
            html += '<span class="pa-live-entry__form">' + escHtml(row.form) + '</span>';
            html += '</div>';
            html += '<h3 class="pa-live-entry__title">' + escHtml(row.title) + '</h3>';
            html += '</div>';
            html += '<div class="pa-live-entry__right">';
            html += '<span class="pa-live-entry__min">' + escHtml(row.min) + ' мин</span>';
            html += '<span class="pa-live-entry__date">' + (row.date || '\u2014') + '</span>';
            html += '</div>';
            if (isReal) html += '<span class="pa-live-entry__arrow">\u2192</span>';
            html += '</' + tag + '>';
        });
        list.innerHTML = html;
    }

    function loadTab(tab) {
        var fd = new FormData();
        fd.append('action', 'palime_live_index');
        fd.append('nonce', palimeData.nonce);
        fd.append('tab', tab);

        fetch(palimeData.ajaxUrl, { method: 'POST', body: fd })
            .then(function(r) { return r.json(); })
            .then(function(res) {
                if (res.success && res.data.rows) {
                    renderEntries(res.data.rows);
                }
            });
    }

    tabs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            tabs.forEach(function(b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            loadTab(btn.getAttribute('data-tab'));
        });
    });

    filters.forEach(function(btn) {
        btn.addEventListener('click', function() {
            filters.forEach(function(b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');
            var medium = btn.getAttribute('data-filter');
            var entries = list.querySelectorAll('.pa-live-entry');
            entries.forEach(function(entry) {
                if (medium === 'all' || entry.getAttribute('data-medium') === medium) {
                    entry.style.display = '';
                } else {
                    entry.style.display = 'none';
                }
            });
        });
    });
})();
</script>

<?php get_footer(); ?>
