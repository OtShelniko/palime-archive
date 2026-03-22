<?php

// Palime Archive — inc/user-points.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// СИСТЕМА ОЧКОВ И УРОВНЕЙ
// =========================================================

define( 'PALIME_POINTS_VOTE',        10 );
define( 'PALIME_POINTS_COMMENT',     15 );
define( 'PALIME_POINTS_SAVE',         5 );
define( 'PALIME_POINTS_PURCHASE',    50 );
define( 'PALIME_POINTS_TELEGRAM',    30 );
define( 'PALIME_POINTS_SHARE',       20 );
define( 'PALIME_POINTS_DAILY_BONUS',  5 );
define( 'PALIME_POINTS_ACHIEVEMENT', 25 );

function palime_get_levels() {
    return [
        1 => [ 'name' => 'ЧИТАТЕЛЬ',  'min' => 0,   'perks' => 'Голосование в рейтингах' ],
        2 => [ 'name' => 'АРХИВИСТ',  'min' => 100, 'perks' => 'Ранний доступ к дропам' ],
        3 => [ 'name' => 'КУРАТОР',   'min' => 300, 'perks' => 'Скидка на мерч + бейдж' ],
        4 => [ 'name' => 'ХРАНИТЕЛЬ', 'min' => 700, 'perks' => 'Закрытый клуб' ],
    ];
}

function palime_add_points( $user_id, $amount, $reason = '' ) {
    $current = (int) get_user_meta( $user_id, 'palime_points', true );
    $new     = $current + $amount;
    update_user_meta( $user_id, 'palime_points', $new );

    $log   = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
    $log[] = [ 'amount' => $amount, 'reason' => $reason, 'date' => current_time( 'mysql' ) ];
    update_user_meta( $user_id, 'palime_points_log', $log );

    return $new;
}

function palime_get_user_level( $user_id ) {
    $points = (int) get_user_meta( $user_id, 'palime_points', true );
    $levels = palime_get_levels();
    $level  = 1;
    foreach ( $levels as $num => $data ) {
        if ( $points >= $data['min'] ) $level = $num;
    }
    return $levels[ $level ];
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

// За комментарий
add_action( 'comment_post', function( $comment_id, $approved ) {
    if ( $approved && is_user_logged_in() ) {
        $user_id = get_current_user_id();
        palime_add_points( $user_id, PALIME_POINTS_COMMENT, 'Комментарий' );
        palime_check_achievements( $user_id );
    }
}, 10, 2 );

// За покупку в магазине
add_action( 'woocommerce_order_status_completed', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;
    $user_id = $order->get_user_id();
    if ( $user_id ) {
        palime_add_points( $user_id, PALIME_POINTS_PURCHASE, 'Покупка в магазине' );
        palime_check_achievements( $user_id );
    }
} );

// =========================================================
// ЕЖЕДНЕВНЫЙ БОНУС И СЕРИИ (STREAKS)
// =========================================================

function palime_check_daily_bonus( $user_id ) {
    $today     = current_time( 'Y-m-d' );
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

    // Бонус растёт с серией: 5 + 1 за каждый день (макс +10)
    $bonus = PALIME_POINTS_DAILY_BONUS + min( $streak - 1, 10 );
    palime_add_points( $user_id, $bonus, 'Ежедневный бонус (серия: ' . $streak . ')' );

    palime_check_achievements( $user_id );

    return $bonus;
}

function palime_get_streak( $user_id ) {
    $today      = current_time( 'Y-m-d' );
    $last_visit = get_user_meta( $user_id, 'palime_last_visit', true );
    $streak     = (int) get_user_meta( $user_id, 'palime_streak', true );

    // Если пользователь пропустил вчера — серия сброшена
    $yesterday = wp_date( 'Y-m-d', strtotime( '-1 day', current_time( 'timestamp' ) ) );
    if ( $last_visit !== $today && $last_visit !== $yesterday ) {
        return 0;
    }

    return $streak;
}

// =========================================================
// СИСТЕМА ДОСТИЖЕНИЙ
// =========================================================

