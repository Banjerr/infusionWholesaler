<?php
/*
Plugin Name: InfusionWholesaler
Version: 0.1-alpha
Description: Provides an elegant solution for a wholesale Infusionsoft cart, utilizing Wordpress and Infusionsoft
Author: Ben Redden
Author URI: http://www.countryfriedcoders.me
Plugin URI: http://www.countryfriedcoders.me/infusionWholesaler
Text Domain: infusionWholesaler
Domain Path: /languages
*/

/*
 * require vendor autoload
 */
require_once 'vendor/autoload.php';

/*
 * require retrieval stuff
 */
require_once(  plugin_dir_path( __FILE__ ) . 'retrieve.php');

/*
 * get the site url for the redirect
 */
$redirectUrl = get_site_url() . '/wp-content/plugins/infusionWholesaler/auth.php';

/*
 * require secret stuff
 */
require_once(  plugin_dir_path( __FILE__ ) . 'secretStuff.php');


global $oauth_db_version;
$oauth_db_version = '1.0';

// create the table in da database
function create_oauth_table () {
  global $wpdb;
  global $oauth_db_version;

  // set the charset collate
  $charset_collate = $wpdb->get_charset_collate();
  // grab the db prefix, add the table name
  $table_name = $wpdb->prefix . "infusionWholesaler";
  // make that table
  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    token text NOT NULL,
    expiration int(15) NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );

  add_option( 'oauth_db_version', $ouath_db_version );
}

// add initial data to set everything up right
function add_dummyData(){
  global $wpdb;

  // dummy data
  $serialToken = '0';
  $endOfLife = '1';

  // table name, with prefix
  $table_name = $wpdb->prefix . 'infusionWholesaler';

  // put some initial data in there
  $wpdb->insert(
  	$table_name,
  	array(
  		'token' => $serialToken,
      'expiration' => $endOfLife
  	)
  );
}

// add dem actions
add_action('admin_menu', 'infusionWholesaler_register_options_page');

// add it to the menu
function infusionWholesaler_register_options_page() {
  add_options_page('InfusionWholesaler Authentication', 'InfusionWholesaler Authentication', 'manage_options', 'infusionWholesaler', 'infusionWholesaler_settings_page');
}

// settings page
function infusionWholesaler_settings_page(){
  // if they shouldnt be here, make them leave
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  if ( $_SERVER["REQUEST_METHOD"] == "POST" ){
  }
  ?>
    <div>
    <?php screen_icon(); ?>
    <h2>IS to WP Form Settings</h2>

    <?php
    global $infusionsoft;
    retrieve_token();
    global $newToken;

    if ($newToken) {
        echo '<p>You are authenticated.</p>';
    } else {
        echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
    } // end oAuth IS STUFF
    ?>
    </div>
  <?php
}

// call the db functions
register_activation_hook( __FILE__, 'create_oauth_table' );
register_activation_hook( __FILE__, 'add_dummyData');
