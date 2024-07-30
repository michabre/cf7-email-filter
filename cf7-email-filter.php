<?php

/*
Plugin Name: CF7 Email Filter
Plugin URI: https://github.com/michabre/cf7-email-filter
Description: Contact Form 7 validator that allows you to block emails from specific domains.
Version: 1.0
Author: michabre
Author URI: https://github.com/michabre
License: GPLv2
*/

include_once ABSPATH . 'wp-admin/includes/plugin.php';
require_once plugin_dir_path( __FILE__ ) . 'src/classes/Helpers/BuildList.php';
require_once plugin_dir_path( __FILE__ ) . 'src/classes/Helpers/CleanStoredTextAreaValues.php';

$cf7_active = false;

// check if CF7 is active
if (is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
  // Run your code or display specific functionality
  $cf7_active = true;
} else {
  // Display a message or alternative functionality
  $cf7_active = false;
  add_action('admin_notices', 'cf7_active_notice');
}

function cf7_active_notice(){
  global $pagenow;
  if ( $pagenow == 'plugins.php' ) {
    echo '<div class="notice notice-warning is-dismissible"><p>CF7 Email Filter requires Contact Form 7 to be active.</p></div>';
  }
}

// add default list of blocked domains to options
// comes in a CSV file packaged with the plugin

//
if ( $cf7_active ) {
  add_action( 'admin_menu', 'wpcf7_admin_menu_extras' );
}
function wpcf7_admin_menu_extras() {
  add_options_page(
    __('CF7 Email Filter Configuration', 'textdomain'),
    __('CF7 Email Filter', 'textdomain'), 
    'manage_options',
    'cf7-email-filter',
    'cf7_email_filter_config_page' 
  );
	// add_submenu_page( 
  //   'wpcf7',
  //   __('CF7 Email Filter Configuration', 'textdomain'),
  //   __('CF7 Email Filter', 'textdomain'), 
  //   'manage_options',
  //   'cf7-email-filter',
  //   'cf7_email_filter_config_page'
	// );
}

function cf7_email_filter_get_options() {
  $options = get_option( 'cf7_email_filter_options', array() );
  $default_list_of_emails = plugin_dir_path( __FILE__ ) . 'default_emails.txt';
  $new_options['list_of_emails'] =  wp_remote_get( $default_list_of_emails );
  $new_options['warning_message'] = 'Please input a valid business email address.';
  $new_options['cf7_forms'] = '';
  $merged_options = wp_parse_args( $options, $new_options );
  $compare_options = array_diff_key( $new_options, $options );

  if ( empty( $options ) || !empty( $compare_options ) ) {
    update_option( 'cf7_email_filter_options', $merged_options );
  }
  
  return $merged_options;
}

function cf7_email_filter_config_page() {
  $options = cf7_email_filter_get_options(); 
  $build = new BuildList($options['list_of_emails']);
  $list = $build->buildList();
?>
  <div id="cf7-email-filter-general" class="wrap">
    <h2>Contact Form 7 Email Filter</h2>
    <?php if ( isset( $_GET['message'] ) && $_GET['message'] == '1' ) { ?>
    <div id='message' class='updated fade'>
      <p><strong>Settings Saved</strong></p>
    </div>
    <?php } elseif ( isset( $_GET['message'] ) && $_GET['message'] == '2' ) { ?>
    <div id='message' class='updated fade'>
      <p><strong>List of blocked URLs reverted</strong></p>
    </div>
    <?php } ?>
    <form name="cf7_email_filter_options_form" method="post" action="admin-post.php">
      <input type="hidden" name="action" value="save_cf7_email_filter_options" />
      <?php wp_nonce_field( 'cf7_email_filter' ); ?>
      <p style="margin-bottom: 0;"><strong>List of Blocked URLs</strong><br>
      Email addresses containing any of the listed URLs will be considered invalid.</p>
      <textarea name="list_of_emails" id="fancy-textarea" rows="10" cols="40" style="font-family:Consolas,Monaco,monospace"><?php echo $list; ?></textarea><br />

      <p><label for="warning-message"><strong>Warning Message</strong><br>This will be displayed at any attempt to use an email using a blocked URL.</label><br />
      <input type="text" name="warning_message" value="<?php echo $options['warning_message']; ?>" size="50" /></p>

      <p><label for="available-forms"><strong>Available Forms</strong><br>Select the forms you want the email filter actively validating.</label></p>
      <ul>
        <?php
          $selected_forms = explode(',', $options['cf7_forms']);
          $posts = get_posts(array(
            'post_type'     => 'wpcf7_contact_form',
            'numberposts'   => -1
          ));
          foreach ( $posts as $p ) {
            $checked = in_array($p->ID, $selected_forms) ? 'checked' : '';
            echo '<li>';
            echo '<input type="checkbox" name="cf7_forms[]" value="'.$p->ID.'" ' . $checked . '>';
            echo '<label for="' . $p->ID .'" >' . $p->post_title . '</label>';
            echo '</li>';
          } 
        ?>
      </ul>

      <input type="submit" value="Submit" class="button-primary" />
      <input type="submit" value="Reset" name="resetstyle" class="button-primary" />
    </form>
  </div>
<?php }

