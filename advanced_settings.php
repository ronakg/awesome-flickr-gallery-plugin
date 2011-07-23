<?php
include_once('afg_libs.php');

if ($_POST) {
    update_option('afg_disable_slideshow', $_POST['afg_disable_slideshow']);
}

function afg_advanced_settings_page() {
    $url=$_SERVER['REQUEST_URI'];
?>
<div class='wrap'>
<h2><a href='http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/'><img src="<?php
echo (BASE_URL . '/images/logo_big.png'); ?>" align='center'/></a>Advanced Settings | Awesome Flickr Gallery</h2>

<form method='post' action='<?php echo $url ?>'>
<?php echo afg_generate_version_line() ?>
<div class="postbox-container" style="width:70%;">
    <div id="poststuff">
    <div class="postbox">
        <h3>Advanced Settings</h3>
        <table class='form-table'>
            <tr valign='top'>
            <th scope='row'>Disable Slideshow</th>
            <td><input type='checkbox' name='afg_disable_slideshow' id='afg_disable_slideshow' value='yes' <?php 
    if (get_option('afg_disable_slideshow')) echo 'checked=\'\'';
?>
></td>
            <td><font size='2'>Disabling slideshow will remove the slideshow built into the Awesome Flickr Gallery.  Use this option if you want to use a different slideshow (probably from your theme or any other plugin).</font></td>
        </table>
        </div>
<input type="submit" name="submit" id="afg_save_changes" class="button-primary" value="Save Changes" />
</div>
</div>

<div class="postbox-container" style="width: 29%;">
<?php
    $message = "<b><font style='color:red'>Settings on this page apply to all your Galleries and can disrupt your existing Galleries.  Change these settings only if you know what you are doing.</font></b>";
    echo afg_box('WARNING', $message);

?>
<?php echo afg_donate_box() ?>
</div>
</form>

<?php
}
