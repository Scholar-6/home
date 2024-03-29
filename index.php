<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 */
 

// CHECK IF USER CAN CREATE BLOG POST
$post_create = woffice_get_settings_option('post_create');
$woffice_role_allowed = Woffice_Frontend::role_allowed($post_create, 'post');
if ($woffice_role_allowed):

	$frontend_process = Woffice_Frontend::frontend_process('post');

endif;

get_header(); 
?>

	<div id="left-content">

		<?php  //GET THEME HEADER CONTENT
		$title = woffice_get_settings_option('index_title');
		woffice_title($title); ?> 	

		<!-- START THE CONTENT CONTAINER -->
		<div id="content-container">

			<!-- START CONTENT -->
			<div id="content">

				<!-- Create post -->
				<?php Woffice_Frontend::frontend_render('post',$hasError); ?>
				
				<?php // We check for the layout
				$blog_layout = 'classic';
				if (get_post_type() === 'post') {

					$blog_layout           = woffice_get_settings_option( 'blog_layout' );
					$masonry_columns       = woffice_get_settings_option( 'masonry_columns' );
					$masonry_columns_class = 'masonry-layout--' . $masonry_columns . '-columns';
				}

				if ($blog_layout === 'masonry' || isset($_GET['blog_masonry'])) { ?>
                    <div id="directory" class="masonry-layout <?php echo esc_html($masonry_columns_class); ?>">
                <?php } ?>

				<?php if ( have_posts() ) : ?>
                    <?php while ( have_posts() ) : the_post(); ?>
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
				<?php woffice_paging_nav(); ?>
				
			</div>
				
		</div><!-- END #content-container -->

		<?php woffice_scroll_top(); ?>
		
	</div><!-- END #left-content -->

<?php 
get_footer(); 