</main><!-- #main-content -->

<footer class="site-footer">
    <div class="container">

        <!-- Верхняя часть: логотип + колонки -->
        <div class="footer__top grid grid--4 mb-2xl">

            <!-- Логотип и описание -->
            <div class="footer__brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer__logo" aria-label="Palime Archive">
                    <?php
                    $logo_path = get_template_directory() . '/assets/img/logo-white.svg';
                    if ( file_exists( $logo_path ) ) {
                        echo file_get_contents( $logo_path );
                    }
                    ?>
                </a>
                <p class="footer__desc text-sm mt-md" style="opacity:.5; line-height:1.6;">
                    Архив культуры.<br>Кино, литература, музыка, ИЗО.
                </p>
                <!-- Соцсети -->
                <div class="footer__socials flex flex--gap mt-lg">
                    <?php $tg = get_option( 'palime_telegram_url' ); ?>
                    <?php $vk = get_option( 'palime_vk_url' ); ?>
                    <?php if ( $tg ) : ?>
                        <a href="<?php echo esc_url( $tg ); ?>" class="footer__social text-mono text-xs" target="_blank" rel="noopener">TG</a>
                    <?php endif; ?>
                    <?php if ( $vk ) : ?>
                        <a href="<?php echo esc_url( $vk ); ?>" class="footer__social text-mono text-xs" target="_blank" rel="noopener">VK</a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Разделы -->
            <div class="footer__col">
                <h4 class="footer__col-title text-mono text-xs text-upper mb-md" style="opacity:.4; letter-spacing:.12em;">Разделы</h4>
                <ul class="footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>" class="footer__link text-sm">Кино</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>" class="footer__link text-sm">Литература</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/music/' ) ); ?>" class="footer__link text-sm">Музыка</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/art/' ) ); ?>" class="footer__link text-sm">ИЗО</a></li>
                </ul>
            </div>

            <!-- Проект -->
            <div class="footer__col">
                <h4 class="footer__col-title text-mono text-xs text-upper mb-md" style="opacity:.4; letter-spacing:.12em;">Проект</h4>
                <ul class="footer__links">
                    <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="footer__link text-sm">Архив</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="footer__link text-sm">Блог</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="footer__link text-sm">Новости</a></li>
                    <li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="footer__link text-sm">Магазин</a></li>
                </ul>
            </div>

            <!-- Подписка -->
            <div class="footer__col">
                <h4 class="footer__col-title text-mono text-xs text-upper mb-md" style="opacity:.4; letter-spacing:.12em;">Рассылка</h4>
                <p class="text-sm mb-md" style="opacity:.5;">Лучшее раз в месяц — без спама</p>
                <form class="subscribe-form" novalidate>
                    <input
                        type="email"
                        class="form-input"
                        placeholder="your@email.com"
                        style="background:rgba(255,255,255,.08); border-color:rgba(255,255,255,.15); color:#fff;"
                        required
                    >
                    <button type="submit" class="btn btn--primary btn--sm">→</button>
                </form>
            </div>

        </div>

        <!-- Нижняя часть: копирайт + ссылки -->
        <div class="footer__bottom flex flex--between" style="border-top:1px solid rgba(255,255,255,.08); padding-top:var(--spacing-lg); opacity:.4;">
            <p class="text-mono text-xs">
                © <?php echo date( 'Y' ); ?> Palime Archive
            </p>
            <div class="flex flex--gap">
                <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="text-mono text-xs">Политика конфиденциальности</a>
            </div>
        </div>

    </div>
</footer>

<!-- Стили для футера (инлайн, чтобы не создавать отдельный файл) -->
<style>
.footer__logo svg { height: 20px; width: auto; }
.footer__links    { display: flex; flex-direction: column; gap: var(--spacing-sm); }
.footer__link     { opacity: .6; transition: opacity var(--transition); }
.footer__link:hover { opacity: 1; color: #fff; }
.footer__social   { opacity: .5; transition: opacity var(--transition); }
.footer__social:hover { opacity: 1; }

@media (max-width: 768px) {
    .footer__top { grid-template-columns: 1fr 1fr; }
    .footer__bottom { flex-direction: column; gap: var(--spacing-sm); align-items: flex-start; }
}
@media (max-width: 480px) {
    .footer__top { grid-template-columns: 1fr; }
}
</style>

<?php wp_footer(); ?>
</body>
</html>
