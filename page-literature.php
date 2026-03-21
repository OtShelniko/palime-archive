<?php
/**
 * Template Name: Раздел — Литература
 * Template Post Type: page
 *
 * Palime Archive — page-literature.php
 * Страница раздела Литература (/literature)
 *
 * @package Palime_Archive
 */

get_header();

get_template_part( 'template-parts/sections/section-page', null, [
    'section_slug'   => 'lit',
    'section_name'   => 'Литература',
    'section_slogan' => 'Книги не утешают. Они вскрывают.',
    'status_line'    => 'БИБЛИОТЕКА: ОТКРЫТА | КАТАЛОГИЗАЦИЯ: ВКЛ | МАРГИНАЛИИ: АКТИВНЫ',
    'bg_color'       => '#4A3428',
    'accent_color'   => '#C8A882',
    'rating_authors' => 'Лучшие писатели',
    'rating_works'   => 'Лучшие романы',
    'monthly_cats'   => [
        'books'    => 'Книги',
        'debuts'   => 'Дебюты',
        'reprints' => 'Переиздания',
    ],
    'section_about'  => '<p>Литература — не утешение и не развлечение. Это инструмент
        вскрытия: текст обнажает механизм времени, языка, власти и частного опыта.
        Мы собираем книги, которые что-то делают с читателем — меняют угол зрения,
        нарушают привычный порядок слов.</p>
        <p>Архив живёт, пока кто-то читает. Мы читаем.</p>',
] );

get_footer();
