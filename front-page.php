<?php
/**
 * Palime Archive — front-page.php
 * Главная страница: макет 1 в 1 по скриншотам
 *
 * @package Palime_Archive
 */

get_header();
?>

<main id="main" role="main">

<!-- ============================================================
     1. ГЕРОЙ
     ============================================================ -->
<section style="display:grid; grid-template-columns:1fr 1fr; min-height:100vh;">

    <!-- Левая колонка -->
    <div style="background:#0A0A0A; color:#fff; padding:60px; display:flex; flex-direction:column; justify-content:center;">

        <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.15em; text-transform:uppercase; color:rgba(255,255,255,0.35); margin-bottom:40px;">
            ARCHIVE · INDEXING: ON
        </p>

        <h1 style="font-family:'Trajan Pro 3',serif; font-size:clamp(2.4rem,5.5vw,4.5rem); line-height:1.05; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:16px;">
            Современное<br>искусство<br>и&nbsp;культура
        </h1>

        <p style="font-family:'IBM Plex Mono',monospace; font-size:clamp(1.4rem,3vw,2.2rem); color:#D91515; letter-spacing:0.15em; margin-bottom:24px;">
            / Архив
        </p>

        <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:rgba(255,255,255,0.3); letter-spacing:0.08em; line-height:1.6; margin-bottom:24px;">
            CASE_ID: PA-2026-021 · INDEX_STATUS: LIVE · LAST_UPDATE: <?php echo esc_html( date( 'd.m.Y' ) ); ?>
        </p>

        <p style="font-family:'EB Garamond',Georgia,serif; font-size:1rem; line-height:1.7; color:rgba(255,255,255,0.7); margin-bottom:40px; max-width:460px;">
            Кино. Музыка. Литература. Визуальная культура. Эссе, критика, теория. Никакого PR. Только контекст.
        </p>

        <div style="display:flex; gap:16px; flex-wrap:wrap; margin-bottom:40px;">
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" style="display:inline-flex; align-items:center; padding:10px 24px; background:#D91515; color:#fff; font-family:'IBM Plex Mono',monospace; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; border:1px solid #D91515; border-radius:4px; text-decoration:none; transition:all 0.15s ease;">
                Открыть архив
            </a>
            <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" style="display:inline-flex; align-items:center; padding:10px 24px; background:transparent; color:#fff; font-family:'IBM Plex Mono',monospace; font-size:0.8rem; letter-spacing:0.1em; text-transform:uppercase; border:1px solid rgba(255,255,255,0.4); border-radius:4px; text-decoration:none; transition:all 0.15s ease;">
                Читать журнал
            </a>
        </div>

        <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:rgba(255,255,255,0.2); letter-spacing:0.08em; line-height:1.6;">
            ACCESS LEVEL: PUBLIC · SOME RECORDS ARE DISPUTED · THIS ARCHIVE IS NOT NEUTRAL
        </p>

    </div>

    <!-- Правая колонка -->
    <div style="background-image:url(<?php echo esc_url( get_template_directory_uri() . '/assets/img/hero-main.jpg' ); ?>); background-size:cover; background-position:center; position:relative; min-height:100vh; background-color:#0A0A0A;">

        <div style="position:absolute; bottom:24px; right:24px; background:rgba(0,0,0,0.85); padding:16px; font-family:'IBM Plex Mono',monospace; color:#fff; font-size:11px; line-height:1.8;">
            EXHIBIT ID &nbsp;&nbsp; PA-2026-021<br>
            MEDIUM &nbsp;&nbsp;&nbsp;&nbsp; ВИЗУАЛЬНАЯ КУЛЬТУРА<br>
            STATUS &nbsp;&nbsp;&nbsp;&nbsp; <span style="color:#D91515;">DISPUTED</span><br>
            CONNECTED &nbsp; 12
        </div>

    </div>

</section>


<!-- ============================================================
     2. ИНДЕКС ПО МЕДИУМУ
     ============================================================ -->
