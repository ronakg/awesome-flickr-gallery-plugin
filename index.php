<?php
/*
Plugin Name: Awesome Flickr Gallery
Plugin URI: http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/
Description: Awesome Flickr Gallery is a simple, fast and light plugin to create a gallery of your Flickr photos on your WordPress enabled website.  This plugin aims at providing a simple yet customizable way to create stunning Flickr gallery.
Version: 2.7.1
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
    include_once('admin_settings.php');
}

include_once('afg_libs.php');

/* Short code to load Awesome Flickr Gallery plugin.  Detects the word
 * [AFG_gallery] in posts or pages and loads the gallery.
 */
if (!is_admin())
    add_filter('widget_text', 'do_shortcode', SHORTCODE_PRIORITY);

add_shortcode('AFG_gallery', 'afg_display_gallery');
add_action('wp_print_scripts', 'enqueue_my_scripts');
add_action('wp_print_styles', 'enqueue_my_styles');

function enqueue_my_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('afg_colorbox_script', BASE_URL . "/colorbox/jquery.colorbox-min.js" , array('jquery'));
    wp_enqueue_script('afg_colorbox_js', BASE_URL . "/colorbox/mycolorbox.js" , array('jquery'));
}

function enqueue_my_styles() {
    wp_enqueue_style('afg_colorbox_css', BASE_URL . "/colorbox/colorbox.css");
    wp_enqueue_style('afg_css', BASE_URL . "/afg.css");
}

function afg_get_photo_page_url($user_id, $pid) {
    return "http://www.flickr.com/photos/$user_id/$pid";
}

function afg_return_error_code($rsp) {
    return $rsp['message'];
}

