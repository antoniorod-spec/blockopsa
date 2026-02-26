<?php
/**
 * Plugin Name: Evitar borrado de CSS de Elementor en actualizaciones ajenas
 * Description: Evita que Elementor borre sus archivos CSS cuando se actualizan otros plugins o temas.
 * Version: 1.1
 * Author: blockopsa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 1) Cambiar Elementor a CSS interno (una sola vez) - evita archivos externos que se borran
add_action( 'init', function () {
	if ( get_option( 'blockopsa_elementor_css_fix_applied', false ) ) {
		return;
	}
	// Solo si Elementor está activo
	if ( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return;
	}
	update_option( 'elementor_css_print_method', 'internal' );
	update_option( 'blockopsa_elementor_css_fix_applied', true );
	// Purgar caché de Breeze para que el nuevo HTML (con CSS embebido) se regenere
	do_action( 'breeze_clear_all_cache' );
}, 20 );

add_action( 'elementor/init', function () {
	$module = \Elementor\Plugin::$instance->modules_manager->get_modules( 'element-cache' );
	if ( ! $module ) {
		return;
	}
	// Quitar el hook que borra TODO el CSS de Elementor cuando CUALQUIER plugin/tema se actualiza
	remove_action( 'upgrader_process_complete', [ $module, 'clear_cache' ], 10 );
	// Añadir nuestro propio hook que solo borra cuando Elementor se actualiza
	add_action( 'upgrader_process_complete', function ( $upgrader, $options ) {
		if ( empty( $options['action'] ) || $options['action'] !== 'update' ) {
			return;
		}
		$is_elementor_update = false;
		if ( ! empty( $options['plugins'] ) && is_array( $options['plugins'] ) ) {
			foreach ( $options['plugins'] as $plugin ) {
				if ( strpos( $plugin, 'elementor' ) !== false ) {
					$is_elementor_update = true;
					break;
				}
			}
		}
		if ( ! empty( $options['themes'] ) && is_array( $options['themes'] ) ) {
			foreach ( $options['themes'] as $theme ) {
				if ( strpos( $theme, 'elementor' ) !== false ) {
					$is_elementor_update = true;
					break;
				}
			}
		}
		if ( $is_elementor_update && class_exists( 'Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}
	}, 10, 2 );
}, 20 );
