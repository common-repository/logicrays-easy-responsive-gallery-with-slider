<?php
/**
 * Plugin Name: Logicrays Easy Responsive Gallery with Slider
 * Version: 1.0
 * Description: Logicrays Easy Responsive Gallery with Slider allow you to add unlimited images galleries with box, stroke, flow of effetcs
 * Author: Logicrays
 */
 
define("lergs-responsive-gallery","lergs_responsive_gallery" );
define('lergs_plugin_url', plugins_url('', __FILE__));
ini_set('allow_url_fopen',1);

// Image Croping 
add_image_size( 'lergs_12_thumb', 500, 9999, array( 'center', 'top') );
add_image_size( 'lergs_346_thumb', 400, 9999, array( 'center', 'top') );
add_image_size( 'lergs_12_same_size_thumb', 500, 500, array( 'center', 'top') );
add_image_size( 'lergs_346_same_size_thumb', 400, 400, array( 'center', 'top') );
/**
 * Admin menu
 */
add_action('admin_menu' , 'lergs_settings_page');
function lergs_settings_page() {
	 add_submenu_page(
        'edit.php?post_type=lergs_gallery',
        __('Settings', lergs-responsive-gallery),
        __('Settings', lergs-responsive-gallery),
        'manage_options',
        'lergs-setting-page',
       'lergs_setting_page');
}
include_once 'includes/class-lergs.php';
function lergs_setting_page(){?>
<div class="wrap">
<div class="icon32" id="icon-options-general"><br>
</div>
<h2>Logicrays Easy Responsive Gallery with Slider Options</h2>
<form action="options.php" method="post">
<?php
settings_fields("section");
?>
<?php
do_settings_sections("gallery-options");
submit_button();
?>
</form>
</div>
<?php
}
add_action("admin_init", "lergs_responsive_gallery_fields");
function lergs_responsive_gallery_fields()
{
	add_settings_section("section", "All Settings", null, "gallery-options");	
	add_settings_field("lergs_image_label", "Show Image Label", "lergs_image_label_element", "gallery-options", "section");
	add_settings_field("lergs_hover_animation", "Image Hover Animation", "lergs_hover_animation_element", "gallery-options", "section");
	add_settings_field("lergs_layout_type", "Gallery Layout Type", "lergs_layout_type_element", "gallery-options", "section");
	add_settings_field("lergs_thumbnail_layout", "Thumbnail Layout", "lergs_thumbnail_layout_element", "gallery-options", "section");
	add_settings_field("lergs_hover_color", "Hover Color", "lergs_hover_color_element", "gallery-options", "section");
	add_settings_field("lergs_hover_text_color", "Hover Text Color", "lergs_hover_text_color_element", "gallery-options", "section");
	add_settings_field("lergs_gallry_custom_css", "Custom css", "lergs_gallry_custom_css_element", "gallery-options", "section");
	add_settings_field("lergs_slider_play", "Slider Autoplay", "lergs_slider_play_element", "gallery-options", "section");
	
	register_setting("section", "lergs_hover_animation");
	register_setting("section", "lergs_image_label");
	register_setting("section", "lergs_layout_type");
	register_setting("section", "lergs_thumbnail_layout");
	register_setting("section", "lergs_hover_color");
	register_setting("section", "lergs_hover_text_color");
	register_setting("section", "lergs_gallry_custom_css");
	register_setting("section", "lergs_slider_play");
}
function lergs_hover_animation_element()
{
$options = get_option('lergs_hover_animation');
?>
<select id="lergs_hover_animation" name='lergs_hover_animation[lergs_hover_animation]'>
<option value='stroke' <?php selected( $options['lergs_hover_animation'], 'stroke' ); ?>><?php _e( 'Stroke', lergs-responsive-gallery); ?></option>
<option value='flow' <?php selected( $options['lergs_hover_animation'], 'flow' ); ?>><?php _e( 'Flow', lergs-responsive-gallery); ?></option>
<option value='box' <?php selected( $options['lergs_hover_animation'], 'box' ); ?>><?php _e( 'Box', lergs-responsive-gallery); ?></option>
</select>
<p class="description"><?php _e( 'Choose an animation effect apply on images after mouse hover.' ); ?></p>
<?php
}
function lergs_image_label_element(){
$options = get_option('lergs_image_label');
?>
<select id="lergs_image_label" name='lergs_image_label[lergs_image_label]'>
<option value='yes' <?php selected( $options['lergs_image_label'], 'yes' ); ?>><?php _e( 'Yes', lergs-responsive-gallery); ?></option>
<option value='no' <?php selected( $options['lergs_image_label'], 'no' ); ?>><?php _e( 'No', lergs-responsive-gallery ); ?></option>
</select>
<?php
}
function lergs_layout_type_element()
{
$options = get_option('lergs_layout_type');
?>
<select id="lergs_layout_type" name='lergs_layout_type[lergs_layout_type]'>
<option value='col-md-6' <?php selected( $options['lergs_layout_type'], 'col-md-6' ); ?>><?php _e( 'Two Column', lergs-responsive-gallery); ?></option>
<option value='col-md-4' <?php selected( $options['lergs_layout_type'], 'col-md-4' ); ?>><?php _e( 'Three Column', lergs-responsive-gallery ); ?></option>
<option value='col-md-3' <?php selected( $options['lergs_layout_type'], 'col-md-3' ); ?>><?php _e( 'Four Column', lergs-responsive-gallery ); ?></option>
</select>
<p class="description"><?php _e( 'Choose a column layout for image gallery.'); ?></p>
<?php
}
function lergs_thumbnail_layout_element()
{
$options = get_option('lergs_thumbnail_layout');
?>
<select id="lergs_thumbnail_layout" name='lergs_thumbnail_layout[lergs_thumbnail_layout]'>
<option value='same-size' <?php selected( $options['lergs_thumbnail_layout'], 'same-size' ); ?>><?php _e( 'Same size thumbnials', lergs-responsive-gallery); ?></option>
<option value='original' <?php selected( $options['lergs_thumbnail_layout'], 'original' ); ?>><?php _e( 'Orignal image as thumbnials Column', lergs-responsive-gallery ); ?></option>
</select>
<p class="description"><?php _e( 'Select an option for thumbnail layout setting.'); ?></p>
<?php }
function lergs_hover_color_element()
{
$options = get_option('lergs_hover_color');
?>
<input type="text" name="lergs_hover_color[button_color]" id="lergs_hover_color" class="color-field" value="<?php echo esc_attr( $options['button_color'] ); ?>" />
<p class="description"><?php _e( 'Choose a Image Hover Color.' ); ?></p>
<?php
}
function lergs_hover_text_color_element()
{
$options = get_option('lergs_hover_text_color');
?>
<input type="text" name="lergs_hover_text_color[button_color]" id="lergs_hover_text_color" class="color-field" value="<?php echo esc_attr( $options['button_color'] ); ?>" />
<p class="description"><?php _e( 'Choose a Image Hover Text Color.' ); ?></p>
<?php
}
function lergs_gallry_custom_css_element()
{
$options = get_option('lergs_gallry_custom_css');
?>
<textarea id="lergs_gallry_custom_css" name="lergs_gallry_custom_css" class="" style="width:60%" placeholder=".test{ font-size:16px;}"><?php echo $options; ?></textarea>
<p class="description"><?php _e( 'Enter any custom css you want to apply on this gallery.
Note: Please Do Not Use Style Tag With Custom CSS.' ); ?></p>
<?php
}
function lergs_slider_play_element(){
$options = get_option('lergs_slider_play');
?>
<select id="lergs_slider_play" name='lergs_slider_play[lergs_slider_play]'>
<option value='true' <?php selected( $options['lergs_slider_play'], 'true' ); ?>><?php _e( 'Yes', lergs-responsive-gallery); ?></option>
<option value='false' <?php selected( $options['lergs_slider_play'], 'false' ); ?>><?php _e( 'No', lergs-responsive-gallery ); ?></option>
</select>
<?php
}