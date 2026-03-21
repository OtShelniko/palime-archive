<?php
/**
 * Palime Archive — template-parts/sections/section-page.php
 * Общий шаблон страниц разделов: Кино / Литература / Музыка / ИЗО
 *
 * Принимает $args:
 *   section_slug    string  'cinema' | 'lit' | 'music' | 'art'
 *   section_name    string  'Кино' | 'Литература' | 'Музыка' | 'ИЗО'
 *   section_slogan  string  слоган раздела
 *   status_line     string  строка статусов в заставке
 *   bg_color        string  HEX фона
 *   accent_color    string  HEX акцента
 *   section_about   string  текст манифеста раздела
 *   rating_authors  string  название категории рейтинга авторов
 *   rating_works    string  название категории рейтинга произведений
 *   monthly_cats    array   категории ежемесячного итога
 *
 * @package Palime_Archive
 */

// Извлекаем конфиг из $args
$section_slug   = $args['section_slug']   ?? '';
$section_name   = $args['section_name']   ?? '';
$section_slogan = $args['section_slogan'] ?? '';
$status_line    = $args['status_line']    ?? '';
$bg_color       = $args['bg_color']       ?? '#0A0A0A';
$accent_color   = $args['accent_color']   ?? '#D91515';
$section_about  = $args['section_about']  ?? '';
$rating_authors = $args['rating_authors'] ?? 'Лучшие авторы';
$rating_works   = $args['rating_works']   ?? 'Лучшие произведения';
$monthly_cats   = $args['monthly_cats']   ?? [];

// Tax query для фильтрации по разделу
$tax_query = [];
if ( $section_slug ) {
    $tax_query = [ [
        'taxonomy' => 'section',
        'field'    => 'slug',
        'terms'    => $section_slug,
    ] ];
}

?>

<!-- ====================================================
     1. ЗАСТАВКА
     ==================================================== -->
<section class="section-hero" style="background:<?php echo esc_attr( $bg_color ); ?>; color:#fff; position:relative; overflow:hidden;">

    <!-- Декоративные линии -->
    <div aria-hidden="true" style="position:absolute; inset:0; background:repeating-linear-gradient(0deg, rgba(255,255,255,.015) 0px, rgba(255,255,255,.015) 1px, transparent 1px, transparent 80px); pointer-events:none;"></div>

    <div class="container" style="position:relative; z-index:1; padding-top:var(--spacing-2xl); padding-bottom:var(--spacing-2xl);">

        <!-- Статус-строка -->
        <?php if ( $status_line ) : ?>
            <p class="text-mono text-xs mb-lg" style="opacity:.4; letter-spacing:.18em; text-transform:uppercase;">
                <?php echo esc_html( $status_line ); ?>
            </p>
        <?php endif; ?>

        <!-- Заголовок раздела -->
        <h1 style="font-family:var(--font-display); font-size:clamp(3rem,8vw,8rem); line-height:1; color:<?php echo esc_attr( $accent_color ); ?>; margin-bottom:var(--spacing-lg);">
            <?php echo esc_html( strtoupper( $section_name ) ); ?>
        </h1>

        <!-- Слоган -->
        <p style="font-family:var(--font-serif); font-size:clamp(1rem,2vw,1.4rem); opacity:.75; max-width:600px; line-height:1.5; margin-bottom:var(--spacing-xl);">
            <?php echo esc_html( $section_slogan ); ?>
        </p>

        <!-- Кнопки -->
        <div class="flex flex--gap flex--wrap">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) . '?section=' . $section_slug ); ?>"
               class="btn btn--primary"
               style="background:<?php echo esc_attr( $accent_color ); ?>; border-color:<?php echo esc_attr( $accent_color ); ?>;">
                Открыть каталог
            </a>
            <a href="#ratings"
               class="btn btn--outline"
               style="color:#fff; border-color:rgba(255,255,255,.4);">
                Смотреть рейтинги
            </a>
        </div>

    </div>
</section>
<!-- /ЗАСТАВКА -->


<!-- ====================================================
     2. СВЕЖИЕ МАТЕРИАЛЫ
     ==================================================== -->
