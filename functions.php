<?php
/**
 * @package WordPress
 * @subpackage P2
 */


// Stop the theme from killing WordPress if BuddyPress is not enabled.
if ( !class_exists( 'BP_Core_User' ) )
	return false;

// Register the widget columns
register_sidebars( 1,
	array(
		'name'          => 'Sidebar',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>'
	)
);
define( 'P2_INC_PATH',  get_template_directory() . '/inc' );
define( 'P2_INC_URL',  get_template_directory_uri() . '/inc' );
define( 'P2_JS_PATH',  get_template_directory() . '/js' );
define( 'P2_JS_URL', get_template_directory_uri() . '/js' );

// Load the AJAX functions for the theme
require_once( TEMPLATEPATH . '/ajax.php' );
require_once( P2_INC_PATH . '/compat.php' );
require_once( P2_INC_PATH . '/template-tags.php' );
require_once( P2_INC_PATH . '/p2.php' );
require_once( P2_INC_PATH . '/list-creator.php' );
require_once( P2_INC_PATH . '/js.php' );
require_once( P2_INC_PATH . '/widgets/recent-tags.php' );
require_once( P2_INC_PATH . '/widgets/recent-comments.php' );

// Load the javascript for the theme
wp_enqueue_script( 'p2-ajax-js', get_template_directory_uri() . '/inc/global.js', array( 'jquery' ) );

// Add words that we need to use in JS to the end of the page so they can be translated and still used.
$params = array(
	'my_favs'           => __( 'My Favorites', 'buddypress' ),
	'accepted'          => __( 'Accepted', 'buddypress' ),
	'rejected'          => __( 'Rejected', 'buddypress' ),
	'show_all_comments' => __( 'Show all comments for this thread', 'buddypress' ),
	'show_all'          => __( 'Show all', 'buddypress' ),
	'comments'          => __( 'comments', 'buddypress' ),
	'close'             => __( 'Close', 'buddypress' ),
	'mention_explain'   => sprintf( __( "%s is a unique identifier for %s that you can type into any message on this site. %s will be sent a notification and a link to your message any time you use it.", 'buddypress' ), '@' . bp_get_displayed_user_username(), bp_get_user_firstname( bp_get_displayed_user_fullname() ), bp_get_user_firstname( bp_get_displayed_user_fullname() ) )
);
wp_localize_script( 'p2-ajax-js', 'p2_Theme', $params );

/**
 * Add the JS needed for blog comment replies
 *
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_add_blog_comments_js() {
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'template_redirect', 'p2_theme_add_blog_comments_js' );

/**
 * HTML for outputting blog comments as defined by the WP comment API
 *
 * @param mixed $comment Comment record from database
 * @param array $args Arguments from wp_list_comments() call
 * @param int $depth Comment nesting level
 * @see wp_list_comments()
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_blog_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>

	<?php if ( 'pingback' == $comment->comment_type ) return false; ?>

	<li id="comment-<?php comment_ID(); ?>">
		<div class="comment-avatar-box">
			<div class="avb">
				<a href="<?php echo get_comment_author_url() ?>" rel="nofollow">
					<?php if ( $comment->user_id ) : ?>
						<?php echo bp_core_fetch_avatar( array( 'item_id' => $comment->user_id, 'width' => 50, 'height' => 50, 'email' => $comment->comment_author_email ) ); ?>
					<?php else : ?>
						<?php echo get_avatar( $comment, 50 ) ?>
					<?php endif; ?>
				</a>
			</div>
		</div>

		<div class="comment-content">

			<div class="comment-meta">
				<a href="<?php echo get_comment_author_url() ?>" rel="nofollow"><?php echo get_comment_author(); ?></a> <?php _e( 'said:', 'buddypress' ) ?>
				<em><?php _e( 'On', 'buddypress' ) ?> <a href="#comment-<?php comment_ID() ?>" title=""><?php comment_date() ?></a></em>
			</div>

			<?php if ( $comment->comment_approved == '0' ) : ?>
			 	<em class="moderate"><?php _e('Your comment is awaiting moderation.'); ?></em><br />
			<?php endif; ?>

			<?php comment_text() ?>

			<div class="comment-options">
				<?php echo comment_reply_link( array('depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ?>
				<?php edit_comment_link( __( 'Edit' ),'','' ); ?>
			</div>

		</div>
<?php
}

/**
 * Filter the dropdown for selecting the page to show on front to include "Activity Stream"
 *
 * @param string $page_html A list of pages as a dropdown (select list)
 * @see wp_dropdown_pages()
 * @return string
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_wp_pages_filter( $page_html ) {
	if ( !bp_is_active( 'activity' ) )
		return $page_html;

	if ( 'page_on_front' != substr( $page_html, 14, 13 ) )
		return $page_html;

	$selected = false;
	$page_html = str_replace( '</select>', '', $page_html );

	if ( p2_theme_page_on_front() == 'activity' )
		$selected = ' selected="selected"';

	$page_html .= '<option class="level-0" value="activity"' . $selected . '>' . __( 'Activity Stream', 'buddypress' ) . '</option></select>';
	return $page_html;
}
add_filter( 'wp_dropdown_pages', 'p2_theme_wp_pages_filter' );

/**
 * Hijack the saving of page on front setting to save the activity stream setting
 *
 * @param $string $oldvalue Previous value of get_option( 'page_on_front' )
 * @param $string $oldvalue New value of get_option( 'page_on_front' )
 * @return string
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_page_on_front_update( $oldvalue, $newvalue ) {
	if ( !is_admin() || !is_super_admin() )
		return false;

	if ( 'activity' == $_POST['page_on_front'] )
		return 'activity';
	else
		return $oldvalue;
}
add_action( 'pre_update_option_page_on_front', 'p2_theme_page_on_front_update', 10, 2 );

/**
 * Load the activity stream template if settings allow
 *
 * @param string $template Absolute path to the page template 
 * @return string
 * @global WP_Query $wp_query WordPress query object
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_page_on_front_template( $template ) {
	global $wp_query;

	if ( empty( $wp_query->post->ID ) )
		return locate_template( array( 'activity/index.php' ), false );
	else
		return $template;
}
add_filter( 'page_template', 'p2_theme_page_on_front_template' );

/**
 * Return the ID of a page set as the home page.
 *
 * @return false|int ID of page set as the home page
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_page_on_front() {
	if ( 'page' != get_option( 'show_on_front' ) )
		return false;

	return apply_filters( 'p2_theme_page_on_front', get_option( 'page_on_front' ) );
}

/**
 * Force the page ID as a string to stop the get_posts query from kicking up a fuss.
 *
 * @global WP_Query $wp_query WordPress query object
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_fix_get_posts_on_activity_front() {
	global $wp_query;

	if ( !empty($wp_query->query_vars['page_id']) && 'activity' == $wp_query->query_vars['page_id'] )
		$wp_query->query_vars['page_id'] = '"activity"';
}
add_action( 'pre_get_posts', 'p2_theme_fix_get_posts_on_activity_front' );

/**
 * WP 3.0 requires there to be a non-null post in the posts array
 *
 * @param array $posts Posts as retrieved by WP_Query
 * @global WP_Query $wp_query WordPress query object
 * @return array
 * @package BuddyPress Theme
 * @since 1.2.5
 */
