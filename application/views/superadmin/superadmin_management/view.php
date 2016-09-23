<?php
/*
    @Description: View Task 
    @Author: Mohit Trivedi
    @Date: 30-08-2014

*/?>

<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
?>
<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('task_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="row">
    <div class="col-md-12">
	<h3 class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </h3>
     <div class="portlet">
      <div class="portlet-header"> 
       <h3> <i class="fa fa-tasks"></i><?php echo $this->lang->line('task_view_head'); ?></h3>
	    
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         
         <div class="row tab-pane fade in active" id="home">
          
         
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="col-sm-8">
            <div class="row">
             <div class="col-sm-12">
              <label><?=$this->lang->line('task_label_name').':';?></label>
              <label><?=ucfirst($editRecord[0]['task_name']);?></label>
             
             </div>
            </div>
            <div class="row">
             <div class="col-sm-8">
              <label><?=$this->lang->line('common_label_desc').':';?></label>
              <label><?= !empty($editRecord[0]['desc'])?($editRecord[0]['desc']): '-';?></label>
			
             </div>
            </div>

 			 
            <div class='row'>
         <div class="col-sm-8">
         <label><?=$this->lang->line('taskdate_label_name').':';?></label>
         <label><?=date($this->config->item('common_date_format'),strtotime($editRecord[0]['task_date']));;?></label>
          </div>
          </div>
            
<div class="row form-group">
             <div class="col-sm-12 checkbox">
              <label class="">
              Is Task Completed
              <div class="float-left margin-left-15">
               <input type="checkbox" value="1" class=""  id="is_completed" name="is_completed" <?php if(!empty($editRecord[0]['is_completed']) && $editRecord[0]['is_completed'] == '1'){ echo 'checked="checked"'; }?> >
              </div>
              </label>
             </div>
            </div>
            
 <div class="row form-group">
 <div class="row">
 <div class="col-sm-3">
 <label class="">
 Reminders:
 </label>
 </div>
 <?php if(!empty($editRecord[0]['is_email']) && $editRecord[0]['is_email'] == '1'){?>
             <div class="col-sm-9">
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

		      
  <div class="row form-group">         
         <div class="col-sm-8">
          <label for="text-input"><?=$this->lang->line('common_label_assignuser').':';?></label>
         <table class="table table-striped table-bordered table-hover table-highlight table-checkable dataTable-helper dataTable datatable-columnfilter" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
          <thead>
           <tr role="row">
           <th width="50%"><?=$this->lang->line('user_label_name')?></th>
		   <th width="50%"><?=$this->lang->line('completed_name')?></th>
            </tr>
            
             <?php if(!empty($datalist) && count($datalist)>0){
					$i=!empty($this->router->uri->segments[4])?$this->router->uri->segments[4]+1:1;
                      foreach($datalist as $row){?>
                      <tr <? if($i%2==1){ ?>class="bgtitle" <? }?>>
                      <td class="">
                    <?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?> </td>
                    <td>
                      <input <?php if($row['is_completed'] == '1'){?> checked="checked" <?php } ?> type="checkbox" name="iscompleted"/>
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
           
          
         
          </form>
         
         </div>
  
        </div>
       </div>
      </div>
    
     </div>
    </div>
   </div>
  </div>
 </div>

