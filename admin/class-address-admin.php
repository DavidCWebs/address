<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://dev-notes.eu
 * @since      1.0.0
 *
 * @package    Address
 * @subpackage Address/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Address
 * @subpackage Address/admin
 * @author     David Egan <david@carawebs.com>
 */
class Address_Admin {

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

	/**
	 * The options name to be used in this plugin
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string      $option_name    Option name of this plugin
	 */
	private $option_name;

	private $options;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->option_name = 'carawebs_' . $plugin_name;
		$this->options = get_option( $this->option_name . '_data' );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @see http://ottopress.com/2009/wordpress-settings-api-tutorial/
	 * @since  1.0.0
	 */
	public function add_options_page() {

	  $this->plugin_screen_hook_suffix = add_options_page(
      __( 'Address & Contact Details', 'address' ),				// Options page Title
      __( 'Address & Contact', 'address' ),								// Displays in the Dashboard Menu
      'edit_pages',																		// User must have this capability (e.g. Admin in this case)
      $this->option_name . '_' . $this->plugin_name,			// Must be unique
      array( $this, 'display_options_page' )							// Content of the page generated by this method
	  );

	}

	/**
	 * Render the options page for plugin - go get a partial
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {

	    include_once 'partials/address-admin-display.php';

	}

	/**
	 * [register_settings description]
	 * @return [type] [description]
	 */
	public function register_settings(){

		register_setting(
			$this->plugin_name,											// Setting Group Name
			$this->option_name . '_data',						// Option name (DB field - this will be an array)
			array( $this, 'sanitize_address' )			// Sanitization callback
			);

		// Add a General section
		add_settings_section(
	    $this->option_name . '_data',												// HTML ID tag for section
	    __( 'Address', 'address' ),													// Section title, shown in a <h3> tag
	    array( $this, $this->option_name . '_general' ),		// Callback function to echo content/section explanation
	    $this->plugin_name																	// Settings page to show this on
		);

		add_settings_field(
	    $this->option_name . '_business_name',
	    __( 'Business Name', 'address' ),
	    array( $this, $this->option_name . '_business_name' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_business_name' )
		);

		add_settings_field(
	    $this->option_name . '_address_line_1',										// HTML ID tag for section
	    __( 'Address Line 1', 'address' ),												// Text printed next to field
	    array( $this, $this->option_name . '_address_line_1' ),		// Callback function to echo the form field
	    $this->plugin_name,																				// Settings page to show this
			$this->option_name . '_data',															// The section, as defined in the add_settings_section() call
	    array( 'label_for' => $this->option_name . '_address_line_1' ) // Set the title as "label_for"
		);

		add_settings_field(
	    $this->option_name . '_address_line_2',
	    __( 'Address Line 2', 'address' ),
	    array( $this, $this->option_name . '_address_line_2' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_address_line_2' )
		);

		add_settings_field(
	    $this->option_name . '_town',
	    __( 'Town', 'address' ),
	    array( $this, $this->option_name . '_town' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_town' )
		);

		add_settings_field(
	    $this->option_name . '_county',
	    __( 'County', 'address' ),
	    array( $this, $this->option_name . '_county' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_county' )
		);

		add_settings_field(
	    $this->option_name . '_postcode',
	    __( 'Postcode', 'address' ),
	    array( $this, $this->option_name . '_postcode' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_postcode' )
		);

		add_settings_field(
	    $this->option_name . '_country',
	    __( 'Country', 'address' ),
	    array( $this, $this->option_name . '_country' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_country' )
		);

		add_settings_field(
	    $this->option_name . '_landline',
	    __( 'Landline', 'address' ),
	    array( $this, $this->option_name . '_landline' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_landline' )
		);

		add_settings_field(
	    $this->option_name . '_mobile',
	    __( 'Mobile Phone Number', 'address' ),
	    array( $this, 'mobile' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_mobile' )
		);

		add_settings_field(
	    $this->option_name . '_facebook',
	    __( 'Facebook', 'address' ),
	    array( $this, 'facebook' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_facebook' )
		);

		add_settings_field(
	    $this->option_name . '_twitter',
	    __( 'Your Twitter Home URL', 'address' ),
	    array( $this, 'twitter' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_twitter' )
		);

		add_settings_field(
	    $this->option_name . 'pinterest',
	    __( 'Your Pinterest Home URL', 'address' ),
	    array( $this, 'pinterest' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_pinterest' )
		);

		add_settings_field(
	    $this->option_name . '_skype',
	    __( 'Your Skype Contact Details', 'address' ),
	    array( $this, 'skype' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_skype' )
		);

		add_settings_field(
	    $this->option_name . '_email',
	    __( 'The email address to be used as the site contact', 'address' ),
	    array( $this, 'email' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_email' )
		);

		add_settings_field(
	    $this->option_name . '_company_no',
	    __( 'Registered Company Number', 'address' ),
	    array( $this, 'company_no' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_company_no' )
		);

		add_settings_field(
	    $this->option_name . '_VAT_no',
	    __( 'VAT Number (if relevant)', 'address' ),
	    array( $this, 'VAT_no' ),
	    $this->plugin_name,
			$this->option_name . '_data',
	    array( 'label_for' => $this->option_name . '_VAT_no' )
		);


	}

	/**
 * Render the text for the general section
 *
 * @since  1.0.0
 */
	public function carawebs_address_general() {

  echo '<p>' . __( 'Please change the settings accordingly.', 'address' ) . '</p>';

	}

	/**
	* Render the text input field for the business name
	*
	* @since  1.0.0
	*/
	public function carawebs_address_business_name() {

		$name = $this->option_name . "_data[business_name]";
		$value = !empty( $this->options['business_name'] ) ? esc_html( $this->options['business_name'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_name" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for address line 1
	*
	* @since  1.0.0
	*/
	public function carawebs_address_address_line_1() {

		$name = $this->option_name . "_data[address_line_1]";
		$value = !empty( $this->options['address_line_1'] ) ? esc_html( $this->options['address_line_1'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_address_line_1" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for address line 2
	*
	* @since  1.0.0
	*/
	public function carawebs_address_address_line_2() {

		$name = $this->option_name . "_data[address_line_2]";
		$value = !empty( $this->options['address_line_2'] ) ? esc_html( $this->options['address_line_2'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_address_line_2" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for town
	*
	* @since  1.0.0
	*/
	public function carawebs_address_town() {

		$name = $this->option_name . "_data[town]";
		$value = !empty( $this->options['town'] ) ? esc_html( $this->options['town'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_town" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for County
	*
	* @since  1.0.0
	*/
	public function carawebs_address_county() {

		$name = $this->option_name . "_data[county]";
		$value = !empty( $this->options['county'] ) ? esc_html( $this->options['county'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_county" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for Postcode
	*
	* @since  1.0.0
	*/
	public function carawebs_address_postcode() {

		$name = $this->option_name . "_data[postcode]";
		$value = !empty( $this->options['postcode'] ) ? esc_html( $this->options['postcode'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_postcode" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for country
	*
	* @since  1.0.0
	*/
	public function carawebs_address_country() {

		$name = $this->option_name . "_data[country]";
		$value = !empty( $this->options['country'] ) ? esc_html( $this->options['country'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_country" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

	/**
	* Render the text input field for landline
	*
	* @since  1.0.0
	*/
	public function carawebs_address_landline() {

		$name = $this->option_name . "_data[landline]";
		$value = !empty( $this->options['landline'] ) ? esc_html( $this->options['landline'] ): null;

		ob_start();

		?>
		<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_landline" placeholder="<?= $value; ?>" value="<?= $value; ?>">
		<?php

		echo ob_get_clean();

	}

		/**
		* Render the text input field for mobile contact number
		*
		* @since  1.0.0
		*/
		public function mobile() {

			$name = $this->option_name . "_data[mobile]";
			$value = !empty( $this->options['mobile'] ) ? esc_html( $this->options['mobile'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_mobile" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for Facebook field
		*
		* @since  1.0.0
		*/
		public function facebook() {

			$name = $this->option_name . "_data[facebook]";
			$value = !empty( $this->options['facebook'] ) ? esc_html( $this->options['facebook'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_facebook" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for Twitter field
		*
		* @since  1.0.0
		*/
		public function twitter() {

			$name = $this->option_name . "_data[twitter]";
			$value = !empty( $this->options['twitter'] ) ? esc_html( $this->options['twitter'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_twitter" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for Twitter field
		*
		* @since  1.0.0
		*/
		public function pinterest() {

			$name = $this->option_name . "_data[pinterest]";
			$value = !empty( $this->options['pinterest'] ) ? esc_html( $this->options['pinterest'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_pinterest" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for Twitter field
		*
		* @since  1.0.0
		*/
		public function skype() {

			$name = $this->option_name . "_data[skype]";
			$value = !empty( $this->options['skype'] ) ? esc_html( $this->options['skype'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_skype" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for Twitter field
		*
		* @since  1.0.0
		*/
		public function email() {

			$name = $this->option_name . "_data[email]";
			$value = !empty( $this->options['email'] ) ? esc_html( $this->options['email'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_email" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for 'co_number' field
		*
		* @since  1.0.0
		*/
		public function company_no() {

			$name = $this->option_name . "_data[company_no]";
			$value = !empty( $this->options['company_no'] ) ? esc_html( $this->options['company_no'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_company_no" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

		/**
		* Render the text input field for 'VAT_no' field
		*
		* @since  1.0.0
		*/
		public function VAT_no() {

			$name = $this->option_name . "_data[VAT_no]";
			$value = !empty( $this->options['VAT_no'] ) ? esc_html( $this->options['VAT_no'] ): null;

			ob_start();

			?>
			<input type="text" name="<?= $name; ?>" id="<?= $this->option_name; ?>_VAT_no" placeholder="<?= $value; ?>" value="<?= $value; ?>">
			<?php

			echo ob_get_clean();

		}

	/**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
		public function sanitize_address( $address_fields ) {

			foreach( $address_fields as $field ){

				sanitize_text_field( $field );

			}

			return $address_fields;

		}

		/**
		 * Add action links to plugins page.
		 *
		 * @param array $links Array of links for plugin action links
		 * @since  1.0.0
		 * @return string $links Modified array of links for plugin action links
		 */
		public function add_action_links( $links ){

			$options_link = $this->option_name . '_' . $this->plugin_name;

			$settings_link = [
	    	'<a href="' . admin_url( 'options-general.php?page=' . $options_link ) . '">' . __('Settings', $this->plugin_name) . '</a>',
				'<a href="http://carawebs.com" target="_blank">Carawebs</a>'
			];

	   return array_merge(  $settings_link, $links );

		}

	public function register_widget() {

    register_widget( 'Address_Widget' );

	}

}
