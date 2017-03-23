<?php

class WDIViewThemes_wdi {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;
  
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display() {
    /*My Edit*/
    global $wdi_options;
   $rows_data = $this->model->get_rows_data();
    $page_nav = $this->model->page_nav();
   $search_value = ((isset($_POST['search_value'])) ? esc_html(stripslashes($_POST['search_value'])) : '');
   $search_select_value = ((isset($_POST['search_select_value'])) ? (int) $_POST['search_select_value'] : 0);
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html(stripslashes($_POST['asc_or_desc'])) : 'asc');
    $order_by = (isset($_POST['order_by']) ? esc_html(stripslashes($_POST['order_by'])) : 'id');
    $order_class = 'manage-column column-title sorted ' . $asc_or_desc;
    $ids_string = '';
    ?>
    <div style="clear: both; float: left; width: 99%;">
      <div style="float: left; font-size: 14px; font-weight: bold;">
        <?php _e('This section allows you to create, edit and delete Themes.', "wdi"); ?>
        <a style="color: #ff4444; text-decoration: none;" target="_blank" href="https://web-dorado.com/wordpress-instagram-feed-wd/editing-themes.html"><?php _e('Read More in User Manual.', "wdi"); ?></a>
      </div>
    </div>
    <form class="wrap" id="sliders_form" method="post" action="admin.php?page=wdi_themes" style="float: left; width: 99%;">
      <?php wp_nonce_field('nonce_wd', 'nonce_wd'); ?>
      <input type="hidden" id="wdi_access_token" name="access_token" value="<?php echo isset($wdi_options['wdi_access_token'])?$wdi_options['wdi_access_token']:'';?>">
      <span class="theme-icon"></span>
      <h2 class="wdi_page_title">
        <?php _e('Themes', "wdi"); ?>
        <a href="" class="add-new-h2" onclick="wdi_spider_set_input_value('task', 'add');
              if(document.getElementById('wdi_access_token').value!=''){
                    wdi_spider_form_submit(event, 'sliders_form');
              }"><?php _e('Add new', "wdi"); ?></a>
      </h2>
      <div class="buttons_div">
        <span class="button-secondary non_selectable" onclick="wdi_spider_check_all_items()">
          <input type="checkbox" id="check_all_items" name="check_all_items" onclick="wdi_spider_check_all_items_checkbox()" style="margin: 0; vertical-align: middle;" />
          <span style="vertical-align: middle;"><?php _e('Select All', "wdi"); ?></span>
        </span>
        <input class="button-secondary" type="submit" onclick="wdi_spider_set_input_value('task', 'duplicate_all')" value="<?php esc_attr_e('Duplicate', "wdi"); ?>" />
        
        <input class="button-secondary" type="submit" onclick="if (confirm('<?php esc_attr_e('Do you want to delete selected items?', "wdi"); ?>')) {
                                                       wdi_spider_set_input_value('task', 'delete_all');
                                                     } else {
                                                       return false;
                                                     }" value="<?php esc_attr_e('Delete', "wdi"); ?> " />
      </div>
      <div class="tablenav top">
        <?php
        WDILibrary::search(__('Name',"wdi"), $search_value, 'sliders_form');
        WDILibrary::html_page_nav($page_nav['total'], $page_nav['limit'], 'sliders_form');
        ?>
      </div>
      <table class="wp-list-table widefat fixed pages">
        <thead>
          <th class="manage-column column-cb check-column table_small_col"><input id="check_all" type="checkbox" onclick="wdi_spider_check_all(this)" style="margin:0;" /></th>
          <th class="table_small_col <?php if ($order_by == 'id') {echo $order_class;} ?>">
            <a onclick="wdi_spider_set_input_value('task', '');
                        wdi_spider_set_input_value('order_by', 'id');
                        wdi_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'id') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        wdi_spider_form_submit(event, 'sliders_form')" href="">
              <span>ID</span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="<?php if ($order_by == 'theme_name') {echo $order_class;} ?>">
            <a onclick="wdi_spider_set_input_value('task', '');
                        wdi_spider_set_input_value('order_by', 'theme_name');
                        wdi_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'theme_name') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        wdi_spider_form_submit(event, 'sliders_form')" href="">
              <span><?php _e('Name',"wdi")?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col <?php if ($order_by == 'default_theme') {echo $order_class;} ?>">
            <a onclick="wdi_spider_set_input_value('task', '');
                        wdi_spider_set_input_value('order_by', 'default_theme');
                        wdi_spider_set_input_value('asc_or_desc', '<?php echo ((isset($_POST['asc_or_desc']) && isset($_POST['order_by']) && (esc_html(stripslashes($_POST['order_by'])) == 'default_theme') && esc_html(stripslashes($_POST['asc_or_desc'])) == 'asc') ? 'desc' : 'asc'); ?>');
                        wdi_spider_form_submit(event, 'sliders_form')" href="">
              <span><?php _e('Default',"wdi")?></span><span class="sorting-indicator"></span>
            </a>
          </th>
          <th class="table_big_col"><?php _e('Edit',"wdi")?></th>
          <th class="table_big_col"><?php _e('Delete',"wdi")?></th>
        </thead>
        <tbody id="tbody_arr">
          <?php
          if ($rows_data) {
            foreach ($rows_data as $row_data) {
              $alternate = (!isset($alternate) || $alternate == 'class="alternate"') ? '' : 'class="alternate"';
              $default_image = (($row_data->default_theme) ? 'default' : 'notdefault');
              $default = (($row_data->default_theme) ? 'remove_default' : 'set_default');
              
              ?>
              <tr id="tr_<?php echo $row_data->id; ?>" <?php echo $alternate; ?>>
                <td class="table_small_col check-column"><input id="check_<?php echo $row_data->id; ?>" name="check_<?php echo $row_data->id; ?>" onclick="wdi_spider_check_all(this)" type="checkbox" /></td>
                <td class="table_small_col"><?php echo $row_data->id; ?></td>
                
                <td>
                  <a onclick="wdi_spider_set_input_value('task', 'edit');
                                wdi_spider_set_input_value('page_number', '1');
                                wdi_spider_set_input_value('search_value', '');
                                wdi_spider_set_input_value('search_or_not', '');
                                wdi_spider_set_input_value('asc_or_desc', 'asc');
                                wdi_spider_set_input_value('order_by', 'order');
                                wdi_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                wdi_spider_form_submit(event, 'sliders_form')" href="" title="Edit"><?php echo $row_data->theme_name; ?>
                  </a>
                </td>
               
                <td class="table_big_col"><a onclick="wdi_spider_set_input_value('task', '<?php echo $default; ?>');wdi_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');wdi_spider_form_submit(event, 'sliders_form')" href=""><img src="<?php echo WDI_URL . '/images/' . $default_image . '.png'; ?>"></img></a></td>
                <td class="table_big_col"><a onclick="wdi_spider_set_input_value('task', 'edit');
                                                      wdi_spider_set_input_value('page_number', '1');
                                                      wdi_spider_set_input_value('search_value', '');
                                                      wdi_spider_set_input_value('search_or_not', '');
                                                      wdi_spider_set_input_value('asc_or_desc', 'asc');
                                                      wdi_spider_set_input_value('order_by', 'order');
                                                      wdi_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                      wdi_spider_form_submit(event, 'sliders_form')" href="">Edit</a></td>
                <td class="table_big_col"><a onclick="if (confirm('<?php esc_attr_e('Do you want to delete selected items?', "wdi"); ?>')){
                                                      wdi_spider_set_input_value('task', 'delete');
                                                      wdi_spider_set_input_value('current_id', '<?php echo $row_data->id; ?>');
                                                      wdi_spider_form_submit(event, 'sliders_form')}" href="">Delete</a></td>
              </tr>
              <?php
              $ids_string .= $row_data->id . ',';
            }
          }
          ?>
        </tbody>
      </table>
      <input id="task" name="task" type="hidden" value="" />
      <input id="current_id" name="current_id" type="hidden" value="" />
      <input id="ids_string" name="ids_string" type="hidden" value="<?php echo $ids_string; ?>" />
      <input id="asc_or_desc" name="asc_or_desc" type="hidden" value="asc" />
      <input id="order_by" name="order_by" type="hidden" value="<?php echo $order_by; ?>" />

    </form>
    <?php
  }
  public function edit($type){
    $this->generateForm($type);
    $tab = isset($_POST['wdi_refresh_tab']) ? $_POST['wdi_refresh_tab'] : 'feed_settings';
    $section = isset($_POST['wdi_refresh_section']) ? $_POST['wdi_refresh_section'] : 'general';
    ?>
    <script>jQuery(document).ready(function(){

      wdi_controller.switchThemeTabs("<?php echo $tab?>","<?php echo $section?>");
  
    });</script>
    <?php
  
  }



