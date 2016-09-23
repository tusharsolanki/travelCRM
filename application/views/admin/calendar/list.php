<?php 
    /*
        @Description: Admin tips list
        @Author: Jayesh Rojasara
        @Date: 07-05-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php
$viewname = $this->router->uri->segments[2];
$this->agent_session = $this->session->userdata('agent_session');

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
                <p>Dashboard | <?php echo strtoupper($this->lang->line("label_property_head"))?></p>
				   
     			<div id="div_msg">
        			<?php 
            			if(isset($msg)) echo '<label class="error">'.urldecode ($msg).'</label>';
       				 ?>
            	</div>
        		
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
            		<tr class="iconment_title">
                      <td width="24%"><strong><?php echo $this->lang->line("common_label_manage")." ".$this->lang->line("label_property_head");?></strong></td>
                      <td width="71%">&nbsp;</td>
                      <td width="2%"><a class="icon_btn" href="<?=base_url('crm/'.$viewname.'/add_record');?>">Add</a></td>
                      <td width="3%" align="right"><a class="icon_btn" onclick="history.go(-1)" href="javascript:void(0)">Back</a> </td>
                    </tr>
					
                    <? if(!empty($datalist) && count($datalist)>0){ ?>
            		<tr>
                      <td colspan="4"><table class="iconment_title_in" width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                                <th><?php echo $this->lang->line('common_serial_no')?></th>
                                <th><?php echo $this->lang->line('label_property_name')?></th>
                                <th><?php echo $this->lang->line('label_property_address')?></th>                                <th><?php echo $this->lang->line('label_property_city')?></th>
                                <th><?php echo $this->lang->line('label_property_type')?></th>
                                <th><?php echo $this->lang->line('label_property_price')?></th>
                                <th><?php echo $this->lang->line('label_property_floor')?></th>
                                <th><?php echo $this->lang->line('label_property_photo')?></th> 
                                <th><?php echo $this->lang->line('label_type')?></th> 
                                <th><?php echo $this->lang->line('label_property_sale')?></th>                               				
                                <th><?php echo $this->lang->line('common_label_action')?></th>
							</tr>
                            <?php
							$i=!empty($this->router->uri->segments[3])?$this->router->uri->segments[3]+1:1;
							
							//echo "<pre>"; print_r($datalist); exit;
                                foreach($datalist as $row)
                                {   
                        ?>
                                    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
                                        
                                        <td class="text_capitalize"><?php echo  $i++?></td>
                                        <td class="text_capitalize"><?=!empty($row['property_name'])?ucfirst(strtolower($row['property_name'])):'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['address'])?ucfirst(strtolower($row['address'])):'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['city'])?ucfirst(strtolower($row['city'])):'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['type'])?$row['type']:'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['price'])?$row['price']:'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['floor_area'])?$row['floor_area']:'-'; ?></td>
                                        <td class="text_capitalize">
										<?php
                                        if(!empty($row['image_name']))
										{?>
											<img style="width:50px;" title="Property Images" src="<?php echo $this->config->item('property_upload_img_small').$row['image_name'];?>" width="50"  /> 
										<? }?>
                                      </td>
                                        <td class="text_capitalize"><?=!empty($row['property_type'])?$row['property_type']:'-'; ?></td>
                                        <td class="text_capitalize"><?=!empty($row['sale_rent'])?$row['sale_rent']:'-'; ?></td>
                                        <td width="80" class="text_capitalize">                                             
                                               <table cellpadding="0" cellspacing="0" class="actionButtons"  border="0" >
                                               		<tr>
                                                    <td width="20" valign="top">
                                                    
                                                    <? 
													
													if(!empty($row['status']) && $row['status']==1){ ?>
                                                    <a href="<?php echo  $this->config->item('crm_base_url').$viewname; ?>/unpublish_record/<?php echo  $row['id'] ?>">
<img title="Unpublish record" src="<?php echo $this->config->item('image_path');?>publish_icon.png" width="15"  /> 
</a> <? }else{ ?>

<a href="<?php echo  $this->config->item('crm_base_url').$viewname; ?>/publish_record/<?php echo  $row['id'] ?>">
<img title="Publish record" src="<?php echo $this->config->item('image_path');?>unpublish_icon.png" width="15"  /> 
</a>
<? } ?>
 </td>
 <td width="20" valign="top"><a href="<?php echo  $this->config->item('crm_base_url').$viewname; ?>/edit_record/tab1/<?php echo  $row['id'] ?>">
<img title="Edit record" src="<?php echo $this->config->item('image_path');?>edit_icon.png" width="15" height="20" /> 
</a> </td>

                                                        <td width="20" valign="top"><a href="javascript:void(0);" onclick="deletepopup('<?=addslashes(ucfirst(strtolower($row['property_name'])))?>','<?php echo $this->lang->line('tips_head_submodel')?>','<?php echo $this->config->item('crm_base_url').$viewname;?>/delete_record/<?php echo  $row['id'] ?>');"><img title="Delete record" src="<?php echo $this->config->item('image_path');?>delete_icon.png" width="15"  /></a></td>

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

<div id="dialog-confirm" style="display:none;">
    Do You want to delete Record <span id="delete_id"></span> From <span id="name"></span> ?
</div>
<script>
    $(document).ready(function(){
        $("#div_msg").fadeOut(4000); 
    });
</script>
