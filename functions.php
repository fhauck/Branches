<?php
	
/*  Thumbnail upscale
/* ------------------------------------ */ 
function alx_thumbnail_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
    if ( !$crop ) return null; // let the wordpress default function handle this
 
    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
 
    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);
 
    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );
 
    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'alx_thumbnail_upscale', 10, 6 );


// Theme setup
add_action( 'after_setup_theme', 'branches_setup' );

function branches_setup() {
	
	// Automatic feed
	add_theme_support( 'automatic-feed-links' );
	
	// Add nav menu
	register_nav_menu( 'primary', __('Primary Menu','branches') );
	
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size ( 360, 230, true );
	
	add_image_size( 'big-header-xxlarge', 2320, 980, true );
	add_image_size( 'big-header-xlarge', 1740, 735, true );
	add_image_size( 'big-header-large', 1160, 490, true );
	add_image_size( 'big-header-medium', 766, 323, true );
	add_image_size( 'big-header-small', 580, 245, true );

	add_image_size( 'post-thumbnail-medium', 720, 460, true );
	add_image_size( 'post-thumbnail-small', 360, 230, true );	

	// Make the theme translation ready
	load_theme_textdomain('branches', get_template_directory() . '/languages');
	
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable($locale_file) )
	  require_once($locale_file);
	  
	// Set content-width
	global $content_width;
	if ( ! isset( $content_width ) ) $content_width = 882;
}




// Register and enqueue styles
function branches_load_style() {
	if ( !is_admin() ) {
	    wp_enqueue_style( 'branches_googleFonts', '//fonts.googleapis.com/css?family=Noto+Serif:400,700|Open+Sans:400,600,700,800,800i' );
	    wp_enqueue_style( 'branches_style', get_stylesheet_uri() );
	}
}

add_action('wp_print_styles', 'branches_load_style');

function insert_jquery(){
wp_enqueue_script('jquery', false, array(), false, false);
}
add_filter('wp_enqueue_scripts','insert_jquery',1);

// Add editor styles
add_action( 'init', 'branches_add_editor_styles' );

function branches_add_editor_styles() {
    add_editor_style( 'branches-editor-styles.css' );
    $font_url = '//fonts.googleapis.com/css?family=Noto+Serif:400,700|Open+Sans:400,600,700,800,800i';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}

// Add sidebar widget area
add_action( 'widgets_init', 'branches_sidebar_reg' ); 

function branches_sidebar_reg() {
	register_sidebar(array(
	  'name' => __( 'Sidebar', 'branches' ),
	  'id' => 'sidebar',
	  'description' => __( 'Widgets in this area will be shown in the sidebar.', 'branches' ),
	  'before_title' => '<h3 class="widget-title">',
	  'after_title' => '</h3>',
	  'before_widget' => '<div class="widget %2$s"><div class="widget-content">',
	  'after_widget' => '</div><div class="clear"></div></div>'
	));
}
 function branches_sanitize_int($yourVar){
	 //return true;
	 $sanitizedNum = filter_var($yourVar, FILTER_SANITIZE_NUMBER_INT);
	 return $sanitizedNum;
 }

// Branches theme options
class branches_Customize {

