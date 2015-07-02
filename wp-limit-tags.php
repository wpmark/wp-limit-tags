<?php
/*
Plugin Name: WP Limit Tags
Description: This plugin allows you to limit the number of tags which can be added to a post from the post edit screen.
Version: 0.3
Author: Mark Wilkinson
Author URI: http://markwilkinson.me
License: GPLv2 or later

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

*/

/**
 * function wplt_on_activation()
 * adds options on activation
 */
function wplt_on_activation() {

    update_option( 'wplt_post_types', array() );
}

register_activation_hook( __FILE__, 'wplt_on_activation' );

/**
 * function wplt_get_max_tags()
 *
 * gets the maximum number of tags allowed
 * @param $default	int		the default number of tags to allow if none are returned - defaults to 5
 * @return 			int		max tags from options or the default if no max tags in options
 */
function wplt_get_max_tags( $default = 5 ) {
	
	/* get the max tags from options */
	$maxtags = get_option( 'wplt_max_tags' );
	
	/* check max tags are returned */
	if( empty( $maxtags ) ) {
		return $default;
	}

	/* returned the max tags from options */
	return $maxtags;
	
}

/**
 * Function wplt_register_settings()
 * Register the settings for this plugin.
 */
function wplt_register_default_settings() {
			
	/* register a setting for the username */
	register_setting( 'wplt_settings', 'wplt_max_tags','intval' );
	register_setting( 'wplt_settings', 'wplt_post_types' );
		
}

add_action( 'admin_init', 'wplt_register_default_settings' );

/**
 * function wplt_add_settings_menu()
 * adds the sub menu page for the wp limit tags settings
 */
function wplt_add_settings_menu() {
	
	add_submenu_page(
		'options-general.php',
		'WP Limit Tags',
		apply_filters( 'wplt_settings_menu_title', 'WP Limit Tags' ),
		apply_filters( 'wplt_set_max_tags_cap', 'manage_options' ),
		'wplt_settings',
		'wplt_admin_settings_content'
	);
	
}

add_action( 'admin_menu', 'wplt_add_settings_menu' );

/**
 * function wplt_admin_settings_content()
 * outputs the contents on the admin settings page
 */
function wplt_admin_settings_content() {
	
	?>
	
	<div class="wrap">
		
		<h2><?php echo apply_filters( 'wplt_settings_menu_title', 'WP Limit Tags' ); ?></h2>
		
		<?php
			
			/**
			 * @hook wplt_before_settings_page
			 */
			do_action( 'wplt_before_settings_page' );	
			
		?>
		
		<form method="post" action="options.php">
			
			<?php
								
				/* output settings field nonce action fields etc. */
				settings_fields( 'wplt_settings' );
			
			?>
			
			<table class="form-table">
							
				<tbody>
					
					<?php
			
						/**
						 * @hook wplt_before_settings_output
						 */
						do_action( 'wplt_before_settings_output' );	
						
					?>
					
					<tr valign="top">
						
						<th scope="row">
							<label for="wplt_max_tags">Max Allowed Tags per Post</label>
						</th>
						
						<td>
							<input type="number" name="wplt_max_tags" value="<?php echo esc_attr( wplt_get_max_tags() ); ?>" />
							<p class="description">Enter the maximum amount of tags each post is allowed to have assigned to it.</p>
						</td>
					
					</tr>
					
					<tr valign="top">
						
						<th scope="row">Enabled Post Types</th>
						
						<td>
							
							<?php
								
								/* create an array of post types for ignore */
								$ignore_post_types = apply_filters(
									'wplt_ignore_post_types',
									array(
										'attachment',
										'revision',
										'nav_menu_item',
									)
								);
								
								/* get the current saved post types */
								$saved_post_types = get_option( 'wplt_post_types' );
								
								/* get all the post types */
								$post_types = get_post_types( '', 'objects' ); 
								
								/* loop through each post type */
								foreach ( $post_types as $post_type ) {
									
									/* if we should be ignoring this post type - move on */
									if( in_array( $post_type->name, $ignore_post_types ) ) {
										continue;
									}
									
									/* get the post type name */
									$post_type_name = $post_type->labels->name;
									
									/* check whether this post type is in the current saved post types */
									if( in_array( $post_type->name, $saved_post_types ) ) {
										
										/* set this as checked */
										$checked = ' checked="checked"';
										
									} else {
										
										/* set checked to nothing */
										$checked = '';
										
									}
									
									?>
									<input type="checkbox" name="wplt_post_types[]" value="<?php echo esc_attr( $post_type->name ); ?>"<?php echo $checked; ?> />
									<label for="wplt_post_types"><?php echo esc_html( $post_type_name ); ?></label><br />
									<?php
								   
								} // end loop through post types
								
							?>
							
							<input type="hidden" name="wplt_post_types[]" value="default" />
							<p class="description">Tick which posts types tag limiting should be activated on.</p>
						</td>
					
					</tr>
					
					<?php
			
						/**
						 * @hook wplt_after_settings_output
						 */
						do_action( 'wplt_after_settings_output' );	
						
					?>
					
				</tbody>
				
			</table>
			
			<p class="submit">
				<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
			</p>
			
		</form>
		
		<?php
			
			/**
			 * @hook wplt_after_settings_page
			 */
			do_action( 'wplt_after_settings_page' );	
			
		?>
	
	</div>
	
	<?php
	
}

