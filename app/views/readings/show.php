<div class="row-fluid" itemscope itemtype="http://schema.org/LiteraryEvent">
<?php
        date_default_timezone_set('Europe/London'); //force all dates expressed to be London time
        $title = $object->title;
        $date = date('F jS, Y', strtotime($object->date)). ': ';
        $time = date('H.i T', strtotime($object->date.' '.$object->time));
        $iso_dt = date('Y-m-d\TH:i', strtotime($object->date.' '.$object->time));
        $alt = $title . ' on ' .$date;
?>
<header>
<div class="span12">
	<a itemprop="sameAs" href="<?php echo $object->url; ?>" target="_blank">
    <?php if($object->banner_url): ?>
	<img src="<?php echo $object->banner_url; ?>" style="width: 100%;" alt="<?php echo $alt; ?>" itemprop="image">
    <?php else: ?>
	<img src="/readings/banner/<?php echo $object->id; ?>/" style="width: 100%;" alt="<?php echo $alt; ?>" itemprop="image">
    <?php endif; ?>
	</a>
</div>
<h1><span itemprop="name"><?php echo $object->title; ?></span><br /><small  itemprop="startDate" content="<?php echo $iso_dt; ?>"><?php echo $date.' '.$time; ?></small></h1>
</header>

<?php if ($object->video_url): ?>
<div class="video-banner roundblack">
	<?php echo do_shortcode('[youtube='.$object->video_url.']'); ?>
</div>
<?php endif; ?>

<?php if(sizeof($object->poets) == 2): ?>

<div class="span12">
    <div class="span6">
        <h2>Featuring</h2>
            <div class="span12">
                <?php $this->render_view('poets/_card', array('collection' => $object->poets, 'as' => 'object', 'locals' => array('span' => 'span6'))); ?>
            </div>
    </div> 
    <div class="span3">
        <h2>Hosted by</h2>
            <div class="span12">
                <?php $this->render_view('hosts/_card', array('collection' => array($object->host), 'locals' => array('span' => 'span12'))); ?>
            </div>
    </div> 
    <div class="span3">
        <h2>Supported by</h2>
            <div class="span12">
                <?php $this->render_view('partners/_card', array('collection' => array($object->partner), 'locals' => array('span' => 'span12'))); ?>
            </div>
        </ul>
    </div>
</div>
<?php else: ?>

    <h2>Featuring</h2>
    <div class="span12">
    <ul class="thumbnails clearfix">
        <?php $this->render_view('poets/_card', array('collection' => $object->poets, 'as' => 'object', 'locals' => array('span' => 'span3'))); ?>
    </ul>
    </div>

    <h2>Hosted by</h2>
    <div class="span12">
    <ul class="thumbnails clearfix">
        <?php $this->render_view('hosts/_card', array('collection' => array($object->host), 'locals' => array('span' => 'span3'))); ?>
    </ul>
    </div>

    <h2>Supported by</h2>
    <div class="span12">
    <ul class="thumbnails clearfix">
        <?php $this->render_view('partners/_card', array('collection' => array($object->partner), 'locals' => array('span' => 'span3'))); ?>
    </ul>
    </div>

<?php endif; ?>
</div>
<div class="span12">
    <h2>On The Map</h2>
    <div style="max-width: 960px; height:480px;">
        <iframe src="<?php echo mvc_public_url( array('controller' => 'map', 'id' => $object->id) ); ?>" width="100%" height="480" border="0" style="width: 100%; height: 480px; border: 0;"></iframe>
        <noframes>
            <a href="<?php echo mvc_public_url( array('controller' => 'map', 'id' => $object->id) ); ?>">
                Click here to view the map
            </a>
        </noframes>
    </div>
</div>

<p class="pull-right">
	<?php echo $this->html->link('All Readings', array('controller' => 'readings')); ?>
</p>
<br class="clearfix" />