function palime_get_achievements() {
    return [
        'first_vote' => [
            'name'        => 'Первый голос',
            'description' => 'Проголосуйте в любом рейтинге',
            'icon'        => '01',
            'bonus'       => 25,
        ],
        'first_comment' => [
            'name'        => 'Первое слово',
            'description' => 'Оставьте первый комментарий',
            'icon'        => '02',
            'bonus'       => 25,
        ],
        'first_save' => [
            'name'        => 'Коллекционер',
            'description' => 'Сохраните первую статью',
            'icon'        => '03',
            'bonus'       => 25,
        ],
        'points_100' => [
            'name'        => 'Сотня',
            'description' => 'Наберите 100 очков',
            'icon'        => '04',
            'bonus'       => 25,
        ],
        'points_300' => [
            'name'        => 'Триста',
            'description' => 'Наберите 300 очков',
            'icon'        => '05',
            'bonus'       => 50,
        ],
        'points_700' => [
            'name'        => 'Семьсот',
            'description' => 'Наберите 700 очков',
            'icon'        => '06',
            'bonus'       => 100,
        ],
        'streak_3' => [
            'name'        => 'Три дня подряд',
            'description' => 'Заходите 3 дня подряд',
            'icon'        => '07',
            'bonus'       => 15,
        ],
        'streak_7' => [
            'name'        => 'Неделя',
            'description' => 'Заходите 7 дней подряд',
            'icon'        => '08',
            'bonus'       => 30,
        ],
        'streak_30' => [
            'name'        => 'Месяц',
            'description' => 'Заходите 30 дней подряд',
            'icon'        => '09',
            'bonus'       => 100,
        ],
        'save_10' => [
            'name'        => 'Библиотекарь',
            'description' => 'Сохраните 10 статей',
            'icon'        => '10',
            'bonus'       => 40,
        ],
        'share_5' => [
            'name'        => 'Амбассадор',
            'description' => 'Поделитесь 5 статьями',
            'icon'        => '11',
            'bonus'       => 30,
        ],
        'first_purchase' => [
            'name'        => 'Меценат',
            'description' => 'Совершите первую покупку',
            'icon'        => '12',
            'bonus'       => 50,
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

    palime_add_points( $user_id, $achievements[ $key ]['bonus'], 'Достижение: ' . $achievements[ $key ]['name'] );

    return true;
}

function palime_check_achievements( $user_id ) {
    $points    = palime_get_points( $user_id );
    $streak    = (int) get_user_meta( $user_id, 'palime_streak', true );
    $saved     = get_user_meta( $user_id, 'palime_saved_articles', true ) ?: [];
    $log       = get_user_meta( $user_id, 'palime_points_log', true ) ?: [];
    $unlocked  = palime_get_user_achievements( $user_id );

    // Подсчитываем действия из лога
    $vote_count    = 0;
    $comment_count = 0;
    $share_count   = 0;
    $purchase_count = 0;
    foreach ( $log as $entry ) {
        $reason = $entry['reason'] ?? '';
        if ( strpos( $reason, 'Голосование' ) !== false )      $vote_count++;
        if ( strpos( $reason, 'Комментарий' ) !== false )      $comment_count++;
        if ( strpos( $reason, 'Шеринг' ) !== false )           $share_count++;
        if ( strpos( $reason, 'Покупка' ) !== false )           $purchase_count++;
    }

    $checks = [
        'first_vote'     => $vote_count >= 1,
        'first_comment'  => $comment_count >= 1,
        'first_save'     => count( $saved ) >= 1,
        'points_100'     => $points >= 100,
        'points_300'     => $points >= 300,
        'points_700'     => $points >= 700,
        'streak_3'       => $streak >= 3,
        'streak_7'       => $streak >= 7,
        'streak_30'      => $streak >= 30,
        'save_10'        => count( $saved ) >= 10,
        'share_5'        => $share_count >= 5,
        'first_purchase' => $purchase_count >= 1,
    ];

    foreach ( $checks as $key => $met ) {
        if ( $met && ! in_array( $key, $unlocked, true ) ) {
            palime_unlock_achievement( $user_id, $key );
        }
    }
}

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
