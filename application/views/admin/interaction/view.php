<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php
$data=$this->session->userdata($this->lang->line('common_admin_session_label'));
//pr($data);
require_once "phpuploader/include_phpuploader.php";
 
$viewname = $this->router->uri->segments[2];
$inte_plan_id = $this->router->uri->segments[4];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>

<script type="text/javascript">
var handlerurl='<?=base_url()?>phpuploader/ajax-attachments-handler.php';
//alert(handlerurl);
function CreateAjaxRequest()
{
	var xh;
	if (window.XMLHttpRequest)
		xh = new window.XMLHttpRequest();
	else
		xh = new ActiveXObject("Microsoft.XMLHTTP");
	
	xh.open("POST", handlerurl, false, null, null);
	xh.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
	//alert(xh.setRequestHeader);
	return xh;
}
</script>
<script type="text/javascript">
	
	var fileArray=[];
	
	function ShowAttachmentsTable()
	{
		var table = document.getElementById("filelist");
		while(table.firstChild)table.removeChild(table.firstChild);
		
		AppendToFileList(fileArray);
	}
	function AppendToFileList(list)
	{
		var table = document.getElementById("filelist");
		//alert(table);
		var site = $("#fileName").val();
		var val = site;
		
		for (var i = 0; i < list.length; i++)
		{
			var item = list[i];
			
			if(site != '')
			{
				site = site + ',' + item.FileName;
			}
			else
			{
				site = item.FileName;
			}
			
			var row=table.insertRow(-1);
			row.setAttribute("fileguid",item.FileGuid);
			row.setAttribute("filename",item.FileName);
			var td1=row.insertCell(-1);
			td1.innerHTML="<img src='<?=base_url()?>phpuploader/resources/circle.png' border='0'/>";
			var td2=row.insertCell(-1);
			td2.innerHTML=item.FileName;
			var td4=row.insertCell(-1);
			/*td4.innerHTML=" <a href='"+handlerurl+"?download="+item.FileGuid+"'>download</a> ";
			var td4=row.insertCell(-1);*/
			//<a href='"+handlerurl+"?download="+item.FileGuid+"'>download</a>
			td4.innerHTML="  &nbsp;&nbsp;<a href='javascript:void(0)' onclick='Attachment_Remove(this)'><img src='<?=base_url()?>images/stop.png' /></a>";
		}
		$("#fileName").val(site);
	}
	
	function Attachment_FindRow(element)
	{
		while(true)
		{
			if(element.nodeName=="TR")
				return element;
			element=element.parentNode;
		}
	}
	
	function Attachment_Remove(link)
	{
		var row=Attachment_FindRow(link);
		/*if(!confirm("Are you sure you want to delete '"+row.getAttribute("filename")+"'?"))
			return;*/
		
		$.ajax({
				type: "POST",
				url: '<?=base_url()?>admin/<?=$viewname;?>/delete_attachment',
				data: {
				result_type:'ajax',file_name:row.getAttribute("filename")
            },
			success: function(data){
				var str = $("#fileName").val();
				var myarray = str.split(",");
				var site = '';
				for(j=0;j<myarray.length;j++)
				{
					if(myarray[j] != data)
						site = site + myarray[j] + ",";
				}
				var cnt = site.lastIndexOf(",");
				var string = site.substring(0,cnt);
				$("#fileName").val(string);
				
			}
		});
		
		var guid=row.getAttribute("fileguid");
		
		var xh=CreateAjaxRequest();
		xh.send("delete=" + guid);

		var table = document.getElementById("filelist");
		table.deleteRow(row.rowIndex);
		
		for(var i=0;i<fileArray.length;i++)
		{
			if(fileArray[i].FileGuid==guid)
			{
				fileArray.splice(i,1);
				break;
			}
		}
	}
	
	function CuteWebUI_AjaxUploader_OnPostback()
	{
		var uploader = document.getElementById("myuploader");
		
		var guidlist = uploader.value;

		//alert(guidlist);
		var xh=CreateAjaxRequest();
		xh.send("guidlist=" + guidlist);

		//call uploader to clear the client state
		uploader.reset();

		if (xh.status != 200)
		{
			alert("http error " + xh.status);
			setTimeout(function() { document.write(xh.responseText); }, 10);
			return;
		}
//alert(xh.responseText);
		var list = eval(xh.responseText); //get JSON objects
		
		fileArray=fileArray.concat(list);
		
		AppendToFileList(list);
	}
	
