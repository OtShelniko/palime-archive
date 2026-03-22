<?php

// Palime Archive — inc/rewards.php
// Награды, бейджи, привилегии по уровням, гейтинг контента

if ( ! defined( 'ABSPATH' ) ) exit;


// =========================================================
// 1. СКРЫТЫЕ МАТЕРИАЛЫ ПО УРОВНЮ
// =========================================================

/**
 * Получить требуемый уровень для поста.
 * Использует post_meta 'required_level'. Если не задан — 0 (доступен всем).
 */
function palime_get_required_level( $post_id ) {
    $level = get_post_meta( $post_id, 'required_level', true );
    return $level ? (int) $level : 0;
}

/**
 * Получить номер уровня текущего пользователя.
 * Неавторизованные = уровень 0.
 */
function palime_get_current_user_level_number() {
    if ( ! is_user_logged_in() ) {
        return 0;
    }
    $level = palime_get_user_level( get_current_user_id() );
    return (int) ( $level['number'] ?? 1 );
}

/**
 * Проверить, имеет ли текущий пользователь доступ к посту.
 */
function palime_user_can_access_post( $post_id ) {
    $required = palime_get_required_level( $post_id );
    if ( $required <= 0 ) {
        return true;
    }
    // Администраторы видят всё
    if ( current_user_can( 'manage_options' ) ) {
        return true;
    }
    return palime_get_current_user_level_number() >= $required;
}

/**
 * Получить название уровня по номеру.
 */
function palime_get_level_name_by_number( $number ) {
    $levels = palime_get_levels();
    return isset( $levels[ $number ] ) ? $levels[ $number ]['name'] : '';
}

/**
 * Фильтр the_content — заменяет контент заглушкой если уровень недостаточный.
 */
add_filter( 'the_content', 'palime_filter_gated_content', 20 );

