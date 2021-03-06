<?php

class ReadingsController extends MvcPublicController {

    public function show() {
        /* If the seo-friendly text has not been appended, redirect to a version with it appended */
        if (!isset($this->params['extra']) || strlen($this->params['extra']) == 0) {
                $reading = $this->Reading->find_one();
                $extra = urlify( $reading->title );
                $base = $_SERVER['REQUEST_URI'];
                $base = substr( $base, 0, (strlen($base) - 1) );
                $this->redirect( $base . '-' . $extra);
        }
        /* Otherwise, use default show behaviour from MvcPublicController */
        parent::show();
    }
    
    public function json() {
        $readings = $this->Reading->find();
        header('Content-type: application/json');
        echo json_encode($readings);
        die();
    }

    public function rss() {
        $readings = $this->Reading->find();
        $this->set('readings',$readings);
        header('Content-type: application/rss+xml');
        $this->render_view('readings/rss', array('layout' => 'bare'));
    }
    
    public function feed() {
        return $this->rss();
    }

    public function csv() {
        $autofields = array('title','description','date');
        $otherfields = array('poets');
        $readings = $this->Reading->find();
        $out = fopen('php://output', 'w');
        header('Content-type: text/csv');
        fputcsv($out, array_merge($autofields,$otherfields));
        foreach ($readings as $reading_obj) {
            $reading = array();
            foreach ($autofields as $k) {
                $reading[] = $reading_obj->$k;
            }
            $poets = array();
            foreach ($reading_obj->poets as $poet_obj) {
                $poets[] = $poet_obj->last_name.', '.$poet_obj->first_name;
            }
            $reading[] = implode(';',$poets);
            fputcsv($out, array_values($reading));
        }
        fclose($out);
        die();
    }

    public function upcoming() {
        $reading = $this->model->find_one(array('conditions' => array(
                                'Reading.date >=' => date('Y-m-d')),
                                'order' => 'Reading.date ASC'
                        ));
        if (is_object($reading)) {
            $extra = urlify( $reading->title );
            $url = MvcRouter::public_url( array('controller' => 'readings', 'action' => 'show', 'id' => $reading->id) );
            $url = substr( $url, 0, (strlen($url) - 1) );
            header('HTTP/1.x 302 Moved Temporarily');
            header('Location: '.$url.'-'.$extra);
        } else {
            $url = MvcRouter::public_url( array('controller' => 'readings', 'action' => 'index') );
            header('HTTP/1.x 302 Moved Temporarily');
            header('Location: '.$url);
        }
        die();
    }

    /* Check whether an event is currently live, return json representation if so or json false otherwise */
    public function liveNow() {
        header('Cache-Control: max-age=1');
        $object = $this->model->find_one( array( conditions => array(
                                        'AND' => array(
                                                'Reading.date' => date('Y-m-d'),
                                                'Reading.time >=' => date('H:i:s'),
                                                'Reading.time <=' => date('H:i:s', time() + 3600),
                                        )
                            )
                ) );
        if (is_object($object)) {
                $this->set('object',$object);
                $this->render_view('readings/liveNow', array('layout' => 'bare'));
        } else {
                header('Content-type: application/json');
                header('HTTP/1.x 204 No Content');
                echo json_encode(false);
                die();
        }
    }

    /* Use wpgd (a wrapper to php gd) to compose and render a banner image for the reading */
    public function banner() {

        /* Initialize */
        $this->init_image();
        $background = wpgd::imagecreatefromextension(plugin_dir_path( __FILE__ ).'../public/images/event-banner-blank.png');
        $reading = $this->Reading->find_one_by_id($this->params['id']);
        $filename = date('Ymd', strtotime($reading->date)).'-banner.png';
        $oxblood = imagecolorallocate($background, 95, 0, 5);
        $darkbrown = imagecolorallocate($background, 43, 34, 33);

        /* Add circle-cropped first poet image */
        $poet1_image_url = $reading->poets[0]->image_url;
        $poet1_resized = wpgd::resizefromurl( $poet1_image_url, 120, 120);
        wpgd::imagecircularcrop($poet1_resized, 120, 120);
        imagecopymerge( $background, $poet1_resized, 40, 40, 0, 0, 120, 120, 90);

        /* Add circle-cropped second poet image */
        $poet2_image_url = $reading->poets[1]->image_url;
        $poet2_resized = wpgd::resizefromurl( $poet2_image_url, 120, 120);
        wpgd::imagecircularcrop($poet2_resized, 120, 120);
        imagecopymerge( $background, $poet2_resized, 180, 40, 0, 0, 120, 120, 90);

        /* Add partner image */
        if ($reading->partner->id == 1 || substr($reading->partner->name,0,20) == 'Transatlantic Poetry') {
            $hosted_by_url = $reading->host->image_url;
            $hosted_by_resized = wpgd::resizefromurl( $hosted_by_url, 120, 120);
            wpgd::imagecircularcrop($hosted_by_resized, 120, 120);
            imagecopymerge( $background, $hosted_by_resized, 980, 75, 0, 0, 120, 120, 90);
            /* Add host name */
            $host_name = $reading->host->name;
            $host_bbox = imagettfbbox ( 34, 0, 'utopia.ttf', strtoupper($host_name));
            imagettftext( $background, 12, 0, 980, 215, $darkbrown, 'utopia.ttf', $host_name);
        } else {
            $hosted_by_url = $reading->partner->image_url;
            $hosted_by_resized = wpgd::resizefromurl( $hosted_by_url, 200, 200);
            imagecopymerge( $background, $hosted_by_resized, 980, 75, 0, 0, 200, 200, 90);
        }

        /* Add first poet name */
        $poet1_name = $reading->poets[0]->name;
        $poet1_bbox = imagettfbbox ( 34, 0, 'utopia.ttf', strtoupper($poet1_name));
        imagettftext( $background, 34, 0, 380, 150, $oxblood, 'utopia.ttf', strtoupper($poet1_name));

        /* Add second poet name */
        $poet2_name = $reading->poets[1]->name;
        $poet2_bbox = imagettfbbox ( 34, 0, 'utopia.ttf', strtoupper($poet2_name));
        imagettftext( $background, 34, 0, 380, 225, $oxblood, 'utopia.ttf', strtoupper($poet2_name));

        /* Add date */
        $dt = strtotime( $reading->date.' '.$reading->time );
        $date_str = date('D., jS M. Y', $dt);
        $date_bbox = imagettfbbox ( 36, 0, 'utopia.ttf', $date_str);
        imagettftext( $background, 24, 0, 40, 235, $darkbrown, 'utopia.ttf', $date_str);

        /* Add time */
        $time_str = $this->get_time_str( $dt );
        imagettftext( $background, 20, 0, 40, 275, $darkbrown, 'utopia.ttf', $time_str);

        /* Output image */
        header('Content-type: image/png');
        header('Content-Disposition: attachment;filename='.$filename);
        imagepng($background); 
        imagedestroy($background);
        die();
    }