function p2_theme_fix_the_posts_on_activity_front( $posts ) {
	global $wp_query;

	// NOTE: the double quotes around '"activity"' are thanks to our previous function p2_theme_fix_get_posts_on_activity_front()
	if ( empty( $posts ) && !empty( $wp_query->query_vars['page_id'] ) && '"activity"' == $wp_query->query_vars['page_id'] )
		$posts = array( (object) array( 'ID' => 'activity' ) );

	return $posts;
}
add_filter( 'the_posts', 'p2_theme_fix_the_posts_on_activity_front' );

/**
 * p2_theme_activity_secondary_avatars()
 *
 * Add secondary avatar image to this activity stream's record, if supported
 *
 * @param string $action The text of this activity
 * @param BP_Activity_Activity $activity Activity object
 * @return string
 * @package BuddyPress Theme
 * @since 1.2.6
 */
function p2_theme_activity_secondary_avatars( $action, $activity ) {
	switch ( $activity->component ) {
		case 'groups' :
		case 'friends' :
			// Only insert avatar if one exists
			if ( $secondary_avatar = bp_get_activity_secondary_avatar() ) {
				$reverse_content = strrev( $action );
				$position        = strpos( $reverse_content, 'a<' );
				$action          = substr_replace( $action, $secondary_avatar, -$position - 2, 0 );
			}
			break;
	}

	return $action;
}
add_filter( 'bp_get_activity_action_pre_meta', 'p2_theme_activity_secondary_avatars', 10, 2 );

/* Custom Header Code */
define( 'HEADER_TEXTCOLOR', '3478E3' );
define( 'HEADER_IMAGE', '' ); // %s is theme dir uri
define( 'HEADER_IMAGE_WIDTH', 980);
define( 'HEADER_IMAGE_HEIGHT', 120);

function p2_admin_header_style() {
?>
	<style type="text/css">
	#headimg {
		background: url(<?php header_image(); ?>) repeat;
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
		padding:0 0 0 18px;
	}
	#headimg a {
		height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
	}

	#headimg h1{
		padding-top:40px;
		margin: 0;
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-weight: 200;
	}
	#headimg h1 a {
		color:#<?php header_textcolor(); ?>;
		text-decoration: none;
		border-bottom: none;
		font-size: 1.4em;
		margin: -0.4em 0 0 0;
	}
	#headimg #desc{
		color:#<?php header_textcolor(); ?>;
		font-size:1.1em;
		margin-top:1em;
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif;
		font-weight: 200;
	}

	<?php if ( 'blank' == get_header_textcolor() ) { ?>
	#headimg h1, #headimg #desc {
		display: none;
	}
	#headimg h1 a, #headimg #desc {
		color:#<?php echo HEADER_TEXTCOLOR ?>;
	}
	<?php } ?>

	</style>
