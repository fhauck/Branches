<?php

// Theme setup
function branches_setup() {
	
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );
	
	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );
	
	// Automatic feed
	add_theme_support( 'automatic-feed-links' );
	
	// Add nav menu
	register_nav_menu( 'primary', __('Primary Menu','branches') );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'branches' ),
	) );
	
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size ( 360, 230, true );
	
	add_image_size( 'branches_big-header-xxlarge', 2320, 980, true );
	add_image_size( 'branches_big-header-xlarge', 1740, 735, true );
	add_image_size( 'branches_big-header-large', 1160, 490, true );
	add_image_size( 'branches_big-header-medium', 766, 323, true );
	add_image_size( 'branches_big-header-small', 580, 245, true );

	add_image_size( 'branches_post-thumbnail-medium', 720, 460, true );
	add_image_size( 'branches_post-thumbnail-small', 360, 230, true );	
	

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
add_action( 'after_setup_theme', 'branches_setup' );

// Add Custom Logo support
function branches_custom_logo_setup() {
    $defaults = array(
		'width'			=> 600,
		'height'		=> 400,
		'flex-height'	=> true,
		'flex-width'	=> true
    );
    add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'branches_custom_logo_setup' );

// Register and enqueue styles
function branches_load_style() {
	if ( !is_admin() ) {
	    wp_enqueue_style( 'branches_googleFonts', '//fonts.googleapis.com/css?family=Noto+Serif:400,700|Open+Sans:400,600,700,800,800i' );
	    wp_enqueue_style( 'branches_style', get_stylesheet_uri() );
	}
}
add_action('wp_print_styles', 'branches_load_style');

// Add editor styles
function branches_add_editor_styles() {
    add_editor_style( 'branches-editor-styles.css' );
    $font_url = '//fonts.googleapis.com/css?family=Noto+Serif:400,700|Open+Sans:400,600,700,800,800i';
    add_editor_style( str_replace( ',', '%2C', $font_url ) );
}
add_action( 'init', 'branches_add_editor_styles' );

// Add sidebar widget area
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
add_action( 'widgets_init', 'branches_sidebar_reg' ); 


// Enqueue scripts and styles.
function branches_scripts() {

	wp_enqueue_script( 'branches-scripts', get_template_directory_uri() . '/js/branches-scripts.js', array( 'jquery' ), '', true);


	if ( comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'branches_scripts' );




function branches_sanitize_int($yourVar){
	//return true;
	$sanitizedNum = filter_var($yourVar, FILTER_SANITIZE_NUMBER_INT);
	return $sanitizedNum;
}

// Branches theme options
class branches_Customize {

	public static function branches_register ( $wp_customize ) {
   
      
		
		
		// Add Setting for Accent Color
		$wp_customize->add_setting( 'accent_color', //No need to use a SERIALIZED name, as `theme_mod` settings already live under one db record
		 array(
		    'default' => '#dd3333', //Default setting/value to save
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
		        'default' => false,
		        'sanitize_callback' => 'branches_sanitize_int'
		    )
		);
		
		$wp_customize->add_setting(
		    'branches_sidebar_singlepage',
		    array(
		        'default' => false,
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
	
	}

   public static function branches_header_output() {
      ?>
      
	      <!-- Customizer CSS --> 
	      
	      <style type="text/css">
	           
	           
	           <?php self::branches_generate_css('.read-more, nav ul li a:hover, nav ul li.current-menu-item > a, nav ul li.current-post-ancestor > a, nav ul li.current-menu-parent > a, nav ul li.current-post-parent > a, nav ul li.current_page_ancestor > a, nav ul li.current-menu-ancestor > a', 'color', 'accent_color'); ?>

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

function branches_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'branches_move_comment_field_to_bottom' );

if ( is_singular() ) wp_enqueue_script( "comment-reply" );
 

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