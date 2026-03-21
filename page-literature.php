<?php
/**
 * Template Name: Раздел — Литература
 * Template Post Type: page
 *
 * Palime Archive — page-literature.php
 * Страница раздела Литература (/literature). Контент: template-parts/sections/section-page.php
 *
 * Routing spec v1.1: статьи раздела → /lit/{postname}/ (CPT article, таксономия section).
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$section_slug = 'lit';
$hero_url     = '';
// hero-{slug}.jpg — канон; hero-literature.jpg — совместимость со старым именем файла.
foreach ( [ '/assets/img/hero-' . $section_slug . '.jpg', '/assets/img/hero-literature.jpg' ] as $hero_rel ) {
	$hero_abs = get_template_directory() . $hero_rel;
	if ( is_readable( $hero_abs ) ) {
		$hero_url = get_template_directory_uri() . $hero_rel;
		break;
	}
}

get_template_part(
	'template-parts/sections/section-page',
	null,
	[
		'section_slug'           => $section_slug,
		'section_name'           => 'Литература',
		'section_slogan'         => 'Книги не утешают. Они вскрывают.',
		'status_line'            => 'БИБЛИОТЕКА: ОТКРЫТА | КАТАЛОГИЗАЦИЯ: ВКЛ | МАРГИНАЛИИ: АКТИВНЫ',
		'bg_color'               => '#4A3428',
		'accent_color'           => '#C8A882',
		'hero_image_url'         => $hero_url,
		'hero_button_text_color' => '#1a1410',
		'rating_authors'         => 'Лучшие писатели',
		'rating_works'           => 'Лучшие романы',
		'monthly_cats'           => [
			'books'    => 'Книги',
			'debuts'   => 'Дебюты',
			'reprints' => 'Переиздания',
		],
		'section_about'          => '<p>Литература — не утешение и не развлечение. Это инструмент вскрытия: текст обнажает механизм времени, языка, власти и частного опыта. Мы собираем книги, которые что-то делают с читателем — меняют угол зрения, нарушают привычный порядок слов.</p><p>Архив живёт, пока кто-то читает. Мы читаем.</p>',
	]
);

get_footer();
