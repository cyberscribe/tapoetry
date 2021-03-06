    <div id="map_canvas">
        <img src="http://www.transatlanticpoetry.com/files/2015/05/map.png" style="width: 100%; height: 100%; border: 0;" />
    </div>
    <script type="text/javascript">
      window.onload=function() {
        var centerLatLng = new google.maps.LatLng(45,-45);
        geocoder = new google.maps.Geocoder();
        var mapOptions = {
          zoom: 3,
          scrollwheel: false,
          center: centerLatLng,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          styles: [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#ddd6c8"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#cdc4bc"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#cdc4bc"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#cdc4bc"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#cdc4bc"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}],
        }
        var map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
        infowindow = new google.maps.InfoWindow({
            content: ""
        });
        var bounds = new google.maps.LatLngBounds();
        var pinImage = new google.maps.MarkerImage( '<?php echo plugin_dir_url( dirname(__FILE__) ).'public/images/pin.png'; ?>' );
        var pinImageHost = new google.maps.MarkerImage( '<?php echo plugin_dir_url( dirname(__FILE__) ).'public/images/pin_host.png'; ?>' );
        var pinImagePartner = new google.maps.MarkerImage( '<?php echo plugin_dir_url( dirname(__FILE__) ).'public/images/pin_partner.png'; ?>' );
        var markerArray = [
        <?php foreach($objects as $object): 
            if ($object->published != 1) {
                continue;
            }
            switch($object->__model_name) {
                case 'Host':
                    $marker = 'pinImageHost';
                break;
                case 'Partner':
                    $marker = 'pinImagePartner';
                break;
                default:
                    $marker = 'pinImage';
                break;
            }
        ?>
            {"lon":"<?php echo $object->lon + (rand(-500,500)/1000); ?>","lat":"<?php echo $object->lat + (rand(-500,500)/1000); ?>","label":"<?php echo $object->__name; ?>","html":"<?php echo '<h3>'.$object->__name.'</h3><img src=\"'.$object->image_url.'\" style=\"max-width: 150px; float: left; margin-right: 1em; margin-bottom: 1em;\" class=\"img-circle\"><p style=\"font-size: 14px;\">'.str_replace('"','\"',trim(str_replace("\n"," ",filter_var($object->description,FILTER_SANITIZE_STRING)))).'</p><a href=\"'.$this->html->object_url($object).'\" class=\"btn btn-primary pull-right\" target=\"_parent\">more</a>'; ?>","marker":<?php echo $marker; ?>},
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
            marker = theMarker['marker'];
            var marker = new google.maps.Marker({
                map: map,
                animation: google.maps.Animation.DROP,
                position: new google.maps.LatLng(lat,lon),
                title:  label,
                html: html,
                icon: marker
            });
            google.maps.event.addListener(marker, 'click', function () {
                infowindow.setContent(this.html);
                infowindow.open(map,this);
            });
            bounds.extend(marker.position);
        }
        map.fitBounds(bounds);
      }
    </script>