<?php
}

function p2_header_style() {
?>
	<style type="text/css">
		<?php if ( '' != get_header_image() ) : ?>
		#header {
			background: url(<?php header_image(); ?>) repeat;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
		#header a.secondary {
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
			display: block;
			position: absolute;
			top: 0;
		}
		#header a.secondary:hover {
			border: 0;
		}
		#header .sleeve {
			position: relative;
			margin-top: 0;
			margin-right: 0;
			background-color: transparent;
			box-shadow: none !important;
			-webkit-box-shadow: none !important;
			-moz-box-shadow: none !important;
			height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
		}
		#header {
			box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2) !important;
			-webkit-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2) !important;
			-moz-box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2) !important;
		}
		<?php endif; ?>
		<?php if ( 'blank' == get_header_textcolor() ) { ?>
		#header h1, #header small {
			padding: 0;
			text-indent: -1000em;
		}
		<?php } else { ?>
		#header h1 a, #header small {
			color: #<?php header_textcolor(); ?>;
		}
		<?php } ?>
	</style>
<?php
}
add_custom_image_header( 'p2_header_style', 'p2_admin_header_style' );

/**
 * Show a notice when the theme is activated - workaround by Ozh (http://old.nabble.com/Activation-hook-exist-for-themes--td25211004.html)
 *
 * @package BuddyPress Theme
 * @since 1.2
 */
function p2_theme_show_notice() { ?>
	<div id="message" class="updated fade">
		<p><?php printf( __( 'Theme activated! This theme contains <a href="%s">custom header image</a> support and <a href="%s">sidebar widgets</a>.', 'buddypress' ), admin_url( 'themes.php?page=custom-header' ), admin_url( 'widgets.php' ) ) ?></p>
	</div>

	<style type="text/css">#message2, #message0 { display: none; }</style>
	<?php
}
if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" )
	add_action( 'admin_notices', 'p2_theme_show_notice' );


// Member Buttons
if ( bp_is_active( 'friends' ) )
	add_action( 'bp_member_header_actions',    'bp_add_friend_button' );

if ( bp_is_active( 'activity' ) )
	add_action( 'bp_member_header_actions',    'bp_send_public_message_button' );

if ( bp_is_active( 'messages' ) )
	add_action( 'bp_member_header_actions',    'bp_send_private_message_button' );

// Group Buttons
if ( bp_is_active( 'groups' ) ) {
	add_action( 'bp_group_header_actions',     'bp_group_join_button' );
	add_action( 'bp_group_header_actions',     'bp_group_new_topic_button' );
	add_action( 'bp_directory_groups_actions', 'bp_group_join_button' );
}

// Blog Buttons
if ( bp_is_active( 'blogs' ) )
	add_action( 'bp_directory_blogs_actions',  'bp_blogs_visit_blog_button' );




/* Post thumbnail support*/
add_theme_support( 'post-thumbnails' );	

$content_width = '632';

// Content Filters
function p2_get_at_name_map() {
	global $wpdb;
	static $name_map = array();
	if ( $name_map ) // since $names is static, the stuff below will only get run once per page load.
 		return $name_map;

	$users = array();
	if ( function_exists( 'get_users' ) )
		$users = get_users();
	else
		$users = get_users_of_blog();

	// get display names (can take out if you only want to handle nicenames)
	foreach ( $users as $user ) {
 		$name_map["@$user->user_login"]['id'] = $user->ID;
		$users_to_array[] = $user->ID;
	}
	// get nicenames (can take out if you only want to handle display names)
	$user_ids = join( ',', array_map( 'intval', $users_to_array ) );

	foreach ( $wpdb->get_results( "SELECT ID, display_name, user_nicename from $wpdb->users WHERE ID IN($user_ids)" ) as $user ) {
 		$name_map["@$user->display_name"]['id'] = $user->ID;
		$name_map["@$user->user_nicename"]['id'] = $user->ID;
	}

	foreach ( $name_map as $name => $values) {
		$username = get_userdata( $values['id'] )->user_login;
 		$name_map[$name]['replacement'] = '<a href="' . esc_url( '/mentions/' . $username ) . '/">' . esc_html( $name ) . '</a>';
	}

	// remove any empty name just in case
	unset( $name_map['@'] );
	return $name_map;
}

add_action( 'init', 'mention_taxonomy', 0 ); // initialize the taxonomy

function mention_taxonomy() {
	register_taxonomy( 'mentions', 'post', array( 'show_ui' => false, 'show_in_nav_menus' => false ) );
	p2_flush_rewrites();
}