function palime_filter_gated_content( $content ) {
    if ( is_admin() || ! is_singular() ) {
        return $content;
    }

    $post_id  = get_the_ID();
    $required = palime_get_required_level( $post_id );

    if ( $required <= 0 ) {
        return $content;
    }

    if ( palime_user_can_access_post( $post_id ) ) {
        return $content;
    }

    $level_name = palime_get_level_name_by_number( $required );
    $user_level = palime_get_current_user_level_number();

    ob_start();
    ?>
    <div class="gated-content">
        <div class="gated-content__icon"></div>
        <div class="gated-content__title">Материал закрыт</div>
        <div class="gated-content__message">
            Этот материал откроется на уровне <strong><?php echo esc_html( $level_name ); ?></strong>.
            <?php if ( $user_level > 0 ) : ?>
                Продолжайте читать, сохранять и участвовать в жизни архива.
            <?php else : ?>
                <a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>">Войдите</a>, чтобы начать набирать XP.
            <?php endif; ?>
        </div>
        <div class="gated-content__progress">
            <?php if ( $user_level > 0 ) : ?>
                Ваш уровень: <?php echo esc_html( palime_get_level_name_by_number( $user_level ) ); ?> (<?php echo $user_level; ?>)
                &rarr; Нужен: <?php echo esc_html( $level_name ); ?> (<?php echo $required; ?>)
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Трекинг открытия закрытого материала (для достижения «За закрытой дверью»).
 */
add_action( 'template_redirect', function() {
    if ( ! is_singular( [ 'article', 'news' ] ) || ! is_user_logged_in() ) return;

    $post_id  = get_queried_object_id();
    $required = palime_get_required_level( $post_id );

    if ( $required <= 0 ) return;
    if ( ! palime_user_can_access_post( $post_id ) ) return;

    $user_id = get_current_user_id();
    if ( ! get_user_meta( $user_id, 'palime_opened_gated', true ) ) {
        update_user_meta( $user_id, 'palime_opened_gated', current_time( 'mysql' ) );
        palime_check_achievements( $user_id );
    }
} );

/**
 * Скрываем комментарии на закрытых материалах.
 */
add_filter( 'comments_open', function( $open, $post_id ) {
    if ( $open && ! palime_user_can_access_post( $post_id ) ) {
        return false;
    }
    return $open;
}, 10, 2 );


// =========================================================
// 2. БЕЙДЖИ ПО УРОВНЮ
// =========================================================

/**
 * Получить данные бейджа по номеру уровня.
 */
function palime_get_level_badge( $level_number ) {
    $badges = [
        1 => [ 'label' => 'ЧИТАТЕЛЬ',      'class' => 'badge--reader' ],
        2 => [ 'label' => 'СВИДЕТЕЛЬ',      'class' => 'badge--witness' ],
        3 => [ 'label' => 'АРХИВИСТ',       'class' => 'badge--archivist' ],
        4 => [ 'label' => 'КУРАТОР',        'class' => 'badge--curator' ],
        5 => [ 'label' => 'ИНТЕРПРЕТАТОР',  'class' => 'badge--interpreter' ],
        6 => [ 'label' => 'НОСИТЕЛЬ',       'class' => 'badge--bearer' ],
        7 => [ 'label' => 'АПОСТОЛ',        'class' => 'badge--apostle' ],
        8 => [ 'label' => 'АРХОНТ',         'class' => 'badge--archon' ],
    ];

    $num = max( 1, min( 8, (int) $level_number ) );
    return $badges[ $num ];
}

/**
 * Вывести HTML бейджа для пользователя.
 */
function palime_render_badge( $user_id, $context = 'inline' ) {
    $level = palime_get_user_level( $user_id );
    $num   = (int) ( $level['number'] ?? 1 );
    $badge = palime_get_level_badge( $num );

    $class = 'palime-badge palime-badge--' . $context . ' ' . $badge['class'] . ' palime-badge--level-' . $num;

    return '<span class="' . esc_attr( $class ) . '">' . esc_html( $badge['label'] ) . '</span>';
}

/**
 * Добавить бейдж к комментариям через wp_list_comments callback.
 */
add_filter( 'get_comment_author', 'palime_comment_author_badge', 10, 3 );

function palime_comment_author_badge( $author, $comment_id, $comment ) {
    if ( ! $comment || is_admin() ) {
        return $author;
    }

    $user_id = (int) $comment->user_id;
    if ( ! $user_id ) {
        return $author;
    }

    $level = palime_get_user_level( $user_id );
    $num   = (int) ( $level['number'] ?? 1 );
    $badge = palime_get_level_badge( $num );

    // Стиль ника по уровню
    $name_class = 'palime-username palime-username--level-' . $num;
    $styled_name = '<span class="' . esc_attr( $name_class ) . '">' . esc_html( $author ) . '</span>';

    // Бейдж рядом с именем
    $badge_html = ' <span class="palime-badge palime-badge--comment ' . esc_attr( $badge['class'] ) . ' palime-badge--level-' . $num . '">' . esc_html( $badge['label'] ) . '</span>';

    return $styled_name . $badge_html;
}


// =========================================================
// 3. ПРИВИЛЕГИИ ПО УРОВНЯМ (описания)
// =========================================================

/**
 * Получить расширенные описания привилегий для каждого уровня.
 */
function palime_get_level_privileges() {
    return [
        1 => [
            'name'       => 'Читатель',
            'privileges' => [ 'Голосование в рейтингах', 'Базовый профиль' ],
        ],
        2 => [
            'name'       => 'Свидетель',
            'privileges' => [ 'Комментарии с приоритетом', 'Бейдж «Свидетель»' ],
        ],
        3 => [
            'name'       => 'Архивист',
            'privileges' => [ 'Доступ к скрытым материалам (уровень 3)', 'Ранний доступ к дропам', 'Рамка профиля' ],
        ],
        4 => [
            'name'       => 'Куратор',
            'privileges' => [ 'Скидка на мерч', 'Расширенный бейдж', 'Акцентный стиль ника' ],
        ],
        5 => [
            'name'       => 'Интерпретатор',
            'privileges' => [ 'Эксклюзивные материалы', 'Кастомный стиль профиля', 'Премиальная рамка' ],
        ],
        6 => [
            'name'       => 'Носитель',
            'privileges' => [ 'Персональный профиль', 'Ранний доступ к публикациям', 'Статусный стиль ника' ],
        ],
        7 => [
            'name'       => 'Апостол',
            'privileges' => [ 'Закрытый клуб', 'Голос куратора', 'Премиум-бейдж' ],
        ],
        8 => [
            'name'       => 'Архонт',
            'privileges' => [ 'Высший ранг', 'Влияние на контент', 'Уникальный стиль профиля' ],
        ],
    ];
}

/**
 * Получить привилегии текущего уровня и следующего.
 */
function palime_get_user_privileges_info( $user_id ) {
    $level      = palime_get_user_level( $user_id );
    $num        = (int) ( $level['number'] ?? 1 );
    $all_privs  = palime_get_level_privileges();

    $current = $all_privs[ $num ] ?? [ 'name' => '', 'privileges' => [] ];
    $next    = null;

    if ( $num < 8 && isset( $all_privs[ $num + 1 ] ) ) {
        $next = $all_privs[ $num + 1 ];
    }

    return [
        'current_level' => $num,
        'current'       => $current,
        'next'          => $next,
    ];
}


// =========================================================
// 4. РЕГИСТРАЦИЯ ПОЛЯ required_level (ЧЕРЕЗ ACF ИЛИ META BOX)
// =========================================================

/**
 * Регистрируем ACF-поле required_level если ACF доступен.
 */
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_required_level',
        'title'    => 'Доступ по уровню',
        'fields'   => [
            [
                'key'           => 'field_required_level',
                'label'         => 'Минимальный уровень',
                'name'          => 'required_level',
                'type'          => 'select',
                'instructions'  => 'Если задан — материал будет скрыт для пользователей ниже этого уровня',
                'choices'       => [
                    ''  => 'Без ограничения (доступно всем)',
                    '2' => '2 — Свидетель',
                    '3' => '3 — Архивист',
                    '4' => '4 — Куратор',
                    '5' => '5 — Интерпретатор',
                    '6' => '6 — Носитель',
                    '7' => '7 — Апостол',
                    '8' => '8 — Архонт',
                ],
                'default_value' => '',
                'allow_null'    => 1,
            ],
        ],
        'location' => [
            [
                [ 'param' => 'post_type', 'operator' => '==', 'value' => 'article' ],
            ],
            [
                [ 'param' => 'post_type', 'operator' => '==', 'value' => 'news' ],
            ],
        ],
        'position'  => 'side',
        'menu_order' => 5,
    ] );
} );

