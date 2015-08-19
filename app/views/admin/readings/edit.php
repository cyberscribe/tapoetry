<?php
$formatted_time = preg_replace('/:00$/', '', $object->time);
?>

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
<?php echo $this->form->end('Update'); ?>
<?php if(extension_loaded('gd')): ?>
<h2>Download Graphics</h2>
<ul>
<li><?php echo $this->html->link('Banner', array('controller' => 'readings', 'action' => 'banner', 'id' => $object->id)); ?> <span class="dashicons dashicons-download"></span></li>
<li><?php echo $this->html->link('Splash', array('controller' => 'readings', 'action' => 'splash', 'id' => $object->id)); ?> <span class="dashicons dashicons-download"></span></li>
</ul>
<?php endif ?>
<h2>Sample Post</h2>
<p>Copy and paste this html into the <em><strong>text</strong></em> tab of a new post to publicise the event on the website and send this content to our email newsletter subscribers. Be sure to preview the post before clicking publish.</p>
<h3><?php echo sprintf('%s, %s %s', $object->title, date('F', strtotime($object->date)),date('Y', strtotime($object->date))); ?></h3>
<textarea rows="10" cols="80" onclick="this.focus();this.select()" readonly="readonly" style="width: 600px; height; 200px; white-space: pre-wrap">
<?php echo sprintf('<a href="%s"><img src="%s" alt="%s" class="alignnone size-full" style="width: 100%%; max-width: 1200px; border: 0" /></a>', $object->url, $object->banner_url ? $object->banner_url : 'http://www.transatlanticpoetry.com/readings/banner/'.$object->id, $object->title); ?>


<?php if (sizeof($object->poets) == 2): ?>
<?php echo sprintf('Our next live worldwide poetry reading will feature <a href="%s">%s %s</a> from %s',Poet::get_guid($object->poets[0]), $object->poets[0]->first_name, $object->poets[0]->last_name, $object->poets[0]->location); ?>
<?php echo sprintf(' and <a href="%s">%s %s</a> from %s, ',Poet::get_guid($object->poets[1]), $object->poets[1]->first_name, $object->poets[1]->last_name, $object->poets[1]->location); ?>
<?php echo sprintf('hosted by <a href="%s">%s %s</a> of <a href="%s">%s</a>.',Host::get_guid($object->host),$object->host->first_name, $object->host->last_name, Partner::get_guid($object->partner),$object->partner->name); ?>
<?php else: ?>
<?php echo $object->description; ?>
<?php endif; ?>


<?php 
date_default_timezone_set('Europe/London'); //force all dates expressed to be London time
$date = date('F jS, Y', strtotime($object->date));
$time = date('H.i T', strtotime($object->date.' '.$object->time)); ?>
<?php echo sprintf('Tune in for free on <a href="%s" target="_blank">%s at %s</a>. The poets will be reading their work and answering your questions live on air.',$object->url,$date,$time); ?>
<?php if (sizeof($object->poets) == 2): ?>


<?php echo $object->poets[0]->description; ?>


<?php echo $object->poets[1]->description; ?>
<?php endif; ?>


<?php echo $object->partner->description; ?> <?php echo $object->host->description; ?>


<?php echo sprintf('Tune in live and ask your questions on <a href="%s">The Google Hangouts Event Page</a> or find out more about all of our previous and upcoming broadcasts at <a href="http://www.transatlanticpoetry.com">www.transatlanticpoetry.com</a>.',$object->url); ?>
</textarea>
