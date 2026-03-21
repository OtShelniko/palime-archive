<?php
/**
 * Template Name: Раздел — Кино
 * Template Post Type: page
 *
 * Palime Archive — page-cinema.php
 * Страница раздела Кино (/cinema) — секции 1–8
 *
 * @package Palime_Archive
 */

get_header();

$section_slug  = 'cinema';
$accent        = '#4DB7FF';
$bg_dark       = '#0A1020';

$tax_query = [ [
    'taxonomy' => 'section',
    'field'    => 'slug',
    'terms'    => $section_slug,
] ];
?>

<!-- ====================================================
     1. ЗАСТАВКА
     ==================================================== -->
<section class="section-hero-cinema" style="background:<?php echo esc_attr( $bg_dark ); ?>; min-height:100vh; display:grid; grid-template-columns:1fr 1fr; color:#fff; position:relative; overflow:hidden;">

    <!-- Левая колонка -->
    <div style="padding:60px; display:flex; flex-direction:column; justify-content:center;">

        <p style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.15em; text-transform:uppercase; margin-bottom:24px;">
            CINEMA · SECTION_01 · INDEX: ACTIVE
        </p>

        <h1 style="font-family:var(--font-display); font-size:clamp(3rem,6vw,5.5rem); line-height:1; color:#fff; margin:0 0 24px;">
            КИНОАРХИВ<br>PALIME
        </h1>

        <p style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.6; opacity:.85; max-width:480px; margin-bottom:24px;">
            Режиссёры, работы, подборки, разборы. Кино как форма власти, памяти и стиля.
        </p>

        <blockquote style="border-left:3px solid <?php echo esc_attr( $accent ); ?>; padding-left:16px; margin:0 0 32px; font-family:var(--font-serif); font-size:.95rem; font-style:italic; opacity:.8; max-width:440px; line-height:1.5;">
            Не рейтинг ради рейтинга. Рейтинг как следствие метода.
        </blockquote>

        <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:40px;">
            <a href="<?php echo esc_url( home_url( '/archive/?section=cinema' ) ); ?>"
               style="display:inline-block; padding:10px 24px; border:1px solid <?php echo esc_attr( $accent ); ?>; color:<?php echo esc_attr( $accent ); ?>; font-family:var(--font-mono); font-size:11px; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; transition:var(--transition);">
                ОТКРЫТЬ КАТАЛОГ
            </a>
            <a href="<?php echo esc_url( home_url( '/rankings/?section=cinema' ) ); ?>"
               style="display:inline-block; padding:10px 24px; border:1px solid rgba(255,255,255,.4); color:#fff; font-family:var(--font-mono); font-size:11px; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; transition:var(--transition);">
                СМОТРЕТЬ РЕЙТИНГИ
            </a>
        </div>

        <p style="font-family:var(--font-mono); font-size:10px; opacity:.5; letter-spacing:.12em; text-transform:uppercase;">
            ПРОЕКТОР: ВКЛ &nbsp;|&nbsp; АРХИВ: АКТИВЕН &nbsp;|&nbsp; ШУМ: ВЫСОКИЙ
        </p>

    </div>

    <!-- Правая колонка — постер-коллаж -->
    <?php
    $hero_cinema = new WP_Query( [
        'post_type'              => 'article',
        'posts_per_page'         => 1,
        'post_status'            => 'publish',
        'tax_query'              => $tax_query,
        'update_post_meta_cache' => false,
    ] );
    $cinema_img = '';
    if ( $hero_cinema->have_posts() ) {
        $hero_cinema->the_post();
        $cinema_img = get_the_post_thumbnail_url( null, 'full' );
        wp_reset_postdata();
    }
    ?>

    <div style="position:relative; display:flex; align-items:center; justify-content:center; padding:40px;">

        <?php if ( $cinema_img ) : ?>
            <div style="width:100%; height:100%; min-height:400px; background-image:url(<?php echo esc_url( $cinema_img ); ?>); background-size:cover; background-position:center; border:1px solid rgba(77,183,255,0.3); position:relative;">
        <?php else : ?>
            <div style="width:100%; height:100%; min-height:400px; background:#0d1a2e; border:1px solid rgba(77,183,255,0.3); display:flex; flex-direction:column; align-items:center; justify-content:center; position:relative;">
                <span style="font-size:48px; margin-bottom:16px;">⚡</span>
                <span style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.15em; text-transform:uppercase;">POSTER COLLAGE PANEL</span>
        <?php endif; ?>

                <!-- Overlay карточка внизу -->
                <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.7); padding:12px; font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; display:flex; align-items:center; justify-content:space-between;">
                    <span>REEL: 03 / CUT: FINAL / STATUS: VERIFIED</span>
                    <span style="background:<?php echo esc_attr( $accent ); ?>; color:#fff; padding:2px 8px; font-size:9px; letter-spacing:.1em;">SCREENING</span>
                </div>

            </div>

    </div>

