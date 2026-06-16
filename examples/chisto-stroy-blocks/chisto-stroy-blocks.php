<?php
/**
 * Plugin Name:       ЧИСТО.СТРОЙ — Блоки
 * Description:       Кастомный блок Gutenberg с лендингом клининговой компании «ЧИСТО.СТРОЙ».
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            ЧИСТО.СТРОЙ
 * License:           GPL-2.0-or-later
 * Text Domain:       chisto-stroy
 *
 * @package ChistoStroy
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the block(s) from the compiled build directory.
 *
 * Each block's scripts/styles are auto-enqueued from its block.json, so we
 * only register here — no manual wp_enqueue_* calls needed.
 */
add_action(
	'init',
	function () {
		$build = __DIR__ . '/build';

		if ( ! file_exists( $build ) ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice notice-warning"><p>';
					echo esc_html__( 'ЧИСТО.СТРОЙ: запустите «npm install && npm run build», чтобы собрать блок.', 'chisto-stroy' );
					echo '</p></div>';
				}
			);
			return;
		}

		// register_block_type accepts the build dir that holds block.json.
		register_block_type( $build . '/landing' );
	}
);
