<?
/*
Plugin Name: zAccordion WordPress-ed
Description: Implementation of zAccordion
Version: 1.0
Author: Brad Parbs; OnYourMark, LLC
Author URI: http://onyourmark.com
License: GPL2
*/

function zaccordionSetup(){

wp_enqueue_script("jquery");

wp_enqueue_script(
  "jquery.zaccordion", WP_PLUGIN_URL."/zAccordion/js/jquery.zaccordion.min.js", 
  array("jquery"), "",1);	

wp_enqueue_script(
  "jquery.easing", WP_PLUGIN_URL."/zAccordion/js/jquery.easing.1.3.js", 
  array("jquery","jquery.zaccordion"), "",1);
}

add_action('wp_footer', 'zaccordionSetup',5); 

function jQueryFun(){

wp_enqueue_script(
  "slider.js", WP_PLUGIN_URL."/zAccordion/js/slider.js", 
  array("jquery","jquery.zaccordion"), "",1);

}

add_action('wp_head','jQueryFun',6);


