<?php
/**
 * Template Name: Раздел — Кино
 * Template Post Type: page
 *
 * Palime Archive — page-cinema.php
 * Страница раздела Кино (/cinema). Контент: template-parts/sections/section-page.php
 *
 * Routing spec v1.1: статьи раздела → /cinema/{postname}/ (CPT article, таксономия section).
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$section_slug = 'cinema';
$hero_url     = '';
foreach ( [ '/assets/img/hero-' . $section_slug . '.jpg', '/assets/img/section-' . $section_slug . '.jpg' ] as $hero_rel ) {
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
		'section_slug'    => $section_slug,
		'section_name'    => 'Кино',
		'section_slogan'  => 'Режиссёры, работы, подборки, разборы. Кино как форма власти, памяти и стиля.',
		'section_intro'   => 'Фильмы, режиссёры, разборы сцен и рейтинги. Каждый материал входит в архив как элемент системы: связан с другими работами, темами и контекстами.',
		'section_code'    => 'CIN',
		'status_line'     => 'CINEMA · SECTION · INDEX: ACTIVE | ПРОЕКТОР: ВКЛ | АРХИВ: АКТИВЕН',
		'bg_color'        => '#0A1020',
		'accent_color'    => '#4DB7FF',
		'hero_image_url'  => $hero_url,
		'rating_authors'  => 'Лучшие режиссёры',
		'rating_works'    => 'Лучшие фильмы',
		'monthly_cats'    => [
			'films'     => 'Фильмы',
			'series'    => 'Сериалы',
			'animation' => 'Анимация',
		],
		'section_about'   => '<p>Кино — не развлечение. Кино — способ видеть мир. Мы разбираем фильмы как тексты, режиссёров как мыслителей, сцены как аргументы. Без рейтингов ради рейтингов — только метод.</p><p>Архив фиксирует, что осталось на экране после того, как погас свет.</p>',
	]
);

get_footer();