</section>
<!-- /ЗАСТАВКА -->

<style>
@media (max-width: 768px) {
    .section-hero-cinema { grid-template-columns: 1fr !important; }
    .cinema-cards-grid { grid-template-columns: 1fr !important; }
    .cinema-rating-hide-mobile { display: none !important; }
    .cinema-monthly-grid { grid-template-columns: 1fr !important; }
    .cinema-about-grid { grid-template-columns: 1fr !important; }
    .cinema-shop-grid { grid-template-columns: 1fr !important; }
}
</style>


<!-- ====================================================
     2. СВЕЖИЕ МАТЕРИАЛЫ
     ==================================================== -->
<section style="background:var(--color-bg); padding:80px 0;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <div style="margin-bottom:48px;">
            <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-text); opacity:.5; letter-spacing:.15em; text-transform:uppercase; margin-bottom:8px;">
                NOW SCREENING
            </p>
            <h2 style="font-family:var(--font-display); font-size:clamp(1.6rem,3vw,2.2rem); color:var(--color-text); margin:0;">
                СВЕЖИЕ МАТЕРИАЛЫ
            </h2>
        </div>

        <!-- Вкладки -->
        <div style="display:flex; gap:8px; margin-bottom:32px; flex-wrap:wrap;">
            <button class="pa-tab active" data-tab="author"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.08em; padding:8px 16px; border:1px solid var(--color-text); background:var(--color-text); color:var(--color-bg); cursor:pointer; text-transform:uppercase;">
                ПРО АВТОРА
            </button>
            <button class="pa-tab" data-tab="work"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.08em; padding:8px 16px; border:1px solid rgba(0,0,0,.2); background:transparent; color:var(--color-text); cursor:pointer; text-transform:uppercase;">
                ПРО ПРОИЗВЕДЕНИЕ
            </button>
            <button class="pa-tab" data-tab="selection"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.08em; padding:8px 16px; border:1px solid rgba(0,0,0,.2); background:transparent; color:var(--color-text); cursor:pointer; text-transform:uppercase;">
                ПОДБОРКИ
            </button>
        </div>

        <?php
        $cinema_fresh = new WP_Query( [
            'post_type'              => 'article',
            'posts_per_page'         => 9,
            'post_status'            => 'publish',
            'tax_query'              => $tax_query,
            'update_post_meta_cache' => true,
        ] );
        ?>

        <?php if ( $cinema_fresh->have_posts() ) : ?>
            <div class="cinema-cards-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
                <?php $i = 1; while ( $cinema_fresh->have_posts() ) : $cinema_fresh->the_post(); ?>
                    <div style="border:1px dashed rgba(0,0,0,0.15); padding:24px;">
                        <p style="font-family:var(--font-mono); font-size:10px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.1em; margin-bottom:12px;">
                            WE-0<?php echo esc_html( $i ); ?>
                        </p>
                        <h3 style="font-family:var(--font-display); font-size:1.1rem; margin:0 0 12px; line-height:1.3;">
                            <?php echo esc_html( get_the_title() ); ?>
                        </h3>
                        <?php
                        $persons = get_the_terms( get_the_ID(), 'person' );
                        $eras    = get_the_terms( get_the_ID(), 'era' );
                        $tags_arr = [];
                        if ( $persons && ! is_wp_error( $persons ) ) {
                            foreach ( $persons as $t ) $tags_arr[] = $t->name;
                        }
                        if ( $eras && ! is_wp_error( $eras ) ) {
                            foreach ( $eras as $t ) $tags_arr[] = $t->name;
                        }
                        if ( $tags_arr ) : ?>
                            <div style="display:flex; gap:6px; flex-wrap:wrap; margin-bottom:12px;">
                                <?php foreach ( $tags_arr as $tag_name ) : ?>
                                    <span style="font-family:var(--font-mono); font-size:9px; background:var(--color-text); color:var(--color-bg); padding:2px 8px; letter-spacing:.05em;">
                                        <?php echo esc_html( $tag_name ); ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <p style="font-family:var(--font-mono); font-size:10px; color:var(--color-text); opacity:.5; margin-bottom:16px;">
                            TC: <?php echo esc_html( function_exists( 'get_field' ) && get_field( 'reading_time' ) ? get_field( 'reading_time' ) : '—' ); ?>:00:00
                        </p>
                        <a href="<?php echo esc_url( get_permalink() ); ?>"
                           style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; text-decoration:none; letter-spacing:.08em;">
                            OPEN CASE →
                        </a>
                    </div>
                <?php $i++; endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <!-- Заглушка — 3 пустые карточки -->
            <div class="cinema-cards-grid" style="display:grid; grid-template-columns:repeat(3,1fr); gap:24px;">
                <?php for ( $j = 1; $j <= 3; $j++ ) : ?>
                    <div style="border:1px dashed rgba(0,0,0,0.15); padding:24px; opacity:0.2;">
                        <p style="font-family:var(--font-mono); font-size:10px; color:<?php echo esc_attr( $accent ); ?>; margin-bottom:12px;">WE-0<?php echo esc_html( $j ); ?></p>
                        <h3 style="font-family:var(--font-display); font-size:1.1rem; margin:0 0 12px;">——————————</h3>
                        <p style="font-family:var(--font-mono); font-size:10px; opacity:.5; margin-bottom:16px;">TC: —:00:00</p>
                        <span style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>;">OPEN CASE →</span>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; ?>

        <div style="margin-top:40px;">
            <a href="<?php echo esc_url( home_url( '/archive/?section=cinema' ) ); ?>"
               style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; text-decoration:none; letter-spacing:.1em; text-transform:uppercase;">
                ВСЕ МАТЕРИАЛЫ →
            </a>
        </div>

    </div>
