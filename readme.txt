=== Blipfoto importer ===
Contributors: lumpysimon
Tags: blipfoto, blipfoto.com, polaroid, polaroidblipfoto.com, journal, photography, import
Requires at least: 4.1
Tested up to: 4.2.2
Stable tag: trunk

Import journal entries and photos from a Blipfoto daily photo journal into your WordPress website.

== Description ==

[Blipfoto](https://www.polaroidblipfoto.com) is an online daily photo journal. Each day you can upload one photo and add some words. It is also an extremely friendly community where people comment on and rate each other's photos, choose favourite 'blips' (the informal name given to journal entries), follow journals, join groups and take part in discussions.

Blipfoto Importer lets you easily import all your photos and journal entries from Blipfoto into your WordPress website. Each imported entry will create a post on your website, with the image set to be the 'featured image'. You can choose the post status (published, draft, private, or pending review) and if you wish automatically insert the image into the post content.

= Instructions =

1. Go to the 'Blipfoto Importer' settings page (under the Settings menu).
2. In a new tab or window, go to the [Blipfoto apps page](https://www.polaroidblipfoto.com/developer/apps)
3. Click the 'Create a new app' button.
4. Enter your name.
5. Choose 'web application'.
6. Type anything in the description field.
7. Enter the address of your website in the 'Website' field.
8. Leave the 'Redirect URI' field blank.
9. Click the 'I agree' checkbox.
10. Copy the 'Client ID', 'Client secret' and 'Access token' into the relevant fields on the settings page on your WordPress website.
11. Set the other options as required.
12. To run the import, go to the 'Blipfoto Importer' page in the 'Tools' menu. The import is run in batches to prevent timeouts or exceeding the Blipfoto API rate limit. Just keep hitting the 'Go!' button until all your entries have been imported.
13. If you hit the Blipfoto API rate limit (about 500 entries), put your feet up with a nice cup of tea and try again in about 15 minutes.

= Important notes =

This is not an official Blipfoto plugin, it is developed and maintained by me in my spare time. I initially wrote it extremely quickly in response to uncertainty over the future of the Blipfoto website. Please treat it as very much a 'beta' product.

Feedback, feature requests and bug reports are all very welcome (please use the [support forum](https://wordpress.org/support/plugin/blipfoto-importer) or the [GitHub issues page](https://github.com/lumpysimon/blipfoto-wordpress-importer/issues).

I would strongly advise that you make a back up of your database before using this plugin.

There appears to be no way to retrieve original high resolution images from Blipfoto, so this plugin retrieves the 'standard' size image instead. This is still good enough for display on most websites, but it is not your original full size image that you uploaded to Blipfoto.

= Known bugs and future plans =

See the [GitHub issues](https://github.com/lumpysimon/blipfoto-wordpress-importer/issues).

== Installation ==

1. Search for 'blipfoto importer' from the 'Add new plugin' page in your WordPress website.
2. Activate the plugin.
3. Follow the instructions on the [main plugin page](https://wordpress.org/plugins/blipfoto-importer)

== Changelog ==

= 1.2 = (9th June 2015)
* Fix post date bug
* Set image title to match the entry title
* Tested with WordPress 4.2.2

= 1.1.3 = (18th March 2015)
* More readme changes

= 1.1.2 = (16th March 2015)
* Improved instructions

= 1.1.1 = (16th March 2015)
* Use 'post' method for form to avoid long URLs

= 1.1 = (16th March 2015)
* Make it compatible with PHP versions below 5.4
* Option to insert images into post content
* Various bug and typo fixes

= 1.0 = (16th March 2015)
* Initial release
