</main><!-- /#main-content -->

<footer class="pa-footer">

    <!-- ── ОСНОВНАЯ СЕТКА ── -->
    <div class="pa-footer__grid">

        <!-- Колонка 1: бренд -->
        <div class="pa-footer__brand">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pa-footer__wordmark" aria-label="Palime Archive">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-pa-white.svg" height="28" alt="Palime Archive">
            </a>
            <p class="pa-footer__desc">
                Независимый культурный архив. Кино,<br>
                литература, музыка, изобразительное<br>
                искусство.
            </p>
            <p class="pa-footer__meta">ОСН. 2026</p>
            <p class="pa-footer__disclaimer">Некоторые записи остаются спорными.</p>
            <p class="pa-footer__version">ВЕРСИЯ 1.8</p>
        </div>

        <!-- Колонка 2: Архив -->
        <div class="pa-footer__col">
            <h4 class="pa-footer__col-title">АРХИВ</h4>
            <ul class="pa-footer__links">
                <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-footer__link">Все статьи</a></li>
                <li><a href="<?php echo esc_url( home_url( '/archive/?type=selection' ) ); ?>" class="pa-footer__link">Подборки</a></li>
                <li><a href="<?php echo esc_url( function_exists( 'palime_get_rankings_archive_url' ) ? palime_get_rankings_archive_url() : home_url( '/rankings/' ) ); ?>" class="pa-footer__link">Рейтинги</a></li>
                <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="pa-footer__link">Новости</a></li>
                <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="pa-footer__link">Поиск</a></li>
            </ul>
        </div>

        <!-- Колонка 3: Разделы -->
        <div class="pa-footer__col">
            <h4 class="pa-footer__col-title">РАЗДЕЛЫ</h4>
            <ul class="pa-footer__links">
                <li><a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>" class="pa-footer__link">Кино</a></li>
                <li><a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>" class="pa-footer__link">Литература</a></li>
                <li><a href="<?php echo esc_url( home_url( '/music/' ) ); ?>" class="pa-footer__link">Музыка</a></li>
                <li><a href="<?php echo esc_url( home_url( '/art/' ) ); ?>" class="pa-footer__link">ИЗО</a></li>
            </ul>
        </div>

        <!-- Колонка 4: Информация + Правовое -->
        <div class="pa-footer__col">
            <h4 class="pa-footer__col-title">ИНФОРМАЦИЯ</h4>
            <ul class="pa-footer__links">
                <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="pa-footer__link">О проекте</a></li>
                <li><a href="<?php echo esc_url( home_url( '/editorial/' ) ); ?>" class="pa-footer__link">Редакция</a></li>
                <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="pa-footer__link">Написать нам</a></li>
            </ul>

            <h4 class="pa-footer__col-title pa-footer__col-title--sub">ПРАВОВОЕ</h4>
            <ul class="pa-footer__links">
                <li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="pa-footer__link">Политика</a></li>
                <li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>" class="pa-footer__link">Условия</a></li>
                <li><a href="<?php echo esc_url( home_url( '/copyright/' ) ); ?>" class="pa-footer__link">Авторское право</a></li>
                <li><a href="<?php echo esc_url( home_url( '/licenses/' ) ); ?>" class="pa-footer__link">Лицензии</a></li>
            </ul>
        </div>

    </div>
    <!-- /.pa-footer__grid -->

    <!-- ── НИЖНЯЯ ПОЛОСА ── -->
    <div class="pa-footer__bottom">
        <p class="pa-footer__copy">
            © <?php echo date( 'Y' ); ?> PALIME ARCHIVE &middot; ВСЕ ПРАВА ЗАЩИЩЕНЫ
        </p>
        <div class="pa-footer__lang">
            <a href="<?php echo esc_url( home_url( '/?lang=vk' ) ); ?>" class="pa-footer__lang-link">ВКЛ.</a>
            <span class="pa-footer__lang-sep">|</span>
            <a href="<?php echo esc_url( home_url( '/?lang=net' ) ); ?>" class="pa-footer__lang-link">НЕТ</a>
            <span class="pa-footer__lang-sep">&middot;</span>
            <a href="<?php echo esc_url( home_url( '/?lang=en' ) ); ?>" class="pa-footer__lang-link">ENGLISH</a>
            <span class="pa-footer__lang-sep">|</span>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pa-footer__lang-link pa-footer__lang-link--active">RU</a>
        </div>
    </div>

