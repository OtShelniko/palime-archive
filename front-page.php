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

<?php get_footer(); ?>