</section>
<!-- /СВЕЖИЕ МАТЕРИАЛЫ -->


<!-- ====================================================
     3. РЕЙТИНГИ
     ==================================================== -->
<section id="ratings" style="background:<?php echo esc_attr( $bg_dark ); ?>; padding:80px 0; color:#fff;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <div style="margin-bottom:12px;">
            <p style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; opacity:.6; letter-spacing:.15em; text-transform:uppercase; margin-bottom:8px;">
                РЕЙТИНГИ РАЗДЕЛА
            </p>
            <h2 style="font-family:var(--font-display); font-size:clamp(1.6rem,3vw,2.2rem); color:#fff; margin:0 0 32px;">
                THE CANON CUT
            </h2>
        </div>

        <!-- Вкладки рейтингов -->
        <div style="display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap;">
            <button class="pa-tab-rating active"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.06em; padding:8px 16px; border:1px solid #fff; background:#fff; color:<?php echo esc_attr( $bg_dark ); ?>; cursor:pointer; text-transform:uppercase;">
                ЛУЧШИЕ РЕЖИССЁРЫ (ТОП 50)
            </button>
            <button class="pa-tab-rating"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.06em; padding:8px 16px; border:1px solid rgba(255,255,255,.3); background:transparent; color:#fff; cursor:pointer; text-transform:uppercase;">
                ЛУЧШИЕ ФИЛЬМЫ (ТОП 50)
            </button>
            <button class="pa-tab-rating"
                    style="font-family:var(--font-mono); font-size:11px; letter-spacing:.06em; padding:8px 16px; border:1px solid rgba(255,255,255,.3); background:transparent; color:#fff; cursor:pointer; text-transform:uppercase;">
                ЛУЧШИЕ СЦЕНЫ (ТОП 20)
            </button>
        </div>

        <!-- Фильтры -->
        <div style="display:flex; gap:6px; margin-bottom:32px; flex-wrap:wrap;">
            <button class="pa-filter active"
                    style="font-family:var(--font-mono); font-size:10px; letter-spacing:.08em; padding:4px 12px; border:1px solid <?php echo esc_attr( $accent ); ?>; background:<?php echo esc_attr( $accent ); ?>; color:#fff; cursor:pointer; text-transform:uppercase;">
                ФОРМА
            </button>
            <button class="pa-filter"
                    style="font-family:var(--font-mono); font-size:10px; letter-spacing:.08em; padding:4px 12px; border:1px solid rgba(77,183,255,.4); background:transparent; color:<?php echo esc_attr( $accent ); ?>; cursor:pointer; text-transform:uppercase;">
                ЭМОЦИЯ
            </button>
            <button class="pa-filter"
                    style="font-family:var(--font-mono); font-size:10px; letter-spacing:.08em; padding:4px 12px; border:1px solid rgba(77,183,255,.4); background:transparent; color:<?php echo esc_attr( $accent ); ?>; cursor:pointer; text-transform:uppercase;">
                ИДЕЯ
            </button>
            <button class="pa-filter"
                    style="font-family:var(--font-mono); font-size:10px; letter-spacing:.08em; padding:4px 12px; border:1px solid rgba(77,183,255,.4); background:transparent; color:<?php echo esc_attr( $accent ); ?>; cursor:pointer; text-transform:uppercase;">
                ВЛИЯНИЕ
            </button>
        </div>

        <!-- Таблица (заглушка) -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-family:'IBM Plex Mono',monospace; font-size:12px; color:#fff;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(77,183,255,0.2); color:<?php echo esc_attr( $accent ); ?>; font-size:10px; text-transform:uppercase;">
                        <td style="padding:10px 0; width:50px;">РАНГ</td>
                        <td style="padding:10px 0;">НАЗВАНИЕ / ИМЯ</td>
                        <td style="padding:10px 0; width:80px;">ОЦЕНКА</td>
                        <td class="cinema-rating-hide-mobile" style="padding:10px 0; width:80px;">ВЛИЯНИЕ</td>
                        <td class="cinema-rating-hide-mobile" style="padding:10px 0; width:80px;">СПОРНО</td>
                        <td style="padding:10px 0; width:40px;">→</td>
                    </tr>
                </thead>
                <tbody>
                    <?php for ( $i = 1; $i <= 8; $i++ ) : ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05); opacity:0.2;">
                        <td style="padding:12px 0;"><?php echo esc_html( $i ); ?></td>
                        <td>——————————————</td>
                        <td>—.—</td>
                        <td class="cinema-rating-hide-mobile" style="color:<?php echo esc_attr( $accent ); ?>;">——</td>
                        <td class="cinema-rating-hide-mobile">——</td>
                        <td>→</td>
                    </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>

        <p style="font-family:var(--font-mono); font-size:10px; opacity:0.4; margin-top:24px; letter-spacing:.08em;">
            РЕЙТИНГИ ФОРМИРУЮТСЯ · ТРЕБУЕТСЯ ACF PRO · ДАННЫЕ ПОЯВЯТСЯ ПОСЛЕ УСТАНОВКИ
        </p>

    </div>
