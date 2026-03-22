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
    'post_type'              => [ 'article', 'news' ],
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

        $s_terms = get_the_terms( $pid, 'section' );
        $medium  = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';

        $form = 'Новость';
        if ( get_post_type( $pid ) === 'article' ) {
            $at   = get_the_terms( $pid, 'article-type' );
            $form = ( $at && ! is_wp_error( $at ) ) ? $at[0]->name : 'Статья';
        }

        $min = function_exists( 'get_field' ) ? get_field( 'reading_time', $pid ) : '';

        $rows[] = [
            'id'     => 'PA-' . get_the_date( 'Y' ) . '-' . str_pad( $pid, 3, '0', STR_PAD_LEFT ),
            'title'  => get_the_title(),
            'url'    => get_permalink(),
            'medium' => $medium,
            'form'   => $form,
            'min'    => $min ? $min : '—',
        ];
    }
    wp_reset_postdata();
}

while ( count( $rows ) < 10 ) {
    $rows[] = [
        'id'     => 'PA-——-———',
        'title'  => '———————————————',
        'url'    => '#',
        'medium' => '——————',
        'form'   => '————',
        'min'    => '——',
    ];
}

$total_count = (int) wp_count_posts( 'article' )->publish + (int) wp_count_posts( 'news' )->publish;

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

            <div class="pa-hero__terminal">
                <p class="pa-hero__meta-line">ДЕЛО_ID: PA-2026-001</p>
                <p class="pa-hero__meta-line pa-hero__meta-line--dim">КООРДИНАТЫ: 55.7558°N, 37.6173°E</p>
            </div>

            <h1 class="pa-hero__title">
                Современное<br>искусство<br>и&nbsp;культура
            </h1>

            <p class="pa-hero__slash">/ АРХИВ</p>

            <div class="pa-hero__status">
                <p class="pa-hero__status-line">ИНДЕКС_СТАТУС: <span class="pa-hero__status-val">АКТИВЕН</span></p>
                <p class="pa-hero__status-line">ПОСЛЕДНЕЕ_ОБНОВЛЕНИЕ: <?php echo esc_html( date( 'd.m.Y_H:i' ) ); ?></p>
                <p class="pa-hero__status-line">ПУТЬ_ФАЙЛА: /АРХИВ/КУЛЬТУРА/СОВРЕМЕННОЕ</p>
                <p class="pa-hero__status-line pa-hero__status-line--error">СИНХР_ОШИБКА_00034</p>
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
                <p>УРОВЕНЬ_ДОСТУПА: ПУБЛИЧНЫЙ (ОГРАНИЧЕН)</p>
                <p class="pa-hero__access--warn">НЕКОТОРЫЕ ЗАПИСИ ОСПОРЕНЫ</p>
                <p>ЭТОТ АРХИВ НЕ НЕЙТРАЛЕН</p>
            </div>

            <div class="pa-hero__session">
                СЕССИЯ: АКТИВНА &nbsp;|&nbsp; ИНДЕКСАЦИЯ: ВКЛ &nbsp;|&nbsp; ТЕГИ ПОСЕТИТЕЛЯ: НЕИЗВЕСТНЫ
            </div>

        </div>
    </div>

    <!-- Правая колонка -->
    <div class="pa-hero__right">

        <div class="pa-hero__img-meta">
            <span>ДЕЛО_ID: PA-2026-001</span>
            <span>КООРДИНАТЫ: 55.7N, 37.6E</span>
        </div>

        <div class="pa-hero__img-date"><?php echo esc_html( mb_strtoupper( date_i18n( 'M d Y' ) ) ); ?></div>

        <?php
        $hero_img = get_template_directory_uri() . '/assets/img/hero-main.jpg';
        ?>
        <img src="<?php echo esc_url( $hero_img ); ?>" alt="Palime Archive — визуальная культура" class="pa-hero__image">

        <div class="pa-hero__exhibit">
            <table class="pa-hero__exhibit-table">
                <tr>
                    <td>ИДЕНТИФИКАТОР</td>
                    <td>PA-2026-031</td>
                </tr>
                <tr>
                    <td>МЕДИУМ</td>
                    <td>ВИЗУАЛЬНАЯ КУЛЬТУРА</td>
                </tr>
                <tr>
                    <td>СТАТУС</td>
                    <td class="pa-hero__exhibit-disputed">СПОРНЫЙ</td>
                </tr>
                <tr>
                    <td>ПОДКЛЮЧЕНО</td>
                    <td>12</td>
                </tr>
            </table>
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
<section class="pa-section pa-section--border">
    <div class="container">
        <div class="pa-manifest">

            <div class="pa-manifest__text">
                <h2 class="pa-manifest__title">Не лента,<br>а архив</h2>
                <div class="pa-manifest__body">
                    <p>Мы не гонимся за трендами. Мы не публикуем пресс-релизы под видом критики. Мы не путаем маркетинг со смыслом.</p>
                    <p>Каждый артефакт входит в систему: с&nbsp;тегами, индексом, перекрёстными ссылками. Кино связано с&nbsp;теорией. Музыка переплетена с&nbsp;литературой.</p>
                    <p>Это не контент. Это контекст.<br>Это живое досье.</p>
                </div>
            </div>

            <div class="pa-manifest__index">
                <table class="pa-index-table">
                    <tr><td class="pa-index-table__num">01</td><td class="pa-index-table__label">Медиум</td><td class="pa-index-table__values">Кино · Музыка · Литература · ИЗО · Теория</td></tr>
                    <tr><td class="pa-index-table__num">02</td><td class="pa-index-table__label">Форма</td><td class="pa-index-table__values">Эссе · Рецензия · Интервью · Досье · Индекс</td></tr>
                    <tr><td class="pa-index-table__num">03</td><td class="pa-index-table__label">Тема</td><td class="pa-index-table__values">Память · Власть · Идентичность · Место · Время</td></tr>
                    <tr><td class="pa-index-table__num">04</td><td class="pa-index-table__label">Связи</td><td class="pa-index-table__values">Кросс-реф · Контекст · Поток · Сеть</td></tr>
                </table>

                <div class="pa-manifest__tags">
                    <span class="tag tag--filled">Спорное</span>
                    <span class="tag">Активное</span>
                    <span class="tag" style="opacity:.5">В архиве</span>
                    <span class="tag">Подтверждено</span>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     4. ЖИВОЙ ИНДЕКС
     ============================================================ -->
