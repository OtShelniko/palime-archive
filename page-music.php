<?php
/**
 * Template Name: Раздел — Музыка
 * Template Post Type: page
 *
 * Palime Archive — page-music.php
 * Страница раздела Музыка (/music)
 *
 * @package Palime_Archive
 */

get_header();

get_template_part( 'template-parts/sections/section-page', null, [
    'section_slug'   => 'music',
    'section_name'   => 'Музыка',
    'section_slogan' => 'Звук не развлекает. Он перепрошивает.',
    'status_line'    => 'СИГНАЛ: В ЭФИРЕ | АРХИВ: АКТИВЕН | ШУМ: ВЫСОКИЙ',
    'bg_color'       => '#07060A',
    'accent_color'   => '#FF4FA3',
    'rating_authors' => 'Лучшие исполнители',
    'rating_works'   => 'Лучшие альбомы',
    'monthly_cats'   => [
        'tracks'   => 'Треки',
        'albums'   => 'Альбомы',
        'artists'  => 'Исполнители',
    ],
    'section_about'  => '<p>Музыка — это самый прямой путь к изменению состояния.
        Не метафора — физиология. Ритм меняет сердцебиение, тембр — эмоциональный
        фон, структура — ожидание. Мы архивируем работы, которые используют этот
        механизм осознанно: как художественный инструмент, а не как фон.</p>
        <p>Звук существует только в настоящем. Архив — способ его удержать.</p>',
] );

get_footer();
