<?php
class wpgd {

    static function imagecreatefromextension( $file ) {
        switch (strtolower(pathinfo($file, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return imagecreatefromjpeg($file);
            case 'png':
                return imagecreatefrompng($file);
            case 'gif':
                return imagecreatefromgif($file);
            default:
                list($w, $h) = getimagesize($file);
                return imagecreate($w,$h);
        }
    }

    static function get_attachment_id_from_src($image_src) {
        global $wpdb;
        $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
        $id = $wpdb->get_var($query);
        return $id;
    }

    static function imagecircularcrop( &$img, $w, $h) {
        imagealphablending($img,true);
        $mask = imagecreatetruecolor($w,$h);
        imagealphablending($mask,true);
        $greenscreen = imagecolorallocate($mask, 0, 255, 0);
        $bluescreen = imagecolorallocate($mask, 0, 0, 255);
        imagefill($mask, 0, 0, $greenscreen);
        imagefilledellipse($mask, $w/2, $h/2, $w, $h, $bluescreen);
        imagecolortransparent($mask, $bluescreen);
        imagecopymerge($img, $mask, 0, 0, 0, 0, $w, $h, 100);
        imagecolortransparent($img, $greenscreen);
    }

    static function resizefromurl( $url, $w, $h ) {
        $url_post_id = wpgd::get_attachment_id_from_src($url);
        $url_img_path = get_attached_file($url_post_id);
        $url_img = wpgd::imagecreatefromextension($url_img_path);
        list($url_w, $url_h) = getimagesize($url_img_path);   
        $url_resized = imagecreatetruecolor($w,$h);
        imagecopyresampled($url_resized, $url_img, 0, 0, 0, 0, $w, $h, $url_w, $url_h);
        return $url_resized;
    }
}
