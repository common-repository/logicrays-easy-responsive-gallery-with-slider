<?php
function lergs_shortcodedetect() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('lergs-hover-pack',lergs_plugin_url.'/js/hover-pack.js', array('jquery'));
    wp_enqueue_script('lergs-fancybox', lergs_plugin_url.'/js/jquery.fancybox.min.js');
	
    wp_enqueue_style('lergs-hover-pack', lergs_plugin_url.'/css/hover-pack.css');
    wp_enqueue_style('lergs-boot-strap', lergs_plugin_url.'/css/bootstrap.css');
    wp_enqueue_style('lergs-img-gallery', lergs_plugin_url.'/css/img-gallery.css');
	wp_enqueue_style('lergs-fancybox-min', lergs_plugin_url.'/css/jquery.fancybox.min.css');	
	wp_enqueue_style('lergs-font-awesome-front', lergs_plugin_url.'/css/font-awesome-latest/css/font-awesome.min.css');
	wp_enqueue_style('owrgf-owl-carousel-min', lergs_plugin_url.'/css/owl.carousel.min.css');
	wp_enqueue_style('owrgf-owl-theme-default-min', lergs_plugin_url.'/css/owl.theme.default.min.css');	
	
	wp_enqueue_script('owrgf_owl_carousel', lergs_plugin_url.'/js/owl.carousel.min.js', array('jquery') );

 }
add_action( 'wp', 'lergs_shortcodedetect' );