    /* Use wpgd (a wrapper to php gd) to compose and render a splash screen image for the reading */
    public function splash() {
        /* Initialize */
        $this->init_image();
        $background = wpgd::imagecreatefromextension(plugin_dir_path( __FILE__ ).'../public/images/splash-blank.png');
        $reading = $this->Reading->find_one_by_id($this->params['id']);
        $filename = date('Ymd', strtotime($reading->date)).'-splash.png';
        $oxblood = imagecolorallocate($background, 95, 0, 5);
        $darkbrown = imagecolorallocate($background, 43, 34, 33);

        /* Add circle-cropped first poet image */
        $poet1_image_url = $reading->poets[0]->image_url;
        $poet1_resized = wpgd::resizefromurl( $poet1_image_url, 120, 120);
        wpgd::imagecircularcrop($poet1_resized, 120, 120);
        imagecopymerge( $background, $poet1_resized, 40, 100, 0, 0, 120, 120, 90);

        /* Add circle-cropped second poet image */
        $poet2_image_url = $reading->poets[1]->image_url;
        $poet2_resized = wpgd::resizefromurl( $poet2_image_url, 120, 120);
        wpgd::imagecircularcrop($poet2_resized, 120, 120);
        imagecopymerge( $background, $poet2_resized, 500, 140, 0, 0, 120, 120, 90);

        /* Add first poet name */
        $poet1_name = $reading->poets[0]->name;
        $poet1_bbox = imagettfbbox ( 34, 0, 'utopia.ttf', strtoupper($poet1_name));
        imagettftext( $background, 20, 0, 180, 160, $oxblood, 'utopia.ttf', strtoupper($poet1_name));

        /* Add second poet name */
        $poet2_name = $reading->poets[1]->name;
        $poet2_bbox = imagettfbbox ( 34, 0, 'utopia.ttf', strtoupper($poet2_name));
        imagettftext( $background, 20, 0, 220, 220, $oxblood, 'utopia.ttf', strtoupper($poet2_name));

        /* Add date */
        $dt = strtotime( $reading->date.' '.$reading->time );
        $date_str = date('D., jS M. Y', $dt);
        $date_bbox = imagettfbbox ( 36, 0, 'utopia.ttf', $date_str);
        imagettftext( $background, 18, 0, 190, 265, $darkbrown, 'utopia.ttf', $date_str);

        /* Add time */
        $time_str = $this->get_time_str( $dt );
        imagettftext( $background, 16, 0, 150, 295, $darkbrown, 'utopia.ttf', $time_str);

        /* Output image */
        header('Content-type: image/png');
        header('Content-Disposition: attachment;filename='.$filename);
        imagepng($background); 
        imagedestroy($background);
        die();
    }

    /* Initalise some settings needed for fonts and time representations */
    private function init_image() {
        putenv('GDFONTPATH=' . plugin_dir_path( __FILE__ ).'../public/fonts');
        date_default_timezone_set('Europe/London');
    }

    /* Display event time in multiple timezones for a global audience */
    private function get_time_str( $dt ) {
        $atlantic_offset = 5;
        if( date('I',$dt) == 1) {
            $atlantic_offset += 1;
            $tz1 = 'BST';
        } else {
            $tz1 = 'GMT';
        }
        date_default_timezone_set('America/Los_Angeles');
        if( date('I',$dt) == 1) {
            $atlantic_offset -= 1;
            $tz2 = 'EDT';
            $tz3 = 'PDT';
        } else {
            $tz2 = 'EST';
            $tz3 = 'PST';
        }
        date_default_timezone_set('Europe/London');
        if (date('i',$dt) != '00') {
            $time_str = date('g:ia', $dt);
            $time_str .= ' '.$tz1;
            $time_str .= ' / '.date('g:ia', $dt - ($atlantic_offset*60*60));
            $time_str .= ' '.$tz2;
            $time_str .= ' / '.date('g:ia', $dt - (($atlantic_offset + 3)*60*60));
            $time_str .= ' '.$tz3;
        } else {
            $time_str = date('ga', $dt);
            $time_str .= ' '.$tz1;
            $time_str .= ' / '.date('ga', $dt - ($atlantic_offset*60*60));
            $time_str .= ' '.$tz2;
            $time_str .= ' / '.date('ga', $dt - (($atlantic_offset + 3)*60*60));
            $time_str .= ' '.$tz3;
        }
        return $time_str;
    }

}
