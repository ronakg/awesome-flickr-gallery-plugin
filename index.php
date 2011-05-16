<?php
/*
Plugin Name: Awesome Flickr Gallery
Plugin URI: http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/
Description: A fully customizable Flickr Gallery plug-in for WordPress.
Version: 1.3.0
Author: Ronak Gandhi
Author URI: http://www.ronakg.in
License: GPL2

Copyright 2011 Ronak Gandhi (email : ronak.gandhi@ronakg.in)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( is_admin() ) {
    include('admin_settings.php');
}

define('BASE_URL', get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)));
define('DEBUG', False);

/* Short code to load Awesome Flickr Gallery plugin.  Detects the word
 * [AFG_gallery] in posts or pages and loads the gallery.
 */
add_shortcode('AFG_gallery', 'afg_display_gallery');

/* Load Lightbox plugin in <head> section of the theme. */
add_action('wp_head', 'afg_add_lightbox_headers');

/* Map for photo titles displayed on the gallery. */
$size_heading_map = array(
    '_s' => '',
    '_t' => '1',
    '_m' => '2',
    'NULL' => '3',
);

$bg_color_map = array(
    'Black' => 'White',
    'White' => 'Black',
);

function afg_add_lightbox_headers() {
    echo "<link href=\"" . BASE_URL . "/colorbox/colorbox.css\" rel=\"stylesheet\" media=\"screen\">";
    echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js\"></script>";
    echo "<script src=\"" . BASE_URL . "/colorbox/jquery.colorbox.js\"></script>";
    echo "<script src=\"" . BASE_URL . "/colorbox/mycolorbox.js\"></script>";
    echo "<style type=\"text/css\">
          a.afg_page:hover {background:royalblue;text-decoration:underline;color:white;}
          a.afg_page:visited, a.afg_page:link {text-decoration:none;border:1px solid gray;}
          </style>";
}

/* Encode the params array to make them URL safe.
 * Example params are api_key, api, user_id etc.
 */
function afg_get_encoded_params($params) {
    $encoded_params = array();

    foreach ($params as $k => $v) {
        $encoded_params[] = urlencode($k).'='.urlencode($v);
    }
    return $encoded_params;
}

function afg_construct_url($encoded_params) {
    $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
    return $url;
}

function afg_get_photo_url($farm, $server, $pid, $secret, $size) {
    if ($size == 'NULL') {
        $size = '';
    }
    return "http://farm$farm.static.flickr.com/$server/{$pid}_$secret$size.jpg";
}

function afg_get_photo_page_url($user_id, $pid) {
    return "http://www.flickr.com/photos/$user_id/$pid";
}

function afg_get_flickr_data($params) {
    $encoded_params = afg_get_encoded_params($params);
    $url = afg_construct_url($encoded_params);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0);  // ignore any headers
    ob_start();  // use output buffering so the contents don't get sent directly to the browser
    curl_exec($curl);  // get the file
    curl_close($curl);
    $rsp = ob_get_contents();  // save the contents of the file into $file
    ob_end_clean();  // turn output buffering back off
    return unserialize($rsp);
}

function afg_return_error_code($rsp) {
    return $rsp['message'];
}

