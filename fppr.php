<?php
/*
Plugin Name: WP Facebook Public Page RSS
Plugin URI: http://www.sideways8.com/plugins/
Description: A widget and a shortcode to show a public Facebook page feed in a sidebar widget or within a page.
Version: 1.0.2
Author: areimann, sideways8
Author URI: http://sideways8.com
License: GPL3

Copyright 2013 Aaron Reimann aaron.reimann@gmail.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 3, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('FPPR', __FILE__);

include_once( plugin_dir_path(FPPR).'fppr_class_widget.php' );
include_once( plugin_dir_path(FPPR).'fppr_common_function.php' );

// inits
function fppr_init()
{
	if (!is_admin()) {
		//wp_enqueue_script('jtweets', WP_PLUGIN_URL . '/wp-jfacebook-public-page-rss/js/wp-jfacebook-public-page-rss.js', array('jquery'));
	}
}
add_action('init', 'fppr_init');

function fppr_widget_init()
{
	register_widget("fpprWidget");
}
add_action('widgets_init', 'fppr_widget_init');


// adding style sheet for front end
function fppr_add_css()
{
	if (!is_admin())
	{
		wp_enqueue_style( 'fppr_fppr_stylesheets', plugins_url( '/style.css', __FILE__ ) );
	}
}
add_action('wp_print_styles', 'fppr_add_css');

// the code that spits out the widget
function fppr_echo( $facebook_id, $facebook_number, $excerpt_length, $avatar, $like, $timestamp, $remove_photos )
{
	include_once( ABSPATH.WPINC.'/rss.php' );
	
	if ( false === ( $feed = get_transient( 'fppr_feed-'.$facebook_id ) ) )
	{
		$feed = fetch_rss('https://www.facebook.com/feeds/page.php?id='.$facebook_id.'&format=rss20');
		set_transient( 'fppr_feed-'.$facebook_id, $feed, 300 );
	}

	// if there is an XML feed to pull, if user doesn't exist, no feed
	if ($feed) 
	{
		
		//$channel = $feed->channel;
		$items = array_slice($feed->items, 0, $facebook_number);

		echo '<div class="fppr-feed">';

			if ($avatar) {
				echo '<img src="http://graph.facebook.com/'.$facebook_id.'/picture?type=small" class="alignleft" />';
			}

			echo '<ul>';

			foreach ($items as $item) :
				$link = $item['link'];
				$title = $item['title'];
				$description = $item['description'];

				// adds alt because it makes more proper HTML
				$description = str_replace('alt="" /></a><br/><a','alt="" /></a><a',$description);

				// sets image to a var
				preg_match('#(<img.*?>)#', $description, $image);

				// splits the string because you don't want to diplay the second article of FB in the sidebar
				$split = explode('<br/><br/><a href=', $description);
				if ( $split[0] ) { $description = $split[0]; }

				// not sure what the limit is but 5,000 should cover it :) - default is set in the class
				if ( $excerpt_length < 5000 )
				{
					$description = wp_trim_words( $description, $excerpt_length, $more = null );
				}

				// check for url, if so, convert to link
				$regex_url = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
				if ( preg_match($regex_url, $description, $url) ) {
					$description = preg_replace($regex_url, '<a href="'.$url[0].'">'.$url[0].'</a> ', $description);
				}

				$fppr_date = fppr_twitter_formatter($item['pubdate']);

				echo '<li>';

					// if there is an image, and they don't have "Remove Photos" check..do this:
					if ( $image && !$remove_photos ) { echo $image[0]; }
					echo $description.' - <a href="'.$link.'" target="_blank">View on Facebook</a><br>';

					if ($timestamp) {
						echo ' <span>'.$fppr_date.'</span>';
					}

					if ($like) {
						// probably going to make these a lot of options
						// go back - make this a function
						echo '<iframe src="http://www.facebook.com/plugins/like.php?href=';
						echo $link;
						echo '&action=like';
						echo '&layout=standard';
						echo '&colorscheme=light';
						echo '&show_faces=false';
						echo '&height=30"';
						echo ' scrolling="no" frameborder="0" style="width: 100%; height: 29px; border: 0; overflow: hidden;" allowTransparency="true">';
						echo '</iframe>';
					}

				echo '</li>';
			endforeach;

			echo '</ul><!-- fppr-feed -->';

		echo '</div><!-- fppr-feed-container -->';

	} 
	else
	{
		echo '<div class="fppr-feed-container" data-rotatetime="2000"><ul class="fppr-feed"><li>No Facebook Feed to Display</li><li>Check Your Settings</li></ul></div>';
	}
}

// the code that echos out the shortcode
add_shortcode( 'wp_facebook_public_post_rss', 'fppr_shortcode' );
function fppr_shortcode( $atts )
{
	extract( shortcode_atts( array(
		'facebook_id' => 239209872277,
		'facebook_number' => 3,
		'avatar' => 'small', // small, normal, large, none
		'like' => 'true', // show like box
		'timestamp' => 'true', // show timestamp
	), $atts ) );
	
	include_once( ABSPATH.WPINC.'/rss.php' );

	if ( false === ( $feed = get_transient( 'fppr_feed-'.$facebook_id ) ) )
	{
		$feed = fetch_rss('https://www.facebook.com/feeds/page.php?id='.$facebook_id.'&format=rss20');
		set_transient( 'fppr_feed-'.$facebook_id, $feed, 300 );
	}

	$output = '';
	ob_start();

	// if there is an XML feed to pull, if user doesn't exist, no feed
	if ($feed) 
	{
		
		// $channel = $feed->channel;
		$items = array_slice($feed->items, 0, $facebook_number);

		echo '<div class="fppr-shortcode-container">';

			foreach ($items as $item) :
				$link = $item['link'];
				$description = $item['description'];

				// checking to see if the post also has a thumbnail of the article
				$description = fppr_check_for_fb_thumb( $description );

				// uses a WP function for cool twitter like timestamp
				$fppr_date = fppr_twitter_formatter($item['pubdate']);

				// returns two
				// $desc_array[0] is $description from function, original text before the split
				// $desc_array[1] is $fb_second_post_text from function, text before image array
				// $desc_array[2] is $images_container from function, images wrapped in a div
				$desc_array = fppr_parse_second_post( $description );
				$description = $desc_array[0];
				$fb_second_post_text = $desc_array[1];
				$images_container = $desc_array[2];

				echo '<p>';
					if ($avatar != 'none')
					{
						echo '<img src="http://graph.facebook.com/'.$facebook_id.'/picture?type='.$avatar.'" class="alignleft" />';
					}
					
					echo $description.' - <a href="'.$link.'" target="_blank">View on Facebook</a><br>';

					if ($images_container)
					{
						echo '<p>'.$fb_second_post_text.'</p>';
						foreach( $images_container as $img ) :
							echo $img;
						endforeach;
					}

					if ($timestamp) {
						echo ' <span>'.$fppr_date.'</span>';
					}

					if ($like) {
						// probably going to make these a lot of options
						// go back - make this a function
						echo '<iframe src="http://www.facebook.com/plugins/like.php?href=';
						echo $link;
						echo '&action=like';
						echo '&layout=standard';
						echo '&colorscheme=light';
						echo '&show_faces=false';
						echo '&height=30"';
						echo ' scrolling="no" frameborder="0" style="width: 100%; height: 29px; border: 0; overflow: hidden;" allowTransparency="true">';
						echo '</iframe>';
					}

				echo '</p>';
			endforeach;

		echo '</div><!-- fppr-shortcode-container -->';

	} 
	else
	{
		echo '<div class="fppr-feed-container"><p>No Facebook Feed to Display. Check Your Settings</p></div>';
	}

	return $output;
}

function fppr_check_for_fb_thumb( $description )
{

	// if this string exist, which means there are thumbs, add a <div>
	if ( strpos( $description, '<br/><br/><a href="') == true )
	{
		$description = str_replace( '<br/><br/><a href="', '<div class="fppr-thumb-article"><a href="', $description );
		$description = $description.'</div><!-- .fppr-thumb-article -->';
	}

	return $description;
}

function fppr_parse_second_post( $description )
{
	if ( strstr($description, '<div class="fppr-thumb-article">') )
	{
		$split = explode('<div class="fppr-thumb-article">', $description);
		$description = $split[0];
		$fb_second_post = $split[1];
		$fb_second_post = strip_tags($fb_second_post, '<img>');

		preg_match_all('/<img[^>]+>/i', $fb_second_post, $fb_second_post_images_array); 

		$fb_second_post_text = strip_tags($fb_second_post);

		$images_container = array();
		foreach($fb_second_post_images_array[0] as $img) :

			// grabs <img> and gets just the src="..."
			// then formats all of that
			if ( preg_match('/<img.+?src(?: )*=(?: )*[\'"](.*?)[\'"]/si', $img, $preg_results) )
			{
				$image_src = $preg_results[1];

				// grab the image's URL and replace s.jpg with n.jpg - n.jpg is the bigger image
				$image_link_src = substr($image_src, 0, -5);
				$image_link_src = $image_link_src.'n.jpg';

				$formatted_image = '<div class="fppr-thumb"';
				$formatted_image .= ' style="background: url(';
				$formatted_image .= $image_src;
				$formatted_image .= ') no-repeat center center;">';
				// some image src's have URL's like this:
				// https://fbexternal-a.akamaihd.net/safe_image.php?d=X111DFqNd3_ETWx-&w=130&h=130&url=http%3A%2F%2Fi3.ytimg.com%2Fvi%2FR0dmLeLCIqc%2Fmaxresdefault.jpg%3Ffeaturen.jpg
				// which doesn't translate well, so I don't want it to show in the lightbox
				if ( !strpos($image_link_src, 'url=http') )
				{
					$formatted_image .= '<a href="'.$image_link_src.'"';
					$formatted_image .= ' style="width: 100%; height: 100%; display: block"';
					$formatted_image .= ' rel="lightbox"';
					$formatted_image .= '></a>';
				}
				$formatted_image .= '</div>';
				array_push($images_container, $formatted_image);
			}
		endforeach;
		
		// adding a div to contain all of the images
		// trying to figure out if these should be part of the array
		array_unshift( $images_container, '<div class="fppr-image-container">' );
		array_push( $images_container, '</div><div style="clear: left"></div>' );
	}
	$desc_array = array();
	array_push( $desc_array, $description, $fb_second_post_text, $images_container );

	return ( $desc_array );
}

