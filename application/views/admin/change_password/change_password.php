<?php
/*
    @Description: Changed Password
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 07-05-14
	
*/

	$userData = $this->session->userdata($this->lang->line('common_admin_session_label'));
 	$viewname = $this->router->uri->segments[2]; 
 
 
 	isset($editRecord) ? $loadcontroller='update_data' : $loadcontroller='insert_data';
    $path = $viewname."/".$loadcontroller;
    ?>

<div class="content_right_part column">

<div class="chart_bg tbl_border"> 
	<table>
	<tr class="iconment_title">
		<td width="244"> <strong>Change Password</strong></td>
		<td width="970">&nbsp;</td>
		<td width="47" align="right"><a class="icon_btn" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td>
	</tr>
	</table>
    <form name="login" id="login" action="<?=base_url();?>admin/change_password" method="post" >
      <table class="loginBox" width="100%" cellpadding="7" cellspacing="0" border="0">
        
        <tr>
            <td>Hello, <?=$userData['name'];?></td>
        </tr>
    	<?php  if(!empty($msg)){ ?>
        <tr>
            <td colspan="3">
				<div id="div_msg" style="display:none;">
				 <?php echo '<label class="error">'.urldecode ($msg).'</label>'; ?>
				</div>
            </td>
        </tr>
        <? } ?>
        <tr>
            <td>
                <table width="100%" cellpadding="7" cellspacing="0" border="0">
                    <tr>
                        <td width="150"><?=$this->lang->line('old_password');?></td>
                        <td width="20"> : </td>
                        <td><input type="password" data-bvalidator="required" name="oldPassword" id="oldPassword" /></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('new_password');?></td>
                        <td> : </td>
                        <td><input type="password" data-bvalidator="required,minlength[6]" name="newPassword" id="newPassword" /></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('new_co_password');?></td>
                        <td> : </td>
                        <td><input type="password" data-bvalidator="equalto[newPassword],required,minlength[6]" name="newCoPassword" id="newCoPassword" /></td>
                    </tr>
                    <tr>
                         <td colspan="2">&nbsp;</td>
                        <td>
                            <input type="submit" class="button" name="submit" id="submit" value="Submit" />
                            <input type="reset" class="button" name="reset" id="reset" value="Reset" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
    </table>
   </form>
</div>
</div>