class lergs{
	private static $instance;
    private $admin_thumbnail_size = 150;
    private $thumbnail_size_w = 150;
    private $thumbnail_size_h = 150;
	var $counter;

    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }	
	private function __construct() {
		$this->counter = 0;
        add_action('admin_print_scripts-post.php', array(&$this, 'lergs_admin_print_scripts'));
        add_action('admin_print_scripts-post-new.php', array(&$this, 'lergs_admin_print_scripts'));
        add_image_size('lergs_gallery_admin_thumb', $this->admin_thumbnail_size, $this->admin_thumbnail_size, true);
        add_image_size('lergs_gallery_thumb', $this->thumbnail_size_w, $this->thumbnail_size_h, true);
        
		add_action( 'admin_enqueue_scripts', array(&$this,'lergs_backend_scripts'));
		
		add_shortcode('lergsgallery', array(&$this, 'shortcode'));
        if (is_admin()) {
			add_action('init', array(&$this, 'lergs_register_cpt_function'), 1);
			add_action('add_meta_boxes', array(&$this, 'add_all_lergs_meta_boxes'));
			add_action('admin_init', array(&$this, 'add_all_lergs_meta_boxes'), 1);			
			add_action('save_post', array(&$this, 'lergs_add_image_meta_box_save'), 9, 1);
			add_action('wp_ajax_lergsgallery_get_thumbnail', array(&$this, 'ajax_get_thumbnail_lergs'));
		}
    }	
	//JS & CSS
	public function lergs_admin_print_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('lergs-media-uploader-js', lergs_plugin_url . '/js/lergs-multiple-media-uploader.js', array('jquery'));
		
		wp_enqueue_media();
		wp_enqueue_style('lergs-meta-css', lergs_plugin_url.'/css/lergs-meta.css');
		wp_enqueue_style('lergs-font-awesome', lergs_plugin_url.'/css/font-awesome-latest/css/font-awesome.min.css');
    }
	public function lergs_backend_scripts(){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker', false, array ( 'jquery' ) );
		wp_enqueue_script( 'color-picker-custom', lergs_plugin_url.'/js/color-picker.js', true);
	}	
	// Register Custom Post Type
	public function lergs_register_cpt_function() {
		$labels = array(
			'name' => _x( 'Logicrays Easy Responsive Gallery with Slider', lergs-responsive-gallery),
			'singular_name' => _x( 'LERGS Gallery', lergs-responsive-gallery),
			'add_new' => _x( 'Add New Gallery', lergs-responsive-gallery),
			'add_new_item' => _x( 'Add New Gallery', lergs-responsive-gallery),
			'edit_item' => _x( 'Edit Photo Gallery', lergs-responsive-gallery),
			'new_item' => _x( 'New Gallery', lergs-responsive-gallery),
			'view_item' => _x( 'View Gallery', lergs-responsive-gallery),
			'search_items' => _x( 'Search Galleries', lergs-responsive-gallery),
			'not_found' => _x( 'No galleries found', lergs-responsive-gallery ),
			'not_found_in_trash' => _x( 'No galleries found in Trash', lergs-responsive-gallery ),
			'parent_item_colon' => _x( 'Parent Gallery:', lergs-responsive-gallery ),
			'all_items' => __( 'All Galleries', lergs-responsive-gallery ),
			'menu_name' => _x( 'LERGS Gallery', lergs-responsive-gallery ),
		);
		$args = array(
			'labels' => $labels,
			'hierarchical' => false,
			'supports' => array( 'title' ),
			'public' => false,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_position' => 10,
			'menu_icon' => 'dashicons-format-image',
			'show_in_nav_menus' => false,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => false,
			'capability_type' => 'post'
		);
        register_post_type( 'lergs_gallery', $args );
        add_filter( 'manage_edit-lergs_gallery_columns', array(&$this, 'lergs_gallery_columns' )) ;
        add_action( 'manage_lergs_gallery_posts_custom_column', array(&$this, 'lergs_gallery_manage_columns' ), 10, 2 );
	}	
	function lergs_gallery_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'LERGS Gallery' ),
            'shortcode' => __( 'LERGS Gallery Shortcode' ),
			'shortcode_owl' => __( 'Slider Gallery Shortcode' ),
            'date' => __( 'Date' )
        );
        return $columns;
    }
    function lergs_gallery_manage_columns( $column, $post_id ){
        global $post;
        switch( $column ) {
          case 'shortcode' :
            echo '<input type="text" value="[LERGS id='.$post_id.']" readonly="readonly" />';
            break;
		  case 'shortcode_owl' :
            echo '<input type="text" value="[OWLERGS id='.$post_id.']" readonly="readonly" />';
            break;
          default :
            break;
        }
    }
	
	public function add_all_lergs_meta_boxes() {
		add_meta_box( __('Add Images', lergs-responsive-gallery), __('Add Images', lergs-responsive-gallery), array(&$this, 'lergs_generate_add_image_meta_box_function'), 'lergs_gallery', 'normal', 'low' );
		add_meta_box ( __('Logicrays Easy Responsive Gallery with Slider Shortcode', lergs-responsive-gallery), __('Logicrays Easy Responsive Gallery with Slider Shortcode', lergs-responsive-gallery), array(&$this, 'lergs_shotcode_meta_box_function'), 'lergs_gallery', 'side', 'low');
    }
	public function lergs_generate_add_image_meta_box_function($post) { ?>
		<div id="lergsgallery_container">
			<input id="lergs_delete_all_button" class="button" type="button" value="Delete All" rel="">
			<input type="hidden" id="lergs_wl_action" name="lergs_wl_action" value="lergs-save-settings">
            <ul id="lergs_gallery_thumbs" class="clearfix">
				<?php
				$lergs_AllPhotosDetails = unserialize(base64_decode(get_post_meta( $post->ID, 'lergs_all_photos_details', true)));
				$TotalImages =  get_post_meta( $post->ID, 'lergs_total_images_count', true );
				if($TotalImages) {
					foreach($lergs_AllPhotosDetails as $lergs_SinglePhotoDetails) {
						$name = $lergs_SinglePhotoDetails['lergs_image_label'];
						$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
						$url = $lergs_SinglePhotoDetails['lergs_image_url'];
						$url1 = $lergs_SinglePhotoDetails['lergs_12_thumb'];
						$url2 = $lergs_SinglePhotoDetails['lergs_346_thumb'];
						$url3 = $lergs_SinglePhotoDetails['lergs_12_same_size_thumb'];
						$url4 = $lergs_SinglePhotoDetails['lergs_346_same_size_thumb'];
						?>
						<li class="lergs-image-entry" id="lergs_img">
							<a class="gallery_remove lergsgallery_remove" href="#lergs_gallery_remove" id="lergs_remove_bt" >
                            <img src="<?php echo lergs_plugin_url.'/images/Close-icon.png'; ?>" /></a>
							<img src="<?php echo $url; ?>" class="lergs-meta-image" alt=""  style="">
							<input type="text" id="lergs_image_label[]" name="lergs_image_label[]" value="<?php echo  htmlentities($name); ?>" placeholder="Enter Image Label" class="lergs_label_text">
							<input type="text" id="lergs_image_url[]" name="lergs_image_url[]" class="lergs_label_text"  value="<?php echo  $url; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="lergs_image_url1[]" name="lergs_image_url1[]" class="lergs_label_text"  value="<?php echo  $url1; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="lergs_image_url2[]" name="lergs_image_url2[]" class="lergs_label_text"  value="<?php echo  $url2; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="lergs_image_url3[]" name="lergs_image_url3[]" class="lergs_label_text"  value="<?php echo  $url3; ?>"  readonly="readonly" style="display:none;" />
							<input type="text" id="lergs_image_url4[]" name="lergs_image_url4[]" class="lergs_label_text"  value="<?php echo  $url4; ?>"  readonly="readonly" style="display:none;" />
						</li>
						<?php
					} // end of foreach
				} else {
					$TotalImages = 0;
				}
				?>
                
            </ul>
        </div>
		<div class="lergs-image-entry add_lergs_new_image" id="lergs_gallery_upload_button" data-uploader_title="Upload Image" data-uploader_button_text="Select" >
		<div class="dashicons dashicons-plus"></div>
		<p>
		<?php _e('Add New Image', lergs-responsive-gallery); ?>
		</p>
		</div>
		<div style="clear:left;"></div>
        <?php
    }	
	public function lergs_shotcode_meta_box_function() { ?>
		<p><?php _e("Use below shortcode in any Page/Post to publish your gallery", lergs-responsive-gallery);?></p>
		<input readonly="readonly" type="text" value="<?php echo "[LERGS id=".get_the_ID()."]"; ?>">
        <p><?php _e("Use below shortcode in any Page/Post to publish your slider gallery", lergs-responsive-gallery);?></p>
		<input readonly="readonly" type="text" value="<?php echo "[OWLERGS id=".get_the_ID()."]"; ?>">
		<?php 
	}	
	public function admin_thumb($id) {
        $image  = wp_get_attachment_image_src($id, 'lergsgallery_admin_medium', true);
        $image1 = wp_get_attachment_image_src($id, 'lergs_12_thumb', true);
        $image2 = wp_get_attachment_image_src($id, 'lergs_346_thumb', true);
        $image3 = wp_get_attachment_image_src($id, 'lergs_12_same_size_thumb', true);
        $image4 = wp_get_attachment_image_src($id, 'lergs_346_same_size_thumb', true);
		$UniqueString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
        ?>
		<li class="lergs-image-entry" id="lergs_img">
			<a class="gallery_remove lergsgallery_remove" href="#lergs_gallery_remove" id="lergs_remove_bt" ><img src="<?php echo lergs_plugin_url.'/images/Close-icon.png'; ?>" /></a>
			<img src="<?php echo $image[0]; ?>" class="lergs-meta-image" alt=""  style="">
			<input type="text" id="lergs_image_label[]" name="lergs_image_label[]" placeholder="Enter Image Label" value="" class="lergs_label_text">			
			<input type="text" id="lergs_image_url[]"  name="lergs_image_url[]"  class="lergs_label_text"  value="<?php echo $image[0]; ?>" readonly="readonly" style="display:none;" />
			<input type="text" id="lergs_image_url1[]" name="lergs_image_url1[]" class="lergs_label_text"  value="<?php echo $image1[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="lergs_image_url2[]" name="lergs_image_url2[]" class="lergs_label_text"  value="<?php echo $image2[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="lergs_image_url3[]" name="lergs_image_url3[]" class="lergs_label_text"  value="<?php echo $image3[0]; ?>"  readonly="readonly" style="display:none;" />
			<input type="text" id="lergs_image_url4[]" name="lergs_image_url4[]" class="lergs_label_text"  value="<?php echo $image4[0]; ?>"  readonly="readonly" style="display:none;" />
		</li>
        <?php
    }
	public function ajax_get_thumbnail_lergs() {
        echo $this->admin_thumb($_POST['imageid']);
        die;
    }

    public function lergs_add_image_meta_box_save($PostID) {
	if(isset($PostID) && isset($_POST['lergs_wl_action'])) {
			$TotalImages = count($_POST['lergs_image_url']);
			$ImagesArray = array();
			if($TotalImages) {
				for($i=0; $i < $TotalImages; $i++) {
					$image_label =  stripslashes($_POST['lergs_image_label'][$i]);
					
					$url  = sanitize_text_field($_POST['lergs_image_url'][$i]);
					$url1 = sanitize_text_field($_POST['lergs_image_url1'][$i]);
					$url2 = sanitize_text_field($_POST['lergs_image_url2'][$i]);
					$url3 = sanitize_text_field($_POST['lergs_image_url3'][$i]);
					$url4 = sanitize_text_field($_POST['lergs_image_url4'][$i]);
					
					$ImagesArray[] = array(
						'lergs_image_label' => $image_label,
						'lergs_image_url' => $url,
						'lergs_12_thumb' => $url1,
						'lergs_346_thumb' => $url2,
						'lergs_12_same_size_thumb' => $url3,
						'lergs_346_same_size_thumb' => $url4
					);
				}
				update_post_meta($PostID, 'lergs_all_photos_details', base64_encode(serialize($ImagesArray)));
				update_post_meta($PostID, 'lergs_total_images_count', $TotalImages);
			} else {
				$TotalImages = 0;
				update_post_meta($PostID, 'lergs_total_images_count', $TotalImages);
				$ImagesArray = array();
				update_post_meta($PostID, 'lergs_all_photos_details', base64_encode(serialize($ImagesArray)));
			}
		}
    }
}
global $lergs;
$lergs = lergs::forge();

