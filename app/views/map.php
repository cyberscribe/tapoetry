    <div id="map_canvas">
        <img src="http://www.transatlanticpoetry.com/files/2014/05/map.jpg" style="width: 100%; height: 100%; border: 0;" />
    </div>
    <script type="text/javascript">
      window.onload=function() {
        var centerLatLng = new google.maps.LatLng(45,-45);
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
          zoom: 3,
          center: centerLatLng,
          mapTypeId: google.maps.MapTypeId.HYBRID
        }
        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        infowindow = new google.maps.InfoWindow({
            content: ""
        });

        var markerArray = [
        <?php foreach($objects as $object): ?>
            {"lon":"<?php echo $object->lon + (rand(-100,100)/1000); ?>","lat":"<?php echo $object->lat + (rand(-100,100)/1000); ?>","label":"<?php echo $object->__name; ?>","html":"<?php echo '<h3>'.$object->__name.'</h3><img src=\"'.$object->image_url.'\" style=\"max-width: 150px; float: left; margin-right: 1em; margin-bottom: 1em;\" class=\"img-circle\"><p style=\"font-size: 14px;\">'.str_replace('"','\"',trim($object->description)).'</p><a href=\"'.$this->html->object_url($object).'\" class=\"btn btn-primary pull-right\" target=\"_parent\">more</a>'; ?>"},
        <?php endforeach; ?>
        ];
        var theMarker;
        var lon;
        var lat;
        var label;
        for (i = 0; i < markerArray.length; i++) {
            theMarker = markerArray[i];
            lon = theMarker['lon'];
            lat = theMarker['lat'];
            label = theMarker['label'];
            html = theMarker['html'];
            var marker = new google.maps.Marker({
                map: map,
                animation: google.maps.Animation.DROP,
                position: new google.maps.LatLng(lat,lon),
                title:  label,
                html: html
            });
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.setContent(this.html);
                infowindow.open(map,this);
            });
        }
      }
    </script>
