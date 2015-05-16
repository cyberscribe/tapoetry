<div class="hero-unit transatlantic-banner clearfix" itemscope itemtype="http://schema.org/Organization" style="margin-top: 10px">
<a href="<?php echo $object->url; ?>" target="_blank">
	<img src="<?php echo $object->image_url; ?>" class="pull-left alignleft size-thumbnail" itemprop="image" style="margin-right: 2em; margin-bottom: 2em; max-width: 180px; max-height: 180px;" />
</a>
<h2><span itemprop="name"><?php echo $object->name; ?></span><br />
<small itemprop="location"><?php echo $object->location; ?></small></h2>
<p itemprop="description"><?php echo $object->description; ?> <br /><a class="btn-small btn-primary pull-right" href="<?php echo $object->url; ?>" target="_blank" itemprop="sameAs url">More <i class="icon-share icon-white"></i></a></p>
</div>

<h3>Supported Readings</h3>
<?php $this->render_view('readings/_banner', array('collection' => $object->readings)); ?>

<p class="pull-right">
	<?php echo $this->html->link('All Partners', array('controller' => 'partners')); ?>
</p>