require_once("lergs-responsive-gallery-shortcode.php");

if(!function_exists('lergs_hex2rgb')) {
    function lergs_hex2rgb($hex) {
       $hex = str_replace("#", "", $hex);

       if(strlen($hex) == 3) {
          $r = hexdec(substr($hex,0,1).substr($hex,0,1));
          $g = hexdec(substr($hex,1,1).substr($hex,1,1));
          $b = hexdec(substr($hex,2,1).substr($hex,2,1));
       } else {
          $r = hexdec(substr($hex,0,2));
          $g = hexdec(substr($hex,2,2));
          $b = hexdec(substr($hex,4,2));
       }
       $rgb = array($r, $g, $b);
       return $rgb;
    }
}

add_action('media_buttons_context', 'add_lergs_custom_button');
add_action('admin_footer', 'add_lergs_popup_content');

function add_lergs_custom_button($context) {
  $img = lergs_plugin_url.'/images/Photos-icon.png';
  $container_id = 'lergs';
  $title = 'Select Logicrays Easy Responsive Gallery to insert into post/pages';
  $context .= '<a class="button button-primary thickbox" title="Select Logicrays Easy Responsive Gallery to insert into post/pages" href="#TB_inline?width=400&inlineId='.$container_id.'">
  <span class="wp-media-buttons-icon" style="background: url('.$img.'); background-repeat: no-repeat; background-position: left bottom;"></span>
  Logicrays Easy Responsive Gallery
  </a>';
  return $context;
}