</script>


<style>
.ui-multiselect{width:100% !important;}
</style>

<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Add New Template</h3>
      </div>
      <div class="modal-body view_page">
			
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<div id="content">
    <div id="content-header">
      <h1><?php echo $this->lang->line('interaction_header');?></h1>
    </div>
    <div id="content-container">
		
      <div class="">
        <div class="col-md-12">
		
          <div class="portlet">
            <div class="portlet-header">
              <h3> <i class="fa fa-table"></i>View Action</h3>
			<?php /*?>  <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)inte_plan_id" class="btn btn-secondary" title="Back">Back</a> </span><?php */?>
			<?php if(!empty($editRecord[0]['interaction_plan_id'])){?>
			  <span class="pull-right"><?php /*?><a title="Back" class="btn btn-secondary margin-left-5px" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname.'/'.$editRecord[0]['interaction_plan_id'];?>"><?php echo $this->lang->line('common_back_title')?></a><?php */?> <a class="margin-left-5px btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
              
               <span class="pull-right"><a title="Edit" class="btn btn-secondary " href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $inte_plan_id;?>"><?php echo $this->lang->line('common_edit_title')?></a> </span>
              
			  <?php }else {?>
			  <span class="pull-right"><a title="Back" class="btn btn-secondary margin-left-5px" href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname.'/'.$inte_plan_id;?>"><?php echo $this->lang->line('common_back_title')?></a> </span> 	
               <span class="pull-right"><a title="Edit" class="btn btn-secondary " href="<?php echo $this->config->item('admin_base_url')?><?php echo $viewname;?>/edit_record/<?php echo $inte_plan_id;?>"><?php echo $this->lang->line('common_edit_title')?></a> </span>
             
              		<?php } ?>
            </div>
            <!-- /.portlet-header -->
            
            <div class="portlet-content">
              <div class="table-responsive">
			   <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" data-validate="parsley" novalidate onsubmit="return validation();" >
                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                  <div class="row dt-rb">
                    <div class="col-xs-12">
                      <div class="text-center pull-right"> 
					  		<?php if(!empty($editRecord)){?> 
							
								<?php if(!empty($previous_interaction)){ ?>
									<a href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?=$previous_interaction?>"><i class="fa fa-backward btn btn-prev-next-avail arrow"></i></a>
								<?php }else{ ?>
									<a href="javascript:void(0);"><i class="fa fa-backward btn btn-tertiary arrow"></i></a>
								<?php } ?>
								
								<?php if(!empty($next_interaction)){ ?>
									<a href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?=$next_interaction?>"><i class="fa fa-forward btn btn-prev-next-avail arrow"></i></a>
								<?php }else{ ?>
									<a href="javascript:void(0);"><i class="fa fa-forward btn btn-tertiary arrow"></i></a>
								<?php } ?>
								
							<?php  } ?>
					  </div>
                    </div>
                    <div class="col-lg-8 col-sm-12">
                      <div class="row">
                        <div class="col-xs-12">
                        
                          <label for="validateSelect">Action Type:</label>
						 
                         
                         
						  	<?php  for($i=0;$i < count($interaction_type);$i++)
							{ ?>
                            <?php if(!empty($editRecord[0]['interaction_type']) && $editRecord[0]['interaction_type'] == $interaction_type[$i]['id']){ $name=$interaction_type[$i]['name']; }else{} ?>
							<?php } ?>
                          
						   <label for="text-input">
							<?php if(!empty($name)){ echo $name; }else{ echo "-"; }?>
                          </label>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <fieldset class="edit_main_div">
                            <legend class="edit_title">Action Details</legend>
                            <div class="cf"></div>
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                   Action Description :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <label for="text-input">
                                <?php if(!empty($editRecord[0]['description'])){echo $editRecord[0]['description'];}else{ echo "-"; }?>
                              </label>
                            </div>
                             </div>
                            
                            </div>
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                 Assigned To :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <label for="text-input">
                               <?php 
							   foreach($user_list as $row)
							   {
								  if(!empty($editRecord[0]['assign_to']) && $editRecord[0]['assign_to'] == $row['id'])
								  {
									if(!empty($row['user_name']))
										echo $row['user_name'].' ('.$row['email_id'].')';
									elseif(!empty($row['admin_name']))
										echo $row['admin_name'].' ('.$row['email_id'].')';
									} 
                               } ?>
                            <?php //if(!empty($user_name)){ echo $user_name; }else{ echo "-"; }?>
                              </label>
                            </div>
                             </div>
                             </div>
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                 Schedule :
                                  </label>
                                </div>
                                <? if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '1'){ ?>
                                    <div class="col-sm-3 form-group">
                                    <label for="text-input">
                                    <?php  
                                    echo $editRecord[0]['number_count'];
                                    ?>
                                    </label>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                    <label for="text-input">
                                    <?php  
                                    echo $editRecord[0]['number_type'];
                                    ?>
                                    </label>
                                    </div>
                                    <div class="col-sm-3">
                                    <label for="text-input">From Plan Start Date</label>
                                    </div>
                                <? } ?>
                                 <? if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '2'){ ?>
                                    <div class="col-sm-3 form-group">
                                    <label for="text-input">
                                    <?php  
                                    echo $editRecord[0]['number_count'];
                                    ?>
                                    </label>
                                    </div>
                                    <div class="col-sm-2 form-group">
                                    <label for="text-input">
                                    <?php  
                                    echo $editRecord[0]['number_type'];
                                    ?>
                                    </label>
                                    </div>
                                    <div class="col-sm-2">
                                    <label for="text-input">After a Preceding Action</label>
                                    </div>
                                    <div class="col-sm-2">
                                     	<?php  for($i=0;$i < count($interaction_list);$i++)
										{ ?>
										<?php if(!empty($editRecord[0]['interaction_id']) && $editRecord[0]['interaction_id'] == $interaction_list[$i]['id']){ $description=$interaction_list[$i]['description']; }else{} ?>
										<?php } ?>
                                    <label for="text-input"> <?php if(!empty($description)){ echo $description; }else{ echo "-"; }?></label>
                                    </div>
                                <? } ?>
                                 <? if(!empty($editRecord[0]['start_type']) && $editRecord[0]['start_type'] == '3'){ ?>
                                 <div class="col-sm-3 form-group">
                                    <label for="text-input">
                                    <?php if(!empty($editRecord[0]['start_date']) && $editRecord[0]['start_date'] != '0000-00-00' && $editRecord[0]['start_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['start_date'])); }?>
                                    </label>
                                    </div>
                               
                                <? } ?>
                             </div>
                              
                            </div>
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                 Priority :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <label for="text-input">
                               <?php if(!empty($editRecord[0]['priority'])){ echo $editRecord[0]['priority']; }else{echo '-';}?>
                              </label>
                            </div>
                             </div>
                            </div>
                            <div class="col-xs-12" style="display:none;">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                Drop From Action List:
                                  </label>
                                </div>
              				  <div class="col-sm-3 form-group">
                              <label for="text-input">
                               <?php if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '1'){ echo 'Do Not Drop Off'; }
							   if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '2'){ echo 'Drop After';}
							   if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '3'){ echo 'Do Not Drop Off';}
							   ?>
                              </label>
                            </div>
                            <div class="col-sm-2 form-group">
                              <label for="text-input">
                               <?php 
							    if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '2'){ 
							  		 if(!empty($editRecord[0]['drop_after_day'])){ echo $editRecord[0]['drop_after_day'];}
								}
								 if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '3'){ 
							  		if(!empty($editRecord[0]['drop_after_date']) && $editRecord[0]['drop_after_date'] != '0000-00-00' && $editRecord[0]['drop_after_date'] != '1970-01-01'){ echo date($this->config->item('common_date_format'),strtotime($editRecord[0]['drop_after_date'])); }
								}
							   ?>
                              </label>
                            </div>
                            <div class="col-sm-3 form-group">
                              <label for="text-input">
                               <?php 
							    if(!empty($editRecord[0]['drop_type']) && $editRecord[0]['drop_type'] == '2'){ 
							  		 echo ' Days From Schedule Date ';
								}
								
							   ?>
                              </label>
                            </div>
                             </div>
                            </div>
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                                 Notes :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <label for="text-input">
                               <?php if(!empty($editRecord[0]['interaction_notes'])){ echo $editRecord[0]['interaction_notes']; }?>
                              </label>
                            </div>
                             </div>
                            </div>
                                                      
                          </fieldset>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xs-12">
                          <fieldset class="edit_main_div template_main_div">
                            <legend class="edit_title">Template</legend>
                            <div class="cf"></div>
							<div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                               		 <?=$this->lang->line('common_label_category');?> :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <?php  for($i=0;$i < count($category);$i++)
							{ ?>
                            <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $category[$i]['id']){ $category_name=$category[$i]['category']; }else{} ?>
							<?php } ?>
                          
						   <label for="text-input">
							<?php if(!empty($category_name)){ echo $category_name; }else{ echo "-"; }?>
                          </label>
                           
                            </div>
                             </div>
                            </div>
                            
                            <div class="col-xs-12">
                            <div class="row lftrgt">
                           	  <div class="col-sm-3">
                                  <label for="text-input">
                               		Template :
                                  </label>
                                </div>
              				  <div class="col-sm-5 form-group">
                              <?php  for($i=0;$i < count($template_list);$i++)
							{ ?>
                            <?php if(!empty($editRecord[0]['template_name']) && $editRecord[0]['template_name'] == $template_list[$i]['id']){ $template_name=$template_list[$i]['template_name']; }else{} ?>
							<?php } ?>
                          
						   <label for="text-input">
							<?php if(!empty($template_name)){ echo $template_name; }else{ echo "-"; }?>
                          </label>
                           
                            </div>
                             </div>
                            </div>
                           <!-- <div class="col-xs-12 mrgb2">
                              <label for="validateSelect">Template:</label>
                              <select  class="form-control parsley-validated" name="slt_template_name" id="slt_template_name">
                                <option value="">Select Template</option>
                              </select>
                            </div>
							<div class="">
								<div class="col-sm-12 topnd_margin">
                                <a href="#basicModal" class="text_color_red text_size add_new_template" id="basicModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add Template </a>
									
								</div>
							</div>-->
                          </fieldset>
                        </div>
                      </div>
                    </div>
					<?php
                    if(!empty($editRecord[0]['interaction_type']) && ($editRecord[0]['interaction_type'] == '3' || $editRecord[0]['interaction_type'] == '6'))
					?>
					<div class="col-lg-4 col-sm-12 show_email_sms_div">
						<div class="row">
                        <div class="col-xs-12">
                          <fieldset class="edit_main_div">
                            <legend class="edit_title">Email/SMS</legend>
							 
							 <div class="col-xs-12 mrgtop1">
                              <label for="text-input" style="display:block;"><?=$this->lang->line('send_auto');?>:</label>
                              <div class="row">
                                
								<div class="col-sm-4">
                                  <label>
                                     <?php if(isset($editRecord[0]['send_automatically']) && $editRecord[0]['send_automatically'] == '1'){ echo 'Yes'; }else{echo 'No';}?></label>
                                </div>
                                </div>
                            </div>
							 
							 <div class="col-sm-12 mrgtop1 div_only_email">
							 	<label for="text-input"><?=$this->lang->line('email_attach');?></label>
								<div id="common_div">
								 <?php if(!empty($attachment) && count($attachment)>0){
					  foreach($attachment as $row){?>
						<?php if(!empty($row['attachment_name'])){?><a target="_blank" href="<?php echo base_url()."uploads/attachment_file/".$row['attachment_name']; ?>"><?php echo ucfirst($row['attachment_name'])?></a><br/><?php } ?>
          <?php } } else { echo $this->lang->line('admin_general_noreocrds');}?>
							 </div>
							 </div>
							 <div class="col-xs-12 mrgtop1 div_only_email">
                              <label for="text-input" style="display:block;"><?=$this->lang->line('include_sign');?>:</label>
                              <div class="row">
                                
								<div class="col-sm-4">
                                <label>
                                     <?php if(isset($editRecord[0]['include_signature']) && $editRecord[0]['include_signature'] == '1'){ echo 'Yes'; }else{echo 'No';}?></label>
                                  <label>
                                </div>
                              </div>
                            </div>
							
						  </fieldset>
						</div>
						</div>
					</div>
                  </div>
                  
                  
              </div>
              <div class="col-sm-12 text-center margin-top-10"> 
				   <input type="hidden" name="interaction_id" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>" >
				   <input type="hidden" name="email_campaign_id" id="email_campaign_id" value="<?=!empty($email_campaign_id) ?$email_campaign_id:'';?>" >
				   <input type="hidden" name="plan_id" value="<?php if(!empty($editRecord[0]['interaction_plan_id'])){ echo $editRecord[0]['interaction_plan_id']; }else{ echo $plan_id; }?>" >
				   <input type="hidden" name="fileName" value="" id="fileName"  />
				  <!--<input type="submit" class="btn btn-secondary" value="Save Action" name="submitbtn" />
				  <a class="btn btn-primary" href="javascript:history.go(-1);" title="Cancel">Cancel</a>-->
                </div>
			  </form>
              <!-- /.table-responsive --> 
              
            </div>
            <!-- /.portlet-content --> 
            
          </div>
        </div>
      </div>
    </div>
    <!-- #content-header --> 
    
    <!-- /#content-container --> 
    
  </div>
  
