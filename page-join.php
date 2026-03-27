<?php
/**
 * Template Name: Присоединиться к архиву
 * Template Post Type: page
 *
 * Palime Archive — page-join.php
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) exit;

wp_enqueue_style(
    'palime-page-join',
    get_template_directory_uri() . '/assets/css/pages/join.css',
    [ 'palime-utilities' ],
    wp_get_theme()->get( 'Version' )
);

$levels       = palime_get_levels();
$achievements = palime_get_achievements();

get_header();
?>

<!-- ============================================================
     1. HERO
     ============================================================ -->
<section class="pa-join-hero">
    <div class="container">
        <p class="pa-join-hero__label">ACCESS PROTOCOL</p>
        <h1 class="pa-join-hero__title">Присоединиться<br>к&nbsp;архиву</h1>
        <p class="pa-join-hero__sub">Palime открывается через участие: чтение, возвращение, достижения, роли и&nbsp;доступ к&nbsp;новым уровням системы. Для тех, кто хочет войти сразу, существует отдельный путь поддержки.</p>
    </div>
</section>


<!-- ============================================================
     2. КАК ЭТО РАБОТАЕТ
     ============================================================ -->
<section class="pa-join-section">
    <div class="container">
        <div class="pa-join-how">
            <div class="pa-join-how__text">
                <p class="pa-join-how__label">SYSTEM OVERVIEW</p>
                <h2 class="pa-join-how__title">Как это работает</h2>
                <p class="pa-join-how__desc">Palime&nbsp;&mdash; это живая система участия. Каждое действие внутри архива начисляет XP: чтение, комментарии, возвращения, сохранения. По мере накопления XP открываются новые роли, возможности и&nbsp;доступ к&nbsp;закрытым материалам.</p>
                <p class="pa-join-how__desc">Это не&nbsp;подписка и&nbsp;не&nbsp;покупка. Это постепенное движение внутрь системы через реальное участие.</p>
            </div>
            <div class="pa-join-how__steps">
                <div class="pa-join-how__step">
                    <span class="pa-join-how__step-num">01</span>
                    <p class="pa-join-how__step-text">Действуйте&nbsp;&mdash; читайте, комментируйте, сохраняйте</p>
                </div>
                <div class="pa-join-how__step">
                    <span class="pa-join-how__step-num">02</span>
                    <p class="pa-join-how__step-text">Получайте XP за каждое осмысленное действие</p>
                </div>
                <div class="pa-join-how__step">
                    <span class="pa-join-how__step-num">03</span>
                    <p class="pa-join-how__step-text">Открывайте роли, достижения и&nbsp;закрытые материалы</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     3. ДВА ПУТИ ВХОДА
     ============================================================ -->
<section class="pa-join-section pa-join-section--dark">
    <div class="container">
        <h2 class="pa-join-block__title">Два пути входа</h2>
        <p class="pa-join-block__sub">Войти в&nbsp;систему Palime можно через участие или через поддержку проекта.</p>

        <div class="pa-join-paths">
            <div class="pa-join-path pa-join-path--earn">
                <span class="pa-join-path__tag">PARTICIPATION</span>
                <h3 class="pa-join-path__title">Через участие</h3>
                <p class="pa-join-path__desc">Вы получаете XP за чтение, комментарии, сохранения и&nbsp;другую активность. Роли, достижения и&nbsp;доступ к&nbsp;материалам открываются постепенно, через реальное присутствие в&nbsp;архиве.</p>
                <ul class="pa-join-path__list">
                    <li>XP за действия</li>
                    <li>8 уровней прогресса</li>
                    <li>Достижения и&nbsp;награды</li>
                    <li>Доступ к&nbsp;закрытым материалам</li>
                </ul>
            </div>
            <div class="pa-join-path pa-join-path--patron">
                <span class="pa-join-path__tag">SUPPORT</span>
                <h3 class="pa-join-path__title">Через поддержку</h3>
                <p class="pa-join-path__desc">Для тех, кто хочет получить доступ сразу или поддержать развитие независимого культурного архива. Отдельная роль&nbsp;&mdash; не&nbsp;замена earned-статусу, а&nbsp;параллельный путь.</p>
                <ul class="pa-join-path__list">
                    <li>Прямой доступ</li>
                    <li>Поддержка проекта</li>
                    <li>Привилегии и&nbsp;ранний доступ</li>
                    <li>Отдельный статус &laquo;Патрон архива&raquo;</li>
                </ul>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     4. XP ЗА ДЕЙСТВИЯ
     ============================================================ -->
<section class="pa-join-section">
    <div class="container">
        <h2 class="pa-join-block__title">XP за действия</h2>
        <p class="pa-join-block__sub">Каждое действие внутри архива приносит опыт. Базовые действия ограничены дневным лимитом <?php echo PALIME_DAILY_BASE_CAP; ?>&nbsp;XP, чтобы система оставалась честной.</p>

        <div class="pa-join-xp">
            <div class="pa-join-xp__group">
                <p class="pa-join-xp__group-label">Базовые</p>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Вход</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_DAILY_LOGIN; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Чтение статьи</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_READ_ARTICLE; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Лайк / голос</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_VOTE; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Сохранение</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_SAVE; ?></span></div>
            </div>
            <div class="pa-join-xp__group">
                <p class="pa-join-xp__group-label">Социальные</p>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Комментарий</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_COMMENT; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Ответ на комментарий</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_REPLY; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Репост</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_SHARE; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Подписка на Telegram</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_TELEGRAM; ?></span></div>
            </div>
            <div class="pa-join-xp__group">
                <p class="pa-join-xp__group-label">Ценные</p>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Лонгрид (>2 мин)</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_LONGREAD; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Возвращение</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_RETURN; ?></span></div>
                <div class="pa-join-xp__row"><span class="pa-join-xp__action">Покупка</span><span class="pa-join-xp__val">+<?php echo PALIME_XP_PURCHASE; ?></span></div>
            </div>
        </div>

        <div class="pa-join-xp__limits">
            <p class="pa-join-xp__limit">1 вход в день</p>
            <p class="pa-join-xp__limit">1 начисление за статью</p>
            <p class="pa-join-xp__limit">Дневной лимит &asymp; <?php echo PALIME_DAILY_BASE_CAP; ?> XP</p>
        </div>
    </div>
</section>


<!-- ============================================================
     5. УРОВНИ АРХИВА
     ============================================================ -->
<section class="pa-join-section pa-join-section--dark">
    <div class="container">
        <h2 class="pa-join-block__title">Уровни архива</h2>
        <p class="pa-join-block__sub">8 уровней прогресса. Каждый открывает новые возможности внутри системы.</p>

        <div class="pa-join-levels">
            <?php foreach ( $levels as $num => $lvl ) :
                $next_min = isset( $levels[ $num + 1 ] ) ? $levels[ $num + 1 ]['min'] : null;
            ?>
                <div class="pa-join-level">
                    <div class="pa-join-level__head">
                        <span class="pa-join-level__num"><?php echo str_pad( $num, 2, '0', STR_PAD_LEFT ); ?></span>
                        <h3 class="pa-join-level__name"><?php echo esc_html( $lvl['name'] ); ?></h3>
                    </div>
                    <p class="pa-join-level__perk"><?php echo esc_html( $lvl['perks'] ); ?></p>
                    <div class="pa-join-level__threshold">
                        <span><?php echo $num === 1 ? 'Старт' : number_format_i18n( $lvl['min'] ) . ' XP'; ?></span>
                        <?php if ( $next_min ) : ?>
                            <span class="pa-join-level__arrow">→</span>
                            <span><?php echo number_format_i18n( $next_min ); ?> XP</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<!-- ============================================================
     6. ДОСТИЖЕНИЯ
     ============================================================ -->
<section class="pa-join-section">
    <div class="container">
        <h2 class="pa-join-block__title">Достижения</h2>
        <p class="pa-join-block__sub">Уникальные награды за действия, серии, коллекционирование и&nbsp;особые условия. Часть достижений скрыта&nbsp;&mdash; их нужно открыть самостоятельно.</p>

        <div class="pa-join-achieve">
            <?php
            $showcase = [ 'first_comment', 'read_10', 'streak_7', 'save_10', 'first_gated_article', 'level_3', 'archaeologist', 'longread_master' ];
            foreach ( $showcase as $key ) :
                if ( ! isset( $achievements[ $key ] ) ) continue;
                $a = $achievements[ $key ];
            ?>
                <div class="pa-join-achieve__card pa-join-achieve__card--<?php echo esc_attr( $a['rarity'] ); ?>">
                    <span class="pa-join-achieve__icon"><?php echo esc_html( $a['icon'] ); ?></span>
                    <div class="pa-join-achieve__body">
                        <h4 class="pa-join-achieve__name"><?php echo esc_html( $a['name'] ); ?></h4>
                        <p class="pa-join-achieve__desc"><?php echo esc_html( $a['description'] ); ?></p>
                    </div>
                    <span class="pa-join-achieve__bonus">+<?php echo esc_html( $a['bonus'] ); ?> XP</span>
                </div>
            <?php endforeach; ?>

            <div class="pa-join-achieve__card pa-join-achieve__card--hidden">
                <span class="pa-join-achieve__icon">?</span>
                <div class="pa-join-achieve__body">
                    <h4 class="pa-join-achieve__name">Скрытое достижение</h4>
                    <p class="pa-join-achieve__desc">Некоторые награды можно получить только непредсказуемым путём</p>
                </div>
                <span class="pa-join-achieve__bonus">? XP</span>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     7. ЧТО ОТКРЫВАЕТСЯ
     ============================================================ -->
<section class="pa-join-section pa-join-section--dark">
    <div class="container">
        <h2 class="pa-join-block__title">Что открывается</h2>
        <p class="pa-join-block__sub">Чем выше уровень&nbsp;&mdash; тем шире доступ. Часть контента требует определённого уровня; при его отсутствии вместо материала показывается заглушка.</p>

        <div class="pa-join-rewards">
            <div class="pa-join-reward">
                <span class="pa-join-reward__icon">◆</span>
                <h4 class="pa-join-reward__title">Закрытые материалы</h4>
                <p class="pa-join-reward__desc">Эксклюзивные тексты, доступные по required_level</p>
            </div>
            <div class="pa-join-reward">
                <span class="pa-join-reward__icon">◆</span>
                <h4 class="pa-join-reward__title">Кастом профиля</h4>
                <p class="pa-join-reward__desc">Рамки, фоны и визуальная настройка</p>
            </div>
            <div class="pa-join-reward">
                <span class="pa-join-reward__icon">◆</span>
                <h4 class="pa-join-reward__title">Ранний доступ</h4>
                <p class="pa-join-reward__desc">Новые материалы до публичного запуска</p>
            </div>
            <div class="pa-join-reward">
                <span class="pa-join-reward__icon">◆</span>
                <h4 class="pa-join-reward__title">Бейджи и&nbsp;статус</h4>
                <p class="pa-join-reward__desc">Визуальные маркеры уровня и&nbsp;достижений</p>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     8. ПОДДЕРЖКА АРХИВА
     ============================================================ -->
<section class="pa-join-section">
    <div class="container">
        <div class="pa-join-patron">
            <div class="pa-join-patron__text">
                <p class="pa-join-patron__label">SUPPORT PATH</p>
                <h2 class="pa-join-patron__title">Патрон архива</h2>
                <p class="pa-join-patron__desc">Для тех, кто хочет получить доступ к&nbsp;закрытым материалам сразу или поддержать развитие независимого культурного проекта, существует отдельная роль&nbsp;&mdash; Патрон архива.</p>
                <p class="pa-join-patron__desc">Патрон получает привилегии и&nbsp;прямой доступ, но&nbsp;это не&nbsp;замена earned-статусу. Достижения, роли прогресса и&nbsp;внутренний престиж остаются отдельной системой, которую нельзя купить.</p>
                <a href="<?php echo esc_url( home_url( '/support/' ) ); ?>" class="pa-join-patron__btn">Поддержать Palime →</a>
            </div>
            <div class="pa-join-patron__perks">
                <div class="pa-join-patron__perk">
                    <span class="pa-join-patron__perk-mark">→</span>
                    <span>Прямой доступ ко&nbsp;всем закрытым материалам</span>
                </div>
                <div class="pa-join-patron__perk">
                    <span class="pa-join-patron__perk-mark">→</span>
                    <span>Ранний доступ к&nbsp;новым публикациям</span>
                </div>
                <div class="pa-join-patron__perk">
                    <span class="pa-join-patron__perk-mark">→</span>
                    <span>Отдельный визуальный статус</span>
                </div>
                <div class="pa-join-patron__perk">
                    <span class="pa-join-patron__perk-mark">→</span>
                    <span>Участие в&nbsp;развитии проекта</span>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ============================================================
     9. ЧТО ДАЛЬШЕ
     ============================================================ -->
<section class="pa-join-section pa-join-section--dark">
    <div class="container">
        <h2 class="pa-join-block__title">Что дальше</h2>
        <p class="pa-join-block__sub">Система участия развивается вместе с&nbsp;проектом. Впереди:</p>

        <div class="pa-join-roadmap">
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Прогресс-бар до следующего уровня</div>
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Уведомления о&nbsp;начислении XP</div>
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Новые достижения и&nbsp;скрытые награды</div>
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Лидерборд участников</div>
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Кураторские права и&nbsp;влияние на&nbsp;контент</div>
            <div class="pa-join-roadmap__item"><span class="pa-join-roadmap__dot"></span>Персональные рекомендации</div>
        </div>
    </div>
</section>


<!-- ============================================================
     10. ФИНАЛЬНЫЙ CTA
     ============================================================ -->
<section class="pa-join-section pa-join-cta">
    <div class="container">
        <h2 class="pa-join-cta__title">Начать путь в&nbsp;архиве</h2>
        <p class="pa-join-cta__sub">Зарегистрируйтесь, чтобы начать получать XP, открывать роли и&nbsp;двигаться внутрь системы Palime.</p>
        <div class="pa-join-cta__actions">
            <a href="<?php echo esc_url( home_url( '/auth/' ) ); ?>" class="pa-join-cta__btn pa-join-cta__btn--primary">Создать аккаунт</a>
            <a href="<?php echo esc_url( home_url( '/support/' ) ); ?>" class="pa-join-cta__btn pa-join-cta__btn--secondary">Поддержать Palime</a>
        </div>
    </div>
</section>

<?php get_footer(); ?>
