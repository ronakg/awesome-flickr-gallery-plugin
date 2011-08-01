<?php

define('BASE_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
define('SITE_URL', get_option('siteurl'));
define('DEBUG', false);
define('VERSION', '2.9.1');

/* Map for photo titles displayed on the gallery. */
$size_heading_map = array(
    '_s' => '',
    '_t' => '0.8em',
    '_m' => '1em',
    'NULL' => '1.1em',
);

$afg_photo_source_map = array(
    'photostream' => 'Photostream',
    'gallery' => 'Gallery',
    'photoset' => 'Photoset',
    'group' => 'Group',
);

$afg_width_map = array(
    'default' => 'Default',
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
    'default' => 'Default',
    '1' => '1  ',
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
    'default' => 'Default',
    '_s' => 'Square (Max 75px)',
    '_t' => 'Thumbnail (Max 100px)',
    '_m' => 'Small (Max 240px - Recommended)',
    'NULL' => 'Medium (Max 500px)',
);

$afg_on_off_map = array(
    'off' => 'Off  ',
    'on' => 'On  ',
    'default' => 'Default',
);

$afg_yes_no_map = array(
    'off' => 'Yes  ',
    'on' => 'No  ',
    'default' => 'Default',
);

$afg_descr_map = array(
    'off' => 'Off',
    'on' => 'On',
    'default' => 'Default',
);

$afg_columns_map = array(
    'default' => 'Default',
    '1' => '1  ',
    '2' => '2  ',
    '3' => '3  ',
    '4' => '4  ',
    '5' => '5  ',
    '6' => '6  ',
    '7' => '7  ',
    '8' => '8  ',
    '9' => '9  ',
    '10' => '10 ',
    '11' => '11 ',
    '12' => '12 ',
);

$afg_bg_color_map = array(
    'default' => 'Default',
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

function delete_afg_caches() {
    $galleries = get_option('afg_galleries');
    foreach($galleries as $id => $ginfo) {
        delete_transient('afg_id_'. $id);
    }
}
function afg_get_photo_url($farm, $server, $pid, $secret, $size) {
    if ($size == 'NULL') {
        $size = '';
    }
    return "http://farm$farm.static.flickr.com/$server/{$pid}_$secret$size.jpg";
}

function afg_get_photo_page_url($pid, $uid) {
    return "http://www.flickr.com/photos/$uid/$pid";
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
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $rsp = curl_exec($curl);  // get the file
    if(curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    }
    curl_close($curl);
    return unserialize($rsp);
}

function afg_generate_version_line() {
    if(isset($_POST['afg_dismis_ss_msg']) && $_POST['afg_dismis_ss_msg']) {
        update_option('afg_dismis_ss_msg', true);
    }
    $return_str = "";

    if (get_option('afg_slideshow_option') == 'colorbox' && !get_option('afg_dismis_ss_msg')) {
        $return_str .= "<p style='background-color:#FFFFE0; line-height:140%; border:1px solid #E6DB55; border-radius:3px; margin:5px 0 15px; padding:6px 10px;'><b>A better slideshow is available for use.  You are using ColorBox," .
            " which doesn't support thumbnail slider.  Go to <a href='{$_SERVER['PHP_SELF']}?page=afg_advanced_page'>Advanced Settings</a>" .
            " to change your slideshow to HighSlide.</b>" .
            " <input type='submit' name='afg_dismis_ss_msg' class='button' value='Dismis Message'/>" .
            " </p>";
    }

   $return_str .= "" .
    " <h4 align=\"right\" style=\"margin-right:0.5%\">" .
       " &nbsp;Version: <b>" . VERSION . "</b> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/faq/\">FAQ</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/\">Rate this plugin</a> |" .
        " <a href=\"http://www.ronakg.com/discussions/\">Support Forums</a> |" .
        " <a href=\"http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/changelog/\">Changelog</a> |" .
        " <a href=\"http://www.ronakg.com/photos/\">Live Demo</a>" .
    " </h4>";
    return $return_str;
}



function afg_generate_flickr_settings_table($photosets, $galleries, $groups) {
    global $afg_photo_source_map;
    $photosets = afg_generate_options($photosets, '', False);
    $galleries = afg_generate_options($galleries, '', False);
    $groups = afg_generate_options($groups, '', False);
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
        </select>
        <select style='display:none' name='afg_groups_box' id='afg_groups_box'>$groups
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
        <td><input type='checkbox' name='afg_per_page_check' id='afg_per_page_check' onclick='showHidePerPage()' value='default' checked=''> Default </input><input name='afg_per_page' disabled='true' id='afg_per_page' type='text' size='3' maxlength='3' onblur='verifyBlank()' value='10'/> 
        </td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Size of Photos</th>
        <td><select name='afg_photo_size' id='afg_photo_size'>
            " . afg_generate_options($afg_photo_size_map, 'default', True, $afg_photo_size_map[get_option('afg_photo_size')]) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Titles</th>
        <td><select name='afg_captions' id='afg_captions'>
            " . afg_generate_options($afg_on_off_map, 'default', True, $afg_on_off_map[get_option('afg_captions')]) . "
        </select></td>
        <td><font size='2'>Photo title setting applies only to Thumbnail (and above) size photos.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Descriptions</th>
        <td><select name='afg_descr' id='afg_descr'>
            " . afg_generate_options($afg_descr_map, 'default', True, $afg_descr_map[get_option('afg_descr')]) . "
        </select></td>
        <td><font size='2'>Photo Description setting applies only to Small and Medium size photos.</td>
        </tr>

        <tr valign='top'>
        <th scope='row'>No of Columns</th>
        <td><select name='afg_columns' id='afg_columns'>
            " . afg_generate_options($afg_columns_map, 'default', True, $afg_columns_map[get_option('afg_columns')]) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Background Color</th>
        <td><select name='afg_bg_color' id='afg_bg_color'>
            " . afg_generate_options($afg_bg_color_map, 'default', True, $afg_bg_color_map[get_option('afg_bg_color')]) . "
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Gallery Width</th>
        <td><select name='afg_width' id='afg_width'>
        " . afg_generate_options($afg_width_map, 'default', True, $afg_width_map[get_option('afg_width')]) . "
        </select></td>
        <td><font size='2'>Width of the Gallery is relative to the width of the page where Gallery is being generated.  <i>Automatic</i> is 100% of page width.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Disable Pagination?</th>
        <td><select name='afg_pagination' id='afg_pagination'>
        " . afg_generate_options($afg_yes_no_map, 'default', True, $afg_yes_no_map[get_option('afg_pagination')]) . "
        </select></td>
        <td><font size='2'>Useful when displaying gallery in a sidebar widget where you want only few recent photos.</td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Add a Small Credit Note?</th>
        <td><select name='afg_credit_note' id='afg_credit_note'>
             " . afg_generate_options($afg_on_off_map, 'default', True, $afg_on_off_map[get_option('afg_credit_note')]) . "
             </select></td>
        <td><font size='2'>Credit Note will appear at the bottom of the gallery as - </font>
            Powered by
            <a href=\"http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin\"/>
            AFG</a></td>
        </tr>
    </table>
</div></div>";
}

function afg_generate_options($params, $selection, $show_default=False, $default_value=0) {
    $str = '';
    foreach($params as $key => $value) {
        if ($key == 'default' && !$show_default)
            continue;

        if ($selection == $key) {
            if ($selection == 'default') $value .= ' - ' . $default_value;
            $str .= "<option value=" . $key . " selected='selected'>" . $value . "</option>";
        }
        else
            $str .= "<option value=" . $key . ">" . $value . "</option>";
    }
    return $str;
}

function afg_filter($param) {
    if ($param == 'default') return "";
    else return $param;
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
    if (isset($gallery[$var]) && $gallery[$var]) return $gallery[$var];
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
