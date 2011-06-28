<?php
add_action('admin_init', 'afg_admin_init');
add_action('admin_menu', 'afg_admin_menu');
include_once('afg_libs.php');
include_once('edit_galleries.php');
include_once('add_gallery.php');
include_once('add_users.php');
include_once('view_delete_galleries.php');

function afg_admin_menu() {
    add_menu_page('Awesome Flickr Gallery', 'Awesome Flickr Gallery', 'manage_options', 'afg_plugin_page', 'afg_admin_html_page', BASE_URL . "/images/afg_logo.png");
    add_submenu_page('afg_plugin_page', 'Default Settings | Awesome Flickr Gallery', 'Default Settings', 'manage_options', 'afg_plugin_page', 'afg_admin_html_page');
    $page2 = add_submenu_page('afg_plugin_page', 'Add Gallery | Awesome Flickr Gallery', 'Add Gallery', 'manage_options', 'afg_add_gallery_page', 'afg_add_gallery');
    $page3 = add_submenu_page('afg_plugin_page', 'Saved Galleries | Awesome Flickr Gallery', 'Saved Galleries', 'manage_options', 'afg_view_edit_galleries_page', 'afg_view_delete_galleries');
    $page1 = add_submenu_page('afg_plugin_page', 'Edit Galleries | Awesome Flickr Gallery', 'Edit Galleries', 'manage_options', 'afg_edit_galleries_page', 'afg_edit_galleries');
//    $page4 = add_submenu_page('afg_plugin_page', 'Add Users | Awesome Flickr Gallery', 'Add Users', 'manage_options', 'afg_add_users_page', 'afg_add_users');
    add_action('admin_print_styles-' . $page1, 'afg_edit_galleries_header');
    add_action('admin_print_styles-' . $page2, 'afg_edit_galleries_header');
    add_action('admin_print_styles-' . $page3, 'afg_view_delete_galleries_header');
    add_action('admin_print_styles-' . $page4, 'afg_delete_users_header');
    afg_setup_options();
}

function afg_setup_options() {
    if (get_option('afg_descr') == '1') update_option('afg_descr', 'on');
    if (get_option('afg_descr') == '0') update_option('afg_descr', 'off');
    if (get_option('afg_captions') == '1') update_option('afg_captions', 'on');
    if (get_option('afg_captions') == '0') update_option('afg_captions', 'off');
    if (get_option('afg_credit_note') == '1' || get_option('afg_credit_note') == 'Yes') update_option('afg_credit_note', 'on');
    if (get_option('afg_credit_note') == '0') update_option('afg_credit_note', 'off');
//    update_option('afg_galleries', '');
    if (!get_option('afg_pagination')) update_option('afg_pagination', 'on');

    $galleries = get_option('afg_galleries');
    if (!$galleries) {
        $galleries = array('0' =>
            array(
                'name' => 'My Photostream',
                'gallery_descr' => 'All photos from my Flickr Photostream with default settings.',
                ));
        update_option('afg_galleries', $galleries);
    }

    update_option('afg_version', VERSION);
}

/* Keep afg_admin_init() and afg_get_all_options() in sync all the time
 */

function afg_admin_init() {
    register_setting('afg_settings_group', 'afg_api_key');
    register_setting('afg_settings_group', 'afg_user_id');
    register_setting('afg_settings_group', 'afg_per_page');
    register_setting('afg_settings_group', 'afg_photo_size');
    register_setting('afg_settings_group', 'afg_captions');
    register_setting('afg_settings_group', 'afg_descr');
    register_setting('afg_settings_group', 'afg_columns');
    register_setting('afg_settings_group', 'afg_credit_note');
    register_setting('afg_settings_group', 'afg_bg_color');
    register_setting('afg_settings_group', 'afg_version');
    register_setting('afg_settings_group', 'afg_galleries');
    register_setting('afg_settings_group', 'afg_page_width');
    register_setting('afg_settings_group', 'afg_pagination');
    register_setting('afg_settings_group', 'afg_users');

    // Register javascripts
    wp_register_script('edit-galleries-script', BASE_URL . '/js/edit_galleries.js');
    wp_register_script('view-delete-galleries-script', BASE_URL . '/js/view_delete_galleries.js');
    wp_register_script('delete-users-script', BASE_URL . '/js/delete_users.js');
}

