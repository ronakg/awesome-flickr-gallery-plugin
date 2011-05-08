<?php
add_action('admin_menu', 'afg_admin_menu');
$base_url = get_option('siteurl') . '/wp-content/plugins/awesome-flickr-gallery-plugin';

function afg_admin_menu() {
    global $base_url;
    add_menu_page('Awesome Flickr Gallery', 'Awesome Flickr Gallery',
        'manage_options', 'afg_plugin_page', 'afg_admin_html_page', "$base_url/images/afg_logo.png");
    add_action('admin_init', 'afg_register_settings');
}

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
}

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
    function generate_options($params, $variable) {
        $str = '';
        foreach($params as $key => $value) {
            if (get_option($variable) == $key) {
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
$base_url = get_option('siteurl') . '/wp-content/plugins/awesome-flickr-gallery-plugin';
echo ($base_url . '/images/logo_big.png'); ?>" align='center'/></a>Awesome Flickr Gallery Settings</h2>

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
        <th scope='row'>Maximum photos to display in the gallery</th>
        <td><select name='afg_per_page'>
            <?php echo generate_options($afg_per_page_map, 'afg_per_page'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Size of the photos</th>
        <td><select name='afg_photo_size'>
            <?php echo generate_options($afg_photo_size_map, 'afg_photo_size'); ?>
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
            <?php echo generate_options($afg_descr_map, 'afg_descr'); ?>
        </select></td>
        <td><font size='2'>Photo Description setting applies only to Small and Medium size photos. <font color='red'>WARNING:</font> Enabling descriptions for photos can significantly slow down loading of the gallery and hence is not recommended.</font></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>No of columns</th>
        <td><select name='afg_columns'>
            <?php echo generate_options($afg_columns_map, 'afg_columns'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th scope='row'>Background Color</th>
        <td><select name='afg_bg_color'>
            <?php echo generate_options($afg_bg_color_map, 'afg_bg_color'); ?>
        </select></td>
        </tr>

        <tr valign='top'>
        <th colspan='2'><font size='4' color='SteelBlue'><b><i>Support this plugin</i></b></font></th>
        </tr>

        <tr valign='top'>
        <th scope='row'>Add a small credit note about this plugin at the bottom?</th>
        <td style='vertical-align:middle'><input type='checkbox' name='afg_credit_note' value='Yes'
             <?php
                if (get_option('afg_credit_note') == 'Yes') {
                    echo 'checked=\'\'';
                }
             ?>/></td>
        <td><font size='2'>Credit Note will appear as - </font>
            Powered by
            <a href="http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin"/>
            Awesome Flickr Gallery</a></td>
        </tr>
    </table>

<p class="submit">
<input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
<?php if ($_POST) { ?>
<p bgcolor='cyan'><font size='3'>Settings updated successfully.</font></p>
<?php } ?>
</p>

</form>
<br />
<br />
<br />
<?php
$base_url = get_option('siteurl') . '/wp-content/plugins/awesome-flickr-gallery-plugin';
?>
It takes time and effort to keep releasing new versions of this plugin.  If you like it, consider donating a few bucks to keep receiving new features.
<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><div class="paypal-donations"><input type="hidden" name="cmd" value="_donations" /><input type="hidden" name="business" value="2P32M6V34HDCQ" /><input type="hidden" name="currency_code" value="USD" /><input type="image" src="<?php echo $base_url . "/images/donate_small.png"; ?>" name="submit" alt="PayPal - The safer, easier way to pay online." /><img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></div></form>

</div>
<?php
}
?>