<section class="pa-section pa-section--dark">
    <div class="container">

        <div class="pa-live__header">
            <div class="pa-live__title">
                <span class="pa-live__dot"></span>
                <span>Живой индекс</span>
                <span class="pa-live__count">РАЗМЕР АРХИВА: <?php echo esc_html( $total_count ); ?> ЗАПИСЕЙ · ОБНОВЛЕНО: СЕГОДНЯ</span>
            </div>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-live__link">Полный реестр →</a>
        </div>

        <div class="pa-live__tabs">
            <button class="pa-live-tab is-active" data-tab="newest">Новейшие</button>
            <button class="pa-live-tab" data-tab="popular">Популярные</button>
            <button class="pa-live-tab" data-tab="today">Обновлено сегодня</button>
            <button class="pa-live-tab" data-tab="editor">Выбор редактора</button>
        </div>

        <div class="pa-live__table-wrap">
            <table class="pa-live__table">
                <thead>
                    <tr>
                        <th class="pa-live__th" style="width:130px">ID</th>
                        <th class="pa-live__th">Заголовок</th>
                        <th class="pa-live__th hide-mobile" style="width:110px">Медиум</th>
                        <th class="pa-live__th hide-mobile" style="width:90px">Форма</th>
                        <th class="pa-live__th hide-mobile" style="width:55px">Мин</th>
                        <th class="pa-live__th" style="width:30px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $rows as $row ) :
                        $is_real = $row['url'] !== '#';
                    ?>
                        <tr class="pa-live-row <?php echo ! $is_real ? 'pa-live-row--empty' : ''; ?>">
                            <td><?php echo esc_html( $row['id'] ); ?></td>
                            <td>
                                <?php if ( $is_real ) : ?>
                                    <a href="<?php echo esc_url( $row['url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html( $row['title'] ); ?>
                                <?php endif; ?>
                            </td>
                            <td class="hide-mobile"><?php echo esc_html( $row['medium'] ); ?></td>
                            <td class="hide-mobile"><?php echo esc_html( $row['form'] ); ?></td>
                            <td class="hide-mobile"><?php echo esc_html( $row['min'] ); ?></td>
                            <td><?php if ( $is_real ) : ?><a href="<?php echo esc_url( $row['url'] ); ?>" class="pa-live__arrow">→</a><?php endif; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <p class="pa-live__quote">Этот архив собирает то, что культура пытается забыть.</p>

    </div>