	public static function branches_register ( $wp_customize ) {
   
      
		// Add Section for Logo
		$wp_customize->add_section( 'branches_logo_section' , array(
		    'title'       => __( 'Logo', 'branches' ),
		    'priority'    => 40,
		    'description' => __('Upload a logo to replace the default site title in the header', 'branches'),
		) );
		
		// Add Setting for Logo
		$wp_customize->add_setting( 'branches_logo', 
			array( 
				'sanitize_callback' => 'esc_url_raw'
			) 
		);
		
		// Add Control for Logo
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'branches_logo', array(
		    'label'    => __( 'Logo', 'branches' ),
		    'section'  => 'branches_logo_section',
		    'settings' => 'branches_logo',
		) ) );
		
		
		// Add Setting for Accent Color
		$wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
		 array(
		    'default' => '#000000', //Default setting/value to save
		    'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
		    'transport' => 'postMessage', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
		    'sanitize_callback' => 'sanitize_hex_color'            
		 ) 
		);
		
		// Add Control for Accent Color
		$wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
		 $wp_customize, //Pass the $wp_customize object (required)
		 'branches_accent_color', //Set a unique ID for the control
		 array(
		    'label' => __( 'Accent Color', 'branches' ), //Admin-visible name of the control
		    'section' => 'colors', //ID of the section this control should render in (can be one of yours, or a WordPress default section)
		    'settings' => 'accent_color', //Which setting to load and manipulate (serialized is okay)
		    'priority' => 10, //Determines the order this control appears in for the specified section
		 ) 
		) );
	      
	      
	
		$wp_customize->add_section('branches_sidebar',
		    array(
		        'title' => 'Sidebar',
		        'description' => __( 'Define where to show the Sidebar.', 'branches' ),
		        'priority' => 65,
		    )
		);
	
		$wp_customize->add_setting(
		    'branches_sidebar_frontpage',
		    array(
		        'default' => true,
		        'sanitize_callback' => 'branches_sanitize_int'
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_sidebar_singlepage',
		    array(
		        'default' => true,
		        'sanitize_callback' => 'branches_sanitize_int'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_sidebar_frontpage',
		    array(
		        'label' => __( 'Show Sidebar on front page and on archive/category pages', 'branches' ),
		        'section' => 'branches_sidebar',
		        'type' => 'checkbox',
		    )
		);  
		
		$wp_customize->add_control(
		    'branches_sidebar_singlepage',
		    array(
		        'label' => __( 'Show Sidebar on single post and page template', 'branches' ),
		        'section' => 'branches_sidebar',
		        'type' => 'checkbox',
		    )
		);  
		
		
		$wp_customize->add_section('branches_posts_pages',
		    array(
		        'title' => 'Posts/Pages',
		        'description' => __( 'Some options for the single post and the page template.', 'branches' ),
		        'priority' => 75,
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_show_header_singlepost',
		    array(
		        'default' => false,
		        'sanitize_callback' => 'branches_sanitize_int'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_show_header_singlepost',
		    array(
		        'label' => __( 'Show Featured Image on single post template', 'branches' ),
		        'section' => 'branches_posts_pages',
		        'type' => 'checkbox',
		    )
		);  
		
		$wp_customize->add_section('branches_social_media_links',
		    array(
		        'title' => 'Social Media Links',
		        'description' => __( 'Link to your Social Media Profiles. Just paste the URL to your Profile in the text fields.', 'branches' ),
		        'priority' => 85,
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_position_header',
		    array(
		        'default' => true,
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		
		$wp_customize->add_control(
		    'branches_social_media_links_position_header',
		    array(
		        'label' => __( 'Show Social Media Links in Header', 'branches' ),
		        'section' => 'branches_social_media_links',
		        'type' => 'checkbox',
		    )
		); 
		
		$wp_customize->add_setting(
		    'branches_social_media_links_position_footer',
		    array(
		        'default' => false,
		        'sanitize_callback' => 'esc_url_raw'
		    )
		); 

		$wp_customize->add_control(
		    'branches_social_media_links_position_footer',
		    array(
		        'label' => __( 'Show Social Media Links in Footer', 'branches' ),
		        'section' => 'branches_social_media_links',
		        'type' => 'checkbox',
		    )
		);  
				
		$wp_customize->add_setting(
		    'branches_social_media_links_facebook',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_facebook',
		    array(
		        'label' => 'Facebook',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		); 
		
		$wp_customize->add_setting(
		    'branches_social_media_links_twitter',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_twitter',
		    array(
		        'label' => 'Twitter',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_instagram',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_instagram',
		    array(
		        'label' => 'Instagram',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_youtube',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_youtube',
		    array(
		        'label' => 'YouTube',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_linkedin',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_linkedin',
		    array(
		        'label' => 'LinkedIn',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_googleplus',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_googleplus',
		    array(
		        'label' => 'Google+',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_social_media_links_pinterest',
		    array(
		        'default' => '',
		        'sanitize_callback' => 'esc_url_raw'
		    )
		);
		
		$wp_customize->add_control(
		    'branches_social_media_links_pinterest',
		    array(
		        'label' => 'Pinterest',
		        'section' => 'branches_social_media_links',
		        'type' => 'text',
		    )
		);

	}

   public static function branches_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           <?php self::branches_generate_css('nav ul li.current-menu-item a', 'color', 'accent_color'); ?>
	           <?php self::branches_generate_css('nav ul li a:hover', 'color', 'accent_color'); ?>
	           <?php self::branches_generate_css('.read-more', 'color', 'accent_color'); ?>
	           <?php self::branches_generate_css('nav ul li.current-post-ancestor a', 'color', 'accent_color'); ?>
	           <?php self::branches_generate_css('nav ul li.current-menu-parent a', 'color', 'accent_color'); ?>
	           <?php self::branches_generate_css('nav ul li.current-post-parent a', 'color', 'accent_color'); ?>
	      </style> 
	      
	      <!--/Customizer CSS-->
	      
      <?php
   }
   

   public static function branches_generate_css( $selector, $style, $mod_name, $prefix='', $postfix='', $echo=true ) {
      $return = '';
      $mod = get_theme_mod($mod_name);
      if ( ! empty( $mod ) ) {
         $return = sprintf('%s { %s:%s; }',
            $selector,
            $style,
            $prefix.$mod.$postfix
         );
         if ( $echo ) {
            echo $return;
         }
      }
      return $return;
    }
}

// Setup the Theme Customizer settings and controls...
add_action( 'customize_register' , array( 'branches_Customize' , 'branches_register' ) );

// Output custom CSS to live site
add_action( 'wp_head' , array( 'branches_Customize' , 'branches_header_output' ) );


// Change the length of excerpts
function branches_custom_excerpt_length( $length ) {
	return 42;
}
add_filter( 'excerpt_length', 'branches_custom_excerpt_length', 999 );


// Change the excerpt ellipsis
function branches_new_excerpt_more( $more ) {
	return ' ...';
}
add_filter( 'excerpt_more', 'branches_new_excerpt_more' );


// Return an alternate title, without prefix, for every type used in the get_the_archive_title().
add_filter('get_the_archive_title', function ($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    } elseif ( is_year() ) {
        $title = get_the_date( _x( 'Y', 'yearly archives date format', 'branches' ) );
    } elseif ( is_month() ) {
        $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'branches' ) );
    } elseif ( is_day() ) {
        $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'branches' ) );
    } elseif ( is_tax( 'post_format' ) ) {
        if ( is_tax( 'post_format', 'post-format-aside' ) ) {
            $title = _x( 'Asides', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
            $title = _x( 'Galleries', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
            $title = _x( 'Images', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
            $title = _x( 'Videos', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
            $title = _x( 'Quotes', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
            $title = _x( 'Links', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
            $title = _x( 'Statuses', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
            $title = _x( 'Audio', 'post format archive title', 'branches' );
        } elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
            $title = _x( 'Chats', 'post format archive title', 'branches' );
        }
    } elseif ( is_post_type_archive() ) {
        $title = post_type_archive_title( '', false );
    } elseif ( is_tax() ) {
        $title = single_term_title( '', false );
    } else {
        $title = __( 'Archives', 'branches' );
    }
    return $title;
});


function wpb_move_comment_field_to_bottom( $fields ) {
$comment_field = $fields['comment'];
unset( $fields['comment'] );
$fields['comment'] = $comment_field;
return $fields;
}

add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );

if ( is_singular() ) wp_enqueue_script( "comment-reply" );

add_theme_support( 'title-tag' );

add_filter( 'wp_title', 'wpdocs_hack_wp_title_for_home' );
 
/**
 * Customize the title for the home page, if one is not set.
 *
 * @param string $title The original title.
 * @return string The title to use.
 */
function wpdocs_hack_wp_title_for_home( $title )
{
  if ( empty( $title ) && ( is_home() || is_front_page() ) ) {
    $title = get_bloginfo( 'name', 'display' )  . ' | ' . get_bloginfo( 'description' );
  }
  return $title;
}

// branches comment function
if ( ! function_exists( 'branches_comment' ) ) :

function branches_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
	
		<?php __( 'Pingback:', 'branches' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'branches' ), '<span class="edit-link">', '</span>' ); ?>
		
	</li>
	<?php
			break;
		default :
		global $post;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	
		<div id="comment-<?php comment_ID(); ?>" class="comment">
			
			<?php echo get_avatar( $comment, 160 ); ?>
			
			<?php if ( $comment->user_id === $post->post_author ) : ?>
					
				<a class="comment-author-icon" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="<?php _e('Post author','branches'); ?>">
				
					<div class="genericon genericon-user"></div>
					
				</a>
			
			<?php endif; ?>
			
			<div class="comment-inner">
			
				<div class="comment-header">
											
					<h4><?php echo get_comment_author_link(); ?></h4>
				
				</div> <!-- /comment-header -->
				
				<div class="comment-content post-content">
			
					<?php comment_text(); ?>
					
				</div><!-- /comment-content -->
				
				<div class="comment-meta">
					
					<div class="fleft">
						<div class="genericon genericon-day"></div><a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>" title="<?php echo get_comment_date() . ' at ' . get_comment_time(); ?>"><?php echo get_comment_date(get_option('date_format')); ?></a>
						<?php edit_comment_link( __( 'Edit', 'branches' ), '<div class="genericon genericon-edit"></div>', '' ); ?>
					</div>
					
					<?php if ( '0' == $comment->comment_approved ) : ?>
				
						<div class="comment-awaiting-moderation fright">
							<div class="genericon genericon-show"></div><?php _e( 'Your comment is awaiting moderation.', 'branches' ); ?>
						</div>
						
					<?php else : ?>
						
						<?php 
							comment_reply_link( array( 
								'reply_text' 	=>  	__('Reply','branches'),
								'depth'			=> 		$depth, 
								'max_depth' 	=> 		$args['max_depth'],
								'before'		=>		'<div class="fright"><div class="genericon genericon-reply"></div>',
								'after'			=>		'</div>'
								) 
							); 
						?>
					
					<?php endif; ?>
					
					<div class="clear"></div>
					
				</div> <!-- /comment-meta -->
								
			</div> <!-- /comment-inner -->
										
		</div><!-- /comment-## -->
				
	<?php
		break;
	endswitch;
}
endif;

?>