<section style="background:var(--color-bg); padding:80px 0;">
    <div class="container">

        <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:8px;">
            Индекс по медиуму
        </h2>
        <p style="font-family:'IBM Plex Mono',monospace; font-size:0.85rem; opacity:0.5; margin-bottom:40px;">
            Четыре каталога. Один фильтр.
        </p>

        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:24px;">

            <!-- ЛИТЕРАТУРА -->
            <a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>" style="display:block; border-top:3px solid #4A3428; text-decoration:none; color:inherit;">
                <div style="aspect-ratio:4/3; overflow:hidden;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/section-lit.jpg' ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( 'Литература' ); ?>">
                </div>
                <div style="padding:20px 0;">
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.5; margin-bottom:8px;">PA-2026-001 · ЛИТЕРАТУРА · ACTIVE</p>
                    <h3 style="font-family:'Trajan Pro 3',serif; font-size:1.4rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:12px;">Литература</h3>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.85rem; opacity:0.7; line-height:1.5; margin-bottom:16px;">Тексты, которые верят форме и&nbsp;смыслу. Без литературного шума.</p>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4A3428; padding:2px 8px; color:#4A3428; text-transform:uppercase;">Эссе</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4A3428; padding:2px 8px; color:#4A3428; text-transform:uppercase;">Рецензия</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4A3428; padding:2px 8px; color:#4A3428; text-transform:uppercase;">Досье</span>
                    </div>
                    <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#4A3428; text-transform:uppercase; letter-spacing:0.08em;">Открыть индекс →</span>
                </div>
            </a>

            <!-- КИНО -->
            <a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>" style="display:block; border-top:3px solid #4DB7FF; text-decoration:none; color:inherit;">
                <div style="aspect-ratio:4/3; overflow:hidden;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/section-cinema.jpg' ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( 'Кино' ); ?>">
                </div>
                <div style="padding:20px 0;">
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.5; margin-bottom:8px;">PA-2026-002 · КИНО · ACTIVE</p>
                    <h3 style="font-family:'Trajan Pro 3',serif; font-size:1.4rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:12px;">Кино</h3>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.85rem; opacity:0.7; line-height:1.5; margin-bottom:16px;">Кино как мышление: кадр, ритм, и&nbsp;смысл. Всё «поверхностное».</p>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4DB7FF; padding:2px 8px; color:#4DB7FF; text-transform:uppercase;">Эссе</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4DB7FF; padding:2px 8px; color:#4DB7FF; text-transform:uppercase;">Рецензия</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #4DB7FF; padding:2px 8px; color:#4DB7FF; text-transform:uppercase;">Досье</span>
                    </div>
                    <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#4DB7FF; text-transform:uppercase; letter-spacing:0.08em;">Открыть индекс →</span>
                </div>
            </a>

            <!-- МУЗЫКА -->
            <a href="<?php echo esc_url( home_url( '/music/' ) ); ?>" style="display:block; border-top:3px solid #FF4FA3; text-decoration:none; color:inherit;">
                <div style="aspect-ratio:4/3; overflow:hidden;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/section-music.jpg' ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( 'Музыка' ); ?>">
                </div>
                <div style="padding:20px 0;">
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.5; margin-bottom:8px;">PA-2026-003 · МУЗЫКА · ACTIVE</p>
                    <h3 style="font-family:'Trajan Pro 3',serif; font-size:1.4rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:12px;">Музыка</h3>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.85rem; opacity:0.7; line-height:1.5; margin-bottom:16px;">Звук, который не просит внимания. Он требует его.</p>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #FF4FA3; padding:2px 8px; color:#FF4FA3; text-transform:uppercase;">Эссе</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #FF4FA3; padding:2px 8px; color:#FF4FA3; text-transform:uppercase;">Рецензия</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #FF4FA3; padding:2px 8px; color:#FF4FA3; text-transform:uppercase;">Досье</span>
                    </div>
                    <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#FF4FA3; text-transform:uppercase; letter-spacing:0.08em;">Открыть индекс →</span>
                </div>
            </a>

            <!-- ИЗО -->
            <a href="<?php echo esc_url( home_url( '/art/' ) ); ?>" style="display:block; border-top:3px solid #C6A25A; text-decoration:none; color:inherit;">
                <div style="aspect-ratio:4/3; overflow:hidden;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/section-art.jpg' ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( 'Визуальное' ); ?>">
                </div>
                <div style="padding:20px 0;">
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.5; margin-bottom:8px;">PA-2026-004 · ВИЗУАЛЬНОЕ · ACTIVE</p>
                    <h3 style="font-family:'Trajan Pro 3',serif; font-size:1.4rem; letter-spacing:0.06em; text-transform:uppercase; margin-bottom:12px;">Визуальное</h3>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.85rem; opacity:0.7; line-height:1.5; margin-bottom:16px;">Работы, которые остаются сложны. Но контекст и&nbsp;память.</p>
                    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:16px;">
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #C6A25A; padding:2px 8px; color:#C6A25A; text-transform:uppercase;">Эссе</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #C6A25A; padding:2px 8px; color:#C6A25A; text-transform:uppercase;">Рецензия</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; border:1px solid #C6A25A; padding:2px 8px; color:#C6A25A; text-transform:uppercase;">Досье</span>
                    </div>
                    <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#C6A25A; text-transform:uppercase; letter-spacing:0.08em;">Открыть индекс →</span>
                </div>
            </a>

        </div>

    </div>
</section>


<!-- ============================================================
     3. АРХИВ — НЕ БЛОГ
     ============================================================ -->
