<?php

define('BASE_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
define('SITE_URL', get_option('siteurl'));
define('DEBUG', false);
define('VERSION', '2.7.1');

/* Map for photo titles displayed on the gallery. */
$size_heading_map = array(
    '_s' => '',
    '_t' => '1',
    '_m' => '2',
    'NULL' => '3',
);

$afg_photo_source_map = array(
    'photostream' => 'Photostream',
    'gallery' => 'Gallery',
    'photoset' => 'Photoset',
);

$afg_width_map = array(
    'default' => 'Use Default',
    'auto' => 'Automatic',
    '10' => '10 %',
    '20' => '20 %',
    '30' => '30 %',
    '40' => '40 %',
    '50' => '50 %',
    '60' => '60 %',
    '70' => '70 %',
    '80' => '80 %',
    '90' => '90 %',
);

$afg_per_page_map = array(
    'default' => 'Use Default',
    '4' => '4  ',
    '5' => '5  ',
    '6' => '6  ',
    '7' => '7  ',
    '8' => '8  ',
    '9' => '9  ',
    '10' => '10 ',
    '11' => '11 ',
    '12' => '12 ',
    '13' => '13 ',
    '14' => '14 ',
    '15' => '15 ',
    '16' => '16 ',
    '17' => '17 ',
    '18' => '18 ',
    '19' => '19 ',
    '20' => '20 ',
    '21' => '21 ',
    '22' => '22 ',
    '23' => '23 ',
    '24' => '24 ',
    '25' => '25 ',
);

$afg_photo_size_map = array(
    'default' => 'Use Default',
    '_s' => 'Square (Max 75px)',
    '_t' => 'Thumbnail (Max 100px)',
    '_m' => 'Small (Max 240px - Recommended)',
    'NULL' => 'Medium (Max 500px)',
);

$afg_on_off_map = array(
    'off' => 'Off  ',
    'on' => 'On  ',
    'default' => 'Use Default',
);

$afg_yes_no_map = array(
    'off' => 'Yes  ',
    'on' => 'No  ',
    'default' => 'Use Default',
);

$afg_descr_map = array(
    'off' => 'Off (Faster)',
    'on' => 'On (Slower)',
    'default' => 'Use Default',
);

$afg_columns_map = array(
    'default' => 'Use Default',
    '1' => '1  ',
    '2' => '2  ',
    '3' => '3  ',
    '4' => '4  ',
    '5' => '5  ',
    '6' => '6  ',
    '7' => '7  ',
    '8' => '8  ',
);

$afg_bg_color_map = array(
    'default' => 'Use Default',
    'Black' => 'Black',
    'White' => 'White',
    'Transparent' => 'Transparent',
);

$afg_text_color_map = array(
    'Black' => 'White',
    'White' => 'Black',
);

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

function afg_get_photo_url($farm, $server, $pid, $secret, $size) {
    if ($size == 'NULL') {
        $size = '';
    }
    return "http://farm$farm.static.flickr.com/$server/{$pid}_$secret$size.jpg";
}

function afg_construct_url($encoded_params) {
    $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
    return $url;
}

function create_cache($content, $cache_file) {
    $fp = fopen($cache_file, 'w');
    fwrite($fp, $content);
    fclose($fp);
}

function get_cached_content($cache_file) {
    if (file_exists($cache_file)) {
        return file_get_contents($cache_file);
    }
    return false;
}

function afg_get_flickr_data($params) {
    $encoded_params = afg_get_encoded_params($params);
    $url = afg_construct_url($encoded_params);
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $rsp = curl_exec($curl);  // get the file
    curl_close($curl);
    return unserialize($rsp);
}

function afg_generate_version_line() {
    return "" .
    " <h4 align=\"right\" style=\"margin-right:0.5%\">" .
       " &nbsp;Version: <b>" . VERSION . "</b> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/faq/\">FAQ</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/\">Rate this plugin</a> |" .
        " <a href=\"http://www.ronakg.in/discussions/\">Support Forums</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/changelog/\">Changelog</a> |" .
        " <a href=\"http://www.ronakg.in/photography/\">Live Demo</a>" .
    " </h4>";
}

function afg_generate_flickr_settings_table($photosets, $galleries, $default_photoset='', $default_gallery='') {
    global $afg_photo_source_map;
    $photosets = afg_generate_options($photosets, '', False);
    $galleries = afg_generate_options($galleries, '', False);
    return "
    <div id=\"poststuff\">
<div class=\"postbox\">
    <h3>Flickr Settings</h3>
    <table class='form-table'>
        <tr valign='top'>
        <th scope='row'>Gallery Source</th>
        <td><select name='afg_photo_source_type' id='afg_photo_source_type' onchange='getPhotoSourceType()' >" . afg_generate_options($afg_photo_source_map, 'photostream', False) . "
        </select></td>
        </tr>
        <tr>
        <th id='afg_photo_source_label'></th>
        <td><select style='display:none' name='afg_photosets_box' id='afg_photosets_box'>$photosets
        </select>
        <select style='display:none' name='afg_galleries_box' id='afg_galleries_box'>$galleries
        </select></td>
        </tr>
    </table>
</div></div>";

}

function afg_generate_gallery_settings_table() {
    global $afg_per_page_map, $afg_photo_size_map, $afg_on_off_map, $afg_descr_map, $afg_columns_map, $afg_bg_color_map, $afg_photo_source_map, $afg_width_map, $afg_yes_no_map;
    return "
    <div id=\"poststuff\">
        <div class=\"postbox\">
        <h3>Gallery Settings</h3>
        <table class='form-table'>

        <tr valign='top'>
        <th scope='row'>Max Photos Per Page</th>
        <td><select name='afg_per_page' id='afg_per_page'>
            " . afg_generate_options($afg_per_page_map, 'default', True) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Size of Photos</th>
        <td><select name='afg_photo_size' id='afg_photo_size'>
            " . afg_generate_options($afg_photo_size_map, 'default', True) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Titles</th>
        <td><select name='afg_captions' id='afg_captions'>
            " . afg_generate_options($afg_on_off_map, 'default', True) . "
        </select></td>
        <td><font size='2'>Photo title setting applies only to Thumbnail (and above) size photos.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Descriptions</th>
        <td><select name='afg_descr' id='afg_descr'>
            " . afg_generate_options($afg_descr_map, 'default', True) . "
        </select></td>
        <td><font size='2'>Photo Description setting applies only to Small and Medium size photos. <font color='red'>WARNING:</font> Enabling descriptions for photos can significantly slow down loading of the gallery and hence is not recommended.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>No of Columns</th>
        <td><select name='afg_columns' id='afg_columns'>
            " . afg_generate_options($afg_columns_map, 'default', True) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Background Color</th>
        <td><select name='afg_bg_color' id='afg_bg_color'>
            " . afg_generate_options($afg_bg_color_map, 'default', True) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Gallery Width</th>
        <td><select name='afg_width' id='afg_width'>
        " . afg_generate_options($afg_width_map, 'default', True) . "
        </select></td>
        <td><font size='2'>Width of the Gallery is relative to the width of the page where Gallery is being generated.  <i>Automatic</i> is 100% of page width.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Disable Pagination?</th>
        <td><select name='afg_pagination' id='afg_pagination'>
        " . afg_generate_options($afg_yes_no_map, 'default', True) . "
        </select></td>
        <td><font size='2'>Useful when displaying gallery in a sidebar widget where you want only few recent photos.</td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Add a Small Credit Note?</th>
        <td><select name='afg_credit_note' id='afg_credit_note'>
             " . afg_generate_options($afg_on_off_map, 'default', True) . "
             </select></td>
        <td><font size='2'>Credit Note will appear at the bottom of the gallery as - </font>
            Powered by
            <a href=\"http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin\"/>
            AFG</a></td>
        </tr>
    </table>
</div></div>";
}

function afg_generate_options($params, $selection, $show_default=False) {
    $str = '';
    foreach($params as $key => $value) {
        if ($key == 'default' && !$show_default) {
            continue;
        }

        if ($selection == $key) {
            $str .= "<option value=" . $key . " selected='selected'>" . $value . "</option>";
        }
        else {
            $str .= "<option value=" . $key . ">" . $value . "</option>";
        }
    }
    return $str;
}

function filter($param) {
    if ($param == 'default') {
        return "";
    }
    else {
        return $param;
    }
}

function afg_box($title, $message) {
     return "
        <div id=\"poststuff\">
        <div class=\"postbox\">
        <h3>$title</h3>
        <table class='form-table'>
        <td>$message</td>
        </table>
        </div></div>
        ";
}

function afg_usage_box($code) {
    return "
        <div id=\"poststuff\">
        <div class=\"postbox\">
        <h3>Usage Instructions</h3>
        <table class='form-table'>
        <td>Just insert $code in any of the posts or page to display your Flickr gallery.</td>
        </table>
        </div></div>
        ";
}

function get_afg_option($gallery, $var) {
    if ($gallery[$var]) return $gallery[$var];
    else return get_option('afg_' . $var);
}

function afg_donate_box() {
    return "
        <div id=\"poststuff\">
        <div class=\"postbox\">
        <h3>Support this plugin</h3>
        <table class='form-table'>
        <td>It takes time and effort to keep releasing new versions of this plugin.  If you like it, consider donating a few bucks <b>(especially if you are using this plugin on a commercial website)</b> to keep receiving new features.
        </form><form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"><div style=\"text-align:center\" class=\"paypal-donations\"><input type=\"hidden\" name=\"cmd\" value=\"_donations\" /><input type=\"hidden\" name=\"business\" value=\"2P32M6V34HDCQ\" /><input type=\"hidden\" name=\"currency_code\" value=\"USD\" /><input type=\"image\" src=\"" . BASE_URL . "/images/donate_small.png\" name=\"submit\" alt=\"PayPal - The safer, easier way to pay online.\" /><img alt=\"PayPal Donate\" src=\"https://www.paypal.com/en_US/i/scr/pixel.gif\" width=\"1\" height=\"1\" /><br /><b>All major credit cards are accepted too.</b></div></form>
        </td>
        </table>
        </div></div>";
}

function afg_reference_box() {
    $message = "Max Photos Per Page - <b>" . get_option('afg_per_page') . "</b>";
    $size = get_option('afg_photo_size');
    if ($size == '_s') $size = 'Square';
    else if ($size == '_t') $size = 'Thumbnail';
    else if ($size == '_m') $size = 'Small';
    else if ($size == 'NULL') $size = 'Medium';
    $message .= "<br />Size of Photos - <b>" . $size . "</b>";
    $message .= "<br />Photo Titles - <b>" . get_option('afg_captions') . "</b>";
    $message .= "<br />Photo Descriptions - <b>" . get_option('afg_descr') . "</b>";
    $message .= "<br />No of Columns - <b>" . get_option('afg_columns') . "</b>";
    $message .= "<br />Background Color - <b>" . get_option('afg_bg_color') . "</b>";
    $message .= "<br />Gallery Width - <b>" . ((get_option('afg_width') == 'auto')?"Automatic":get_option('afg_width') . "%") . "</b>";
    $message .= "<br />Pagination - <b>" . get_option('afg_pagination') . "</b>";
    $message .= "<br />Credit Note - <b>" . get_option('afg_credit_note') . "</b>";
    return afg_box('Default Settings for Reference', $message);
}

?>
