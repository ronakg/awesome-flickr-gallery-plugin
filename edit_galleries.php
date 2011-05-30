<?php
include_once('afg_libs.php');
$default_gallery_id = 0;

if ($_POST && $_POST['afg_edit_gallery_name']) {
    global $default_gallery_id;
    $gallery = array(
        'name' => $_POST['afg_edit_gallery_name'],
        'gallery_descr' => $_POST['afg_edit_gallery_descr'],
        'photo_source' => $_POST['afg_photo_source_type'],
        'per_page' => filter($_POST['afg_per_page']),
        'photo_size' => filter($_POST['afg_photo_size']),
        'captions' => filter($_POST['afg_captions']),
        'descr' => filter($_POST['afg_descr']),
        'columns' => filter($_POST['afg_columns']),
        'credit_note' => filter($_POST['afg_credit_note']),
        'width' => filter($_POST['afg_width']),
        'pagination' => filter($_POST['afg_pagination']),
        'bg_color' => filter($_POST['afg_bg_color']),
    );

    if ($_POST['afg_photo_source_type'] == 'photoset') $gallery['photoset_id'] = $_POST['afg_photosets_box'];
    else if ($_POST['afg_photo_source_type'] == 'gallery') $gallery['gallery_id'] = $_POST['afg_galleries_box'];

    $id = $_POST['afg_photo_gallery'];

    $galleries = get_option('afg_galleries');
    $galleries[$id] = $gallery;
    update_option('afg_galleries', $galleries);
    $default_gallery_id = $id;
}

function afg_edit_galleries_header() {
    $params = array(
        'api_key' => get_option('afg_api_key'),
        'user_id' => get_option('afg_user_id'),
        'galleries' => json_encode(get_option('afg_galleries')),
    );
    wp_enqueue_script('edit-galleries-script');
    wp_localize_script('edit-galleries-script', 'genparams', $params);
}

function afg_get_galleries($default='') {
    $galleries = get_option('afg_galleries');
    $gstr = "";
    foreach($galleries as $id => $ginfo) {
        if ($id) {
            if ($id == $default)
                $gstr .= "<option value=\"$id\" selected>$id - {$ginfo['name']}</option>";
            else
                $gstr .= "<option value=\"$id\">$id - {$ginfo['name']}</option>";
        }
    }
    return $gstr;
}

function afg_edit_galleries() {
    global $afg_per_page_map, $afg_photo_size_map, $afg_on_off_map,
        $afg_descr_map, $afg_columns_map, $afg_bg_color_map,
        $afg_photo_source_map, $default_gallery_id;

    $cur_page_url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    preg_match('/\&gallery_id=(?P<gallery_id>\d+)/', $cur_page_url, $matches);
    if ($matches && !$default_gallery_id) {
        $default_gallery_id = $matches['gallery_id'];
        $match_pos = strpos($cur_page_url, "&gallery_id=$default_gallery_id");
        $cur_page_url = substr($cur_page_url, 0, $match_pos);
    }

    $params = array(
        'api_key' => get_option('afg_api_key'),
        'method' => 'flickr.photosets.getList',
        'format' => 'php_serial',
        'user_id' => get_option('afg_user_id'),
    );

    $rsp_obj = afg_get_flickr_data($params);
    if ($rsp_obj['stat'] == 'fail') {
        echo $rsp_obj['message'];
    }
    else {
        $photosets_map = array();
        foreach($rsp_obj['photosets']['photoset'] as $photoset) {
            $photosets_map[$photoset['id']] = $photoset['title']['_content'];
        }
    }

    $params = array(
        'api_key' => get_option('afg_api_key'),
        'method' => 'flickr.galleries.getList',
        'format' => 'php_serial',
        'user_id' => get_option('afg_user_id'),
    );

    $rsp_obj = afg_get_flickr_data($params);
    if ($rsp_obj['stat'] == 'fail') {
        echo $rsp_obj['message'];
    }
    else {
        $galleries_map = array();
        foreach($rsp_obj['galleries']['gallery'] as $gallery) {
            $galleries_map[$gallery['id']] = $gallery['title']['_content'];
        }
    }
?>
<div class='wrap'>
<h2><a href='http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Edit Galleries | Awesome Flickr Gallery</h2>
<?php if ($_POST) {
?>
    <div class="updated"><p><strong>
       Gallery updated successfully.
        </strong></p></div>
<?php   }

echo afg_generate_version_line();
    $url=$_SERVER['REQUEST_URI'];
?>

<form method='post' action='<?php echo $url ?>'>
<div class="postbox-container" style="width:70%;">

<div id="poststuff">
<div class="postbox">
    <h3>Saved Galleries</h3>
    <table class='form-table'>
        <tr valign='top'>
        <th scope='row'>Select Gallery to Edit</th>
        <td><select id='afg_photo_gallery' name='afg_photo_gallery' onchange='loadGallerySettings()'>
            <?php echo afg_get_galleries($default_gallery_id) ?>
        </select></td>
        <tr valign='top'>
        <th scope='row'>Gallery Name</th>
        <td><input maxlength='30' type='text' id='afg_edit_gallery_name' name='afg_edit_gallery_name' onblur="verifyEditBlank()" value="" /><font size='3' color='red'>*</font></td>
        </tr>
        <tr valign='top'>
        <th scope='row'>Gallery Description</th>
        <td><input maxlength='100' size='70%' type='text' id='afg_edit_gallery_descr' name='afg_edit_gallery_descr' value="" /></td>
        </tr>
    </table>
</div></div>

<?php
    echo afg_generate_flickr_settings_table($photosets_map, $galleries_map);
    echo afg_generate_gallery_settings_table();
    $gals = get_option('afg_galleries');
    if (sizeof($gals) == 1) $disable_submit = True;
    else $disable_submit = False;
?>

<input type="submit" id="afg_save_changes" class="button-primary"
<?php if ($disable_submit) echo "disabled='yes'"; ?>
 value="Save Changes" />
<br /><br />
<div id="poststuff">
<div class="postbox">
    <h3>Gallery Code</h3>
    <table class='form-table'>
        <tr valign='top'>
        <td>
        <p id='afg_flickr_gallery_code'>[AFG_gallery]</p>
        </td>
        </tr>
    </table>
</div></div>
</div>
<div class="postbox-container" style="width: 29%;">
<?php
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
    $message .= "<br />Credit Note - <b>" . get_option('afg_credit_note') . "</b>";
 echo afg_box('Default Settings for Reference', $message) ?>
<?php echo afg_box('Usage Instructions', 'Insert the Gallery Code in any of your posts of pages to display your Flickr Gallery.') ?>
<?php echo afg_donate_box() ?>
</div>
</form>
<?php
}
