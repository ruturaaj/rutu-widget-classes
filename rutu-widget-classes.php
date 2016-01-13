<?php
/*
  Plugin Name: Rutu's Custom Widget Classes
  Plugin URI: http://www.inovacreations.com/rutu-custom-widget-classes
  Description: Add Custom CSS classes to Widgets. 
  Version: 1.10
  Author: Ruturaaj
  Author URI: http://www.inovacreations.com
  Author Email: ruturaj@inovacreations.com
  License:

  Copyright 2015 Ruturaaj (ruturaj@inovacreations.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

  
 */

class Rutu_CustomWidgetClasses
{
    
    public function __construct() {
        
        //add field for custom classes
        add_action('in_widget_form', array(__CLASS__, 'rutu_extend_widget_form'), 10, 3);
        
        //save class value
        add_action('widget_update_callback', array(__CLASS__, 'rutu_update_widget'), 10, 2);
        
        //output class to front-end
        add_filter('dynamic_sidebar_params', array(__CLASS__, 'rutu_add_widget_classes'));
        
    }
    
    public function rutu_extend_widget_form($widget, $return, $instance) {
        
        if (!isset($instance['w_classes'])) {
            $instance['w_classes'] = null;
        }
        
        $fields .= "\t<p style='padding: 10px 0; border-top: 1px dashed #dddddd'>"
                . "<label for='widget-{$widget->id_base}-{$widget->number}-w_classes'>CSS Classes:</label>"
                . "<input type='text' "
                        . "name='widget-{$widget->id_base}[{$widget->number}][w_classes]' "
                        . "id='widget-{$widget->id_base}-{$widget->number}-[w_classes]' "
                        . "value='{$instance['w_classes']}' class='widefat' />"
                . "</p>\n";

        echo $fields;
        return $instance;
        
    }
    
    public function rutu_update_widget($instance, $new_instance) {
        $instance['w_classes'] = $new_instance['w_classes'];
        return $instance;
    }
    
    public function rutu_add_widget_classes($params) {
        
        global $widget_number, $wp_registered_widgets;
        
        //get widget
        $widget_id = $params[0]['widget_id'];
        $widget_obj = $wp_registered_widgets[$widget_id];
        $widget_number = $widget_obj['params'][0]['number'];
        
        //init widget options
        $widget_opt = null;
        
        if (isset($widget_obj['callback'][0]->option_name)) {
            $widget_opt = get_option( $widget_obj['callback'][0]->option_name );
        }
        
        //add classes to front-end
        if (isset( $widget_opt[$widget_number]['w_classes'] ) && !empty($widget_opt[$widget_number]['w_classes']) ) {
            $params[0]['before_widget'] = preg_replace( '/class="/', "class='widget " . $widget_opt[$widget_number]['w_classes'] . "'", $params[0]['before_widget'], 1 );
        }
        
        //return updated params
        return $params;
    }
    
}
new Rutu_CustomWidgetClasses();