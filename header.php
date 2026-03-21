<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?> data-section="<?php echo esc_attr( palime_get_current_section() ); ?>">
<?php wp_body_open(); ?>

<?php
$current_section = palime_get_current_section();
$is_home         = is_front_page() || ( is_home() && ! is_front_page() );
$is_blog         = is_page( 'blog' );
$is_news         = is_post_type_archive( 'news' ) || is_singular( 'news' ) || is_page( 'news' );
$is_archive      = is_page( 'archive' );
$is_shop         = function_exists( 'is_woocommerce' ) && is_woocommerce();
$is_about        = is_page( 'about' ) || is_page( 'o-nas' );
$shop_url        = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

// Логотип: белый на тёмных разделах (cinema, music, art), тёмный — на остальных
$dark_sections = [ 'cinema', 'music', 'art' ];
$logo_file     = in_array( $current_section, $dark_sections, true ) ? 'logo-pa-white.svg' : 'logo-pa-dark.svg';
?>

<header class="pa-header">
    <div class="pa-header__inner">

        <!-- ── ЛОГОТИП ── -->
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pa-logo" aria-label="Palime Archive — на главную">
            <img
                src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/' . $logo_file ); ?>"
                height="28"
                alt="Palime Archive"
                width="auto"
            >
        </a>

        <!-- ── ДЕСКТОПНАЯ НАВИГАЦИЯ ── -->
        <nav class="pa-nav" aria-label="Основная навигация">
            <ul class="pa-nav__list" role="list">

                <!-- Главная + дропдаун -->
                <li class="pa-nav__item pa-nav__item--has-drop">
                    <button
                        class="pa-nav__link pa-nav__link--drop <?php echo ( $current_section || $is_home ) ? 'is-active' : ''; ?>"
                        aria-haspopup="true"
                        aria-expanded="false"
                        id="nav-home-btn"
                    >
                        <span class="pa-nav__dot" aria-hidden="true"></span>
                        ГЛАВНАЯ
                        <svg class="pa-nav__arrow" width="10" height="6" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.2" stroke-linecap="round"/>
                        </svg>
                    </button>
                    <ul class="pa-dropdown" role="menu" aria-labelledby="nav-home-btn">
                        <li role="none">
                            <a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>"
                               class="pa-dropdown__link <?php echo $current_section === 'cinema' ? 'is-active' : ''; ?>"
                               role="menuitem">
                                Кино
                            </a>
                        </li>
                        <li role="none">
                            <a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>"
                               class="pa-dropdown__link <?php echo $current_section === 'lit' ? 'is-active' : ''; ?>"
                               role="menuitem">
                                Литература
                            </a>
                        </li>
                        <li role="none">
                            <a href="<?php echo esc_url( home_url( '/music/' ) ); ?>"
                               class="pa-dropdown__link <?php echo $current_section === 'music' ? 'is-active' : ''; ?>"
                               role="menuitem">
                                Музыка
                            </a>
                        </li>
                        <li role="none">
                            <a href="<?php echo esc_url( home_url( '/art/' ) ); ?>"
                               class="pa-dropdown__link <?php echo $current_section === 'art' ? 'is-active' : ''; ?>"
                               role="menuitem">
                                ИЗО
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="pa-nav__item">
                    <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
                       class="pa-nav__link <?php echo $is_blog ? 'is-active' : ''; ?>">
                        БЛОГ
                    </a>
                </li>

                <li class="pa-nav__item">
                    <a href="<?php echo esc_url( home_url( '/news/' ) ); ?>"
                       class="pa-nav__link <?php echo $is_news ? 'is-active' : ''; ?>">
                        НОВОСТИ
                    </a>
                </li>

                <li class="pa-nav__item">
                    <a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>"
                       class="pa-nav__link <?php echo $is_archive ? 'is-active' : ''; ?>">
                        АРХИВ
                    </a>
                </li>

                <li class="pa-nav__item">
                    <a href="<?php echo esc_url( $shop_url ); ?>"
                       class="pa-nav__link <?php echo $is_shop ? 'is-active' : ''; ?>">
                        МАГАЗИН
                    </a>
                </li>

                <li class="pa-nav__item">
                    <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"
                       class="pa-nav__link <?php echo $is_about ? 'is-active' : ''; ?>">
                        О НАС
                    </a>
                </li>

            </ul>
        </nav>

        <!-- ── ПРАВАЯ ЧАСТЬ ── -->
        <div class="pa-header__actions">

            <!-- Кнопка поиска -->
            <button
                class="pa-search-toggle"
                id="pa-search-toggle"
                aria-label="Открыть поиск"
                aria-expanded="false"
                aria-controls="pa-search-bar"
            >
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
            </button>

            <?php if ( is_user_logged_in() ) : ?>
                <a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="pa-btn-profile">
                    ПРОФИЛЬ
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( wp_login_url() ); ?>" class="pa-btn-profile">
                    ВОЙТИ
                </a>
            <?php endif; ?>

            <!-- Бургер (мобайл) -->
            <button class="pa-burger" id="pa-burger" aria-label="Открыть меню" aria-expanded="false" aria-controls="pa-mobile-menu">
                <span></span><span></span><span></span>
            </button>
        </div>

    </div><!-- /.pa-header__inner -->

    <!-- ── СТРОКА ПОИСКА ── -->
    <div class="pa-search-bar" id="pa-search-bar" aria-hidden="true">
        <form
            role="search"
            method="get"
            action="<?php echo esc_url( home_url( '/' ) ); ?>"
            class="pa-search-bar__form"
        >
            <label for="pa-search-input" class="sr-only">Поиск по архиву</label>
            <input
                type="search"
                id="pa-search-input"
                class="pa-search-bar__input"
                name="s"
                placeholder="Поиск по архиву…"
                value="<?php echo esc_attr( get_search_query() ); ?>"
                autocomplete="off"
            >
            <button type="submit" class="pa-search-bar__submit" aria-label="Найти">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                    <circle cx="6.5" cy="6.5" r="5" stroke="currentColor" stroke-width="1.3"/>
                    <path d="M10.5 10.5L14 14" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
            </button>
            <button type="button" class="pa-search-bar__close" id="pa-search-close" aria-label="Закрыть поиск">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                    <path d="M1 1l12 12M13 1L1 13" stroke="currentColor" stroke-width="1.3" stroke-linecap="round"/>
                </svg>
            </button>
        </form>
    </div>

    <!-- ── МОБИЛЬНОЕ МЕНЮ ── -->
    <div class="pa-mobile-menu" id="pa-mobile-menu" aria-hidden="true" role="dialog" aria-label="Мобильное меню">
        <ul role="list">
            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Главная</a></li>
            <li><a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>">Кино</a></li>
            <li><a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>">Литература</a></li>
            <li><a href="<?php echo esc_url( home_url( '/music/' ) ); ?>">Музыка</a></li>
            <li><a href="<?php echo esc_url( home_url( '/art/' ) ); ?>">ИЗО</a></li>
            <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Блог</a></li>
            <li><a href="<?php echo esc_url( home_url( '/news/' ) ); ?>">Новости</a></li>
            <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>">Архив</a></li>
            <li><a href="<?php echo esc_url( $shop_url ); ?>">Магазин</a></li>
            <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">О нас</a></li>
            <?php if ( is_user_logged_in() ) : ?>
                <li><a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>">Профиль</a></li>
            <?php else : ?>
                <li><a href="<?php echo esc_url( wp_login_url() ); ?>">Войти</a></li>
            <?php endif; ?>
        </ul>
    </div>