public function getFormElements($current_id=''){
    $elements = array(
      'theme_name' => array('name'=>'theme_name','title'=>__('Theme Name',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      
      'feed_container_width' => array('name'=>'feed_container_width','title'=>__('Feed Container Width',"wdi"),'type'=>'input','tooltip'=>__('Includes all feed elements',"wdi"),'attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'feed_container_bg_color' => array('name'=>'feed_container_bg_color','title'=>__('Feed Container Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'feed_wrapper_width' => array('name'=>'feed_wrapper_width','title'=>__('Feed Wrapper Width',"wdi"),'type'=>'input','tooltip'=>__('Includes only feed images, does not include feed header',"wdi"),'attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'feed_wrapper_bg_color' => array('name'=>'feed_wrapper_bg_color','title'=>__('Feed Wrapper Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'active_filter_bg_color' => array('name'=>'active_filter_bg_color','title'=>__('Active Filter Background Color',"wdi"),'type'=>'color','tooltip'=>__('Background color of filter when it is active',"wdi"),'attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'header_margin' => array('status'=>'disabled','name'=>'header_margin','title'=>__('Header Margin',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_padding' => array('name'=>'header_padding','title'=>__('Header Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_border_size' => array('status'=>'disabled','name'=>'header_border_size','title'=>__('Header Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_border_color' => array('status'=>'disabled','name'=>'header_border_color','title'=>__('Header Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_position' => array('name'=>'header_position','title'=>__('Header Position',"wdi"),'type'=>'select','valid_options'=>array('left'=>__('Left',"wdi"),'center'=>__('Center',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_img_width' => array('name'=>'header_img_width','title'=>__('Header Image Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_border_radius' => array('name'=>'header_border_radius','title'=>__('Header Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_text_padding' => array('name'=>'header_text_padding','title'=>__('Header Text Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_text_color' => array('name'=>'header_text_color','title'=>__('Header Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_font_weight' => array('name'=>'header_font_weight','title'=>__('Header Font Weight',"wdi"),'type'=>'select','valid_options'=>array('100'=>'100','200'=>'200','300'=>'300','400'=>__('400 (Normal)',"wdi"),'500'=>'500','600'=>'600','700'=>'700 (Bold)'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_text_font_size' => array('name'=>'header_text_font_size','title'=>__('Header Text Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      'header_text_font_style' => array('name'=>'header_text_font_style','title'=>__('Header Text Font Style',"wdi"),'type'=>'select','valid_options'=>array('normal'=>'Normal','italic'=>'Italic','oblique'=>'Oblique'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'header'))),
      
      'user_horizontal_margin' => array('status'=>'disabled','name'=>'user_horizontal_margin','title'=>__('User Horizontal Margin',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'user_padding' => array('name'=>'user_padding','title'=>__('Single User Padding',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'user_border_size' => array('status'=>'disabled','name'=>'user_border_size','title'=>__('User Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'user_border_color' => array('status'=>'disabled','name'=>'user_border_color','title'=>__('User Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'user_img_width' => array('name'=>'user_img_width','title'=>__('User Image Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'user_border_radius' => array('name'=>'user_border_radius','title'=>__('User Border Radius',"wdi"),'type'=>'input','input_type'=>'number','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'user_background_color' => array('status'=>'disabled','name'=>'user_background_color','title'=>__('User Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'users_border_size' => array('status'=>'disabled','name'=>'users_border_size','title'=>__('User Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'users_border_color' => array('status'=>'disabled','name'=>'users_border_color','title'=>__('Users Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'users_background_color' => array('status'=>'disabled','name'=>'users_background_color','title'=>__('Users Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'general'))),
      'users_text_color' => array('name'=>'users_text_color','title'=>__('Users Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'users_font_weight' => array('name'=>'users_font_weight','title'=>__('Users Font Weight',"wdi"),'type'=>'select','valid_options'=>array('100'=>'100','200'=>'200','300'=>'300','400'=>__('400 (Normal)',"wdi"),'500'=>'500','600'=>'600','700'=>'700 (Bold)'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'users_text_font_size' => array('name'=>'users_text_font_size','title'=>__('Username Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'users_text_font_style' => array('name'=>'users_text_font_style','title'=>__('Users Text Font Style',"wdi"),'type'=>'select','valid_options'=>array('normal'=>'Normal','italic'=>'Italic','oblique'=>'Oblique'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'user_description_font_size' => array('name'=>'user_description_font_size','title'=>__('User Description Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),

      'follow_btn_border_radius' => array('name'=>'follow_btn_border_radius','title'=>__('Follow Button Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_padding' => array('name'=>'follow_btn_padding','title'=>__('Follow Button Padding',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_margin'=> array('name'=>'follow_btn_margin','title'=>__('Distance between user\'s name and follow button',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('place'=>'after','text'=>'px'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      
      'follow_btn_bg_color'=>array('name'=>'follow_btn_bg_color','title'=>__('Follow Button Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_border_color'=>array('name'=>'follow_btn_border_color','title'=>__('Follow Button Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_text_color'=>array('name'=>'follow_btn_text_color','title'=>__('Follow Button Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),

      'follow_btn_font_size' => array('name'=>'follow_btn_font_size','title'=>__('Follow Button Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_border_hover_color' => array('name'=>'follow_btn_border_hover_color','title'=>__('Follow Button Border Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_text_hover_color' => array('name'=>'follow_btn_text_hover_color','title'=>__('Follow Button Text Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),
      'follow_btn_background_hover_color' => array('name'=>'follow_btn_background_hover_color','title'=>__('Follow Button Background Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'user_data'))),

      
      

      'load_more_position' => array('name'=>'load_more_position','title'=>__('Load More Button Position',"wdi"),'type'=>'select','valid_options'=>array('left'=>__('Left',"wdi"),'center'=>__('Center',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_padding' => array('name'=>'load_more_padding','title'=>__('Load More Button Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_bg_color' => array('name'=>'load_more_bg_color','title'=>__('Load More Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_border_radius' => array('name'=>'load_more_border_radius','title'=>__('Load More Button Border Radius',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_height' => array('name'=>'load_more_height','title'=>__('Load More Button Height',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_width' => array('name'=>'load_more_width','title'=>__('Load More Button Width',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_border_size' => array('name'=>'load_more_border_size','title'=>__('Load More Button Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_border_color' => array('name'=>'load_more_border_color','title'=>__('Load More Button Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_text_color' => array('name'=>'load_more_text_color','title'=>__('Load More Button Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_text_font_size' => array('name'=>'load_more_text_font_size','title'=>__('Load More Button Text Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'load_more_wrap_hover_color' => array('name'=>'load_more_wrap_hover_color','title'=>__('Load More Button Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_ctrl_color' => array('name'=>'pagination_ctrl_color','title'=>__('Pagination Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_size' => array('name'=>'pagination_size','title'=>__('Pagination Height',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_ctrl_margin' => array('name'=>'pagination_ctrl_margin','title'=>__('Pagination Button Margins',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_ctrl_hover_color' => array('name'=>'pagination_ctrl_hover_color','title'=>__('Pagination Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_position' => array('name'=>'pagination_position','title'=>__('Pagination Buttons Alignment',"wdi"),'type'=>'select','valid_options'=>array('left'=>__('Left',"wdi"),'center'=>__('Center',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),
      'pagination_position_vert' => array('name'=>'pagination_position_vert','title'=>__('Pagination Buttons Position',"wdi"),'type'=>'select','valid_options'=>array('top'=>__('Top',"wdi"),'bottom'=>__('Bottom',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'load_more'))),

      //lightbox settings
      'lightbox_overlay_bg_color' => array('name'=>'lightbox_overlay_bg_color','title'=>__('Overlay Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_general'))),
      'lightbox_overlay_bg_transparent' => array('name'=>'lightbox_overlay_bg_transparent','title'=>__('Overlay Background Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_general'))),
      'lightbox_bg_color' => array('name'=>'lightbox_bg_color','title'=>__('Lightbox Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_general'))),
      'lightbox_ctrl_btn_height' => array('name'=>'lightbox_ctrl_btn_height','title'=>__('Control Buttons Height',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_margin_top' => array('name'=>'lightbox_ctrl_btn_margin_top','title'=>__('Control Buttons Margin (top)',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_margin_left' => array('name'=>'lightbox_ctrl_btn_margin_left','title'=>__('Control Buttons Margin (left)',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_pos' => array('name'=>'lightbox_ctrl_btn_pos','title'=>__('Control Buttons Position',"wdi"),'type'=>'radio','valid_options'=>array('top'=>__('Top',"wdi"),'bottom'=>__('Bottom',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_cont_bg_color' => array('name'=>'lightbox_ctrl_cont_bg_color','title'=>__('Control Buttons Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_cont_border_radius' => array('name'=>'lightbox_ctrl_cont_border_radius','title'=>__('Control Buttons Contain Container Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_cont_transparent' => array('name'=>'lightbox_ctrl_cont_transparent','title'=>__('Control Buttons Container Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_align' => array('name'=>'lightbox_ctrl_btn_align','title'=>__('Control Buttons Alignment',"wdi"),'type'=>'select','valid_options'=>array('left'=>__('Left',"wdi"),'center'=>__('Center',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_color' => array('name'=>'lightbox_ctrl_btn_color','title'=>__('Control Buttons Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_ctrl_btn_transparent' => array('name'=>'lightbox_ctrl_btn_transparent','title'=>__('Control Buttons Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),

      'lightbox_toggle_btn_height' => array('name'=>'lightbox_toggle_btn_height','title'=>__('Toggle Button Height',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_toggle_btn_width' => array('name'=>'lightbox_toggle_btn_width','title'=>__('Toggle Button Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_ctrl_btns'))),
      'lightbox_close_btn_border_radius' => array('name'=>'lightbox_close_btn_border_radius','title'=>__('Close Button Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_border_width' => array('name'=>'lightbox_close_btn_border_width','title'=>__('Close Button Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_border_style' => array('name'=>'lightbox_close_btn_border_style','title'=>__('Close Button Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_border_color' => array('name'=>'lightbox_close_btn_border_color','title'=>__('Close Button Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_box_shadow' => array('name'=>'lightbox_close_btn_box_shadow','title'=>__('Close Button Box Shadow',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_bg_color' => array('name'=>'lightbox_close_btn_bg_color','title'=>__('Close Button Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_transparent' => array('name'=>'lightbox_close_btn_transparent','title'=>__('Close Button Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_width' => array('name'=>'lightbox_close_btn_width','title'=>__('Close Button Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_height' => array('name'=>'lightbox_close_btn_height','title'=>__('Close Button Height',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_top' => array('name'=>'lightbox_close_btn_top','title'=>__('Close Button Top',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_right' => array('name'=>'lightbox_close_btn_right','title'=>__('Close Button Right',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_size' => array('name'=>'lightbox_close_btn_size','title'=>__('Close Button Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_color' => array('name'=>'lightbox_close_btn_color','title'=>__('Close Button Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_full_color' => array('name'=>'lightbox_close_btn_full_color','title'=>__('Fullscreen Close Button Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_close_btn_hover_color' => array('name'=>'lightbox_close_btn_hover_color','title'=>__('Close Button Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_close_btn'))),
      'lightbox_comment_share_button_color' => array('name'=>'lightbox_comment_share_button_color','title'=>__('Share Buttons Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_general'))),

      'lightbox_rl_btn_style' => array('name'=>'lightbox_rl_btn_style','title'=>__('Right, Left Buttons Style',"wdi"),'type'=>'select','valid_options'=>array('fa-chevron'=>'Chevron','fa-angle'=>'Angle','fa-angle-double'=>'Double'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_bg_color' => array('name'=>'lightbox_rl_btn_bg_color','title'=>__('Right, Left Buttons Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_transparent' => array('name'=>'lightbox_rl_btn_transparent','title'=>__('Right, Left Buttons Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_box_shadow' => array('name'=>'lightbox_rl_btn_box_shadow','title'=>__('Right, Left Buttons Box Shadow: ',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_height' => array('name'=>'lightbox_rl_btn_height','title'=>__('Right, Left Buttons Height',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_width' => array('name'=>'lightbox_rl_btn_width','title'=>__('Right, Left Buttons Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_size' => array('name'=>'lightbox_rl_btn_size','title'=>__('Right, Left Buttons Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_close_rl_btn_hover_color' => array('name'=>'lightbox_close_rl_btn_hover_color','title'=>__('Right, Left, Close Buttons Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_color' => array('name'=>'lightbox_rl_btn_color','title'=>__('Right, Left Buttons Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_border_radius' => array('name'=>'lightbox_rl_btn_border_radius','title'=>__('Right, Left Buttons Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_border_width' => array('name'=>'lightbox_rl_btn_border_width','title'=>__('Right, Left Buttons Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_border_style' => array('name'=>'lightbox_rl_btn_border_style','title'=>__('Right, Left Buttons Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      'lightbox_rl_btn_border_color' => array('name'=>'lightbox_rl_btn_border_color','title'=>__('Right, Left Buttons Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_nav_btns'))),
      
      'lightbox_filmstrip_pos' => array('name'=>'lightbox_filmstrip_pos','title'=>__('Filmstrip Position',"wdi"),'type'=>'select','valid_options'=>array('top'=>__('Top',"wdi"),'right'=>__('Right',"wdi"),'bottom'=>__('Bottom',"wdi"),'left'=>__('Left',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_margin' => array('name'=>'lightbox_filmstrip_thumb_margin','title'=>__('Filmstrip Thumbnail Margin',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_border_width' => array('name'=>'lightbox_filmstrip_thumb_border_width','title'=>__('Filmstrip Thumbnail Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_border_style' => array('name'=>'lightbox_filmstrip_thumb_border_style','title'=>__('Filmstrip Thumbnail Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_border_color' => array('name'=>'lightbox_filmstrip_thumb_border_color','title'=>__('Filmstrip Thumbnail Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_border_radius' => array('name'=>'lightbox_filmstrip_thumb_border_radius','title'=>__('Filmstrip Thumbnail Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_active_border_width' => array('name'=>'lightbox_filmstrip_thumb_active_border_width','title'=>__('Filmstrip Thumbnail Active Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_active_border_color' => array('name'=>'lightbox_filmstrip_thumb_active_border_color','title'=>__('Filmstrip Thumbnail Active Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_thumb_deactive_transparent' => array('name'=>'lightbox_filmstrip_thumb_deactive_transparent','title'=>__('Filmstrip Thumbnail Deactive Opacity',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_rl_btn_size' => array('name'=>'lightbox_filmstrip_rl_btn_size','title'=>__('Filmstrip Right, Left Buttons Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_rl_btn_color' => array('name'=>'lightbox_filmstrip_rl_btn_color','title'=>__('Filmstrip Right, Left Buttons Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),
      'lightbox_filmstrip_rl_bg_color' => array('name'=>'lightbox_filmstrip_rl_bg_color','title'=>__('Filmstrip Right, Left Button Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_filmstrip'))),

      'lightbox_info_pos' => array('name'=>'lightbox_info_pos','title'=>__('Info Position',"wdi"),'type'=>'radio','valid_options'=>array('top'=>__('Top',"wdi"),'bottom'=>__('Bottom',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_align' => array('name'=>'lightbox_info_align','title'=>__('Info Alignment',"wdi"),'type'=>'select','valid_options'=>array('left'=>__('Left',"wdi"),'center'=>__('Center',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_bg_color' => array('name'=>'lightbox_info_bg_color','title'=>__('Info Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_bg_transparent' => array('name'=>'lightbox_info_bg_transparent','title'=>__('Info Background Transparency',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_border_width' => array('name'=>'lightbox_info_border_width','title'=>__('Info border width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_border_style' => array('name'=>'lightbox_info_border_style','title'=>__('Info Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_border_color' => array('name'=>'lightbox_info_border_color','title'=>__('Info Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_border_radius' => array('name'=>'lightbox_info_border_radius','title'=>__('Info Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_padding' => array('name'=>'lightbox_info_padding','title'=>__('Info Padding',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_margin' => array('name'=>'lightbox_info_margin','title'=>__('Info Margin',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),

      'lightbox_title_color' => array('name'=>'lightbox_title_color','title'=>__('Title Font Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_title_font_style' => array('name'=>'lightbox_title_font_style','title'=>__('Title Font Family',"wdi"),'type'=>'select','valid_options'=>array('arial'=>'Arial','lucida grande'=>'Lucida grande','segoe ui'=>'Segoe ui','tahoma'=>'Tahoma','trebuchet ms'=>'Trebuchet ms','verdana'=>'Verdana','cursive'=>'Cursive','fantasy'=>'Fantasy','monospace'=>'Monospace','serif'=>'Serif'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_title_font_weight' => array('name'=>'lightbox_title_font_weight','title'=>__('Title Font Weight',"wdi"),'type'=>'select','valid_options'=>array('lighter'=>'Lighter','normal'=>'Normal','bold'=>'Bold'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_title_font_size' => array('name'=>'lightbox_title_font_size','title'=>__('Title Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),

      'lightbox_description_color' => array('name'=>'lightbox_description_color','title'=>__('Description Font Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_description_font_style' => array('name'=>'lightbox_description_font_style','title'=>__('Description Font Family',"wdi"),'type'=>'select','valid_options'=>array('arial'=>'Arial','lucida grande'=>'Lucida grande','segoe ui'=>'Segoe ui','tahoma'=>'Tahoma','trebuchet ms'=>'Trebuchet ms','verdana'=>'Verdana','cursive'=>'Cursive','fantasy'=>'Fantasy','monospace'=>'Monospace','serif'=>'Serif'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_description_font_weight' => array('name'=>'lightbox_description_font_weight','title'=>__('Description Font Weight',"wdi"),'type'=>'select','valid_options'=>array('lighter'=>'Lighter','normal'=>'Normal','bold'=>'Bold'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_description_font_size' => array('name'=>'lightbox_description_font_size','title'=>__('Description Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      'lightbox_info_height' => array('status'=>'disabled','name'=>'lightbox_info_height','title'=>__('Lightbox Image Description Height',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_info'))),
      
      'lightbox_comment_width' => array('name'=>'lightbox_comment_width','title'=>__('Comments Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_pos' => array('name'=>'lightbox_comment_pos','title'=>__('Comments Position',"wdi"),'type'=>'radio','valid_options'=>array('left'=>__('Left',"wdi"),'right'=>__('Right',"wdi")),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_bg_color' => array('name'=>'lightbox_comment_bg_color','title'=>__('Comments Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_font_size' => array('name'=>'lightbox_comment_font_size','title'=>__('Comments Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_font_color' => array('name'=>'lightbox_comment_font_color','title'=>__('Comments Font Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_font_style' => array('name'=>'lightbox_comment_font_style','title'=>__('Comments Font Family',"wdi"),'type'=>'select','valid_options'=>array('arial'=>'Arial','lucida grande'=>'Lucida grande','segoe ui'=>'Segoe ui','tahoma'=>'Tahoma','trebuchet ms'=>'Trebuchet ms','verdana'=>'Verdana','cursive'=>'Cursive','fantasy'=>'Fantasy','monospace'=>'Monospace','serif'=>'Serif'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_author_font_size' => array('name'=>'lightbox_comment_author_font_size','title'=>__('Comments Author Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_author_font_color' => array('name'=>'lightbox_comment_author_font_color','title'=>__('Users and Hashtag Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_author_font_color_hover' => array('name'=>'lightbox_comment_author_font_color_hover','title'=>__('Users and Hashtag Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_date_font_size' => array('name'=>'lightbox_comment_date_font_size','title'=>__('Comments Date Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_body_font_size' => array('name'=>'lightbox_comment_body_font_size','title'=>__('Comments Body Font Size',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_border_width' => array('status'=>'disabled','name'=>'lightbox_comment_input_border_width','title'=>__('Comment Input Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_border_style' => array('status'=>'disabled','name'=>'lightbox_comment_input_border_style','title'=>__('Comment Input Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_border_color' => array('status'=>'disabled','name'=>'lightbox_comment_input_border_color','title'=>__('Comment Input Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_border_radius' => array('status'=>'disabled','name'=>'lightbox_comment_input_border_radius','title'=>__('Comment Input Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_padding' => array('status'=>'disabled','name'=>'lightbox_comment_input_padding','title'=>__('Comment Input Padding',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_input_bg_color' => array('status'=>'disabled','name'=>'lightbox_comment_input_bg_color','title'=>__('Comment Input Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_bg_color' => array('status'=>'disabled','name'=>'lightbox_comment_button_bg_color','title'=>__('Comment Button Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_padding' => array('status'=>'disabled','name'=>'lightbox_comment_button_padding','title'=>__('Comment Button Padding',"wdi"),'type'=>'input','label'=>array('text'=>'Use CSS types','place'=>'after','br'=>'1'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_border_width' => array('status'=>'disabled','name'=>'lightbox_comment_button_border_width','title'=>__('Comment Button Border Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_border_style' => array('status'=>'disabled','name'=>'lightbox_comment_button_border_style','title'=>__('Comment Button Border Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_border_color' => array('status'=>'disabled','name'=>'lightbox_comment_button_border_color','title'=>__('Comment Button Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_button_border_radius' => array('status'=>'disabled','name'=>'lightbox_comment_button_border_radius','title'=>__('Comment Button Border Radius',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_separator_width' => array('name'=>'lightbox_comment_separator_width','title'=>__('Comment Separator Width',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'px','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_separator_style' => array('name'=>'lightbox_comment_separator_style','title'=>__('Comment Separator Style',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','solid'=>'Solid','dotted'=>'Dotted','dashed'=>'Dashed','double'=>'Double','groove'=>'Groove','ridge'=>'Ridge','inset'=>'Inset','outset'=>'Outset'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_separator_color' => array('name'=>'lightbox_comment_separator_color','title'=>__('Comment Separator Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_load_more_color' => array('name'=>'lightbox_comment_load_more_color','title'=>__('Load More Comments Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      'lightbox_comment_load_more_color_hover' => array('name'=>'lightbox_comment_load_more_color_hover','title'=>__('Load More Comments Text Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'lightbox_settings'),array('name'=>'section','value'=>'lb_comments'))),
      //thumbnail settings
      'th_photo_wrap_padding' => array('name'=>'th_photo_wrap_padding','title'=>__('Photo Wrapper Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_wrap_border_size' => array('name'=>'th_photo_wrap_border_size','title'=>__('Photo Wrapper Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_wrap_border_color' => array('name'=>'th_photo_wrap_border_color','title'=>__('Photo Wrapper Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_img_border_radius' => array('name'=>'th_photo_img_border_radius','title'=>__('Photo border Radius',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_wrap_bg_color' => array('name'=>'th_photo_wrap_bg_color','title'=>__('Photo Wrapper Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_meta_bg_color' => array('name'=>'th_photo_meta_bg_color','title'=>__('Photo Meta Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_meta_one_line' => array('name'=>'th_photo_meta_one_line','title'=>__('Same Line For Likes and Comments',"wdi"),'type'=>'checkbox','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_like_text_color' => array('name'=>'th_like_text_color','title'=>__('"Likes" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_comment_text_color' => array('name'=>'th_comment_text_color','title'=>__('"Comments" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_caption_font_size' => array('name'=>'th_photo_caption_font_size','title'=>__('Photo Caption Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_caption_color' => array('name'=>'th_photo_caption_color','title'=>__('Photo Caption Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_feed_item_margin' => array('status'=>'disabled','name'=>'th_feed_item_margin','title'=>__('Photo Margin',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_caption_hover_color' => array('name'=>'th_photo_caption_hover_color','title'=>__('Photo Caption Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_like_comm_font_size' => array('name'=>'th_like_comm_font_size','title'=>__('"Likes" and "Comments" Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_overlay_hover_color' => array('name'=>'th_overlay_hover_color','title'=>__('Image Overlay Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_overlay_hover_transparent' => array('name'=>'th_overlay_hover_transparent','title'=>__('Image Overlay Transparency',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_overlay_hover_icon_color' => array('name'=>'th_overlay_hover_icon_color','title'=>__('Hover Icon Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_overlay_hover_icon_font_size' => array('name'=>'th_overlay_hover_icon_font_size','title'=>__('Hover Icon Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_thumb_user_bg_color' => array('name'=>'th_thumb_user_bg_color','title'=>__('Thumbnail Username Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_thumb_user_color' => array('name'=>'th_thumb_user_color','title'=>__('Thumbnail Username Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),
      'th_photo_img_hover_effect' => array('name'=>'th_photo_img_hover_effect','title'=>__('Thumbnail Hover Effect',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','scale'=>'Scale','rotate'=>'Rotate','rotate_and_scale'=>'Rotate & Scale'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'thumbnails'))),


      //masonry settings
      'mas_photo_wrap_padding' => array('name'=>'mas_photo_wrap_padding','title'=>__('Photo Wrapper Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_wrap_border_size' => array('name'=>'mas_photo_wrap_border_size','title'=>__('Photo Wrapper Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_wrap_border_color' => array('name'=>'mas_photo_wrap_border_color','title'=>__('Photo Wrapper Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_img_border_radius' => array('name'=>'mas_photo_img_border_radius','title'=>__('Photo border Radius',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_wrap_bg_color' => array('name'=>'mas_photo_wrap_bg_color','title'=>__('Photo Wrapper Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_meta_bg_color' => array('name'=>'mas_photo_meta_bg_color','title'=>__('Photo Meta Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_meta_one_line' => array('name'=>'mas_photo_meta_one_line','title'=>__('Same Line For Likes and Comments',"wdi"),'type'=>'checkbox','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_like_text_color' => array('name'=>'mas_like_text_color','title'=>__('"Likes" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_comment_text_color' => array('name'=>'mas_comment_text_color','title'=>__('"Comments" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_caption_font_size' => array('name'=>'mas_photo_caption_font_size','title'=>__('Photo Caption Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_caption_color' => array('name'=>'mas_photo_caption_color','title'=>__('Photo Caption Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_feed_item_margin' => array('status'=>'disabled','name'=>'th_feed_item_margin','title'=>__('Photo Margin',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_caption_hover_color' => array('name'=>'mas_photo_caption_hover_color','title'=>__('Photo Caption Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_like_comm_font_size' => array('name'=>'mas_like_comm_font_size','title'=>__('"Likes" and "Comments" Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_overlay_hover_color' => array('name'=>'mas_overlay_hover_color','title'=>__('Image Overlay Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_overlay_hover_transparent' => array('name'=>'mas_overlay_hover_transparent','title'=>__('Image Overlay Transparency',"wdi"),'type'=>'input','input_type'=>'number','label'=>array('text'=>'%','place'=>'after'),'tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_overlay_hover_icon_color' => array('name'=>'mas_overlay_hover_icon_color','title'=>__('Hover Icon Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_overlay_hover_icon_font_size' => array('name'=>'mas_overlay_hover_icon_font_size','title'=>__('Hover Icon Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_thumb_user_bg_color' => array('name'=>'mas_thumb_user_bg_color','title'=>__('Thumbnail Username Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_thumb_user_color' => array('name'=>'mas_thumb_user_color','title'=>__('Thumbnail Username Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),
      'mas_photo_img_hover_effect' => array('name'=>'mas_photo_img_hover_effect','title'=>__('Thumbnail Hover Effect',"wdi"),'type'=>'select','valid_options'=>array('none'=>'None','scale'=>'Scale','rotate'=>'Rotate','rotate_and_scale'=>'Rotate & Scale'),'tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'masonry'))),

      //blog style settings
      'blog_style_photo_wrap_padding' => array('name'=>'blog_style_photo_wrap_padding','title'=>__('Photo Wrapper Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_wrap_border_size' => array('name'=>'blog_style_photo_wrap_border_size','title'=>__('Photo Wrapper Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_wrap_border_color' => array('name'=>'blog_style_photo_wrap_border_color','title'=>__('Photo Wrapper Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_img_border_radius' => array('name'=>'blog_style_photo_img_border_radius','title'=>__('Photo border Radius',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_wrap_bg_color' => array('name'=>'blog_style_photo_wrap_bg_color','title'=>__('Photo Wrapper Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_meta_bg_color' => array('name'=>'blog_style_photo_meta_bg_color','title'=>__('Photo Meta Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_meta_one_line' => array('name'=>'blog_style_photo_meta_one_line','title'=>__('Same Line For Likes and Comments',"wdi"),'type'=>'checkbox','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_like_text_color' => array('name'=>'blog_style_like_text_color','title'=>__('"Likes" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_comment_text_color' => array('name'=>'blog_style_comment_text_color','title'=>__('"Comments" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_caption_font_size' => array('name'=>'blog_style_photo_caption_font_size','title'=>__('Photo Caption Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_caption_color' => array('name'=>'blog_style_photo_caption_color','title'=>__('Photo Caption Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_feed_item_margin' => array('status'=>'disabled','name'=>'th_feed_item_margin','title'=>__('Photo Margin',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_photo_caption_hover_color' => array('name'=>'blog_style_photo_caption_hover_color','title'=>__('Photo Caption Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),
      'blog_style_like_comm_font_size' => array('name'=>'blog_style_like_comm_font_size','title'=>__('"Likes" and "Comments" Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'blog_style'))),

      //blog style settings
      'image_browser_photo_wrap_padding' => array('name'=>'image_browser_photo_wrap_padding','title'=>__('Photo Wrapper Padding',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_wrap_border_size' => array('name'=>'image_browser_photo_wrap_border_size','title'=>__('Photo Wrapper Border Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_wrap_border_color' => array('name'=>'image_browser_photo_wrap_border_color','title'=>__('Photo Wrapper Border Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_img_border_radius' => array('name'=>'image_browser_photo_img_border_radius','title'=>__('Photo border Radius',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_wrap_bg_color' => array('name'=>'image_browser_photo_wrap_bg_color','title'=>__('Photo Wrapper Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_meta_bg_color' => array('name'=>'image_browser_photo_meta_bg_color','title'=>__('Photo Meta Background Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_meta_one_line' => array('name'=>'image_browser_photo_meta_one_line','title'=>__('Same Line For Likes and Comments',"wdi"),'type'=>'checkbox','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_like_text_color' => array('name'=>'image_browser_like_text_color','title'=>__('"Likes" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_comment_text_color' => array('name'=>'image_browser_comment_text_color','title'=>__('"Comments" Text Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_caption_font_size' => array('name'=>'image_browser_photo_caption_font_size','title'=>__('Photo Caption Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_caption_color' => array('name'=>'image_browser_photo_caption_color','title'=>__('Photo Caption Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_feed_item_margin' => array('status'=>'disabled','name'=>'th_feed_item_margin','title'=>__('Photo Margin',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_photo_caption_hover_color' => array('name'=>'image_browser_photo_caption_hover_color','title'=>__('Photo Caption Hover Color',"wdi"),'type'=>'color','tooltip'=>'','attr'=>array(array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),
      'image_browser_like_comm_font_size' => array('name'=>'image_browser_like_comm_font_size','title'=>__('"Likes" and "Comments" Font Size',"wdi"),'type'=>'input','tooltip'=>'','attr'=>array(array('name'=>'class','value'=>'small_input'),array('name'=>'tab','value'=>'feed_settings'),array('name'=>'section','value'=>'image_browser'))),


      );
    $return = array('elements'=>$elements,'current_id'=>$current_id);
    return $return;
}


private function genarateFeedViews(){
    ?>
      <div class="wdi_border_wrapper">
      
      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="general" name="feed_type" value="general"><label for="general">General</label></div>
        <label for="general"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/general.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="header" name="feed_type" value="header"><label for="header">Header</label></div>
        <label for="header"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/header.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="user_data" name="feed_type" value="user_data"><label for="user_data">User Data</label></div>
        <label for="user_data"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/user_data.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="load_more" name="feed_type" value="load_more"><label for="load_more">Pagination</label></div>
        <label for="load_more"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/pagination.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="thumbnails" name="feed_type" value="thumbnails"><label for="thumbnails">Thumbnails</label></div>
        <label for="thumbnails"><img src="<?php echo plugins_url('../../images/feed_views/thumbnails.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="masonry" name="feed_type" value="masonry"><label for="masonry">Masonry</label></div>
        <label for="masonry"><img src="<?php echo plugins_url('../../images/feed_views/masonry.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="blog_style" name="feed_type" value="blog_style"><label for="blog_style">Blog Style</label></div>
        <label for="blog_style"><img src="<?php echo plugins_url('../../images/feed_views/blog_style.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="feed_settings" style="margin:5px;float:left;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="image_browser" name="feed_type" value="image_browser"><label for="image_browser">Image Browser</label></div>
        <label for="image_browser"><img src="<?php echo plugins_url('../../images/feed_views/image_browser.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_general" name="feed_type" value="lb_general"><label for="lb_general">General</label></div>
        <label for="lb_general"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/general.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_ctrl_btns" name="feed_type" value="lb_ctrl_btns"><label for="lb_ctrl_btns">Control Buttons</label></div>
        <label for="lb_ctrl_btns"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/control_buttons.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_close_btn" name="feed_type" value="lb_close_btn"><label for="lb_close_btn">Close Button</label></div>
        <label for="lb_close_btn"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/close_button.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_nav_btns" name="feed_type" value="lb_nav_btns"><label for="lb_nav_btns">Navigation Buttons</label></div>
        <label for="lb_nav_btns"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/navigation_buttons.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_filmstrip" name="feed_type" value="lb_filmstrip"><label for="lb_filmstrip">Filmstrip</label></div>
        <label for="lb_filmstrip"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/filmstrip.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_info" name="feed_type" value="lb_info"><label for="lb_info">Info</label></div>
        <label for="lb_info"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/info.png',__FILE__);?>"></label>
      </div>

      <div class="display_type" tab="lightbox_settings" style="margin:5px;float:left;display:none;">
        <div style="text-align:center;padding:2px;" ><input type="radio" id="lb_comments" name="feed_type" value="lb_comments"><label for="lb_comments">Comments</label></div>
        <label for="lb_comments"><img style="width:120px;height:80px;" src="<?php echo plugins_url('../../images/feed_views/comment.png',__FILE__);?>"></label>
      </div>
      <br class="clear">
    <?php
}
public function generateTabs(){
  ?>
    <div id="wdi_feed_tabs">
      <div class="wdi_feed_tabs" id="wdi_feed_settings" onclick="wdi_controller.switchThemeTabs('feed_settings');">Customize Feed</div>
      <div class="wdi_feed_tabs" id="wdi_lightbox_settings" onclick="wdi_controller.switchThemeTabs('lightbox_settings');">Customize Lightbox</div>
      <br class="clear">
    </div>
  <?php
}
public function generateForm($current_id = ''){

  $formInfo = $this->getFormElements($current_id);
  $elements=$formInfo['elements'];
  global  $wdi_options;
  //for edit
  $edit = false;
  if($current_id != 0){
      $theme_row = WDILibrary::objectToarray($this->model->get_theme_row($current_id));
      $edit = true; 
  }
  else{
    $theme_row = '';
  }

  ?>
   
    <div style="float: left; font-size: 14px; font-weight: bold;">
        <?php _e('Here You Can Customize Your Theme', "wdi"); ?>
        <a style="color: #ff4444; text-decoration: none;font-size:14px;" target="_blank" href="https://web-dorado.com/wordpress-instagram-feed-wd/editing-themes.html"><?php _e('Read More in User Manual',"wdi"); ?></a>
    </div>
    <div class="wrap">
    <h2><?php if($edit==true && isset($theme_row['theme_name'])){
      echo 'Edit theme <b style="font-size:23px;color:rgb(255, 97, 0);">' .$theme_row['theme_name'].'</b>';
      }
      else{
        echo "Add new Theme";
      }?>
    

    </h2>
    <?php $this->generateTabs();?>
    <?php $this->genarateFeedViews();?>
    <form method="post" action="admin.php?page=wdi_themes" id='wdi_save_feed'>
      <?php wp_nonce_field('nonce_wd', 'nonce_wd'); ?>
      <input type="hidden" id="task" name='task'>
      <input type="hidden" id="wdi_add_or_edit" name="add_or_edit" value="<?php echo $current_id;?>">
      <input type="hidden" id="wdi_current_id" name="current_id" value=''>
      <input type="hidden" id="WDI_default_theme" name="<?php echo WDI_TSN;?>[default_theme]" value="<?php echo $this->model->check_default($current_id);?>">
      <input type="hidden" id="wdi_refresh_tab" name="wdi_refresh_tab">
      <input type="hidden" id="wdi_refresh_section" name="wdi_refresh_section">
      <table class="form-table">
        <tbody>
          <?php
          foreach ($elements as $element) {

            if(isset($element['status'])){
              if($element['status'] == 'disabled'){
                  continue;
              }
            }

            ?><tr>
              <th scope="row"><a  href="#" <?php echo ($element['tooltip']!='' && isset($element['tooltip'])) ?  'class="wdi_tooltip" wdi-tooltip="'.$element['tooltip'].'"' :  'class="wdi_settings_link"'; ?> ><?php echo $element['title'];?></a></th>
                  <td><?php $this->buildField($element,$theme_row);?>
                  </td>
              </tr><?php

          }
          ?>
        </tbody>
      </table>
    <div id="wdi_save_theme_submit" class="button button-primary"><?php _e("Save","wdi")?></div>
    
      <div id="wdi_save_theme_apply" class="button button-primary"><?php _e("Apply","wdi")?></div>
      <div id="wdi_save_theme_reset" class="button button-secondary"><?php _e("Reset","wdi")?></div>
     
    
    
    </form>
    </div> 
    </div>
  <?php
}
public function buildField($element,$theme_row=''){

  require_once(WDI_DIR.'/framework/WDI_form_builder.php');
  $element['defaults'] = $this->model->get_theme_defaults();
  $element['CONST'] = WDI_TSN;
  $builder = new WDI_form_builder();
  switch($element['type']){
    case 'input':{
      $builder->input($element,$theme_row);
      break;
    }
    case 'select':{
      $builder->select($element,$theme_row);
      break;
    }
    case 'radio':{
      $builder->radio($element,$theme_row);
      break;
    }
    case 'checkbox':{
      $builder->checkbox($element,$theme_row);
      break;
    }
    case 'color':{
      $builder->color($element,$theme_row);
      break;
    }
  }
}


}