<script type="text/javascript">
$(document).ready(function(){
	$( "#rad_drop_after_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
	$( "#rad_start_type_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
 
   //Datepicker show on select redio button
  $(".mrgt3").hide();
    $('input[type="radio"]').click(function(){
      //alert($(this).attr("value"));
      if($(this).attr("value")=="2"){
          $(".mrgt3").show();
      }else{
          $(".mrgt3").hide();
      }
  });
});
function validation()
{
	 var data = $('input[name=rad_start_type]:checked', '#<?php echo $viewname;?>').val();
	 var drop_data = $('input[name=rad_drop_type]:checked', '#<?php echo $viewname;?>').val();
	
		var txt_interaction_stat_1=$('#txt_interaction_stat_1').val();
		var txt_interaction_stat_2=$('#txt_interaction_stat_2').val();
		var txt_interaction_stat_3=$('#rad_start_type_date').val();
		var slt_interaction_stat_2=$('#slt_interaction_stat_2').val();
		
		var txt_drop_after_day=$('#txt_drop_after_day').val();
		var rad_drop_after_date=$('#rad_drop_after_date').val();
		
	if(data == '1' && txt_interaction_stat_1 == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please Enter Day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_interaction_stat_1').focus();
									}},  }});
		//alert('Please Enter Day');
		return false;
	}
	if(data == '2' && txt_interaction_stat_2 == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please Enter Day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_interaction_stat_2').focus();
									}},  }});
		//alert('Please Enter Day');
		return false;
	}
	if(data == '2' && slt_interaction_stat_2 == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please select Action "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#slt_interaction_stat_2').focus();
									}},  }});
		return false;
	}
	if(data == '3' && txt_interaction_stat_3 == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please Select Date "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#rad_start_type_date').focus();
									}},  }});
		//$.confirm({'title': 'Alert','message': " <strong> Please Select Date"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//alert('Please Select Date');
		//$('#rad_start_type_date').focus();
		 return false;
	}
	if(drop_data == '2' && txt_drop_after_day == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please Enter Day "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#txt_drop_after_day').focus();
									}},  }});
