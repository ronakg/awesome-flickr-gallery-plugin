# Awesome Flickr Gallery #

_Awesome Flickr Gallery_ is a simple, fast and light plugin to create a gallery of your Flickr photos on your WordPress enabled website.  This plugin aims at providing a simple yet customizable way to create clean and professional looking Flickr galleries.

##Features:

* Fast and light - uses intelligent caching to load galleries instantly
* Support for both Public and Private photos
* Create multiple galleries with different parameters
* Two powerful slideshow options in Colorbox and HighSlide
* Select Photos from your Flickr Photostream, a Photoset, a Gallery, a Group or a set of tags
* Multiple sorting options available so that you don't have to rely on Flickr's sorting options
* Customizable image sizes with cropping settings
* Infinitely customizable with custom CSS field
* Fits into a sidebar widget too
* Insert multiple galleries on same page with independent slideshow and pagination
* Fits automatically according to the width of the theme or you can select the width of the gallery yourself
* Ability to disable built-in slideshow so that you can use a slideshow plugin of your choice
* Intuitive menu pages with easy configuration options and photo previews
* SEO friendly, all your photos are available to search engine crawlers

You can see a live demo of this plugin on my personal photography page - [Photos | RonakG.com](http://www.ronakg.com/photos/)

###More Examples:

* [Awesome Flickr Gallery Demo Page | RonakG.com](http://www.ronakg.com/projects/awesome-flickr-gallery-wordpress-plugin/demo-page/)

###Support:

Can't get the plugin working?  Head to the discussion forum for solution - [Discussions | RonakG.com](http://www.ronakg.com/discussions/)

##Installation:

- Extract the contents of the zip archive to the `/wp-content/plugins/` directory or install the plugin from your WordPress dashboard -> plugins -> add new menu
- Activate the plugin through the 'Plugins' menu in WordPress
- Configure plugin using Awesome Flickr Gallery settings page
- Place `[AFG_gallery]` in your posts and/or pages to show the default gallery or create new galleries with different settings and insert the generated code

##Screenshots:

![Default Settings Page](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-4.jpeg "Default Settings Page")
![Add Gallery Page](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-5.jpeg "Add Gallery Page")
![Saved Galleries Page](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-7.jpeg "Saved Galleries Page")
![Advanced Settings Page](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-8.jpeg "Advanced Settings Page")
![Awesome Flickr Gallery with Thumbnail size photos with white background](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-1.png "Awesome Flickr Gallery with Thumbnail size photos with white background")
![Awesome Flickr Gallery with photos of size Square with Title and Description OFF](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-2.png "Awesome Flickr Gallery with photos of size Square with Title and Description OFF")
![Awesome Flickr Gallery with photos of size Small with Title and Description ON](http://ps.w.org/awesome-flickr-gallery-plugin/assets/screenshot-3.png "Awesome Flickr Gallery with photos of size Small with Title and Description ON")

##Frequently Asked Questions:

#### After upgrade to version 3.0.0, my photo descriptions appear as "array". ####

> Just delete the cached gallery data from "Default Settings" page of the plugin and also delete any cached pages from your caching plugins (like mentioned below).

#### After upgrade, only one column appears in the gallery. ####

> This happens when you have a cache plugin (like WP Super Cache or W3 All Cache) installed. Old cached CSS file is loaded instead of the new one. Just delete the cached pages from your cache plugin and refresh the gallery page 2-3 times, it will appear fine.

#### I have activated the plugin, but gallery doesn't load. ####

> Make sure your Flickr API key and Flickr User ID are correct.

#### My Flickr API key and User ID are correct but the gallery doesn't load ####

> Make sure you add the shortcode `[AFG_gallery]` to your post or page where you want to load the gallery.  This code is case-sensitive.

#### When I click the photo, it doesn't open full size photo. ####

> Awesome Flickr Gallery uses *Colorbox* to display full size photos.  Most likey you have another plugin enabled, which also uses the colorbox and is overriding the Awesome Flickr Gallery settings.  It is recommended to deactivate any other plugins that uses colorbox.

> Also, some themes have built-in settings to display images using lightbox or colorbox etc.  If your theme has such an option, turn it off.

#### I have created separate galleries with different photosets as Gallery Source, but all the galleries are using Photostream as source. ####

> This typically happens when you are using a plugin for editing your posts/pages. Try to remove the quotes from id parameter of the shortcode and it should work fine. For example, if the shortcode for your gallery is `[AFG_gallery id='1']`, use `[AFG_gallery id=1]` instead.

> Also, some themes have built-in settings to display images using lightbox or colorbox etc. If your theme has such an option, turn it off.

#### I made changes to my Flickr account but they don't reflect on my website. ####

> Awesome Flickr Gallery uses caching to avoid expensive calls to Flickr servers.  It intelligently figures out if cache needs to be updated or not.  However, sometimes it may not work as expected.  You should go to Default Settings and delete all cached data.

#### I created a gallery with source as a Group.  In this gallery, only 500 photos are appearing. ####

> As Flickr Groups have thousands of photos, it becomes very expensive to fetch all the photos from Flickr.  Hence, Groups galleries are limited to latest 500 photos.

##User Testimonials:

* Awesome Flickr Gallery plugin is legend by the way - thanks for such a fantastic tool - LeonsLens
* This remains the best way to set up flickr galleries! – forpetessake93
* The Tag feature works great! I have been waiting for a plugin like this. I love your plugin. - ryangirtler
* The name of AFG says it all. I really like the way you’ve taken the time to make a UI for creating & editing galleries. – chassy
* this plugin is AMAZING!! Thank you – Yardena
* The gallery looks exceptionally clean and professional. – sherrieJD
* Awesome plugin! Awesome support! – zumine
* Super. It is a pleasure to work with you. Very professional and highly reactive. – fibonaccifactory
* Awesome. You rock. – VisionsInEd
* Thanks Ronak. It works beautifully. Awesome app you have built here. – miracleboy31
* You rock so much. Such a quick response time. Your plugin is SO SO SO much better than other flickr plugins. – AutoEntropy
* this gallery plugin is excellent I really like it and working with Flickr and WordPress makes my workflow much easier. – AtlantisWeb
* I have to say that I am loving what I am seeing. I set up a gallery and it looks great.  – svogt
* MANY thanks for your awesome Awesome Flickr Gallery! Works like a charm. -  Jessin
* i prefer awesome flickr gallery (i think is smoother and faster than slickr flickr) – apocalipsis1234
* your Awesome Flickr Gallery is one of the best plugins out there!! – RichardF
