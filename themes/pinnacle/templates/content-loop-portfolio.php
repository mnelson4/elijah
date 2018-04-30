<?php 
/**
 * Portfolio Summary Content Loop
 *
 */
global $post, $kt_portfolio_loop;

$postsummery = get_post_meta( $post->ID, '_kad_post_summery', true );
?>
<div class="portfolio-item grid_item postclass kad-light-gallery kad_portfolio_fade_in">
    <?php if (has_post_thumbnail( $post->ID ) ) {
          $image_id = get_post_thumbnail_id( $post->ID );
          $image_url = wp_get_attachment_image_src( $image_id, 'full' ); 
          $thumbnailURL = $image_url[0];
          $image = aq_resize($thumbnailURL, $kt_portfolio_loop['slidewidth'], $kt_portfolio_loop['slideheight'], true, false);
          if(empty($image[0])) {$image = array($thumbnailURL,$kt_portfolio_loop['slidewidth'],$kt_portfolio_loop['slideheight']);} ?>
              <div class="portfolio-imagepadding">
                <div class="portfolio-hoverclass">
                  <a href="<?php the_permalink() ?>" class="kt-portfoliolink">
                      <img src="<?php echo esc_url($image[0]); ?>" width="<?php echo esc_attr($image[1]); ?>" height="<?php echo esc_attr($image[2]); ?>" alt="<?php the_title(); ?>" <?php echo kt_get_srcset_output($image[1], $image[2], $thumbnailURL, $image_id);?> class="kad-lightboxhover">
                              <div class="portfolio-hoverover"></div>
                              <div class="portfolio-table">
                                  <div class="portfolio-cell">
                                    <?php if($kt_portfolio_loop['pstyleclass'] == "padded_style" ) { ?>
                                          <a href="<?php the_permalink() ?>" class="kad-btn kad-btn-primary"><?php echo __('View details', 'pinnacle');?></a>
                                          <?php if($kt_portfolio_loop['lightbox'] == 'true') {?>
                                                <a href="<?php echo esc_url($thumbnailURL); ?>" class="kad-btn kad-btn-primary plightbox-btn" title="<?php the_title();?>" data-rel="lightbox"><i class="icon-search"></i></a>
                                          <?php } ?>
                                <?php } elseif($kt_portfolio_loop['pstyleclass'] == "flat-no-margin" || $kt_portfolio_loop['pstyleclass'] == "flat-w-margin" ) { ?>
                                          <h5><?php the_title();?></h5>
                                    <?php if($kt_portfolio_loop['showtypes'] == 'true') { 
                                        $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) {?>
                                          <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> 
                                      <?php } 
                                    } 
                                    if($kt_portfolio_loop['showexcerpt'] == 'true') {?>
                                      <p class="p_excerpt"><?php echo pinnacle_excerpt(16); ?></p> 
                                    <?php } 
                                    if($kt_portfolio_loop['lightbox'] == 'true') {?>
                                            <a href="<?php echo esc_url($thumbnailURL); ?>" class="kad-btn kad-btn-primary plightbox-btn" title="<?php the_title();?>" data-rel="lightbox"><i class="icon-search"></i></a>
                                          <?php }?>
                                <?php } ?>
                                  </div>
                              </div>
                                    </a>
                                  </div>
                              </div>
                          <?php $image = null; $thumbnailURL = null;?>
                    <?php } ?>

                <?php if($kt_portfolio_loop['pstyleclass'] == "padded_style" ) { ?>
              <a href="<?php the_permalink() ?>" class="portfoliolink">
                <div class="piteminfo">   
                          <h5><?php the_title();?></h5>
                          <?php if($kt_portfolio_loop['showtypes'] == 'true') {
                            $terms = get_the_terms( $post->ID, 'portfolio-type' ); if ($terms) { ?>
                              <p class="cportfoliotag"><?php $output = array(); foreach($terms as $term){ $output[] = $term->name;} echo implode(', ', $output); ?></p> 
                             <?php } 
                          }
                          if($kt_portfolio_loop['showexcerpt'] == 'true') {?>
                            <p><?php echo pinnacle_excerpt(16); ?></p> 
                          <?php } ?>
                  </div>
              </a>
          <?php } ?>
          </div>