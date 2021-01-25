<?php if (session()->has('message')) : ?>
	<?= session('message') ?>
<?php endif ?>

<?php if (session()->has('error')) : ?>
	<?= session('error') ?>
<?php endif ?>

<!-- <?php if (session()->has('errors')) : ?>
	<ul class="alert alert-danger" style="padding-left: 15px; list-style: none;">
		<?php foreach (session('errors') as $error) : ?>
			<li>
				<i class="fas fa-exclamation-circle mr-1"></i>
				<?= $error ?>
			</li>
		<?php endforeach ?>
	</ul>
<?php endif ?> -->