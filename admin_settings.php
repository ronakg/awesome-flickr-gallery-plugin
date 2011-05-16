<?php
add_action('admin_menu', 'afg_admin_menu');
define('BASE_URL', get_option('siteurl') . '/wp-content/plugins/' . basename(dirname(__FILE__)));
define('DEBUG', False);
define('VERSION', '1.3.0');

function afg_admin_menu() {
    add_menu_page('Awesome Flickr Gallery', 'Awesome Flickr Gallery', 'manage_options', 'afg_plugin_page', 'afg_admin_html_page', BASE_URL . "/images/afg_logo.png");
    /*
    add_submenu_page('afg_plugin_page', 'Awesome Flickr Gallery | Settings', 'Settings', 'manage_options', 'afg_plugin_page', 'afg_admin_html_page');
    add_submenu_page('afg_plugin_page', 'Awesome Flickr Gallery | Edit Galleries', 'Edit Galleries', 'manage_options', 'afg_edit_galleries_page', 'afg_edit_galleries_page');
    add_submenu_page('afg_plugin_page', 'Awesome Flickr Gallery | Add Gallery', 'Add Gallery', 'manage_options', 'afg_add_gallery_page', 'afg_add_gallery_page');
     */
    add_action('admin_init', 'afg_register_settings');
}

/* Keep afg_register_settings() and afg_get_all_options() in sync all the time
 */

function afg_register_settings() {
    register_setting('afg_settings_group', 'afg_api_key');
    register_setting('afg_settings_group', 'afg_user_id');
    register_setting('afg_settings_group', 'afg_per_page');
    register_setting('afg_settings_group', 'afg_photo_size');
    register_setting('afg_settings_group', 'afg_captions');
    register_setting('afg_settings_group', 'afg_descr');
    register_setting('afg_settings_group', 'afg_columns');
    register_setting('afg_settings_group', 'afg_credit_note');
    register_setting('afg_settings_group', 'afg_bg_color');
    register_setting('afg_settings_group', 'afg_galleries');
    register_setting('afg_settings_group', 'afg_gallery_count');

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
        'afg_galleries' => get_option('afg_galleries'),
        'afg_gallery_count' => get_option('afg_gallery_count'),
    );
}

/*
function afg_edit_galleries_page() {
?>
<div class='wrap'>
<h2><a href='http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Manage Galleries</h2>
</div>
<?php
    $galleries = get_option('afg_galleries');
    if ($galleries) {

    }
    else {
        echo 'No Galleries Found';
    }
}

function afg_add_gallery_page() {
$url=$_SERVER['REQUEST_URI']; ?>
<div class='wrap'>
<h2><a href='http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Add New Gallery</h2>
<form method='post' action='<?php echo $url ?>'>
    <table class='form-table'>
        <tr valign='top'>
        <th scope='row'>Gallery Name:</td>
        <td><input type='text' name='afg_gallery_name' /></td>
        </tr>
    </table>
</form>
</div>
<?php
}
 */