</section>


<!-- ============================================================
     5. ВЫБОР РЕДАКЦИИ
     ============================================================ -->
<?php
$editorial = new WP_Query( [
    'post_type'              => 'article',
    'posts_per_page'         => 5,
    'post_status'            => 'publish',
    'tax_query'              => [ [
        'taxonomy' => 'status',
        'field'    => 'slug',
        'terms'    => 'редакция',
    ] ],
    'update_post_meta_cache' => false,
] );
?>
<section class="pa-section">
    <div class="container">

        <div class="pa-section__head">
            <h2 class="pa-section__title">Выбор редакции</h2>
            <span class="pa-section__note">Редакционная подборка недели</span>
        </div>

        <?php if ( $editorial->have_posts() ) :
            $ed_posts = $editorial->posts;
            $featured = $ed_posts[0];
            $rest     = array_slice( $ed_posts, 1, 4 );

            $f_id      = $featured->ID;
            $f_terms   = get_the_terms( $f_id, 'section' );
            $f_medium  = ( $f_terms && ! is_wp_error( $f_terms ) ) ? $f_terms[0]->name : '—';
            $f_at      = get_the_terms( $f_id, 'article-type' );
            $f_form    = ( $f_at && ! is_wp_error( $f_at ) ) ? $f_at[0]->name : 'Статья';
            $f_reading = function_exists( 'get_field' ) ? get_field( 'reading_time', $f_id ) : '';
            $f_thumb   = get_the_post_thumbnail_url( $f_id, 'large' );
            $f_author  = get_the_author_meta( 'display_name', $featured->post_author );
        ?>
            <div class="pa-editorial">

                <div class="pa-editorial__featured">
                    <p class="pa-editorial__issue">ВЫПУСК #<?php echo esc_html( str_pad( $f_id, 3, '0', STR_PAD_LEFT ) ); ?></p>
                    <p class="pa-editorial__meta"><?php echo esc_html( $f_medium ); ?> · <?php echo esc_html( $f_form ); ?></p>
                    <h3 class="pa-editorial__title"><?php echo esc_html( get_the_title( $f_id ) ); ?></h3>
                    <p class="pa-editorial__author">
                        <?php echo esc_html( $f_author ); ?><?php if ( $f_reading ) : ?> · <?php echo esc_html( $f_reading ); ?> мин<?php endif; ?>
                    </p>
                    <?php if ( $f_thumb ) : ?>
                        <div class="pa-editorial__thumb">
                            <img src="<?php echo esc_url( $f_thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $f_id ) ); ?>">
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( get_permalink( $f_id ) ); ?>" class="pa-editorial__cta">Читать сейчас →</a>
                </div>

                <div class="pa-editorial__grid">
                    <?php
                    $ed_counter = 2;
                    foreach ( $rest as $ed_post ) :
                        $ep_id      = $ed_post->ID;
                        $ep_terms   = get_the_terms( $ep_id, 'section' );
                        $ep_medium  = ( $ep_terms && ! is_wp_error( $ep_terms ) ) ? $ep_terms[0]->name : '—';
                        $ep_reading = function_exists( 'get_field' ) ? get_field( 'reading_time', $ep_id ) : '';
                    ?>
                        <a href="<?php echo esc_url( get_permalink( $ep_id ) ); ?>" class="pa-editorial__card">
                            <div>
                                <p class="pa-editorial__card-id">#<?php echo esc_html( str_pad( $ep_id, 3, '0', STR_PAD_LEFT ) ); ?> · <?php echo esc_html( $ep_medium ); ?></p>
                                <h4 class="pa-editorial__card-title"><?php echo esc_html( get_the_title( $ep_id ) ); ?></h4>
                                <p class="pa-editorial__card-meta"><?php echo esc_html( $ep_medium ); ?><?php if ( $ep_reading ) : ?> · <?php echo esc_html( $ep_reading ); ?> мин<?php endif; ?></p>
                            </div>
                            <span class="pa-editorial__card-arrow">→</span>
                        </a>
                    <?php
                        $ed_counter++;
                    endforeach;

                    while ( $ed_counter <= 5 ) : ?>
                        <div class="pa-editorial__card pa-editorial__card--empty"><p>—</p></div>
                    <?php $ed_counter++; endwhile; ?>
                </div>

            </div>
        <?php else : ?>
            <div class="pa-empty">РЕДАКЦИЯ ГОТОВИТ ПОДБОРКУ · СЛЕДИТЕ ЗА ОБНОВЛЕНИЯМИ</div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     6. НОВОСТИ
     ============================================================ -->
