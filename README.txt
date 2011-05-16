=== Awesome Flickr Gallery ===
Contributors: ronakg
Donate link: http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/
Tags: gallery, flickr, photo, image, slideshow, colorbox, portfolio
Requires at least: 3.0
Tested up to: 3.1.2
Stable tag: 1.3.0

Create a photo gallery of your Flickr photos on your WordPress enabled website
enabling you to customize it the way you like it.

== Description ==

*Awesome Flickr Gallery* is a simple, fast and light plugin to create a gallery
of your Flickr photos on your WordPress enabled website.  This plugin aims at
providing a simple yet customizable way to create stunning Flickr gallery.

**Features:**

* Fast and light
* View full size photos very quickly with easy navigation
* View full size photos with slideshow
* Fits automatically according to the width of the theme
* Select number of photos to display per page in the gallery
* Turn on/off photo titles
* Turn on/off photo descriptions and photo taken date
* Select number of columns to organize photos
* Select background color between White, Black or Transparent based on your theme

You can see a *live demo* of this plugin on my personal photography page -
[Photography | RonakG.in](http://www.ronakg.in/photography/)

== Installation ==

1. Extract the contents of the zip archive to the `/wp-content/plugins/`
directory or install the plugin from your WordPress dashboard -> plugins -> add
new menu
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure plugin using Awesome Flickr Gallery settings page
1. Place [AFG_gallery] in your posts and/or pages to show the gallery

== Frequently Asked Questions ==

= I have activated the plugin, but gallery doesn't load. =

Make sure your Flickr API key and Flickr User ID are correct.

= My Flickr API key and User ID are correct but the gallery doesn't load =

Make sure you add the shortcode [AFG_gallery] to your post or page where you
want to load the gallery.  This code is case-sensitive.

= When I click the photo, it doesn't open full size photo. =

Awesome Flickr Gallery uses *Lightbox* to display full size photos.  Most likey
you have another plugin enabled, which also uses the lightbox and is overriding
the Awesome Flickr Gallery settings.  It is recommended to deactivate any other
plugins that uses Lightbox.

== Upgrade Notice ==

= 1.3.0 =
* Added SlideShow option when viewing full size photos
* Moved to colorbox from lightbox for displaying full size photos

= 1.1.7 =
* [Bug Fix] Moved to a better API to fetch data from Flickr.

= 1.1.6 =
* [Bug Fix] Compatibility issue with some browsers.

= 1.1.5 =
* Much better looking page navigation.  Check out the screenshot.

= 1.1.0 =
* Added page navigation feature in this release.  Now there is a page
  navigation links at the bottom of the gallery, so that users can go through
  all your photos.

== Screenshots ==

1. Awesome Flickr Gallery Settings page
2. Awesome Flickr Gallery with photos of size Small with Title and Description ON
3. Awesome Flickr Gallery with photos of size Square with Title and Description OFF
4. Full size photo view with SlideShow and navigation options to next and previous photos in gallery
5. Awesome Flickr Gallery with Small size photos and black background
6. Page navigation feature added in version 1.1.5

== Features Planned ==

Here's a list of planned features in upcoming releases of Awesome Flickr
Gallery (In no particular order):

* View EXIF data of the images
* Display images from specific photosets only
* Exclude photos with specific tags
* View photo comments
* Comment on the photos from your website itself

== Changelog ==

= 1.3.0 =
* Added SlideShow option when viewing full size photos
* Moved to colorbox from lightbox for displaying full size photos

= 1.1.7 =
* [Bug Fix] Moved to a better API to fetch data from Flickr.

= 1.1.6 =
* [Bug Fix] Compatibility issue with some browsers.

= 1.1.5 =
* Added much better looking page navigation.

= 1.1.0 =
* Added page navigation to the gallery.  Now max photos setting refers to max photos per page.
* Better exception handling
* Minor bug fixes

= 1.0.1 =
* [Bug Fix] Clicking the photos wouldn't open full size photo in lightbox.

= 1.0.0 =
* Release
