<?php
get_header();
?>

	<section id="primary" class="content-area col-sm-12 col-lg-8">
		<div id="main" class="site-main" role="main">
			<?php if(have_posts()): ?>
				<header class="page-header">
					<?php the_archive_title( '<h1 class="page-title">', '</h1>' );?>
				</header>
			<?php
				while (have_posts()):
					the_post();
			?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					    <header class="entry-header">
							<h1 class="entry-title"><a href="<?= esc_url( get_permalink() ) ?>"><?php the_title(); ?></a></h1>
						</header>

						<div class="entry-content">
							<?php the_content(); ?>
						</div>

					</article>

			<?php
				endwhile;
				wp_reset_postdata();
			else:
			?>
				<h2>Записи не найдены</h2>
			<?php endif; ?>

		</div>
	</section>

<?php
get_footer();
?>
