<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wplauncher.com
 * @since      1.0.0
 *
 * @package    Wc_Office_store
 * @subpackage Wc_Office_store/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wc_Office_store
 * @subpackage Wc_Office_store/admin
 * @author     Ben Shadle <benshadle@gmail.com>
 */
class Wc_Office_store_Admin {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array( $this, 'addPluginAdminMenu' ), 9);   
		add_action('admin_init', array( $this, 'registerAndBuildFields' )); 
		// add_action( 'admin_menu',  array( $this, 'my_admin_menu' ));
		// add_action( 'admin_init',  array( $this, 'my_admin_init' ));
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wc_Office_store_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wc_Office_store_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wc-office-store-admin.css', array(), $this->version, 'all' );

	}
	

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name.'bootstrap-toggle', plugin_dir_url( __FILE__ ) . 'js/bootstrap-toggle.min.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wc-office-store-admin.js', array( 'jquery' ), $this->version, false );

	}
	public function addPluginAdminMenu() {
		//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
		add_menu_page(  
			$this->plugin_name, 
			'WC Office Store',
			'administrator', 
			$this->plugin_name, 
			array( $this, 'displayPluginAdminDashboard' ),
			'dashicons-superhero', 
			26 
		);

		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page( 
			$this->plugin_name, 
			'WC Office Store Settings',
			'WC Office Store Settings',
			'administrator',
			$this->plugin_name.'-settings',
			array( $this, 'displayPluginAdminSettings' )
		);
		
		//add_submenu_page( '$parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		add_submenu_page( 
			$this->plugin_name, 
			'Help',
			'WC Office Store Help',
			'administrator',
			$this->plugin_name.'-help',
			array( $this, 'displayHelpAdminDashboard' )
		);
		
	}
	public function displayPluginAdminDashboard() {
		require_once 'partials/'.$this->plugin_name.'-admin-display.php';
 	}
	
	public function displayPluginAdminSettings() {
		// set this var to be used in the settings-display view
		$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
		if(isset($_GET['error_message'])){
			add_action('admin_notices', array($this,'settingsPageSettingsMessages'));
			do_action( 'admin_notices', $_GET['error_message'] );
		}
		require_once 'partials/'.$this->plugin_name.'-admin-settings-display.php';
	}

	public function displayHelpAdminDashboard() {
		require_once 'partials/'.$this->plugin_name.'-admin-help-display.php';
	}

	public function settingsPageSettingsMessages($error_message){
		switch ($error_message) {
			case '1':
				$message = __( 'There was an error adding this setting. Please try again.  If this persists, shoot us an email.', 'my-text-domain' );
				$err_code = esc_attr( 'Wc_Office_store_example_setting' );
				$setting_field = 'Wc_Office_store_example_setting';                 
			break;
		}
		$type = 'error';
		add_settings_error(
			$setting_field,
			$err_code,
			$message,
			$type
		);
	}
	public function registerAndBuildFields() {
		
		add_settings_section(
			// ID used to identify this section and with which to register options
			'Wc_Office_store_general_section', 
			// Title to be displayed on the administration page
			'',  
			// Callback used to render the description of the section
			'',  
			// array( $this, 'Wc_Office_store_display_general_account' ),    
			// Page on which to add this section of options
			'Wc_Office_store_general_settings'                   
		);
		// product_group_title
		unset($argsGroupTitle);
		$argsGroupTitle = array (
			'type'      => 'input',
			'subtype'   => 'text',
			'id'    => 'product_group_title',
			'name'      => 'product_group_title',
			'required' => 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' => 'option'
		);
		add_settings_field(
			'product_group_title',
			'Tiêu đề product group',
			array( $this, 'Wc_Office_store_render_settings_field' ),
			'Wc_Office_store_general_settings',
			'Wc_Office_store_general_section',
			$argsGroupTitle
		);

		// checkout_suggest_title
		unset($argsCheckoutSuggestTitle);
		$argsCheckoutSuggestTitle = array (
			'type'      => 'input',
			'subtype'   => 'text',
			'id'    => 'checkout_suggest_title',
			'name'      => 'checkout_suggest_title',
			'required' => 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' => 'option'
		);
		add_settings_field(
			'checkout_suggest_title',
			'Tiêu đề gợi ý sản phẩm ở trang thanh toán',
			array( $this, 'Wc_Office_store_render_settings_field' ),
			'Wc_Office_store_general_settings',
			'Wc_Office_store_general_section',
			$argsCheckoutSuggestTitle
		);

		// wc_suggest_product
		unset($args_suggest_product);
		$args_suggest_product =  array (
			'type'      => 'input',
			'subtype'   => 'checkbox',
			'title' => '',
			'id'    => 'wc_suggest_product',
			'name'      => 'wc_suggest_product',
			'required' => 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' => 'option'
		);
		add_settings_field(
			'wc_suggest_product',
			'Bật chức năng gợi ý sản phẩm',
			array( $this, 'Wc_Office_store_render_settings_field' ),
			'Wc_Office_store_general_settings',
			'Wc_Office_store_general_section',
			$args_suggest_product
		);

		// wc_group_product
		unset($args_group_product);
		$args_group_product =  array (
			'type'      => 'input',
			'subtype'   => 'checkbox',
			'title' => '',
			'id'    => 'wc_group_product',
			'name'      => 'wc_group_product',
			'required' => 'true',
			'get_options_list' => '',
			'value_type'=>'normal',
			'wp_data' => 'option'
		);
		add_settings_field(
			'wc_group_product',
			'Bật chức năng show Group',
			array( $this, 'Wc_Office_store_render_settings_field' ),
			'Wc_Office_store_general_settings',
			'Wc_Office_store_general_section',
			$args_group_product
		);

		// Select type of product	
		add_settings_field(
			'select_group_product_data',
			__( 'Chọn hiển thị Upsell / Crosssell cho Group', 'flowygo' ),
			array( $this,'Wc_Office_store_render_radio' ),
			'Wc_Office_store_general_settings',
			'Wc_Office_store_general_section',
			array( 'label_for' => 'select_group_product_data' )
		);

		register_setting(
			'Wc_Office_store_general_settings',
			'product_group_title'
		);
		register_setting(
			'Wc_Office_store_general_settings',
			'checkout_suggest_title'
		);
		register_setting(
			'Wc_Office_store_general_settings',
			'wc_suggest_product'
		);	

		register_setting(
			'Wc_Office_store_general_settings',
			'wc_group_product'
		);
		register_setting(
			'Wc_Office_store_general_settings',
			'select_group_product_data'
		);
	}
	

	public function Wc_Office_store_display_general_account() {
		echo '<p>These settings apply to all Plugin Name functionality.</p>';
	}

	public function  Wc_Office_store_render_radio() {
		$val = get_option( 'select_group_product_data');
		?>
			<fieldset>
				<label>
					<input type="radio" name="select_group_product_data" value="up_sell" <?php checked( $val, 'up_sell' ); ?>>
					<?php _e( 'Up sell', 'Wc_Office' ); ?>
				</label>
				<br>
				<label>
					<input type="radio" name="select_group_product_data" value="cross_sell" <?php checked( $val, 'cross_sell' ); ?>>
					<?php _e( 'Cross sell', 'Wc_Office' ); ?>
				</label>
			</fieldset>
		<?php
	} 

	public function Wc_Office_store_render_settings_field($args) {
	 
		if($args['wp_data'] == 'option'){
			$wp_data_value = get_option($args['name']);
		} elseif($args['wp_data'] == 'post_meta'){
			$wp_data_value = get_post_meta($args['post_id'], $args['name'], true );
		}

		switch ($args['type']) {

			case 'input':
				$value = ($args['value_type'] == 'serialized') ? serialize($wp_data_value) : $wp_data_value;
				if($args['subtype'] != 'checkbox'){
						$prependStart = (isset($args['prepend_value'])) ? '<div class="input-prepend"> <span class="add-on">'.$args['prepend_value'].'</span>' : '';
						$prependEnd = (isset($args['prepend_value'])) ? '</div>' : '';
						$step = (isset($args['step'])) ? 'step="'.$args['step'].'"' : '';
						$min = (isset($args['min'])) ? 'min="'.$args['min'].'"' : '';
						$max = (isset($args['max'])) ? 'max="'.$args['max'].'"' : '';
						if(isset($args['disabled'])){
								// hide the actual input bc if it was just a disabled input the info saved in the database would be wrong - bc it would pass empty values and wipe the actual information
								echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'_disabled" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'_disabled" size="40" disabled value="' . esc_attr($value) . '" /><input type="hidden" id="'.$args['id'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
						} else {
								echo $prependStart.'<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" '.$step.' '.$max.' '.$min.' name="'.$args['name'].'" size="40" value="' . esc_attr($value) . '" />'.$prependEnd;
						}
						/*<input required="required" '.$disabled.' type="number" step="any" id="'.$this->plugin_name.'_cost2" name="'.$this->plugin_name.'_cost2" value="' . esc_attr( $cost ) . '" size="25" /><input type="hidden" id="'.$this->plugin_name.'_cost" step="any" name="'.$this->plugin_name.'_cost" value="' . esc_attr( $cost ) . '" />*/

				} else {
						$checked = ($value) ? 'checked' : '';
						echo '<input type="'.$args['subtype'].'" id="'.$args['id'].'" "'.$args['required'].'" name="'.$args['name'].'" size="40" value="1" '.$checked.' />';
				}
				break;
			case 'select':
				break;
		
			case 'textarea':
				break;

			default:
				# code...
				break;
		}
	}
}
