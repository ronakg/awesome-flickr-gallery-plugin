mjson = jQuery.noConflict();

mjson(document).ready(function(){
    loadGallerySettings();
    });

function getPhotoSourceType() {
    var source_element = document.getElementById('afg_photo_source_type');
    var photosets_box = document.getElementById('afg_photosets_box');
    var galleries_box = document.getElementById('afg_galleries_box');
    var source_label = document.getElementById('afg_photo_source_label');

    if (source_element.value == 'photostream') {
        source_label.style.display = 'none';
        photosets_box.style.display = 'none';
        galleries_box.style.display = 'none';
    }
    else if (source_element.value == 'gallery') {
        if (!galleries_box.value) {
            alert('You have no galleries associated with your Flickr account.');
            source_element.value = 'photostream';
            source_label.style.display = 'none';
            photosets_box.style.display = 'none';
            galleries_box.style.display = 'none';
            return;
        }
        source_label.style.display = 'block';
        galleries_box.style.display = 'block';
        photosets_box.style.display = 'none';
        source_label.innerHTML = "Select Gallery";
    }
    else {
        if (!photosets_box.value) {
            alert('You have no photosets associated with your Flickr account.');
            source_element.value = 'photostream';
            source_label.style.display = 'none';
            photosets_box.style.display = 'none';
            galleries_box.style.display = 'none';
            return;
        }
        source_label.style.display = 'block';
        photosets_box.style.display = 'block';
        galleries_box.style.display = 'none';
        source_label.innerHTML = "Select Photoset";
    }
}

function verifyAddBlank() {
    var gname = document.getElementById("afg_add_gallery_name");
    var submit_button = document.getElementById('afg_save_changes');
    if (gname.value == "") {
        alert('Gallery Name can not be blank.');
        submit_button.disabled = true;
        gname.focus();
        return;
    }
    submit_button.disabled = false;
}

function verifyEditBlank() {
    var gname = document.getElementById("afg_edit_gallery_name");
    var submit_button = document.getElementById('afg_save_changes');
    if (gname.value == "") {
        alert('Gallery Name can not be blank.');
        submit_button.disabled = true;
        gname.focus();
        return;
    }
    submit_button.disabled = false;
}
function loadGallerySettings() {
    var gallery = document.getElementById('afg_photo_gallery');
    var gallery_name = document.getElementById('afg_edit_gallery_name');
    var gallery_descr = document.getElementById('afg_edit_gallery_descr');
    var photosets_box = document.getElementById('afg_photosets_box');
    var galleries_box = document.getElementById('afg_galleries_box');
    var source_label = document.getElementById('afg_source_label');
    var per_page = document.getElementById('afg_per_page');
    var photo_size = document.getElementById('afg_photo_size');
    var captions = document.getElementById('afg_captions');
    var descr = document.getElementById('afg_descr');
    var columns = document.getElementById('afg_columns');
    var credit_note = document.getElementById('afg_credit_note');
    var bg_color = document.getElementById('afg_bg_color');
    var width = document.getElementById('afg_width');
    var pagination = document.getElementById('afg_pagination');
    var gallery_code = document.getElementById('afg_flickr_gallery_code');

    var galleries = genparams.galleries.replace(/&quot;/g, '"');
    var jgalleries = jQuery.parseJSON(galleries);
    active_gallery = jgalleries[gallery.value];


    source_element = document.getElementById('afg_photo_source_type');
    source_element.value = active_gallery.photo_source;

    gallery_name.value = active_gallery.name;
    gallery_descr.value = active_gallery.gallery_descr;
    per_page.value = active_gallery.per_page || 'default';
    photo_size.value = active_gallery.photo_size || 'default';
    captions.value = active_gallery.captions || 'default';
    descr.value = active_gallery.descr || 'default';
    columns.value = active_gallery.columns || 'default';
    bg_color.value = active_gallery.bg_color || 'default';
    width.value = active_gallery.width || 'default';
    pagination.value = active_gallery.pagination || 'default';
    credit_note.value = active_gallery.credit_note || 'default';
    gallery_code.innerHTML = '[AFG_gallery id=\'' + gallery.value + '\']';

    getPhotoSourceType();

    if (source_element.value == 'photoset') {
        photosets_box.value = active_gallery.photoset_id;
        galleries_box.value = NULL;
    }
    if (source_element.value == 'gallery') {
        galleries_box.value = active_gallery.gallery_id;
        photosets_box.value = NULL;
    }
}
