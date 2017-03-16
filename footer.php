</div>
<!-- /wrapper -->

</div>
<!-- /outer-wrapper -->

<footer>
	<div class="footer-inner">
		<div class="theme-copyright">
			&copy; <?php bloginfo( 'name' ); ?> <?php echo date('Y'); ?>
		</div>
		<?php if( get_theme_mod( 'branches_social_media_links_position_footer' ) == '') { } else { ?>
		<div class="social">
		<?php 
			if(get_theme_mod( 'branches_social_media_links_facebook' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_facebook' ).'" target="_blank">Facebook</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_twitter' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_twitter' ).'" target="_blank">Twitter</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_instagram' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_instagram' ).'" target="_blank">Instagram</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_youtube' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_youtube' ).'" target="_blank">YouTube</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_linkedin' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_linkedin' ).'" target="_blank">LinkedIn</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_googleplus' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_googleplus' ).'" target="_blank">Google+</a>'; 
			}
			if(get_theme_mod( 'branches_social_media_links_pinterest' ) != ''){
				echo '<a href="'.get_theme_mod( 'branches_social_media_links_pinterest' ).'" target="_blank">Pinterest</a>'; 
			}
		?>
		</div>
		<?php } ?>
		<div class="theme-linklove">
			Theme by <a href="http://www.flohauck.de" target="_blank">Flo Hauck</a>
		</div>
		<div class="clear"></div>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>