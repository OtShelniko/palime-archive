<?php
/**
 * Palime Archive — 404.php
 * Страница «Запись не найдена»
 *
 * @package Palime_Archive
 */

get_header();
?>

<div class="section" style="min-height: 60vh; display:flex; align-items:center;">
    <div class="container text-center">

        <p class="text-mono text-muted text-upper mb-lg" style="letter-spacing:.2em; font-size:.75rem;">
            — Ошибка 404 —
        </p>

        <h1 class="mb-md" style="font-family:var(--font-display); font-size:clamp(4rem,12vw,10rem); line-height:1; color:var(--accent);">
            404
        </h1>

        <p class="text-lg text-muted mb-xl" style="font-family:var(--font-serif); max-width:480px; margin-left:auto; margin-right:auto; line-height:1.7;">
            Эта страница не существует или была удалена из архива.
        </p>

        <div class="flex flex--center flex--gap flex--wrap">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">На главную</a>
            <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="btn btn--outline">Открыть архив</a>
        </div>

        <!-- Поиск -->
        <div class="mt-2xl" style="max-width: 480px; margin-left: auto; margin-right: auto;">
            <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="subscribe-form">
                    <input
                        type="search"
                        class="form-input"
                        placeholder="Поиск по архиву…"
                        value="<?php echo get_search_query(); ?>"
                        name="s"
                    >
                    <button type="submit" class="btn btn--primary">→</button>
                </div>
            </form>
        </div>

    </div>
</div>

<?php get_footer(); ?>
