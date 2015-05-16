<li class="<?php echo $span; ?>">
<div class="thumbnail pagination-centered" style="height: 320px;">
	<a href="<?php echo Poet::get_guid($object); ?>">
		<img src="<?php echo $object->image_url; ?>" alt="<?php echo $object->first_name.' '.$object->last_name; ?>" style="max-height: 200px;" class="img-circle" />
		<h4><span itemprop="performer"><span><?php echo $object->first_name; ?></span> <span><?php echo $object->last_name; ?></span></span><br />
		<small itemprop="location"><?php echo $object->location; ?></small>
		</h4>
	</a>
</div>
</li>
