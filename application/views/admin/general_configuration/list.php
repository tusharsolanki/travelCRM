<?php 
    /*
        @Description: Admin tips list
        @Author: Mit Makwana
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));

if ($this->input->post('search_txt'))
{
    ?>
    <div class="header_txt" >
        <label class="uppertxt">
            <?php echo  $this->input->post('user_search_filter') ?>
        </label>&gt; '<?php echo  $this->input->post('search_txt') ?>'
    </div>
    <?php
}
?>
<div class="content_right_part column">

<div class="chart_bg">
                <p>Dashboard | <?php echo strtoupper($this->lang->line("contact_head"))?></p>	   
     			<div id="div_msg">
        			<?php 
            			if(isset($msg)) echo '<label class="error">'.urldecode ($msg).'</label>';
       				 ?>
            	</div>
                <div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Email_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                      <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($user_type) && count($user_type)>0){ ?>
            		<tr>
                      <td colspan="4">
					  <table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($user_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  ucfirst(strtolower($row['user_type'])) ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    <? 
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_email/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_email/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_user_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['user_type'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_user_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

                                                    </tr>
                                               </table>
                                               
                                      </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            	<input type="hidden" name="usr" id="usr" value="" class="hid">
                        </table></td>
                    </tr>
                    <tr>
		                <td colspan="4">
		                    <div class="viewall">
		           			 <ul>

		                      <?php echo  $this->pagination->create_links(); ?>
		                    </ul>
		          			</div>
		      			</td>
          			</tr>
                    <? } else{?>
                        <tr>
                        	<td colspan="4">&nbsp;</td>
                        </tr>
                        <tr>
                        	<td colspan="4"><strong>No Records Found</strong></td>
                        </tr>
                   <? } ?>
          	</table>
             </div>
			 			 
			 
        </div>

</div>

<div id="dialog-confirm" style="display:none;">
    Do You want to delete Record <span id="delete_id"></span> From <span id="name"></span> ?
</div>
<script>
    $(document).ready(function(){
        $("#div_msg").fadeOut(4000); 
    });
</script>
