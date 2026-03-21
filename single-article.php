<?php
/**
 * Palime Archive — single-article.php
 * Шаблон одиночной статьи (CPT: article) с ACF-полями
 *
 * ACF-поля:
 *   article_type     — тип: 'author' | 'work' | 'selection'
 *   reading_time     — время чтения (число, мин)
 *   article_status   — статус: ПОДТВЕРЖДЕНО | СПОРНО | В АРХИВЕ
 *   person_featured  — имя главного персонажа статьи
 *   work_title       — название произведения (для типа 'work')
 *   article_lead     — лид (вступительный абзац, без тегов)
 *
 * @package Palime_Archive
 */

get_header();

while ( have_posts() ) : the_post();

    $post_id      = get_the_ID();
    $section_terms = get_the_terms( $post_id, 'section' );
    $section_slug  = ( $section_terms && ! is_wp_error( $section_terms ) ) ? $section_terms[0]->slug : '';

    // ACF поля
    $article_type    = function_exists( 'get_field' ) ? get_field( 'article_type' )    : '';
    $reading_time    = function_exists( 'get_field' ) ? get_field( 'reading_time' )    : '';
    $article_status  = function_exists( 'get_field' ) ? get_field( 'article_status' )  : '';
    $person_featured = function_exists( 'get_field' ) ? get_field( 'person_featured' ) : '';
    $work_title      = function_exists( 'get_field' ) ? get_field( 'work_title' )      : '';
    $article_lead    = function_exists( 'get_field' ) ? get_field( 'article_lead' )    : '';

    // Тип статьи — русское название
    $type_labels = [
        'author'    => 'Про автора',
        'work'      => 'Про произведение',
        'selection' => 'Подборка',
    ];
    $type_label = isset( $type_labels[ $article_type ] ) ? $type_labels[ $article_type ] : '';

    // Статус — человекочитаемый
    $status_labels = [
        'verified' => 'Подтверждено',
        'disputed' => 'Спорно',
        'archived' => 'В архиве',
    ];
    $status_label = isset( $status_labels[ $article_status ] ) ? $status_labels[ $article_status ] : $article_status;

?>

<!-- ====================================================
     HERO / ОБЛОЖКА СТАТЬИ
     ==================================================== -->
<!-- Кнопка назад: если same-origin referrer → history.back(), иначе → /archive/?section={slug} -->
<div class="mb-lg container" style="padding-top:var(--spacing-lg);">
    <a href="<?php echo esc_url( home_url( '/archive/?section=' . $section_slug ) ); ?>"
       class="btn btn--ghost btn--sm pa-back-btn"
       id="pa-article-back">
        ← Назад в архив
    </a>
</div>
<script>
(function () {
    var btn = document.getElementById('pa-article-back');
    if (btn && document.referrer) {
        try {
            var ref = new URL(document.referrer);
            if (ref.origin === location.origin) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    history.back();
                });
            }
        } catch (e) {}
    }
}());
</script>

<div class="article-hero" style="position:relative; background:var(--color-text); color:#fff; min-height:50vh; display:flex; align-items:flex-end;">

    <?php if ( has_post_thumbnail() ) : ?>
        <div style="position:absolute; inset:0; overflow:hidden;">
            <?php the_post_thumbnail( 'hero', [ 'style' => 'width:100%;height:100%;object-fit:cover;opacity:.4;', 'alt' => '' ] ); ?>
        </div>
    <?php endif; ?>

    <div class="container" style="position:relative; z-index:1; padding-top:var(--spacing-2xl); padding-bottom:var(--spacing-2xl);">

        <!-- Мета-строка -->
        <div class="flex flex--gap flex--wrap mb-lg" style="gap:var(--spacing-sm);">

            <?php if ( $type_label ) : ?>
                <span class="tag tag--filled" style="border-color:rgba(255,255,255,.3); background:rgba(255,255,255,.12); color:#fff;">
                    <?php echo esc_html( $type_label ); ?>
                </span>
            <?php endif; ?>

            <?php
            // Раздел → /{section}/
            if ( $section_terms && ! is_wp_error( $section_terms ) ) :
                foreach ( $section_terms as $st ) : ?>
                    <a href="<?php echo esc_url( home_url( '/' . $st->slug . '/' ) ); ?>" class="tag" style="border-color:var(--accent); color:var(--accent);">
                        <?php echo esc_html( $st->name ); ?>
                    </a>
                <?php endforeach;
            endif;

            // Тип статьи (таксономия)
            palime_the_terms( $post_id, 'article-type', 'tag' );
            ?>

            <?php if ( $status_label ) : ?>
                <span class="tag" style="border-color:rgba(255,255,255,.3); color:rgba(255,255,255,.6);">
                    <?php echo esc_html( $status_label ); ?>
                </span>
            <?php endif; ?>

        </div>

        <!-- Заголовок -->
        <h1 style="font-family:var(--font-display); font-size:clamp(1.8rem,4vw,3.5rem); line-height:1.15; max-width:800px; margin-bottom:var(--spacing-lg);">
            <?php the_title(); ?>
        </h1>

        <?php if ( $work_title ) : ?>
            <p class="text-mono text-xs" style="opacity:.55; letter-spacing:.1em; margin-bottom:var(--spacing-md);">
                <?php echo esc_html( $work_title ); ?>
            </p>
        <?php endif; ?>

        <!-- Нижняя мета -->
        <div class="flex flex--gap flex--wrap" style="font-family:var(--font-mono); font-size:.7rem; letter-spacing:.08em; text-transform:uppercase; opacity:.6;">
            <span><?php echo esc_html( palime_get_date() ); ?></span>
            <?php if ( $reading_time ) : ?>
                <span>· <?php echo esc_html( $reading_time ); ?> мин</span>
            <?php endif; ?>
            <?php if ( $person_featured ) : ?>
                <span>· <?php echo esc_html( $person_featured ); ?></span>
            <?php endif; ?>
        </div>

    </div>
