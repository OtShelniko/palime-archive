<?php
/**
 * Template Name: Раздел — Кино
 * Template Post Type: page
 *
 * Palime Archive — page-cinema.php
 * Страница раздела Кино (/cinema)
 *
 * @package Palime_Archive
 */

get_header();

get_template_part( 'template-parts/sections/section-page', null, [
    'section_slug'   => 'cinema',
    'section_name'   => 'Кино',
    'section_slogan' => 'Кино — не развлечение. Кино — это способ видеть мир.',
    'status_line'    => 'ПРОЕКТОР: ВКЛ | АРХИВ: АКТИВЕН | ШУМ: ВЫСОКИЙ',
    'bg_color'       => '#0A1020',
    'accent_color'   => '#4DB7FF',
    'rating_authors' => 'Лучшие режиссёры',
    'rating_works'   => 'Лучшие фильмы',
    'monthly_cats'   => [
        'films'  => 'Фильмы',
        'series' => 'Сериалы',
        'anime'  => 'Анимация',
    ],
    'section_about'  => '<p>Кино — это архив взглядов. Каждый фильм — документ эпохи,
        зафиксированный способ видеть мир. Мы собираем не только великие работы,
        но и те, что открывают механизм: как работает режиссёрский метод,
        что стоит за монтажом, почему этот кадр — политическое высказывание.</p>
        <p>Палиме не развлекает. Палиме — архивирует.</p>',
] );

get_footer();
