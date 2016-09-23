<div class="row tab-pane fade in active" id="home">
          
         
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           
            <div class="col-sm-12 form-group task_view">
			<div class="row">
           	 <div class="col-sm-3">
              <label><?=$this->lang->line('task_label_name');?></label>
              </div>
				 <div class="col-sm-1">
						:
				 </div>

			  <div class="col-sm-6">
			  <?=ucfirst($editRecord[0]['task_name']);?>
             </div>
            </div>
			
            <div class="row">
             <div class="col-sm-3">
              <label><?=$this->lang->line('common_label_desc');?></label>
			  </div>
			 <div class="col-sm-1">
					:
			 </div>

              <div class="col-sm-6">
				<?= !empty($editRecord[0]['desc'])?($editRecord[0]['desc']): '-';?>
             </div>
            </div>

 			 
            <div class='row form-group'>
         <div class="col-sm-3">
         <label><?=$this->lang->line('taskdate_label_name');?></label>
		 </div>
	 	 <div class="col-sm-1">
	    		:
		 </div>

		 <div class="col-sm-6">
         <?=date($this->config->item('common_date_format'),strtotime($editRecord[0]['task_date']));;?>
         </div>
         
         </div>
            
		<div class="row form-group">
              <div class="col-sm-3">
              <label class="">
              Task Status
              </label>
              </div>
			  <div class="col-sm-1">
			  :
			  </div>
              <div class="col-sm-6">
                <?php if(!empty($editRecord[0]['is_completed']) && $editRecord[0]['is_completed'] == '1')
						{
							echo 'Completed';
						}else{
							echo 'Pending';
						}?> 
            </div>
            </div>
 <div class="form-group">
 <div class="row">
 <div class="col-sm-3">
 <label class="">
 Reminders:
 </label>
 </div>
 <?php if(!empty($editRecord[0]['is_email']) && $editRecord[0]['is_email'] == '1'){?>
             <div class="col-sm-6">
             <label class="">
              Email Before   <?= $editRecord[0]['email_time_before']?> <?php if(!empty($editRecord[0]['email_time_type']) && $editRecord[0]['email_time_type'] == '1'){ echo "Hour"; }
			  else
			  {
			  echo "Day";
			  }
			  ?>
             </label>
             </div>
                         
<?php
 }
?>
             </div>

 <div class="row">
 <div class="col-sm-3">
 </div>         
 <?php if(!empty($editRecord[0]['is_popup']) && $editRecord[0]['is_popup'] == '1'){?>
             <div class="col-sm-9">
              <label class="">
              Pop-Up Before  <?= $editRecord[0]['popup_time_before']?> <?php if(!empty($editRecord[0]['popup_time_type']) && $editRecord[0]['popup_time_type'] == '1'){ echo "Hour"; }
			  else
			  {
			  echo "Day";
			  }
			  ?>
              </label>
             </div>
             
<?php
 }
?>
			</div>
             </div>
            </div>

		      
  <div class="form-group">         
         <div class="col-sm-8">
          <label for="text-input"><?=$this->lang->line('common_label_assignuser').':';?></label>
         <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           <th width="50%"><?=$this->lang->line('user_label_name')?></th>
		   <th width="50%"><?=$this->lang->line('common_label_istatus')?></th>
            </tr>
            
             <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
                      <tr <? if($i%2==1){ ?>class="bgtitle" <? }?>>
                      <td class="">
                    <?php if($row['admin_name']!='') { echo ucfirst(strtolower($row['admin_name']));}else{ echo ucfirst(strtolower($row['user_name']));}?> </td>
                    <td>
                       <?php if($row['is_completed'] == '1')
					   {
						    echo'Completed'; 
					   } 
					   else
					   {
						   echo'Pending';
					   }?> 
				      </td>
                      </tr>
                      <?php }} else {?>
                      
		  <tr>
		  	<td colspan="2" align="center"><?=$this->lang->line('admin_general_noreocrds')?></td>
		  </tr>
		  
		  <?php } ?>
            </tr>
            </thead>
            </table>

         </div>
   </div>      
		  
            <div class="row">
             
            </div>
               </div>