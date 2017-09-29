<h2><?php echo MvcInflector::pluralize($model->name); ?></h2>

<ul class="thumbnails">
<?php foreach($objects as $object): ?>
    <?php if ($object->published == 1): ?>
	    <?php $this->render_view('_card', array('locals' => array('object' => $object, 'span' => 'span3'))); ?>
    <?php endif; ?>
<?php endforeach; ?>
</ul>

<?php echo $this->pagination(); ?>
