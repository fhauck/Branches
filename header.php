<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- outer-wrapper -->
<div id="outer-wrapper">

<!-- wrapper -->
<div id="wrapper">
	
	<header>
		
		<div id="logo">
			<?php if ( get_theme_mod( 'branches_logo' ) ) : ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img id="logo-img" src="<?php echo esc_url( get_theme_mod( 'branches_logo' ) ); ?>" alt="<?php bloginfo( 'title' ); ?>">
				</a>
			<?php elseif ( get_bloginfo( 'description' ) || get_bloginfo( 'title' ) ) : ?>
				<h1 id="logo-maintitle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 id="logo-subtitle"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'description' ); ?></a></h2>
			<?php endif; ?>
		</div>
		
	</header>
	
	<nav>
		
		<a href="javascript:;" class="burger-menu">
		  <?php esc_html_e('Navigation','branches'); ?>
		</a>
		<ul class="main-menu">
		<?php if ( has_nav_menu( 'primary' ) ) {
															
			wp_nav_menu( array( 
			
				'container' => '', 
				'items_wrap' => '%3$s',
				'theme_location' => 'primary'
											
			) ); } else {
		
			wp_list_pages( array(
			
				'container' => '',
				'title_li' => ''
			
			));
			
		} ?>
		</ul>
	</nav>