<section style="background:var(--color-bg); padding:80px 0; border-top:1px solid rgba(0,0,0,0.06);">
    <div class="container">
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:80px; align-items:start;">

            <!-- Левый блок: манифест -->
            <div>
                <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.8rem,3.5vw,2.8rem); letter-spacing:0.08em; line-height:1.1; text-transform:uppercase; margin-bottom:24px;">
                    Архив —<br>не блог
                </h2>
                <div style="font-family:'EB Garamond',Georgia,serif; font-size:1.05rem; line-height:1.8; opacity:0.85;">
                    <p>Мы не гонимся за трендами. Мы не публикуем пресс-релизы под видом критики. Мы не путаем маркетинг со смыслом.</p>
                    <p style="margin-top:16px;">Каждый артефакт входит в систему: с&nbsp;тегами, индексом, перекрёстными ссылками. Кино связано с&nbsp;теорией. Музыка переплетена с&nbsp;литературой.</p>
                    <p style="margin-top:16px;">Это не контент. Это контекст.<br>Это живое досье.</p>
                </div>
            </div>

            <!-- Правый блок: таблица индексации -->
            <div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.08);">
                        <td style="padding:16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.9rem; opacity:0.25; width:40px; vertical-align:top;">01</td>
                        <td style="padding:16px 16px 16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; opacity:0.5; width:80px; vertical-align:top; white-space:nowrap;">Медиум</td>
                        <td style="padding:16px 0; font-family:'EB Garamond',Georgia,serif; font-size:0.875rem; line-height:1.6; opacity:0.8; vertical-align:top;">Кино · Музыка · Литература · ИЗО · Теория</td>
                    </tr>
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.08);">
                        <td style="padding:16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.9rem; opacity:0.25; vertical-align:top;">02</td>
                        <td style="padding:16px 16px 16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; opacity:0.5; vertical-align:top; white-space:nowrap;">Форма</td>
                        <td style="padding:16px 0; font-family:'EB Garamond',Georgia,serif; font-size:0.875rem; line-height:1.6; opacity:0.8; vertical-align:top;">Эссе · Рецензия · Интервью · Досье · Индекс</td>
                    </tr>
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.08);">
                        <td style="padding:16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.9rem; opacity:0.25; vertical-align:top;">03</td>
                        <td style="padding:16px 16px 16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; opacity:0.5; vertical-align:top; white-space:nowrap;">Тема</td>
                        <td style="padding:16px 0; font-family:'EB Garamond',Georgia,serif; font-size:0.875rem; line-height:1.6; opacity:0.8; vertical-align:top;">Память · Власть · Идентичность · Место · Время</td>
                    </tr>
                    <tr style="border-bottom:1px solid rgba(0,0,0,0.08);">
                        <td style="padding:16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.9rem; opacity:0.25; vertical-align:top;">04</td>
                        <td style="padding:16px 16px 16px 0; font-family:'IBM Plex Mono',monospace; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.1em; opacity:0.5; vertical-align:top; white-space:nowrap;">Связи</td>
                        <td style="padding:16px 0; font-family:'EB Garamond',Georgia,serif; font-size:0.875rem; line-height:1.6; opacity:0.8; vertical-align:top;">Кросс-реф · Контекст · Поток · Сеть</td>
                    </tr>
                </table>

                <!-- Статусы-метки -->
                <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:24px;">
                    <span style="display:inline-block; padding:3px 10px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; border:1px solid #D91515; color:#D91515; border-radius:2px;">Спорное</span>
                    <span style="display:inline-block; padding:3px 10px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; border:1px solid #0A0A0A; color:#0A0A0A; border-radius:2px;">Активное</span>
                    <span style="display:inline-block; padding:3px 10px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; border:1px solid rgba(0,0,0,0.3); color:rgba(0,0,0,0.5); border-radius:2px;">В архиве</span>
                    <span style="display:inline-block; padding:3px 10px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; border:1px solid #0A0A0A; color:#0A0A0A; border-radius:2px;">Подтверждено</span>
                </div>
            </div>

        </div>
    </div>
</section>


<!-- ============================================================
     4. ЖИВОЙ ИНДЕКС
     ============================================================ -->
<?php
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

        // Раздел
        $s_terms = get_the_terms( $pid, 'section' );
        $medium  = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';

        // Форма
        $form = 'Новость';
        if ( get_post_type( $pid ) === 'article' ) {
            $at = get_the_terms( $pid, 'article-type' );
            $form = ( $at && ! is_wp_error( $at ) ) ? $at[0]->name : 'Статья';
        }

        // Время чтения
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

