<?php

/*
Plugin Name: Amikelive Google Adsense Widget
Plugin URI: http://tech.amikelive.com/
Description: This is a rewrite of Google Adsense Sidebar Widget available for download from http://tech.amikelive.com/node-101/quick-notes-on-google-adsense-sidebar-widget/. This version is compatible with Wordpress > 2.8.x
Author: Mikael Fernandus Simalango
Version: 0.5
Author URI: http://tech.amikelive.com
*/

/**
 *AmlAdsenseWidget class
 *This class handles the logic of the widget and extends the native functionality of the widget class
 */
class AmlAdsenseWidget extends WP_Widget {
	var $_adsenseConfigs = array('google_ad_client', 'google_ad_slot', 'google_ad_width', 'google_ad_height');
	
	/**
	 *@constructor
	 */
	function AmlAdsenseWidget () {
		/** AmlAdsenseWidget settings **/
		$widgetOptions = array('classname' => 'widget_aml_adsense','description' => 'A widget to display Google Adsense on the sidebar');
		$controlOptions = array('width' => 250, 'height' => 250, 'id_base' => 'aml-adsense-widget');
		
		/** Create the widget **/
		$this->WP_Widget('aml-adsense-widget','Amikelive Adsense Widget',$widgetOptions,$controlOptions);
	}
	
	/**
	 *Function handler for widget display
	 */
	function widget ($args, $widgetInstance) {
		extract($args);
		
		$title = apply_filters('widget_title',$widgetInstance['title']);
		
		/** Before widget **/
		echo $before_widget;
		
		/** Widget title **/
		if($title)
			echo $before_title.$title.$after_title;
		
		/** The adsense javascript code **/
		$adsenseScript =  <<<AML
<!-- Google AdSense -->
<script type="text/javascript"><!--
##CONFIG_CODE##
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
<!-- /Google AdSense -->
AML;
		$configCode = '';
		for($i=0; $i<count($this->_adsenseConfigs); $i++) {
			$configCode .= $this->_adsenseConfigs[$i]." = \"".$widgetInstance[$this->_adsenseConfigs[$i]]."\";\r\n";
		}		
		echo str_replace("##CONFIG_CODE##",$configCode,$adsenseScript);
		
		/** After widget **/
		echo $after_widget;
	}
	
	/**
	 *Widget form containing configuration
	 */
	function form ($widgetInstance) {			
		$defaultConfigs = array('title' => 'Sponsors', 'google_ad_client' => '', 'google_ad_width' => '', 'google_ad_height' => '');
		$widgetInstance = wp_parse_args((array) $widgetInstance, $defaultConfigs);
		
		echo <<<AML
<p>
	<label for="{$this->get_field_id('title')}">Title:</label>
	<input id={$this->get_field_id('title')}" name="{$this->get_field_name('title')}" value="{$widgetInstance['title']}" style="width:200px;" />
</p>
<p>
	<label for="{$this->get_field_id('google_ad_client')}">Adsense Client ID:</label>
	<input id={$this->get_field_id('google_ad_client')}" name="{$this->get_field_name('google_ad_client')}" value="{$widgetInstance['google_ad_client']}" style="width:200px;" />
</p>
<p>
	<label for="{$this->get_field_id('google_ad_slot')}">Adsense Slot Number:</label>
	<input id={$this->get_field_id('google_ad_slot')}" name="{$this->get_field_name('google_ad_slot')}" value="{$widgetInstance['google_ad_slot']}" style="width:200px;" />
</p>
<p>
	<label for="{$this->get_field_id('google_ad_width')}">Adsense Width:</label>
	<input id={$this->get_field_id('google_ad_width')}" name="{$this->get_field_name('google_ad_width')}" value="{$widgetInstance['google_ad_width']}" style="width:200px;" />
</p>
<p>
	<label for="{$this->get_field_id('google_ad_height')}">Adsense Height:</label>
	<input id={$this->get_field_id('google_ad_height')}" name="{$this->get_field_name('google_ad_height')}" value="{$widgetInstance['google_ad_height']}" style="width:200px;" />
</p>
AML;
	}
	
	/**
	 *Function handler for widget configuration update
	 */
	function update ($newInstance, $oldInstance) {
		$instance = $old_instance;
		
		/** Sanitize input **/
		$instance['title'] = strip_tags(stripslashes($newInstance['title']));
		$instance['google_ad_client'] = strip_tags(stripslashes($newInstance['google_ad_client']));
		$instance['google_ad_slot'] = strip_tags(stripslashes($newInstance['google_ad_slot']));
		$instance['google_ad_width'] = strip_tags(stripslashes($newInstance['google_ad_width']));
		$instance['google_ad_height'] = strip_tags(stripslashes($newInstance['google_ad_height']));
		
		return $instance;
	}
}

/**
 *Register AmlAdsenseWidget
 *
 *Hook the initialization function with 'add_action'
 */
function AmlAdsenseWidgetInit () {
	register_widget('AmlAdsenseWidget');
}
add_action('widgets_init','AmlAdsenseWidgetInit');

?>