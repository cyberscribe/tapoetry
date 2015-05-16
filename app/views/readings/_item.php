<li>
<?php
	$title = $object->title;
	echo date('F jS, Y', strtotime($object->date)). ': ';
?>
	<a href="<?php echo Reading::get_guid($object); ?>">
        <?php echo $object->__name; ?>
    </a>
</li>