</footer>

<style>
/* =========================================================
   PA FOOTER
   ========================================================= */

/* Убрать подсветку посещённых ссылок */
.pa-footer a:visited {
    color: inherit;
}

.pa-footer {
    --color-ui: #D91515;
    --accent: #D91515;
    background: #0A0A0A;
    color: #fff;
    border-top: 1px solid rgba(255,255,255,.06);
    font-family: var(--font-mono);
}

/* Основная сетка: 1 широкая + 3 колонки */
.pa-footer__grid {
    display: grid;
    grid-template-columns: 1.6fr 1fr 1fr 1.2fr;
    gap: var(--spacing-xl);
    max-width: var(--container);
    margin: 0 auto;
    padding: 56px var(--gutter) 48px;
}

/* Бренд */
.pa-footer__wordmark {
    display: block;
    text-decoration: none;
    margin-bottom: 20px;
}

.pa-footer__wordmark img {
    display: block;
    height: 28px;
    width: auto;
}

.pa-footer__desc {
    font-size: .72rem;
    line-height: 1.75;
    color: rgba(255,255,255,.45);
    margin-bottom: 18px;
}

.pa-footer__meta {
    font-size: .65rem;
    letter-spacing: .1em;
    color: rgba(255,255,255,.25);
    margin-bottom: 14px;
}

.pa-footer__disclaimer {
    font-size: .68rem;
    color: rgba(255,255,255,.35);
    margin-bottom: 8px;
    line-height: 1.5;
}

.pa-footer__version {
    font-size: .63rem;
    letter-spacing: .1em;
    color: rgba(255,255,255,.2);
}

/* Заголовок колонки */
.pa-footer__col-title {
    font-family: var(--font-mono);
    font-size: .62rem;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: rgba(255,255,255,.3);
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(255,255,255,.08);
    font-weight: 400;
}

.pa-footer__col-title--sub {
    margin-top: 28px;
}

/* Ссылки */
.pa-footer__links {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.pa-footer__link {
    font-size: .72rem;
    letter-spacing: .04em;
    color: rgba(255,255,255,.45);
    text-decoration: none;
    transition: color .12s;
}

.pa-footer__link:hover {
    color: #fff;
}

/* Нижняя полоса */
.pa-footer__bottom {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: var(--container);
    margin: 0 auto;
    padding: 16px var(--gutter);
    border-top: 1px solid rgba(255,255,255,.06);
    gap: var(--spacing-md);
}

.pa-footer__copy {
    font-size: .6rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.22);
}

.pa-footer__lang {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pa-footer__lang-link {
    font-size: .6rem;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: rgba(255,255,255,.25);
    text-decoration: none;
    transition: color .12s;
}

.pa-footer__lang-link:hover {
    color: rgba(255,255,255,.6);
}

.pa-footer__lang-link--active {
    color: rgba(255,255,255,.55);
}

.pa-footer__lang-sep {
    color: rgba(255,255,255,.12);
    font-size: .6rem;
}

/* ── АДАПТИВ ── */
@media (max-width: 1024px) {
    .pa-footer__grid {
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-lg);
    }
}

@media (max-width: 600px) {
    .pa-footer__grid {
        grid-template-columns: 1fr;
        padding-top: 40px;
        padding-bottom: 32px;
    }
    .pa-footer__bottom {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<?php wp_footer(); ?>
</body>
</html>
