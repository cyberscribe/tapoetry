<h2><?php echo MvcInflector::titleize($this->action); ?> <?php echo MvcInflector::titleize($model->name); ?></h2>

<?php echo $this->form->create($model->name); ?>
<?php echo $this->form->input('title', array('style' => 'width: 200px;')); ?>
<?php echo $this->form->belongs_to_dropdown('Partner', $partners, array('style' => 'width: 200px;', 'empty' => true)); ?>
<?php echo $this->form->belongs_to_dropdown('Host', $hosts, array('style' => 'width: 200px;', 'empty' => true)); ?>
<?php echo $this->form->input('date', array('label' => 'Date (YYYY-MM-DD)')); ?>
<?php echo $this->form->input('time', array('label' => 'Time (24-hour clock)')); ?>
<?php echo $this->form->input('description', array('style' => 'width: 600px; height: 200px')); ?>
<?php echo $this->form->input('url', array('style' => 'width: 200px;')); ?>
<?php echo $this->form->input('banner_url', array('style' => 'width: 200px;')); ?>
<?php echo $this->form->input('video_url', array('style' => 'width: 200px;')); ?>
<?php echo $this->form->has_many_dropdown('Poet', $poets, array('style' => 'width: 200px;', 'empty' => true)); ?>
<?php echo $this->form->end('Add'); ?>