function add_lergs_popup_content() {
	?>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#lergsgalleryinsert').on('click', function() {
			var id = jQuery('#lergs-gallery-select option:selected').val();
			window.send_to_editor('<p>[LERGS id=' + id + ']</p>');
			tb_remove();
		})
		jQuery('#lergssliderinsert').on('click', function() {
			var id = jQuery('#lergs-gallery-select option:selected').val();
			window.send_to_editor('<p>[OWLERGS id=' + id + ']</p>');
			tb_remove();
		})
	});
	</script>
	<div id="lergs" style="display:none;">
	  <h3>Select Logicrays Easy Responsive Gallery with Slider to insert into post/pages</h3>
	  <?php 
		$all_posts = wp_count_posts( 'lergs_gallery')->publish;
		$args = array('post_type' => 'lergs_gallery', 'posts_per_page' =>$all_posts);
		global $lergs_galleries;
		$lergs_galleries = new WP_Query( $args );			
		if( $lergs_galleries->have_posts() ) { ?>
        	<label>Select Gallery: </label>
			<select id="lergs-gallery-select"> <?php
				while ( $lergs_galleries->have_posts() ) : $lergs_galleries->the_post();  ?>
				<option value="<?php echo get_the_ID(); ?>"><?php the_title(); ?></option>
				<?php endwhile; ?>
			</select>
			<button class='button primary' id='lergsgalleryinsert'>Insert Gallery Shortcode</button>
            <button class='button primary' id='lergssliderinsert'>Insert Slider Gallery Shortcode</button>
			<?php
		} else {
			_e("No Gallery Found", lergs-responsive-gallery); 
		} ?>
	</div>
	<?php
}