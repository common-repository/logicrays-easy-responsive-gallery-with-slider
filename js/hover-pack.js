jQuery(document).ready(function(){
    /* Slide */
    jQuery('#slide a').each(function(index, element) {
        jQuery(this).hoverdir();
    });
    /* Stroke */
    jQuery('.b-link-stroke').prepend('<div class="b-top-line"></div>');
    jQuery('.b-link-stroke').prepend('<div class="b-bottom-line"></div>');
    /* Twist */
    jQuery('.b-link-twist').prepend('<div class="b-top-line"><b></b></div>');
    jQuery('.b-link-twist').prepend('<div class="b-bottom-line"><b></b></div>');
    jQuery('.b-link-twist img').each(function(index, element) {
        jQuery(this).css('visibility','hidden');
        jQuery(this).parent().find('.b-top-line, .b-bottom-line').css('background-image','url('+jQuery(this).attr('src')+')');
    });
    /* Flip */
    jQuery('.b-link-flip').prepend('<div class="b-top-line"><b></b></div>');
    jQuery('.b-link-flip').prepend('<div class="b-bottom-line"><b></b></div>');
    jQuery('.b-link-flip img').each(function(index, element) {
        jQuery(this).css('visibility','hidden');
        jQuery(this).parent().find('.b-top-line, .b-bottom-line').css('background-image','url('+jQuery(this).attr('src')+')');

    });
    /* Fade */
    jQuery('.b-link-fade').each(function(index, element) {
        jQuery(this).append('<div class="b-top-line"></div>')
    });
    /* Flow */
    jQuery('.b-link-flow').each(function(index, element) {
        jQuery(this).append('<div class="b-top-line"></div>')
    });
    /* Box */
    jQuery('.b-link-box').prepend('<div class="b-top-line"></div>');
    jQuery('.b-link-box').prepend('<div class="b-bottom-line"></div>');
    /* Stripe */
    jQuery('.b-link-stripe').each(function(index, element) {
        jQuery(this).prepend('<div class="b-line b-line1"></div><div class="b-line b-line2"></div><div class="b-line b-line3"></div><div class="b-line b-line4"></div><div class="b-line b-line5"></div>');
    });
    /* Apart */
    jQuery('.b-link-apart-vertical, .b-link-apart-horisontal').each(function(index, element) {
        jQuery(this).prepend('<div class="b-top-line"></div><div class="b-bottom-line"></div><div class="b-top-line-up"></div><div class="b-bottom-line-up"></div>');
    });
    /* diagonal */
    jQuery('.b-link-diagonal').each(function(index, element) {
        jQuery(this).prepend('<div class="b-line-group"><div class="b-line b-line1"></div><div class="b-line b-line2"></div><div class="b-line b-line3"></div><div class="b-line b-line4"></div><div class="b-line b-line5"></div></div>');
    });
    setTimeout("calculate_margin();", 100);
});

var count_calc_margin = 0;

function calculate_margin() {
    // Vertical alignment
    jQuery('.b-animate-go .b-wrapper').each(function(i, v){
      var this_h = jQuery(v).outerHeight();
      var el_h = 0;
      var m_t = 0;
      var m_b = 0;
      var el_len = jQuery(v).children().length;
      jQuery(v).children().each(function(ii, vv){
          el_h += jQuery(vv).outerHeight();
          if(ii > 0) {
              m_t += parseInt(jQuery(vv).css('margin-top'));
          }
          if((ii+1) <= el_len-1) {
              m_b += parseInt(jQuery(vv).css('margin-bottom'));
          }
      });
      var set_mar = parseInt((this_h/2)-((m_t+m_b+el_h)/2));
      if(set_mar > 0) {
        jQuery(v).children().first().css('margin-top', set_mar);
      } else {
        if(count_calc_margin < 5) {
          count_calc_margin++;
          setTimeout("calculate_margin();", 100);
        }
      }
  });
}