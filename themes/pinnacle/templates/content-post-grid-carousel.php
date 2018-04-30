<?php global $post, $pinnacle, $postcolumn;
            if(!empty($postcolumn)) {
              if($postcolumn == '3') {
                $image_width = 370; 
                $image_height = 246;
              } else if($postcolumn == '2') {
                $image_width = 560;
                $image_height = 370;
              } else {
                $image_width = 340;
                $image_height = 226;
              }
            } else {
              $image_width = 340;
              $image_height = 226;
            }?>
              <div id="post-<?php esc_attr(the_ID()); ?>" class="blog_item postclass grid_item" itemscope="" itemtype="http://schema.org/BlogPosting">
                  <?php
                        $img = pinnacle_get_image_array( $image_width, $image_height, true,'iconhover', get_the_title(), null, true); ?>
                        <div class="imghoverclass img-margin-center" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
                              <a href="<?php the_permalink()  ?>" title="<?php the_title_attribute(); ?>">
                                 <img src="<?php echo esc_url($img['src']); ?>" alt="<?php the_title_attribute(); ?>"  itemprop="contentUrl" <?php echo 'width="'.esc_attr($img['width']).'" height="'.esc_attr($img['height']).'"';?> <?php echo $img['srcset']; ?> class="<?php echo esc_attr($img['class']);?>" style="display:block;">
                                    <meta itemprop="url" content="<?php echo esc_url($img['src']); ?>">
		                            <meta itemprop="width" content="<?php echo esc_attr($img['width'])?>">
		                            <meta itemprop="height" content="<?php echo esc_attr($img['height'])?>">
                              </a> 
                        </div>
                        <?php $image = null; $thumbnailURL = null;   ?>
                        <div class="postcontent">
                          <header>
                              <a href="<?php the_permalink() ?>">
                                <h5 class="entry-title" itemprop="name headline"><?php the_title();?></h5>
                              </a>
                              <?php get_template_part('templates/entry', 'meta-subhead'); ?>
                          </header>
                          <div class="entry-content color_body" itemprop="description">
                                <p>
                                  <?php echo pinnacle_excerpt(16); ?> 
                                  <a href="<?php the_permalink() ?>"><?php echo __('Read More', 'pinnacle');?></a>
                                </p> 
                              </div>
                          <footer class="clearfix">
                          <?php 
                          echo '<meta itemprop="dateModified" content="'.esc_attr(get_the_modified_date('c')).'">';
							echo '<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="'.esc_url(get_the_permalink()).'">';
							echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
							    if (!empty($pinnacle['x1_logo_upload']['url'])) {  
							    echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
							    echo '<meta itemprop="url" content="'.esc_attr($pinnacle['x1_logo_upload']['url']).'">';
							    echo '<meta itemprop="width" content="'.esc_attr($pinnacle['x1_logo_upload']['width']).'">';
							    echo '<meta itemprop="height" content="'.esc_attr($pinnacle['x1_logo_upload']['height']).'">';
							    echo '</div>';
							    }
							    echo '<meta itemprop="name" content="'.esc_attr(get_bloginfo('name')).'">';
							echo '</div>';
							?>
                          </footer>
                        </div><!-- Text size -->
            </div> <!-- Blog Item -->