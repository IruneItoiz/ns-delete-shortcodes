<?php
/***
 *
 * Class to process the post and remove the shortcodes from the content
 *
 *
 */
namespace Northset\Delete_Shortcodes;

class Shortcode_Remover {

	private $shortcode;

	public function remove_shortcode( $content ) {
		$content = preg_replace( "~(?:\[/?)" . $this->shortcode . "[^/\]]*?\]~i", '', $content );

		return $content;
	}

	public function get_posts() {
		global $wpdb;

		return $wpdb->get_results( "SELECT ID FROM {$wpdb->posts} WHERE (post_status != 'auto-draft' AND post_status != 'inherit' ) AND post_content LIKE '%[" . $this->shortcode . "%'", ARRAY_N );
	}

	public function run( $shortcode ) {
		$this->shortcode = trim( $shortcode );

		$posts = $this->get_posts();

		$total = count($posts);
		if (0 === $total)
		{
			echo 'That shortcode was not found on any posts<br>';
		} else {
			echo 'Updating '.$total.' posts.<br>';
		}
		foreach ( $posts as $post_id ) {
			$post = get_post( $post_id[0] );

			$post->post_content = $this->remove_shortcode( $post->post_content );

			if ( wp_update_post( $post ) ) {
				echo 'Post '.$post->ID.' has been updated: <a href="'.get_edit_post_link( $post->ID ).'">Edit</a><br>';
			} else {
				echo 'Post '.$post->ID.' failed to update<br>';
			}
		}
	}
}
