<?php
/**
 * Template Name: Раздел — Кино
 * Template Post Type: page
 *
 * Palime Archive — page-cinema.php
 * Страница раздела Кино (/cinema) — секции 1–4
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
