<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( has_post_thumbnail() ) { ?>
	<div class="teaser-image">
		<a href="<?php the_permalink(); ?>" class="post-info-left-top"><?php echo get_the_date(); ?></a>
		<a href="<?php the_permalink(); ?>#comments" class="post-info-right-top"><?php comments_number( '0 '. __( 'Comments', 'branches' ) .'', '1 '. __( 'Comment', 'branches' ) .'', '%  '. __( 'Comments', 'branches' ) .'' ); ?></a>
		<span class="post-info-left-bottom"><?php echo get_the_category_list(', '); ?></span>
		<a class="teaser-image-link" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('post-thumbnail-medium'); ?></a>
	</div>
	<?php } ?>
	<div class="<?php if ( !has_post_thumbnail() ) { ?>full-width <?php } ?>teaser-content">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php the_excerpt(); ?>
		<p><a href="<?php the_permalink(); ?>" class="read-more">&raquo; <?php _e('read more','branches'); ?></a></p>
	</div>
	<div class="clear"></div>
</article>