<?php

// Palime Archive — inc/user-points.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// СИСТЕМА ОЧКОВ (XP) — ТРЁХУРОВНЕВАЯ
// =========================================================

// 🟢 Базовые действия (фарм, но ограниченный)
define( 'PALIME_XP_DAILY_LOGIN',    2 );   // Вход в день
define( 'PALIME_XP_READ_ARTICLE',   3 );   // Прочитал статью (скролл >70%)
define( 'PALIME_XP_VOTE',           1 );   // Лайк / голос
define( 'PALIME_XP_SAVE',           2 );   // Сохранение

// 🟡 Социальные действия
define( 'PALIME_XP_COMMENT',        5 );   // Комментарий
define( 'PALIME_XP_REPLY',          3 );   // Ответ на комментарий
define( 'PALIME_XP_SHARE',         10 );   // Репост (TG / VK) — 1 раз за статью
define( 'PALIME_XP_TELEGRAM',      15 );   // Подписка на TG — 1 раз вообще
define( 'PALIME_XP_LINK_ACCOUNT',  10 );   // Привязка аккаунта — 1 раз

// 🔴 Ценные действия
define( 'PALIME_XP_LONGREAD',      15 );   // Дочитал лонгрид (время >5 мин)
define( 'PALIME_XP_RETURN',         5 );   // Вернулся на сайт на следующий день
define( 'PALIME_XP_PURCHASE',      50 );   // Покупка

// Серии (streaks) — бонусы
define( 'PALIME_XP_STREAK_3',      20 );
define( 'PALIME_XP_STREAK_7',      50 );
define( 'PALIME_XP_STREAK_30',    150 );

// Дневной лимит базовых очков (чтобы не фармили)
define( 'PALIME_DAILY_BASE_CAP',   30 );

// Антиспам комментариев: минимальный интервал в секундах
define( 'PALIME_COMMENT_COOLDOWN', 300 ); // 5 минут


// =========================================================
// 8 УРОВНЕЙ — ЭКСПОНЕНЦИАЛЬНАЯ КРИВАЯ
// =========================================================

function palime_get_levels() {
    return [
        1 => [ 'name' => 'ЧИТАТЕЛЬ',      'min' => 0,    'perks' => 'Голосование в рейтингах' ],
        2 => [ 'name' => 'СВИДЕТЕЛЬ',      'min' => 50,   'perks' => 'Комментарии с приоритетом' ],
        3 => [ 'name' => 'АРХИВИСТ',       'min' => 120,  'perks' => 'Ранний доступ к дропам' ],
        4 => [ 'name' => 'КУРАТОР',        'min' => 250,  'perks' => 'Скидка на мерч + бейдж' ],
        5 => [ 'name' => 'ИНТЕРПРЕТАТОР',  'min' => 500,  'perks' => 'Эксклюзивные материалы' ],
        6 => [ 'name' => 'НОСИТЕЛЬ',       'min' => 900,  'perks' => 'Персональный профиль' ],
        7 => [ 'name' => 'АПОСТОЛ',        'min' => 1500, 'perks' => 'Закрытый клуб + голос куратора' ],
        8 => [ 'name' => 'АРХОНТ',         'min' => 2500, 'perks' => 'Высший ранг — влияние на контент' ],
    ];
}


// =========================================================
// НАЧИСЛЕНИЕ ОЧКОВ (с логом)
// =========================================================

