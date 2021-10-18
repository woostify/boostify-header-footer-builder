<?php
/**
 * Get Sub Menu
 *
 * @package Boostify_Header_Footer_Sub_Menu
 *
 * Written by ptp
 */

namespace Boostify_Header_Footer;

defined( 'ABSPATH' ) || exit;

/**
 * Boostify Header WP Sub Menu Template Class.
 */
class WP_Sub_Menu {

	/**
	 * Menu Item
	 *
	 * @var menu_items
	 */
	private $menu_items = array();

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize plugin.
		add_action( 'init', array( $this, 'init' ), 1 );
	}

	/**
	 * Initializes the plugin
	 */
	public function init() {
		// Add filters.
		add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
	}

	/**
	 * Extends the default function
	 *
	 * @param array  $sortedmenu_items | sortedmenu_items.
	 * @param object $args | munu object.
	 *
	 * @return array
	 */
	public function wp_nav_menu_objects( $sortedmenu_items, $args ) {
		// Add additional args.
		if ( ! isset( $args->level ) ) {
			$args->level = 0;
		}
		if ( ! isset( $args->child_of ) ) {
			$args->child_of = '';
		}

		// Check if we need to do anything.
		if ( ! $sortedmenu_items || ( 0 == $args->level && '' == $args->child_of ) ) { //phpcs:ignore
			return $sortedmenu_items;
		}

		// Build a tree.
		$this->menu_items = $sortedmenu_items;
		$temp_array       = array();
		foreach ( $this->menu_items as $item ) {
			$temp_array[ $item->menu_item_parent ][] = $item;
		}
		$tree = $this->build_items_tree( $temp_array, $temp_array[0] );

		// Prepare updated items.
		$updated_items = $this->get_level_items( $tree, $args->level, $args->child_of );

		// Start array keys from 1.
		$updated_items = array_filter( array_merge( array( 0 ), $updated_items ) );

		// Return updated items.
		return $updated_items;
	}

	/**
	 * Builds a tree of menu items recursively
	 *
	 * @param  array  $list | list menu item.
	 * @param  object $parent | parent.
	 * @param  int    $level | item level.
	 * @return array
	 */
	private function build_items_tree( &$list, $parent, $level = 1 ) {
		$tree = array();
		foreach ( $parent as $k => $l ) {
			if ( isset( $list[ $l->ID ] ) ) {
				$l->children = $this->build_items_tree( $list, $list[ $l->ID ], $level + 1 );
			}

			$l->level = $level;
			$tree[]   = $l;
		}

		return $tree;
	}

	/**
	 * Gets items from a particular level
	 *
	 * @param  array   $tree | Menu Tree.
	 * @param  integer $level | Level.
	 * @param  string  $child_of | Child of.
	 * @return array
	 */
	private function get_level_items( $tree, $level = 1, $child_of = '' ) {
		$items = array();
		foreach ( $tree as $item ) {
			$child_of_flag = false;

			if ( '' != $child_of ) { //phpcs:ignore
				if ( 'integer' == gettype( $child_of ) && $item->menu_item_parent != $child_of ) { //phpcs:ignore
					$child_of_flag = true;
				} elseif ( 'string' == gettype( $child_of ) && $item->menu_item_parent != $this->get_menu_id_from_title( $child_of ) ){ //phpcs:ignore
					$child_of_flag = true;
				}
			}

			if ( $item->level == $level && ! $child_of_flag ) { //phpcs:ignore
				unset( $item->children );
				$items[] = $item;
			}

			if ( isset( $item->children ) && $item->children ) {
				$items = $items + $this->get_level_items( $item->children, $level, $child_of );
			}
		}

		return $items;
	}

	/**
	 * Gets a menu ID based on the title of the item
	 *
	 * @param  string $name | name of menu.
	 * @return string
	 */
	private function get_menu_id_from_title( $name = '' ) {
		foreach ( $this->menu_items as $item ) {
			if ( $item->title == $name ) { //phpcs:ignore
				return $item->ID;
			}
		}

		return '';
	}

}

