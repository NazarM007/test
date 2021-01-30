<?php
get_header();
?>

	<section id="primary" class="content-area col-12">
		<div id="main" class="site-main" role="main">
			<?php
			while (have_posts()):
				the_post();
				$object_name = get_field('object_name');
				$object_floors_count = get_field('object_floors-count');
				$object_build_type = get_field('object_build-type');
				$object_image = get_field('object_image');
				$object_location = get_field('object_location');
				$object_environmental_friendliness = get_field('object_environmental-friendliness');
				$object_apartments = get_field('object_apartments');
				$object_districts = get_the_terms(get_the_ID(), 'district');
			?>
				<article class="card col-12">
					<img class="card-img-top mt-2" src="<?= $object_image ?>" alt="Card image cap">
					<div class="card-body">
						<h5 class="card-title"><?= $object_name ?></h5>
						<ul class="list-group list-group-flush">
							<li class="list-group-item">Количество этажей - <?= $object_floors_count ?></li>
							<li class="list-group-item">Тип строения - <?= $object_build_type ?></li>
							<li class="list-group-item">Экологичность - <?= $object_environmental_friendliness ?></li>
							<li class="list-group-item">Координаты местонахождения - <?= $object_location ?></li>
							<?php if($object_districts): ?>
								<li class="list-group-item">Район - <?= $object_districts[0]->name ?></li>
							<?php endif; ?>
						</ul>
						<?php if($object_apartments): ?>
							<h5 class="card-title mt-3">Помещения:</h5>
							<div class="row">
								<?php foreach($object_apartments as $apartment):?>
									<div class="card col-12 col-sm-6 col-lg-4">
										<img class="card-img-top mt-2" src="<?= $apartment['image'] ?>" alt="Card image cap">
										<ul class="list-group list-group-flush">
											<li class="list-group-item">Площадь (м²) - <?= $apartment['square'] ?></li>
											<li class="list-group-item">Кол.комнат - <?= $apartment['rooms-count'] ?></li>
											<li class="list-group-item">Балкон - <?= $apartment['balcony'] ?></li>
											<li class="list-group-item">Санузел - <?= $apartment['bathroom'] ?></li>
										</ul>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</article>

			<?php
			endwhile;
			wp_reset_postdata();
			?>

		</div>
	</section>

<?php
get_footer();
?>