function palime_add_points( $user_id, $amount, $reason = '', $category = 'base' ) {
    // Проверяем дневной лимит для базовых действий
    if ( $category === 'base' ) {
        $today_earned = palime_get_daily_points( $user_id );
        if ( $today_earned >= PALIME_DAILY_BASE_CAP ) {
            return palime_get_points( $user_id );
        }
        // Не даём превысить лимит
        $remaining = PALIME_DAILY_BASE_CAP - $today_earned;
        $amount    = min( $amount, $remaining );
        if ( $amount <= 0 ) {
            return palime_get_points( $user_id );
        }
    }

    $current = (int) get_user_meta( $user_id, 'palime_points', true );
    $new     = $current + $amount;
    update_user_meta( $user_id, 'palime_points', $new );

    $log   = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
    $log[] = [
        'amount'   => $amount,
        'reason'   => $reason,
        'category' => $category,
        'date'     => current_time( 'mysql' ),
    ];

    // Храним максимум 200 записей
    if ( count( $log ) > 200 ) {
        $log = array_slice( $log, -200 );
    }

    update_user_meta( $user_id, 'palime_points_log', $log );

    return $new;
}

/**
 * Подсчитать базовые очки за сегодня.
 */
function palime_get_daily_points( $user_id ) {
    $log   = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
    $today = current_time( 'Y-m-d' );
    $total = 0;

    foreach ( $log as $entry ) {
        if ( ( $entry['category'] ?? 'base' ) === 'base' && strpos( $entry['date'], $today ) === 0 ) {
            $total += (int) $entry['amount'];
        }
    }

    return $total;
}


// =========================================================
// ОПРЕДЕЛЕНИЕ УРОВНЯ И ПРОГРЕССА
// =========================================================

function palime_get_user_level( $user_id ) {
    $points = (int) get_user_meta( $user_id, 'palime_points', true );
    $levels = palime_get_levels();
    $level  = 1;
    foreach ( $levels as $num => $data ) {
        if ( $points >= $data['min'] ) $level = $num;
    }
    return array_merge( $levels[ $level ], [ 'number' => $level ] );
}

function palime_get_level_progress( $user_id ) {
    $points = (int) get_user_meta( $user_id, 'palime_points', true );
    $levels = palime_get_levels();
    $level  = 1;
    foreach ( $levels as $num => $data ) {
        if ( $points >= $data['min'] ) $level = $num;
    }
    if ( $level >= count( $levels ) ) {
        return [ 'percent' => 100, 'current' => $points, 'next_min' => null, 'next_name' => null ];
    }
    $current_min = $levels[ $level ]['min'];
    $next        = $levels[ $level + 1 ];
    $percent     = round( ( $points - $current_min ) / ( $next['min'] - $current_min ) * 100 );
    return [
        'percent'   => min( $percent, 100 ),
        'current'   => $points,
        'next_min'  => $next['min'],
        'next_name' => $next['name'],
    ];
}


// =========================================================
// ЕЖЕДНЕВНЫЙ БОНУС И СЕРИИ (STREAKS)
// =========================================================

function palime_check_daily_bonus( $user_id ) {
    $today      = current_time( 'Y-m-d' );
    $last_visit = get_user_meta( $user_id, 'palime_last_visit', true );

    if ( $last_visit === $today ) {
        return false;
    }

    $yesterday = wp_date( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) );
    $streak    = (int) get_user_meta( $user_id, 'palime_streak', true );

    if ( $last_visit === $yesterday ) {
        $streak++;
    } else {
        $streak = 1;
    }

    update_user_meta( $user_id, 'palime_last_visit', $today );
    update_user_meta( $user_id, 'palime_streak', $streak );

    // Базовый бонус за вход
    palime_add_points( $user_id, PALIME_XP_DAILY_LOGIN, 'Ежедневный вход', 'base' );

    // Бонус за возвращение (если серия > 1)
    if ( $streak > 1 ) {
        palime_add_points( $user_id, PALIME_XP_RETURN, 'Вернулся на следующий день (серия: ' . $streak . ')', 'valuable' );
    }

    // Бонусы за вехи серии
    if ( $streak === 3 ) {
        palime_add_points( $user_id, PALIME_XP_STREAK_3, 'Серия 3 дня', 'streak' );
    }
    if ( $streak === 7 ) {
        palime_add_points( $user_id, PALIME_XP_STREAK_7, 'Серия 7 дней', 'streak' );
    }
    if ( $streak === 30 ) {
        palime_add_points( $user_id, PALIME_XP_STREAK_30, 'Серия 30 дней', 'streak' );
    }

    palime_check_achievements( $user_id );

    return $streak;
}

