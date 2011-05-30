<?php
include_once('afg_libs.php');

function afg_add_gallery() {
    global $afg_per_page_map, $afg_photo_size_map, $afg_on_off_map,
        $afg_descr_map, $afg_columns_map, $afg_bg_color_map,
        $afg_photo_source_map;

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
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Add Gallery | Awesome Flickr Gallery</h2>

<?php
    if ($_POST) {
        $gallery = array(
            'name' => $_POST['afg_add_gallery_name'],
            'gallery_descr' => $_POST['afg_add_gallery_descr'],
            'photo_source' => $_POST['afg_photo_source_type'],
            'per_page' => filter($_POST['afg_per_page']),
            'photo_size' => filter($_POST['afg_photo_size']),
            'captions' => filter($_POST['afg_captions']),
            'descr' => filter($_POST['afg_descr']),
            'columns' => filter($_POST['afg_columns']),
            'credit_note' => filter($_POST['afg_credit_note']),
            'bg_color' => filter($_POST['afg_bg_color']),
            'width' => filter($_POST['afg_width']),
            'pagination' => filter($_POST['afg_pagination']),
        );

        if ($_POST['afg_photo_source_type'] == 'photoset') {
            $gallery['photoset_id'] = $_POST['afg_photosets_box'];
        }
        else if ($_POST['afg_photo_source_type'] == 'gallery') {
            $gallery['gallery_id'] = $_POST['afg_galleries_box'];
        }

        $galleries = get_option('afg_galleries');
        $galleries[] = $gallery;
        update_option('afg_galleries', $galleries);
        end($galleries);
        $id = key($galleries);
        ?>
        <div class="updated"><p><strong>
        <?php echo "Gallery \"{$_POST['afg_add_gallery_name']}\" created successfully.  Shortcode for this gallery is </strong>[AFG_gallery id='$id']" ?>
        </p></div>

    <?php
    }

    echo afg_generate_version_line();
    $url=$_SERVER['REQUEST_URI'];
?>

<form method='post' action='<?php echo $url ?>'>
<div class="postbox-container" style="width:70%;">
<div id="poststuff">
<div class="postbox">
    <h3>Gallery Parameters</h3>
    <table class='form-table'>
        <tr valign='top'>
        <th scope='row'>Gallery Name</th>
        <td><input maxlength='30' type='text' id='afg_add_gallery_name' name='afg_add_gallery_name' onblur="verifyAddBlank()" value="" /><font size='3' color='red'>*</font></td>
        </tr>
        <tr valign='top'>
        <th scope='row'>Gallery Description</th>
        <td><input maxlength='100' size='70%' type='text' id='afg_add_gallery_descr' name='afg_add_gallery_descr'" value="" /></td>
        </tr>
    </table>
</div></div>
<?php
    echo afg_generate_flickr_settings_table($photosets_map, $galleries_map);
    echo afg_generate_gallery_settings_table();
?>
<input type="submit" disabled='true' id="afg_save_changes" class="button-primary"
value="Add Gallery" />
</div>
<div class="postbox-container" style="width: 29%;">
<?php
    $message = "<b>Gallery Description</b> - Provide a meaningful description of
        your gallery for you to recognize it easily.<br /><br />
        <b>Gallery Source</b> - Where do you want to fetch your photos from?
        Your Flickr Photostream, a Photoset or a Gallery?<br /><br />
                <b>What is <i>Use Default</i>?</b> - When you select <i>Use
                Default</i> for a setting, it will be inherited from <a href=\"" .
                $_SERVER[PHP_SELF] . "?page=afg_plugin_page\"><i>Default
                Settings</i></a>.  If you change the <i>Default Settings</i>,
                the setting for this specific gallery will also change.
        ";
    echo afg_box('Help', $message);
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

<?php echo afg_donate_box() ?>
</div>
</form>
<?php
}