function p2_flush_rewrites() {
	if ( false == get_option( 'p2_rewrites_flushed' ) ) {
		update_option( 'p2_rewrites_flushed', true );
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
}

// Filter to be ran on the_content, calls the do_list function from our class
function p2_list_creator( $content ) {
	$list_creator = new P2ListCreator;

	return $list_creator->do_list( $content );
}

// Call the filter on normal, non admin calls (this code exists in ajax.php for the special p2 instances)
if ( ! is_admin() )
	add_filter( 'pre_kses', 'p2_list_creator', 1 );
add_filter( 'pre_comment_content', 'p2_list_creator', 1 );

function p2_at_names( $content ) {
	global $post, $comment;
	$name_map = p2_get_at_name_map(); // get users user_login and display_name map
	$content_original = $content; // save content before @names are found
	$users_to_add = array();

	foreach ( $name_map as $name => $values ) { //loop and...
		$content = preg_replace( "/\B" . preg_quote( $name, '/' ) . "(?![^<]*<\/a)\b/i", $values['replacement'], $content );
		$content = strtr( $content, $name, $name ); // Replaces keys with values longest to shortest, without re-replacing pieces it's already done
		if ( $content != $content_original ) // if the content has changed, an @name has been found.
 			$users_to_add[] = get_userdata( $name_map[$name]['id'] )->user_login; // add that user to an array
		$content_original = $content;
	}
	if ( !empty( $users_to_add ) )
		$cache_data = implode($users_to_add); // if we've got an array, make it a comma delimited string
	if ( isset($cache_data) && $cache_data != wp_cache_get( 'mentions', $post->ID) ) {
		wp_set_object_terms( $post->ID, $users_to_add, 'mentions', true ); // tag the post.
		wp_cache_set( 'mentions', $cache_data, $post->ID);
	}

	return $content;
}

if ( !is_admin() ) add_filter( 'the_content', 'p2_at_names' ); // hook into content
if ( !is_admin() ) add_filter( 'comment_text', 'p2_at_names' ); // hook into comment text

function p2_at_name_highlight( $c ) {

	if ( !( get_query_var( 'taxonomy' ) && 'mentions' == get_query_var( 'taxonomy' ) ) )
		return $c;

	$mention_name = '';
	$names = array();
	$name_map = p2_get_at_name_map();

	if ( get_query_var( 'term' ) )
		$mention_name = get_query_var( 'term' );

	if ( isset( $name_map["@$mention_name"] ) ) {
		$names[] = get_userdata( $name_map["@$mention_name"]['id'] )->display_name;
		$names[] = get_userdata( $name_map["@$mention_name"]['id'] )->user_login;
	}

	foreach ( $names as $key => $name ) {
		$at_name = "@$name";
		$c = str_replace( $at_name, "<span class='mention-highlight'>$at_name</span>", $c );
	}

	return $c;
}

add_filter( 'the_content', 'p2_at_name_highlight' );
add_filter( 'comment_text', 'p2_at_name_highlight' );

// Widgets
function prologue_flush_tag_cache( $post_ID, $post ) {
	// Don't call for anything but normal posts (avoid pages, custom taxonomy, nav menu items)
	if ( ! is_object( $post ) || 'post' !== $post->post_type )
		return;

	wp_cache_delete( 'prologue_theme_tag_list' );
}
add_action( 'save_post', 'prologue_flush_tag_cache', 10, 2 );

function prologue_get_avatar( $user_id, $email, $size ) {
	if ( $user_id )
		return get_avatar( $user_id, $size );
	else
		return get_avatar( $email, $size );
}

function prologue_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>
<li <?php comment_class(); ?> id="comment-<?php comment_ID( ); ?>">
	<?php echo get_avatar( $comment, 32 ); ?>
	<h4>
		<?php comment_author_link(); ?>
		<span class="meta"><?php comment_time(); ?> <?php _e( 'on', 'p2' ); ?> <?php comment_date(); ?> <span class="actions"><a href="#comment-<?php comment_ID( ); ?>"><?php _e( 'Permalink', 'p2' ); ?></a><?php echo comment_reply_link(array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ' )); ?><?php edit_comment_link( __( 'Edit' , 'p2' ), ' | ','' ); ?></span><br /></span>
	</h4>
	<div class="commentcontent<?php if (current_user_can( 'edit_post', $comment->comment_post_ID)) echo( ' comment-edit' ); ?>"  id="commentcontent-<?php comment_ID( ); ?>">
			<?php comment_text( ); ?>
	<?php if ( $comment->comment_approved == '0' ) : ?>
	<p><em><?php _e( 'Your comment is awaiting moderation.', 'p2' ); ?></em></p>
	<?php endif; ?>
	</div>
<?php
}

function p2_title( $before = '<h2>', $after = '</h2>', $returner = false ) {
	if ( is_page() )
		return;

	if ( is_single() && false === p2_the_title( '', '', true ) ) { ?>
		<h2 class="transparent-title"><?php echo the_title(); ?></h2><?php
		return true;
	} else {
		p2_the_title( $before, $after, $returner );
	}
}

/**
 * Generate a nicely formatted post title
 *
 * Ignore empty titles, titles that are auto-generated from the
 * first part of the post_content
 *
 * @package WordPress
 * @subpackage P2
 * @since 1.0.5
 *
 * @param    string    $before    content to prepend to title
 * @param    string    $after     content to append to title
 * @param    string    $echo      echo or return
 * @return   string    $out       nicely formatted title, will be boolean(false) if no title
 */
function p2_the_title( $before = '<h2>', $after = '</h2>', $returner = false ) {
	global $post;

	$temp = $post;
	$t = apply_filters( 'the_title', $temp->post_title );
	$title = $temp->post_title;
	$content = $temp->post_content;
	$pos = 0;
	$out = '';

	// Don't show post title if turned off in options or title is default text
	if ( 1 != (int) get_option( 'prologue_show_titles' ) || 'Post Title' == $title )
		return false;

	$content = trim( $content );
	$title = trim( $title );
	$title = preg_replace( '/\.\.\.$/', '', $title );
	$title = str_replace( "\n", ' ', $title );
	$title = str_replace( '  ', ' ', $title);
	$content = str_replace( "\n", ' ', strip_tags( $content) );
	$content = str_replace( '  ', ' ', $content );
	$content = trim( $content );
	$title = trim( $title );

	// Clean up links in the title
	if ( false !== strpos( $title, 'http' ) )  {
		$split = @str_split( $content, strpos( $content, 'http' ) );
		$content = $split[0];
		$split2 = @str_split( $title, strpos( $title, 'http' ) );
		$title = $split2[0];
	}

	// Avoid processing an empty title
	if ( '' == $title )
		return false;

	// Avoid processing the title if it's the very first part of the post content
	// Which is the case with most "status" posts
	$pos = strpos( $content, $title );
	if ( false === $pos || 0 < $pos ) {
		if ( is_single() )
			$out = $before . $t . $after;
		else
			$out = $before . '<a href="' . get_permalink( $temp->ID ) . '">' . $t . '&nbsp;</a>' . $after;

		if ( $returner )
			return $out;
		else
			echo $out;
	}

	return false;
}

function prologue_loop() {
	global $looping;
	$looping = ($looping === 1 ) ? 0 : 1;
}
add_action( 'loop_start', 'prologue_loop' );
add_action( 'loop_end', 'prologue_loop' );


function p2_comments( $comment, $args, $echo = true ) {
	$GLOBALS['comment'] = $comment;

	$depth = prologue_get_comment_depth( get_comment_ID() );
	$comment_text =  apply_filters( 'comment_text', $comment->comment_content );
	$comment_class = comment_class( '', null, null, false );
	$comment_time = get_comment_time();
	$comment_date = get_comment_date();
	$id = get_comment_ID();
	$avatar = get_avatar( $comment, 32 );
	$author_link = get_comment_author_link();
	$reply_link = prologue_get_comment_reply_link(
				array( 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => ' | ', 'reply_text' => __( 'Reply', 'p2' ) ),
				$comment->comment_ID, $comment->comment_post_ID );
	$can_edit = current_user_can( 'edit_post', $comment->comment_post_ID );
	$edit_comment_url = get_edit_comment_link( $comment->comment_ID );
	$edit_link = $can_edit? " | <a class='comment-edit-link' href='$edit_comment_url' title='".esc_attr__( 'Edit comment', 'p2' )."'>".__( 'Edit', 'p2' )."</a>" : '';
	$content_class = $can_edit? 'commentcontent comment-edit' : 'commentcontent';
	$awaiting_message = $comment->comment_approved == '0'? '<p><em>' . __( 'Your comment is awaiting moderation.', 'p2' ) . '</em></p>' : '';
	$permalink = esc_url( get_comment_link() );
	$permalink_text = __( 'Permalink', 'p2' );
	$date_time = p2_date_time_with_microformat( 'comment' );
	$html = <<<HTML
<li $comment_class id="comment-$id">
		$avatar
		<h4>
				$author_link
				<span class="meta">
						$date_time
						<span class="actions"><a href="$permalink">$permalink_text</a> $reply_link $edit_link</span>
				</span>
		</h4>
		<div class="$content_class" id="commentcontent-$id">
				$comment_text
				$awaiting_message
		</div>
HTML;
	if (!is_single() && get_comment_type() != 'comment' )
		return false;

	if ( $echo )
		echo $html;
	else
		return $html;
}

function get_tags_with_count( $post, $format = 'list', $before = '', $sep = '', $after = '' ) {
	$posttags = get_the_tags($post->ID, 'post_tag' );

	if ( !$posttags )
		return '';

	foreach ( $posttags as $tag ) {
		if ( $tag->count > 1 && !is_tag($tag->slug) ) {
			$tag_link = '<a href="' . get_term_link($tag, 'post_tag' ) . '" rel="tag">' . $tag->name . ' ( ' . number_format_i18n( $tag->count ) . ' )</a>';
		} else {
			$tag_link = $tag->name;
		}

		if ( $format == 'list' )
			$tag_link = '<li>' . $tag_link . '</li>';

		$tag_links[] = $tag_link;
	}

	return apply_filters( 'tags_with_count', $before . join( $sep, $tag_links ) . $after, $post );
}

function tags_with_count( $format = 'list', $before = '', $sep = '', $after = '' ) {
	global $post;
	echo get_tags_with_count( $post, $format, $before, $sep, $after );
}


function latest_post_permalink() {
	global $wpdb;
	$sql = "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' ORDER BY post_date DESC LIMIT 1";
	$last_post_id = $wpdb->get_var($sql);
	$permalink = get_permalink($last_post_id);
	return $permalink;
}

function p2_title_from_content( $content ) {
	$title = p2_excerpted_title( $content, 8 ); // limit title to 8 full words

	// Try to detect image or video only posts, and set post title accordingly
	if ( empty( $title ) ) {
		if ( preg_match("/<object|<embed/", $content ) )
			$title = __( 'Video Post', 'p2' );
		elseif ( preg_match( "/<img/", $content ) )
			$title = __( 'Image Post', 'p2' );
	}

	return $title;
}

if ( is_admin() && ( false === get_option( 'prologue_show_titles' ) ) ) {
	add_option( 'prologue_show_titles', 1);
}

function p2_excerpted_title( $content, $word_count ) {
	$content = strip_tags( $content );
	$words = preg_split( '/([\s_;?!\/\(\)\[\]{}<>\r\n\t"]|\.$|(?<=\D)[:,.\-]|[:,.\-](?=\D))/', $content, $word_count + 1, PREG_SPLIT_NO_EMPTY );

	if ( count( $words ) > $word_count ) {
		array_pop( $words ); // remove remainder of words
		$content = implode( ' ', $words );
		$content = $content . '...';
	} else {
		$content = implode( ' ', $words );
	}

	$content = trim( strip_tags( $content ) );

	return $content;
}

function p2_fix_empty_titles( $post_ID, $post ) {

	// Don't call for anything but normal posts (avoid pages, custom taxonomy, nav menu items)
	if ( ! is_object( $post ) || 'post' !== $post->post_type )
		return;

	if ( empty( $post->post_title ) ) {
		$post->post_title = p2_title_from_content( $post->post_content );
		$post->post_modified = current_time( 'mysql' );
		$post->post_modified_gmt = current_time( 'mysql', 1 );
		return wp_update_post( $post );
	}

}
add_action( 'save_post', 'p2_fix_empty_titles', 10, 2 );

function p2_init_at_names() {
	global $init_var_names, $name;

	// @names
	$init_var_names = array( 'comment_author', 'comment_author_email', 'comment_author_url' );
	foreach($init_var_names as $name)
		if (!isset($$name)) $$name = '';
}
add_action( 'template_redirect' , 'p2_init_at_names' );

function p2_add_head_content() {
	if ( is_home() && is_user_logged_in() ) {
		include_once( ABSPATH . '/wp-admin/includes/media.php' );
	}
}
add_action( 'wp_head', 'p2_add_head_content' );

function prologue_new_post_noajax() {
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || empty( $_POST['action'] ) || $_POST['action'] != 'post' )
	    return;

	if ( !is_user_logged_in() )
		auth_redirect();

	if ( !current_user_can( 'publish_posts' ) ) {
		wp_redirect( home_url( '/' ) );
		exit;
	}

	global $current_user;

	check_admin_referer( 'new-post' );

	$user_id		= $current_user->ID;
	$post_content	= $_POST['posttext'];
	$tags			= $_POST['tags'];

	$post_title = p2_title_from_content( $post_content );

	$post_id = wp_insert_post( array(
		'post_author'	=> $user_id,
		'post_title'	=> $post_title,
		'post_content'	=> $post_content,
		'tags_input'	=> $tags,
		'post_status'	=> 'publish'
	) );

	wp_redirect( home_url( '/' ) );

	exit;
}
add_filter( 'template_redirect', 'prologue_new_post_noajax' );

