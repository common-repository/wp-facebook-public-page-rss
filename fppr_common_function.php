<?php
/*
 * Standard functions Sideways8 uses in themes and plugins
 */

function fppr_twitter_formatter($date)
{
	$time = strftime("%s", strtotime($date));
	$twitter_time = human_time_diff($time, current_time('timestamp') ) . ' ago';
	return $twitter_time;
}

function fppr_strip_tags_keep_p( $string, $remove_breaks = false )
{
	$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string );
	$string = strip_tags($string, '<p>');

	if ( $remove_breaks )
		$string = preg_replace('/[\r\n\t ]+/', ' ', $string);

	return trim($string);
}

function fppr_trim_words( $text, $num_words = 55, $more = null, $keep_tags = false )
{
	if(null === $more)
		$more = __('&hellip;');
	$original_text = $text;
	if($keep_tags !== true)
		$text = fppr_strip_tags_keep_p($text);
	$words_array = preg_split("/[\n\r\t ]+/", $text, $num_words + 1, PREG_SPLIT_NO_EMPTY);
	if (count($words_array) > $num_words) {
		array_pop( $words_array );
		$text = implode(' ', $words_array);
		$text = fppr_balance_tags( $text );
		$text = $text . $more;
	} else {
		$text = implode(' ', $words_array);
	}
	return apply_filters('wp_trim_words', $text, $num_words, $more, $original_text);
}

function fppr_balance_tags( $text )
{
	$text = str_replace( '<p>', '&lt;p&gt;', $text );
	$text = str_replace( '</p>', '&lt;/p&gt;', $text );
	$start_pos = strrpos( $text, '<' );
	$end_pos = strrpos( $text, '>' );
	if ( $start_pos > $end_pos ) {
		$text = substr( $text, 0, $start_pos );
	}
	$text = str_replace( '&lt;p&gt;', '<p>', $text );
	$text = str_replace( '&lt;/p&gt;','</p>', $text );
	return $text;
}

