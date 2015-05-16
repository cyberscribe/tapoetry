<h2><?php echo MvcInflector::pluralize($model->name); ?></h2>

<ul class="thumbnails">
<?php foreach($objects as $object): ?>
	<?php $this->render_view('_card', array('locals' => array('object' => $object, 'span' => 'span3'))); ?>
<?php endforeach; ?>
</ul>

<?php echo $this->pagination(); ?>
