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

<header class="site-header">
    <div class="container">
        <nav class="nav flex flex--between">

            <!-- Логотип -->
            <?php
            $section       = palime_get_current_section();
            $dark_sections = [ 'cinema', 'music', 'art' ];
            $logo_file     = in_array( $section, $dark_sections ) ? 'logo-white.svg' : 'logo-dark.svg';
            $logo_url      = get_template_directory_uri() . '/assets/img/' . $logo_file;
            ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav__logo" aria-label="Palime Archive">
                <img src="<?php echo esc_url( $logo_url ); ?>" alt="Palime Archive" height="20" style="height:20px;width:auto;display:block;">
            </a>

            <!-- Десктопное меню -->
            <ul class="nav__links nav__links--desktop" role="list">
                <li><a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>" class="nav__link <?php echo $section === 'cinema' ? 'active' : ''; ?>" data-go-section="cinema">Кино</a></li>
                <li><a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>" class="nav__link <?php echo $section === 'lit' ? 'active' : ''; ?>" data-go-section="lit">Литература</a></li>
                <li><a href="<?php echo esc_url( home_url( '/music/' ) ); ?>" class="nav__link <?php echo $section === 'music' ? 'active' : ''; ?>" data-go-section="music">Музыка</a></li>
                <li><a href="<?php echo esc_url( home_url( '/art/' ) ); ?>" class="nav__link <?php echo $section === 'art' ? 'active' : ''; ?>" data-go-section="art">ИЗО</a></li>
                <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="nav__link <?php echo is_page( 'archive' ) ? 'active' : ''; ?>">Архив</a></li>
                <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="nav__link <?php echo is_page( 'blog' ) ? 'active' : ''; ?>">Блог</a></li>
                <li><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>" class="nav__link <?php echo function_exists( 'is_woocommerce' ) && is_woocommerce() ? 'active' : ''; ?>">Магазин</a></li>
            </ul>

            <!-- Правая часть -->
            <div class="nav__actions flex flex--gap">
                <?php if ( is_user_logged_in() ) : ?>
                    <a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="btn btn--ghost btn--sm text-mono">Профиль</a>
                <?php else : ?>
                    <a href="<?php echo esc_url( wp_login_url() ); ?>" class="btn btn--outline btn--sm">Войти</a>
                <?php endif; ?>
                <button class="burger" id="burger-btn" aria-label="Меню" aria-expanded="false" aria-controls="mobile-menu">
                    <span class="burger__line"></span>
                    <span class="burger__line"></span>
                    <span class="burger__line"></span>
                </button>
            </div>

        </nav>
    </div>

    <!-- Мобильное меню -->
    <div class="nav__mobile" id="mobile-menu" aria-hidden="true">
        <ul class="nav__mobile-list" role="list">
            <li><a href="<?php echo esc_url( home_url( '/cinema/' ) ); ?>" class="nav__link" data-go-section="cinema">Кино</a></li>
            <li><a href="<?php echo esc_url( home_url( '/literature/' ) ); ?>" class="nav__link" data-go-section="lit">Литература</a></li>
            <li><a href="<?php echo esc_url( home_url( '/music/' ) ); ?>" class="nav__link" data-go-section="music">Музыка</a></li>
            <li><a href="<?php echo esc_url( home_url( '/art/' ) ); ?>" class="nav__link" data-go-section="art">ИЗО</a></li>
            <li><a href="<?php echo esc_url( home_url( '/archive/' ) ); ?>" class="nav__link">Архив</a></li>
            <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="nav__link">Блог</a></li>
            <li><a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) ); ?>" class="nav__link">Магазин</a></li>
            <?php if ( is_user_logged_in() ) : ?>
                <li><a href="<?php echo esc_url( home_url( '/profile/' ) ); ?>" class="nav__link">Профиль</a></li>
            <?php else : ?>
                <li><a href="<?php echo esc_url( wp_login_url() ); ?>" class="nav__link">Войти</a></li>
            <?php endif; ?>
        </ul>
    </div>

</header>

<main id="main-content">