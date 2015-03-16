=== Blipfoto importer ===
Contributors: lumpysimon
Tags: blipfoto, blipfoto.com, polaroid, polaroidblipfoto.com, journal, photography, import
Requires at least: 4.1
Tested up to: 4.1.1
Stable tag: trunk

A WordPress plugin to import journal entries and photos from a [Blipfoto](https://www.polaroidblipfoto.com) daily photo journal into your WordPress website.

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

== Installation ==

1. Search for 'blipfoto importer' from the 'Add new plugin' page in your WordPress website.
2. Activate the plugin.
3. Follow the instructions on the [main plugin page](https://wordpress.org/plugins/blipfoto-importer)

== Changelog ==

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