/**
 * Фоллбэк: если ACF не установлен — добавляем простой meta box.
 */
add_action( 'add_meta_boxes', function() {
    if ( function_exists( 'acf_add_local_field_group' ) ) return;

    add_meta_box(
        'palime_required_level',
        'Доступ по уровню',
        'palime_required_level_meta_box',
        [ 'article', 'news' ],
        'side',
        'default'
    );
} );

function palime_required_level_meta_box( $post ) {
    $value = get_post_meta( $post->ID, 'required_level', true );
    wp_nonce_field( 'palime_required_level', 'palime_required_level_nonce' );

    $options = [
        ''  => 'Без ограничения',
        '2' => '2 — Свидетель',
        '3' => '3 — Архивист',
        '4' => '4 — Куратор',
        '5' => '5 — Интерпретатор',
        '6' => '6 — Носитель',
        '7' => '7 — Апостол',
        '8' => '8 — Архонт',
    ];

    echo '<select name="required_level" style="width:100%">';
    foreach ( $options as $k => $label ) {
        $selected = selected( $value, $k, false );
        echo '<option value="' . esc_attr( $k ) . '" ' . $selected . '>' . esc_html( $label ) . '</option>';
    }
    echo '</select>';
    echo '<p class="description" style="margin-top:8px;">Материал будет скрыт для пользователей ниже выбранного уровня.</p>';
}

add_action( 'save_post', function( $post_id ) {
    if ( ! isset( $_POST['palime_required_level_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['palime_required_level_nonce'], 'palime_required_level' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $level = sanitize_text_field( $_POST['required_level'] ?? '' );
    if ( $level ) {
        update_post_meta( $post_id, 'required_level', (int) $level );
    } else {
        delete_post_meta( $post_id, 'required_level' );
    }
} );
