<?php
add_shortcode( 'LERGS', 'lergs_ShortCode_Page' );
function lergs_ShortCode_Page( $Id ) {
    ob_start();	
	if(isset($Id['id'])) {
		$lergs_image_label = get_option('lergs_image_label');
		$lergs_show_image_label = $lergs_image_label['lergs_image_label'];
		$lergs_hover_animation = get_option('lergs_hover_animation');
		$lergs_hover_animation = $lergs_hover_animation['lergs_hover_animation'];
		$lergs_layout_type = get_option('lergs_layout_type');
		$lergs_layout_type = $lergs_layout_type['lergs_layout_type'];
		
		$lergs_thumbnail_layout = get_option('lergs_thumbnail_layout');
		$lergs_thumbnail_layout = $lergs_thumbnail_layout['lergs_thumbnail_layout'];
		
		$lergs_hover_color = get_option('lergs_hover_color');
		$lergs_hover_color = $lergs_hover_color['button_color'];
		$lergs_hover_text_color = get_option('lergs_hover_text_color');
		$lergs_hover_text_color = $lergs_hover_text_color['button_color'];
		$lergs_gallry_custom_css = get_option('lergs_gallry_custom_css');
	}
    $RGB = lergs_hex2rgb($lergs_hover_color);
    $HoverColorRGB = implode(", ", $RGB);
	?>
	<style>
    #logicrays .lergs-header-label {color:<?php echo $lergs_hover_text_color; ?> !important;}
    #logicrays .b-link-stroke .b-top-line {background: rgba(<?php echo $HoverColorRGB; ?>, 0.5);}
    #logicrays .b-link-stroke .b-bottom-line {background: rgba(<?php echo $HoverColorRGB; ?>, 0.5); }
    #logicrays .b-link-box .b-top-line {border: 30px solid <?php echo $lergs_hover_color; ?>;}
    #logicrays .b-link-box .b-bottom-line {background: rgba(<?php echo $HoverColorRGB; ?>, 0.5) !important;}
    #logicrays .b-link-flow .b-top-line {background:<?php echo $lergs_hover_color; ?> !important;opacity:0.5;}
    #logicrays .b-link-flow .b-bottom-line {background:<?php echo $lergs_hover_color; ?> !important;opacity:0.5;}
    #logicrays .b-link-flow .b-wrapper{background:<?php echo $lergs_hover_color; ?> !important;opacity:0.5;}
    @media (min-width: 992px) {
    .col-md-6{width:49.97%!important;padding-right:10px;padding-left:10px}
    .col-md-4{width:33.3%!important;padding-right:10px;padding-left:10px}
    .col-md-3{width:24.9%!important;padding-right:10px;padding-left:10px}
    }
    #logicrays .lergs-header-label{display:block}
    #logicrays a{border-bottom:none;overflow:hidden;float:left;margin-right:0;padding-left:0}
    <?php echo $lergs_gallry_custom_css; ?>
    </style>
    <?php
    $IG_CPT_Name = "lergs_gallery";
    $AllGalleries = array('p' => $Id['id'], 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC');
    $loop = new WP_Query( $AllGalleries );
    ?>
  <div  class="gal-container">
  <?php while ( $loop->have_posts() ) : $loop->the_post();?>
  <?php $post_id = get_the_ID(); ?>
  <div class="gallery1" id="logicrays">
    <?php
            $lergs_allphotosdetails = unserialize(base64_decode(get_post_meta( get_the_ID(), 'lergs_all_photos_details', true)));
            $TotalImages =  get_post_meta( get_the_ID(), 'lergs_total_images_count', true );
            $i = 1;

            foreach($lergs_allphotosdetails as $lergs_singlephotodetails) {
				$lergs_image_label = $lergs_singlephotodetails['lergs_image_label'];
				$url  = $lergs_singlephotodetails['lergs_image_url'];
				
				$url1 = $lergs_singlephotodetails['lergs_12_thumb'];
				$url2 = $lergs_singlephotodetails['lergs_346_thumb'];
				$url3 = $lergs_singlephotodetails['lergs_12_same_size_thumb'];
				$url4 = $lergs_singlephotodetails['lergs_346_same_size_thumb'];
				$i++;
				
				if($lergs_image_label == "") {
						global $wpdb;
						$post_table_prefix = $wpdb->prefix. "posts";
						if($attachment = $wpdb->get_col($wpdb->prepare("SELECT `post_title` FROM `$post_table_prefix` WHERE `guid` LIKE '%s'", $url))) { 
							$slide_alt = $attachment[0];
							if(empty($attachment[0])) {
								$slide_alt = get_the_title( $post_id );
							}
						}														
					} else {
						$slide_alt = $lergs_image_label;
					}
				
				if($lergs_layout_type == "col-md-12") { // 1 column
					$Thummb_Url = $url;
				}								
				if($lergs_layout_type == "col-md-6" && $lergs_thumbnail_layout == "same-size" ) {// 2 column
					$Thummb_Url = $url3;
				}
				if($lergs_layout_type == "col-md-3" && $lergs_thumbnail_layout == "same-size" ) {// 3 column
					$Thummb_Url = $url4;					
				}
				if($lergs_layout_type == "col-md-4" && $lergs_thumbnail_layout == "same-size" ){// 4 column
					 $Thummb_Url = $url4; 
				} 
				if($lergs_thumbnail_layout == "original"){
					 $Thummb_Url = $url;
				}
                ?>
    <div class="<?php echo $lergs_layout_type; ?> col-sm-6 lergs-gallery">
      <div class="b-link-<?php echo $lergs_hover_animation; ?> b-animate-go">
      <a alt="<?php echo $lergs_image_label; ?>" data-fancybox="images" href="<?php echo $url; ?>">
      <img src="<?php echo $Thummb_Url; ?>" class="gall-img-responsive" alt="<?php echo $slide_alt; ?>">
        <div class="b-wrapper">
          <?php if($lergs_layout_type == "col-md-12" || $lergs_layout_type == "col-md-6" || $lergs_layout_type == "col-md-4" || $lergs_layout_type == "col-md-3") {?>
          <?php if($lergs_show_image_label == "yes"){?>
          <h2 class="b-from-left b-animate b-delay03 lergs-header-label"><?php echo $lergs_image_label; ?></h2>
          <?php } ?>
          <?php }?>
        </div>
        </a></div>
    </div>
    <?php } ?>
  </div>
  <?php endwhile; ?>
</div>
<script>
jQuery('[data-fancybox="images"]').fancybox({
  afterLoad : function(instance, current) {
    var pixelRatio = window.devicePixelRatio || 1;

    if ( pixelRatio > 1.5 ) {
      current.width  = current.width  / pixelRatio;
      current.height = current.height / pixelRatio;
    }
  }
});
</script>
<?php wp_reset_query();
    return ob_get_clean();
}
add_shortcode( 'OWLERGS', 'owlergs_ShortCode_Page' );
function owlergs_ShortCode_Page( $Id ) {
    ob_start();	
    if(isset($Id['id'])) {
		$lergs_image_label = get_option('lergs_image_label');
		$lergs_show_image_label = $lergs_image_label['lergs_image_label'];
		$lergs_hover_animation = get_option('lergs_hover_animation');
		$lergs_hover_animation = $lergs_hover_animation['lergs_hover_animation'];
		$lergs_layout_type = get_option('lergs_layout_type');
		$lergs_layout_type = $lergs_layout_type['lergs_layout_type'];
		
		$lergs_thumbnail_layout = get_option('lergs_thumbnail_layout');
		$lergs_thumbnail_layout = $lergs_thumbnail_layout['lergs_thumbnail_layout'];
		
		$lergs_hover_color = get_option('lergs_hover_color');
		$lergs_hover_color = $lergs_hover_color['button_color'];
		$lergs_hover_text_color = get_option('lergs_hover_text_color');
		$lergs_hover_text_color = $lergs_hover_text_color['button_color'];
		$lergs_gallry_custom_css = get_option('lergs_gallry_custom_css');
		$lergs_slider_play = get_option('lergs_slider_play');
		$lergs_slider_play = $lergs_slider_play['lergs_slider_play'];
	}
    $RGB = lergs_hex2rgb($lergs_hover_color);
    $HoverColorRGB = implode(", ", $RGB);
    ?>
    <style>
	#logicrays .lergs-header-label {
	 color:<?php echo $lergs_hover_text_color;
	?> !important;
	}
	#logicrays .b-link-stroke .b-top-line {
	 background: rgba(<?php echo $HoverColorRGB;
	?>);
	}
	#logicrays .b-link-stroke .b-bottom-line {
	 background: rgba(<?php echo $HoverColorRGB;
	?>);
	}
	@media (min-width: 992px) {
	.col-md-6{width:49.97%!important;padding-right:10px;padding-left:10px}
	.col-md-4{width:33.3%!important;padding-right:10px;padding-left:10px}
	.col-md-3{width:24.9%!important;padding-right:10px;padding-left:10px}
	}
	#logicrays .lergs-header-label{display:block}
	#logicrays a{border-bottom:none;overflow:hidden;float:left;margin-right:0;padding-left:0}
	<?php echo $lergs_gallry_custom_css; ?>
	</style>
    <?php
    $IG_CPT_Name = "lergs_gallery";
    $AllGalleries = array('p' => $Id['id'], 'post_type' => $IG_CPT_Name, 'orderby' => 'ASC');
    $loop = new WP_Query( $AllGalleries );
    ?>
  <div  class="gal-container">
  <?php while ( $loop->have_posts() ) : $loop->the_post();?>
  <?php $post_id = get_the_ID(); ?>
  <div class="gallery1" id="logicrays">
  <div class="owl-carousel owl-theme" id="owl_<?php echo get_the_ID(); ?>">
    <?php
            $lergs_allphotosdetails = unserialize(base64_decode(get_post_meta( get_the_ID(), 'lergs_all_photos_details', true)));
            $TotalImages =  get_post_meta( get_the_ID(), 'lergs_total_images_count', true );
            $i = 1;

            foreach($lergs_allphotosdetails as $lergs_singlephotodetails) {
				$lergs_image_label = $lergs_singlephotodetails['lergs_image_label'];
				$url  = $lergs_singlephotodetails['lergs_image_url'];
				
				$url1 = $lergs_singlephotodetails['lergs_12_thumb'];
				$url2 = $lergs_singlephotodetails['lergs_346_thumb'];
				$url3 = $lergs_singlephotodetails['lergs_12_same_size_thumb'];
				$url4 = $lergs_singlephotodetails['lergs_346_same_size_thumb'];
				$i++;
				
				if($lergs_image_label == "") {
						global $wpdb;
						$post_table_prefix = $wpdb->prefix. "posts";
						if($attachment = $wpdb->get_col($wpdb->prepare("SELECT `post_title` FROM `$post_table_prefix` WHERE `guid` LIKE '%s'", $url))) { 
							$slide_alt = $attachment[0];
							if(empty($attachment[0])) {
								$slide_alt = get_the_title( $post_id );
							}
						}														
					} else {
						$slide_alt = $lergs_image_label;
					}
				if($lergs_layout_type == "col-md-12") { // 1 column
					$Thummb_Url = $url;
				}								
				if($lergs_layout_type == "col-md-6" && $lergs_thumbnail_layout == "same-size" ) {// 2 column
					$Thummb_Url = $url3;
				}
				if($lergs_layout_type == "col-md-3" && $lergs_thumbnail_layout == "same-size" ) {// 3 column
					$Thummb_Url = $url4;					
				}
				if($lergs_layout_type == "col-md-4" && $lergs_thumbnail_layout == "same-size" ){// 4 column
					 $Thummb_Url = $url4; 
				} 
				if($lergs_thumbnail_layout == "original"){
					 $Thummb_Url = $url;
				}
                ?>
                <div class="item">
                <a alt="<?php echo $name; ?>" data-fancybox="images" href="<?php echo $url; ?>">
                <img src="<?php echo $Thummb_Url; ?>" class="gall-img-responsive" alt="<?php echo $slide_alt; ?>">
                </a>  
                </div>    
    			<?php } ?>
  				</div>
  				<?php endwhile; ?>
				</div>
                </div>
				<script>
				jQuery(document).ready(function() { 
						var owl = jQuery("#owl_<?php echo get_the_ID(); ?>");
						  owl.owlCarousel({
							nav: true,
							margin: 10,
							autoplayTimeout:2500,
							dots: false,
							loop: true,
							autoplay: <?php echo $lergs_slider_play; ?>,
							navText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
							responsive: {
							  0: {
								items: 1
							  },
							  600: {
								items: 3
							  },
							  1000: {
								items: 3
							  }
							}
						  });
						  jQuery('.play').on('click',function(){
								owl.trigger('play.owl.autoplay',[2500])
						  })
						  jQuery('.stop').on('click',function(){
								owl.trigger('stop.owl.autoplay')
						  })
			  			});
						jQuery('[data-fancybox="images"]').fancybox({
						  afterLoad : function(instance, current) {
							var pixelRatio = window.devicePixelRatio || 1;
						
							if ( pixelRatio > 1.5 ) {
							  current.width  = current.width  / pixelRatio;
							  current.height = current.height / pixelRatio;
							}
						  }
						});
				</script>
<?php wp_reset_query();
    return ob_get_clean();
}
?>