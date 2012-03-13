# Build a zip file for current version of the plugin. Takes version number as argument for zipfile name.
[ $# -eq 0 ] && { echo "Usage: $0 version_no"; exit 1; }
zip -r awesome-flickr-gallery-plugin-$1.zip . -x \*.git\* -x \*timthumb.txt\*