<section class="section">
    <div class="container">

        <div class="flex flex--between mb-xl">
            <div>
                <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Свежие материалы —</span>
                <h2 class="mt-sm" style="font-family:var(--font-display); font-size:1.8rem;">Последние статьи</h2>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>"
               class="btn btn--outline btn--sm hide-mobile">
                Весь архив →
            </a>
        </div>

        <?php
        // Запрашиваем статьи по типам
        $types = [
            'author'    => 'Про автора',
            'work'      => 'Про произведение',
            'selection' => 'Подборка',
        ];
        $fresh_args = [
            'post_type'      => 'article',
            'posts_per_page' => 6,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => $tax_query,
        ];
        $fresh_query = new WP_Query( $fresh_args );
        ?>

        <?php if ( $fresh_query->have_posts() ) : ?>
            <div class="grid grid--cards">
                <?php while ( $fresh_query->have_posts() ) : $fresh_query->the_post(); ?>
                    <?php get_template_part( 'template-parts/cards/card', 'article' ); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <p class="text-muted text-mono text-xs" style="letter-spacing:.08em;">— Материалы появятся здесь —</p>
        <?php endif; ?>

        <div class="mt-xl show-mobile">
            <a href="<?php echo esc_url( get_post_type_archive_link( 'article' ) ); ?>" class="btn btn--outline">
                Весь архив →
            </a>
        </div>

    </div>
</section>
<!-- /СВЕЖИЕ МАТЕРИАЛЫ -->


<!-- ====================================================
     3. РЕЙТИНГИ
     ==================================================== -->
<section id="ratings" class="section" style="background:var(--color-second);">
    <div class="container">

        <div class="mb-xl">
            <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Рейтинги —</span>
            <h2 class="mt-sm" style="font-family:var(--font-display); font-size:1.8rem;">
                <?php echo esc_html( $section_name ); ?> · Топ
            </h2>
            <p class="text-mono text-xs text-muted mt-xs">Некоторые записи остаются спорными</p>
        </div>

        <div class="grid grid--2">

            <!-- Топ авторов -->
            <div>
                <h3 class="text-mono text-xs text-upper mb-lg" style="letter-spacing:.12em; color:var(--accent);">
                    <?php echo esc_html( $rating_authors ); ?>
                </h3>
                <?php
                $authors_ranking = new WP_Query( [
                    'post_type'      => 'ranking',
                    'posts_per_page' => 1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'tax_query'      => $tax_query,
                    'meta_query'     => [ [
                        'key'     => 'ranking_category',
                        'value'   => 'authors',
                        'compare' => '=',
                    ] ],
                ] );

                if ( $authors_ranking->have_posts() ) :
                    while ( $authors_ranking->have_posts() ) : $authors_ranking->the_post();
                        $items = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
                        if ( $items ) : ?>
                            <ol style="list-style:none; display:flex; flex-direction:column; gap:var(--spacing-sm);">
                                <?php foreach ( $items as $i => $item ) : ?>
                                    <li class="flex flex--gap" style="padding:var(--spacing-sm) 0; border-bottom:1px solid rgba(0,0,0,.06);">
                                        <span class="text-mono" style="color:var(--accent); min-width:24px; font-size:.8rem;"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
                                        <span style="font-family:var(--font-serif);">
                                            <?php echo esc_html( is_array( $item ) ? ( $item['name'] ?? $item[0] ?? '' ) : $item ); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else : ?>
                            <p class="text-muted text-mono text-xs">— Рейтинг формируется —</p>
                        <?php endif;
                    endwhile; wp_reset_postdata();
                else : ?>
                    <p class="text-muted text-mono text-xs">— Рейтинг формируется —</p>
                <?php endif; ?>
            </div>

            <!-- Топ произведений -->
            <div>
                <h3 class="text-mono text-xs text-upper mb-lg" style="letter-spacing:.12em; color:var(--accent);">
                    <?php echo esc_html( $rating_works ); ?>
                </h3>
                <?php
                $works_ranking = new WP_Query( [
                    'post_type'      => 'ranking',
                    'posts_per_page' => 1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                    'tax_query'      => $tax_query,
                    'meta_query'     => [ [
                        'key'     => 'ranking_category',
                        'value'   => 'works',
                        'compare' => '=',
                    ] ],
                ] );

                if ( $works_ranking->have_posts() ) :
                    while ( $works_ranking->have_posts() ) : $works_ranking->the_post();
                        $items = function_exists( 'get_field' ) ? get_field( 'ranking_items' ) : [];
                        if ( $items ) : ?>
                            <ol style="list-style:none; display:flex; flex-direction:column; gap:var(--spacing-sm);">
                                <?php foreach ( $items as $i => $item ) : ?>
                                    <li class="flex flex--gap" style="padding:var(--spacing-sm) 0; border-bottom:1px solid rgba(0,0,0,.06);">
                                        <span class="text-mono" style="color:var(--accent); min-width:24px; font-size:.8rem;"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
                                        <span style="font-family:var(--font-serif);">
                                            <?php echo esc_html( is_array( $item ) ? ( $item['name'] ?? $item[0] ?? '' ) : $item ); ?>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else : ?>
                            <p class="text-muted text-mono text-xs">— Рейтинг формируется —</p>
                        <?php endif;
                    endwhile; wp_reset_postdata();
                else : ?>
                    <p class="text-muted text-mono text-xs">— Рейтинг формируется —</p>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
<!-- /РЕЙТИНГИ -->


