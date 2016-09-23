<?php if(!empty($contacts)){ 
$viewname = $this->router->uri->segments[2];
$path = $viewname.'/insert_merge_data';?>
<form accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" enctype="multipart/form-data" method="post">
<div class="col-sm-12">
   
   <div class="table-responsive2">
	<table aria-describedby="DataTables_Table_0_info" id="DataTables_Table_0" class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter">
	<thead>
	  <tr role="row">
		<th width="15%">CRM Field</th>
		<?php for($i=0;$i<count($contacts);$i++){ ?>
		<input type="hidden" name="old_contacts[]" value="<?=$contacts[$i]['id'];?>" />
		<th width="<?=(85/count($contacts))?>%">RECOURSE <?=$i+1;?></th>
		<?php } ?>
	  </tr>
	</thead>
	<tbody aria-relevant="all" aria-live="polite" role="alert">
	  <?php 
	  	if(!empty($crmfields)){
	  		for($f=0;$f<count($crmfields);$f++){ ?>
			  <tr <?php if($f%2==0){?> class="odd" <?php }else{?> class="even" <?php }?> >
				<td class="sorting_1"><?=$crmfields[$f][0]?></td>
				
				<?php for($i=0;$i<count($contacts);$i++){ ?>
					<td class="sorting_2">
					  <div class="form-group1">
						<div class="radio checkbox1">
						  <label class="">
						  <div>
						  	<?php if($crmfields[$f][1] == 'prefix'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-prefix" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['prefix']?>" >
							  <label><?=$contacts[$i]['prefix']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'first_name'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-first_name" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['first_name']?>" >
							  <label><?=$contacts[$i]['first_name']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'middle_name'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-middle_name" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['middle_name']?>" >
							  <label><?=$contacts[$i]['middle_name']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'last_name'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-last_name" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['last_name']?>" >
							  <label><?=$contacts[$i]['last_name']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'company_name'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-company_name" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['company_name']?>" >
							  <label><?=$contacts[$i]['company_name']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'address1'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-address1" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['address_line1']?>" >
							  <label><?=$contacts[$i]['address_line1']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'address2'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-address2" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['address_line2']?>" >
							  <label><?=$contacts[$i]['address_line2']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'city'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-city" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['city']?>" >
							  <label><?=$contacts[$i]['city']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'state'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-state" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['state']?>" >
							  <label><?=$contacts[$i]['state']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'contact_type'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-contact_type" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['contact_id_list']?>" >
							  <label><?=$contacts[$i]['contacttype']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'contact_source'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-contact_source" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['contact_source']?>" >
							  <label><?=$contacts[$i]['sourcename']?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'islead'){ ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-islead" <?=($i==0)?'checked="checked"':'';?> value="<?=$contacts[$i]['is_lead']?'1':'0'?>" >
							  <label><?=$contacts[$i]['is_lead']?'Yes':'No'?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'email1'){
								$emaildata = explode(",",$contacts[$i]['email']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-default-email" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($emaildata[0])?$emaildata[0]:''?>" >
							  <label><?=!empty($emaildata[0])?$emaildata[0]:''?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'email2'){
								$emaildata = explode(",",$contacts[$i]['email']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-email2" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($emaildata[1])?$emaildata[1]:''?>" >
							  <label><?=!empty($emaildata[1])?$emaildata[1]:''?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'email3'){
								$emaildata = explode(",",$contacts[$i]['email']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-email3" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($emaildata[2])?$emaildata[2]:''?>" >
							  <label><?=!empty($emaildata[2])?$emaildata[2]:''?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'phone1'){
								$phonedata = explode(",",$contacts[$i]['phone']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-default-phone" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($phonedata[0])?$phonedata[0]:''?>" >
							  <label><?=!empty($phonedata[0])?$phonedata[0]:''?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'phone2'){
								$phonedata = explode(",",$contacts[$i]['phone']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-phone2" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($phonedata[1])?$phonedata[1]:''?>" >
							  <label><?=!empty($phonedata[1])?$phonedata[1]:''?></label>
							</div>
							<?php }elseif($crmfields[$f][1] == 'phone3'){
								$phonedata = explode(",",$contacts[$i]['phone']);
							 ?>
							<div class="">
							  <input type="radio" data-required="true" class="" name="radio-phone3" <?=($i==0)?'checked="checked"':'';?> value="<?=!empty($phonedata[2])?$phonedata[2]:''?>" >
							  <label><?=!empty($phonedata[2])?$phonedata[2]:''?></label>
							</div>
							<?php } ?>
						  </div>
						  </label>
						</div>
					  </div>
					</td>
				<?php } ?>
				
			  </tr>
		<?php } ?>
	<?php } ?>	
	</tbody>
  </table>
 
  </div>
</div>
<div class="col-sm-12 text-center mrgb4">
	<button type="submit" class="btn btn-success">Merge</button>
</div>
</form>
<?php }else{ ?>

<div class="col-sm-12 text-center mrgb4">
	No data found.
</div>

<?php } ?>