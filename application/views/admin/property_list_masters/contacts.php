<?php 
    /*
        @Description: Admin tips list
        @Author: Mit Makwana
        @Date: 28-06-2014
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));

if ($this->input->post('search_txt'))
{
?>
<div class="header_txt" >
  <label class="uppertxt"> <?php echo  $this->input->post('user_search_filter') ?> </label>
  &gt; '<?php echo  $this->input->post('search_txt') ?>' </div>
<?php
}
?>
<div class="content_right_part column">
  <div class="chart_bg">
    <div class="table-format">
      <p>Configuration</p>
	  
      <a href="<?php echo  $this->config->item('admin_base_url').$viewname."/add_record"; ?>">
	  	<img title="contact record" src="<?php echo $this->config->item('image_path');?>contact.jpg"/>
	  </a> 
	</div>
  </div>
</div>
<div id="dialog-confirm" style="display:none;"> Do You want to delete record <span id="delete_id"></span> from <span id="name"></span> ? </div>
<script>
    $(document).ready(function(){
        $("#div_msg").fadeOut(4000); 
    });
</script>