</section>
<!-- /РЕЙТИНГИ -->


<!-- ====================================================
     4. НОВОСТИ
     ==================================================== -->
<section style="background:var(--color-second); padding:80px 0;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <?php
        $cinema_news = new WP_Query( [
            'post_type'              => 'news',
            'posts_per_page'         => 6,
            'post_status'            => 'publish',
            'tax_query'              => $tax_query,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ] );
        ?>

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:32px; flex-wrap:wrap; gap:16px;">
            <h2 style="font-family:var(--font-display); font-size:clamp(1.6rem,3vw,2.2rem); color:var(--color-text); margin:0;">
                НОВОСТИ
            </h2>
            <a href="<?php echo esc_url( home_url( '/news/?section=cinema' ) ); ?>"
               style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; text-decoration:none; letter-spacing:.1em; text-transform:uppercase;">
                ВСЕ НОВОСТИ РАЗДЕЛА →
            </a>
        </div>

        <?php if ( $cinema_news->have_posts() ) : ?>
            <?php while ( $cinema_news->have_posts() ) : $cinema_news->the_post(); ?>
                <div style="display:grid; grid-template-columns:100px 1fr 120px 24px; gap:16px; padding:14px 0; border-bottom:1px solid rgba(0,0,0,0.08); align-items:center;">
                    <span style="background:<?php echo esc_attr( $accent ); ?>; color:#fff; font-family:var(--font-mono); font-size:10px; padding:2px 8px; text-align:center; letter-spacing:.06em; justify-self:start;">
                        КИНО
                    </span>
                    <a href="<?php echo esc_url( home_url( '/news/' . get_post_field( 'post_name' ) . '/' ) ); ?>"
                       style="font-family:var(--font-serif); font-size:.95rem; color:var(--color-text); text-decoration:none; line-height:1.4;">
                        <?php echo esc_html( get_the_title() ); ?>
                    </a>
                    <span style="font-family:var(--font-mono); font-size:10px; color:var(--color-text); opacity:.5;">
                        <?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
                    </span>
                    <span style="color:<?php echo esc_attr( $accent ); ?>; font-size:14px;">→</span>
                </div>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php else : ?>
            <!-- Заглушка — 6 пустых строк -->
            <?php for ( $k = 0; $k < 6; $k++ ) : ?>
                <div style="display:grid; grid-template-columns:100px 1fr 120px 24px; gap:16px; padding:14px 0; border-bottom:1px solid rgba(0,0,0,0.08); align-items:center; opacity:0.15;">
                    <span style="background:<?php echo esc_attr( $accent ); ?>; color:#fff; font-family:var(--font-mono); font-size:10px; padding:2px 8px; text-align:center; justify-self:start;">КИНО</span>
                    <span style="font-family:var(--font-mono); font-size:12px;">——————————————————</span>
                    <span style="font-family:var(--font-mono); font-size:10px;">——.——.————</span>
                    <span style="color:<?php echo esc_attr( $accent ); ?>;">→</span>
                </div>
            <?php endfor; ?>
        <?php endif; ?>

    </div>