// Заглушки до 10 строк
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
?>
<section style="background:#0A0A0A; padding:80px 0;">
    <div class="container">

        <!-- Шапка -->
        <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:16px; border-bottom:1px solid rgba(255,255,255,0.12); margin-bottom:24px; flex-wrap:wrap; gap:16px;">
            <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
                <span style="display:flex; align-items:center; gap:8px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.15em; text-transform:uppercase; color:#fff;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#D91515; animation:pulse 1.5s ease infinite; display:inline-block;"></span>
                    Живой индекс
                </span>
                <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:rgba(255,255,255,0.35); letter-spacing:0.08em;">
                    РАЗМЕР АРХИВА: <?php echo esc_html( $total_count ); ?> ЗАПИСЕЙ · ОБНОВЛЕНО: СЕГОДНЯ
                </span>
            </div>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" style="display:inline-flex; align-items:center; padding:6px 14px; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.1em; text-transform:uppercase; color:#fff; border:1px solid rgba(255,255,255,0.25); border-radius:4px; text-decoration:none; transition:all 0.15s ease;">
                Полный реестр →
            </a>
        </div>

        <!-- Вкладки -->
        <div style="display:flex; gap:0; margin-bottom:24px; flex-wrap:wrap;">
            <button class="pa-tab active" data-tab="newest" style="padding:10px 20px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.25); color:#fff; font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border-radius:4px 0 0 4px; margin-right:-1px; transition:all 0.15s ease;">Новейшие</button>
            <button class="pa-tab" data-tab="popular" style="padding:10px 20px; background:transparent; border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.4); font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border-radius:0; margin-right:-1px; transition:all 0.15s ease;">Популярные</button>
            <button class="pa-tab" data-tab="today" style="padding:10px 20px; background:transparent; border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.4); font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border-radius:0; margin-right:-1px; transition:all 0.15s ease;">Обновлено сегодня</button>
            <button class="pa-tab" data-tab="editor" style="padding:10px 20px; background:transparent; border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.4); font-family:'IBM Plex Mono',monospace; font-size:0.7rem; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer; border-radius:0 4px 4px 0; transition:all 0.15s ease;">Выбор редактора</button>
        </div>

        <!-- Таблица -->
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; font-family:'IBM Plex Mono',monospace; font-size:12px; color:#fff;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.15);">
                        <td style="padding:10px 0; width:130px; opacity:0.5; text-transform:uppercase;">ID</td>
                        <td style="padding:10px 0; opacity:0.5; text-transform:uppercase;">Заголовок</td>
                        <td class="hide-mobile" style="padding:10px 0; width:110px; opacity:0.5; text-transform:uppercase;">Медиум</td>
                        <td class="hide-mobile" style="padding:10px 0; width:90px; opacity:0.5; text-transform:uppercase;">Форма</td>
                        <td class="hide-mobile" style="padding:10px 0; width:55px; opacity:0.5; text-transform:uppercase;">Мин</td>
                        <td style="padding:10px 0; width:30px;"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ( $rows as $row ) :
                        $is_real = $row['url'] !== '#';
                    ?>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);<?php echo ! $is_real ? 'opacity:0.2;' : ''; ?>"
                            <?php if ( $is_real ) : ?>
                                onmouseover="this.style.background='rgba(255,255,255,0.04)'"
                                onmouseout="this.style.background=''"
                            <?php endif; ?>>
                            <td style="padding:10px 0;"><?php echo esc_html( $row['id'] ); ?></td>
                            <td style="padding:10px 0;">
                                <?php if ( $is_real ) : ?>
                                    <a href="<?php echo esc_url( $row['url'] ); ?>" style="color:#fff; text-decoration:none; transition:color 0.15s ease;"><?php echo esc_html( $row['title'] ); ?></a>
                                <?php else : ?>
                                    <?php echo esc_html( $row['title'] ); ?>
                                <?php endif; ?>
                            </td>
                            <td class="hide-mobile" style="padding:10px 0;"><?php echo esc_html( $row['medium'] ); ?></td>
                            <td class="hide-mobile" style="padding:10px 0;"><?php echo esc_html( $row['form'] ); ?></td>
                            <td class="hide-mobile" style="padding:10px 0;"><?php echo esc_html( $row['min'] ); ?></td>
                            <td style="padding:10px 0;">
                                <?php if ( $is_real ) : ?>
                                    <a href="<?php echo esc_url( $row['url'] ); ?>" style="color:#D91515; text-decoration:none;">→</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Подпись -->
        <p style="font-family:'EB Garamond',Georgia,serif; font-style:italic; color:rgba(255,255,255,0.4); font-size:0.9rem; margin-top:24px;">
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
document.querySelectorAll('.pa-tab').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.pa-tab').forEach(function(b) {
            b.classList.remove('active');
            b.style.background = 'transparent';
            b.style.color = 'rgba(255,255,255,0.4)';
            b.style.borderColor = 'rgba(255,255,255,0.12)';
        });
        btn.classList.add('active');
        btn.style.background = 'rgba(255,255,255,0.08)';
        btn.style.color = '#fff';
        btn.style.borderColor = 'rgba(255,255,255,0.25)';
    });
});
</script>

