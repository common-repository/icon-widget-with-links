<?php
/**
 * Icon Widget - With Links
 *
 * 
 * 
 * The <a href="https://wordpress.org/plugins/icon-widget/">Icon Widget</a> plugin is a great way 
 * to add icons to your site, but this customization adds the possibility of including links.
 *
 * @package   Icon_Widget
 * @author    Phil Spectrum <philspectrum13@gmail.com>
 * @license   GPL-2.0+
 * @link      https://seothemes.com
 * @copyright 2018 SEO Themes
 *
 * Plugin Name:       Icon Widget - With Links
 * Description:       Displays an icon widget with a title and description and custom links
 * Version:           1.1.0
 * Author:            Phil Spectrum
 * Text Domain:       icon-widget
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {

	exit;

}

/**
 * Widget class.
 */
class Icon_Widget extends WP_Widget {

	/**
	 * Unique identifier for the widget.
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since 1.0.0
	 *
	 * @var   string
	 */
	protected $widget_slug = 'icon-widget';

	/**
	 * List of available icon fonts.
	 *
	 * @var array
	 */
	public static $fonts = array();

	/**
	 * Constructor
	 *
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		// Define available icon fonts.
		self::$fonts = array(
			'font-awesome',
			'line-awesome',
			'ionicons',
			'streamline',
			'et-line',
		);

		// Load plugin text domain.
		add_action( 'init', array( $this, 'widget_textdomain' ) );

		parent::__construct(
			$this->get_widget_slug(),
			__( 'Icon', 'icon-widget' ),
			array(
				'classname'   => 'icon_widget',
				'description' => __( 'Displays an icon with a title and description and custom links.', 'icon-widget' ),
			)
		);

		// Add settings link.
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array(
			$this,
			'action_links'
		) );

		// Register admin styles and scripts.
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );

	}

	/**
	 * Return the widget slug.
	 *
	 * @since  1.0.0
	 *
	 * @return Plugin slug variable.
	 */
	public function get_widget_slug() {

		return $this->widget_slug;

	}

	/**
	 * Add settings link.
	 *
	 * @param  array $links Plugin links.
	 *
	 * @return array
	 */
	public function action_links( $links ) {

		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=icon_widget' ) . '">Settings</a>',
		);

