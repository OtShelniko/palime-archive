<?php
/**
 * Template Name: Раздел — ИЗО
 * Template Post Type: page
 *
 * Palime Archive — page-art.php
 * Страница раздела Изобразительное искусство (/art)
 *
 * @package Palime_Archive
 */

get_header();

get_template_part( 'template-parts/sections/section-page', null, [
    'section_slug'   => 'art',
    'section_name'   => 'ИЗО',
    'section_slogan' => 'Канон — это политика вкуса. Мы показываем механизм.',
    'status_line'    => 'КАНОН: АКТИВЕН | СПОРЫ: АКТИВНЫ | ПЕРИОД: ЕВРОПА / 1300–1900',
    'bg_color'       => '#0D0C0A',
    'accent_color'   => '#C6A25A',
    'rating_authors' => 'Лучшие художники',
    'rating_works'   => 'Лучшие работы',
    'monthly_cats'   => [
        'exhibitions' => 'Выставки',
        'works'       => 'Работы',
        'artists'     => 'Художники',
    ],
    'section_about'  => '<p>Изобразительное искусство — это материализованная точка
        зрения. Каждая работа в архиве — не просто объект эстетического суждения,
        но и документ: исторического момента, идеологии, технического решения,
        личного мифа художника.</p>
        <p>Канон формируется людьми. Мы показываем, кем, когда и зачем.</p>',
] );

get_footer();