<!-- CSS: адаптив героя и сетки разделов -->
<style>
@media (max-width: 1024px) {
    /* Герой: одна колонка */
    #main > section:first-child {
        grid-template-columns: 1fr !important;
    }
    #main > section:first-child > div:last-child {
        min-height: 50vh !important;
    }
    /* Разделы: 2×2 */
    #main > section:nth-child(2) .container > div:last-child {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    /* Архив не блог: одна колонка */
    #main > section:nth-child(3) .container > div {
        grid-template-columns: 1fr !important;
        gap: 40px !important;
    }
}
@media (max-width: 768px) {
    #main > section:first-child > div:first-child {
        padding: 40px 16px !important;
    }
    #main > section:nth-child(2),
    #main > section:nth-child(3),
    #main > section:nth-child(4) {
        padding: 40px 0 !important;
    }
}
@media (max-width: 480px) {
    /* Разделы: одна колонка */
    #main > section:nth-child(2) .container > div:last-child {
        grid-template-columns: 1fr !important;
    }
}
</style>

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
<section style="background:var(--color-bg); padding:80px 0;">
    <div class="container">
 
        <div style="display:flex; align-items:baseline; justify-content:space-between; margin-bottom:40px;">
            <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase;">
                Выбор редакции
            </h2>
            <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; opacity:0.4; text-transform:uppercase; letter-spacing:0.08em;">
                Редакционная подборка недели
            </span>
        </div>
 
        <?php if ( $editorial->have_posts() ) :
            $ed_posts = $editorial->posts;
            $featured = $ed_posts[0];
            $rest     = array_slice( $ed_posts, 1, 4 );
 
            // Featured data
            $f_id      = $featured->ID;
            $f_terms   = get_the_terms( $f_id, 'section' );
            $f_medium  = ( $f_terms && ! is_wp_error( $f_terms ) ) ? $f_terms[0]->name : '—';
            $f_at      = get_the_terms( $f_id, 'article-type' );
            $f_form    = ( $f_at && ! is_wp_error( $f_at ) ) ? $f_at[0]->name : 'Статья';
            $f_reading = function_exists( 'get_field' ) ? get_field( 'reading_time', $f_id ) : '';
            $f_thumb   = get_the_post_thumbnail_url( $f_id, 'large' );
            $f_author  = get_the_author_meta( 'display_name', $featured->post_author );
        ?>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:2px;">
 
                <!-- Большая карточка -->
                <div style="background:var(--color-second); padding:32px;">
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:#D91515; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">
                        ВЫПУСК #<?php echo esc_html( str_pad( $f_id, 3, '0', STR_PAD_LEFT ) ); ?>
                    </p>
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; opacity:0.4; margin-bottom:12px;">
                        <?php echo esc_html( $f_medium ); ?> · <?php echo esc_html( $f_form ); ?>
                    </p>
                    <h3 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.4rem,2.5vw,2rem); letter-spacing:0.06em; text-transform:uppercase; margin-bottom:12px;">
                        <?php echo esc_html( get_the_title( $f_id ) ); ?>
                    </h3>
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; opacity:0.5; margin-bottom:20px;">
                        <?php echo esc_html( $f_author ); ?><?php if ( $f_reading ) : ?> · <?php echo esc_html( $f_reading ); ?> мин<?php endif; ?>
                    </p>
                    <?php if ( $f_thumb ) : ?>
                        <div style="aspect-ratio:16/9; overflow:hidden; margin-bottom:20px;">
                            <img src="<?php echo esc_url( $f_thumb ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( get_the_title( $f_id ) ); ?>">
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( get_permalink( $f_id ) ); ?>" style="display:inline-block; padding:10px 24px; background:#D91515; color:#fff; font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; text-decoration:none; border:none;">
                        Читать сейчас →
                    </a>
                </div>
 
                <!-- Правая сетка 2×2 -->
                <div style="display:grid; grid-template-columns:1fr 1fr; grid-template-rows:1fr 1fr; gap:2px;">
                    <?php
                    $ed_counter = 2;
                    foreach ( $rest as $ed_post ) :
                        $ep_id     = $ed_post->ID;
                        $ep_terms  = get_the_terms( $ep_id, 'section' );
                        $ep_medium = ( $ep_terms && ! is_wp_error( $ep_terms ) ) ? $ep_terms[0]->name : '—';
                        $ep_reading = function_exists( 'get_field' ) ? get_field( 'reading_time', $ep_id ) : '';
                    ?>
                        <a href="<?php echo esc_url( get_permalink( $ep_id ) ); ?>" style="background:var(--color-second); padding:24px; text-decoration:none; color:inherit; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:#D91515; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;">
                                    #<?php echo esc_html( str_pad( $ep_id, 3, '0', STR_PAD_LEFT ) ); ?> · <?php echo esc_html( $ep_medium ); ?>
                                </p>
                                <h4 style="font-family:'Trajan Pro 3',serif; font-size:1rem; letter-spacing:0.04em; text-transform:uppercase; margin-bottom:8px;">
                                    <?php echo esc_html( get_the_title( $ep_id ) ); ?>
                                </h4>
                                <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.4;">
                                    <?php echo esc_html( $ep_medium ); ?><?php if ( $ep_reading ) : ?> · <?php echo esc_html( $ep_reading ); ?> мин<?php endif; ?>
                                </p>
                            </div>
                            <span style="font-family:'IBM Plex Mono',monospace; font-size:14px; color:#D91515; align-self:flex-end;">→</span>
                        </a>
                    <?php
                        $ed_counter++;
                    endforeach;
 
                    // Fill empty slots
                    while ( $ed_counter <= 5 ) :
                    ?>
                        <div style="background:var(--color-second); padding:24px; opacity:0.15;">
                            <p style="font-family:'IBM Plex Mono',monospace; font-size:10px;">—</p>
                        </div>
                    <?php
                        $ed_counter++;
                    endwhile;
                    ?>
                </div>
 
            </div>
        <?php else : ?>
            <div style="padding:60px; text-align:center; opacity:0.4; font-family:'IBM Plex Mono',monospace; font-size:11px;">
                РЕДАКЦИЯ ГОТОВИТ ПОДБОРКУ · СЛЕДИТЕ ЗА ОБНОВЛЕНИЯМИ
            </div>
        <?php endif; wp_reset_postdata(); ?>
 
    </div>
</section>
 
 
<!-- ============================================================
     6. НОВОСТИ
     ============================================================ -->
<?php
$section_colors = [
    'cinema' => '#4DB7FF',
    'lit'    => '#4A3428',
    'music'  => '#FF4FA3',
    'art'    => '#C6A25A',
];
 