		return array_merge( $links, $settings_link );

	}

	/*
	 |--------------------------------------------------------------------------
	 | Widget API Functions
	 |--------------------------------------------------------------------------
	 */

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     The array of form elements.
	 * @param array $instance The current instance of the widget.
	 */
	public function widget( $args, $instance ) {

		if ( ! isset( $args['widget_id'] ) ) {

			$args['widget_id'] = $this->id;

		}

		echo $args['before_widget'];

		printf( '<div class="icon-widget" style="text-align: %s">', esc_attr( $instance['align'] ) );

		if(!empty($instance['link_to'])) {
			echo '<a href="'.esc_html( $instance['link_to']).'">';
		}

		printf( '<i class="fa %1$s fa-%2$s" style="color:%3$s;background-color:%4$s;padding:%5$spx;border-radius:%6$spx;"></i>', esc_attr( $instance['icon'] ), esc_attr( $instance['size'] ), esc_attr( $instance['color'] ), esc_attr( $instance['bg'] ), esc_attr( $instance['padding'] ), esc_attr( $instance['radius'] ) );

		if(!empty($instance['link_to'])) {
			echo '</a>';
		}

		echo apply_filters( 'icon_widget_line_break', true ) ? '<br>' : '';

		echo $args['before_title'];

		if(!empty($instance['link_to'])) {
			echo '<a href="'.esc_html( $instance['link_to']).'">';
		}
		
		echo esc_html( $instance['title'] );

		if(!empty($instance['link_to'])) {
			echo '</a>';
		}
		
		echo $args['after_title'];

		echo apply_filters( 'icon_widget_wpautop', true ) ? wp_kses_post( wpautop( $instance['content'] ) ) : wp_kses_post( $instance['content'] );

		echo '</div>';

		echo $args['after_widget'];

	}

	/**
	 * Process the widget's options to be saved.
	 *
	 * @param array $new_instance The new instance of values to be generated via the
	 *                            update.
	 * @param array $old_instance The previous instance of values before the update.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		// Update widget's old values with new incoming values.
		$instance['title']   = sanitize_text_field( $new_instance['title'] );
		$instance['link_to'] = sanitize_text_field( $new_instance['link_to'] );
		$instance['content'] = wp_kses_post( $new_instance['content'] );
		$instance['icon']    = sanitize_html_class( $new_instance['icon'] );
		$instance['size']    = sanitize_html_class( $new_instance['size'] );
		$instance['align']   = sanitize_html_class( $new_instance['align'] );
		$instance['color']   = sanitize_hex_color( $new_instance['color'] );
		$instance['bg']      = sanitize_hex_color( $new_instance['bg'] );
		$instance['padding'] = intval( $new_instance['padding'] );
		$instance['radius']  = intval( $new_instance['radius'] );

		return $instance;

	}

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array $instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = apply_filters( 'icon_widget_defaults', array(
			'title'   => '',
			'link_to' => '',
			'content' => '',
			// Keep sub filters for backwards compat.
			'icon'    => apply_filters( 'icon_widget_default_icon', '\f000' ),
			'size'    => apply_filters( 'icon_widget_default_size', '2x' ),
			'align'   => apply_filters( 'icon_widget_default_align', 'left' ),
			'color'   => apply_filters( 'icon_widget_default_color', '#333333' ),
			'bg'      => '',
			'padding' => '',
			'radius'  => '',
		) );

		// Define default values for your variables.
		$instance = wp_parse_args( (array) $instance, $defaults );

		// Store the values of the widget in their own variable.
		$title   = $instance['title'];
		$link_to = $instance['link_to'];
		$content = $instance['content'];
		$icon    = $instance['icon'];
		$size    = $instance['size'];
		$align   = $instance['align'];
		$color   = $instance['color'];
		$bg      = $instance['bg'];
		$padding = $instance['padding'];
		$radius  = $instance['radius'];

		// Display the admin form.
		include( plugin_dir_path( __FILE__ ) . 'views/admin.php' );

	}

	/*
	 |--------------------------------------------------------------------------
	 | Public Functions
	 |--------------------------------------------------------------------------
	 */

	/**
	 * Loads the Widget's text domain for localization and translation.
	 */
	public function widget_textdomain() {

		load_plugin_textdomain( $this->get_widget_slug(), false, plugin_dir_path( __FILE__ ) . 'languages/' );

	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @param  boolean $network_wide True if WPMU superadmin uses "Network Activate"
	 *                               action, false if WPMU is disabled or plugin is
	 *                               activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		// Add default icon font.
		if ( ! get_option( 'icon_widget_settings' ) ) {

			$defaults = apply_filters( 'icon_widget_defaults', array(
				'font' => apply_filters( 'icon_widget_default_font', 'font-awesome' ),
			) );

			add_option( 'icon_widget_settings', $defaults );

		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param boolean $network_wide True if WPMU superadmin uses "Network
	 *                              Activate"action, false if WPMU is disabled or plugin
	 *                              is activated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		// Clean up.
		delete_option( 'icon_widget_settings' );

	}

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

		if ( ! is_customize_preview() && get_current_screen()->id !== 'widgets' ) {

			return;

		}

		wp_enqueue_style( 'bootstrap', plugins_url( 'assets/css/bootstrap.min.css', __FILE__ ), array( 'wp-color-picker' ) );

		wp_enqueue_style( 'bootstrap-select', plugins_url( 'assets/css/bootstrap-select.min.css', __FILE__ ), array( 'bootstrap' ) );

		// Load icon font CSS.
		$this->register_widget_styles();

	}

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {

		if ( ! is_customize_preview() && get_current_screen()->id !== 'widgets' ) {

			return;

		}

		wp_enqueue_script( 'bootstrap', plugins_url( 'assets/js/bootstrap.min.js', __FILE__ ), array(
			'jquery',
			'wp-color-picker'
		) );

		wp_enqueue_script( 'bootstrap-select', plugins_url( 'assets/js/bootstrap-select.min.js', __FILE__ ), array( 'bootstrap' ) );

	}

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

		$settings     = get_option( 'icon_widget_settings' );
		$current_font = $settings['font'];

		foreach ( self::$fonts as $font ) {

			if ( $font === $current_font ) {

				wp_enqueue_style( $font, plugins_url( 'assets/css/' . $font . '.min.css', __FILE__ ) );

			}
		}
	}
}

add_action( 'widgets_init', 'icon_widget_register_widget' );
/**
 * Register widget
 *
 * Registers the Icon Widget widget with WordPress.
 *
 * @since  1.0.8
 *
 * @return void
 */
function icon_widget_register_widget() {

	register_widget( 'Icon_Widget' );

}

// Register settings.
include( plugin_dir_path( __FILE__ ) . 'includes/settings.php' );

// Add shortcode.
include( plugin_dir_path( __FILE__ ) . 'includes/shortcode.php' );

// Hooks fired when the Widget is activated and deactivated.
register_activation_hook( __FILE__, array( 'Icon_Widget', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Icon_Widget', 'deactivate' ) );