<!-- ====================================================
     4. НОВОСТИ
     ==================================================== -->
<section class="section">
    <div class="container">

        <div class="flex flex--between mb-xl">
            <div>
                <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Новости —</span>
                <h2 class="mt-sm" style="font-family:var(--font-display); font-size:1.8rem;">
                    <?php echo esc_html( $section_name ); ?> · Лента
                </h2>
            </div>
            <a href="<?php echo esc_url( get_post_type_archive_link( 'news' ) . '?section=' . $section_slug ); ?>"
               class="btn btn--outline btn--sm hide-mobile">
                Все новости →
            </a>
        </div>

        <?php
        $news_query = new WP_Query( [
            'post_type'      => 'news',
            'posts_per_page' => 5,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'tax_query'      => $tax_query,
        ] );
        ?>

        <?php if ( $news_query->have_posts() ) : ?>
            <ul style="list-style:none;">
                <?php while ( $news_query->have_posts() ) : $news_query->the_post(); ?>
                    <?php get_template_part( 'template-parts/cards/card', 'news' ); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </ul>
        <?php else : ?>
            <p class="text-muted text-mono text-xs" style="letter-spacing:.08em;">— Новости появятся здесь —</p>
        <?php endif; ?>

    </div>
</section>
<!-- /НОВОСТИ -->


<!-- ====================================================
     5. ЦИТАТА ДНЯ
     ==================================================== -->
<?php
$today = current_time( 'Y-m-d' );
$quote_query = new WP_Query( [
    'post_type'      => 'quote_of_day',
    'posts_per_page' => 1,
    'tax_query'      => $tax_query,
    'date_query'     => [ [
        'year'  => date( 'Y', strtotime( $today ) ),
        'month' => date( 'm', strtotime( $today ) ),
        'day'   => date( 'd', strtotime( $today ) ),
    ] ],
] );

// Fallback: последняя цитата
if ( ! $quote_query->have_posts() ) {
    $quote_query = new WP_Query( [
        'post_type'      => 'quote_of_day',
        'posts_per_page' => 1,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'tax_query'      => $tax_query,
    ] );
}
?>

<?php if ( $quote_query->have_posts() ) : $quote_query->the_post();
    $q_id         = get_the_ID();
    $q_text       = function_exists( 'get_field' ) ? get_field( 'quote_text', $q_id )   : get_the_title();
    $q_author     = function_exists( 'get_field' ) ? get_field( 'quote_author', $q_id ) : '';
    $q_work       = function_exists( 'get_field' ) ? get_field( 'quote_work', $q_id )   : '';
    $q_link       = function_exists( 'get_field' ) ? get_field( 'quote_link', $q_id )   : '';
    if ( ! $q_text ) $q_text = get_the_title();
?>

<section class="section" style="background:<?php echo esc_attr( $bg_color ); ?>; color:#fff;">
    <div class="container text-center">

        <p class="text-mono text-xs mb-lg" style="opacity:.4; letter-spacing:.2em; text-transform:uppercase;">
            — Цитата дня —
        </p>

        <blockquote style="font-family:var(--font-serif); font-size:clamp(1.2rem,2.5vw,2rem); line-height:1.5; max-width:760px; margin:0 auto; font-style:italic;">
            &laquo;<?php echo esc_html( $q_text ); ?>&raquo;
        </blockquote>

        <?php if ( $q_author || $q_work ) : ?>
            <p class="mt-lg text-mono text-xs" style="opacity:.55; letter-spacing:.08em;">
                <?php if ( $q_author ) echo esc_html( $q_author ); ?>
                <?php if ( $q_author && $q_work ) echo ' · '; ?>
                <?php if ( $q_work ) echo esc_html( $q_work ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $q_link ) : ?>
            <div class="mt-lg">
                <a href="<?php echo esc_url( $q_link ); ?>" class="btn btn--outline" style="color:#fff; border-color:rgba(255,255,255,.3);">
                    Открыть дело →
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php wp_reset_postdata(); endif; ?>
<!-- /ЦИТАТА ДНЯ -->


<!-- ====================================================
     6. ЛУЧШЕЕ ЗА МЕСЯЦ
     ==================================================== -->
<?php
$monthly_query = new WP_Query( [
    'post_type'      => 'monthly_best',
    'posts_per_page' => 1,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => $tax_query,
] );
?>

