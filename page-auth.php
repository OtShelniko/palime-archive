<?php
/**
 * Template Name: Авторизация
 *
 * Palime Archive — page-auth.php
 * Кастомная страница входа / регистрации
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Если уже авторизован — на профиль
if ( is_user_logged_in() ) {
    wp_redirect( home_url( '/profile/' ) );
    exit;
}

// Определяем активную вкладку
$active_tab = isset( $_GET['tab'] ) && $_GET['tab'] === 'register' ? 'register' : 'login';
$redirect   = ! empty( $_GET['redirect_to'] ) ? esc_url( $_GET['redirect_to'] ) : home_url( '/profile/' );

get_header();
?>

<div class="auth-page">
    <div class="auth-page__container">

        <!-- ── ЛЕВАЯ КОЛОНКА: декоративная ── -->
        <div class="auth-page__aside">
            <div class="auth-page__aside-inner">
                <div class="auth-page__brand">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-pa-white.svg" height="28" alt="Palime Archive">
                </div>
                <h2 class="auth-page__tagline">НЕЗАВИСИМЫЙ<br>КУЛЬТУРНЫЙ<br>АРХИВ</h2>
                <p class="auth-page__aside-desc">
                    Кино. Литература. Музыка. ИЗО.<br>
                    Зарегистрируйтесь, чтобы сохранять статьи,<br>
                    голосовать в рейтингах и получать XP.
                </p>
                <div class="auth-page__aside-meta">ОСН. 2026</div>
            </div>
        </div>

        <!-- ── ПРАВАЯ КОЛОНКА: формы ── -->
        <div class="auth-page__main">

            <!-- Табы -->
            <div class="auth-tabs">
                <button class="auth-tabs__btn <?php echo $active_tab === 'login' ? 'is-active' : ''; ?>" data-tab="login">ВХОД</button>
                <button class="auth-tabs__btn <?php echo $active_tab === 'register' ? 'is-active' : ''; ?>" data-tab="register">РЕГИСТРАЦИЯ</button>
            </div>

            <!-- Уведомления -->
            <div class="auth-notice" id="auth-notice" style="display:none;"></div>

            <!-- ── ФОРМА ВХОДА ── -->
            <form class="auth-form <?php echo $active_tab === 'login' ? 'is-active' : ''; ?>" id="auth-login-form" data-tab="login" novalidate>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>">

                <div class="auth-form__field">
                    <label class="auth-form__label" for="login-email">Email или имя пользователя</label>
                    <input type="text" id="login-email" name="log" class="auth-form__input" autocomplete="username" required>
                </div>

                <div class="auth-form__field">
                    <label class="auth-form__label" for="login-pass">Пароль</label>
                    <input type="password" id="login-pass" name="pwd" class="auth-form__input" autocomplete="current-password" required>
                </div>

                <div class="auth-form__row">
                    <label class="auth-form__checkbox">
                        <input type="checkbox" name="rememberme" value="forever">
                        <span>Запомнить меня</span>
                    </label>
                    <a href="<?php echo esc_url( wp_lostpassword_url( get_permalink() ) ); ?>" class="auth-form__link">Забыли пароль?</a>
                </div>

                <button type="submit" class="auth-form__submit">ВОЙТИ</button>
            </form>

            <!-- ── ФОРМА РЕГИСТРАЦИИ ── -->
            <?php if ( get_option( 'users_can_register' ) ) : ?>
            <form class="auth-form <?php echo $active_tab === 'register' ? 'is-active' : ''; ?>" id="auth-register-form" data-tab="register" novalidate>
                <input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>">

                <div class="auth-form__field">
                    <label class="auth-form__label" for="reg-username">Имя пользователя</label>
                    <input type="text" id="reg-username" name="user_login" class="auth-form__input" autocomplete="username" required>
                </div>

                <div class="auth-form__field">
                    <label class="auth-form__label" for="reg-email">Email</label>
                    <input type="email" id="reg-email" name="user_email" class="auth-form__input" autocomplete="email" required>
                </div>

                <div class="auth-form__field">
                    <label class="auth-form__label" for="reg-pass">Пароль</label>
                    <input type="password" id="reg-pass" name="user_pass" class="auth-form__input" autocomplete="new-password" required minlength="6">
                </div>

                <div class="auth-form__field">
                    <label class="auth-form__label" for="reg-pass2">Подтвердите пароль</label>
                    <input type="password" id="reg-pass2" name="user_pass2" class="auth-form__input" autocomplete="new-password" required minlength="6">
                </div>

                <button type="submit" class="auth-form__submit">ЗАРЕГИСТРИРОВАТЬСЯ</button>

                <p class="auth-form__terms">
                    Регистрируясь, вы принимаете
                    <a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>">условия</a> и
                    <a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>">политику конфиденциальности</a>.
                </p>
            </form>
            <?php endif; ?>

            <!-- ── СОЦСЕТИ ── -->
            <div class="auth-social">
                <div class="auth-social__divider">
                    <span>или</span>
                </div>

                <!-- Telegram -->
                <div class="auth-social__btn auth-social__btn--telegram" id="auth-telegram-wrap">
                    <div id="telegram-login-widget"></div>
                    <noscript>
                        <p class="auth-form__hint">Для входа через Telegram включите JavaScript.</p>
                    </noscript>
                </div>

                <!-- VK (заглушка) -->
                <button class="auth-social__btn auth-social__btn--vk" disabled>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M21.547 7h-3.29a.743.743 0 0 0-.655.392s-1.312 2.416-1.734 3.23C14.734 12.813 14 12.126 14 11.11V7.603A1.104 1.104 0 0 0 12.896 6.5h-2.474a1.982 1.982 0 0 0-1.75.813s1.255-.204 1.255 1.49c0 .42.022 1.626.04 2.64a.73.73 0 0 1-1.272.503 21.54 21.54 0 0 1-2.498-4.543.693.693 0 0 0-.63-.403h-2.99a.508.508 0 0 0-.48.685C3.005 10.175 6.918 18 11.38 18h1.878a.742.742 0 0 0 .742-.742v-1.135a.73.73 0 0 1 1.23-.53l2.247 2.112a1.09 1.09 0 0 0 .746.295h2.953c1.424 0 1.424-.988.647-1.753-.546-.538-2.518-2.617-2.518-2.617a1.02 1.02 0 0 1-.078-1.323c.637-.84 1.68-2.212 2.122-2.8.603-.804 1.697-2.507.197-2.507z"/></svg>
                    ВОЙТИ ЧЕРЕЗ VK
                    <span class="auth-social__soon">скоро</span>
                </button>
            </div>

        </div><!-- /.auth-page__main -->

    </div>
</div>

<?php get_footer(); ?>