function afg_admin_html_page() {
    $afg_per_page_map = array(
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
        '_s' => 'Square (Max 75px)',
        '_t' => 'Thumbnail (Max 100px)',
        '_m' => 'Small (Max 240px - Recommended)',
        'NULL' => 'Medium (Max 500px)',
    );
    $afg_on_off_map = array(
        '1' => 'On  ',
        '0' => 'Off  ',
    );
    $afg_credit_note_map = array(
        '1' => 'Yes',
        '0' => 'No',
    );
    $afg_descr_map = array(
        '0' => 'Off (Faster)',
        '1' => 'On (Slower)',
    );
    $afg_columns_map = array(
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
        'Black' => 'Black',
        'White' => 'White',
        'Transparent' => 'Transparent',
    );
    function generate_options($params, $variable, $default) {
        $str = '';
        foreach($params as $key => $value) {
            if (get_option($variable, $default) == $key) {
                $str .= "<option value=" . $key . " selected='selected'>" . $value . "</option>";
            }
            else {
                $str .= "<option value=" . $key . ">" . $value . "</option>";
            }
        }
        return $str;
    }
?>
<div class='wrap'>
<h2><a href='http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Awesome Flickr Gallery Settings</h2>

<?php
if ($_POST) {
    update_option('afg_api_key', $_POST['afg_api_key']);
    update_option('afg_user_id', $_POST['afg_user_id']);
    update_option('afg_per_page', $_POST['afg_per_page']);
    update_option('afg_photo_size', $_POST['afg_photo_size']);
    update_option('afg_captions', $_POST['afg_captions']);
    update_option('afg_descr', $_POST['afg_descr']);
    update_option('afg_columns', $_POST['afg_columns']);
    update_option('afg_credit_note', $_POST['afg_credit_note']);
    update_option('afg_bg_color', $_POST['afg_bg_color']);
}
$url=$_SERVER['REQUEST_URI']; ?>
<form method='post' action='<?php echo $url ?>'>
    <?php settings_fields('afg_settings_group'); ?>
    <?php do_settings_sections('afg_plugin_page'); ?>

    <h4 align="right">
        &nbsp;&nbsp;&nbsp;Version: <b><?php echo VERSION; ?></b> |
         <a href="http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/faq/">FAQ</a> |
         <a href="http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/">Rate this plugin</a> |
         <a href="http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/">Support</a> |
         <a href="http://wordpress.org/extend/plugins/awesome-flickr-gallery-plugin/changelog/">Changelog</a> |
         <a href="http://www.ronakg.in/photography/">Live Demo</a> |
         <a href="http://www.ronakg.in/about/">Contact Ronak Gandhi</a>
    </h4>

    <table class='form-table'>
        <tr valign='top'>
        <th colspan='2'><font size='4' color='SteelBlue'><b><i>Flickr Settings</i></b></font></th>
        </tr>

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

        <tr valign='top'>
        <th colspan='2'><font size='4' color='SteelBlue'><b><i>Gallery Settings</i></b></font></th>
        </tr>

        <tr valign='top'>
        <th scope='row'>Maximum photos to display per page in the gallery</th>
        <td><select name='afg_per_page'>
            <?php echo generate_options($afg_per_page_map, 'afg_per_page', '10'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Size of the photos</th>
        <td><select name='afg_photo_size'>
            <?php echo generate_options($afg_photo_size_map, 'afg_photo_size', '_m'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Titles</th>
        <td><select name='afg_captions'>
            <?php echo generate_options($afg_on_off_map, 'afg_captions'); ?>
        </select></td>
        <td><font size='2'>Photo title setting applies only to Thumbnail (and above) size photos.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Photo Descriptions</th>
        <td><select name='afg_descr'>
            <?php echo generate_options($afg_descr_map, 'afg_descr', '0'); ?>
        </select></td>
        <td><font size='2'>Photo Description setting applies only to Small and Medium size photos. <font color='red'>WARNING:</font> Enabling descriptions for photos can significantly slow down loading of the gallery and hence is not recommended.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>No of columns</th>
        <td><select name='afg_columns'>
            <?php echo generate_options($afg_columns_map, 'afg_columns', '2'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Background Color</th>
        <td><select name='afg_bg_color'>
            <?php echo generate_options($afg_bg_color_map, 'afg_bg_color', 'Transparent'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th colspan='2'><font size='4' color='SteelBlue'><b><i>Support this plugin</i></b></font></th>
        </tr>

        <tr valign='top'>
        <th scope='row'>Add a small credit note about this plugin at the bottom?</th>
        <td style='vertical-align:middle'><input type='checkbox' name='afg_credit_note' value='Yes'
             <?php
                if (get_option('afg_credit_note', 'Yes') == 'Yes') {
                    echo 'checked=\'\'';
                }
             ?>/></td>
        <td><font size='2'>Credit Note will appear as - </font>
            Powered by
            <a href="http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin"/>
            AFG</a></td>
        </tr>
    </table>

<p class="submit">
<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
<?php if ($_POST) { ?>
<div class="updated"><p><strong><?php echo 'Settings updated successfully' ?></strong></p></div>
<?php
if (DEBUG) {
    $all_options = afg_get_all_options();
    foreach($all_options as $key => $value) {
        echo $key . ' => ' . $value . '<br />';
    }
}

}
?>
</p>

<p><h3>Usage Instructions:</h3><hr />
Just use code [AFG_gallery] in any of the posts or page to display your Flickr gallery.</p>

</form>
<br />
<br />
<h3>Support this plugin</h3><hr />
It takes time and effort to keep releasing new versions of this plugin.  If you like it, consider donating a few bucks to keep receiving new features.
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><div class="paypal-donations"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="business" value="2P32M6V34HDCQ" /><input type="hidden" name="currency_code" value="USD" /><input type="image" src="<?php echo BASE_URL . "/images/donate_small.png"; ?>" name="submit" alt="PayPal - The safer, easier way to pay online." /><img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></div></form>

</div>
<?php
}
?>