</header>

<style>
/* =========================================================
   PA HEADER
   ========================================================= */

.pa-header {
    position: sticky;
    top: 0;
    z-index: var(--z-header);
    background: #0A0A0A;
    border-bottom: 1px solid rgba(255,255,255,.06);
    color: #fff;
}

.pa-header__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 48px;
    padding: 0 var(--gutter);
    max-width: var(--container);
    margin: 0 auto;
    gap: var(--spacing-xl);
}

/* Логотип */
.pa-logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    flex-shrink: 0;
    line-height: 0;
}

.pa-logo img {
    height: 28px;
    width: auto;
    display: block;
}

/* Навигация */
.pa-nav {
    flex: 1;
    display: flex;
    justify-content: center;
}

.pa-nav__list {
    display: flex;
    align-items: center;
    list-style: none;
    gap: 0;
    margin: 0;
    padding: 0;
}

.pa-nav__item {
    position: relative;
}

.pa-nav__link {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 14px;
    height: 48px;
    font-family: var(--font-mono);
    font-size: .7rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.55);
    background: none;
    border: none;
    cursor: pointer;
    white-space: nowrap;
    text-decoration: none;
    transition: color .15s ease;
    line-height: 1;
}

.pa-nav__link:visited {
    color: rgba(255,255,255,.55);
}

