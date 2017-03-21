<?php get_header(); ?>

<?php if( get_theme_mod( 'branches_show_header_singlepost' ) == '') { } else { ?>
	<article id="sticky-post">
		<?php the_post_thumbnail('branches_big-header-xxlarge'); ?>
	</article>
<?php } ?>

<div id="post-area" class="single-post-wrapper <?php if( get_theme_mod( 'branches_sidebar_singlepage' ) == '') { ?>fullwidth<?php } ?>">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>


	<div id="single-post" <?php if( get_theme_mod( 'branches_show_header_singlepost' ) == '') { } else { ?>style="margin-top: 40px;"<?php } ?>>
		
		<h1><?php the_title(); ?></h1>
		
		<div class="post-info">
			<?php echo get_the_date(); ?>&nbsp;&nbsp;|&nbsp;&nbsp;<?php _e('by','branches'); ?> <span class="bypostauthor"><?php echo get_the_author(); ?></span>
			<?php edit_post_link(__( 'Edit Post', 'branches' ), '&nbsp;&nbsp;|&nbsp;&nbsp;', ''); ?>
		</div>
		
		<div class="entry">
			
			<?php the_content(); ?>
			
		</div>
		
		<?php wp_link_pages( array(
			'before'      => '<nav class="navigation pagination"><div class="nav-links">',
			'after'       => '</div></nav>',
			'link_before' => '<span class="page-numbers">',
			'link_after'  => '</span>',
			) );
		?>
		
		<?php
		echo get_the_tag_list('<div class="tag-list">',', ','</div>');
		?>
		
		<?php comments_template( '', true ); ?>
		
	</div>
<?php endwhile; ?>
<?php endif; ?>
</div>

<?php if( get_theme_mod( 'branches_sidebar_singlepage' ) == '') { ?>

<?php } else { ?>
<div id="sidebar">
	<?php get_sidebar(); ?>
</div>
<?php } ?>

<div class="clear"></div>

<?php get_footer(); ?>