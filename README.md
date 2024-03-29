# Street Art Aberdeen
A gallery and map of street art in Aberdeen, Scotland, built with Joomla, online at https://streetartaberdeen.org/


## Screen grabs
They'll probably be out of date shortly, but a picture tells a 1000 words I guess.
![Map View](https://raw.githubusercontent.com/SSOFB/StreetArtAberdeen/main/graphics/StreetArtAberdeen_map.png) ![Gallery View](https://raw.githubusercontent.com/SSOFB/StreetArtAberdeen/main/graphics/StreetArtAberdeen_gallery.png)


## To-do
Moved into issues, see https://github.com/SSOFB/StreetArtAberdeen/issues


## Project links
* Git repo - https://github.com/SSOFB/StreetArtAberdeen
* Domains: https://streetartaberdeen.org, https://streetartaberdeen.org.uk, https://streetartaberdeen.com and https://streetartaberdeen.co.uk


## Website tech links
* https://www.creativebloq.com/typography/free-graffiti-fonts-11121160
* https://www.richeyweb.com/software/joomla/plugins/125-free-field-plugins
* https://coolcat-creations.com/en/blog/tutorial-build-your-own-custom-field-plugin / https://github.com/coolcat-creations/plg_fields_owlimg
* https://joomla.stackexchange.com/questions/31787/creating-a-field-plug-in-need-the-com-content-form-to-be-enctype-multipart
* https://joomla.stackexchange.com/questions/26322/how-to-programmatically-set-the-value-of-a-custom-field-of-type-checkbox
* https://developers.google.com/maps/documentation/geocoding/requests-reverse-geocoding
* https://developers.google.com/maps/documentation/javascript/geolocation
* https://developers.google.com/maps/documentation/javascript/controls
* https://developers.google.com/maps/documentation/javascript/examples/control-custom
* https://magazine.joomla.org/all-issues/may-2021/explore-the-core-play-with-custom-fields-to-enrich-your-content-or-your-design
* https://getbootstrap.com/docs/5.0/getting-started/introduction/
* https://visme.co/blog/website-color-schemes/
* https://slides.woluweb.be/cassiopeia/cassiopeia.html
* https://joomla.stackexchange.com/questions/31868/
* https://socialsharepreview.com/?url=https://streetartaberdeen.org/
* https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/markup & https://cards-dev.twitter.com/validator


## Useful Joomla! docs
* https://docs.joomla.org/File_form_field_type
* https://docs.joomla.org/How_to_use_the_filesystem_package
* https://docs.joomla.org/Adding_custom_fields_to_core_components_using_a_plugin
* https://docs.joomla.org/Basic_form_guide
* https://docs.joomla.org/Advanced_form_guide
* https://docs.joomla.org/Retrieving_request_data_using_JInput
* https://docs.joomla.org/Creating_a_custom_form_field_type
* https://docs.joomla.org/Subform_form_field_type
* https://docs.joomla.org/J4.x:Writing_A_CLI_Application
* https://docs.joomla.org/J4_CLI_example_-_Onoffbydate
* https://docs.joomla.org/JDOC:Joomla_4_Tutorials_Project
* https://docs.joomla.org/Using_own_library_in_your_extensions
* https://docs.joomla.org/J3.x:Access_Control_List_Tutorial


## Street Art Links
* https://www.visitabdn.com/listing/nuart-aberdeen
* https://www.aberdeeninspired.com/festival/nuart-aberdeen/nuart-aberdeen-walking-tours
* https://2021.nuartaberdeen.co.uk/
* https://twitter.com/nuartaberdeen
* https://www.wallspot.org/en/wall/sunnybank-park
* https://www.wallspot.org/en/wall/mounthooly-roundabout-underpasses
* https://twitter.com/streetartabdn
* https://inspiringcity.com/2018/04/13/where-to-find-street-art-in-aberdeen/
* https://inspiringcity.com/2018/04/15/the-murals-of-the-2018-nuart-aberdeen-street-art-festival/
* https://inspiringcity.com/2019/04/20/the-street-art-of-the-2019-aberdeen-nuart-festival/
* https://inspiringcity.com/2021/08/08/nuart-murals-in-aberdeen-for-2021-street-art-festival/
* https://www.instagram.com/nogreywalls/
* https://www.instagram.com/nuartaberdeen/
* https://www.google.com/maps/d/viewer?mid=1ug4n2zC_PzJG-VLCGh97tHRP5X5rbhqG


## Fonts
* Kneewave - https://www.theleagueofmoveabletype.com/knewave
* Philly Sans - https://allfont.net/download/philly-sans/
* Don Graffiti - https://www.dafont.com/don-graffiti.font
* Rock Salt - https://fonts.google.com/specimen/Rock+Salt?query=rock+#standard-styles
* Mansalva - https://fonts.google.com/specimen/Mansalva?query=Mansalva
* Sarina - https://fonts.google.com/specimen/Sarina?query=Sarina


## Git cheat-sheet
* Get started `git clone git@github.com:SSOFB/StreetArtAberdeen.git`
* Get updates from main `git fetch origin` then `git pull`
* Commit changes `git commit -m "all fixed"` or `git commit -a -m "all fixed"`
* Add a file `git add blah`
* Send your changes to the main `git push origin main`
* Set git to ignore file permission changes `git config core.fileMode false`


## DNS and hosting
178.79.157.89
* https://dcc.godaddy.com/manage/streetartaberdeen.org/dns?plid=1
* https://dcc.godaddy.com/manage/streetartaberdeen.org.uk/dns?plid=1
* https://dcc.godaddy.com/manage/streetartaberdeen.com/dns?plid=1
* https://dcc.godaddy.com/manage/streetartaberdeen.co.uk/dns?plid=1



## Handy SQL

Change a field value...
```
UPDATE `s3ib7_fields_values` SET `value`='Sculpture' WHERE `value`='3D' AND `field_id`=1;

UPDATE `s3ib7_fields_values` SET `value`='Mosaic' WHERE `value`='Sculpture' AND `field_id`=1 AND `item_id` IN(832,475,687,474,536,710,476,690,151,860,303);

UPDATE `s3ib7_fields_values` SET `value`='Spray' WHERE `value`='Unknown' AND `field_id`=1;
```


## Handy Video snippets


Create a video stretching the image to fit, 5fps...
```
ffmpeg -framerate 5 -pattern_type glob -i "large*.jpeg" -vf "pad=ceil(iw/2)*2:ceil(ih/2)*2"  movie01.mp4
```

Create a video, keeping the image aspect ratio, 10fps...
```
ffmpeg -framerate 10 -pattern_type glob -i "large*.jpeg" -vf "scale=1280:720:force_original_aspect_ratio=decrease,pad=1280:720:(ow-iw)/2:(oh-ih)/2"  movie03.mp4
```
