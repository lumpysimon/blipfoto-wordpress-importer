=== Blipfoto importer ===
Contributors: lumpysimon
Tags: blipfoto, blipfoto.com, polaroid, polaroidblipfoto.com, journal, photography, import
Requires at least: 4.1
Tested up to: 4.1.1
Stable tag: trunk

A WordPress plugin to import journal entries and photos from a [Blipfoto](polaroidblipfoto.com) daily photo journal into your WordPress website.

== Description ==

[Blipfoto](https://www.polaroidblipfoto.com) is an online daily photo journal. Each day you can upload one photo and add some words. It is also a very friendly community where people comment on and rate each other's photos, choose favourite 'blips' (the informal name given to journal entries), follow journals, join groups and take part in discussions.

Blipfoto Importer lets you easily import all your photos and journal entries from Blipfoto into your WordPress website.

== Installation ==

1. Search for 'blipfoto importer' from the 'Add new plugin' page in your WordPress website.
2. Activate the plugin.
3. Go to the 'Blipfoto importer' settings page (under the Settings menu).
4. In a new tab or window, go to https://www.polaroidblipfoto.com/developer/apps
5. Click the 'Create a new app' button.
6. Enter your name.
7. Choose 'web application'.
8. Type anything in the description field.
9. Enter the address of your website in the 'Website' field.
10. Leave the 'redirect URI' field blank.
11. Click the 'I agree' checkbox.
12. Copy the 'Client ID', 'Client secret' and 'Access token' into the relevant fields on the settings page on your WordPress website.
13. To run the import, go to the 'Blipfoto Importer' page in the 'Tools' menu. The import is run in batches to prevent timeouts or exceeding the Blipfoto API rate limit. Just keep hitting the 'Go!' button until all your entries have been imported.

== Changelog ==

= 1.0 = (16th March 2015)
* Initial release
