<div class="item-list-tabs no-ajax" id="subnav">
	<ul>
		<?php bp_get_options_nav() ?>
	</ul>
</div><!-- .item-list-tabs -->

<?php do_action( 'bp_before_member_flickr_content' ) ?>
<div class="flickr myflickr-settings">
	<?php do_action( 'bp_before_flickr_settings' ) ?>

	<form class="standard-form" method="post" action="">

		<fieldset>
			<legend>Flickr Account settings</legend>
			<p>Please enter your flickr account NSID
			<input type="text" name="flickr_account" value="<?php echo bp_flickr_get_user_account();?>"/>
			</p>
			<?php wp_nonce_field( 'flickr_settings') ?>
			<input type="submit" name="save_settings" value="Save" />
			
		</fieldset>

	</form>






<?php do_action( 'bp_after_flickr_settings' ) ?>
	
</div><!-- flickr -->

<?php do_action( 'bp_after_member_flickr_content' ) ?>
