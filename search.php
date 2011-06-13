<?php
/**
 * @package WordPress
 * @subpackage P2
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">

	<div class="padder">
	
		<?php do_action( 'bp_before_blog_search' ) ?>

		<h2>
			<?php printf( __( 'Search Results for: %s', 'p2' ), get_search_query() ); ?>
			<span class="controls">
				<a href="#" id="togglecomments"> <?php _e( 'Toggle Comment Threads', 'p2' ); ?></a> | <a href="#directions" id="directions-keyboard"><?php _e( 'Keyboard Shortcuts', 'p2' ); ?></a>
			</span>
		</h2>

		<?php if ( have_posts() ) : ?>

			<ul id="postlist">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php p2_load_entry(); // loads entry.php ?>
			<?php endwhile; ?>
			</ul>

		<?php else : ?>

		<div class="navigation">

			<div class="alignleft"><?php next_posts_link( __( '&larr; Previous Entries', 'buddypress' ) ) ?></div>
			<div class="alignright"><?php previous_posts_link( __( 'Next Entries &rarr;', 'buddypress' ) ) ?></div>

		</div>
				
		<?php else : ?>
			
				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<p class="center"><?php _e( 'Sorry, but you are looking for something that isn\'t here.', 'buddypress' ) ?></p>

				<?php locate_template( array( 'searchform.php' ), true ) ?>
			
		<?php endif; ?>

		<?php do_action( 'bp_after_blog_search' ) ?>

		</div><!-- .padder -->
</div> <!-- sleeve -->

<?php get_footer(); ?>