/* Main function that loads the gallery. */
function afg_display_gallery($atts) {
    global $size_heading_map, $afg_text_color_map;

    if (!get_option('afg_pagination')) update_option('afg_pagination', 'on');

    extract( shortcode_atts( array(
        'id' => '0',
    ), $atts ) );

    $cur_page = 1;
    $cur_page_url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    preg_match("/\?afg{$id}_page_id=(?P<page_id>\d+)/", $cur_page_url, $matches);
    if ($matches) {
        $cur_page = ($matches['page_id']);
        $match_pos = strpos($cur_page_url, "?afg{$id}_page_id=$cur_page");
        $cur_page_url = substr($cur_page_url, 0, $match_pos);
    }

    $galleries = get_option('afg_galleries');
    $gallery = $galleries[$id];

    $api_key = get_option('afg_api_key');
    $user_id = get_option('afg_user_id');

    $per_page = get_afg_option($gallery, 'per_page');
    $photo_size = get_afg_option($gallery, 'photo_size');
    $photo_title = get_afg_option($gallery, 'captions');
    $photo_descr = get_afg_option($gallery, 'descr');
    $bg_color = get_afg_option($gallery, 'bg_color');
    $columns = get_afg_option($gallery, 'columns');
    $credit_note = get_afg_option($gallery, 'credit_note');
    $gallery_width = get_afg_option($gallery, 'width');
    $pagination = get_afg_option($gallery, 'pagination');

    if ($gallery['photo_source'] == 'photoset') $photoset_id = $gallery['photoset_id'];
    else if ($gallery['photo_source'] == 'gallery') $gallery_id = $gallery['gallery_id'];



    $disp_gallery = "<!-- Awesome Flickr Gallery Start" .
        "User ID - " . $user_id .
        "Per Page - " . $per_page .
        "Photo Size - " . $photo_size .
        "Captions - " . $photo_title .
        "Description - " . $photo_descr .
        "Columns - " . $columns .
        "Credit Note - " . $credit_note .
        "Background Color - " . $bg_color .
        "Width - " . $gallery_width .
        "Pagination - " . $pagination . "-->";

    /* Parameters to get public photos of the user.  Format we are requesting
     * from Flickr is php_serial.
     */

    /*
    if ($photoset_id) {
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.photosets.getInfo',
            'photoset_id' => $photoset_id,
            'format' => 'php_serial',
            'user_id' => $user_id,
        );
    }
    else if ($gallery_id) {
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.galleries.getInfo',
            'photoset_id' => $gallery_id,
            'format' => 'php_serial',
            'user_id' => $user_id,
        );
    }
    else {
        $params = array(
            'api_key' => $api_key,
            'method' => 'flickr.people.getInfo',
            'format' => 'php_serial',
            'user_id' => $user_id,
        );
    }
     */

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

    $rsp_obj = afg_get_flickr_data($params);

    if ($rsp_obj['stat'] == 'fail') {
        return "<h3>" . afg_return_error_code($rsp_obj) . "</h3>";
    }

    $total_pages = $rsp_obj[$flickr_api]['pages'];
    $total_photos = $rsp_obj[$flickr_api]['total'];
    $photos = array();

    for($i=1; $i<=($total_photos/500)+1; $i++) {
        $params['per_page'] = 500;
        $params['page'] = $i;

        $rsp_obj_total = afg_get_flickr_data($params);
        if ($rsp_obj_total['stat'] == 'fail') {
            return "<h3>" . afg_return_error_code($rsp_obj_total) . "</h3>";
        }
        $photos = array_merge($photos, $rsp_obj_total[$flickr_api]['photo']);
    }

    if ($gallery_width == 'auto') $gallery_width = 100;
    $disp_gallery .= "<table class=\"afg_gallery\"" .
        "style=\"background-color:{$bg_color}; border-color:{$bg_color};\"" .
        "width='$gallery_width%'>";

    $photo_count = 1;
    $cur_col = 0;
    $column_width = (int)($gallery_width/$columns);

    foreach($photos as $pid => $photo) {
        $photo_page_url = afg_get_photo_url($photo['farm'], $photo['server'],
            $photo['id'], $photo['secret'], '_b');
        if ( ($photo_count <= $per_page * $cur_page) && ($photo_count > $per_page * ($cur_page - 1)) ) {
            $photo_url = afg_get_photo_url($photo['farm'], $photo['server'],
                $photo['id'], $photo['secret'], $photo_size);

            $text_color = $afg_text_color_map[$bg_color];

            if ($cur_col % $columns == 0) {
                $disp_gallery .= "<tr><td class=\"afg_gallery\" width=\"$column_width%\" style=\"" .
                    "color:{$text_color}; background-color:{$bg_color}; border-color:{$bg_color}\">";
            }
            else {
                $disp_gallery .= "<td class=\"afg_gallery\" width=\"$column_width%\" style=\"color:{$text_color};" .
                    "background-color:{$bg_color}; border-color:{$bg_color}\">";
            }

            $pid_len = strlen($photo['id']);

            /* If photo descriptions are ON and size is not Square and Thumbnail,
             * get photo descriptions
             */

            if($photo_descr == 'on' && $photo_size != '_s' && $photo_size != '_t') {
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
            }
            $disp_gallery .= "<a class=\"afgcolorbox\" rel=\"example4$id\" href=\"$photo_page_url\"" .
                " title=\"{$photo['title']}\">" .
                "<img src=\"$photo_url\" alt=\"{$photo['title']}\"/></a>";
            if($size_heading_map[$photo_size] && $photo_title == 'on') {
                $disp_gallery .= "<br /><b>" .
                    "<font size=\"$size_heading_map[$photo_size]\">{$photo['title']}</font></b>";
            }

            if($photo_descr == 'on' && $photo_size != '_s' && $photo_size != '_t') {
                if($photo_info['photo']['description']['_content']) {
                    $disp_gallery .= "<p>" .
                        $photo_info['photo']['description']['_content'] . "</p>";
                }
            }

            if ($photo_size != '_s' && $photo_size != '_t') {
                $disp_gallery .= "<p>&nbsp;</p>";
            }

            $cur_col += 1;
            if ($cur_col % $columns == 0) {
                $disp_gallery .= '</td></tr>';
            }
            else {
                $disp_gallery .= '</td>';
            }
        }
        else {
            if ($pagination == 'on') {
                $disp_gallery .= "<tr style=\"display:none\"><td>";
                $disp_gallery .= "<a class=\"afgcolorbox\" rel=\"example4$id\" href=\"$photo_page_url\"" .
                    " title=\"{$photo['title']}\">" .
                    " <img alt=\"{$photo['title']}\"></a>";
                $disp_gallery .= "</td></tr>";
            }
        }
        $photo_count += 1;
    }

    if ($pagination == 'on' && $total_pages > 1) {
        $disp_gallery .= "<tr><td style=\"text-align:center; color:{$text_color};" .
            "vertical-align:top; background-color:{$bg_color}; font-size:90%;" .
            "border-color:{$bg_color}\" colspan=\"$columns\">";
        $disp_gallery .= "<p>";
        if ($cur_page == 1) {
            $disp_gallery .="<font style=\"border:1px solid gray;\">&nbsp;&#171; prev&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;";
            $disp_gallery .="<font style=\"border:1px solid gray; background:gray; color:white\"> 1 </font>&nbsp;";
        }
        else {
            $prev_page = $cur_page - 1;
            $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg{$id}_page_id=$prev_page\" title=\"Prev Page\">&nbsp;&#171; prev </a>&nbsp;&nbsp;&nbsp;&nbsp;";
            $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg{$id}_page_id=1\" title=\"Page 1\"> 1 </a>&nbsp;";
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
                $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg{$id}_page_id={$count}\" title=\"Page {$count}\">&nbsp;{$count} </a>&nbsp;";
            }
        }

        if ($count < $total_pages) $disp_gallery .= " ... ";
        if ($count <= $total_pages) {
            $disp_gallery .= "<a class=\"afg_page\" href=\"{$cur_page_url}?afg{$id}_page_id={$total_pages}\" title=\"Page {$total_pages}\">&nbsp;{$total_pages} </a>&nbsp;";
        }
        if ($cur_page == $total_pages) $disp_gallery .= "&nbsp;&nbsp;&nbsp;<font style=\"border:1px solid gray\">&nbsp;next &#187;&nbsp;</font>";
        else {
            $next_page = $cur_page + 1;
            $disp_gallery .= "&nbsp;&nbsp;&nbsp;<a class=\"afg_page\" href=\"{$cur_page_url}?afg{$id}_page_id=$next_page\" title=\"Next Page\"> next &#187; </a>&nbsp;";
        }

        $disp_gallery .= "<br />({$total_photos} Photos)</p></td></tr>";
    }
    if ($credit_note == 'on') {
        $disp_gallery .= "<tr><td style=\"text-align:center; color:{$text_color};" .
            "vertical-align:top; background-color:{$bg_color}; font-size:90%;" .
            "border-color:{$bg_color}\" colspan=\"$columns\">";
        $disp_gallery .= "<br /><p style='text-align:right'>Powered by " .
            "<a href=\"http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin\"" .
            "title=\"Awesome Flickr Gallery by Ronak Gandhi\"/>AFG</p></a>";
    }
    $disp_gallery .= '</table>';
    $disp_gallery .= "<!-- Awesome Flickr Gallery End -->";
    return $disp_gallery;
}
?>
