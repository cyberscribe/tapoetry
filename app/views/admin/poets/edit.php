<h2><?php echo MvcInflector::titleize($this->action); ?> <?php echo MvcInflector::titleize($model->name); ?></h2>

<?php echo $this->form->create($model->name); ?>
<?php echo $this->form->input('first_name'); ?>
<?php echo $this->form->input('last_name'); ?>
<?php echo $this->form->input('location'); ?>
<?php echo $this->form->input('url', array('label' => 'URL', 'style' => 'width: 300px;')); ?>
<?php echo $this->form->input('image_url', array('label' => 'Photo', 'style' => 'width: 300px;')); ?>
<?php echo $this->form->input('description', array('style' => 'width: 600px; height: 200px')); ?>
<?php echo $this->form->end('Update'); ?>
<?php $this->render_view('_upload_js'); ?>
