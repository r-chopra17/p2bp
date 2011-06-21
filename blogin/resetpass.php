<?php get_header();?>
        <div id="container"><!-- Container goes here... -->

           

        	<div id="contents"> <!-- Contents goes here... -->

          <div id="login-wrap">
		  <div class="widget">
		  
		<h3 class="widgettitle"><?php _e("Resetting Password!",'bl');?></h3>
<div class="widget-content">           
		
			  <?php do_action('login_form_login');?>
					<!-- while changing this page, just make sure 2 things, keep the input names in form same -->
						<?php do_action( 'template_notices' ) ?>
                 <form name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url('resetpass', 'login_post') ?>" method="post">
                          <p>
                               <label><?php _e('Username or E-mail:','bl') ?><br />
                                 <input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" /></label>
                           </p>
                        <?php do_action('lostpassword_form'); ?>
                                <p class="submit"><input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php esc_attr_e('Get New Password','bl'); ?>" tabindex="100" /></p>
                        </form>

                        <p id="nav">
                        <?php if (get_option('users_can_register')) : ?>
                            <a href="<?php echo site_url(BP_LOGIN_SLUG, 'login') ?>"><?php _e('Log in','bl') ?></a> |
                        <a href="<?php echo site_url(BP_REGISTER_SLUG, 'login') ?>"><?php _e('Register','bl') ?></a>
                        <?php else : ?>
                        <a href="<?php echo site_url(BP_LOGIN_SLUG, 'login') ?>"><?php _e('Log in','bl') ?></a>
                        <?php endif; ?>
                        </p>
					<script type="text/javascript">
                        try{document.getElementById('user_login').focus();}catch(e){}
                        </script>
                                

                                 


					

				

			
                    
		
                    <?php do_action( 'bp_after_password_reset' ) ?>

                 </div>
				 </div>
				</div> 
               
                            
            </div> <!-- Contents ends here... -->
      
            
        </div><!-- Container ends here... -->
        
  <?php get_footer();?>