add_action( 'admin_init', 'cf7_email_filter_admin_init' );
function cf7_email_filter_admin_init() {
  add_action( 'admin_post_save_cf7_email_filter_options', 'process_cf7_email_filter_options' );
}

function process_cf7_email_filter_options() {
  // Check that user has proper security level
  if ( !current_user_can( 'manage_options' ) ) {
    wp_die( 'Not allowed' );
  }
  
  // Check if nonce field is present
  check_admin_referer( 'cf7_email_filter' );
  
  // Retrieve original plugin options array
  $options = cf7_email_filter_get_options();

  if ( isset( $_POST['resetstyle'] ) ) {
    $default_list_of_emails = plugin_dir_path( __FILE__ ) . 'default_emails.txt';
    $options['list_of_emails'] = file_get_contents( $default_list_of_emails );
    $options['warning_message'] = 'Please input a valid business email address.';
    $options['cf7_forms'] = '';
    $message = 2;
  } elseif ( !empty( $_POST ) ) {
    foreach ( array( 'list_of_emails', 'warning_message', 'cf7_forms' ) as $option_name ) {
      if ( isset( $_POST[$option_name] ) ) {
        if ( $option_name == 'cf7_forms' ) {
          $options[$option_name] = gettype($_POST[$option_name]) == 'array' ? implode(',', $_POST[$option_name]) : '';
        } else {
          $options[$option_name] = $_POST[$option_name];
        }
      } else {
        $options[$option_name] = '';
      }
    }
    $message = 1;
  }

  update_option( 'cf7_email_filter_options', $options );
  
  wp_redirect( add_query_arg( 
    array(
      'page' => 'cf7-email-filter',
      'message' => $message
    ),
    admin_url( 'options-general.php' )
  ) );

  exit;
}

// check if email is from a blocked domain
if ( $cf7_active ) {
  add_filter( 'wpcf7_validate_email*', 'free_email_validation_filter', 5, 2 );
}
function free_email_validation_filter( $result, $tag ) {  
  $options = cf7_email_filter_get_options(); 
  $warning_message = $options['warning_message'];
  $clean = new CleanStoredTextAreaValues($options['list_of_emails']);
  $freeDomainEmails = $clean->getValues();
  $form_id = $_POST['_wpcf7'];
  $forms_to_check = explode(',', $options['cf7_forms']);

  if ( !in_array($form_id, $forms_to_check) ) {
    return $result;
  }

  if ( $tag->type == 'email*' ) {
    $your_email = isset( $_POST[$tag->name] ) ? trim( $_POST[$tag->name] ) : '';
    foreach ($freeDomainEmails as $free) {
      $domain = substr($your_email, strpos($your_email, '@') + 1);
      if ( $domain == $free ) {
        $result->invalidate( $tag, $warning_message);
      }
    }
  }

  return $result;
  
}
/**
 * Add Settings link to the plugins page
 */
add_filter( 'plugin_action_links', 'cf7_email_filter_settings_link' );
function cf7_email_filter_settings_link( $links ) {
	// Build and escape the URL.
	$url = esc_url( add_query_arg(
		array(
      'page' => 'cf7-email-filter'
    ),
    admin_url( 'options-general.php' )
	) );
	// Create the link.
	$settings_link = "<a href='$url'>" . __( 'Settings' ) . '</a>';
	// Adds the link to the end of the array.
	array_push(
		$links,
		$settings_link
	);
	return $links;
}