$news = new WP_Query( [
    'post_type'              => 'news',
    'posts_per_page'         => 6,
    'post_status'            => 'publish',
    'update_post_meta_cache' => false,
    'update_post_term_cache' => false,
] );
?>
<section style="background:var(--color-second); padding:80px 0;">
    <div class="container">
 
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:32px;">
            <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase;">
                Новости
            </h2>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" style="font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.08em; text-transform:uppercase; color:inherit; text-decoration:none; opacity:0.6;">
                Все новости →
            </a>
        </div>
 
        <?php if ( $news->have_posts() ) : ?>
            <div>
                <?php while ( $news->have_posts() ) : $news->the_post();
                    $n_id    = get_the_ID();
                    $n_terms = get_the_terms( $n_id, 'section' );
                    $n_slug  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->slug : '';
                    $n_name  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->name : '—';
                    $n_color = isset( $section_colors[ $n_slug ] ) ? $section_colors[ $n_slug ] : '#D91515';
                ?>
                    <div style="display:grid; grid-template-columns:80px 1fr 120px 24px; gap:16px; align-items:center; padding:14px 0; border-bottom:1px solid rgba(0,0,0,0.08);">
                        <span style="background:<?php echo esc_attr( $n_color ); ?>; color:#fff; font-family:'IBM Plex Mono',monospace; font-size:10px; padding:2px 8px; text-transform:uppercase; text-align:center; white-space:nowrap;">
                            <?php echo esc_html( $n_name ); ?>
                        </span>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="color:inherit; text-decoration:none; font-family:'EB Garamond',Georgia,serif; font-size:0.95rem;">
                            <?php echo esc_html( get_the_title() ); ?>
                        </a>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; opacity:0.4;">
                            <?php echo esc_html( get_the_date( 'd.m.Y' ) ); ?>
                        </span>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" style="font-size:14px; color:<?php echo esc_attr( $n_color ); ?>; text-decoration:none;">→</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else : ?>
            <div>
                <?php for ( $i = 0; $i < 6; $i++ ) : ?>
                    <div style="display:grid; grid-template-columns:80px 1fr 120px 24px; gap:16px; align-items:center; padding:14px 0; border-bottom:1px solid rgba(0,0,0,0.08); opacity:0.15;">
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:10px;">——</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:11px;">————————————————</span>
                        <span style="font-family:'IBM Plex Mono',monospace; font-size:11px;">——.——.————</span>
                        <span>—</span>
                    </div>
                <?php endfor; ?>
            </div>
        <?php endif; wp_reset_postdata(); ?>
 
    </div>
</section>
 
 
<!-- ============================================================
     7. О ПРОЕКТЕ
     ============================================================ -->
