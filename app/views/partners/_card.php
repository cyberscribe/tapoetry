<li class="<?php echo $span; ?>">
<div class="thumbnail pagination-centered" style="height: 320px;">
	<a href="<?php echo Partner::get_guid($object); ?>">
		<img src="<?php echo $object->image_url; ?>" alt="<?php echo $object->name; ?>" style="max-height: 200px;" />
		<h4><span><?php echo $object->name; ?></span><br />
        <small><?php echo $object->location; ?></small>
		</h4>
	</a>
</div>
</li>