function palime_get_streak( $user_id ) {
    $today      = current_time( 'Y-m-d' );
    $last_visit = get_user_meta( $user_id, 'palime_last_visit', true );
    $streak     = (int) get_user_meta( $user_id, 'palime_streak', true );

    $yesterday = wp_date( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) );
    if ( $last_visit !== $today && $last_visit !== $yesterday ) {
        return 0;
    }

    return $streak;
}


// =========================================================
// ХУКИ НАЧИСЛЕНИЯ ОЧКОВ
// =========================================================

// За комментарий — с антиспам-кулдауном
add_action( 'comment_post', function( $comment_id, $approved ) {
    if ( ! $approved || ! is_user_logged_in() ) return;

    $user_id = get_current_user_id();
    $comment = get_comment( $comment_id );

    // Антиспам: проверяем время последнего комментария
    $last_comment_time = get_user_meta( $user_id, 'palime_last_comment_time', true );
    $now = current_time( 'timestamp' );

    if ( $last_comment_time && ( $now - (int) $last_comment_time ) < PALIME_COMMENT_COOLDOWN ) {
        return; // Слишком рано, не начисляем
    }

    update_user_meta( $user_id, 'palime_last_comment_time', $now );

    // Ответ на комментарий vs обычный комментарий
    if ( $comment && $comment->comment_parent > 0 ) {
        palime_add_points( $user_id, PALIME_XP_REPLY, 'Ответ на комментарий', 'social' );
    } else {
        palime_add_points( $user_id, PALIME_XP_COMMENT, 'Комментарий', 'social' );
    }

    palime_check_achievements( $user_id );
}, 10, 2 );

// За покупку в магазине
add_action( 'woocommerce_order_status_completed', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;
    $user_id = $order->get_user_id();
    if ( $user_id ) {
        palime_add_points( $user_id, PALIME_XP_PURCHASE, 'Покупка в магазине', 'valuable' );
        palime_check_achievements( $user_id );
    }
} );


// =========================================================
// AJAX — ТРЕКИНГ ЧТЕНИЯ СТАТЬИ
// =========================================================

add_action( 'wp_ajax_palime_track_read', function() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $user_id    = get_current_user_id();
    $article_id = (int) ( $_POST['article_id'] ?? 0 );
    $scroll_pct = (int) ( $_POST['scroll_percent'] ?? 0 );
    $time_spent = (int) ( $_POST['time_spent'] ?? 0 ); // секунды

    if ( ! $user_id || ! $article_id ) {
        wp_send_json_error();
    }

    $read_key = 'palime_read_' . $article_id;

    // Уже засчитано?
    if ( get_user_meta( $user_id, $read_key, true ) ) {
        wp_send_json_success( [ 'already' => true ] );
        return;
    }

    $points_awarded = 0;

    // Базовое чтение: скролл > 70%
    if ( $scroll_pct >= 70 ) {
        update_user_meta( $user_id, $read_key, current_time( 'mysql' ) );
        palime_add_points( $user_id, PALIME_XP_READ_ARTICLE, 'Прочитал статью', 'base' );
        $points_awarded += PALIME_XP_READ_ARTICLE;

        // Лонгрид бонус: время > 5 минут
        if ( $time_spent >= 300 ) {
            palime_add_points( $user_id, PALIME_XP_LONGREAD, 'Дочитал лонгрид', 'valuable' );
            $points_awarded += PALIME_XP_LONGREAD;
        }

        // Подсчёт прочитанных статей для достижений
        palime_check_achievements( $user_id );
    }

    wp_send_json_success( [ 'points' => $points_awarded ] );
} );


// =========================================================
// СИСТЕМА ДОСТИЖЕНИЙ — ЭКСКЛЮЗИВНЫЕ
// =========================================================