<section style="background:var(--color-second); padding:80px 0;">
    <div class="container">
        <div style="text-align:center; max-width:600px; margin:0 auto;">
 
            <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:24px;">
                О проекте
            </h2>
 
            <p style="font-family:'EB Garamond',Georgia,serif; font-size:1.05rem; line-height:1.8; opacity:0.85; margin-bottom:32px;">
                Palime Archive — независимый медиаархив современной культуры. Мы систематизируем кино, литературу, музыку и визуальное искусство с глубиной и вниманием, которых они заслуживают. Каждый материал — это исследование: не рецензия, а разбор. Не мнение, а аргумент. Мы верим, что культура — не развлечение, а способ понимания мира.
            </p>
 
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" style="display:inline-block; padding:10px 24px; font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#0A0A0A; border:1px solid #0A0A0A; text-decoration:none; transition:all 0.15s ease;">
                Подробнее
            </a>
 
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
$section_colors_shop = [
    'cinema' => '#4DB7FF',
    'lit'    => '#4A3428',
    'music'  => '#FF4FA3',
    'art'    => '#C6A25A',
];
?>
<section style="background:#0A0A0A; color:#fff; padding:80px 0;">
    <div class="container">
 
        <!-- Шапка -->
        <div style="text-align:center; margin-bottom:40px;">
            <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase; color:#fff; margin-bottom:12px;">
                <?php
                if ( $shop_products ) {
                    echo esc_html( get_post_meta( $shop_products[0]->ID, 'collection_title', true ) ?: 'ТЕКУЩАЯ КОЛЛЕКЦИЯ' );
                } else {
                    echo 'ТЕКУЩАЯ КОЛЛЕКЦИЯ';
                }
                ?>
            </h2>
            <p style="font-family:'EB Garamond',Georgia,serif; font-size:1rem; color:rgba(255,255,255,0.7); margin-bottom:8px;">
                Лимитированные артефакты. Одна тема — одна идея.
            </p>
            <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:rgba(255,255,255,0.35); letter-spacing:0.08em;">
                КАТАЛОГ: АКТИВЕН · ТИРАЖ: ОГРАНИЧЕН
            </p>
        </div>
 
        <!-- О выпуске -->
        <div style="background:rgba(255,255,255,0.06); border:1px solid rgba(255,255,255,0.1); padding:24px; margin:32px 0;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <span style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#fff; text-transform:uppercase; letter-spacing:0.1em;">О выпуске</span>
                <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:#D91515; text-transform:uppercase; letter-spacing:0.08em;">Активен</span>
            </div>
            <div class="pa-shop-about-grid" style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:24px;">
                <div>
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.4; text-transform:uppercase; margin-bottom:6px;">Тема</p>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.9rem; color:rgba(255,255,255,0.8);">
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
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.4; text-transform:uppercase; margin-bottom:6px;">Формат</p>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.9rem; color:rgba(255,255,255,0.8);">Четыре товара. Четыре раздела.</p>
                </div>
                <div>
                    <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.4; text-transform:uppercase; margin-bottom:6px;">Смысл</p>
                    <p style="font-family:'EB Garamond',Georgia,serif; font-size:0.9rem; color:rgba(255,255,255,0.8);">Не что купить. А что помнить.</p>
                </div>
            </div>
        </div>
 
        <!-- Товары -->
        <?php if ( $shop_products ) : ?>
            <div class="pa-shop-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:2px; margin-top:32px;">
                <?php foreach ( $shop_products as $product_post ) :
                    $p_id          = $product_post->ID;
                    $section_slug  = get_post_meta( $p_id, 'section', true );
                    $accent        = isset( $section_colors_shop[ $section_slug ] ) ? $section_colors_shop[ $section_slug ] : '#D91515';
                    $price         = get_post_meta( $p_id, '_price', true );
                    $stock         = get_post_meta( $p_id, '_stock', true );
                    $stock_total   = get_post_meta( $p_id, '_stock_total', true );
                    $img           = get_the_post_thumbnail_url( $p_id, 'large' );
                    $p_section_t   = get_the_terms( $p_id, 'section' );
                    $p_section_nm  = ( $p_section_t && ! is_wp_error( $p_section_t ) ) ? $p_section_t[0]->name : ( $section_slug ?: '—' );
                ?>
                    <div style="background:#111; position:relative;">
                        <div style="position:absolute; top:12px; left:12px; background:<?php echo esc_attr( $accent ); ?>; color:#fff; font-family:'IBM Plex Mono',monospace; font-size:10px; padding:2px 8px; text-transform:uppercase; z-index:1;">
                            <?php echo esc_html( $p_section_nm ); ?>
                        </div>
                        <div style="aspect-ratio:3/4; overflow:hidden; background:#1a1a1a;">
                            <?php if ( $img ) : ?>
                                <img src="<?php echo esc_url( $img ); ?>" style="width:100%; height:100%; object-fit:cover;" alt="<?php echo esc_attr( get_the_title( $p_id ) ); ?>">
                            <?php else : ?>
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-family:'IBM Plex Mono',monospace; font-size:11px; opacity:0.2; color:#fff;">ФОТО</div>
                            <?php endif; ?>
                        </div>
                        <div style="padding:16px; border-top:2px solid <?php echo esc_attr( $accent ); ?>;">
                            <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; opacity:0.5; margin-bottom:6px; color:#fff;">
                                <?php echo esc_html( $p_id ); ?> · <?php echo esc_html( $stock_total ?: '—' ); ?> / <?php echo esc_html( $stock ?: '—' ); ?>
                            </p>
                            <h4 style="font-family:'Trajan Pro 3',serif; font-size:1rem; color:#fff; margin-bottom:8px;">
                                <?php echo esc_html( get_the_title( $p_id ) ); ?>
                            </h4>
                            <p style="font-size:0.8rem; opacity:0.6; margin-bottom:12px; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <?php echo esc_html( wp_trim_words( $product_post->post_excerpt ?: $product_post->post_content, 8, '...' ) ); ?>
                            </p>
                            <div style="display:flex; justify-content:space-between; align-items:center;">
                                <span style="font-family:'IBM Plex Mono',monospace; font-size:0.9rem; color:<?php echo esc_attr( $accent ); ?>;">
                                    <?php echo $price ? esc_html( $price ) . ' ₽' : '—'; ?>
                                </span>
                                <span style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:#4caf50;">ДОСТУПНО</span>
                            </div>
                            <a href="<?php echo esc_url( get_permalink( $p_id ) ); ?>" style="display:block; margin-top:12px; font-family:'IBM Plex Mono',monospace; font-size:11px; color:<?php echo esc_attr( $accent ); ?>; text-transform:uppercase; text-decoration:none;">
                                Купить →
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:2px; margin-top:32px;">
                <div style="grid-column:1/-1; text-align:center; padding:80px; opacity:0.4; font-family:'IBM Plex Mono',monospace; color:#fff;">
                    КОЛЛЕКЦИЯ ГОТОВИТСЯ К ЗАПУСКУ
                </div>
            </div>
        <?php endif; ?>
 
        <!-- Кнопка магазина -->
        <div style="text-align:center; margin-top:40px;">
            <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" style="display:inline-block; padding:12px 32px; font-family:'IBM Plex Mono',monospace; font-size:11px; letter-spacing:0.1em; text-transform:uppercase; color:#fff; border:1px solid rgba(255,255,255,0.4); text-decoration:none; transition:all 0.15s ease;">
                В магазин →
            </a>
        </div>
 
    </div>
</section>
 
 
<!-- ============================================================
     9. ПРИСОЕДИНИТЬСЯ К АРХИВУ
     ============================================================ -->
