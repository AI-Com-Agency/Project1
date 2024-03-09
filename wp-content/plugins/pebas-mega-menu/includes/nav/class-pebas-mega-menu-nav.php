<?php

/**
 * Template to customize default WordPress menu
 *
 * Created by pebas.
 */
if ( ! class_exists( 'pbs_nav' ) ) {
	class pbs_nav extends Walker_Nav_Menu {

		/**
		 * Starts the list before the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::start_lvl()
		 *
		 * @param string $output Passed by reference. Used to append additional content.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param array $args An array of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent = str_repeat( "\t", $depth );
			$output .= "\n$indent<div class=\"sub-menu dropdown-menu\"><ul class=\"sub-menu-wrapper\">\n";
		}

		/**
		 * Ends the list of after the elements are added.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent = str_repeat( $t, $depth );
			$output .= "$indent</ul></div>{$n}";
		}

		/**
		 * Starts the element output.
		 *
		 * @since 3.0.0
		 * @since 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 * @see Walker::start_el()
		 *
		 * @param string $output Used to append additional content (passed by reference).
		 * @param WP_Post $item Menu item data object.
		 * @param int $depth Depth of menu item. Used for padding.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 * @param int $id Current item ID.
		 */
		function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
			$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

			$class_names = $value = '';

			$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID . ' list-item-inline nav-item';
			if ( $this->has_children ) {
				$classes[] = 'dropdown';
			}

			$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li' . $id . $value . $class_names . '>';

			$atts           = array();
			$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
			$atts['href']   = ! empty( $item->url ) ? $item->url : '';

			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			if ( 'pebas_mega_menu' == $item->object ) {
				ob_start();
				include pebas_mega_menu_helper::get_view( 'mega-menu' );
				$mega_menu   = ob_get_contents();
				$item_output = $args->before;
				$item_output .= '<a class="nav-link btn-mega-menu" href="javascript:">';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</a>' . '<i class="nav-icon material-icons">' . esc_html( 'arrow_drop_down' ) . '</i>';
				$item_output .= $mega_menu;
				$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
				ob_get_clean();
			} elseif ( 'pebas_mega_menu' != $item->object ) { // if it is not mega menu
				$item_output = $args->before;
				$item_label  = get_post_meta( $item->ID, 'menu-item-menu-label', true );
				$item_label  = get_post_meta( $item->ID, 'menu-item-menu-label', true );
				$item_label  = isset( $item_label ) ? $item_label : '';
				if ( ! empty( $item_label ) ) {
					$item_output .= '<span class="menu-label">' . esc_html( $item_label ) . '</span>';
				}
				$item_output .= '<a class="nav-link ' . ( $this->has_children ? esc_attr( 'dropdown-toggle' ) : '' ) . '" data-toggle="' . ( $this->has_children ? esc_attr( 'dropdown' ) : '' ) . '"' . $attributes . '>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= '</a>' . ( $this->has_children ? '<i class="nav-icon material-icons">' . esc_html( 'arrow_drop_down' ) . '</i>' : '' );
				$output      .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}

		}

		/**
		 * Ends the element output, if needed.
		 *
		 * @since 3.0.0
		 *
		 * @see Walker::end_el()
		 *
		 * @param string $output Used to append additional content (passed by reference).
		 * @param WP_Post $item Page data object. Not used.
		 * @param int $depth Depth of page. Not Used.
		 * @param stdClass $args An object of wp_nav_menu() arguments.
		 */
		public function end_el( &$output, $item, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$output .= "</li>{$n}";

		}

	}
}
