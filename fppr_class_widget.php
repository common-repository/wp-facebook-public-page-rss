<?php
class fpprWidget extends WP_Widget
{
	
	function fpprWidget()
	{
		$widget_options = array(
			'classname'		=>	'fppr-widget',
			'description'	=>	'A widget to show a public Facebook page RSS feed'
		);

		parent::WP_Widget(false, 'WP Facebook Public Page RSS', $widget_options);
	}
	
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP );
		$title = ($instance['title']) ? $instance['title'] : 'Facebook Public Page RSS';
		$facebook_id = ($instance['facebook_id']) ? $instance['facebook_id'] : 'No Facebook ID Number Entered';
		$excerpt_length = ($instance['excerpt_length']) ? $instance['excerpt_length'] : '50';
		$facebook_number = ($instance['facebook_number']) ? $instance['facebook_number'] : '3';
		$avatar = ($instance['avatar']) ? $instance['avatar'] : '0';
		$like = ($instance['like']) ? $instance['like'] : '0';
		$timestamp = ($instance['timestamp']) ? $instance['timestamp'] : '0';
		$remove_photos = ($instance['remove_photos']) ? $instance['remove_photos'] : '0';

		echo $before_widget;
		echo $before_title . $title . $after_title;

		if ($facebook_id != 'No Facebook ID') {
			fppr_echo( $facebook_id, $facebook_number, $excerpt_length, $avatar, $like, $timestamp, $remove_photos );
		}
		echo $after_widget;
	}

    function update($new_instance, $instance) {

		$instance = array();

		$instance['title'] = strip_tags(trim($new_instance['title']));
		$instance['facebook_id'] = strip_tags($new_instance['facebook_id']);
		$instance['excerpt_length'] = strip_tags($new_instance['excerpt_length']);
		$instance['facebook_number'] = strip_tags($new_instance['facebook_number']);
		$instance['avatar'] = absint(strip_tags($new_instance['avatar']));
		$instance['like'] = absint(strip_tags($new_instance['like']));
		$instance['timestamp'] = absint(strip_tags($new_instance['timestamp']));
		$instance['remove_photos'] = absint(strip_tags($new_instance['remove_photos']));

		return $instance;
	}

	function form($instance)
	{
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$facebook_id = isset( $instance['facebook_id'] ) ? esc_attr( $instance['facebook_id'] ) : '';
		$facebook_number = isset( $instance['facebook_number'] ) ? esc_attr( $instance['facebook_number'] ) : '';
		$excerpt_length = isset( $instance['excerpt_length'] ) ? esc_attr( $instance['excerpt_length'] ) : '';
		$avatar = isset( $instance['avatar'] ) ? esc_attr( $instance['avatar'] ) : '';
		$like = isset( $instance['like'] ) ? esc_attr( $instance['like'] ) : '';
		$timestamp = isset( $instance['timestamp'] ) ? esc_attr( $instance['timestamp'] ) : '';
		$remove_photos = isset( $instance['remove_photos'] ) ? esc_attr( $instance['remove_photos'] ) : '';
		?>

		<label for="<?php echo $this->get_field_id('title');?>">
			Title:<br />
			<input
				id="<?php echo $this->get_field_id('title');?>"
				name="<?php echo $this->get_field_name('title');?>"
				value="<?php echo $title ?>"
			/><br />
		</label>
		
		<label for="<?php echo $this->get_field_id('facebook_id');?>">
			Facebook Page ID (<a href="http://wordpress.org/plugins/wp-facebook-public-page-rss/faq/">(instructions)</a>:<br />
			<input
				id="<?php echo $this->get_field_id('facebook_id');?>"
				name="<?php echo $this->get_field_name('facebook_id');?>"
				value="<?php echo $facebook_id; ?>"
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('facebook_number');?>">
			Number of Posts:<br />
			<input
				id="<?php echo $this->get_field_id('facebook_number');?>"
				name="<?php echo $this->get_field_name('facebook_number');?>"
				value="<?php echo $facebook_number; ?>"
				size="2" maxlength="2"
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('excerpt_length');?>">
			Shorten post to this number of words:<br />
			<input
				id="<?php echo $this->get_field_id('excerpt_length');?>"
				name="<?php echo $this->get_field_name('excerpt_length');?>"
				value="<?php echo $excerpt_length; ?>"
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('avatar');?>">
			Show Avatar 
			<input
				type="checkbox"
				id="<?php echo $this->get_field_id('avatar');?>"
				name="<?php echo $this->get_field_name('avatar');?>"
				value="1"
				<?php if ( $avatar == TRUE) { print 'checked="yes" '; } ?>
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('like');?>">
			Show Like Box On Each Post
			<input
				type="checkbox"
				id="<?php echo $this->get_field_id('like');?>"
				name="<?php echo $this->get_field_name('like');?>"
				value="1"
				<?php if ( $like == TRUE) { print 'checked="yes" '; } ?>
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('timestamp');?>">
			Show Timestamp
			<input
				type="checkbox"
				id="<?php echo $this->get_field_id('timestamp');?>"
				name="<?php echo $this->get_field_name('timestamp');?>"
				value="1"
				<?php if ( $timestamp == TRUE) { print 'checked="yes" '; } ?>
			/><br />
		</label>

		<label for="<?php echo $this->get_field_id('remove_photos');?>">
			Remove All Photos
			<input
				type="checkbox"
				id="<?php echo $this->get_field_id('remove_photos');?>"
				name="<?php echo $this->get_field_name('remove_photos');?>"
				value="1"
				<?php if ( $remove_photos == TRUE) { print 'checked="yes" '; } ?>
			/><br />
		</label>
		<?php
	}
}