//Search related Functions

function search_comments_distinct( $distinct ) {
	global $wp_query;
	if (!empty($wp_query->query_vars['s']))
		return 'DISTINCT';
}
add_filter( 'posts_distinct', 'search_comments_distinct' );

function search_comments_where( $where ) {
	global $wp_query, $wpdb;
	if (!empty($wp_query->query_vars['s'])) {
			$or = " OR ( comment_post_ID = ".$wpdb->posts . ".ID  AND comment_approved =  '1' AND comment_content LIKE '%" . like_escape( $wpdb->escape($wp_query->query_vars['s'] ) ) . "%' ) ";
				$where = preg_replace( "/\bor\b/i", $or." OR", $where, 1 );
	}
	return $where;
}
add_filter( 'posts_where', 'search_comments_where' );

function search_comments_join( $join ) {
	global $wp_query, $wpdb, $request;
	if (!empty($wp_query->query_vars['s']))
		$join .= " LEFT JOIN $wpdb->comments ON ( comment_post_ID = ID  AND comment_approved =  '1' )";
	return $join;
}
add_filter( 'posts_join', 'search_comments_join' );

function get_search_query_terms() {
	$search = get_query_var( 's' );
	$search_terms = get_query_var( 'search_terms' );
	if ( !empty($search_terms) ) {
		return $search_terms;
	} else if ( !empty($search) ) {
		return array($search);
	}
	return array();
}

