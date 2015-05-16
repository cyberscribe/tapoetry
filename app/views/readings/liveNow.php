<div class="row-fluid">
<?php
        $title = $object->title;
        $date = date('F jS, Y', strtotime($object->date)). ': ';
        $alt = $title . ' on ' .$date;
?>
<div class="span12">
        <a href="<?php echo $object->url; ?>">
        <img src="<?php echo $object->banner_url; ?>" style="width: 100%;" alt="<?php echo $alt; ?>">
        </a>
</div>
<h2><span><?php echo $object->title; ?></span><br /><small content="<?php echo date('Y-m-d\TH:i', strtotime($object->date.' '.$object->time)); ?>"><?php echo date('F jS, Y H.i e', strtotime($object->date.' '.$object->time)); ?></small></h2>
<a class="btn btn-large btn-primary pull-right" href="<?php echo $object->url; ?>">Watch Now</a>
</div>
