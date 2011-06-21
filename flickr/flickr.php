<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<?php bp_get_options_nav() ?>
	</ul>
</div><!-- .item-list-tabs -->

<?php do_action( 'bp_before_member_flickr_content' ) ?>

<div class="flickr myflickr">
<?php do_action( 'bp_before_flickr_loop' ) ?>

<?php if ( bp_flickr_has_photos() ) : ?>

	<div class="pagination no-ajax">

		<div class="pag-count" >
			<?php bp_flickr_pagination_count() ?>
		</div>

		<div class="pagination-links no-ajax" >
			<?php  bp_flickr_pagination_links() ?>
		</div>

	</div>

	<div id="flickr-list" >
	<?php while ( bp_flickr_photos() ) : bp_flickr_the_photo(); ?>

			<div class="flickr-photo">
				<h5><?php bp_flickr_photo_title();?></h5>
                                <a href="<?php  bp_flickr_photo_large_src() ?>" rel="prettyPhoto[flickr]" title="<?php echo esc_html(bp_flickr_get_photo_description_raw());?>" >
					<img src="<?php bp_flickr_photo_mid_src()?>" alt="<?php bp_flickr_photo_title();?>" title="<?php bp_flickr_photo_title();?>" />
				</a>
				<p><?php bp_flickr_get_photo_description();?></p>
			<div class="clear"></div>
		
			</div>

			
			
	<?php endwhile; ?>
	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'Sorry, there were no photos found in the stream.', 'bp-flickr' ) ?></p>
	</div>

<?php endif; ?>

</div><!-- flickr-list -->

<?php do_action( 'bp_after_member_flickr_content' ) ?>
