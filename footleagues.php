<?php
/*
Plugin Name: 7 Football Leagues
Plugin URI: http://futnik.ru/informery-i-plaginy
Description: A plugin to desplay the a chosen football league.
Version: 1.1.2
Author: FutNik
Author URI: http://futnik.ru
License: NO
*/

/**
 * Add function to widgets_init that'll load our widget.
 * @since 0.1
 */
add_action( 'widgets_init', 'football_league_load_widgets' );

/**
 * Register our widget.
 * 'FL_Widget' is the widget class used below.
 *
 * @since 0.1
 */
function football_league_load_widgets() {
	register_widget( 'FL_Widget' );
}

/**
 * FL Widget class.
 * This class handles everything that needs to be handled with the widget:
 * the settings, form, display, and update.  Nice!
 *
 * @since 0.1
 */
class FL_Widget extends WP_Widget {

	function FL_Widget() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'football_league', 'description' => __('Плагин для показывания таблицы 7 определенных лиг.', 'football_league') );

		/* Widget control settings. */
		$control_ops = array('id_base' => 'football_league-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'football_league-widget', __('7 футбольных лиг', 'football_league'), $widget_ops, $control_ops );
        
        /* Setup the explode function */
        function explode_leagues($league, $limit){
          switch ($league) {
          case 'Russian league':
              $html = implode('', file('http://futnik.ru/widget/russian.php'));
              break;
          case 'English league':
              $html = implode('', file('http://futnik.ru/widget/england.php'));
              break;
          case 'Italian league':
              $html = implode('', file('http://futnik.ru/widget/italian.php'));
              break;
          case 'Spain league':
              $html = implode('', file('http://futnik.ru/widget/spain.php'));
              break;
          case 'Ukrain league':
              $html = implode('', file('http://futnik.ru/widget/ukrain.php'));
              break;
          case 'FNK league':
              $html = implode('', file('http://futnik.ru/widget/fnk.php'));
              break;
          case 'Germany league':
              $html = implode('', file('http://futnik.ru/widget/germany.php'));
              break;
          }

          $output='<table class="tblResults" cellpadding="0" cellspacing="2" border="0"><tr><th width="20">П</th><th width="150">Клуб</th><th width="20">Игр</th><th width="20">О</th></tr>'.$html.'<center><object width="500" height="350" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"><param value="http://futnik.ru/widget/ball.swf" name="MOVIE"/><param value="TRUE" name="PLAY"/><param value="TRUE" name="LOOP"/><param value="HIGH" name="QUALITY"/><embed width="32" height="32" quality="HIGH" loop="TRUE" play="TRUE" src="http://futnik.ru/widget/ball.swf"/></object></center>';
          echo $output;
      }
    
	}

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );
		$name = $instance['name'];
		$league = $instance['league'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* If show league was selected, display the user's league. */
		if ( $league )
			explode_leagues($instance['league'], $instance['limit_teams']);

		/* After widget (defined by themes). */
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['limit_teams'] = strip_tags( $new_instance['limit_teams'] );
    	$instance['league'] = $new_instance['league'];

		return $instance;
	}

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array( 'title' => __('FL', 'football_league'), 'name' => __('John Doe', 'football_league'), 'league' => 'male', 'show_league' => true );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Название:', 'hybrid'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
		</p>

        <p>
          <label>Лига:</label><br />
          <select id="<?php echo $this->get_field_id( 'league' ); ?>" name="<?php echo $this->get_field_name( 'league' ); ?>" style="width:100%;">
              <option value="Russian league" <?php if ($instance['league'] == 'Russian league'){echo 'selected="selected"';}?> >РФПЛ</option>
              <option value="FNK league" <?php if ($instance['league'] == 'FNK league'){echo 'selected="selected"';}?> >ФНЛ</option>
              <option value="English league" <?php if ($instance['league'] == 'English league'){echo 'selected="selected"';}?> >Премьер-лига Англии</option>
              <option value="Italian league" <?php if ($instance['league'] == 'Italian league'){echo 'selected="selected"';}?> >Серия А Италии</option>
              <option value="Spain league" <?php if ($instance['league'] == 'Spain league'){echo 'selected="selected"';}?> >1 дивизион Испании</option>
              <option value="Ukrain league" <?php if ($instance['league'] == 'Ukrain league'){echo 'selected="selected"';}?> >Высшая лига Украины</option>
              <option value="Germany league" <?php if ($instance['league'] == 'Germany league'){echo 'selected="selected"';}?> >Бундес лига Германии</option>
          </select>
        </p>
        

	<?php
	}
}

?>