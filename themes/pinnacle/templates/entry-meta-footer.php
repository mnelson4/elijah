<?php $post_category = get_the_category(); 
	if ( $post_category ) {?>
		<span class="postedinbottom"><i class="icon-folder-close"></i> <?php the_category(', ') ?></span>
	<?php }
	$tags = get_the_tags(); 
	if ($tags) { ?>
		<span class="posttags color_gray"><i class="icon-tag"></i> <?php the_tags('', ', ', ''); ?> </span>
	<?php } 
	 $headcontent = pinnacle_get_post_head_content();
	 if('none' == $headcontent) {
	 	if(has_post_thumbnail()) {
                $image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' ); 
                echo '<div class="meta_post_image" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
                    echo '<meta itemprop="url" content="'.esc_url($image[0]).'">';
                    echo '<meta itemprop="width" content="'.esc_attr($image[1]).'">';
                    echo '<meta itemprop="height" content="'.esc_attr($image[2]).'">';
                echo '</div>';
	 	}
	 }
	global $pinnacle;
	echo '<meta itemprop="dateModified" content="'.esc_attr(get_the_modified_date('c')).'">';
	echo '<meta itemscope itemprop="mainEntityOfPage"  itemType="https://schema.org/WebPage" itemid="'.esc_url(get_the_permalink()).'">';
	echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
	$site_icon_id = get_option( 'site_icon' );
	    if (!empty($pinnacle['x1_logo_upload']['url'])) {  
		    echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
		    echo '<meta itemprop="url" content="'.esc_attr($pinnacle['x1_logo_upload']['url']).'">';
		    echo '<meta itemprop="width" content="'.esc_attr($pinnacle['x1_logo_upload']['width']).'">';
		    echo '<meta itemprop="height" content="'.esc_attr($pinnacle['x1_logo_upload']['height']).'">';
		    echo '</div>';
	    } else if(!empty( $site_icon_id ) ) {
	    	$image = wp_get_attachment_image_src($site_icon_id, 'full');
	    	  	echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
			    echo '<meta itemprop="url" content="'.esc_attr($image[0]).'">';
			    echo '<meta itemprop="width" content="'.esc_attr($image[1]).'">';
			    echo '<meta itemprop="height" content="'.esc_attr($image[2]).'">';
			    echo '</div>';
	    }
	    echo '<meta itemprop="name" content="'.esc_attr(get_bloginfo('name')).'">';
	echo '</div>';