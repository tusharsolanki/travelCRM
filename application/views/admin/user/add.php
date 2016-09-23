<?php
/*
    @Description: User list
    @Author: Jayesh Rojasara
    @Date: 07-05-14

*/
if(count($this->session->userdata('admin_regi_session'))>0){
	$adminUserRegi = 	$this->session->userdata('admin_regi_session');
}

$viewName = $this->router->uri->segments[2];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewName.'/'.$formAction;

?>
<div class="content_right_part column">


<div class="chart_bg1 tbl_border"> 
	<table width="100%">
    	<tr class="iconment_title">
                      <td width="5%"> <?php echo (isset($editRecord)?$this->lang->line('common_edit_title'):$this->lang->line('common_add_title'))." ".($this->lang->line('common_label_user'));?></td>
                      <td width="5%">&nbsp;</td>
                      <td width="5%" align="right"><a class="icon_btn" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td>
                    </tr>
	</table>
   <form enctype="multipart/form-data" name="commonForm" id="commonForm" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url').$path;?>" >
          <table class="loginBox" width="100%" cellpadding="7" cellspacing="0" border="0">
      
      <?php

		 if(!empty($msg))
		 {

			  ?>
      <tr>
        <td colspan="3"><div id="div_msg" style="display:none;"> <?php echo '<label class="error">'.urldecode ($msg).'</label>'; ?> </div></td>
      </tr>
      <? } ?>
      <tr>
        <td width="25%" ><?php echo $this->lang->line("common_label_name");?>
          <span class="required"> * </span></td>
        <td width="2%"> : </td>
        <td width="80%"><input data-bvalidator="required" type="text" name="name" id="name" value="<?php echo !empty($editRecord[0]['name'])?$editRecord[0]['name']:'';?>" /></td>
      </tr>
      
      <tr>
        <td><?php echo $this->lang->line("common_label_email");?>
          <span class="required"> * </span></td>
        <td> : </td>
        <td><input data-bvalidator="required,email" type="text" name="email" id="email" value="<?php echo !empty($editRecord[0]['email'])?$editRecord[0]['email']:'';?>" /></td>
      </tr>
	  <? if($this->uri->segments[3] == 'add_record'){  ?>
      <tr>
        <td><?php echo $this->lang->line("common_label_password");?>
          <span class="required"> * </span></td>
        <td> : </td>
        <td><input data-bvalidator="minlength[6],required" type="password" name="password" id="password" /></td>
      </tr>
      <tr>
        <td><?php echo $this->lang->line("common_label_cpassword");?>
          <span class="required"> * </span></td>
        <td> : </td>
        <td><input data-bvalidator="equalto[password],minlength[6],required" type="password" name="copassword" id="copassword" /></td>
      </tr>
      <? } ?>
      <tr >
        <td colspan="2">&nbsp;</td>
        <td><table width="18%" border="0">
            <tr>
              <td valign="top"><input type="hidden" name="id" id="id" value="<?php echo !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" /><input type="submit" name="submit" id="submit" value="<?php echo $this->lang->line('common_label_submit');?>" class="button" /></td>
              <td valign="top"><input type="button" name="button" id="button" value="<?php echo $this->lang->line('common_label_back_to_home');?>" class="button" onclick="javascript:history.back();" /></td>
            </tr>
          </table></td>
      </tr>
</table>
          </form>
</div>

</div>