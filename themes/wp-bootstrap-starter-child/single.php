<?php
get_header();
?>

	<section id="primary" class="content-area col-sm-12 col-lg-8">
		<div id="main" class="site-main" role="main">
			<?php
			if(have_posts()):
				while (have_posts()):
					the_post();
			?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					    <header class="entry-header">
							<h1 class="entry-title"><?php the_title(); ?>
						</header>

						<div class="entry-content">
							<?php the_content(); ?>
						</div>

					</article>

			<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

<?php
get_footer();
?>