</section>
<!-- /НОВОСТИ -->


<!-- ====================================================
     5. ЦИТАТА ДНЯ
     ==================================================== -->
<?php
$today = current_time( 'Y-m-d' );
$quote = new WP_Query( [
    'post_type'              => 'quote_of_day',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'tax_query'              => $tax_query,
    'date_query'             => [ [
        'year'  => date( 'Y', strtotime( $today ) ),
        'month' => date( 'm', strtotime( $today ) ),
        'day'   => date( 'd', strtotime( $today ) ),
    ] ],
    'update_post_meta_cache' => true,
] );

// Fallback: последняя цитата
if ( ! $quote->have_posts() ) {
    $quote = new WP_Query( [
        'post_type'              => 'quote_of_day',
        'posts_per_page'         => 1,
        'post_status'            => 'publish',
        'tax_query'              => $tax_query,
        'orderby'                => 'date',
        'order'                  => 'DESC',
        'update_post_meta_cache' => true,
    ] );
}
?>

<section style="background:<?php echo esc_attr( $bg_dark ); ?>; padding:60px 0; color:#fff;">
    <div style="max-width:700px; margin:0 auto; padding:0 var(--gutter); text-align:center;">

        <?php if ( $quote->have_posts() ) : $quote->the_post();
            $q_id     = get_the_ID();
            $q_text   = function_exists( 'get_field' ) ? get_field( 'quote_text', $q_id ) : '';
            $q_author = function_exists( 'get_field' ) ? get_field( 'quote_author', $q_id ) : '';
            $q_work   = function_exists( 'get_field' ) ? get_field( 'quote_work', $q_id ) : '';
            if ( ! $q_text ) $q_text = get_the_title();
        ?>

            <p style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.15em; text-transform:uppercase; margin-bottom:16px;">
                ЦИТАТА ДНЯ · <?php echo esc_html( date( 'd.m.Y', strtotime( $today ) ) ); ?>
            </p>

            <div style="font-family:var(--font-display); font-size:6rem; color:<?php echo esc_attr( $accent ); ?>; opacity:0.3; line-height:1; margin-bottom:-20px;">
                &ldquo;
            </div>

            <p style="font-family:var(--font-serif); font-size:1.4rem; color:#fff; line-height:1.7; font-style:italic; margin-bottom:24px;">
                <?php echo esc_html( $q_text ); ?>
            </p>

            <?php if ( $q_author || $q_work ) : ?>
                <p style="font-family:var(--font-mono); font-size:11px; opacity:.55; letter-spacing:.08em; margin-bottom:16px;">
                    — <?php if ( $q_author ) echo esc_html( $q_author ); ?><?php if ( $q_author && $q_work ) echo ' · '; ?><?php if ( $q_work ) echo esc_html( $q_work ); ?>
                </p>
            <?php endif; ?>

            <a href="<?php echo esc_url( get_permalink() ); ?>"
               style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; text-decoration:none; letter-spacing:.1em; text-transform:uppercase;">
                ЧИТАТЬ СТАТЬЮ →
            </a>

        <?php wp_reset_postdata(); else : ?>

            <p style="font-family:var(--font-mono); font-size:11px; opacity:0.3; color:#fff; letter-spacing:.08em;">
                ЦИТАТА ДНЯ · РЕДАКТОР ЗАПОЛНЯЕТ ЗАРАНЕЕ · СЕГОДНЯ ПУСТО
            </p>

        <?php endif; ?>

    </div>