function hilite( $text ) {
	$query_terms = array_filter( array_map( 'trim', get_search_query_terms() ) );
	foreach ( $query_terms as $term ) {
	    $term = preg_quote( $term, '/' );
		if ( !preg_match( '/<.+>/', $text ) ) {
			$text = preg_replace( '/(\b'.$term.'\b)/i','<span class="hilite">$1</span>', $text );
		} else {
			$text = preg_replace( '/(?<=>)([^<]+)?(\b'.$term.'\b)/i','$1<span class="hilite">$2</span>', $text );
		}
	}
	return $text;
}

function hilite_tags( $tags ) {
	$query_terms = array_filter( array_map( 'trim', get_search_query_terms() ) );
	// tags are kept escaped in the db
	$query_terms = array_map( 'esc_html', $query_terms );
	foreach( array_filter((array)$tags) as $tag )
	    if ( in_array( trim($tag->name), $query_terms ) )
	        $tag->name ="<span class='hilite'>". $tag->name . "</span>";
	return $tags;
}

// Highlight text and comments:
add_filter( 'the_content', 'hilite' );
add_filter( 'get_the_tags', 'hilite_tags' );
add_filter( 'the_excerpt', 'hilite' );
add_filter( 'comment_text', 'hilite' );

function iphone_css() {
if ( strstr( $_SERVER['HTTP_USER_AGENT'], 'iPhone' ) or isset($_GET['iphone']) && $_GET['iphone'] ) { ?>
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<style type="text/css">
/* <![CDATA[ */
/* iPhone CSS */
<?php $iphonecss = dirname( __FILE__ ) . '/style-iphone.css'; if ( is_file( $iphonecss ) ) require $iphonecss; ?>
/* ]]> */
</style>
<?php } }
add_action( 'wp_head', 'iphone_css' );

