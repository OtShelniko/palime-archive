<?php
/**
 * Template Name: Раздел — Музыка
 * Template Post Type: page
 *
 * Palime Archive — page-music.php
 * Страница раздела Музыка (/music). Контент: template-parts/sections/section-page.php
 *
 * Routing spec v1.1: статьи раздела → /music/{postname}/ (CPT article, таксономия section).
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$section_slug = 'music';
$hero_rel     = '/assets/img/hero-' . $section_slug . '.jpg';
$hero_abs     = get_template_directory() . $hero_rel;
$hero_url     = is_readable( $hero_abs ) ? get_template_directory_uri() . $hero_rel : '';

get_template_part(
	'template-parts/sections/section-page',
	null,
	[
		'section_slug'    => $section_slug,
		'section_name'    => 'Музыка',
		'section_slogan'  => 'Звук не развлекает. Он перепрошивает.',
		'status_line'     => 'СИГНАЛ: В ЭФИРЕ | АРХИВ: АКТИВЕН | ШУМ: ВЫСОКИЙ',
		'bg_color'        => '#07060A',
		'accent_color'    => '#FF4FA3',
		'hero_image_url'  => $hero_url,
		'rating_authors'  => 'Лучшие исполнители',
		'rating_works'    => 'Лучшие альбомы',
		'monthly_cats'    => [
			'tracks'  => 'Треки',
			'albums'  => 'Альбомы',
			'artists' => 'Исполнители',
		],
		'section_about'   => '<p>Музыка — это самый прямой путь к изменению состояния. Не метафора — физиология. Ритм меняет сердцебиение, тембр — эмоциональный фон, структура — ожидание. Мы архивируем работы, которые используют этот механизм осознанно: как художественный инструмент, а не как фон.</p><p>Звук существует только в настоящем. Архив — способ его удержать.</p>',
	]
);

get_footer();
