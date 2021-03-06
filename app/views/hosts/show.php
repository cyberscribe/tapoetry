<div class="hero-unit transatlantic-banner clearfix" itemscope itemtype="http://schema.org/Person" style="margin-top: 10px">
<p>
	<a href="<?php echo $object->url; ?>" target="_blank">
		<img src="<?php echo $object->image_url; ?>" class="pull-left alignleft size-thumbnail" style="margin-right: 2em; margin-bottom: 2em; max-width: 180px; max-height: 180px;" itemprop="image" />
	</a>
</p>
<h2 itemprop="name"><span itemprop="givenName"><?php echo $object->first_name; ?></span> <span itemprop="familyName"><?php echo $object->last_name; ?></span><br /><small itemprop="workLocation"><?php echo $object->location; ?></small></h2>
<p itemprop="description"><?php echo $object->description; ?> <br /><a class="btn-small btn-primary pull-right" href="<?php echo $object->url; ?>" target="_blank" itemprop="sameAs url">More<i class="icon-share icon-white"></i></a></p>
</div>

<?php if (sizeof($object->readings) > 1): ?>
    <h3>Hosted Readings</h3>
<?php else: ?>
    <h3>Hosted Reading</h3>
<?php endif; ?>
<?php $this->render_view('readings/_banner', array('collection' => $object->readings, 'locals' => array('show_date' => true))); ?>
<p class="pull-right">
	<?php echo $this->html->link('All Hosts', array('controller' => 'hosts')); ?>
</p>
