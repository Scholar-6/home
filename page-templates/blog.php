<?php
/**
* Template Name: Blog
*/

// CHECK IF USER CAN CREATE BLOG POST
$post_create = woffice_get_settings_option('post_create');
$woffice_role_allowed = Woffice_Frontend::role_allowed($post_create, 'post');
if ($woffice_role_allowed):
	
	$hasError = Woffice_Frontend::frontend_process('post');
	
endif;
 
get_header(); 
?>

	<div id="left-content">

		<?php  //GET THEME HEADER CONTENT
		woffice_title(get_the_title()); ?> 	

		<!-- START THE CONTENT CONTAINER -->
		<div id="content-container">

			<!-- START CONTENT -->
			<div id="content">
                <?php Woffice_Frontend::frontend_render('post',$hasError); ?>

				<?php // We check for the layout 
				$blog_layout = woffice_get_settings_option('blog_layout');
				$masonry_columns = woffice_get_settings_option('masonry_columns');
				$masonry_columns_class = 'masonry-layout--'.$masonry_columns.'-columns';

				if ($blog_layout === 'masonry' || isset($_GET['blog_masonry'])) { ?>
                    <div id="directory" class="masonry-layout <?php echo esc_html($masonry_columns_class); ?>">
                <?php } ?>

                <?php
                // THE LOOP :
                $posts_per_page = woffice_get_settings_option('blog_number');

                $pagination_slug = (is_front_page()) ? 'page' : 'paged';
                $paged = (get_query_var($pagination_slug)) ? get_query_var($pagination_slug) : 1;

                /**
                 * Filter args of the blog posts query
                 *
                 * @param array $args
                 * @param int $paged
                 * @param int $posts_per_page
                 */
                $args = apply_filters('woffice_blog_query_args', array(
                    'post_type' => 'post',
                    'paged' => $paged,
                    'posts_per_page' => $posts_per_page
                ), $paged, $posts_per_page);

                $blog_query = new WP_Query($args);
                if ( $blog_query->have_posts() ) :	?>
                    <?php while ( $blog_query->have_posts() ) : $blog_query->the_post(); ?>
                        <?php // We check for the role :
                        if (woffice_is_user_allowed()) { ?>
                            <?php if (($blog_layout == "masonry")) {
                                get_template_part( 'content-masonry' );
                            }
                            else {
                                get_template_part( 'content' );
                            } ?>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php else : ?>
                    <?php get_template_part( 'content', 'none' ); ?>
                <?php endif; ?>

                <?php if ($blog_layout === 'masonry' || isset($_GET['blog_masonry'])) { ?>
                    </div>
			    <?php } ?>

				<!-- THE NAVIGATION --> 
				<?php woffice_paging_nav($blog_query); ?>
				
			</div>
				
		</div><!-- END #content-container -->

		<?php woffice_scroll_top(); ?>
		
	</div><!-- END #left-content -->

<?php 
get_footer(); 