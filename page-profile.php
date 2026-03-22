<?php
/**
 * Template Name: Профиль
 *
 * Palime Archive — page-profile.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Если пользователь не авторизован — редирект на логин
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

$user_id = get_current_user_id();
$user    = wp_get_current_user();

// Ежедневный бонус
palime_check_daily_bonus( $user_id );

// Данные профиля
$points   = palime_get_points( $user_id );
$level    = palime_get_user_level( $user_id );
$progress = palime_get_level_progress( $user_id );
$streak   = palime_get_streak( $user_id );

// Достижения
$achievements     = palime_get_achievements();
$user_achievements = palime_get_user_achievements( $user_id );

// Лог очков
$log = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
$log = array_reverse( array_slice( $log, -20 ) );

// Сохранённые статьи
$saved_ids = get_user_meta( $user_id, 'palime_saved_articles', true ) ?: [];

get_header();
?>

<div class="profile-page">
    <div class="profile-page__container">

        <!-- ══════════════════════════════════════════
             САЙДБАР
             ══════════════════════════════════════════ -->
        <aside class="profile-sidebar">

            <!-- Аватар -->
            <div class="profile-avatar">
                <?php echo get_avatar( $user_id, 80 ); ?>
            </div>

            <!-- Имя и email -->
            <h1 class="profile-name"><?php echo esc_html( $user->display_name ); ?></h1>
            <p class="profile-email"><?php echo esc_html( $user->user_email ); ?></p>

            <!-- Уровень -->
            <div class="profile-level">
                <span class="profile-level__badge"><?php echo esc_html( $level['name'] ); ?></span>
                <div class="profile-level__points"><?php echo number_format( $points, 0, '', ' ' ); ?></div>
                <div class="profile-level__label">ОЧКОВ</div>

                <!-- Прогресс -->
                <div class="profile-progress">
                    <div class="profile-progress__bar">
                        <div class="profile-progress__fill" data-percent="<?php echo (int) $progress['percent']; ?>" style="width: <?php echo (int) $progress['percent']; ?>%;"></div>
                    </div>
                    <div class="profile-progress__label">
                        <?php if ( $progress['next_name'] ) : ?>
                            До <?php echo esc_html( $progress['next_name'] ); ?>: <?php echo (int) ( $progress['next_min'] - $progress['current'] ); ?> очков
                        <?php else : ?>
                            Максимальный уровень
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Серия дней -->
            <?php if ( $streak > 0 ) : ?>
            <div class="profile-streak">
                <span class="profile-streak__count"><?php echo (int) $streak; ?></span>
                <span class="profile-streak__label"><?php echo palime_plural( $streak, 'день', 'дня', 'дней' ); ?> подряд</span>
            </div>
            <?php endif; ?>

            <!-- Навигация -->
            <nav class="profile-nav">
                <div class="profile-nav__item active" data-tab="overview">Обзор</div>
                <div class="profile-nav__item" data-tab="achievements">Достижения</div>
                <div class="profile-nav__item" data-tab="history">История очков</div>
                <div class="profile-nav__item" data-tab="saved">Сохранённое</div>
                <div class="profile-nav__item" data-tab="settings">Настройки</div>
            </nav>

            <!-- Выход -->
            <a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="profile-logout" data-logout="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">
                ВЫЙТИ
            </a>

        </aside>

        <!-- ══════════════════════════════════════════
             КОНТЕНТ
             ══════════════════════════════════════════ -->
        <div class="profile-content">

            <!-- ── ОБЗОР ── -->
            <section class="profile-content__section active" data-section="overview">
                <h2 class="profile-content__title">ОБЗОР</h2>

                <!-- Статистика -->
                <div class="profile-stats">
                    <div class="profile-stats__card">
                        <div class="profile-stats__value"><?php echo number_format( $points, 0, '', ' ' ); ?></div>
                        <div class="profile-stats__label">Очков</div>
                    </div>
                    <div class="profile-stats__card">
                        <div class="profile-stats__value"><?php echo count( $user_achievements ); ?>/<?php echo count( $achievements ); ?></div>
                        <div class="profile-stats__label">Достижений</div>
                    </div>
                    <div class="profile-stats__card">
                        <div class="profile-stats__value"><?php echo count( $saved_ids ); ?></div>
                        <div class="profile-stats__label">Сохранено</div>
                    </div>
                    <div class="profile-stats__card">
                        <div class="profile-stats__value"><?php echo (int) $streak; ?></div>
                        <div class="profile-stats__label">Дней подряд</div>
                    </div>
                </div>

                <!-- Уровни -->
                <h3 class="profile-content__subtitle">СИСТЕМА УРОВНЕЙ</h3>
                <div class="profile-levels-map">
                    <?php
                    $all_levels  = palime_get_levels();
                    $current_lvl = 1;
                    foreach ( $all_levels as $num => $data ) {
                        if ( $points >= $data['min'] ) $current_lvl = $num;
                    }
                    foreach ( $all_levels as $num => $data ) :
                        $is_current  = ( $num === $current_lvl );
                        $is_unlocked = ( $num <= $current_lvl );
                    ?>
                        <div class="level-card <?php echo $is_current ? 'level-card--current' : ''; ?> <?php echo $is_unlocked ? 'level-card--unlocked' : 'level-card--locked'; ?>">
                            <div class="level-card__num"><?php echo $num; ?></div>
                            <div class="level-card__info">
                                <div class="level-card__name"><?php echo esc_html( $data['name'] ); ?></div>
                                <div class="level-card__perks"><?php echo esc_html( $data['perks'] ); ?></div>
                                <div class="level-card__min"><?php echo $data['min']; ?> очков</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Последние действия -->
                <?php if ( ! empty( $log ) ) : ?>
                <h3 class="profile-content__subtitle">ПОСЛЕДНИЕ ДЕЙСТВИЯ</h3>
                <div class="points-log">
                    <?php foreach ( array_slice( $log, 0, 5 ) as $entry ) : ?>
                        <div class="points-log__item">
                            <span><?php echo esc_html( $entry['reason'] ?: 'Действие' ); ?></span>
                            <span class="points-log__date"><?php echo esc_html( palime_format_date_short( $entry['date'] ) ); ?></span>
                            <span class="points-log__amount"><?php echo (int) $entry['amount']; ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <!-- ── ДОСТИЖЕНИЯ ── -->
            <section class="profile-content__section" data-section="achievements">
                <h2 class="profile-content__title">ДОСТИЖЕНИЯ</h2>

                <div class="achievements-grid">
                    <?php foreach ( $achievements as $key => $ach ) :
                        $unlocked = in_array( $key, $user_achievements, true );
                    ?>
                        <div class="achievement-card <?php echo $unlocked ? 'achievement-card--unlocked' : 'achievement-card--locked'; ?>">
                            <div class="achievement-card__icon"><?php echo $ach['icon']; ?></div>
                            <div class="achievement-card__info">
                                <div class="achievement-card__name"><?php echo esc_html( $ach['name'] ); ?></div>
                                <div class="achievement-card__desc"><?php echo esc_html( $ach['description'] ); ?></div>
                            </div>
                            <?php if ( $unlocked ) : ?>
                                <div class="achievement-card__status">+<?php echo (int) $ach['bonus']; ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ── ИСТОРИЯ ОЧКОВ ── -->
            <section class="profile-content__section" data-section="history">
                <h2 class="profile-content__title">ИСТОРИЯ ОЧКОВ</h2>

                <?php if ( ! empty( $log ) ) : ?>
                    <div class="points-log">
                        <?php foreach ( $log as $entry ) : ?>
                            <div class="points-log__item">
                                <span><?php echo esc_html( $entry['reason'] ?: 'Действие' ); ?></span>
                                <span class="points-log__date"><?php echo esc_html( palime_format_date_short( $entry['date'] ) ); ?></span>
                                <span class="points-log__amount"><?php echo (int) $entry['amount']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="profile-empty">Пока нет действий. Голосуйте, комментируйте и сохраняйте статьи, чтобы получать очки.</p>
                <?php endif; ?>
            </section>

            <!-- ── СОХРАНЁННОЕ ── -->
            <section class="profile-content__section" data-section="saved">
                <h2 class="profile-content__title">СОХРАНЁННОЕ</h2>

                <?php if ( ! empty( $saved_ids ) ) : ?>
                    <div class="saved-articles">
                        <?php
                        $saved_query = new WP_Query( [
                            'post_type'      => 'article',
                            'post__in'       => $saved_ids,
                            'posts_per_page' => 20,
                            'post_status'    => 'publish',
                            'orderby'        => 'post__in',
                        ] );
                        if ( $saved_query->have_posts() ) :
                            while ( $saved_query->have_posts() ) : $saved_query->the_post();
                        ?>
                            <div class="saved-article">
                                <a href="<?php the_permalink(); ?>" class="saved-article__title"><?php the_title(); ?></a>
                                <span class="saved-article__date"><?php echo palime_get_date(); ?></span>
                                <button class="saved-article__remove" data-article-id="<?php echo get_the_ID(); ?>">Убрать</button>
                            </div>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                <?php else : ?>
                    <p class="profile-empty">Нет сохранённых статей. Нажмите на закладку в любой статье.</p>
                <?php endif; ?>
            </section>

            <!-- ── НАСТРОЙКИ ── -->
            <section class="profile-content__section" data-section="settings">
                <h2 class="profile-content__title">НАСТРОЙКИ</h2>

                <form class="profile-settings" id="profile-settings-form">
                    <div class="profile-settings__field">
                        <label class="profile-settings__label">Отображаемое имя</label>
                        <input type="text" name="display_name" value="<?php echo esc_attr( $user->display_name ); ?>" class="profile-settings__input">
                    </div>

                    <div class="profile-settings__field">
                        <label class="profile-settings__label">Email</label>
                        <input type="email" name="email" value="<?php echo esc_attr( $user->user_email ); ?>" class="profile-settings__input" readonly>
                        <span class="profile-settings__hint">Для смены email обратитесь в поддержку</span>
                    </div>

                    <?php
                    $telegram_id = get_user_meta( $user_id, 'telegram_id', true );
                    ?>
                    <div class="profile-settings__field">
                        <label class="profile-settings__label">Telegram</label>
                        <?php if ( $telegram_id ) : ?>
                            <span class="profile-settings__connected">Подключён (ID: <?php echo esc_html( $telegram_id ); ?>)</span>
                        <?php else : ?>
                            <span class="profile-settings__hint">Не подключён. Войдите через Telegram для привязки.</span>
                        <?php endif; ?>
                    </div>

                    <button type="submit" class="profile-settings__save">СОХРАНИТЬ</button>
                </form>
            </section>

        </div><!-- /.profile-content -->

    </div><!-- /.profile-page__container -->
</div><!-- /.profile-page -->

<?php get_footer(); ?>