</section>
<!-- /ЦИТАТА ДНЯ -->


<!-- ====================================================
     6. ЛУЧШЕЕ ЗА МЕСЯЦ
     ==================================================== -->
<?php
$monthly = new WP_Query( [
    'post_type'              => 'monthly_best',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'tax_query'              => $tax_query,
    'update_post_meta_cache' => true,
] );
?>

<section style="background:var(--color-bg); padding:80px 0;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <div style="margin-bottom:48px;">
            <p style="font-family:var(--font-mono); font-size:11px; color:var(--color-text); opacity:.5; letter-spacing:.15em; text-transform:uppercase; margin-bottom:8px;">
                <?php echo esc_html( strtoupper( date_i18n( 'F Y' ) ) ); ?>
            </p>
            <h2 style="font-family:var(--font-display); font-size:clamp(1.6rem,3vw,2.2rem); color:var(--color-text); margin:0;">
                ЛУЧШЕЕ ЗА МЕСЯЦ
            </h2>
        </div>

        <?php if ( $monthly->have_posts() ) : $monthly->the_post();
            $monthly_cats = [
                'monthly_films'     => 'ФИЛЬМЫ',
                'monthly_series'    => 'СЕРИАЛЫ',
                'monthly_animation' => 'АНИМАЦИЯ',
            ];
        ?>

            <div class="cinema-monthly-grid" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:24px;">
                <?php foreach ( $monthly_cats as $field_key => $cat_label ) :
                    $items = function_exists( 'get_field' ) ? get_field( $field_key ) : [];
                ?>
                    <div>
                        <h3 style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.1em; text-transform:uppercase; margin-bottom:16px;">
                            <?php echo esc_html( $cat_label ); ?>
                        </h3>
                        <?php if ( $items && is_array( $items ) ) : ?>
                            <?php foreach ( $items as $idx => $entry ) :
                                $entry_title = is_array( $entry ) ? ( $entry['title'] ?? $entry['name'] ?? $entry[0] ?? '' ) : $entry;
                            ?>
                                <div style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,0.08); display:flex; align-items:baseline; gap:12px;">
                                    <span style="font-family:var(--font-mono); font-size:10px; color:<?php echo esc_attr( $accent ); ?>;">
                                        <?php echo esc_html( str_pad( $idx + 1, 2, '0', STR_PAD_LEFT ) ); ?>
                                    </span>
                                    <span style="font-family:var(--font-serif); font-size:.95rem;">
                                        <?php echo esc_html( $entry_title ); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php for ( $p = 1; $p <= 5; $p++ ) : ?>
                                <div style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,0.08); opacity:0.2; display:flex; align-items:baseline; gap:12px;">
                                    <span style="font-family:var(--font-mono); font-size:10px; color:<?php echo esc_attr( $accent ); ?>;">
                                        <?php echo esc_html( str_pad( $p, 2, '0', STR_PAD_LEFT ) ); ?>
                                    </span>
                                    <span style="font-family:var(--font-mono); font-size:12px;">——————————</span>
                                </div>
                            <?php endfor; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php wp_reset_postdata(); else : ?>

            <div class="cinema-monthly-grid" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:24px;">
                <?php
                $stub_labels = [ 'ФИЛЬМЫ', 'СЕРИАЛЫ', 'АНИМАЦИЯ' ];
                foreach ( $stub_labels as $sl ) : ?>
                    <div>
                        <h3 style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.1em; margin-bottom:16px;">
                            <?php echo esc_html( $sl ); ?>
                        </h3>
                        <?php for ( $p = 1; $p <= 5; $p++ ) : ?>
                            <div style="padding:12px 0; border-bottom:1px solid rgba(0,0,0,0.08); opacity:0.2; display:flex; align-items:baseline; gap:12px;">
                                <span style="font-family:var(--font-mono); font-size:10px; color:<?php echo esc_attr( $accent ); ?>;">
                                    <?php echo esc_html( str_pad( $p, 2, '0', STR_PAD_LEFT ) ); ?>
                                </span>
                                <span style="font-family:var(--font-mono); font-size:12px;">——————————</span>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <p style="font-family:var(--font-mono); font-size:10px; opacity:0.4; margin-top:24px; letter-spacing:.08em; color:var(--color-text);">
                ИТОГИ МЕСЯЦА ФОРМИРУЮТСЯ
            </p>

        <?php endif; ?>

    </div>
