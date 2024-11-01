<?php
/*
Plugin Name: Sno Looken120
Plugin URI: http://snolooken.se/wp_plugin
Description: A plugin for matching images to products
Version: 0.21
Author: 49lights AB / Mattias Aspelund
Author URI: http://www.49lights.com
License: GPL2
*/
/*  Copyright 2011  49lights AB (email : info@49lights.com)

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

add_option("SnolookenID", "-1", null, 'no');


class snolooken {
    function add_jquery(){
            wp_enqueue_script( 'jquery' );
    }
    function add_wp_javascript()  {
?>
<script  type="text/javascript">
    document.write('<script charset="utf-8" type="text/javascript" src="http://snolooken.se/public/scripts/snolooken_blogg_v0.4.js?' + (new Date().getTime()) + '"></' + 'script>');
jQuery(document).ready(function(){
    sleBlog.init(<?php echo get_option('SnoLookenID'); ?>);    
});

</script>
<?php
    }
    function plugins_page() {
        if (!current_user_can('manage_options'))  {
            wp_die( __('You do not have sufficient permissions to access this page.') );
        }
        
        $opt_name = "SnolookenID";
        $opt_val = get_option($opt_name);
        
        if(isset($_POST[$opt_name])){
            $opt_val = $_POST[$opt_name];
            update_option($opt_name, $opt_val); ?>
<div class="updated"><p><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>            
            <?php  
        }
        
        echo '<div class="wrap">';
        echo "<h2>Sno looken</h2>";
        ?>
<form name="form1" method="post" action="">
<p><?php _e("Blogg-id:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $opt_name; ?>" value="<?php echo $opt_val; ?>" size="20">
</p>
<p>Du hittar ditt blogg-id p&aring; <a href='http://snolooken.se'>Snolooken.se</a> under <i>Redigera inst&auml;llningar</i>.</p>
<hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>

</div>
<?        
    }
    function add_plugins_page(){
        add_plugins_page("Sno looken Settings", "Sno looken", "read", "se-snolooken-plugins-page", array("snolooken", "plugins_page"));        
    }
    function add_media_column($cols){
        $cols['snolooken_edit'] = "Sno Looken";
        return $cols;
    }
    function get_media_column_value( $column_name, $id ) { 
        if ( $column_name == "snolooken_edit" ) {  
            if ( wp_attachment_is_image( $id ) ) {  
                global $_wp_additional_image_sizes;  
                $sizes = array( "full", "large", "medium", "thumbnail" );  
                if ( count( $_wp_additional_image_sizes ) ) {  
                    foreach ( $_wp_additional_image_sizes as $additional_image_size_label => $additional_image_size_info )  
                        $sizes[] = $additional_image_size_label;  
                }  
                echo '<div class="box" id="snolooken-media-' . $id . '">';                 
                    echo '<input type="hidden" name="SnoLookenID" value="'.get_option('SnoLookenID').'" >';
                foreach( $sizes as $size ) {  
                    $image_src = wp_get_attachment_image_src( $id, $size );  
                    echo '<input name="image[]" type="hidden" value="' . $image_src[0] . '" />';  
                }  
                echo '<p class="submit">
                        <input onclick="window.open(\'http://snolooken.se/wpplugin/editimageset?\' + jQuery(\'#snolooken-media-'.$id.' input\').serialize(), \'_blank\');return false;" type="submit" name="Submit" class="button-primary" value="Redigera look" />
                    </p>';                
                echo '</div>'; 
            }
        }
    }
}

add_action('wp_footer',array('snolooken','add_wp_javascript'));
add_action('admin_menu', array('snolooken','add_plugins_page'));
add_action('wp_enqueue_scripts', array('snolooken','add_jquery'));
add_action( 'manage_media_custom_column', array('snolooken','get_media_column_value'), 10, 2 );  


add_filter( 'manage_media_columns', array('snolooken','add_media_column'), 10, 2 );  



?>