</div>
<!-- /HERO -->

<!-- ====================================================
     ОСНОВНОЙ КОНТЕНТ
     ==================================================== -->
<div class="section">
    <div class="container">
        <div class="grid grid--sidebar">

            <!-- КОНТЕНТ -->
            <div class="article-content">

                <?php if ( $article_lead ) : ?>
                    <p class="article-lead mb-xl" style="font-family:var(--font-serif); font-size:1.25rem; line-height:1.7; color:var(--color-text); border-left:3px solid var(--accent); padding-left:var(--spacing-md);">
                        <?php echo esc_html( $article_lead ); ?>
                    </p>
                <?php endif; ?>

                <div class="entry-content" style="font-family:var(--font-serif); font-size:1.1rem; line-height:1.85;">
                    <?php the_content(); ?>
                </div>

                <!-- Теги персон -->
                <?php
                $person_terms = get_the_terms( $post_id, 'person' );
                if ( $person_terms && ! is_wp_error( $person_terms ) ) : ?>
                    <div class="mt-xl">
                        <p class="text-mono text-xs text-muted text-upper mb-sm" style="letter-spacing:.1em;">Персоны в материале</p>
                        <div class="flex flex--gap flex--wrap">
                            <?php foreach ( $person_terms as $person ) : ?>
                                <a href="<?php echo esc_url( home_url( '/archive/?person=' . $person->slug ) ); ?>" class="tag">
                                    <?php echo esc_html( $person->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Прочие таксономии -->
                <?php
                $era_terms = get_the_terms( $post_id, 'era' );
                if ( $era_terms && ! is_wp_error( $era_terms ) ) : ?>
                    <div class="mt-md">
                        <p class="text-mono text-xs text-muted text-upper mb-sm" style="letter-spacing:.1em;">Эпоха</p>
                        <div class="flex flex--gap flex--wrap">
                            <?php foreach ( $era_terms as $era ) : ?>
                                <a href="<?php echo esc_url( get_term_link( $era ) ); ?>" class="tag">
                                    <?php echo esc_html( $era->name ); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Навигация по статьям -->
                <nav class="flex flex--between mt-2xl" style="border-top:1px solid rgba(0,0,0,.08); padding-top:var(--spacing-xl);">
                    <?php
                    $prev_article = get_previous_post( false, '', 'section' );
                    $next_article = get_next_post( false, '', 'section' );
                    ?>
                    <?php if ( $prev_article ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $prev_article ) ); ?>" class="btn btn--ghost btn--sm">
                            ← <?php echo esc_html( wp_trim_words( get_the_title( $prev_article ), 5, '…' ) ); ?>
                        </a>
                    <?php else : ?>
                        <span></span>
                    <?php endif; ?>
                    <?php if ( $next_article ) : ?>
                        <a href="<?php echo esc_url( get_permalink( $next_article ) ); ?>" class="btn btn--ghost btn--sm">
                            <?php echo esc_html( wp_trim_words( get_the_title( $next_article ), 5, '…' ) ); ?> →
                        </a>
                    <?php endif; ?>
                </nav>

                <!-- Комментарии -->
                <?php if ( comments_open() || get_comments_number() ) : ?>
                    <hr class="divider">
                    <?php comments_template(); ?>
                <?php endif; ?>

            </div>
            <!-- /КОНТЕНТ -->

            <!-- САЙДБАР -->
            <aside class="article-sidebar">

                <!-- Карточка материала -->
                <div class="notice mb-lg" style="font-family:var(--font-mono); font-size:.75rem;">
                    <p class="text-upper text-muted mb-sm" style="letter-spacing:.1em; font-size:.65rem;">— Дело —</p>
                    <table style="width:100%; border-collapse:collapse;">
                        <?php if ( $type_label ) : ?>
                            <tr style="border-bottom:1px solid rgba(0,0,0,.06);">
                                <td style="padding:6px 0; opacity:.5;">Тип</td>
                                <td style="padding:6px 0; text-align:right;"><?php echo esc_html( $type_label ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $reading_time ) : ?>
                            <tr style="border-bottom:1px solid rgba(0,0,0,.06);">
                                <td style="padding:6px 0; opacity:.5;">Чтение</td>
                                <td style="padding:6px 0; text-align:right;"><?php echo esc_html( $reading_time ); ?> мин</td>
                            </tr>
                        <?php endif; ?>
                        <?php if ( $status_label ) : ?>
                            <tr style="border-bottom:1px solid rgba(0,0,0,.06);">
                                <td style="padding:6px 0; opacity:.5;">Статус</td>
                                <td style="padding:6px 0; text-align:right; color:var(--accent);"><?php echo esc_html( $status_label ); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding:6px 0; opacity:.5;">ID</td>
                            <td style="padding:6px 0; text-align:right; opacity:.4;">#<?php echo esc_html( $post_id ); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Похожие материалы -->
                <?php
                $related_args = [
                    'post_type'      => 'article',
                    'posts_per_page' => 4,
                    'post__not_in'   => [ $post_id ],
                    'orderby'        => 'rand',
                ];
                // Фильтруем по разделу, если есть
                if ( $section_slug ) {
                    $related_args['tax_query'] = [ [
                        'taxonomy' => 'section',
                        'field'    => 'slug',
                        'terms'    => $section_slug,
                    ] ];
                }
                $related = new WP_Query( $related_args );
                if ( $related->have_posts() ) : ?>
                    <div>
                        <p class="text-mono text-xs text-muted text-upper mb-md" style="letter-spacing:.1em;">Похожие материалы</p>
                        <ul style="list-style:none; display:flex; flex-direction:column; gap:var(--spacing-sm);">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <li style="border-bottom:1px solid rgba(0,0,0,.06); padding-bottom:var(--spacing-sm);">
                                    <a href="<?php the_permalink(); ?>" style="font-family:var(--font-serif); font-size:.95rem; line-height:1.4; display:block;">
                                        <?php the_title(); ?>
                                    </a>
                                    <span class="text-mono text-xs text-muted" style="font-size:.65rem;">
                                        <?php echo esc_html( palime_get_date() ); ?>
                                    </span>
                                </li>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </aside>
            <!-- /САЙДБАР -->

        </div>
    </div>
</div>

<!-- ====================================================
     БЛОК ПОХОЖИХ — полная сетка внизу
     ==================================================== -->
<?php
$more_args = [
    'post_type'      => 'article',
    'posts_per_page' => 3,
    'post__not_in'   => [ $post_id ],
    'orderby'        => 'date',
    'order'          => 'DESC',
];
if ( $section_slug ) {
    $more_args['tax_query'] = [ [
        'taxonomy' => 'section',
        'field'    => 'slug',
        'terms'    => $section_slug,
    ] ];
}
$more_posts = new WP_Query( $more_args );
if ( $more_posts->have_posts() ) : ?>

<div class="section section--accent">
    <div class="container">

        <div class="flex flex--between mb-xl">
            <h2 class="text-display" style="font-family:var(--font-display); font-size:1.5rem;">
                Ещё из <?php echo $section_slug ? esc_html( $section_terms[0]->name ) : 'архива'; ?>
            </h2>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--outline btn--sm">
                Весь архив →
            </a>
        </div>

        <div class="grid grid--3">
            <?php while ( $more_posts->have_posts() ) : $more_posts->the_post(); ?>
                <?php get_template_part( 'template-parts/cards/card', 'article' ); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>

    </div>
</div>

<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
