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
					
                    <? if(!empty($email_type) && count($email_type)>0){ ?>
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
                                foreach($email_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['email_type'] ?></td>
                                        
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
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_email_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['email_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_email_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"/></a></td>

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
			 			 
			 	<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Phone_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                     <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($phone_type) && count($phone_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                               <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($phone_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['phone_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_phone/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_phone/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_phone_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>
                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['phone_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_phone_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>
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
			 	
			 	<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Address_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                     <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($address_type) && count($address_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                               <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($address_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['address_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_address/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_address/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_address_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['address_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_address_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
			 	
			 	<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Website_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                     <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($website_type) && count($website_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($website_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['website_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_website/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_website/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_website_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['website_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_website_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
			 	
			 	<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Profile_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                      <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($profile_type) && count($profile_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                               <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($profile_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['profile_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_profile/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_profile/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_profile_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['profile_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_profile_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
			 	
				<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Contact_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                      <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($contact_type) && count($contact_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                               <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($contact_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['contact_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_contact/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_contact/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_contact_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['contact_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_contact_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
			 	
			 	<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Document_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                      <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($document_type) && count($document_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($document_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['document_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_document/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_document/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_document_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['document_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_document_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
				
				
				<div class="table-format-contact">
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="30%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("Source_head");?></strong></td>
                      <td width="65%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add</a></td>
                      <?php /* <td width="3%" align="right"><a class="icon_btn special" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td> */ ?>
                    </tr>
					
                    <? if(!empty($source_type) && count($source_type)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                <th width="15%"><?php echo $this->lang->line('common_serial_no')?></th>
                                <th width="70%"><?php echo $this->lang->line('common_label_title')?></th>
								<th width="15%"><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($source_type as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?php echo  $row['source_type'] ?></td>
                                        
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_source/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_source/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('admin_base_url').$viewname; ?>/edit_source_record/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes($row['source_type'])?>','<?php echo $this->lang->line('')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_source_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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
