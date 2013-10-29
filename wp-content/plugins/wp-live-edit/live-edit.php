<?php
/*
	Plugin Name: WP Live Edit
	Plugin URI: http://wordpress.org/extend/plugins/wp-live-edit
	Description: WP Live Edit is a Wordpress plugin that allows you to edit the content and blog posts live on the site. Without going back and forth between the admin panel and the site. Completely WYSIWYG editing.
	Version: 1.1
	Author: Ole-Kenneth Rangnes
	Author URI: http://olekenneth.com
*/

class liveEdit {

	private $title_class,
	$content_class,
	$post_id,
	$prefix = "liveEdit_";


	function liveEditAddScripts() {
		wp_enqueue_script( 'live-edit', plugins_url('/js/live-edit.js', __FILE__), array('jquery') );
		wp_register_style( 'live-edit', plugins_url('/css/live-edit.css', __FILE__) );
		wp_enqueue_style( 'live-edit' );
	}

	function liveEditAddjQueryDocumentReady() {
		$id = $this->post_id;
		$url = plugins_url('/live-edit.php?save=y', __FILE__);
		$nonce = wp_create_nonce('DBzFAMJ');

		echo <<<EOF
<script>
	jQuery(function() {
		jQuery("{$this->title_class}").editable({ field: 'post_title', post_id: {$id}, url: '{$url}', nonce: '{$nonce}' });
		jQuery("{$this->content_class}").editable({ field: 'post_content', post_id: {$id}, url: '{$url}', nonce: '{$nonce}'});
	});
</script>
EOF;
	}

	function liveEditFooter() {
		global $wp_query;
		$this->post_id = $wp_query->post->ID;

		if ( ( is_single() || is_page() ) && current_user_can('edit_post', $this->post_id) ) {
			add_action('wp_enqueue_scripts', array(&$this, 'liveEditAddScripts'));
			add_action('wp_print_footer_scripts', array(&$this, 'liveEditAddjQueryDocumentReady'));
		}
	}

	function liveEdit_add_options_page() {

		if (!current_user_can('manage_options'))
		{
			wp_die( __('You do not have sufficient permissions to access this page.') );
		}

		$hidden_field_name = $this->prefix . 'submit_hidden';

		if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
			$this->title_class   = $_POST[$this->prefix . "title_class"];
			$this->content_class = $_POST[$this->prefix . "content_class"];

			update_option( $this->prefix . "title_class",   $this->title_class );
			update_option( $this->prefix . "content_class", $this->content_class );

			echo "<div class=\"updated\"><p><strong>" . __('Saved', 'liveEdit' ) ."</strong></p></div>";
		}
?>
		<div class="wrap"><h2><?php echo __( 'Live Edit settings', 'liveEdit' ); ?></h2>
		<form name="form1" method="post" action="">
		<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
		<p><?php _e("Title class:", 'liveEdit' ); ?>
		<input type="text" name="<?php echo $this->prefix . "title_class"; ?>" value="<?php echo $this->title_class; ?>"/> (twenty eleven: .entry-title, remember the dot)
		</p>
		<p><?php _e("Content class:", 'liveEdit' ); ?>
		<input type="text" name="<?php echo $this->prefix . "content_class"; ?>" value="<?php echo $this->content_class; ?>"/> (twenty eleven: .entry-content, remember the dot)
		</p>
		<hr />
		<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
		</p>
		</form>
		</div>
		<?php
	}

	function liveEdit_admin_menu() {
		add_options_page(__('liveEdit','liveEdit'), __('Live Edit','liveEdit'), 'manage_options', 'liveEdit', array(&$this, 'liveEdit_add_options_page'));
	}


	function liveEdit() {
		$this->title_class   = get_option($this->prefix . "title_class");
		$this->content_class  = get_option($this->prefix . "content_class");

		if (empty($this->title_class)) {
			$this->title_class = ".entry-title";
			update_option( $this->prefix . "title_class",  $this->title_class );
		}
		if (empty($this->content_class)) {
			$this->content_class = ".entry-content";
			update_option( $this->prefix . "content_class",  $this->content_class );
		}

		add_action('wp', array(&$this, 'liveEditFooter'));
		add_action('admin_menu', array(&$this, 'liveEdit_admin_menu'));
	}
}

if (isset($_GET['save'])) {
	require_once("../../../wp-config.php");

	if (! wp_verify_nonce($_POST['nonce'], 'DBzFAMJ') || !current_user_can('edit_post', $_POST['id']) ) {
		header("HTTP/1.0 401 Unauthorized");
		die(json_encode(array("success" => false, "error" => "Unauthorized")));
	}

	header("Content-type: application/json");
	if (isset($_POST['field'])) {
		$insert = wp_update_post(array(
				'ID'   => $_POST['id'],
				$_POST['field'] => $_POST['content'],
			));
	}

	echo json_encode(array("success" => true, $_POST['field'] => $_POST['content'], "post_id" => $insert));
	exit;
} else {
	new liveEdit();	
}