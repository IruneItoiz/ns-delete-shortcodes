<?php
/***
 * Settings page
 */


namespace Northset\Delete_Shortcodes;


class Settings {

	function init()
	{
		add_action('admin_menu', [$this, 'add_admin_page']);
	}
	/**
	 * Create the admin page.
	 */
	function add_admin_page() {
		add_management_page('Delete Shortcodes', 'Delete Shortcodes', 'administrator', 'admin-shortcode-delete', [$this, 'admin_shortcode_delete_page']);
	}


	function admin_shortcode_delete_page() {
		$admin_commands = [];
		?>
		<div class="wrap">
			<h1>Admin Commands</h1>
			<?php
			if (isset($_GET['delete'])) {
				//Verify nonce


				$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce']  : '';
				if ( ! wp_verify_nonce( $nonce, 'delete-nonce' ) || !current_user_can('administrator')) {
					// This nonce is not valid or the user is not an admin.
					die( 'You are not allowed to perform this action' );
				} else {
					$shortcode = sanitize_text_field($_REQUEST['shortcode']);

					$shortcode_remover = new Shortcode_Remover();
					$shortcode_remover->run($shortcode);
				}
 			} else {
				?>
				<div class="update-nag notice">
					<p><strong>Warning!</strong> Please back-up your content before running this.</p>
					<p>Running this will <strong>permanently</strong> delete the shortcode from the post content.</p>
				</div>
				<div class="card">
					<form name="shortcode-delete">
						<p>Enter the shortcode to delete. Do not include [ or ] or attributes</p>
						<input type="text" value="" name="shortcode">
						<button class="button-primary alignright" type="submit" name="delete">Delete</button>
						<input type="hidden" name="page" value="admin-shortcode-delete">
						<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'delete-nonce' );?>">
					</form>
				</div>

				<?php foreach ($admin_commands as $command_slug => $command): ?>
					<div class="card">
						<h2 class="alignleft"><?php echo $command->name; ?></h2>
						<a href="<?php echo esc_attr(admin_url('tools.php?page=admin-shortcode-delete&run-command=' . $command_slug)); ?>"
						   class="button-primary alignright" style="margin-top: 10px">Run</a>
						<div class="clear"></div>
					</div>
				<?php endforeach; ?>

				<?php
			}
			?>
		</div>
		<?php
	}
}