<?php
/**
 * @package WordPress
 * @subpackage P2
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">
		<?php if ( p2_user_can_post() && !is_archive() ) : ?>
		<?php locate_template( array( 'post-form.php' ), true ); ?>
		<?php endif; ?>
	<div class="padder">
	<div id="main">
		<h2 class="recent-updates">
			<?php if ( is_home() or is_front_page() ) : ?>
		
				<?php _e( 'Recent Updates' , 'p2' ); ?> <?php if ( p2_get_page_number() > 1 ) printf( __( 'Page %s', 'p2' ), p2_get_page_number() ); ?>
			

			<?php elseif ( is_author() ) : ?>
				
				<?php printf( _x( 'Updates from %s', 'Author name', 'p2' ), p2_get_archive_author() ); ?>
				<a class="rss" href="<?php p2_author_feed_link(); ?>">RSS</a>
				
			<?php elseif ( is_tax( 'mentions' ) ) : ?>

				<?php printf( _x( 'Posts Mentioning %s', 'Author name', 'p2' ), p2_get_mention_name() ); ?>
				<a class="rss" href="<?php p2_author_feed_link(); ?>">RSS</a>
		
			<?php else : ?>
	
				<?php printf( _x( 'Updates from %s', 'Month name', 'p2' ), get_the_time( 'F, Y' ) ); ?>
		
			<?php endif; ?>
	
			<span class="controls">
				<a href="#" id="togglecomments"> <?php _e( 'Toggle Comment Threads', 'p2' ); ?></a> | <a href="#directions" id="directions-keyboard"><?php _e( 'Keyboard Shortcuts', 'p2' ); ?></a>
			</span>
		</h2>
			
		<?php do_action( 'bp_before_blog_home' ) ?>

		

		<?php do_action( 'bp_before_blog_post' ) ?>
					
		<ul id="postlist">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php p2_load_entry(); // loads entry.php ?>
					<?php do_action( 'bp_after_blog_post' ) ?>
				<?php endwhile; ?>
		</ul>	
				<div class="navigation">

					<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
					<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

				</div>
				
				<?php else : ?>
			
				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<p class="center"><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'buddypress' ) ?></p>

				<?php locate_template( array( 'searchform.php' ), true ) ?>
			
			<?php endif; ?>
		
		

		<?php do_action( 'bp_after_blog_home' ) ?>
	
	</div><!--main-->

		</div><!-- .padder -->
	
	

</div> <!-- sleeve -->

<?php get_footer(); ?>