function palime_get_achievements() {
    return [
        // ── Стартовые (легко получить)
        'first_vote' => [
            'name'        => 'Первый голос',
            'description' => 'Проголосуйте в любом рейтинге',
            'icon'        => '01',
            'bonus'       => 10,
            'rarity'      => 'common',
        ],
        'first_comment' => [
            'name'        => 'Первое слово',
            'description' => 'Оставьте первый комментарий',
            'icon'        => '02',
            'bonus'       => 10,
            'rarity'      => 'common',
        ],
        'first_save' => [
            'name'        => 'Коллекционер',
            'description' => 'Сохраните первую статью',
            'icon'        => '03',
            'bonus'       => 10,
            'rarity'      => 'common',
        ],
        'first_purchase' => [
            'name'        => 'Меценат',
            'description' => 'Совершите первую покупку',
            'icon'        => '04',
            'bonus'       => 30,
            'rarity'      => 'uncommon',
        ],

        // ── Серии
        'streak_3' => [
            'name'        => 'Три дня',
            'description' => 'Заходите 3 дня подряд',
            'icon'        => '05',
            'bonus'       => 15,
            'rarity'      => 'common',
        ],
        'streak_7' => [
            'name'        => 'Неделя',
            'description' => 'Заходите 7 дней подряд',
            'icon'        => '06',
            'bonus'       => 30,
            'rarity'      => 'uncommon',
        ],
        'streak_30' => [
            'name'        => 'Месяц',
            'description' => 'Заходите 30 дней подряд',
            'icon'        => '07',
            'bonus'       => 100,
            'rarity'      => 'rare',
        ],

        // ── Контент
        'read_10' => [
            'name'        => 'Свидетель',
            'description' => 'Прочитайте 10 статей',
            'icon'        => '08',
            'bonus'       => 20,
            'rarity'      => 'common',
        ],
        'read_50' => [
            'name'        => 'Наблюдатель',
            'description' => 'Прочитайте 50 статей без единого комментария',
            'icon'        => '09',
            'bonus'       => 60,
            'rarity'      => 'rare',
            'hidden'      => true,
        ],

        // ── Социальные
        'share_5' => [
            'name'        => 'Амбассадор',
            'description' => 'Поделитесь 5 статьями',
            'icon'        => '10',
            'bonus'       => 30,
            'rarity'      => 'uncommon',
        ],
        'comments_20' => [
            'name'        => 'Голос толпы',
            'description' => 'Оставьте 20 комментариев',
            'icon'        => '11',
            'bonus'       => 40,
            'rarity'      => 'uncommon',
        ],

        // ── Коллекционирование
        'save_20' => [
            'name'        => 'Куратор тьмы',
            'description' => 'Сохраните 20 материалов',
            'icon'        => '12',
            'bonus'       => 50,
            'rarity'      => 'rare',
        ],

        // ── Эксклюзивные Palime
        'first_blood' => [
            'name'        => 'Первый след',
            'description' => 'Ваш комментарий набрал 10 лайков',
            'icon'        => '13',
            'bonus'       => 50,
            'rarity'      => 'rare',
        ],
        'parasite' => [
            'name'        => 'Паразит культуры',
            'description' => 'Проведите на сайте больше 60 минут за один день',
            'icon'        => '14',
            'bonus'       => 40,
            'rarity'      => 'uncommon',
        ],
        'archaeologist' => [
            'name'        => 'Археолог',
            'description' => 'Прочитайте 10 статей старше 6 месяцев',
            'icon'        => '15',
            'bonus'       => 60,
            'rarity'      => 'rare',
        ],
        'shadow' => [
            'name'        => 'Тень',
            'description' => 'Заходили 7 дней подряд, но ничего не лайкали',
            'icon'        => '16',
            'bonus'       => 30,
            'rarity'      => 'rare',
            'hidden'      => true,
        ],
        'heretic' => [
            'name'        => 'Еретик',
            'description' => 'Ваш комментарий вызвал бурную дискуссию (10+ ответов)',
            'icon'        => '17',
            'bonus'       => 50,
            'rarity'      => 'epic',
            'hidden'      => true,
        ],
        'longread_master' => [
            'name'        => 'Глубоководный',
            'description' => 'Дочитайте 10 лонгридов (>5 мин каждый)',
            'icon'        => '18',
            'bonus'       => 80,
            'rarity'      => 'rare',
        ],

        // ── Уровневые вехи
        'level_4' => [
            'name'        => 'Куратор',
            'description' => 'Достигните уровня Куратор',
            'icon'        => '19',
            'bonus'       => 50,
            'rarity'      => 'uncommon',
        ],
        'level_6' => [
            'name'        => 'Носитель',
            'description' => 'Достигните уровня Носитель',
            'icon'        => '20',
            'bonus'       => 100,
            'rarity'      => 'rare',
        ],
        'level_8' => [
            'name'        => 'Архонт',
            'description' => 'Достигните высшего уровня',
            'icon'        => '21',
            'bonus'       => 200,
            'rarity'      => 'epic',
        ],
    ];
}