</section>
<!-- /ЛУЧШЕЕ ЗА МЕСЯЦ -->


<!-- ====================================================
     7. О ПРОЕКТЕ
     ==================================================== -->
<?php
// Получаем последнюю статью для даты
$last_article_q = new WP_Query( [
    'post_type'      => 'article',
    'posts_per_page' => 1,
    'post_status'    => 'publish',
    'tax_query'      => $tax_query,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'fields'         => 'ids',
] );
$last_article_id   = $last_article_q->have_posts() ? $last_article_q->posts[0] : 0;
$last_article_date = $last_article_id ? get_the_date( 'd.m.Y', $last_article_id ) : '—';
wp_reset_postdata();

$article_count = wp_count_posts( 'article' );
$article_total = isset( $article_count->publish ) ? $article_count->publish : 0;
?>

<section style="background:var(--color-second); padding:80px 0;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <div class="cinema-about-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:60px; align-items:start;">

            <!-- Левая — манифест -->
            <div>
                <p style="font-family:var(--font-mono); font-size:11px; color:<?php echo esc_attr( $accent ); ?>; letter-spacing:.15em; text-transform:uppercase; margin-bottom:16px;">
                    CINEMA · О РАЗДЕЛЕ
                </p>
                <h2 style="font-family:var(--font-display); font-size:clamp(1.6rem,3vw,2.2rem); color:var(--color-text); margin:0 0 24px;">
                    О РАЗДЕЛЕ
                </h2>
                <p style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.8; color:var(--color-text); max-width:520px;">
                    Кино — не развлечение. Кино — это способ видеть мир. Мы разбираем фильмы как тексты, режиссёров как мыслителей, сцены как аргументы. Без рейтингов ради рейтингов. Только метод.
                </p>
            </div>

            <!-- Правая — статус-блок -->
            <div style="background:<?php echo esc_attr( $bg_dark ); ?>; padding:32px; font-family:var(--font-mono); color:#fff;">

                <div style="display:grid; grid-template-columns:140px 1fr; padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.08); font-size:11px;">
                    <span style="opacity:.5;">РАЗДЕЛ</span>
                    <span>КИНО</span>
                </div>
                <div style="display:grid; grid-template-columns:140px 1fr; padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.08); font-size:11px;">
                    <span style="opacity:.5;">СТАТУС</span>
                    <span style="color:<?php echo esc_attr( $accent ); ?>;">АКТИВЕН</span>
                </div>
                <div style="display:grid; grid-template-columns:140px 1fr; padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.08); font-size:11px;">
                    <span style="opacity:.5;">МАТЕРИАЛОВ</span>
                    <span><?php echo esc_html( $article_total ); ?></span>
                </div>
                <div style="display:grid; grid-template-columns:140px 1fr; padding:8px 0; border-bottom:1px solid rgba(255,255,255,0.08); font-size:11px;">
                    <span style="opacity:.5;">ПОСЛЕДНЕЕ</span>
                    <span><?php echo esc_html( $last_article_date ); ?></span>
                </div>
                <div style="display:grid; grid-template-columns:140px 1fr; padding:8px 0; font-size:11px;">
                    <span style="opacity:.5;">АКЦЕНТ</span>
                    <span style="color:<?php echo esc_attr( $accent ); ?>;"><?php echo esc_html( $accent ); ?></span>
                </div>

            </div>

        </div>

    </div>
</section>
<!-- /О ПРОЕКТЕ -->


<!-- ====================================================
     8. ПРЕВЬЮ МАГАЗИНА
     ==================================================== -->
<?php
$shop_item = null;
$shop_img  = '';
$shop_name = '';
$shop_desc = '';
$shop_price = '';

if ( class_exists( 'WooCommerce' ) ) {
    $sq = new WP_Query( [
        'post_type'              => 'product',
        'posts_per_page'         => 1,
        'post_status'            => 'publish',
        'update_post_meta_cache' => true,
    ] );
    if ( $sq->have_posts() ) {
        $sq->the_post();
        $shop_item  = get_post();
        $shop_name  = get_the_title();
        $shop_desc  = get_the_excerpt();
        $shop_img   = get_the_post_thumbnail_url( null, 'full' );
        $product    = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
        $shop_price = $product ? $product->get_price() : '';
        wp_reset_postdata();
    }
}
?>