//		alert('Please Enter Day');
		//$.confirm({'title': 'Alert','message': " <strong> Please Enter Day"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//$('#txt_drop_after_day').focus();
		 return false;
	}
	if(drop_data == '3' && rad_drop_after_date == '')
	{ 
		$.confirm({'title': 'Alert','message': " <strong> Please Select Date "+"<strong></strong>",
						'buttons': {'ok'	: {
								'class'	: 'btn_center alert_ok',	
								'action': function(){
										 $('#rad_drop_after_date').focus();
									}},  }});
		//$.confirm({'title': 'Alert','message': " <strong> Please Select Date"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		//alert('Please Select Date');
		//$('#rad_drop_after_date').focus();
		return false;
	}
}
</script>
<script type="text/javascript">

	$("select#category").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
	}).multiselectfilter();
	
	$("select#subcategory").multiselect({
		 multiple: false,
		 header: "Sub-Category",
		 noneSelectedText: "Sub-Category",
		 selectedList: 1
	}).multiselectfilter();
	
	$("#subcategory").change(function() 
	{
		selecttemplate($("#slt_interaction_type").val());
		
	});
	
	function selectsubcategory(id){
	 if(id!="-1"){
	   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
	   loadData('category',id);
	 }else{
	   $('#slt_template_name').empty();
	   	var option = $(
		'<option />');
		option.attr('value', '-1').text('Select Template');
		$('#slt_template_name').append(option);
	   	$("#subcategory").html("<option value='-1'>Sub-Category</option>");
	  	$("select#subcategory").multiselect('refresh').multiselectfilter();
	 }
	}
	
	function loadData(loadType,loadId){
	 /* $.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_subcategory';?>",
		 dataType: 'json',
		 data: {loadType:loadType,loadId:loadId},
		 cache: false,
		 async:false,
		 success: function(result){
			 
			 var selectedsubcat = 0;
			 
			 <?php if(!empty($editRecord[0]['template_subcategory'])){ ?>
						
				selectedsubcat = '<?=$editRecord[0]['template_subcategory']?>';
				
				<?php } ?>
			 
			$.each(result,function(i,item){ 
						var option = $('<option />');
						option.attr('value', item.id).text(this.category);
						
						if(selectedsubcat == item.id)
							option.attr("selected","selected");
						
						$('#subcategory').append(option);
				});
			$("select#subcategory").multiselect('refresh').multiselectfilter();
			selecttemplate($("#slt_interaction_type").val());				
		 }
	   });*/
	   selecttemplate($("#slt_interaction_type").val());
	}
	
	<?php if(!empty($editRecord[0]['template_category'])){ ?>

		selectsubcategory('<?=$editRecord[0]['template_category']?>');
	
	<?php } ?>
	
	function selecttemplate(id,val){
		
		if(id == 7)
			$('.template_main_div').css('display','none');
		else
			$('.template_main_div').css('display','');
			
			
		if(id == 3 || id == 6)
		{
			$('.show_email_sms_div').css('display','');
			if(id == 3)
				$('.div_only_email').css('display','none');
			else
				$('.div_only_email').css('display','');
		}
		else
			$('.show_email_sms_div').css('display','none');
			
		categoryid = $('#category').val();
		subcategoryid = $('#subcategory').val();
		
		//alert(subcategoryid);
	
		$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_selecttemplate';?>",
		 dataType: 'json',
		 data: {loadId:id,'category':categoryid,'subcategory':subcategoryid,selected:val},
		 cache: false,
		 success: function(result){
		 
		 	if(result != null)
			{
				var selectedsubcat = 0;
				 
				<?php if(!empty($editRecord[0]['template_name'])){ ?>
							
					selectedsubcat = '<?=$editRecord[0]['template_name']?>';
					
				<?php } ?>
				
				$('#slt_template_name').empty();
				var option = $('<option />');
				option.attr('value', '').text('Select Template');
				$('#slt_template_name').append(option);
				 
				$.each(result,function(i,item){ 
							var option = $('<option />');
							option.attr('value', item.id).text(this.template_name);
							if(val == "selected" && i == 0)
								option.attr("selected","selected");
							else if(selectedsubcat == item.id && val != "selected")
								option.attr("selected","selected");
							
							$('#slt_template_name').append(option);
					});
				//$("select#subcategory").multiselect('refresh').multiselectfilter();
			
			}
			else
			{
				$('#slt_template_name').empty();
				var option = $('<option />');
				option.attr('value', '').text('Select Template');
				$('#slt_template_name').append(option);
			}
							
		 }
	   });
	   
	}
	
	function selectcategory(id,val)
	{
		$.ajax({
		 type: "POST",
		 url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_selecttemplate';?>",
		 dataType: 'json',
		 data: {loadId:id,'category':categoryid,'subcategory':subcategoryid,selected:val},
		 cache: false,
		 success: function(result){
		 
		 	if(result != null)
			{
				$.each(result,function(i,item){
							if(val == "selected" && i == 0)
								$('#category option[value="' + item.template_category + '"]').prop('selected', true);
				});
				$("select#category").multiselect('refresh').multiselectfilter();
				selecttemplate(id,val);
			}		
		 }
	   });	
	}
	
	<?php if(!empty($editRecord[0]['interaction_type'])){ ?>

		selecttemplate('<?=$editRecord[0]['interaction_type']?>');
	
	<?php }else{ ?>
	
		selecttemplate($("#slt_interaction_type").val());
	
	<?php } ?>
		
