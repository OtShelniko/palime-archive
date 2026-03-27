<?php
/**
 * Template Name: Раздел — ИЗО
 * Template Post Type: page
 *
 * Palime Archive — page-art.php
 * Страница раздела Изобразительное искусство (/art). Контент: template-parts/sections/section-page.php
 *
 * Routing spec v1.1: статьи раздела → /art/{postname}/ (CPT article, таксономия section).
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$section_slug = 'art';
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
		'section_name'    => 'ИЗО',
		'section_slogan'  => 'Канон — это политика вкуса. Мы показываем механизм.',
		'section_intro'   => 'Художники, работы, выставки и разборы. Каждый материал входит в архив как документ: эпохи, идеологии и внутренних связей визуальной культуры.',
		'section_code'    => 'ART',
		'status_line'     => 'КАНОН: АКТИВЕН | СПОРЫ: АКТИВНЫ | ПЕРИОД: ЕВРОПА / 1300–1900',
		'bg_color'        => '#0D0C0A',
		'accent_color'    => '#C6A25A',
		'hero_image_url'  => $hero_url,
		'rating_authors'  => 'Лучшие художники',
		'rating_works'    => 'Лучшие работы',
		'monthly_cats'    => [
			'exhibitions' => 'Выставки',
			'works'       => 'Работы',
			'artists'     => 'Художники',
		],
		'section_about'   => '<p>Изобразительное искусство — это материализованная точка зрения. Каждая работа в архиве — не просто объект эстетического суждения, но и документ: исторического момента, идеологии, технического решения, личного мифа художника.</p><p>Канон формируется людьми. Мы показываем, кем, когда и зачем.</p>',
	]
);

get_footer();