/*
	Modified to replace query string with blog url in output string
*/
function prologue_get_comment_reply_link( $args = array(), $comment = null, $post = null ) {
	global $user_ID;

	if ( post_password_required() )
		return;

	$defaults = array( 'add_below' => 'comment', 'respond_id' => 'respond', 'reply_text' => __( 'Reply', 'p2' ),
		'login_text' => __( 'Log in to Reply', 'p2' ), 'depth' => 0, 'before' => '', 'after' => '' );

	$args = wp_parse_args($args, $defaults);
	if ( 0 == $args['depth'] || $args['max_depth'] <= $args['depth'] )
		return;

	extract($args, EXTR_SKIP);

	$comment = get_comment($comment);
	$post = get_post($post);

	if ( 'open' != $post->comment_status )
		return false;

	$link = '';

	$reply_text = esc_html( $reply_text );

	if ( get_option( 'comment_registration' ) && !$user_ID )
		$link = '<a rel="nofollow" href="' . site_url( 'wp-login.php?redirect_to=' . urlencode( get_permalink() ) ) . '">' . esc_html( $login_text ) . '</a>';
	else
		$link = "<a rel='nofollow' class='comment-reply-link' href='". get_permalink($post). "#" . urlencode( $respond_id ) . "' onclick='return addComment.moveForm(\"" . esc_js( "$add_below-$comment->comment_ID" ) . "\", \"$comment->comment_ID\", \"" . esc_js( $respond_id ) . "\", \"$post->ID\")'>$reply_text</a>";
	return apply_filters( 'comment_reply_link', $before . $link . $after, $args, $comment, $post);
}

function prologue_comment_depth_loop( $comment_id, $depth )  {
	$comment = get_comment( $comment_id );

	if ( isset( $comment->comment_parent ) && 0 != $comment->comment_parent ) {
		return prologue_comment_depth_loop( $comment->comment_parent, $depth + 1 );
	}
	return $depth;
}

function prologue_get_comment_depth( $comment_id ) {
	return prologue_comment_depth_loop( $comment_id, 1 );
}

function prologue_comment_depth( $comment_id ) {
	echo prologue_get_comment_depth( $comment_id );
}