</script>


<script type='text/javascript'>
/*function CuteWebUI_AjaxUploader_OnTaskComplete(task)
{
	//var div=document.createElement("DIV");
	//div.innerHTML=task.FileName + " is uploaded!";
	//document.body.appendChild(div);
	//alert(task.FileName);
	var site = document.getElementById("fileName");
	var vali=site.value;
	//alert(vali);
	if(vali != '')
	{site.value=vali+','+task.FileName;}
	else
	{site.value=task.FileName;}
		setTimeout(function(){$('.AjaxUploaderQueueTable tbody tr:last').find('td:last').after( "<td><a href='javascript:void(0);' value='"+task.FileName+"' ><img src='<?=base_url()?>images/stop.png' /></a></td>" )}, 1000);
		
	//$('#fileName').val(task.FileName);
}

$('.AjaxUploaderQueueTable tbody tr td a').live( "click", function() {
		//var file_name = $(this).attr("value");
		 $('.AjaxUploaderQueueTable tbody tr:last').remove();
		$.ajax({
			type: "POST",
			url: '<?=base_url()?>admin/emails/delete_attachment',
			data: {
			result_type:'ajax',file_name:$(this).attr("value")
		},
		success: function(data){
				var str = $("#fileName").val();
				var myarray = str.split(",");
				var site = '';
				for(j=0;j<myarray.length;j++)
				{
					if(myarray[j] != data)
						site = site + myarray[j] + ",";
				}
				var cnt = site.lastIndexOf(",");
				var string = site.substring(0,cnt);
				$("#fileName").val(string);
				
			}
		});
		//$(this).closest('.AjaxUploaderQueueTableRow').remove();
		return false;
});*/