function palime_get_user_achievements( $user_id ) {
    return get_user_meta( $user_id, 'palime_achievements', true ) ?: [];
}

function palime_unlock_achievement( $user_id, $key ) {
    $unlocked = palime_get_user_achievements( $user_id );
    if ( in_array( $key, $unlocked, true ) ) {
        return false;
    }

    $achievements = palime_get_achievements();
    if ( ! isset( $achievements[ $key ] ) ) {
        return false;
    }

    $unlocked[] = $key;
    update_user_meta( $user_id, 'palime_achievements', $unlocked );

    palime_add_points( $user_id, $achievements[ $key ]['bonus'], 'Достижение: ' . $achievements[ $key ]['name'], 'achievement' );

    return true;
}

function palime_check_achievements( $user_id ) {
    $points    = palime_get_points( $user_id );
    $streak    = (int) get_user_meta( $user_id, 'palime_streak', true );
    $saved     = get_user_meta( $user_id, 'palime_saved_articles', true ) ?: [];
    $log       = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
    $unlocked  = palime_get_user_achievements( $user_id );
    $level     = palime_get_user_level( $user_id );

    // Подсчитываем действия из лога
    $vote_count     = 0;
    $comment_count  = 0;
    $share_count    = 0;
    $purchase_count = 0;
    $read_count     = 0;
    $longread_count = 0;

    foreach ( $log as $entry ) {
        $reason = $entry['reason'] ?? '';
        if ( strpos( $reason, 'Голосование' ) !== false )    $vote_count++;
        if ( strpos( $reason, 'Комментарий' ) !== false )    $comment_count++;
        if ( strpos( $reason, 'Ответ на комментарий' ) !== false ) $comment_count++;
        if ( strpos( $reason, 'Шеринг' ) !== false )         $share_count++;
        if ( strpos( $reason, 'Покупка' ) !== false )         $purchase_count++;
        if ( strpos( $reason, 'Прочитал статью' ) !== false ) $read_count++;
        if ( strpos( $reason, 'Дочитал лонгрид' ) !== false ) $longread_count++;
    }

    // Подсчитаем голоса за сегодня (для «Тень»)
    $today_votes = 0;
    $today = current_time( 'Y-m-d' );
    foreach ( $log as $entry ) {
        if ( strpos( $entry['date'], $today ) === 0 && strpos( $entry['reason'] ?? '', 'Голосование' ) !== false ) {
            $today_votes++;
        }
    }

    // Подсчитаем старые статьи для «Археолог»
    $old_read_count = palime_count_old_reads( $user_id );

    $checks = [
        // Стартовые
        'first_vote'     => $vote_count >= 1,
        'first_comment'  => $comment_count >= 1,
        'first_save'     => count( $saved ) >= 1,
        'first_purchase' => $purchase_count >= 1,

        // Серии
        'streak_3'       => $streak >= 3,
        'streak_7'       => $streak >= 7,
        'streak_30'      => $streak >= 30,

        // Контент
        'read_10'        => $read_count >= 10,
        'read_50'        => $read_count >= 50 && $comment_count === 0, // Скрытое: 50 прочитанных, 0 комментов

        // Социальные
        'share_5'        => $share_count >= 5,
        'comments_20'    => $comment_count >= 20,

        // Коллекционирование
        'save_20'        => count( $saved ) >= 20,

        // Эксклюзивные
        'archaeologist'  => $old_read_count >= 10,
        'longread_master' => $longread_count >= 10,

        // Тень: 7+ дней серии и 0 голосов за всю серию
        'shadow'         => $streak >= 7 && $vote_count === 0,

        // Уровневые
        'level_4'        => ( $level['number'] ?? 0 ) >= 4,
        'level_6'        => ( $level['number'] ?? 0 ) >= 6,
        'level_8'        => ( $level['number'] ?? 0 ) >= 8,
    ];

    // Примечание: first_blood, parasite, heretic проверяются отдельно
    // (через хуки или AJAX-трекинг)

    foreach ( $checks as $key => $met ) {
        if ( $met && ! in_array( $key, $unlocked, true ) ) {
            palime_unlock_achievement( $user_id, $key );
        }
    }
}

