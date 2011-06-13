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
		
		<?php endif; ?>
		</ul>
		
	</div> <!-- padder -->

</div> <!-- sleeve -->

<?php get_footer(); ?>