function afg_get_all_options() {
    return array(
        'afg_api_key' => get_option('afg_api_key'),
        'afg_user_id' => get_option('afg_user_id'),
        'afg_photo_size' => get_option('afg_photo_size'),
        'afg_per_page' => get_option('afg_per_page'),
        'afg_captions' => get_option('afg_captions'),
        'afg_descr' => get_option('afg_descr'),
        'afg_columns' => get_option('afg_columns'),
        'afg_credit_note' => get_option('afg_credit_note'),
        'afg_bg_color' => get_option('afg_bg_color'),
        'afg_width' => get_option('afg_width'),
        'afg_pagination' => get_option('afg_pagination'),
    );
}

function afg_admin_html_page() {
    global $afg_per_page_map, $afg_photo_size_map, $afg_on_off_map, $afg_descr_map, $afg_columns_map, $afg_bg_color_map, $afg_width_map;
?>
<div class='wrap'>
<h2><a href='http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Awesome Flickr Gallery Settings</h2>

<?php
if ($_POST) {
    if ($_POST['submit'] == 'Delete Cached Galleries') {
        $galleries = get_option('afg_galleries');
        foreach($galleries as $id => $ginfo) {
            delete_transient('afg_id_'. $id);
        }
        echo "<div class='updated'><p><strong>Cached data deleted successfully.</strong></p></div>";
    }
    else {
        update_option('afg_api_key', $_POST['afg_api_key']);
        update_option('afg_user_id', $_POST['afg_user_id']);
        update_option('afg_per_page', $_POST['afg_per_page']);
        update_option('afg_photo_size', $_POST['afg_photo_size']);
        update_option('afg_captions', $_POST['afg_captions']);
        update_option('afg_descr', $_POST['afg_descr']);
        update_option('afg_columns', $_POST['afg_columns']);
        update_option('afg_width', $_POST['afg_width']);
        update_option('afg_bg_color', $_POST['afg_bg_color']);

        if ($_POST['afg_credit_note']) update_option('afg_credit_note', 'on');
        else update_option('afg_credit_note', 'off');

        if ($_POST['afg_pagination']) update_option('afg_pagination', 'off');
        else update_option('afg_pagination', 'on');

        echo "<div class='updated'><p><strong>Settings updated successfully</strong></p></div>";
    }
}
$url=$_SERVER['REQUEST_URI']; ?>
<form method='post' action='<?php echo $url ?>'>
    <?php settings_fields('afg_settings_group'); ?>
    <?php do_settings_sections('afg_plugin_page'); ?>
    <?php echo afg_generate_version_line() ?>

    <div class="postbox-container" style="width:70%;">
    <div id="poststuff">
    <div class="postbox">
        <h3>Flickr Settings</h3>
        <table class='form-table'>
            <tr valign='top'>
            <th scope='row'>Flickr API Key</th>
            <td><input type='text' name='afg_api_key' value="<?php echo get_option('afg_api_key'); ?>" /> </td>
            <td><font size='2'>Don't have a Flickr API Key?  Get it from <a href="http://www.flickr.com/services/api/keys/" target='blank'>here.</a> Go through the <a href='http://www.flickr.com/services/api/tos/'>Flickr API Terms of Service.</a></font></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Flickr User ID</th>
            <td><input type='text' name='afg_user_id' value="<?php echo get_option('afg_user_id'); ?>" /> </td>
            <td><font size='2'>Don't know your Flickr Usesr ID?  Get it from <a href="http://idgettr.com/" target='blank'>here.</a></font></td>
            </tr>
        </table>
    </div></div>

    <div id="poststuff">
    <div class="postbox">
            <h3>Gallery Settings</h3>
        <table class='form-table'>

            <tr valign='top'>
            <th scope='row'>Max Photos Per Page</th>
            <td><select name='afg_per_page'>
                <?php echo afg_generate_options($afg_per_page_map, get_option('afg_per_page', '10')); ?>
            </select></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Size of the Photos</th>
            <td><select name='afg_photo_size'>
                <?php echo afg_generate_options($afg_photo_size_map, get_option('afg_photo_size', '_m')); ?>
            </select></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Photo Titles</th>
            <td><select name='afg_captions'>
                <?php echo afg_generate_options($afg_on_off_map, get_option('afg_captions', 'on')); ?>
            </select></td>
            <td><font size='2'>Photo title setting applies only to Thumbnail (and above) size photos.</font></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Photo Descriptions</th>
            <td><select name='afg_descr'>
                <?php echo afg_generate_options($afg_descr_map, get_option('afg_descr', 'off')); ?>
            </select></td>
            <td><font size='2'>Photo Description setting applies only to Small and Medium size photos. <font color='red'>WARNING:</font> Enabling descriptions for photos can significantly slow down loading of the gallery and hence is not recommended.</font></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>No of Columns</th>
            <td><select name='afg_columns'>
                <?php echo afg_generate_options($afg_columns_map, get_option('afg_columns', '2')); ?>
            </select></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Background Color</th>
            <td><select name='afg_bg_color'>
                <?php echo afg_generate_options($afg_bg_color_map, get_option('afg_bg_color', 'Transparent')); ?>
            </select></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Gallery Width</th>
            <td><select name='afg_width'>
                <?php echo afg_generate_options($afg_width_map, get_option('afg_width', 'auto')); ?>
            </select></td>
            <td><font size='2'>Width of the Gallery is relative to the width of the page where Gallery is being generated.  <i>Automatic</i> is 100% of page width.</font></td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Disable Pagination?</th>
            <td><input type='checkbox' name='afg_pagination' value='off'
                 <?php
                    if (get_option('afg_pagination', 'off') == 'off') {
                        echo 'checked=\'\'';
                    }
                ?>/></td>
            <td><font size='2'>Useful when displaying gallery in a sidebar widget where you want only few recent photos.</td>
            </tr>

            <tr valign='top'>
            <th scope='row'>Add a Small Credit Note?</th>
            <td><input type='checkbox' name='afg_credit_note' value='Yes'
                 <?php
                    if (get_option('afg_credit_note', 'on') == 'on') {
                        echo 'checked=\'\'';
                    }
                 ?>/></td>
            <td><font size='2'>Credit Note will appear at the bottom of the gallery as - </font>
                Powered by
                <a href="http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin" title="Awesome Flickr Gallery by Ronak Gandhi"/>
                AFG</a></td>
            </tr>
        </table>
    </div></div>
<input type="submit" name="submit" class="button-primary" value="Save Changes" />
<br /><br />
<div id="poststuff">
<div class="postbox">
<h3>Your Photostream Preview</h3>
    <table class='form-table'>
    <tr><th>If your Flickr Settings are correct, 5 of your recent photos from your Flickr photostream should appear here.</th></tr>
    <td>
<?php

$params = array(
    'api_key' => get_option('afg_api_key'),
    'method' => 'flickr.people.getPublicPhotos',
    'format' => 'php_serial',
    'user_id' => get_option('afg_user_id'),
    'per_page' => 5,
);

$rsp_obj = afg_get_flickr_data($params);
if ($rsp_obj['stat'] == 'fail') {
    echo $rsp_obj['message'];
}
else {
    foreach($rsp_obj['photos']['photo'] as $photo) {
        $photo_url = "http://farm{$photo['farm']}.static.flickr.com/{$photo['server']}/{$photo['id']}_{$photo['secret']}_s.jpg";
        echo "<img src=\"$photo_url\"/>&nbsp;&nbsp;&nbsp;";
    }
}
?>
<br />
Note:  This preview is based on the Flickr Settings only.  Gallery Settings 
have no effect on this preview.  You will need to insert gallery code to a post 
or page to actually see the Gallery.
</td>
    </table></div>
<input type="submit" name="submit" class="button" value="Delete Cached Galleries" />
</div>
<?php
if (DEBUG) {
    $all_options = afg_get_all_options();
    foreach($all_options as $key => $value) {
        echo $key . ' => ' . $value . '<br />';
    }
}
?>
</div>
<div class="postbox-container" style="width: 29%;">
<?php
    $message = "<b>What are Default Settings?</b> - Default Settings serve as a 
    template for the galleries.  When you create a new gallery, you can assign 
    <i>Use Default</i> to a setting.  Such a setting will reference the <b>Default 
    Settings</b> instead of a specific setting defined for that particular 
    gallery. <br /> <br />
    When you change any of <b>Default Settings</b>, all the settings in a gallery 
    referencing the <b>Default Settings</b> will inherit the new value.
    ";
    echo afg_box('Help', $message);

    $message = "Just insert the code <strong><font color='steelblue'>[AFG_gallery]</font></strong> in any of your posts or pages to display the Awesome Flickr Gallery.
        <br /><p style='text-align:center'><i>-- OR --</i></p>You can create a new Awesome Flickr Gallery with different settings on page <a href='{$_SERVER[PHP_SELF]}?page=afg_add_gallery_page'>Add Galleries.";
    echo afg_box('Usage Instructions', $message);

 echo afg_donate_box() ?>
</div>
</form>
<?php

}
?>