/**
 * Подсчитать прочитанные старые статьи (>6 месяцев).
 */
function palime_count_old_reads( $user_id ) {
    global $wpdb;

    $six_months_ago = wp_date( 'Y-m-d', strtotime( '-6 months', current_time( 'timestamp' ) ) );

    $count = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->usermeta} um
         INNER JOIN {$wpdb->posts} p ON um.meta_key = CONCAT('palime_read_', p.ID)
         WHERE um.user_id = %d
         AND p.post_date < %s
         AND p.post_status = 'publish'",
        $user_id,
        $six_months_ago
    ) );

    return (int) $count;
}

/**
 * AJAX — трекинг времени на сайте (для «Паразит культуры»).
 */
add_action( 'wp_ajax_palime_track_session', function() {
    check_ajax_referer( 'wp_rest', 'nonce' );

    $user_id  = get_current_user_id();
    $minutes  = (int) ( $_POST['minutes'] ?? 0 );

    if ( ! $user_id || $minutes < 1 ) {
        wp_send_json_error();
    }

    $today     = current_time( 'Y-m-d' );
    $total_key = 'palime_session_' . $today;
    $total     = (int) get_user_meta( $user_id, $total_key, true );
    $total    += $minutes;
    update_user_meta( $user_id, $total_key, $total );

    // Паразит культуры: >60 минут за день
    if ( $total >= 60 ) {
        palime_unlock_achievement( $user_id, 'parasite' );
    }

    wp_send_json_success( [ 'total_minutes' => $total ] );
} );


// =========================================================
// ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
// =========================================================

function palime_plural( $n, $one, $few, $many ) {
    $n = abs( $n ) % 100;
    $n1 = $n % 10;
    if ( $n > 10 && $n < 20 ) return $many;
    if ( $n1 > 1 && $n1 < 5 )  return $few;
    if ( $n1 === 1 )            return $one;
    return $many;
}

function palime_format_date_short( $mysql_date ) {
    if ( ! $mysql_date ) return '';
    $timestamp = strtotime( $mysql_date );
    return wp_date( 'j M', $timestamp );
}

/**
 * Получить информацию о рарности достижений.
 */
function palime_get_rarity_label( $rarity ) {
    $labels = [
        'common'   => 'Обычное',
        'uncommon' => 'Необычное',
        'rare'     => 'Редкое',
        'epic'     => 'Эпическое',
    ];
    return $labels[ $rarity ] ?? 'Обычное';
}