<section style="background:#0A0A0A; padding:80px 0;">
    <div style="max-width:var(--container); margin:0 auto; padding:0 var(--gutter);">

        <?php if ( $shop_item ) : ?>
            <div class="cinema-shop-grid" style="display:grid; grid-template-columns:1fr 1fr; gap:2px;">

                <!-- Фото товара -->
                <div style="aspect-ratio:1/1; background:<?php echo $shop_img ? 'url(' . esc_url( $shop_img ) . ') center/cover no-repeat' : '#111'; ?>; display:flex; align-items:center; justify-content:center;">
                    <?php if ( ! $shop_img ) : ?>
                        <span style="font-family:var(--font-mono); font-size:11px; color:rgba(255,255,255,0.3); letter-spacing:.1em; text-transform:uppercase;">КОЛЛЕКЦИЯ ГОТОВИТСЯ</span>
                    <?php endif; ?>
                </div>

                <!-- Описание товара -->
                <div style="padding:40px; background:#111; color:#fff; display:flex; flex-direction:column; justify-content:center;">
                    <p style="font-family:var(--font-mono); font-size:10px; color:rgba(255,255,255,.5); letter-spacing:.15em; text-transform:uppercase; margin-bottom:16px;">
                        ТЕКУЩИЙ ДРОП · ТИРАЖ ОГРАНИЧЕН
                    </p>
                    <h2 style="font-family:var(--font-display); font-size:clamp(1.4rem,2.5vw,2rem); color:#fff; margin:0 0 16px;">
                        <?php echo esc_html( $shop_name ); ?>
                    </h2>
                    <?php if ( $shop_desc ) : ?>
                        <p style="font-family:var(--font-serif); font-size:.95rem; opacity:0.7; line-height:1.6; margin-bottom:20px;">
                            <?php echo esc_html( $shop_desc ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $shop_price ) : ?>
                        <p style="font-family:var(--font-mono); font-size:1.2rem; color:#D91515; margin-bottom:24px;">
                            <?php echo esc_html( $shop_price ); ?> ₽
                        </p>
                    <?php endif; ?>
                    <div>
                        <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>"
                           style="display:inline-block; padding:12px 28px; background:#D91515; color:#fff; font-family:var(--font-mono); font-size:11px; letter-spacing:.1em; text-transform:uppercase; text-decoration:none; transition:var(--transition);">
                            В МАГАЗИН →
                        </a>
                    </div>
                </div>

            </div>
        <?php else : ?>
            <div style="text-align:center; padding:60px; color:rgba(255,255,255,0.3); font-family:var(--font-mono); font-size:12px; letter-spacing:.08em;">
                КОЛЛЕКЦИЯ ГОТОВИТСЯ К ЗАПУСКУ ·
                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" style="color:#D91515; text-decoration:none;">В МАГАЗИН →</a>
            </div>
        <?php endif; ?>

    </div>
</section>
<!-- /ПРЕВЬЮ МАГАЗИНА -->


<!-- JS: переключение вкладок -->
<script>
document.querySelectorAll('.pa-tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pa-tab').forEach(b => {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = 'var(--color-text)';
            b.style.borderColor = 'rgba(0,0,0,.2)';
        });
        btn.classList.add('active');
        btn.style.background = 'var(--color-text)';
        btn.style.color = 'var(--color-bg)';
        btn.style.borderColor = 'var(--color-text)';
    });
});

document.querySelectorAll('.pa-tab-rating').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pa-tab-rating').forEach(b => {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = '#fff';
            b.style.borderColor = 'rgba(255,255,255,.3)';
        });
        btn.classList.add('active');
        btn.style.background = '#fff';
        btn.style.color = '#0A1020';
        btn.style.borderColor = '#fff';
    });
});

document.querySelectorAll('.pa-filter').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pa-filter').forEach(b => {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = '#4DB7FF';
            b.style.borderColor = 'rgba(77,183,255,.4)';
        });
        btn.classList.add('active');
        btn.style.background = '#4DB7FF';
        btn.style.color = '#fff';
        btn.style.borderColor = '#4DB7FF';
    });
});
</script>

<?php get_footer(); ?>