/* Main function that loads the gallery. */
function afg_display_gallery() {
    global $size_heading_map, $bg_color_map;

    $cur_page = 1;
    $cur_page_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    preg_match('/\?afg_page_id=(?P<page_id>\d+)/', $cur_page_url, $matches);
    if ($matches) {
        $cur_page = ($matches['page_id']);
        $match_pos = strpos($cur_page_url, "?afg_page_id=$cur_page");
        $cur_page_url = substr($cur_page_url, 0, $match_pos);
    }

    $api_key = get_option('afg_api_key');
    $user_id = get_option('afg_user_id');
    $per_page = get_option('afg_per_page');
    $photo_size = get_option('afg_photo_size');
    $captions = get_option('afg_captions');
    $descr = get_option('afg_descr');
    $columns = get_option('afg_columns');
    $bg_color = get_option('afg_bg_color');
    $credit_note = get_option('afg_credit_note');

    $disp_gallery = '';

    if (DEBUG) {
        $disp_gallery .= 'API Key - ' . $api_key . '<br />';
        $disp_gallery .= 'User ID - ' . $user_id . '<br />';
        $disp_gallery .= 'Per Page - ' . $per_page . '<br />';
        $disp_gallery .= 'Photo Size 0 ' . $photo_size . '<br />';
        $disp_gallery .= 'Captions - ' . $captions . '<br />';
        $disp_gallery .= 'Description - ' . $descr . '<br />';
        $disp_gallery .= 'Columns - ' . $columns . '<br />';
        $disp_gallery .= 'Credit Note - ' . $credit_note . '<br />';
        $disp_gallery .= 'Background Color - ' . $bg_color . '<br />';
    }

    /* Parameters to get public photos of the user.  Format we are requesting
     * from Flickr is php_serial.
     */
    $params = array(
        'api_key' => $api_key,
        'method' => 'flickr.people.getPublicPhotos',
        'format' => 'php_serial',
        'user_id' => $user_id,
        'per_page' => $per_page,
        'page' => $cur_page,
    );
    /*
    if ($photoset_id) {
        $flickr_api = 'photoset';
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.photosets.getPhotos',
            'photoset_id' => $photoset_id,
            'format' => 'php_serial',
            'user_id' => $user_id,
            'per_page' => $per_page,
            'page' => $cur_page,
        );
    }
    else if ($gallery_id) {
        $flickr_api = 'photos';
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.galleries.getPhotos',
            'gallery_id' => $gallery_id,
            'format' => 'php_serial',
            'user_id' => $user_id,
            'per_page' => $per_page,
            'page' => $cur_page,
        );
    }
    else {
        $flickr_api = 'photos';
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.people.getPublicPhotos',
            'format' => 'php_serial',
            'user_id' => $user_id,
            'per_page' => $per_page,
            'page' => $cur_page,
        );
    }
     */

    $rsp_obj = afg_get_flickr_data($params);

    if ($rsp_obj['stat'] == 'fail') {
        return "<h3>" . afg_return_error_code($rsp_obj) . "</h3>";
    }
    $flickr_api = 'photos';

    $total_pages = $rsp_obj[$flickr_api]['pages'];
    $cur_col = 0;

    $disp_gallery .= "<table align='center'
        style=\"background-color:{$bg_color}; border-color:{$bg_color}\"
        width='100%'>";

    foreach($rsp_obj[$flickr_api]['photo'] as $photo) {
        $photo_url = afg_get_photo_url($photo['farm'], $photo['server'],
            $photo['id'], $photo['secret'], $photo_size);

        $text_color = $bg_color_map[$bg_color];

        if ($cur_col % $columns == 0) {
            /* Add an extra blank row for right margins */
            $disp_gallery .= "<tr><td style=\"text-align:left;
                color:$text_color; vertical-align:top;
                background-color:{$bg_color};
                border-color:{$bg_color}\">&nbsp;</td></tr>";
            $disp_gallery .= "<tr><td style=\"text-align:left; margin-top:100;
                color:$text_color; vertical-align:top;
                background-color:{$bg_color}; border-color:{$bg_color}\">";
        }
        else {
            $disp_gallery .= "<td style=\"text-align:left; color:$text_color;
                vertical-align:top; background-color:{$bg_color};
                border-color:{$bg_color}\">";
        }

        $photo_page_url = afg_get_photo_url($photo['farm'], $photo['server'],
            $photo['id'], $photo['secret'], '_z');
        $pid_len = strlen($photo['id']);

        $disp_gallery .= "<a href=\"$photo_page_url\"
            class=\"cboxElement\" rel=\"example4\" title=\"{$photo['title']}\"><img
            src=\"$photo_url\" alt=\"{$photo['title']}\"/></a>";
        if($size_heading_map[$photo_size] && $captions) {
            $disp_gallery .= "<br /><b><font
                size=\"$size_heading_map[$photo_size]\">{$photo['title']}</font></b>";
        }

        /* If photo descriptions are ON and size is not Square and Thumbnail,
         * get photo descriptions
         */
        if($descr && $photo_size != '_s' && $photo_size != '_t') {
            $params = array(
                'api_key' => $api_key,
                'method' => 'flickr.photos.getInfo',
                'format' => 'php_serial',
                'photo_id' => $photo['id'],
            );
            $photo_info = afg_get_flickr_data($params);
            if ($photo_info['stat'] != 'ok') {
                return "<h2>" . afg_return_error_code($photo_info) . "</h2>";
            }
            $date_taken = $photo_info['photo']['dates']['taken'];
            $date_taken_format = date("F j, Y", strtotime($date_taken));
            $disp_gallery .= "<br /><i>Taken on:</i> $date_taken_format<br />";
            if($photo_info['photo']['description']['_content']) {
                $disp_gallery .= "<br />" .
                    $photo_info['photo']['description']['_content'];
            }
        }
        $cur_col += 1;
        if ($cur_col % $columns == 0) {
            $disp_gallery .= '</td></tr>';
        }
        else {
            $disp_gallery .= '</td>';
        }
    }
    $disp_gallery .= "<tr><td style=\"text-align:center; color:$text_color;
        vertical-align:top; background-color:{$bg_color}; font-size:90%;
        border-color:{$bg_color}\" colspan=\"$columns\"><br /><br />";
    if ($cur_page == 1) {
        $disp_gallery .="<font style=\"border:1px solid gray;\">&nbsp;< prev&nbsp;</font>&nbsp;";
        $disp_gallery .="<font style=\"border:1px solid gray;background:gray;color:white\"> 1 </font>&nbsp;";
    }
    else {
        $prev_page = $cur_page - 1;
        $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg_page_id=$prev_page\" title=\"Prev Page\">&nbsp;< prev </a>&nbsp;";
        $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg_page_id=1\" title=\"Page 1\"> 1 </a>&nbsp;";
    }
    if ($cur_page - 2 > 2) {
        $start_page = $cur_page - 2;
        $end_page = $cur_page + 2;
        $disp_gallery .= " ... ";
    }
    else {
        $start_page = 2;
        $end_page = 6;
    }
    for ($count = $start_page; $count <= $end_page; $count += 1) {
        if ($count > $total_pages) break;
        if ($cur_page == $count) {
            $disp_gallery .= "<font style=\"border:1px solid gray;background:gray;color:white\">&nbsp;{$count}&nbsp;</font>&nbsp;";
        }
        else {
            $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg_page_id={$count}\" title=\"Page {$count}\">&nbsp;{$count} </a>&nbsp;";
        }
    }

    if ($count < $total_pages) $disp_gallery .= " ... ";
    if ($count <= $total_pages) {
        $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg_page_id={$total_pages}\" title=\"Page {$total_pages}\">&nbsp;{$total_pages} </a>&nbsp;";
    }
    if ($cur_page == $total_pages) $disp_gallery .= "<font style=\"border:1px solid gray\">&nbsp;next >&nbsp;</font>";
    else {
        $next_page = $cur_page + 1;
        $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg_page_id=$next_page\" title=\"Next Page\"> next > </a>&nbsp;";
    }

    $disp_gallery .= "<br />({$rsp_obj[$flickr_api]['total']} photos)<br /><br />";
    if ($credit_note) {
        $wp_plugins_url = get_option('siteurl') . '/wp-content/plugins/';
        $disp_gallery .= "<br /><p style='text-align:right'>Powered by <a
            href=\"http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin\"/>AFG</p></a></td></tr>";
    }
    $disp_gallery .= '</table>';
    return $disp_gallery;
}
?>
