<?php

// Palime Archive — inc/quote-fields.php
// ACF-поля для CPT quote_of_day + fallback meta box

if ( ! defined( 'ABSPATH' ) ) exit;


// =========================================================
// 1. ACF FIELD GROUP
// =========================================================

add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) return;

    acf_add_local_field_group( [
        'key'      => 'group_quote_of_day',
        'title'    => 'Цитата дня — поля',
        'fields'   => [
            [
                'key'          => 'field_quote_text',
                'label'        => 'Текст цитаты',
                'name'         => 'quote_text',
                'type'         => 'textarea',
                'rows'         => 4,
                'instructions' => 'Основной текст цитаты',
                'required'     => 1,
            ],
            [
                'key'          => 'field_quote_author',
                'label'        => 'Автор',
                'name'         => 'quote_author',
                'type'         => 'text',
                'instructions' => 'Имя автора цитаты',
            ],
            [
                'key'          => 'field_quote_work',
                'label'        => 'Источник / произведение',
                'name'         => 'quote_work',
                'type'         => 'text',
                'instructions' => 'Название произведения или источника',
            ],
            [
                'key'          => 'field_quote_link',
                'label'        => 'Связанная статья',
                'name'         => 'quote_link',
                'type'         => 'post_object',
                'post_type'    => [ 'article' ],
                'return_format'=> 'id',
                'instructions' => 'Статья, на которую ведёт кнопка «Открыть дело»',
                'allow_null'   => 1,
            ],
        ],
        'location' => [
            [
                [ 'param' => 'post_type', 'operator' => '==', 'value' => 'quote_of_day' ],
            ],
        ],
        'position'   => 'normal',
        'menu_order'  => 0,
    ] );
} );


// =========================================================
// 2. FALLBACK META BOX (если ACF не установлен)
// =========================================================

add_action( 'add_meta_boxes', function() {
    if ( function_exists( 'acf_add_local_field_group' ) ) return;

    add_meta_box(
        'palime_quote_fields',
        'Цитата дня — поля',
        'palime_quote_meta_box_render',
        [ 'quote_of_day' ],
        'normal',
        'high'
    );
} );

function palime_quote_meta_box_render( $post ) {
    $text   = get_post_meta( $post->ID, 'quote_text', true );
    $author = get_post_meta( $post->ID, 'quote_author', true );
    $work   = get_post_meta( $post->ID, 'quote_work', true );
    $link   = get_post_meta( $post->ID, 'quote_link', true );

    wp_nonce_field( 'palime_quote_fields', 'palime_quote_fields_nonce' );
    ?>
    <p>
        <label for="quote_text"><strong>Текст цитаты</strong></label><br>
        <textarea id="quote_text" name="quote_text" rows="4" style="width:100%"><?php echo esc_textarea( $text ); ?></textarea>
    </p>
    <p>
        <label for="quote_author"><strong>Автор</strong></label><br>
        <input type="text" id="quote_author" name="quote_author" value="<?php echo esc_attr( $author ); ?>" style="width:100%">
    </p>
    <p>
        <label for="quote_work"><strong>Источник / произведение</strong></label><br>
        <input type="text" id="quote_work" name="quote_work" value="<?php echo esc_attr( $work ); ?>" style="width:100%">
    </p>
    <p>
        <label for="quote_link"><strong>ID связанной статьи (CPT article)</strong></label><br>
        <input type="number" id="quote_link" name="quote_link" value="<?php echo esc_attr( $link ); ?>" style="width:200px" min="0">
        <span class="description">Оставьте 0 или пустым если нет привязки</span>
    </p>
    <?php
}

add_action( 'save_post_quote_of_day', function( $post_id ) {
    if ( ! isset( $_POST['palime_quote_fields_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['palime_quote_fields_nonce'], 'palime_quote_fields' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    $fields = [ 'quote_text', 'quote_author', 'quote_work', 'quote_link' ];
    foreach ( $fields as $key ) {
        $value = sanitize_text_field( $_POST[ $key ] ?? '' );
        if ( $key === 'quote_text' ) {
            $value = sanitize_textarea_field( $_POST[ $key ] ?? '' );
        }
        if ( $key === 'quote_link' ) {
            $value = (int) $value;
        }
        if ( $value ) {
            update_post_meta( $post_id, $key, $value );
        } else {
            delete_post_meta( $post_id, $key );
        }
    }
} );
