<?php 
    /*
        @Description: Admin SMS list
        @Author: Niral Patel
        @Date: 6-1-2015
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
?>
<?php if(isset($sortby) && $sortby == 'asc'){ $sorttypepass = 'desc';}else{$sorttypepass = 'asc';}?>

<table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
  <thead>
    <tr role="row">
      <?php if(!empty($this->modules_unique_name) && in_array('sms_auto_responder_delete',$this->modules_unique_name)){?>
            <th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="" width="5%">
             <div class="text-center">
              <input type="checkbox" class="selecctall" id="selecctall">
             </div>
            </th>
            <? } ?>
      <th width="25%" data-direction="desc" data-sortable="true" data-filterable="true" <?php if(isset($sortfield) && $sortfield == 'message'){if($sortby == 'asc'){echo "class = 'sorting_desc'";}else{echo "class = 'sorting_asc'";}} ?> role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="descending" aria-label="Rendering engine: activate to sort column ascending"><a href="javascript:void(0);" onclick="applysortfilte_contact('message','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('label_message')?>
        </a></th>
      <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'from_number'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('from_number','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('from_number')?>
        </a></th>
      <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'to_number'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('to_number','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('to_number')?>
        </a></th>
      <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'from_city'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('from_city','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('common_label_city')?>
        </a></th>
      <th width="12%" class="hidden-xs hidden-sm <?php if(isset($sortfield) && $sortfield == 'response_date'){if($sortby == 'asc'){echo "sorting_desc";}else{echo "sorting_asc";}} ?> " data-filterable="false" role="columnheader" rowspan="1" colspan="1" aria-label="Engine version"><a href="javascript:void(0);" onclick="applysortfilte_contact('response_date','<?php echo $sorttypepass;?>')">
        <?=$this->lang->line('response_date')?>
        </a></th>
      <? if(in_array('sms_auto_responder_edit',$this->modules_unique_name) || in_array('sms_auto_responder_delete',$this->modules_unique_name)){ ?>
             <th width="10%" class="hidden-xs hidden-sm sorting_disabled" data-filterable="true" role="columnheader" rowspan="1" colspan="1" aria-label="CSS grade"><?php echo $this->lang->line('common_label_action')?></th>
             <? } ?>
    </tr>
  </thead>
  <tbody role="alert" aria-live="polite" aria-relevant="all">
    <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){
						   if(!empty($row['message']) && strlen($row['message']) > 17)
            				{
						  	 $smsdel = substr($row['message'],0,20);
							}
							else
							{$smsdel = $row['message'];}
						  ?>
    <tr <? if($i%2==1){ ?>class="bgtitle" <? }?> >
     <?php if(!empty($this->modules_unique_name) && in_array('sms_auto_responder_delete',$this->modules_unique_name)){?>
							<td class="">
                              <div class="text-center">
                                  <input type="checkbox" class="mycheckbox" name="check[]" value="<?php echo  $row['id'] ?>">
							  </div>
                            </td>
                            <? } ?>
      <td class="hidden-xs hidden-sm ">
	   <a class="view_form_btn" data-toggle="modal" title="Template"  href="#basicModal" data-id="<?=$row['id']?>">
			<?php
            if(!empty($row['message']) && strlen($row['message']) > 57)
            {
                $sms = substr($row['message'],0,60);
				
                echo $sms."...";
            }
            else
                echo ucfirst(strtolower($row['message']));
            ?>
            </a>
            <div id="message_<?=$row['id']?>" style="display:none;">
				<?=!empty($row['message'])?ucfirst(strtolower($row['message'])):'';?>
            </div>
	 </td>
      <td class="hidden-xs hidden-sm "><?=!empty($row['from_number'])?$row['from_number']:'';?></td>
      <td class="hidden-xs hidden-sm "><?=!empty($row['to_number'])?$row['to_number']:'';?></td>
      <td class="hidden-xs hidden-sm "><?=!empty($row['from_city'])?ucfirst(strtolower($row['from_city'])):'';?></td>
      <td class="hidden-xs hidden-sm "><?php if($row['response_date']=='0000-00-00 00:00:00'){ echo '';}else
							{?>
        <?=!empty($row['response_date'])?date($this->config->item('common_datetime_format'),strtotime($row['response_date'])):'';}?>
         <input type="hidden" id="sortfield" name="sortfield" value="<?php if(isset($sortfield)) echo $sortfield;?>" />
        <input type="hidden" id="sortby" name="sortby" value="<?php if(isset($sortby)) echo $sortby;?>" />
        </td>
        <?php if(!empty($this->modules_unique_name) && in_array('sms_auto_responder_delete',$this->modules_unique_name)){?>
      <td class="hidden-xs hidden-sm text-center"><button class="btn btn-xs btn-primary" title="Delete Record" onclick="deletepopup1('<?php echo  $row['id'] ?>','<?php echo rawurlencode(ucfirst(strtolower($smsdel))) ?>');"><i class="fa fa-times"></i></button>
       </td>
       <? } ?>
    </tr>
    <?php } } else {?>
    <tr>
      <td colspan="10" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row dt-rb" id="common_tb">
  <div class="col-sm-6">
    <div class="dataTables_paginate paging_bootstrap float-right">
      <div id="DataTables_Table_0_length" class="dataTables_length row pagignation_margin_right">
        <label>
          <select name="DataTables_Table_0_length" size="1" aria-controls="DataTables_Table_0" onchange="changepages();" id="perpage">
            <option value="">
            <?=$this->lang->line('label_sms_response_per_page');?>
            </option>
            <option <?php if(!empty($perpage) && $perpage == 10){ echo 'selected="selected"';}?> value="10">10</option>
            <option <?php if(!empty($perpage) && $perpage == 25){ echo 'selected="selected"';}?> value="25">25</option>
            <option <?php if(!empty($perpage) && $perpage == 50){ echo 'selected="selected"';}?> value="50">50</option>
            <option <?php if(!empty($perpage) && $perpage == 100){ echo 'selected="selected"';}?> value="100">100</option>
          </select>
        </label>
      </div>
    </div>
  </div>
  <div class="col-sm-6">
    <?php 
			 
			if(isset($pagination))
			{
				echo $pagination;
			}
		  	?>
  </div>
</div>

