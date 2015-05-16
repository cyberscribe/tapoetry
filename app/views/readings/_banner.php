<?php
	$title = $object->title;
	$date = date('F jS, Y', strtotime($object->date)). ': ';
	$alt = $title . ' on ' .$date;
?>
<div class="span12 clearfix">
<p>
	<a href="<?php echo Reading::get_guid($object); ?>">
            <?php if($object->banner_url): ?>
        	<img src="<?php echo $object->banner_url; ?>" style="width: 100%; max-width: 940px" alt="<?php echo $alt; ?>">
            <?php else: ?>
        	<img src="/readings/banner/<?php echo $object->id; ?>/" style="width: 100%; max-width: 940px" alt="<?php echo $alt; ?>">
            <?php endif; ?>
    </a>
</p>
</div>
