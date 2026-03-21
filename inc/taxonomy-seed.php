<?php
/**
 * Palime Archive — inc/taxonomy-seed.php
 * Одноразовое создание стартовых терминов для theme / era / editorial-flag.
 * Срабатывает один раз (опция palime_taxonomy_seed_v3).
 * v3: повторный прогон после регистрации theme и editorial-flag (если v2 сработал без них).
 *
 * @package Palime_Archive
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Вставить термин, если slug ещё не занят.
 *
 * @param string $name     Название.
 * @param string $taxonomy Machine name таксономии.
 * @param string $slug     Slug (латиница).
 */
function palime_insert_term_if_missing( $name, $taxonomy, $slug ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return;
	}
	$slug = sanitize_title( $slug );
	if ( '' === $slug ) {
		return;
	}
	if ( get_term_by( 'slug', $slug, $taxonomy ) ) {
		return;
	}
	wp_insert_term(
		$name,
		$taxonomy,
		[
			'slug' => $slug,
		]
	);
}

/**
 * Стартовый словарь (идемпотентно по slug).
 */
function palime_seed_archive_taxonomy_terms() {
	if ( get_option( 'palime_taxonomy_seed_v3' ) ) {
		return;
	}

	$themes = [
		'власть'         => 'vlast',
		'смерть'         => 'smert',
		'память'         => 'pamyat',
		'одиночество'    => 'odinochestvo',
		'любовь'         => 'lyubov',
		'насилие'        => 'nasilie',
		'религия'        => 'religiya',
		'безумие'        => 'bezumie',
		'абсурд'         => 'absurd',
		'идентичность'   => 'identichnost',
		'тело'           => 'telo',
		'страх'          => 'strakh',
		'война'          => 'voyna',
		'искусство'      => 'iskusstvo',
		'мораль'         => 'moral',
	];

	foreach ( $themes as $name => $slug ) {
		palime_insert_term_if_missing( $name, 'theme', $slug );
	}

	$eras = [
		'античность'      => 'antiquity',
		'средневековье'   => 'medieval',
		'возрождение'     => 'renaissance',
		'барокко'         => 'baroque',
		'классицизм'      => 'classicism',
		'романтизм'       => 'romanticism',
		'реализм'         => 'realism',
		'модернизм'       => 'modernism',
		'авангард'        => 'avantgarde',
		'постмодернизм'   => 'postmodernism',
		'современность'   => 'contemporary',
	];

	foreach ( $eras as $name => $slug ) {
		palime_insert_term_if_missing( $name, 'era', $slug );
	}

	$flags = [
		'выбор редакции'  => 'editors-choice',
		'канон'           => 'canon',
		'точка входа'     => 'entry-point',
		'essential'       => 'essential',
		'спорное'         => 'controversial',
		'недооценённое'   => 'underrated',
	];

	foreach ( $flags as $name => $slug ) {
		palime_insert_term_if_missing( $name, 'editorial-flag', $slug );
	}

	update_option( 'palime_taxonomy_seed_v3', 1 );
}

add_action( 'init', 'palime_seed_archive_taxonomy_terms', 20 );
