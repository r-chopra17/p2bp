<?php
/**
 * @package WordPress
 * @subpackage P2
 */
?>
<?php get_header(); ?>

<div class="sleeve_main">
	
	<div id="padder">
		
		
		
				<ul id="postlist">
				<?php if ( have_posts() ) : ?>
			
			<?php while ( have_posts() ) : the_post(); ?>
		    		<?php p2_load_entry(); // loads entry.php ?>
					
			<?php endwhile; ?>
			
		<?php else : ?>
		<ul id="postlist">
				<li class="no-posts">
			    	<h3><?php _e( 'No posts yet!', 'p2' ); ?></h3>
				</li>
			</ul>
				</ul>
			
			
			
			
		<?php endif; ?>

		<div class="navigation">
			<p class="nav-older"><?php previous_post_link( '%link', __( '&larr;', 'Previous post link', 'p2' ) . ' %title' ); ?></p>
			<p class="nav-newer"><?php next_post_link( '%link', '%title ' . __( '&rarr;', 'Next post link', 'p2' ) ); ?></p>
		</div>
		
	</div> <!-- padder -->

</div> <!-- sleeve -->

<?php get_footer(); ?>