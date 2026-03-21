<?php

// Palime Archive — inc/user-points.php

if ( ! defined( 'ABSPATH' ) ) exit;

// =========================================================
// СИСТЕМА ОЧКОВ И УРОВНЕЙ
// =========================================================

define( 'PALIME_POINTS_VOTE',     10 );
define( 'PALIME_POINTS_COMMENT',  15 );
define( 'PALIME_POINTS_SAVE',      5 );
define( 'PALIME_POINTS_PURCHASE', 50 );
define( 'PALIME_POINTS_TELEGRAM', 30 );
define( 'PALIME_POINTS_SHARE',    20 );

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
        palime_add_points( get_current_user_id(), PALIME_POINTS_COMMENT, 'Комментарий' );
    }
}, 10, 2 );

// За покупку в магазине
add_action( 'woocommerce_order_status_completed', function( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) return;
    $user_id = $order->get_user_id();
    if ( $user_id ) {
        palime_add_points( $user_id, PALIME_POINTS_PURCHASE, 'Покупка в магазине' );
    }
} );
