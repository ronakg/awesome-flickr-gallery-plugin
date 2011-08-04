=== Awesome Flickr Gallery ===
Contributors: ronakg
Donate link: http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/
Tags: gallery, flickr, photo, image, slideshow, colorbox, portfolio, highslide
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 2.9.4

Create a photo gallery of your Flickr photos on your WordPress enabled website
enabling you to customize it the way you like it.

== Description ==

**Awesome Flickr Gallery** is a simple, fast and light plugin to create a
gallery of your Flickr photos on your WordPress enabled website.  This plugin
aims at providing a simple yet customizable way to create stunning Flickr
gallery.

**Features:**

* Fast and light - uses intelligent caching to load galleries instantly
* Create multiple galleries with different parameters
* Select Photos from your Flickr Photostream, a Photoset, a Gallery or a Group
* 2 Powerful slideshow options with thumbnail slider navigation
* Fits into a sidebar widget too
* Insert multiple galleries on same page with independent slideshow and pagination
* Fits automatically according to the width of the theme or you can select the width of the gallery yourself
* Ability to disable built-in slideshow so that you can use a slideshow plugin of your choice
* Intuitive menu pages with easy configuration options and photo previews

You can see a *live demo* of this plugin on my personal photography page -
[Photos | RonakG.com](http://www.ronakg.com/photos/)

Check out my home page to see a demo of how the Gallery fits into a sidebar widget - [RonakG.com | Live life to the fullest...](http://www.ronakg.com/)

**More Examples:**

* [Demo Page 1](http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-1/)
* [Demo Page 2](http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-2/)
* [Demo Page 3](http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/demo-page-3/)

**Support:**

Can't get the plugin working?  Head to the discussion forum for solution -
[Discussions | RonakG.com](http://www.ronakg.com/discussions/)

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

= I have created separate galleries with different photosets as Gallery Source, but all the galleries are using Photostream as source. =

This typically happens when you are using a plugin for editing your posts/pages. Try to remove the quotes from id parameter of the shortcode and it should work fine. For example, if the shortcode for your gallery is

[AFG_gallery id='1']

, use

[AFG_gallery id=1]

instead.

Also, some themes have built-in settings to display images using lightbox or colorbox etc. If your theme has such an option, turn it off.

= I made changes to my Flickr account but they don't reflect on my website. =

Awesome Flickr Gallery uses caching to avoid expensive calls to Flickr servers.  It intelligently figures out if cache needs to be updated or not.  However, sometimes it may not work as expected.  You should go to Default Settings and delete all cached data.

= I created a gallery with source as a Group.  In this gallery, only 500 photos are appearing. =

As Flickr Groups have thousands of photos, it becomes very expensive to fetch all the photos from Flickr.  Hence, Groups galleries are limited to latest 500 photos.

Still can't get the plugin working?  Head to the discussion forum for solution -
[Discussions | RonakG.com](http://www.ronakg.com/discussions/)

== Upgrade Notice ==

= 2.9.4 =
[ENHANCEMENT] Subtle mouse-over effect for gallery images
[PERFORMANCE] Large galleries load faster
[Bug Fix] Sidebar appears at bottom of the page with AFG activated

= 2.9.2 =
****** MUST UPGRADE ******. A NEW slideshow option now available which has a built in thumbnail slider for even easier navigation.

= 2.7.11 =
[ENHANCEMENT] Ability to create Gallery using any of your plublic Groups on Flickr.
[ENHANCEMENT] Ability to disable built-in slideshow so that you can use slideshow from any other plugin or theme of your choice
[ENHANCEMENT] Max photo per page limit extended to 999
[ENHANCEMENT] Max columns limit extended to 12
[Bug Fix] Improved stability

= 2.7.7 =
[ENHANCEMENT] Improved caching mechanism extended to descriptions too.  Now switching ON descriptions is NOT expensive.
[Bug Fix] This photo is not available on Flickr error fixed.  Intelligently identify low resolution photos and show a smaller version for slideshow.

= 2.7.5 =
Added caching mechanism to avoid expensive calls to Flickr servers.  This should improve page loading times a lot, especially for those with 500+ photos.

= 2.7.2 =
[Bug Fix] Can't see photos when there are more than 500 photos
[Enhancement] View full size photos in slideshow
[Enhancement] Minimum number of photos in gallery reduced to 4

= 2.7.0 =
Now slideshow displays all the pages in the gallery so that users can navigate through without having to visit all pages.  Support for multiple galleries with independent slideshows and pagination.
MUST UPGRADE for all who are facing conflict issue with other plugin or themes.

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
5. Advanced Settings Page
6. Awesome Flickr Gallery with photos of size Small with Title and Description ON
7. Awesome Flickr Gallery with photos of size Square with Title and Description OFF
8. Full size photo view with SlideShow along with thumbnail slider at the bottom
9. Awesome Flickr Gallery with Thumbnail size photos with white background

== Features Planned ==

Here's a list of planned features in upcoming releases of Awesome Flickr
Gallery (In no particular order):

* Add multiple User accounts to generate Galleries
* Generate a master gallery page with all your galleries linking to individual gallery pages
* View photo comments

== Changelog ==

= 2.9.4 =
* [Enhancement] Subtle mouse-over effect for gallery images
* [Performance] Large galleries load faster
* [Bug Fix] Sidebar appears at bottom of the page with AFG activated

= 2.9.2 =
* [Feature] A NEW slideshow option now available which has a built in thumbnail slider for even easier navigation.
* Minor bug fixes

= 2.7.11 =
* [Enhancement] Ability to create Gallery using any of your plublic Groups on Flickr.
* [Enhancement] Ability to disable built-in slideshow so that you can use slideshow from any other plugin or theme of your choice
* [Enhancement] Max photo per page limit extended to 999
* [Enhancement] Max columns limit extended to 12
* [Bug Fix] Improved stability

= 2.7.7 =
* [ENHANCEMENT] Improved caching mechanism extended to descriptions too.  Now switching ON descriptions is NOT expensive.
* [Bug Fix] This photo is not available on Flickr error fixed.  Intelligently identify low resolution photos and show a smaller version for slideshow.

= 2.7.5 =
* Added caching mechanism to avoid expensive calls to Flickr servers.  This should improve page loading times a lot, especially for those with 500+ photos.

= 2.7.0 =
* Now slideshow displays all the pages in the gallery so that users can navigate through without having to visit all pages.
* Support for multiple galleries with independent slideshows and pagination.
* MUST UPGRADE for all who are facing conflict issue with other plugin or themes. Everyone should be happy now.

= 2.6.1 =
* Added a configuration option to control width of the gallery for better flexibility.
* Added a configuration option to enale/disable pagination.  This helps if you want to insert the gallery into a sidebar widget.
* Change slideshow behavior based on feedback.  Now it doesn't start automatically.

= 2.5.0 =
* Create multiple instances of your gallery with different settings
* Select photo source from your Flickr photostream, photoset or galleries
* New improved admin panel with slick menus and photo previews
* Bug fixes

= 1.3.0 =
* Added SlideShow option when viewing full size photos
* Moved to colorbox from lightbox for displaying full size photos

= 1.1.0 =
* Added page navigation to the gallery.  Now max photos setting refers to max photos per page.
* Better exception handling
* Minor bug fixes

= 1.0.0 =
* Release
