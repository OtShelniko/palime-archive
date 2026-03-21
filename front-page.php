<?php
/**
 * Palime Archive — front-page.php
 * Главная страница: 8 секций по ТЗ
 *
 * @package Palime_Archive
 */

get_header();

// ─── Данные разделов ───
$sections = [
    'cinema' => [ 'label' => 'Кино',        'slug' => 'cinema', 'url' => '/cinema/' ],
    'lit'    => [ 'label' => 'Литература',   'slug' => 'lit',    'url' => '/literature/' ],
    'music'  => [ 'label' => 'Музыка',       'slug' => 'music',  'url' => '/music/' ],
    'art'    => [ 'label' => 'ИЗО',          'slug' => 'art',    'url' => '/art/' ],
];

$section_labels = [
    'cinema' => 'Кино',
    'lit'    => 'Литература',
    'music'  => 'Музыка',
    'art'    => 'ИЗО',
];
?>

<main id="main" role="main">

<!-- ============================================================
     1. ЗАСТАВКА
     ============================================================ -->
<section class="home-hero">
    <div class="home-hero__main">
        <h1 class="text-display" style="font-size:clamp(2rem,5vw,4rem); line-height:1.1; letter-spacing:0.08em; text-transform:uppercase; margin-bottom:var(--spacing-lg);">
            Современное<br>искусство<br>и&nbsp;культура
        </h1>
        <p class="text-display" style="font-size:clamp(1.2rem,2.5vw,2rem); letter-spacing:0.2em; text-transform:uppercase; margin-bottom:var(--spacing-lg); color:var(--color-ui);">
            Архив
        </p>
        <p class="text-serif text-lg" style="max-width:480px; opacity:0.8; line-height:1.6; margin-bottom:var(--spacing-xl);">
            Систематизируем культуру. Разбираем&nbsp;кино, литературу, музыку и&nbsp;визуальное искусство — с&nbsp;глубиной, которую они заслуживают.
        </p>
    </div>

    <div class="home-hero__sections">
        <?php foreach ( $sections as $key => $sec ) : ?>
            <a href="<?php echo esc_url( home_url( $sec['url'] ) ); ?>"
               class="home-hero__section home-hero__section--<?php echo esc_attr( $key ); ?>">
                <span class="home-hero__section-title"><?php echo esc_html( $sec['label'] ); ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</section>


<!-- ============================================================
     2. ЖИВОЙ ИНДЕКС
     ============================================================ -->
<?php
$live_query = new WP_Query( [
    'post_type'              => [ 'article', 'news' ],
    'posts_per_page'         => 12,
    'post_status'            => 'publish',
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
] );
?>
<section class="section--dark" style="padding:var(--spacing-2xl) 0;">
    <div class="container">

        <div class="live-index__header" style="border-bottom-color:rgba(255,255,255,0.12);">
            <span class="live-index__label" style="color:var(--color-bg);">
                <span class="live-index__dot"></span>
                Живой индекс
            </span>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--sm" style="color:var(--color-bg); border-color:rgba(255,255,255,0.3);">
                Смотреть все →
            </a>
        </div>

        <?php if ( $live_query->have_posts() ) : ?>
            <div style="overflow-x:auto;">
                <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                    <?php while ( $live_query->have_posts() ) : $live_query->the_post();
                        $pid           = get_the_ID();
                        $s_terms       = get_the_terms( $pid, 'section' );
                        $s_name        = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->name : '—';
                        $s_slug        = ( $s_terms && ! is_wp_error( $s_terms ) ) ? $s_terms[0]->slug : '';
                        $type_label    = get_post_type( $pid ) === 'news' ? 'Новость' : 'Статья';
                    ?>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.06);">
                            <td class="text-mono text-xs section-<?php echo esc_attr( $s_slug ); ?>" style="padding:10px 12px 10px 0; color:var(--accent); white-space:nowrap; width:100px;">
                                <?php echo esc_html( $s_name ); ?>
                            </td>
                            <td class="text-mono text-xs text-muted" style="padding:10px 12px; white-space:nowrap; width:80px;">
                                <?php echo esc_html( $type_label ); ?>
                            </td>
                            <td style="padding:10px 12px;">
                                <a href="<?php echo esc_url( get_permalink() ); ?>" style="color:var(--color-bg); font-family:var(--font-serif); transition:color var(--transition);">
                                    <?php echo esc_html( get_the_title() ); ?>
                                </a>
                            </td>
                            <td class="text-mono text-xs text-muted hide-mobile" style="padding:10px 0 10px 12px; white-space:nowrap; text-align:right;">
                                <?php echo esc_html( palime_get_date( $pid ) ); ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </table>
            </div>
        <?php else : ?>
            <p class="text-mono text-sm text-muted" style="padding:var(--spacing-xl) 0;">Материалы скоро появятся.</p>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     3. ВЫБОР РЕДАКЦИИ
     ============================================================ -->
