<?php get_header() ?>

	<div id="content">
		<div class="padder">

		<form action="" method="post" id="galleries-directory-form" class="dir-form">

			<h3><?php _e( 'Gallery Directory', 'bp-gallery' ) ?></h3>

			<?php do_action( 'bp_before_directory_gallery_content' ) ?>

                        <div id="members-dir-search" class="dir-search">
				<?php bp_directory_members_search_form() ?>
			</div><!-- #members-dir-search -->
                        
                        <div class="item-list-tabs">
				<ul>
					<li class="selected" id="gallery-all"><a href="<?php bp_root_domain() ?>"><?php printf( __( 'All Galleries (%s)', 'bp-gallery' ), bp_get_total_gallery_count_for_dir() ) ?></a></li>

					<?php if ( is_user_logged_in() && function_exists( 'gallery_total_gallery_for_user' )  ) : ?>
						<li id="gallery-personal"><a href="<?php echo bp_loggedin_user_domain() . BP_GALLERY_SLUG . '/my-galleries/' ?>"><?php printf( __( 'My Galleries (%s)', 'bp-gallery' ),  gallery_total_gallery_for_user(bp_loggedin_user_id()) ) ?></a></li>
						<li id="gallery-groups"><a href="<?php echo bp_loggedin_user_domain() . BP_GROUPS_SLUG . '/group-galleries/' ?>"><?php printf( __( 'My Group Galleries', 'bp-gallery' ) ) ?></a></li>
					<?php endif; ?>
					
                                        <?php do_action( 'bp_gallery_directory_member_types' ) ?>

					<li id="gallery-order-select" class="last filter">

						<?php _e( 'Filter By:', 'bp-gallery' ) ?>
						<select><option value=""><?php _e( 'All Galleries', 'bp-gallery' ) ?></option>
							<option value="photo"><?php _e( 'Photo Gallery', 'bp-gallery' ) ?></option>
							<option value="audio"><?php _e( 'Audio Gallery', 'bp-gallery' ) ?></option>
							<option value="video"><?php _e( 'Video Gallery', 'bp-gallery' ) ?></option>

							

							<?php do_action( 'bp_gallery_directory_order_options' ) ?>
						</select>
					</li>
					
				</ul>
			</div><!-- .item-list-tabs -->

			<div id="galleries-dir-list" class="gallery dir-list">
				<?php locate_template( array( 'gallery/gallery-loop.php' ), true ) ?>
			</div><!-- #galleries-dir-list -->

			<?php do_action( 'bp_directory_gallery_content' ) ?>

			<?php wp_nonce_field( 'directory_galleries', '_wpnonce-gallery-filter' ) ?>

			<?php do_action( 'bp_after_directory_galleries_content' ) ?>

		</form><!-- #galleries-directory-form -->

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php locate_template( array( 'sidebar.php' ), true ) ?>

<?php get_footer() ?>