<section class="section section--accent">
    <div class="container">

        <div class="flex flex--between mb-xl">
            <div>
                <span class="text-mono text-xs text-muted text-upper" style="letter-spacing:.12em;">— Итог —</span>
                <h2 class="mt-sm" style="font-family:var(--font-display); font-size:1.8rem;">
                    Лучшее за месяц
                </h2>
            </div>
        </div>

        <?php if ( $monthly_query->have_posts() ) : $monthly_query->the_post(); ?>

            <div class="grid grid--3">
                <?php foreach ( $monthly_cats as $cat_key => $cat_label ) :
                    $cat_items = function_exists( 'get_field' ) ? get_field( 'monthly_' . $cat_key ) : [];
                    if ( ! $cat_items ) continue;
                ?>
                    <div class="card" style="padding:var(--spacing-lg);">
                        <h3 class="text-mono text-xs text-upper mb-lg" style="letter-spacing:.1em; color:var(--accent);">
                            <?php echo esc_html( $cat_label ); ?>
                        </h3>
                        <ol style="list-style:none; display:flex; flex-direction:column; gap:var(--spacing-sm); counter-reset:monthly-counter;">
                            <?php foreach ( $cat_items as $i => $entry ) : ?>
                                <li style="counter-increment:monthly-counter; display:flex; gap:var(--spacing-sm); padding-bottom:var(--spacing-xs); border-bottom:1px solid rgba(0,0,0,.05);">
                                    <span class="text-mono text-xs" style="color:var(--accent); min-width:20px;"><?php echo esc_html( str_pad( $i + 1, 2, '0', STR_PAD_LEFT ) ); ?></span>
                                    <span style="font-family:var(--font-serif); font-size:.95rem; line-height:1.4;">
                                        <?php echo esc_html( is_array( $entry ) ? ( $entry['title'] ?? $entry[0] ?? '' ) : $entry ); ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php wp_reset_postdata(); else : ?>
            <p class="text-muted text-mono text-xs" style="letter-spacing:.08em;">— Итог месяца появится здесь —</p>
        <?php endif; ?>

    </div>
</section>
<!-- /ЛУЧШЕЕ ЗА МЕСЯЦ -->


<!-- ====================================================
     7. О ПРОЕКТЕ
     ==================================================== -->
<?php if ( $section_about ) : ?>
<section class="section">
    <div class="container">
        <div class="grid grid--sidebar">
            <div>
                <span class="text-mono text-xs text-muted text-upper mb-lg" style="letter-spacing:.12em; display:block;">— О разделе —</span>
                <div style="font-family:var(--font-serif); font-size:1.15rem; line-height:1.8; max-width:640px;">
                    <?php echo wp_kses_post( $section_about ); ?>
                </div>
            </div>
            <div style="align-self:center; text-align:center;">
                <p class="text-display" style="font-family:var(--font-display); font-size:clamp(2rem,4vw,4rem); line-height:1.1; color:var(--accent);">
                    <?php echo esc_html( strtoupper( $section_name ) ); ?>
                </p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- /О ПРОЕКТЕ -->


<!-- ====================================================
     8. ПРЕВЬЮ МАГАЗИНА
     ==================================================== -->
<?php
if ( function_exists( 'wc_get_products' ) ) :
    $drop_products = wc_get_products( [
        'limit'   => 1,
        'status'  => 'publish',
        'orderby' => 'date',
        'order'   => 'DESC',
        'meta_key'   => '_palime_section',
        'meta_value' => $section_slug,
    ] );
?>
<section class="section" style="background:var(--color-ui); color:#fff;">
    <div class="container">
        <div class="flex flex--between flex--wrap" style="gap:var(--spacing-xl);">
            <div>
                <p class="text-mono text-xs mb-md" style="opacity:.6; letter-spacing:.2em; text-transform:uppercase;">
                    — Текущий дроп —
                </p>
                <?php if ( ! empty( $drop_products ) ) :
                    $drop = $drop_products[0]; ?>
                    <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem,3vw,2.5rem); margin-bottom:var(--spacing-md);">
                        <?php echo esc_html( $drop->get_name() ); ?>
                    </h2>
                    <p style="opacity:.75; font-family:var(--font-serif); margin-bottom:var(--spacing-xl);">
                        <?php echo esc_html( wp_trim_words( $drop->get_short_description(), 20, '…' ) ); ?>
                    </p>
                <?php else : ?>
                    <h2 style="font-family:var(--font-display); font-size:clamp(1.5rem,3vw,2.5rem); margin-bottom:var(--spacing-xl);">
                        Магазин Palime
                    </h2>
                <?php endif; ?>
                <a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>"
                   class="btn btn--outline"
                   style="color:#fff; border-color:#fff;">
                    Перейти в магазин →
                </a>
            </div>
            <?php if ( ! empty( $drop_products ) ) :
                $img = $drop_products[0]->get_image( 'card', [ 'style' => 'max-height:200px;width:auto;border-radius:var(--radius-md);' ] );
                if ( $img ) : ?>
                    <div style="align-self:center;">
                        <?php echo $img; ?>
                    </div>
                <?php endif;
            endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>
<!-- /ПРЕВЬЮ МАГАЗИНА -->
