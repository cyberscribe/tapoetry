<h2><?php echo MvcInflector::pluralize($model->name); ?></h2>

<?php foreach($objects as $object): ?>
    <p class="span12">
	    <?php $this->render_view('_banner', array('locals' => array('object' => $object, 'span' => 'span3'))); ?>
    </p>
<?php endforeach; ?>

<?php echo $this->pagination(); ?>
