<?php get_header();?>
        <div id="sleeve_main"><!-- Container goes here... -->

           

        	<div id="contents"> <!-- Contents goes here... -->
			
          <div id="login-wrap">
		  <div class="widget">
		  
		<h3 class="widgettitle"><?php _e("Login! Please",'bl');?></h3>
<div class="widget-content">           
		   <?php do_action( 'template_notices' ) ?>
			  <?php do_action('login_form_login');?>
					<!-- while changing this page, just make sure 2 things, keep the input names in form same -->
						<form name="loginform" id="loginform" action="<?php echo site_url(BP_LOGIN_SLUG, 'login_post') ?>" method="post">
                             <p>
                               <label><?php _e('Username','bl') ?><br />
									<input type="text" name="log" id="user_login" class="input" value="<?php echo esc_attr($user_login); ?>" size="20" tabindex="10" /></label>
                              </p>
                              <p>
                                <label><?php _e('Password','bl') ?><br />
                                <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></label>
                              </p>
                               <?php do_action('login_form'); ?>
                               <p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" tabindex="90" /> <?php esc_attr_e('Remember Me','bl'); ?></label></p>
                                <p class="submit">
                                 <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="<?php esc_attr_e('Log In','bl'); ?>" tabindex="100" />
                               <?php	if ( $interim_login ) { ?>
                                           <input type="hidden" name="interim-login" value="1" />
                                 <?php	} else { ?>
                                                    <input type="hidden" name="redirect_to" value="<?php echo esc_attr($_REQUEST['redirect_to']); ?>" />
                                <?php 	} ?>
                                                    <input type="hidden" name="testcookie" value="1" />
                                    </p>
                                 </form>
                                

                                 <?php global $interim_login; if ( !$interim_login ) { ?>
                                    <p id="nav">
                                        <?php if (get_option('users_can_register')) : ?>
                                            <a href="<?php echo site_url(BP_REGISTER_SLUG, 'login') ?>"><?php _e('Register','bl') ?></a> |
                                        <?php endif;?>
                                       <a href="<?php echo bl_get_reset_pass_link() ?>" title="<?php _e('Password Lost and Found','bl') ?>"><?php _e('Lost your password?','bl') ?></a>
                                                           
                                    </p>
                                    <?php } ?>
                                    

                                    <script type="text/javascript">
                                    <?php if ( $user_login || $interim_login ) { ?>
                                    setTimeout( function(){ try{
                                    d = document.getElementById('user_pass');
                                    d.value = '';
                                    d.focus();
                                    } catch(e){}
                                    }, 200);
                                    <?php } else { ?>
                                    try{document.getElementById('user_login').focus();}catch(e){}
                                    <?php } ?>
                                    </script>


					

				


                 </div>
				 </div>
				</div> 
           
                            
            </div> <!-- Contents ends here... -->
            
        </div><!-- Container ends here... -->
        
  <?php get_footer();?>