.pa-nav__link:hover,
.pa-nav__link.is-active {
    color: #fff;
}

/* Красная точка перед «Главная» */
.pa-nav__dot {
    display: block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #D91515;
    flex-shrink: 0;
}

/* Стрелка дропдауна */
.pa-nav__arrow {
    transition: transform .15s ease;
    flex-shrink: 0;
    opacity: .5;
}

.pa-nav__item--has-drop.is-open .pa-nav__arrow {
    transform: rotate(180deg);
}

/* Дропдаун */
.pa-dropdown {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    min-width: 160px;
    background: #111;
    border: 1px solid rgba(255,255,255,.08);
    border-top: 2px solid #D91515;
    list-style: none;
    margin: 0;
    padding: 4px 0;
    z-index: 200;
}

.pa-nav__item--has-drop.is-open .pa-dropdown {
    display: block;
}

.pa-dropdown__link {
    display: block;
    padding: 9px 16px;
    font-family: var(--font-mono);
    font-size: .68rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: rgba(255,255,255,.5);
    text-decoration: none;
    transition: color .12s, background .12s;
}

.pa-dropdown__link:visited {
    color: rgba(255,255,255,.5);
}

.pa-dropdown__link:hover,
.pa-dropdown__link.is-active {
    color: #fff;
    background: rgba(255,255,255,.04);
}

.pa-dropdown__link.is-active {
    color: #D91515;
}

/* Правая часть */
.pa-header__actions {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    flex-shrink: 0;
}

/* Кнопка поиска */
.pa-search-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    color: rgba(255,255,255,.55);
    background: none;
    border: none;
    cursor: pointer;
    transition: color .15s;
    padding: 0;
}

.pa-search-toggle:hover,
.pa-search-toggle[aria-expanded="true"] {
    color: #fff;
}

/* Строка поиска */
.pa-search-bar {
    overflow: hidden;
    max-height: 0;
    transition: max-height .25s ease, border-color .25s;
    border-top: 0px solid rgba(255,255,255,.06);
    background: #0A0A0A;
}

.pa-search-bar[aria-hidden="false"] {
    max-height: 56px;
    border-top-width: 1px;
}

.pa-search-bar__form {
    display: flex;
    align-items: center;
    gap: 0;
    max-width: var(--container);
    margin: 0 auto;
    padding: 0 var(--gutter);
    height: 56px;
}

.pa-search-bar__input {
    flex: 1;
    height: 100%;
    background: none;
    border: none;
    outline: none;
    color: #fff;
    font-family: var(--font-mono);
    font-size: .85rem;
    letter-spacing: .04em;
    caret-color: #D91515;
}

.pa-search-bar__input::placeholder {
    color: rgba(255,255,255,.25);
}

.pa-search-bar__submit,
.pa-search-bar__close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: none;
    border: none;
    cursor: pointer;
    color: rgba(255,255,255,.45);
    transition: color .12s;
    padding: 0;
    flex-shrink: 0;
}

