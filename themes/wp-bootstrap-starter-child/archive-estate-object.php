<?php
get_header();
?>

	<section id="primary" class="content-area col-12">
		<div id="main" class="site-main" role="main">
			<?php if(have_posts()): ?>
				<header class="page-header">
					<h1 class="page-title"><?php the_archive_title(); ?></h1>
				</header>
				<div class="row">
					<?php
					while (have_posts()):
						the_post();
						$object_name = get_field('object_name');
						$object_floors_count = get_field('object_floors-count');
						$object_build_type = get_field('object_build-type');
						$object_image = get_field('object_image');
						$object_environmental_friendliness = get_field('object_environmental-friendliness');
						$object_apartments_count = count(get_field('object_apartments'));
						$object_districts = get_the_terms(get_the_ID(), 'district');
					?>
						<article class="card col-12 col-sm-6 col-lg-4">
							<img class="card-img-top mt-2" src="<?= $object_image ?>" alt="Card image cap">
							<div class="card-body">
								<h5 class="card-title"><?= $object_name ?></h5>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Количество этажей - <?= $object_floors_count ?></li>
									<li class="list-group-item">Тип строения - <?= $object_build_type ?></li>
									<li class="list-group-item">Экологичность - <?= $object_environmental_friendliness ?></li>
									<li class="list-group-item">Количество помещений - <?= $object_apartments_count ?></li>
									<?php if($object_districts): ?>
										<li class="list-group-item">Район - <?= $object_districts[0]->name ?></li>
									<?php endif; ?>
								</ul>
								<a href="<?= esc_url( get_permalink() ) ?>" class="btn btn-primary mt-2">Подробнее</a>
							</div>
						</article>

					<?php
						endwhile;
						wp_reset_postdata();
					else:
					?>
						<h2>Объекты недвижимости не найдены</h2>
					<?php endif; ?>
				</div>

		</div>
	</section>

<?php
get_footer();
?>