function isNumberKey(evt)
{
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if(charCode > 31 && (charCode < 48 || charCode > 57))
		return false;

	return true;
}

$('body').on('click','.add_new_template',function(e){
	
	var templatetype = $('#slt_interaction_type').val();
	var modulelink = '';
	
	
	if(templatetype == 1)
		modulelink = 'label_library/add_record';
	else if(templatetype == 2)
		modulelink = 'envelope_library/add_record';
	else if(templatetype == 3)
		modulelink = 'sms_texts/add_record';
	else if(templatetype == 4)
		modulelink = 'phonecall_script/add_record';
	else if(templatetype == 5)
		modulelink = 'letter_library/add_record';
	else if(templatetype == 6)
		modulelink = 'email_library/add_record';
	$(".view_page").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	
		
	if(modulelink != '')
	{
		var frameSrc = '<?php echo $this->config->item('admin_base_url')?>'+modulelink+'/iframe';
		$('iframe').attr("src",frameSrc);
		$(".view_page").html('<iframe src="'+frameSrc+'" style="zoom:0.60" frameborder="0" height="530" width="99.6%"></iframe>');
	}
	/*if(modulelink != '')
		window.open('<?php echo $this->config->item('admin_base_url')?>'+modulelink,'_blank');*/
	
});

</script>