.pa-search-bar__submit:hover { color: #fff; }
.pa-search-bar__close:hover  { color: #fff; }

/* Кнопка профиля */
.pa-btn-profile {
    padding: 6px 16px;
    font-family: var(--font-mono);
    font-size: .68rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #fff;
    border: 1px solid rgba(255,255,255,.35);
    text-decoration: none;
    transition: background .15s, border-color .15s;
    white-space: nowrap;
}

.pa-btn-profile:visited {
    color: #fff;
}

.pa-btn-profile:hover {
    background: rgba(255,255,255,.08);
    border-color: rgba(255,255,255,.6);
}

/* Бургер */
.pa-burger {
    display: none;
    flex-direction: column;
    gap: 4px;
    padding: 6px;
    background: none;
    border: none;
    cursor: pointer;
}

.pa-burger span {
    display: block;
    width: 20px;
    height: 1px;
    background: #fff;
    transition: all .2s;
}

.pa-burger[aria-expanded="true"] span:nth-child(1) { transform: translateY(5px) rotate(45deg); }
.pa-burger[aria-expanded="true"] span:nth-child(2) { opacity: 0; }
.pa-burger[aria-expanded="true"] span:nth-child(3) { transform: translateY(-5px) rotate(-45deg); }

/* Мобильное меню */
.pa-mobile-menu {
    display: none;
    position: fixed;
    inset: 48px 0 0 0;
    background: #0A0A0A;
    z-index: 150;
    overflow-y: auto;
    padding: var(--spacing-xl) var(--gutter);
}

.pa-mobile-menu[aria-hidden="false"] {
    display: block;
}

.pa-mobile-menu ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 0;
}

.pa-mobile-menu a {
    display: block;
    padding: 14px 0;
    font-family: var(--font-mono);
    font-size: .85rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: rgba(255,255,255,.6);
    text-decoration: none;
    border-bottom: 1px solid rgba(255,255,255,.06);
    transition: color .12s;
}

.pa-mobile-menu a:visited {
    color: rgba(255,255,255,.6);
}

.pa-mobile-menu a:hover {
    color: #fff;
}

/* ── АДАПТИВ ── */
@media (max-width: 900px) {
    .pa-nav { display: none; }
    .pa-burger { display: flex; }
}

@media (max-width: 480px) {
    .pa-btn-profile { display: none; }
}
</style>

<script>
(function () {
    // ── Дропдаун «Главная» ─────────────────────────────────
    var dropItem = document.querySelector('.pa-nav__item--has-drop');
    var dropBtn  = dropItem ? dropItem.querySelector('.pa-nav__link--drop') : null;

    if (dropBtn) {
        dropBtn.addEventListener('click', function () {
            var open = dropItem.classList.toggle('is-open');
            dropBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        document.addEventListener('click', function (e) {
            if (!dropItem.contains(e.target)) {
                dropItem.classList.remove('is-open');
                dropBtn.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                dropItem.classList.remove('is-open');
                dropBtn.setAttribute('aria-expanded', 'false');
                dropBtn.focus();
            }
        });
    }

    // ── Бургер ─────────────────────────────────────────────
    var burger     = document.getElementById('pa-burger');
    var mobileMenu = document.getElementById('pa-mobile-menu');

    if (burger && mobileMenu) {
        burger.addEventListener('click', function () {
            var open = mobileMenu.getAttribute('aria-hidden') === 'true';
            mobileMenu.setAttribute('aria-hidden', open ? 'false' : 'true');
            burger.setAttribute('aria-expanded',   open ? 'true'  : 'false');
            document.body.style.overflow = open ? 'hidden' : '';
        });
    }

    // ── Поиск ──────────────────────────────────────────────
    var searchToggle = document.getElementById('pa-search-toggle');
    var searchBar    = document.getElementById('pa-search-bar');
    var searchClose  = document.getElementById('pa-search-close');
    var searchInput  = document.getElementById('pa-search-input');

    function openSearch() {
        searchBar.setAttribute('aria-hidden', 'false');
        searchToggle.setAttribute('aria-expanded', 'true');
        // Фокус после анимации
        setTimeout(function () { if (searchInput) searchInput.focus(); }, 50);
    }

    function closeSearch() {
        searchBar.setAttribute('aria-hidden', 'true');
        searchToggle.setAttribute('aria-expanded', 'false');
        searchToggle.focus();
    }

    if (searchToggle && searchBar) {
        searchToggle.addEventListener('click', function () {
            var isOpen = searchBar.getAttribute('aria-hidden') === 'false';
            isOpen ? closeSearch() : openSearch();
        });
    }

    if (searchClose) {
        searchClose.addEventListener('click', closeSearch);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && searchBar && searchBar.getAttribute('aria-hidden') === 'false') {
            closeSearch();
        }
    });
})();
</script>

<main id="main-content">
