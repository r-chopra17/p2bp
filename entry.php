<div id="prologue-<?php the_ID(); ?>" <?php post_class( get_the_author_meta( 'ID' ) ); ?>>

						<?php if(has_post_thumbnail()):?>
							<div class="author-box">
								<?php the_post_thumbnail();?>
							</div>
						<?php endif;?>
						
						
						
								<div class="meta">
									<h2 class="post-title"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
									<span class="actions">
										<?php if ( ! is_singular() ) : ?>
											<a href="<?php the_permalink(); ?>" class="thepermalink"><?php _e( 'Permalink', 'p2' ); ?></a>
											<?php if ( ! post_password_required() ) : ?>
												<?php echo post_reply_link( array( 'before' => ' | ', 'after' => '',  'reply_text' => __( 'Reply', 'p2' ), 'add_below' => 'prologue' ), get_the_id() ); ?>
											<?php endif; ?>
										<?php else : ?>
											<?php if ( comments_open() && ! post_password_required() ) :
												echo post_reply_link( array( 'before' => '', 'after' => '',  'reply_text' => __( 'Reply', 'p2' ), 'add_below' => 'prologue' ), get_the_id() ); ?>
											<?php endif; ?>
										<?php endif;?>
										<?php if ( current_user_can( 'edit_post', get_the_id() ) ) : ?>
											| <a href="<?php echo ( get_edit_post_link( get_the_id() ) ); ?>" class="edit-post-link" rel="<?php the_ID(); ?>"><?php _e( 'Edit', 'p2' ); ?></a>
										<?php endif; ?>
										<br class="clear" />
										<?php do_action( 'p2_action_links' ); ?>
									</span>
									<br class="clear" />
					
								</div>
								
								<div class="bread-crumbs">
									<?php if ( !is_page() ) : ?>
										<p class="date"><?php the_time('F j, Y') ?> <em><?php _e( 'in', 'buddypress' ) ?> <?php the_category(', ') ?> <?php printf( __( 'by %s', 'buddypress' ), bp_core_get_userlink($post->post_author) ) ?></em></p>
													
									<?php endif; ?>
									
								</div>
								
								<div class="entry postcontent<?php if ( current_user_can( 'edit_post', get_the_id() ) ) : ?> editarea<?php endif ?>" id="content-<?php the_ID(); ?>">
								<?php if ( 'post' == p2_get_the_category() || 'link' == p2_get_the_category() ) : ?>
								<?php the_content( __( '(More ...)' , 'p2' ) ); ?>
								<?php elseif ( 'quote' == p2_get_the_category() ) : ?>

									<blockquote>
										<?php p2_quote_content(); ?>
									</blockquote>

								<?php elseif ( 'post' == p2_get_the_category() ) : ?>

								
								<?php the_content( __( '(More ...)' , 'p2' ) ); ?>

								<?php else : ?>

								
								<?php the_content( __( '(More ...)' , 'p2' ) ); ?>

								<?php endif; ?>
								<?php if ( !is_page() ) : ?>
									<span class="tags">
										<?php the_tags( __( 'Tags: ', 'buddypress' )); ?>
									<?php endif; ?>
								</div>  <!-- End of post entry-->
						
				
					
					<?php if ( get_comments_number() > 0 && ! post_password_required() ) : ?>
					<div class="discussion" style="display: none">
					<p>
						<?php p2_discussion_links(); ?>
						<a href="#" class="show-comments"><?php _e( 'Toggle Comments', 'p2' ); ?></a>
					</p>
					</div>
					<?php endif; ?>
					<?php wp_link_pages( array( 'before' => '<p class="page-nav">' . __( 'Pages:', 'p2' ) ) ); ?>

					<div class="bottom-of-entry">&nbsp;</div>

					<?php if ( ! p2_is_ajax_request() ) : ?>
						<?php comments_template(); ?>
						<?php $pc = 0; ?>
						<?php if ( p2_show_comment_form() && $pc == 0 && ! post_password_required() ) : ?>
							<?php $pc++; ?>
							<div class="respond-wrap"<?php if ( ! is_singular() ): ?> style="display: none; "<?php endif; ?>>
								<?php
									$p2_comment_args = array(
										'title_reply' => __( 'Reply', 'p2' ),
										'comment_field' => '<div class="form"><textarea id="comment" class="expand50-100" name="comment" cols="45" rows="3"></textarea></div> <label class="post-error" for="comment" id="commenttext_error"></label>',
										'comment_notes_before' => '<p class="comment-notes">' . ( get_option( 'require_name_email' ) ? sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' ) : '' ) . '</p>',
										'comment_notes_after' => sprintf(
											'<span class="progress"><img src="%1$s" alt="%2$s" title="%2$s" /></span>',
											str_replace( WP_CONTENT_DIR, content_url(), locate_template( array( "i/indicator.gif" ) ) ),
											esc_attr( 'Loading...', 'p2' )
										),
										'label_submit' => __( 'Reply', 'p2' ),
										'id_submit' => 'comment-submit',
									);
									comment_form( $p2_comment_args );
								?>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				
</div>   <!--post ends here-->