<?php 
    /*
        @Description: User Envelope list
        @Author: Mit Makwana
        @Date: 12-08-2014
    */
header('Cache-Control: max-age=900');
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
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
<?php

$viewname = $this->router->uri->segments[2];
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));

//$templates_data = $this->session->set_userdata('finlaOutputdata',$finlaOutput);
//$tmp_finaldata = $this->session->userdata('finlaOutputdata');

?>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Mail Out</h3>
      </div>
      <!-- /.portlet-header -->
      
		<div class="portlet-content">
       	<form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').'dashboard/generate_pdf1'?>" novalidate>
        <?php if(count($finlaOutputPrint) > 1){ ?>
        <input type="button" class="btn btn-secondary" value="Print All" onClick="Popup('0')" name="print" />
        <?php } ?>
        <!--<input type="submit" class="btn btn-secondary" value="Download" onClick="download('0')" name="submit" />-->
        <input type="hidden" name="allinteractionid" id="allinteractionid" value="<?=$allinteractionid?>" />
        <input type="hidden" name="interaction_id" id="interaction_id" value=""/>
        <input type="hidden" name="flag" id="flag" value=""/>
		<?php //pr("START : ".$finlaOutput." : END");exit; ?>
        <?php
			if(!empty($finlaOutputPrint))
			{
				for($i=0;$i<count($finlaOutputPrint);$i++)
				{
		?>
			<div class="table_large-responsive">
				<div class="col-sm-12">
					<ul class="mail_btns">
					<li><input type="button" class="btn btn-secondary" value="Print" onClick="Popup(<?=$interaction_id[$i]?>)" name="print" /></li><li>
						<input type="submit" class="btn btn-secondary" value="Download" name="download_<?=$interaction_id[$i]?>" onClick="download('<?=$interaction_id[$i]?>')" />
                        <input type="hidden" id="tmp_data_<?=$interaction_id[$i]?>" name="tmp_data_<?=$interaction_id[$i]?>" value="<?php echo htmlspecialchars($finlaOutputPrint[$i]); ?>" />
						<!--<input type="hidden" id="size_w_<?=$interaction_id[$i]?>" name="size_w_<?=$interaction_id[$i]?>" value="<?php echo $tmp_size_w[$j]; ?>" />
						<input type="hidden" id="size_h_<?=$interaction_id[$i]?>" name="size_h_<?=$interaction_id[$i]?>" value="<?php echo $tmp_size_h[$j]; ?>" />
                          <input type="hidden" id="post_mail_out_type" name="post_mail_out_type" value="<?=$post_data['mail_out_type']?$post_data['mail_out_type']:'';?>" />
                      
                        <input type="hidden" id="post_template" name="post_template" value="<?=$post_data['template_id']?$post_data['template_id']:'';?>" />
                         <input type="hidden" id="post_slt_category" name="post_slt_category" value="<?=$post_data['category_id']?$post_data['category_id']:'';?>" />
                        <input type="hidden" id="post_finalcontactlist" name="post_finalcontactlist" value="<?=$post_data['contact_id']?$post_data['contact_id']:'';?>" />-->
					</li>
					<!--<a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-primary">Cancel</a>-->
					<li> <a class="btn btn-primary" href="<?php  echo $this->config->item('user_base_url').'dashboard/letter_label_envelope_task';?>">Cancel</a></li>
					</ul>
				</div>
				<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
				 <div class="row dt-rt">
						<?php if(!empty($msg)){?>
							<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
							$newdata = array('msg'  => '');
							$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
				 </div>
				 <div id="common_div">
					 <div class="col-sm-12">
						<div id="divToPrint_<?=$interaction_id[$i]?>">
							<?php 
								echo $finlaOutput[$i];
								
							?>
						</div>	
					 </div>
				 </div>
				<input type="hidden" id="finlaOutputPrint_<?=$interaction_id[$i]?>" name="finlaOutputPrint_<?=$interaction_id[$i]?>" value="<?php echo htmlspecialchars($finlaOutputPrint[$i]); ?>" />
                
				</div>	
         	</div>
        <?php
				}
			}
		?>
        </form>
        <div id="my_print_op">
				<?php 
				if(isset($tmp_type) && $tmp_type == 'Letter'){ ?>
                 <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url').'mail_out/mail_out_print'?>"  data-validate="parsley" novalidate target="_blank">
                 	<div class="row">
                    <div class="col-sm-12">	
                    
                    <div class="col-ms-10">
                     <label for="text-input">Mail Out Type&nbsp;<span class="valid">*</span></label>
                    </div>
<div class="row">
<div class="col-sm-6">
                         <label class="checkbox left_box">
						 Label
						 	<div class="col-sm-1">
							
							<input type="radio" value="Label" class="" id="mail_out_type" name="mail_out_type" checked="checked" onclick="selecttemplate();" >  
							</div>
							 </label>
						</div>  
                        <div class="col-sm-6 clear">
                        <label class="checkbox left_box" >
						  Envelope
						 	<div class="col-sm-1">
							
							<input type="radio" name="mail_out_type" id="mail_out_type" class="" value="Envelope" onclick="selecttemplate();" > 
							</div>
							 </label></div></div>                       
						                       
					</div>
                    </div>
                    <div class="row">
                      <div class="col-sm-10">						
                        <label for="text-input">
                        <?=$this->lang->line('common_label_category');?>
                        </label>
                      </div>
                      <div class="col-sm-7">
                      
                        <select class="selectBox" name='slt_category' id='category' onchange="selecttemplate(this.value)"  data-required="true">
                          <option value="">Category</option>
                          <?php if(isset($category) && count($category) > 0){
                            foreach($category as $row1){
                                if(!empty($row1['id'])){?>
                          <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category']) && $editRecord[0]['template_category'] == $row1['id']){ echo "selected=selected"; } ?>><?php echo ucwords($row1['category']);?></option>
                          <?php 		}
                            }
                        } ?>
                        </select>
                        </div>
                        <div class="col-sm-7">
                      
              
                        <label for="text-input">Template&nbsp;<span class="valid">*</span></label>
                        <select id="template" name="template" class="form-control parsley-validated" data-required="true">
                           <option value="">Select Template</option>
                           <?php 
                           
                           if(!empty($template_data) && count($template_data) > 0){
                                foreach($template_data as $envelope_row){
                                
                                ?>
                                <option value="<?php echo $envelope_row['id'];?>" <?php if(!empty($envelope_row['template_name']) && $envelope_row['template_name'] == $template_name){ echo "selected=selected"; } ?>>
                                    <?php echo ucwords($envelope_row['template_name']);?>
                                </option>
                                <?php 
                                }
                            } ?>
                        </select>
                        </div>
                  </div>
                  <div class="col-sm-7">
                   <div class="center_btn"> <input type="hidden" name="sort_by" value="<?=!empty($sort_by)?$sort_by:'';?>" />
                   		<input type="hidden" value="1" class="" id="flag" name="flag">
                        <input type="hidden" id="finalcontactlist" name="finalcontactlist" value="<?=!empty($finalcontactlist)?$finalcontactlist:''?>" />
                        <input type="submit" class="btn btn-secondary" value="Print"  name="submitbtn" onclick="return checkcontactcount();" />
                   </div>
                  </div>
                 </form>
                <?php
				}
				?>
          </div>
        </div>
       </div>
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
 <!-- #content --> 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
$("select#category").multiselect({
		 multiple: false,
		 header: "Category",
		 noneSelectedText: "Category",
		 selectedList: 1
}).multiselectfilter();

function selectsubcategory(id){
 if(id!="-1"){
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $('#mail_out_type').change();
 }else{
   $("#template").html("<option value='-1'>Select Template</option>");
   $("select#template").multiselect('refresh').multiselectfilter();
   
 }
}

function selecttemplate()
{
	if($('input:radio[name=mail_out_type]:checked').val() == 'Label')
		var mail_type = 'Label';
	else if($('input:radio[name=mail_out_type]:checked').val() == 'Envelope')
		var mail_type = 'Envelope';
	
	if(mail_type == 'Envelope')
	{
		var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";				
	}else if(mail_type == 'Label'){
		var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";
	}else{
		var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";
	}
	
	if($('#mail_out_type').val() != '')
	{	
		$.ajax({  
			type: "post",
			url:pro_url,
			data: {'template_type':mail_type,slt_category:$("#category").val()},
				success: function(data)
				{	
					//alert(data);
					if(data.length != 0)
					{
						var myObject = eval(data);
						var html = '<option value="">Select Template</option>';
						for (var i=0;i< myObject.length;i++)
						{
							html += '<option value="'+myObject[i].id+'">'+myObject[i].template_name+'</option>';
						} 
					}
					else
						var html = '<option value="">Select Template</option>';
					$('#template').html(html);
				} 
		}); 
	}
}
			
	// Print Templates Page
	
    function Popup(id)
    {
		var tmp_data = '';
		if(id == '0')
		{
			var interaction_id = $("#allinteractionid").val();
			var myarray = Array();
			var splt = interaction_id.split(',')
			for(i=0;i<splt.length;i++)
				tmp_data += $('#divToPrint_'+splt[i]).html();
			//tmp_data += $('#divToPrint_12').html();
		}
		else
		{
			tmp_data = $('#divToPrint_'+id).html();
			var interaction_id = id;
		}
		$.ajax({  
				type: "post",
				url:'<?=base_url('user/dashboard/insert_data1')?>',
				data: {/*'post_mail_out_type':$('#post_mail_out_type').val(),'post_slt_category':$('#post_slt_category').val(),'post_template':$('#post_template').val(),'post_sort_by':$('#post_sort_by').val(),'post_finalcontactlist':$('#post_finalcontactlist').val(),*/interaction_id:interaction_id},
					success: function(data)
					{
						return false;
						//alert(data);	
					} 
			});
		if(tmp_data != '')
		{
			//var mywindow = window.open('', '+finlaOutputPrint+', 'height=400,width=600');
			var mywindow = window.open('', '', 'height=400,width=600');
			mywindow.document.write('<html><head><title>Data</title>');
			/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
			mywindow.document.write('<style type="text/css" media="print">@page{size: auto; margin: 0mm; }body{          background-color:#FFFFFF; ;margin: 15px;}</style>');
			mywindow.document.write('</head><body>');
			mywindow.document.write(tmp_data);
			mywindow.document.write('</body></html>');
	
			mywindow.print();
			mywindow.close();
			//alert("1");
			return false;
		}else{
			return false;
		}
		
    }
	
	function download(id)
	{
		var tmp_data = '';
		if(id == '0')
			$("#interaction_id").val($("#allinteractionid").val());
		else
			$("#interaction_id").val(id);
	}

    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	function checkmail_out_type()
	{
		var mail_type=$('#mail_out_type').val();
				
		if(mail_type == 'Envelope')
		{
			var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";				
		}else if(mail_type == 'Label'){
			var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";
		}else{
			var pro_url = "<?php echo $this->config->base_url(); ?>user/mail_out/get_envelope/"+mail_type+"/";
		}
		
		if($('#mail_out_type').val() != '')
		{	
			$.ajax({  
				type: "post",
				url:pro_url,
				data: {'template_type':mail_type},
					url:pro_url,				
					success: function(data)
					{	
						//alert(data);
						if(data.length != 0)
						{
							var myObject = eval(data);
							var html = '<option value="">Select Template</option>';
							for (var i=0;i< myObject.length;i++)
							{
								html += '<option value="'+myObject[i].id+'">'+myObject[i].template_name+'</option>';
							} 
						}
						else
							var html = '<option value="">Select Template</option>';
						$('#template').html(html);
					} 
			}); 
		}
	}
function checkcontactcount()
{
	//alert(JSON.stringify(popupemplist));
	if($("#category").val().trim() == '')
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select category."+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
		return false;		
	}
	else
		return true;
}
</script>

<?php if(!empty($tmp_size_w)){?>
<script type="text/javascript">
	var $pix = 96;
	var imgWidth = $("#my_print_op img").width();
	var div_size = <?=$tmp_size_w?> * 96;
	//alert(imgWidth);
	if(imgWidth !='')
	{
		if(imgWidth > div_size)
		{
			$('#divToPrint img').css({'width':'<?=$tmp_size_w?>in','height':'auto'});
			$('#my_print_op img').css({'width':'<?=$tmp_size_w?>in','height':'auto'});	
		}
	}
</script>
<?php } ?>