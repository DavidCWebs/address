<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://dev-notes.eu
 * @since      1.0.0
 *
 * @package    Address
 * @subpackage Address/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Address
 * @subpackage Address/public
 * @author     David Egan <david@carawebs.com>
 */
class Address_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $option_name;

	/**
	 * This is a static property, so it can be accessed outside the class.
	 * @var [type]
	 */
	private static $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->option_name = 'carawebs_' . $plugin_name;
		self::$options = get_option( $this->option_name . '_data' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/address-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/address-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Echo the address
	 *
	 * @since    1.0.0
	 *
	 * @return string Address HTML
	 */
	public function the_address(){

		$address = self::get_address();
		echo $address;

	}

	/**
	 * Echo the Contact Block
	 *
	 * @since    1.0.1
	 *
	 * @return string Contact HTML
	 */
	public function the_contact_details(){

		$contact = self::get_contact_details();
		echo $contact;

	}

	public function the_mobile_number(){

		$number = self::get_mobile_number();

		echo $number;

	}

	public function the_landline_number(){

		echo self::get_landline_number();

	}

  public function the_email() {

    echo self::get_email();

  }

	/**
	 * Register_shortcodes
	 *
	 * @since		1.0.0
	 *
	 */
	public function register_shortcodes() {

		add_shortcode( 'address', array( $this, 'address_shortcode') );
		//add_shortcode( 'addressCTA', array( $this, 'CTA_shortcode') );

	}

	/**
	 * Define basic shortcode for the address.
	 *
	 * Callback function returning address HTML to the `add_shortcode` hook.
	 *
	 * @since		1.0.0
	 * @param  [type] $atts [description]
	 * @return string Returns the address as a HTML block
	 */
	public static function address_shortcode( $atts ){

		$address = self::get_address();

		return apply_filters( 'carawebs_address_shortcode_html', $address );

	}

	public static function CTA_shortcode( $atts ){

		$CTA = self::get_CTA();

		return apply_filters( 'carawebs_CTA_shortcode_html', $CTA );

	}

	public static function get_CTA(){

		ob_start();
		?>
		<p class="lead">Contact Us to Discuss Your Project</p>
		<?php

		return ob_get_clean();

	}

	/**
	 * Return a properly formatted HTML block for the address.
	 *
	 * 'carawebs_address_data' Allows the address data array to be filtered
	 *
	 * @since    1.0.0
	 * @see http://schema.org
	 *
	 * @return string HTML address block, with schema.org properies.
	 */
	public static function get_address() {

		// Filter the address data array
		// -------------------------------------------------------------------------
		$address = apply_filters( 'carawebs_address_data', self::$options );

		$business_name		= !empty( $address['business_name'] ) ? esc_html( $address['business_name'] ) : null;
		$address_line_1		= !empty( $address['address_line_1'] ) ? esc_html( $address['address_line_1'] ) : null;
		$address_line_2		= !empty( $address['address_line_2'] ) ? esc_html( $address['address_line_2'] ) : null;
		$town							= !empty( $address['town'] ) ? esc_html( $address['town'] ) : null;
		$county						= !empty( $address['county'] ) ? esc_html( $address['county'] ) : null;
		$country					= !empty( $address['country'] ) ? esc_html( $address['country'] ) : null;
		$postcode					= !empty( $address['postcode'] ) ? esc_html( $address['postcode'] ) : null;

		ob_start();

		// Action hook before the address
		// -------------------------------------------------------------------------
		do_action( 'carawebs_before_address' );

		// The address block - each line can be filtered
		// -------------------------------------------------------------------------
		echo apply_filters( 'carawebs_address_open_div', '<div class="address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">' );

		if( !empty( $business_name ) ){
			echo apply_filters( 'carawebs_address_business_name', '<h2><span itemprop="name">' . $business_name . '</span></h2>', $business_name );
		}

		if( !empty( $address_line_1 ) ){
			echo apply_filters( 'carawebs_address_line_1', '<span itemprop="streetAddress">' . $address_line_1 . '</span><br />', $address_line_1 );
		}

		if( !empty( $address_line_2 ) ){
			echo apply_filters( 'carawebs_address_line_2', '<span itemprop="streetAddress">' . $address_line_2 . '</span><br />', $address_line_2 );
		}

		if( !empty( $town ) ){
			echo apply_filters( 'carawebs_address_town', '<span itemprop="addressLocality">' . $town . '</span><br />', $town );
		}

		if( !empty( $county ) ){
			echo apply_filters( 'carawebs_address_county', '<span itemprop="addressLocality">' . $address['county'] . '</span><br />', $county );
		}

		if( !empty( $country ) ){
			echo apply_filters( 'carawebs_address_country', '<span itemprop="addressCountry">' . $country . '</span><br />', $country );
		}

		if( !empty( $postcode ) ){
			echo apply_filters( 'carawebs_address_postcode', '<span itemprop="postalCode">' . $postcode . '</span>', $postcode );
		}

		// Filter the closing tag
		// -------------------------------------------------------------------------
		echo apply_filters( 'carawebs_address_close_div', "</div>" );

		// Action hook after the address
		// -------------------------------------------------------------------------
		do_action( 'carawebs_after_address' );

		return ob_get_clean();

	}

	/**
	 * Return a properly formatted HTML block for the contact details.
	 *
	 * 'carawebs_address_data' Allows the address data array to be filtered
	 *
	 * @since    1.0.0
	 * @see http://schema.org
	 *
	 * @return string HTML contact block, with schema.org properies.
	 */
	public static function get_contact_details() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-helpers.php';

		// Filter the address data array
		// -------------------------------------------------------------------------
		$address = apply_filters( 'carawebs_address_data', self::$options );
		$landline	= !empty( $address['landline'] ) ? $address['landline'] : null;
		$email	= !empty( $address['email'] ) ? $address['email'] : null;
		$mobile_number = !empty( $address['mobile'] ) ? $address['mobile'] : null;
		$facebook = !empty( $address['facebook'] ) ? $address['facebook'] : null;
		$twitter = !empty( $address['twitter'] ) ? $address['twitter'] : null;

		ob_start();

		include( plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/contact-block.php');

		$contact_block = ob_get_clean();

		$content = apply_filters(
			'carawebs_contact_block', $contact_block,
			$landline,
			$mobile_number,
			$facebook,
			$twitter
		);

		echo $content;

	}

	public static function get_mobile_number() {

		$mobile_number = !empty( self::$options['mobile'] ) ? self::$options['mobile'] : null;

		return $mobile_number;

	}

	public static function get_landline_number() {

		$landline	= !empty( self::$options['landline'] ) ? self::$options['landline'] : null;

		return $landline;

	}

  public static function get_email() {

		$email	= !empty( self::$options['email'] ) ? self::$options['email'] : null;

		return $email;

	}

}
