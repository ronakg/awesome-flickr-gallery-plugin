<?php
/*
Plugin Name: Awesome Flickr Gallery
Plugin URI: http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/
Description: A fully customizable Flickr Gallery plug-in for WordPress.
Version: 2.6.1
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
add_shortcode('AFG_gallery', 'afg_display_gallery');

/* Load Lightbox plugin in <head> section of the theme. */
add_action('wp_head', 'afg_add_lightbox_headers');

function afg_add_lightbox_headers() {
    echo "<link href=\"" . BASE_URL . "/colorbox/colorbox.css\" rel=\"stylesheet\" media=\"screen\">";
    echo "<script src=\"https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js\"></script>";
    echo "<script src=\"" . BASE_URL . "/colorbox/jquery.colorbox-min.js\"></script>";
    echo "<script src=\"" . BASE_URL . "/colorbox/mycolorbox.js\"></script>";
    echo "<style type=\"text/css\">
          a.afg_page:hover {background:royalblue;text-decoration:underline;color:white;}
          a.afg_page:visited, a.afg_page:link {text-decoration:none;border:1px solid gray;}
          </style>";
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
    $cur_page_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];

    preg_match('/\?afg_page_id=(?P<page_id>\d+)/', $cur_page_url, $matches);
    if ($matches) {
        $cur_page = ($matches['page_id']);
        $match_pos = strpos($cur_page_url, "?afg_page_id=$cur_page");
        $cur_page_url = substr($cur_page_url, 0, $match_pos);
    }

    $galleries = get_option('afg_galleries');
    $gallery = $galleries[$id];

    if (DEBUG) print_r($gallery);

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

    $disp_gallery = '';

    if (DEBUG) {
        $disp_gallery .= 'API Key - ' . $api_key . '<br />';
        $disp_gallery .= 'User ID - ' . $user_id . '<br />';
        $disp_gallery .= 'Per Page - ' . $per_page . '<br />';
        $disp_gallery .= 'Photo Size - ' . $photo_size . '<br />';
        $disp_gallery .= 'Captions - ' . $photo_title . '<br />';
        $disp_gallery .= 'Description - ' . $photo_descr . '<br />';
        $disp_gallery .= 'Columns - ' . $columns . '<br />';
        $disp_gallery .= 'Credit Note - ' . $credit_note . '<br />';
        $disp_gallery .= 'Background Color - ' . $bg_color . '<br />';
        $disp_gallery .= 'Width - ' . $gallery_width . '<br />';
        $disp_gallery .= 'Pagination - ' . $pagination . '<br />';
    }

    /* Parameters to get public photos of the user.  Format we are requesting
     * from Flickr is php_serial.
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
    $cur_col = 0;

    if ($gallery_width == 'auto') $gallery_width = 100;

    $disp_gallery .= "<table " .
        "style=\"background-color:{$bg_color}; border-color:{$bg_color};\"" .
        "width='$gallery_width%'>";

    foreach($rsp_obj[$flickr_api]['photo'] as $photo) {
        $photo_url = afg_get_photo_url($photo['farm'], $photo['server'],
            $photo['id'], $photo['secret'], $photo_size);

        $text_color = $afg_text_color_map[$bg_color];

        if ($cur_col % $columns == 0) {
            /* Add an extra blank row for right margins */
            $disp_gallery .= "<tr><td style=\"text-align:left;" .
                "color:{$text_color}; vertical-align:top;" .
                "background-color:{$bg_color};" .
                "border-color:{$bg_color}\">&nbsp;</td></tr>";
            $disp_gallery .= "<tr><td style=\"text-align:left; margin-top:100;" .
                "color:{$text_color}; vertical-align:top;" .
                "background-color:{$bg_color}; border-color:{$bg_color}\">";
        }
        else {
            $disp_gallery .= "<td style=\"text-align:left; color:{$text_color};" .
                "vertical-align:top; background-color:{$bg_color};" .
                "border-color:{$bg_color}\">";
        }

        $photo_page_url = afg_get_photo_url($photo['farm'], $photo['server'],
            $photo['id'], $photo['secret'], '_z');
        $pid_len = strlen($photo['id']);

        $disp_gallery .= "<a href=\"$photo_page_url\"" .
            "class=\"afgcboxElement\" rel=\"example4\" title=\"{$photo['title']}\">" .
            "<img src=\"$photo_url\" alt=\"{$photo['title']}\"/></a>";
        if($size_heading_map[$photo_size] && $photo_title == 'on') {
            $disp_gallery .= "<br /><b>" .
                "<font size=\"$size_heading_map[$photo_size]\">{$photo['title']}</font></b>";
        }

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

    if ($pagination == 'on' && $total_pages > 1) {
        $disp_gallery .= "<tr><td style=\"text-align:center; color:{$text_color};" .
            "vertical-align:top; background-color:{$bg_color}; font-size:90%;" .
            "border-color:{$bg_color}\" colspan=\"$columns\"><br /><br />";
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

        $disp_gallery .= "<br />({$rsp_obj[$flickr_api]['total']} photos)<br /><br /></td></tr>";
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
    return $disp_gallery;
}
?>