/**
 *
 */
function wplt_remove_quick_edit( $actions, $post ) {

	/* get the array of post type on which to limit the tags */
	$post_types = get_option( 'wplt_post_types' );
	
	/* check this post type is in the post types array */
	if( in_array( $post->post_type, $post_types ) ) {
		
		/* remove the inline action to remove quick editing */
		unset( $actions[ 'inline hide-if-no-js' ] );
		
	}
	
	return $actions;
	
}

add_filter( 'post_row_actions', 'wplt_remove_quick_edit', 10, 2 );

/**
 * function wplt_remove_edit_bulk_action()
 */
function wplt_remove_edit_bulk_action( $actions ) {
	
	/* check if a post type is set from the url */
	if( isset( $_GET[ 'post_type' ] ) ) {
		
		$post_type = $_GET[ 'post_type' ];
	
	/* no post type is set in url */
	} else {
		
		/* must be posts */
		$post_type = 'post';
		
	}
	
	/* get the array of post type on which to limit the tags */
	$post_types = get_option( 'wplt_post_types' );
	
	/* if this post type is in the post type array */
	if( in_array( $post_type, $post_types ) ) {
		
		/* remove the edit action */
		unset( $actions[ 'edit' ] );
		
	}
	
	return $actions;
	
}

add_filter( 'bulk_actions-edit-post', 'wplt_remove_edit_bulk_action' );

/**
 * function wplt_limit_tags_js()
 * outputs the javascript for the plugin to work
 */
function wplt_limit_tags_js() {
	
	/* get the current admin screen */
	$screen = get_current_screen();
	
	/* if the screen base is not post */
	if( $screen->base != 'post' ) {
		return;
	}
	
	/* get the array of post type on which to limit the tags */
	$post_types = get_option( 'wplt_post_types' );
	
	/* get this posts post type */
	$this_post_type = get_post_type( $_GET[ 'post' ] );
	
	/* check whether this posts post type is one we should be limiting */
	if( ! in_array( $this_post_type, $post_types ) ) {
		return;
	}
	
	/* get the max tags from options */
	$maxtags = wplt_get_max_tags();
	
	?>
	
	<script type="text/javascript">
		
		( function( $ ) {
			
			/* set the maximum number of tags allowed - pulled from options */
			var maxtags = <?php echo intval( $maxtags ); ?>;
			
			/**
			 * function disabletags()
			 * this hides the tags button and disables the input when max tags is reached
			 */
			function disbaletags() {
				$( "input.newtag" ).prop('disabled', true );
				$( ".tagadd" ).css( 'visibility', 'hidden' );
			}
			
			/**
			 * function disabletagsbutton()
			 * this hides the tags button when max tags is reached
			 */
			function disabletagsbutton() {
				$( ".tagadd" ).css( 'visibility', 'hidden' );
			}
			
			/**
			 * function showaddtagsbutton()
			 * this shows the tags button and enables the input when below max tags
			 */
			function showaddtagsbutton() {
				$( "input.newtag" ).prop('disabled', false );
				$( ".tagadd" ).css( 'visibility', 'visible' );	
			}
			
			/**
			 * here we are checking for DOM changes within the tagchecklist element
			 * we a change is detected we run either hide or show
			 * depending on whether the change is adding or removing an element
			 * we also count number of tags added to the input and disable if more than max tags
			 */
			$(document).ready( function() {
				$( '.tagchecklist' ).bind( "DOMSubtreeModified", function() {
					var count = $(".tagchecklist > span").length;
					if( count >= maxtags ) {
						disbaletags();
					} else {
						showaddtagsbutton();
					}
				});
				
				/* count tags as tying in input */
				$( "input.newtag" ).bind("keyup", function() {
					var tags = $( "input.newtag" ).val();
					var tagscount = tags.split( ',' ).length;
					if( tagscount > maxtags ) {
						disabletagsbutton();
					} else {
						showaddtagsbutton();
					}
				});
				
			});
			
		} )( jQuery );
		
	</script>
	
	<?php
	
}

add_action( 'admin_print_scripts', 'wplt_limit_tags_js', 20 );