function prologue_poweredby_link() {
	return apply_filters( 'prologue_poweredby_link', sprintf( '<a href="%1$s" rel="generator">%2$s</a>', esc_url( __('http://wordpress.org/', 'p2') ), sprintf( __('Proudly powered by %s.', 'p2'), 'WordPress' ) ) );
}

/* Custom Header Code */
define( 'HEADER_TEXTCOLOR', '3478E3' );
define( 'HEADER_IMAGE', '' ); // %s is theme dir uri
define( 'HEADER_IMAGE_WIDTH', 980);
define( 'HEADER_IMAGE_HEIGHT', 120);




function p2_background_color() {
	$background_color = get_option( 'p2_background_color' );

	if ( '' != $background_color ) :
	?>
	<style type="text/css">
		body {
			background-color: <?php esc_attr_e( $background_color ); ?>;
		}
	</style>
	<?php endif;
}
add_action( 'wp_head', 'p2_background_color' );

function p2_background_image() {
	$p2_background_image = get_option( 'p2_background_image' );

	if ( 'none' == $p2_background_image || '' == $p2_background_image )
		return false;

?>
	<style type="text/css">
		body {
			background-image: url( <?php echo get_template_directory_uri() . '/i/backgrounds/pattern-' . $p2_background_image . '.png' ?> );
		}
	</style>
<?php
}
add_action( 'wp_head', 'p2_background_image' );

function p2_hidden_sidebar_css() {
	$hide_sidebar = get_option( 'p2_hide_sidebar' );
		$sleeve_margin = ( is_rtl() ) ? 'margin-left: 0;' : 'margin-right: 0;';
	if ( '' != $hide_sidebar ) :
	?>
	<style type="text/css">
		.sleeve_main { <?php echo $sleeve_margin;?> }
		#wrapper { background: transparent; }
		#header, #footer, #wrapper { width: 760px; }
	</style>
	<?php endif;
}
add_action( 'wp_head', 'p2_hidden_sidebar_css' );

function p2_user_suggestion() {
	global $wpdb, $current_user;

	if ( ( function_exists( 'is_user_member_of_blog' ) && is_user_member_of_blog( $current_user->id ) ) || ( current_user_can( 'edit_posts' ) ) ) {

		$p2_users = array();
		if ( function_exists( 'get_users' ) )
			$p2_users = get_users();
		else
			$p2_users = get_users_of_blog();

		foreach( $p2_users as $p2_user ) {
			$p2_user_ids[$p2_user->ID] = $p2_user->ID;
		}

		$post_counts = count_many_users_posts( array_keys( $p2_user_ids ) );
		$users = array();

		foreach( $p2_users as $p2_user ) {
			if ( $post_counts[$p2_user->ID] > 0 ) {
				$gravatar = get_avatar( $p2_user->user_email, 32 );
				$users[] = array( 'name' => $p2_user->display_name, 'username' => $p2_user->user_login, 'gravatar' => $gravatar );
			}
		}

	 	$users = apply_filters( "p2_user_suggestion", $users );
		$users = json_encode( $users );
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
			jQuery(document).ready(function() {
					var json = <?php echo $users; ?>;
					var names = [];
					for( var i = 0, ol = json.length; i < ol; i++ ) {
						names[i] = [json[i].name, json[i].username, json[i].gravatar];
					}
					jQuery("#comment").autocomplete(names, {
						matchContains: true,
						hotkeymode:true,
						scroll: false,
						formatItem: function(row) { return row[2] + ' ' + row[0] + ' @' + row[1] },
						startmsg: '<?php echo esc_js( __( 'After typing @, type a name or username to find a member of this site' ) ); ?>',
						noresultsmsg: '<?php echo esc_js( __( 'No matches.' ) ); ?>',
						formatResult: function(row) { return '@' + row[1]; }
					});
					jQuery("#posttext").autocomplete(names, {
						matchContains: true,
						hotkeymode:true,
						scroll: false,
						formatItem: function(row) { return row[2] + ' ' + row[0] + ' @' + row[1] },
						startmsg: '<?php echo esc_js( __( 'After typing @, type a name or username to find a member of this site' ) ); ?>',
						noresultsmsg: '<?php echo esc_js( __( 'No matches.' ) ); ?>',
						formatResult: function(row) { return '@' + row[1]; }
					});
			});
		/* ]]> */
		</script>
<?php
	}
}

add_action( 'wp_footer', 'p2_user_suggestion', 10 );

// Network signup form
function p2_before_signup_form() {
	echo '<div class="sleeve_main"><div id="main">';
}
add_action( 'before_signup_form', 'p2_before_signup_form' );

function p2_after_signup_form() {
	echo '</div></div>';
}
add_action( 'after_signup_form', 'p2_after_signup_form' );

// Enable background
add_custom_background();

// Feed me
add_theme_support( 'automatic-feed-links' );



?>