<?php

add_action( 'admin_init', 'wppf_options_init' );
function wppf_options_init()
{
	register_setting( 'wppf_options', 'wppf_options', 'wppf_sanitize_callback' );
	register_setting( 'wppf_options_two', 'wppf_options_two', 'wppf_sanitize_callback_two' );
}

function wppf_settings_page()
{
?>
	<div class="wrap">
		<h2><?php _e( 'WPPF Settings', 'wppf' ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'wppf_options' ); ?>
			<?php $options = get_option( 'wppf_options' ); ?>
			<p>
				<label for="wppf-text"><?php _e( 'WPPF Text Input:', 'wppf' ); ?></label>
				<input type="text" id="wppf-text" name="wppf_options[wppf_text]" value="<?php echo $options['wppf_text']; ?>">
			</p>
			<p>
				<label for="wppf-checkbox"><?php _e( 'WPPF Checkbox:', 'wppf' ); ?></label>
				<input type="checkbox" id="wppf-checkbox" name="wppf_options[wppf_checkbox]" value="1" <?php checked( $options['wppf_checkbox'], 1 ); ?> />
			</p>
			<p>
				<label for="wppf-select"><?php _e( 'WPPF Select Input:', 'wppf' ); ?></label>
				<select id="wppf-select" name="wppf_options[wppf_select]">
					<option value="one"<?php echo ( $options['wppf_select'] == 'one' ) ? ' selected="selected"' : ''; ?>><?php _e('Option One', 'wppf'); ?></option>
					<option value="two"<?php echo ( $options['wppf_select'] == 'two' ) ? ' selected="selected"' : ''; ?>><?php _e('Option Two', 'wppf'); ?></option>
					<option value="three"<?php echo ( $options['wppf_select'] == 'three' ) ? ' selected="selected"' : ''; ?>><?php _e('Option Three', 'wppf'); ?></option>
				</select>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
			</p>
		</form>
	</div> <!-- .wrap -->
<?php
}

function wppf_sanitize_callback( $options )
{
	$options['wppf_text'] = strip_tags( $options['wppf_text'] );
	$options['wppf_checkbox'] = ( ( !empty( $options['wppf_checkbox'] ) && $options['wppf_checkbox'] ) == 1 ? 1 : 0 );
		
	return $options;
}

function wppf_settings_page_two()
{
?>
	<div class="wrap">
		<h2><?php _e( 'WPPF Settings 2', 'wppf' ); ?></h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'wppf_options_two' ); ?>
			<?php $options = get_option( 'wppf_options_two' ); ?>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options') ?>" />
			</p>
		</form>
	</div> <!-- .wrap -->
<?php
}

function wppf_sanitize_callback_two( $options )
{		
	return $options;
}

//end lib/admin/settings.php