<section style="background:#0A0A0A; color:#fff; padding:80px 0;">
    <div class="container">
 
        <div style="text-align:center; margin-bottom:16px;">
            <h2 style="font-family:'Trajan Pro 3',serif; font-size:clamp(1.6rem,3vw,2.4rem); letter-spacing:0.1em; text-transform:uppercase; color:#fff; margin-bottom:12px;">
                Присоединиться к архиву
            </h2>
            <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:rgba(255,255,255,0.5); letter-spacing:0.08em;">
                Выберите уровень. Все письма написаны вручную.
            </p>
        </div>
 
        <!-- 4 уровня -->
        <div class="pa-join-grid" style="display:grid; grid-template-columns:repeat(4,1fr); gap:16px; margin:40px 0;">
            <div style="border:1px solid rgba(255,255,255,0.15); padding:24px; text-align:center;">
                <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#D91515; margin-bottom:8px;">ЧИТАТЕЛЬ</p>
                <p style="font-size:0.8rem; opacity:0.6; color:#fff;">Доступ к материалам и живому индексу</p>
            </div>
            <div style="border:1px solid rgba(255,255,255,0.15); padding:24px; text-align:center;">
                <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#D91515; margin-bottom:8px;">АРХИВИСТ</p>
                <p style="font-size:0.8rem; opacity:0.6; color:#fff;">Голосование, комментарии, сохранение статей</p>
            </div>
            <div style="border:1px solid rgba(255,255,255,0.15); padding:24px; text-align:center;">
                <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#D91515; margin-bottom:8px;">КУРАТОР</p>
                <p style="font-size:0.8rem; opacity:0.6; color:#fff;">Ранний доступ к дропам и рейтингам</p>
            </div>
            <div style="border:1px solid rgba(255,255,255,0.15); padding:24px; text-align:center;">
                <p style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#D91515; margin-bottom:8px;">ХРАНИТЕЛЬ</p>
                <p style="font-size:0.8rem; opacity:0.6; color:#fff;">Полный доступ, влияние на контент</p>
            </div>
        </div>
 
        <!-- Форма подписки -->
        <div style="max-width:500px; margin:0 auto; text-align:center;">
            <form class="subscribe-form" style="display:flex; gap:8px; margin-top:32px;">
                <input type="email" placeholder="Email для подписки" style="flex:1; padding:12px 16px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.2); color:#fff; font-family:'IBM Plex Mono',monospace; font-size:12px;" required>
                <button type="submit" style="background:#D91515; color:#fff; border:none; padding:12px 24px; font-family:'IBM Plex Mono',monospace; font-size:12px; text-transform:uppercase; cursor:pointer;">Подписаться</button>
            </form>
 
            <!-- Соцсети -->
            <?php
            $tg = get_option( 'palime_telegram_url' );
            $vk = get_option( 'palime_vk_url' );
            if ( $tg || $vk ) :
            ?>
                <div style="display:flex; gap:16px; justify-content:center; margin-top:24px;">
                    <?php if ( $tg ) : ?>
                        <a href="<?php echo esc_url( $tg ); ?>" style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#fff; opacity:0.5; text-decoration:none;">TELEGRAM →</a>
                    <?php endif; ?>
                    <?php if ( $vk ) : ?>
                        <a href="<?php echo esc_url( $vk ); ?>" style="font-family:'IBM Plex Mono',monospace; font-size:11px; color:#fff; opacity:0.5; text-decoration:none;">VK →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
 
            <p style="font-family:'IBM Plex Mono',monospace; font-size:10px; color:rgba(255,255,255,0.3); margin-top:16px; letter-spacing:0.04em;">
                Никакого спама. Только контекст. Можно отписаться в любой момент.
            </p>
        </div>
 
    </div>
</section>
 
 
</main>

Expand 16 hidden lines
});
</script>
 
<!-- CSS: адаптив героя и сетки разделов -->
<!-- CSS: адаптив всех секций -->
<style>
@media (max-width: 1024px) {
    /* Герой: одна колонка */

Expand 12 hidden lines
        grid-template-columns: 1fr !important;
        gap: 40px !important;
    }
    /* Магазин: товары 2×2 */
    .pa-shop-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
    /* Присоединиться: 2×2 */
    .pa-join-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (max-width: 768px) {
    #main > section:first-child > div:first-child {
        padding: 40px 16px !important;
    }
    #main > section:nth-child(2),
    #main > section:nth-child(3),
    #main > section:nth-child(4) {
    #main > section {
        padding: 40px 0 !important;
    }
    /* Выбор редакции: одна колонка */
    #main > section:nth-child(5) .container > div:nth-child(2) {
        grid-template-columns: 1fr !important;
    }
    #main > section:nth-child(5) .container > div:nth-child(2) > div:last-child {
        grid-template-columns: 1fr 1fr !important;
    }
    /* Новости: убираем дату и стрелку */
    #main > section:nth-child(6) .container > div > div {
        grid-template-columns: 60px 1fr !important;
    }
    /* О выпуске: одна колонка */
    .pa-shop-about-grid {
        grid-template-columns: 1fr !important;
    }
    /* Магазин: одна колонка */
    .pa-shop-grid {
        grid-template-columns: 1fr !important;
    }
    /* Присоединиться: одна колонка */
    .pa-join-grid {
        grid-template-columns: 1fr !important;
    }
    /* Форма подписки: колонка */
    .subscribe-form {
        flex-direction: column !important;
    }
}
@media (max-width: 480px) {
    /* Разделы: одна колонка */
    #main > section:nth-child(2) .container > div:last-child {
        grid-template-columns: 1fr !important;
    }
    /* Выбор редакции: мини-карточки в одну колонку */
    #main > section:nth-child(5) .container > div:nth-child(2) > div:last-child {
        grid-template-columns: 1fr !important;
    }
}
</style>

<?php get_footer(); ?>