<?php
$editors_query = new WP_Query( [
    'post_type'      => 'article',
    'posts_per_page' => 5,
    'post_status'    => 'publish',
    'no_found_rows'  => true,
    'tax_query'      => [ [
        'taxonomy' => 'status',
        'field'    => 'slug',
        'terms'    => 'redakciya',
    ] ],
] );
?>
<section class="section">
    <div class="container">

        <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em;">
            Выбор редакции
        </h2>

        <?php if ( $editors_query->have_posts() ) :
            $posts_arr = $editors_query->posts;
            $first     = $posts_arr[0];
            $rest      = array_slice( $posts_arr, 1 );
        ?>
            <div class="grid grid--sidebar" style="align-items:start;">

                <!-- Большая карточка -->
                <article class="card fade-in">
                    <?php
                    $fp_id     = $first->ID;
                    $fp_link   = get_permalink( $fp_id );
                    $fp_title  = get_the_title( $fp_id );
                    $fp_terms  = get_the_terms( $fp_id, 'section' );
                    $fp_sec    = ( $fp_terms && ! is_wp_error( $fp_terms ) ) ? $fp_terms[0]->name : '';
                    $fp_slug   = ( $fp_terms && ! is_wp_error( $fp_terms ) ) ? $fp_terms[0]->slug : '';
                    $fp_lead   = function_exists( 'get_field' ) ? get_field( 'article_lead', $fp_id ) : '';
                    ?>
                    <div class="card__image aspect-16-9">
                        <a href="<?php echo esc_url( $fp_link ); ?>" tabindex="-1" aria-hidden="true">
                            <?php echo palime_get_thumbnail( $fp_id, 'card-lg', $fp_title ); ?>
                        </a>
                    </div>
                    <div class="card__body">
                        <div class="card__meta">
                            <?php if ( $fp_sec ) : ?>
                                <span class="text-accent section-<?php echo esc_attr( $fp_slug ); ?>"><?php echo esc_html( $fp_sec ); ?></span>
                                <span>·</span>
                            <?php endif; ?>
                            <span><?php echo esc_html( palime_get_date( $fp_id ) ); ?></span>
                        </div>
                        <h3 class="card__title" style="font-size:1.4rem;">
                            <a href="<?php echo esc_url( $fp_link ); ?>"><?php echo esc_html( $fp_title ); ?></a>
                        </h3>
                        <?php if ( $fp_lead ) : ?>
                            <p class="card__excerpt"><?php echo esc_html( palime_excerpt( $fp_lead, 30 ) ); ?></p>
                        <?php endif; ?>
                        <div class="card__footer">
                            <a href="<?php echo esc_url( $fp_link ); ?>" class="btn btn--ghost btn--sm">Открыть дело →</a>
                        </div>
                    </div>
                </article>

                <!-- Малые карточки -->
                <?php if ( $rest ) : ?>
                    <div style="display:flex; flex-direction:column; gap:var(--spacing-md);">
                        <?php foreach ( $rest as $rp ) :
                            $rp_id    = $rp->ID;
                            $rp_link  = get_permalink( $rp_id );
                            $rp_title = get_the_title( $rp_id );
                            $rp_terms = get_the_terms( $rp_id, 'section' );
                            $rp_sec   = ( $rp_terms && ! is_wp_error( $rp_terms ) ) ? $rp_terms[0]->name : '';
                            $rp_slug  = ( $rp_terms && ! is_wp_error( $rp_terms ) ) ? $rp_terms[0]->slug : '';
                        ?>
                            <article class="card card--horizontal fade-in">
                                <?php if ( has_post_thumbnail( $rp_id ) ) : ?>
                                    <div class="card__image">
                                        <a href="<?php echo esc_url( $rp_link ); ?>" tabindex="-1" aria-hidden="true">
                                            <?php echo palime_get_thumbnail( $rp_id, 'card', $rp_title ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <div class="card__body">
                                    <div class="card__meta">
                                        <?php if ( $rp_sec ) : ?>
                                            <span class="text-accent section-<?php echo esc_attr( $rp_slug ); ?>"><?php echo esc_html( $rp_sec ); ?></span>
                                            <span>·</span>
                                        <?php endif; ?>
                                        <span><?php echo esc_html( palime_get_date( $rp_id ) ); ?></span>
                                    </div>
                                    <h3 class="card__title">
                                        <a href="<?php echo esc_url( $rp_link ); ?>"><?php echo esc_html( $rp_title ); ?></a>
                                    </h3>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php else : ?>
            <p class="text-mono text-sm text-muted">Редакция готовит подборку.</p>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     4. ЛУЧШЕЕ ЗА МЕСЯЦ
     ============================================================ -->
<?php
$monthly_query = new WP_Query( [
    'post_type'              => 'monthly_best',
    'posts_per_page'         => 1,
    'post_status'            => 'publish',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
] );
?>
<section class="section section--accent">
    <div class="container">

        <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em;">
            Лучшее за месяц
        </h2>

        <?php if ( $monthly_query->have_posts() ) : $monthly_query->the_post();
            $mb_id = get_the_ID();
        ?>
            <div class="grid grid--2" style="align-items:start;">
                <div>
                    <h3 class="text-display mb-sm" style="font-size:1.3rem; letter-spacing:0.06em;">
                        <?php echo esc_html( get_the_title() ); ?>
                    </h3>
                    <div class="text-serif" style="line-height:1.7; opacity:0.85;">
                        <?php the_content(); ?>
                    </div>
                </div>

                <div>
                    <?php
                    $mb_sections = get_the_terms( $mb_id, 'section' );
                    if ( $mb_sections && ! is_wp_error( $mb_sections ) ) : ?>
                        <div style="display:flex; flex-direction:column; gap:var(--spacing-sm);">
                            <?php foreach ( $mb_sections as $mb_sec ) : ?>
                                <span class="tag section-<?php echo esc_attr( $mb_sec->slug ); ?>">
                                    <?php echo esc_html( $mb_sec->name ); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="flex flex--gap flex--wrap">
                            <?php foreach ( $section_labels as $slug => $label ) : ?>
                                <span class="tag section-<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $label ); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <p class="text-mono text-sm text-muted">Итоги месяца формируются.</p>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     5. НОВОСТИ
     ============================================================ -->
<?php
$news_query = new WP_Query( [
    'post_type'              => 'news',
    'posts_per_page'         => 6,
    'post_status'            => 'publish',
    'orderby'                => 'date',
    'order'                  => 'DESC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
] );
?>
<section class="section">
    <div class="container">

        <div class="flex flex--between mb-lg">
            <h2 class="text-display text-upper" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em;">
                Новости
            </h2>
            <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="btn btn--outline btn--sm">
                Все новости →
            </a>
        </div>

        <?php if ( $news_query->have_posts() ) : ?>
            <ul style="list-style:none; padding:0; margin:0;">
                <?php while ( $news_query->have_posts() ) : $news_query->the_post();
                    $n_id    = get_the_ID();
                    $n_terms = get_the_terms( $n_id, 'section' );
                    $n_sec   = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->name : '';
                    $n_slug  = ( $n_terms && ! is_wp_error( $n_terms ) ) ? $n_terms[0]->slug : '';
                ?>
                    <li style="border-bottom:1px solid rgba(0,0,0,0.08); padding:var(--spacing-md) 0;">
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="flex flex--between flex--gap" style="color:inherit; text-decoration:none;">
                            <div style="flex:1; min-width:0;">
                                <div class="card__meta mb-xs">
                                    <?php if ( $n_sec ) : ?>
                                        <span class="text-accent section-<?php echo esc_attr( $n_slug ); ?>"><?php echo esc_html( $n_sec ); ?></span>
                                        <span>·</span>
                                    <?php endif; ?>
                                    <span><?php echo esc_html( palime_get_date( $n_id ) ); ?></span>
                                </div>
                                <h3 style="font-family:var(--font-serif); font-size:1.05rem; font-weight:400; line-height:1.4;">
                                    <?php echo esc_html( get_the_title() ); ?>
                                </h3>
                            </div>
                            <span class="text-mono" style="flex-shrink:0; align-self:center; font-size:1.2rem; opacity:0.4;">→</span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else : ?>
            <p class="text-mono text-sm text-muted">Новостей пока нет.</p>
        <?php endif;
        wp_reset_postdata(); ?>

    </div>
</section>


<!-- ============================================================
     6. О ПРОЕКТЕ
     ============================================================ -->
<section class="section" style="background:var(--color-bg);">
    <div class="container container--narrow text-center">

        <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em;">
            О проекте
        </h2>

        <?php
        $manifesto = get_option( 'palime_manifesto' );
        if ( ! $manifesto ) :
            $manifesto = 'PALIME ARCHIVE — независимый медиаархив современной культуры. Мы систематизируем кино, литературу, музыку и визуальное искусство с глубиной и вниманием, которых они заслуживают. Каждый материал — это исследование: не рецензия, а разбор. Не мнение, а аргумент. Мы верим, что культура — не развлечение, а способ понимания мира.';
        endif;
        ?>
        <p class="text-serif text-lg" style="line-height:1.8; max-width:640px; margin:0 auto var(--spacing-xl);">
            <?php echo esc_html( $manifesto ); ?>
        </p>

        <div class="flex flex--center flex--gap">
            <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="btn btn--outline btn--sm">Подробнее</a>
        </div>

    </div>
</section>


<!-- ============================================================
     7. ПРЕВЬЮ МАГАЗИНА (АРТЕФАКТЫ)
     ============================================================ -->
<?php
$shop_product = null;
if ( class_exists( 'WooCommerce' ) ) {
    $shop_query = new WP_Query( [
        'post_type'      => 'product',
        'posts_per_page' => 1,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ] );
    if ( $shop_query->have_posts() ) {
        $shop_query->the_post();
        $shop_product = wc_get_product( get_the_ID() );
    }
    wp_reset_postdata();
}
?>
<section class="section--dark" style="padding:var(--spacing-2xl) 0;">
    <div class="container">

        <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em; color:var(--color-bg);">
            Артефакты
        </h2>

        <?php if ( $shop_product ) :
            $sp_name  = $shop_product->get_name();
            $sp_desc  = $shop_product->get_short_description();
            $sp_link  = $shop_product->get_permalink();
            $sp_price = $shop_product->get_price();
            $sp_img   = $shop_product->get_image_id();
        ?>
            <div class="grid grid--2" style="align-items:center; gap:var(--spacing-xl);">
                <div>
                    <?php if ( $sp_img ) : ?>
                        <div class="aspect-1-1 overflow-hidden" style="border-radius:var(--radius-md);">
                            <?php echo $shop_product->get_image( 'large', [ 'alt' => esc_attr( $sp_name ), 'style' => 'width:100%;height:100%;object-fit:cover;' ] ); ?>
                        </div>
                    <?php else : ?>
                        <div class="aspect-1-1" style="background:rgba(255,255,255,0.05); border-radius:var(--radius-md); display:flex; align-items:center; justify-content:center;">
                            <span class="text-mono text-muted" style="color:var(--color-bg);">Изображение скоро</span>
                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-mono text-xs text-upper mb-sm" style="color:var(--color-ui); letter-spacing:0.15em;">Текущий дроп</p>
                    <h3 class="text-display mb-md" style="font-size:1.6rem; color:var(--color-bg); letter-spacing:0.06em;">
                        <?php echo esc_html( $sp_name ); ?>
                    </h3>
                    <?php if ( $sp_desc ) : ?>
                        <p class="text-serif mb-lg" style="color:rgba(249,247,244,0.7); line-height:1.6;">
                            <?php echo esc_html( wp_strip_all_tags( $sp_desc ) ); ?>
                        </p>
                    <?php endif; ?>
                    <?php if ( $sp_price ) : ?>
                        <p class="text-mono mb-lg" style="font-size:1.2rem; color:var(--color-bg);">
                            <?php echo esc_html( number_format( (float) $sp_price, 0, '.', ' ' ) ); ?> ₽
                        </p>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn btn--primary">
                        В магазин →
                    </a>
                </div>
            </div>
        <?php else : ?>
            <div class="text-center" style="padding:var(--spacing-xl) 0;">
                <p class="text-mono text-sm" style="color:rgba(249,247,244,0.5); margin-bottom:var(--spacing-lg);">Коллекция готовится к запуску.</p>
                <a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" class="btn" style="color:var(--color-bg); border-color:rgba(255,255,255,0.3);">
                    В магазин →
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>


<!-- ============================================================
     8. ПРИСОЕДИНИТЬСЯ К АРХИВУ
     ============================================================ -->
<section class="section--dark" style="padding:var(--spacing-2xl) 0; border-top:1px solid rgba(255,255,255,0.06);">
    <div class="container container--narrow text-center">

        <h2 class="text-display text-upper mb-lg" style="font-size:clamp(1.4rem,3vw,2.2rem); letter-spacing:0.1em; color:var(--color-bg);">
            Присоединиться к&nbsp;архиву
        </h2>

        <!-- Уровни -->
        <div class="grid grid--4 mb-xl" style="gap:var(--spacing-md);">
            <?php
            $levels = [
                [ 'name' => 'Читатель',  'desc' => 'Доступ к материалам и живому индексу' ],
                [ 'name' => 'Архивист',  'desc' => 'Голосования, комментарии, сохранение статей' ],
                [ 'name' => 'Куратор',   'desc' => 'Ранний доступ к дропам и рейтингам' ],
                [ 'name' => 'Хранитель', 'desc' => 'Полный доступ, влияние на контент' ],
            ];
            foreach ( $levels as $level ) : ?>
                <div style="padding:var(--spacing-lg) var(--spacing-md); border:1px solid rgba(255,255,255,0.1); border-radius:var(--radius-sm);">
                    <p class="text-mono text-xs text-upper mb-sm" style="color:var(--color-ui); letter-spacing:0.12em;">
                        <?php echo esc_html( $level['name'] ); ?>
                    </p>
                    <p class="text-serif text-sm" style="color:rgba(249,247,244,0.6); line-height:1.5;">
                        <?php echo esc_html( $level['desc'] ); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Форма подписки -->
        <form class="subscribe-form mb-lg" style="max-width:480px; margin-left:auto; margin-right:auto;" action="#" method="post">
            <input type="email" name="email" class="form-input" placeholder="Email для подписки" required style="background:rgba(255,255,255,0.05); border-color:rgba(255,255,255,0.2); color:var(--color-bg);">
            <button type="submit" class="btn btn--primary">Подписаться</button>
        </form>

        <!-- Соцсети -->
        <div class="flex flex--center flex--gap">
            <?php
            $tg_url = get_option( 'palime_telegram_url' );
            $vk_url = get_option( 'palime_vk_url' );
            ?>
            <?php if ( $tg_url ) : ?>
                <a href="<?php echo esc_url( $tg_url ); ?>" class="btn btn--sm" style="color:var(--color-bg); border-color:rgba(255,255,255,0.3);" target="_blank" rel="noopener noreferrer">
                    Telegram
                </a>
            <?php endif; ?>
            <?php if ( $vk_url ) : ?>
                <a href="<?php echo esc_url( $vk_url ); ?>" class="btn btn--sm" style="color:var(--color-bg); border-color:rgba(255,255,255,0.3);" target="_blank" rel="noopener noreferrer">
                    VK
                </a>
            <?php endif; ?>
            <?php if ( ! $tg_url && ! $vk_url ) : ?>
                <span class="text-mono text-xs text-muted" style="color:rgba(249,247,244,0.4);">Ссылки на соцсети настраиваются в панели Palime</span>
            <?php endif; ?>
        </div>

    </div>
</section>

</main>

<?php get_footer(); ?>
