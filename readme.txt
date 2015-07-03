=== Wp Google Map Short Code ===
Author URI: http://odrasoft.com
Plugin URI: http://odrasoft.com
Contributors: swadeshswain
Tags: Wp Google Map Short Code , Google Maps, Google Map, Short Code, swadesh swain
Requires at least: 3.0
Tested up to: 4.2
Stable Tag: 1.0


Adds  Google Maps using the  short code in your wordpress site.

== Description ==

This plugin will enable  short code that you can use  Google Maps in any WordPress post or page.
 

Maps are displayed with the below short code:

[google_map address="Elizabeth St Melbourne VIC 3000,
 Australia"  zoom="15" desc="Wp Google Map ShortCode" icon="http://google-maps-icons.googlecode.com/files/sailboat-tourism.png" ]
 
 <b>OR </b>
 
 
[google_map lat="36.7782610" long="-119.4179324"  zoom="15" desc="Wp Google Map ShortCode" icon="http://google-maps-icons.googlecode.com/files/sailboat-tourism.png"]


You can displayed Map using address or latitude and longitude.

*address = "add location name to display on map"
or
*lat = "add latitude"
*long = "add longitude"

desc = "add desc for tooltip on map"
icon = "add custom icon url for Google map marker otherwise it display default icon"






== Frequently Asked Questions ==

1. How Can I change the width or height of the map?

Yes, you can add width , hight to tha map 

[google_map lat="36.7782610" long="-119.4179324"  width="500px" height="500px"]

or 

[google_map address="Elizabeth St Melbourne VIC 3000, Australia" width="500px" height="500px"]



2. How Can I disable the map scrolling ?

Yes, you can add scrollwheel="false" to the short code.


[google_map lat="36.7782610" long="-119.4179324"  scrollwheel="false"]

or 

[google_map address="Elizabeth St Melbourne VIC 3000, Australia" scrollwheel="false"]

3. Can I disable the map controls?

Yes, you can add mapcontrols="true" to the short code.

[google_map lat="36.7782610" long="-119.4179324"  mapcontrols="false"]

or 

[google_map address="Elizabeth St Melbourne VIC 3000, Australia" mapcontrols="false"]

== Screenshots ==

1. screen 1
2. screen 2
3. screen 3
4. screen 4

== Installation ==

1. Upload and Activate the plugin
2. Added [google_map address="your address here"] to any post or page

== Changelog ==

= 1.0 =

* First release!