<?php
$news = new WP_Query( [
    'post_type'              => 'news',
    'posts_per_page'         => 6,
    'post_status'            => 'publish',
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
] );
?>
<section class="pa-section pa-section--alt">
    <div class="container">

        <div class="pa-section__head">
            <h2 class="pa-section__title">Новости</h2>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="pa-section__note pa-section__note--link">Все новости →</a>
        </div>

        <?php if ( $news->have_posts() ) : ?>
            <div class="pa-news-list">
                <?php while ( $news->have_posts() ) : $news->the_post();
                    $n_id    = get_the_ID();
                    $n_terms = get_the_terms( $n_id, 'section' );
                    $n_slug  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->slug : '';
                    $n_name  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->name : '—';
                    $n_color = isset( $section_colors[ $n_slug ] ) ? $section_colors[ $n_slug ] : '#D91515';
                ?>
                    <div class="pa-news-row">
                        <span class="pa-news-row__tag" style="background:<?php echo esc_attr( $n_color ); ?>"><?php echo esc_html( $n_name ); ?></span>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="pa-news-row__title"><?php echo esc_html( get_the_title() ); ?></a>
                        <span class="pa-news-row__date hide-mobile"><?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?></span>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="pa-news-row__arrow" style="color:<?php echo esc_attr( $n_color ); ?>">→</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div class="pa-news-list">
                <?php for ( $i = 0; $i < 6; $i++ ) : ?>
                    <div class="pa-news-row pa-news-row--empty">
                        <span>——</span><span>————————————————</span><span>——.——.————</span><span>—</span>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     7. О ПРОЕКТЕ
     ============================================================ -->
<section class="pa-section pa-section--alt">
    <div class="container">
        <div class="pa-about">
            <h2 class="pa-section__title">О проекте</h2>
            <p class="pa-about__text">
                Palime Archive — независимый медиаархив современной культуры. Мы систематизируем кино, литературу, музыку и визуальное искусство с глубиной и вниманием, которых они заслуживают. Каждый материал — это исследование: не рецензия, а разбор. Не мнение, а аргумент. Мы верим, что культура — не развлечение, а способ понимания мира.
            </p>
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="pa-about__btn">Подробнее</a>
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
<section class="pa-section pa-section--dark">
    <div class="container">

        <div class="pa-join__head">
            <h2 class="pa-section__title pa-section__title--light">Присоединиться к архиву</h2>
            <p class="pa-join__sub">Выберите уровень. Все письма написаны вручную.</p>
        </div>

        <div class="pa-join__grid">
            <?php
            $levels = [
                [ 'name' => 'ЧИТАТЕЛЬ', 'desc' => 'Доступ к материалам и живому индексу' ],
                [ 'name' => 'АРХИВИСТ', 'desc' => 'Голосование, комментарии, сохранение статей' ],
                [ 'name' => 'КУРАТОР',  'desc' => 'Ранний доступ к дропам и рейтингам' ],
                [ 'name' => 'ХРАНИТЕЛЬ','desc' => 'Полный доступ, влияние на контент' ],
            ];
            foreach ( $levels as $lvl ) : ?>
                <div class="pa-join__card">
                    <p class="pa-join__card-name"><?php echo esc_html( $lvl['name'] ); ?></p>
                    <p class="pa-join__card-desc"><?php echo esc_html( $lvl['desc'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

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

            <p class="pa-join__disclaimer">Никакого спама. Только контекст. Можно отписаться в любой момент.</p>
        </div>

    </div>
</section>


<!-- JS: вкладки живого индекса -->
<script>
document.querySelectorAll('.pa-live-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.pa-live-tab').forEach(function(b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
    });
});
</script>

<?php get_footer(); ?>
