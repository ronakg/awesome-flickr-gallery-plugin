=== Awesome Flickr Gallery ===
Contributors: ronakg
Donate link: http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/
Tags: gallery, flickr, photo, image, slideshow, colorbox, portfolio
Requires at least: 2.5
Tested up to: 3.2-beta2
Stable tag: 2.6.5

Create a photo gallery of your Flickr photos on your WordPress enabled website
enabling you to customize it the way you like it.

== Description ==

*Awesome Flickr Gallery* is a simple, fast and light plugin to create a gallery
of your Flickr photos on your WordPress enabled website.  This plugin aims at
providing a simple yet customizable way to create stunning Flickr gallery.

**Features:**

* Fast and light
* Create multiple galleries with different parameters
* Select Photos from your Flickr Photostream, a Photoset or a Gallery
* Fits into a sidebar widget too
* View full size photos very quickly with easy navigation
* View full size photos with slideshow
* Fits automatically according to the width of the theme or you can select the width of the gallery yourself
* Select number of photos to display per page in the gallery
* Turn on/off photo titles
* Turn on/off photo descriptions and photo taken date
* Select number of columns to organize photos
* Select background color between White, Black or Transparent based on your theme
* Intuitive menu pages with easy navigation and photo previews

You can see a *live demo* of this plugin on my personal photography page -
[Photography | RonakG.in](http://www.ronakg.in/photography/)

Check out my home page to see a demo of how the Gallery fits into a sidebar widget - [RonakG.in | Live life to the fullest...](http://www.ronakg.in/)

**More Examples:**

* [Demo Page 1](http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-1/)
* [Demo Page 2](http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-2/)
* [Demo Page 3](http://www.ronakg.in/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-3/)

**Support:**

Can't get the plugin working?  Head to the discussion forum for solution -
[Discussions | RonakG.in](http://www.ronakg.in/discussions/)

== Installation ==

1. Extract the contents of the zip archive to the `/wp-content/plugins/` directory or install the plugin from your WordPress dashboard -> plugins -> add new menu
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Configure plugin using Awesome Flickr Gallery settings page
1. Place [AFG_gallery] in your posts and/or pages to show the default gallery or create new galleries with different settings and insert the generated code

== Frequently Asked Questions ==

= I have activated the plugin, but gallery doesn't load. =

Make sure your Flickr API key and Flickr User ID are correct.

= My Flickr API key and User ID are correct but the gallery doesn't load =

Make sure you add the shortcode [AFG_gallery] to your post or page where you
want to load the gallery.  This code is case-sensitive.

= When I click the photo, it doesn't open full size photo. =

Awesome Flickr Gallery uses *Colorbox* to display full size photos.  Most likey
you have another plugin enabled, which also uses the colorbox and is overriding
the Awesome Flickr Gallery settings.  It is recommended to deactivate any other
plugins that uses colorbox.

Also, some themes have built-in settings to display images using lightbox or
colorbox etc.  If your theme has such an option, turn it off.


Still can't get the plugin working?  Head to the discussion forum for solution -
[Discussions | RonakG.in](http://www.ronakg.in/discussions/)

== Upgrade Notice ==

= 2.6.5 =
- [Bug Fix] Awesome Flickr Gallery conflicts with other slider plugins
- Minor improvements in UI

= 2.6.2 =
- [Bug Fix] Pagination broken on multisite wordpress deployment
- [Bug Fix] Gallery doesn't load inside sidebar widget on some themes
- [Bug Fix] Pagination and Gallery Width options doesn't appear in "Default Settings for References" box on admin pages
- [Enhancement] Minor UI improvement for galleries displayed with Square and Thumbnail size photos

= 2.6.1 =
- Added a configuration option to control width of the gallery for better flexibility.
- Added a configuration option to enale/disable pagination.  This helps if you want to insert the gallery into a sidebar widget.

= 2.5.3 =
- [Bug Fix] When changes are submitted on Edit Galleries page, selected gallery would go back to default instead of last edited gallery.
- [Bug Fix] Credit Note would appear above the gallery if gallery has only 1 page
- [Bug Fix] Donate button link was broken, fixed it.  Now shower all the donations :).

= 2.5.1 =
- [Bug Fix] Extra line breaks were added by some themes.
- [Bug Fix] Do not show pagination bar if there is only one page.

= 2.5.0 =
- Now create multiple instances of your gallery with different settings.
- Select photo source from your Flickr photostream, photoset or galleries.
- New improved admin panel with slick menus and photo previews.

= 1.3.0 =
- Added SlideShow option when viewing full size photos
- Moved to colorbox from lightbox for displaying full size photos

= 1.1.7 =
- [Bug Fix] Moved to a better API to fetch data from Flickr.

= 1.1.6 =
- [Bug Fix] Compatibility issue with some browsers.

= 1.1.5 =
- Much better looking page navigation.  Check out the screenshot.

= 1.1.0 =
- Added page navigation feature in this release.  Now there is a page
  navigation links at the bottom of the gallery, so that users can go through
  all your photos.

== Screenshots ==

1. Default Settings Page
2. Add Gallery Page
3. Edit Galleries Page
4. Saved Galleries Page
5. Awesome Flickr Gallery with photos of size Small with Title and Description ON
6. Awesome Flickr Gallery with photos of size Square with Title and Description OFF
7. Full size photo view with SlideShow and navigation options to next and previous photos in gallery
8. Awesome Flickr Gallery with Small size photos and black background
9. Page navigation feature added in version 1.1.5

== Features Planned ==

Here's a list of planned features in upcoming releases of Awesome Flickr
Gallery (In no particular order):

* View EXIF data of the images
* View photos from Flickr Groups
* Add multiple User accounts to generate Galleries
* Exclude photos with specific tags
* View photo comments
* Comment on the photos from your website itself

== Changelog ==

= 2.6.5 =
* [Bug Fix] Awesome Flickr Gallery conflicts with other slider plugins
* Minor improvements in UI

= 2.6.2 =
* [Bug Fix] Pagination broken on multisite wordpress deployment
* [Bug Fix] Gallery doesn't load inside sidebar widget on some themes
* [Bug Fix] Pagination and Gallery Width options doesn't appear in "Default Settings for References" box on admin pages
* [Enhancement] Minor UI improvement for galleries displayed with Square and Thumbnail size photos

= 2.6.1 =
* Added a configuration option to control width of the gallery for better flexibility.
* Added a configuration option to enale/disable pagination.  This helps if you want to insert the gallery into a sidebar widget.
* Change slideshow behavior based on feedback.  Now it doesn't start automatically.

= 2.5.3 =
* [Bug Fix] When changes are submitted on Edit Galleries page, selected gallery would go back to default instead of last edited gallery.
* [Bug Fix] Credit Note would appear above the gallery if gallery has only 1 page
* [Bug Fix] Donate button link was broken, fixed it.  Now shower all the donations :).

= 2.5.1 =

* [Bug Fix] Extra line breaks were added by some themes.
* [Bug Fix] Do not show pagination bar if there is only one page.

= 2.5.0 =

* Create multiple instances of your gallery with different settings
* Select photo source from your Flickr photostream, photoset or galleries
* New improved admin panel with slick menus and photo previews
* Bug fixes

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
