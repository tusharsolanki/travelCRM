<?php
/*
	@Description: Emails campaign add/edit page
    @Author: Sanjay Chabhadiya
    @Date: 06-08-2014

*/
require_once "phpuploader/include_phpuploader.php";
//require_once "phpuploader/ajax-attachments-handler.php";

$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
else
	$tabid = 1;
	
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
if(isset($insert_data))
{
$formAction ='insert_data'; 
}
$path = $viewname.'/'.$formAction;
?>

<script type="text/javascript">
var handlerurl='<?=base_url()?>phpuploader/ajax-attachments-handler.php';
//alert(handlerurl);
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

function doStart()
{
	var uploadobj = document.getElementById('myuploader');
	if (uploadobj.getqueuecount() > 0)
	{
		uploadobj.startupload();
		/*$("#send_now").attr("disabled","disabled");
		$("#save_campaign").attr("disabled","disabled");
		$("#save_template").attr("disabled","disabled");*/
	}
	else
	{
		alert("Please browse files for upload");
	}
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
		/*$("#send_now").removeAttr("disabled");
		$("#save_campaign").removeAttr("disabled");
		$("#save_template").removeAttr("disabled");*/
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
				url: '<?=base_url()?>user/<?=$viewname;?>/delete_attachment',
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
$('.AjaxUploaderCancelAllButton').live('click',function(){
	$("#send_now").removeAttr("disabled");
	$("#save_campaign").removeAttr("disabled");
	$("#save_template").removeAttr("disabled");
});

/*function CuteWebUI_AjaxUploader_OnTaskComplete(task)
{
	$("#send_now").removeAttr("disabled");
	$("#save_campaign").removeAttr("disabled");
	$("#save_template").removeAttr("disabled");
}*/

//function uploadercancelbuttonbtn()
$('.uploadercancelbuttonbtn').live('click',function(){
alert("hi");
	$("#send_now").removeAttr("disabled");
	$("#save_campaign").removeAttr("disabled");
	$("#save_template").removeAttr("disabled");

});


	
</script>

<style>
.ui-multiselect{width:100% !important;}

</style>



<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>
<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tokeninput.js"></script>-->


<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>

<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>

<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />

<!--time picker-->
<link rel="stylesheet" href="<?php echo base_url();?>css/datepicker_css/jquery.ui.timepicker.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.core.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/datepicker_js/timepicker/jquery.ui.timepicker.js"></script>

<!-- Select Contact To -->

<div aria-hidden="true" style="display: none;" id="basicModal_to" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_to" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_to();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_to();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 contact_to">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/contact_to');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- Select Contact CC -->

<div aria-hidden="true" style="display: none;" id="basicModal_cc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_cc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_cc();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_cc();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 contact_cc">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/contact_cc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan_cc();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- Select Contact BCC -->

<div aria-hidden="true" style="display: none;" id="basicModal_bcc" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_bcc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_ser_bcc();">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clear_contact_bcc();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 contact_bcc">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/contact_bcc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstoemailplan_bcc();">Select Contact</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->

<!-- To select Contact Type -->

<div aria-hidden="true" style="display: none;" id="basicModal1" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type </h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- End -->
<!-- CC select Contact Type -->

<div aria-hidden="true" style="display: none;" id="basicModal2" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax_cc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax_cc">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search_cc();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact_cc();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup1">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/add_contact_popup_ajax_cc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan_cc();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- End -->

<!-- BCC select Contact Type -->
<div aria-hidden="true" style="display: none;" id="basicModal3" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Select Contact Type</h3>
      </div>
      <div class="modal-body">
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax_bcc" class="form-control inputsrh pull-left" name="search_contact_popup_ajax_bcc">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);" onclick="contact_search_bcc();">Search Contact Type</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact_bcc();">View All</button>
		   </div>
        </div>
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup_bcc">
          <div class="table-responsive">
		  <?php $this->load->view('user/emails/add_contact_popup_ajax_bcc');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstointeractionplan_bcc();">Select Contact Type</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- End -->


<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('phonecallscript_header');?></h1>
  </div>
  <div id="content-container" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-tasks"></i> <?php  echo $this->lang->line('email_campaign');
	   /*if(empty($editRecord)){ echo $this->lang->line('templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('templete_add_head'); } 
	   else{ echo $this->lang->line('templete_edit_head'); } */ ?> </h3>
       <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
	  </div>
    
      <div class="portlet-content">
       <div class="">
        <div class="tab-content" id="myTab1Content">
         
         <div class="tab-pane fade in active" id="home">
          
          <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('user_base_url')?><?php echo $path?>" novalidate data-validate="parsley">
		  <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
           <div class="row">
           <div class="col-sm-8">
		   <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_to');?><span class="val">*</span> : </label>
              <input id="email_to" name="email_to" class="form-control parsley-validated" type="text" value=""   data-required="true">
		<script type="text/javascript">
			$(document).ready(function() {
			
				$("#email_to").tokenInput([ 
				<?php foreach($contact as $row){ ?>
					//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
					{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
				<?php } ?>
				],
				{prePopulate:[
					<?php 
					if(isset($email_to)) {
					foreach($email_to as $row){ ?>
						//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
						{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
					<?php } } ?>
					<?php if(isset($contact_type_to)) {
					foreach($contact_type_to as $row){ ?>
						{id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
					<?php } } ?>
				],
				onAdd: function (item) {
					var str1 = item.id;
					if(!isNaN(str1))
					{
						//var myvalue = str.substr(3);
						add_contact_to(str1);
					}
				},
				onDelete: function (item) {
					var str = item.id;
					//var char = str.substr(0,2);
					if(isNaN(str))
					{
						var myvalue = str.substr(3);
						remove_to(myvalue);
					}
					else
					{
						remove_contact_to(item.id);
					}
				},
				preventDuplicates: true,
				hintText: "Enter Contact Name",
                noResultsText: "No Contact Found",
                searchingText: "Searching...",
				theme: "facebook"} 
				);
			
		});
		</script>
			 <a href="#basicModal_to" class="text_color_red text_size" id="contact_to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
			 
			 <a href="#basicModal1" class="text_color_red text_size" id="to_email" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>
             </div>
            </div>
			<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_cc');?> : </label>
              <input id="email_cc" name="email_cc" class="form-control parsley-validated" type="text" value="" >
			   <script type="text/javascript">
					$(document).ready(function() {
					
						$("#email_cc").tokenInput([ 
						<?php foreach($contact as $row){ ?>
							//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
							{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
						<?php } ?>
						],
						{prePopulate:[
							<?php 
							if(isset($email_cc)) {
							foreach($email_cc as $row){ ?>
								//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
								{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
							<?php } } ?>
							
							<?php if(isset($contact_type_cc)) {
							foreach($contact_type_cc as $row){ ?>
								{id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
							<?php } } ?>
						],
						onAdd: function (item) {
							var str1 = item.id;
							if(!isNaN(str1))
							{
								//var myvalue = str.substr(3);
								add_contact_cc(str1);
							}
						},
						onDelete: function (item) {
							var str = item.id;
							//var char = str.substr(0,2);
							if(isNaN(str))
							{
								var myvalue = str.substr(3);
								remove_cc(myvalue);
							}
							else
							{
								remove_contact_cc(item.id);
							}
						},
						preventDuplicates: true,
						hintText: "Enter Contact Name",
						noResultsText: "No Contact Found",
						searchingText: "Searching...",
						theme: "facebook"}
						);
					
				});
				</script>
				<a href="#basicModal_cc" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
				
				 <a href="#basicModal2" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>
             </div>
            </div>
			<div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('email_bcc');?> : </label>
              <input id="email_bcc" name="email_bcc" class="form-control parsley-validated" type="text" value="" >
			   
			   <script type="text/javascript">
					$(document).ready(function() {
					
						$("#email_bcc").tokenInput([ 
						<?php foreach($contact as $row){ ?>
							//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
							{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
						<?php } ?>
						],
						{prePopulate:[
							<?php 
							if(isset($email_bcc)) {
							foreach($email_bcc as $row){ ?>
								//{id: <?=$row['id']?>, name: "<?=ucwords($row['first_name'])?> <?=ucwords($row['last_name'])?>"},
								{id: <?=$row['id']?>, name: "<?=ucwords($row['contact_name'])?>(<?=ucwords($row['email_address'])?>)"},
							<?php } } ?>
							
							<?php if(isset($contact_type_bcc)) {
							foreach($contact_type_bcc as $row){ ?>
								{id: "CT-"+<?=$row['id']?>, name: "<?=ucwords($row['name'])?>"},
							<?php } } ?>
						],
						onAdd: function (item) {
							var str1 = item.id;
							if(!isNaN(str1))
							{
								//var myvalue = str.substr(3);
								add_contact_bcc(str1);
							}
						},
						onDelete: function (item) {
							var str = item.id;
							//var char = str.substr(0,2);
							if(isNaN(str))
							{
								var myvalue = str.substr(3);
								remove_bcc(myvalue);
							}
							else
							{
								remove_contact_bcc(item.id);
							}
						},
						preventDuplicates: true,
						hintText: "Enter Contact Name",
						noResultsText: "No Contact Found",
						searchingText: "Searching...",
						theme: "facebook"}
						);
					
				});
				</script>
				<a href="#basicModal_bcc" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact </a>
				
				 <a href="#basicModal3" class="text_color_red text_size" data-toggle="modal"><i class="fa fa-plus-square"></i> Select Contact Type</a>
             </div>
            </div>
			
           
            <div class="row">
             <div class="col-sm-12">
              <label for="text-input"><?=$this->lang->line('common_label_category');?> : </label>
			  </div>
              <div class="col-sm-6">
              <select class="selectBox" name='slt_category' id='category' onchange="selectsubcategory(this.value)" >
              <option value="-1">Category</option>
                 <?php if(isset($category) && count($category) > 0){
							foreach($category as $row1){
								if(!empty($row1['id'])){?>
                <option value="<?php echo $row1['id'];?>" <?php if(!empty($editRecord[0]['template_category_id']) && $editRecord[0]['template_category_id'] == $row1['id']){ echo "selected=selected"; } ?> ><?php echo ucwords($row1['category']);?></option>
                <?php 		}
							}
						} ?>
              </select>
              </div>
     			
              <div class="col-sm-6">
              <select class="selectBox" name='slt_subcategory' id='subcategory'>
              </select>
              <span id="category_loader"></span>
              </div>
              
            </div>
			 <div class="row">
             <div class="col-sm-12 form-group">
              <label for="text-input"><?=$this->lang->line('template_label_name');?> : </label>
			   <select class="selectBox" name='template_name' id='template_name'>
              </select>
             </div>
            </div>
           <div class='row mycalclass'>
         <div class="col-sm-12 form-group">
         <label for="text-input"><?=$this->lang->line('tasksubject_label_name');?> <span class="val">*</span> : </label>
         <input id="txt_template_subject" name="txt_template_subject" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['template_subject'])){ echo $editRecord[0]['template_subject']; }?>" data-required="true" onkeypress="return isNumberKey(event);">
          </div>
          </div>
          </div>
          <div class="col-sm-4">
            
            <div class="attachfiel">
               <label>Attach File :</label>
	  <!--<div class="fileUpload btn btn-primary">-->
	  <div>
        <p> 
		<?php
			$uploader=new PhpUploader();
			$uploader->MaxSizeKB=10240;
			$uploader->Name="myuploader";
			
		//	$uploader->InsertButtonID="uploadbutton";
			$uploader->CancelButtonID="uploadercancelbutton";
			//$uploader->ProgressCtrlID="uploaderprogresspanel";
			//$uploader->ProgressTextID="uploaderprogresstext";
			
			//$uploader->CancelUploadMsg="Cancel Uploads";
			//$uploader->InsertButtonID="Cancel";
			$uploader->MultipleFilesUpload=true;
			$uploader->InsertText="Upload Multiple File (Max 10M)";
			//$uploader->CancelButtonID="cancelbutton";
			$uploader->AllowedFileExtensions="jpeg,jpg,gif,png,zip,docx,txt,xls,xlsx";
			$uploader->SaveDirectory="uploads/attachment_temp/";
			$uploader->ManualStartUpload=true;	
			$uploader->Render();
			
		?>	
		<table id="filelist" style='border-collapse: collapse' class='Grid' border='0' cellspacing='3' cellpadding='2' width="100%" >
		</table>
		<button class="uploadercancelbuttonbtn" id="uploadercancelbutton" style="display:none;" onclick="uploadercancelbuttonbtn();" >Cancel</button>
		<button id="submitbutton" onclick="doStart();return false;">Start Uploading Files</button>
		</p>	
		<div id="common_div">
		<?php if(isset($attachment) && count($attachment) > 0) { 
			$this->load->view('user/emails/attachmentlist');
			}
		 ?>
		 </div>
	
		</div>
		 
</div>
            
          </div>
          
            </div>
            <div class="row">
          <div class="col-sm-8">
          <div class="form-group">
                  <label for="select-multi-input">
                 	Email Message : 
                  </label>
	  <textarea name="email_message" id="email_message" class="form-control parsley-validated" ><?=!empty($editRecord[0]['email_message'])?$editRecord[0]['email_message']:'';?></textarea>			  
                  <script type="text/javascript">
												CKEDITOR.replace('email_message',
												 {
													fullPage : false,
													
													//toolbar:[['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],[ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ],[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],[ 'Find','Replace','-','SelectAll','-' ],[ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar' ],[ 'TextColor','BGColor' ],[ 'Maximize', 'ShowBlocks'],[ 'Font','FontSize'],[ 'Link','Unlink','Anchor' ],['Source']],
													
													baseHref : '<?=$this->config->item('ck_editor_path')?>',
													filebrowserUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php',
													filebrowserImageUploadUrl : '<?=$this->config->item('ck_editor_path')?>ckupload.php'
												}, {width: 200});												
											</script>
                </div>
                <div class="row">
                <div class="col-sm-5">
               <label for="text-input">Signature : 
			   </label>
              <select id="email_signature" name="email_signature" class="form-control parsley-validated" >
				   <option value="">Please Select</option>
				   <?php foreach($email_signature_data as $row) { ?>
				   <option value="<?=$row['id']?>" <?php if(!empty($editRecord[0]['email_signature']) && $editRecord[0]['email_signature'] == $row['id']){ echo "selected=selected"; } elseif(!isset($editRecord) && $row['is_default'] == '1') { echo "selected=selected"; } ?> > <?=$row['signature_name']?>  </option>
				   <?php } ?>
				   </select>
                                                          </div>
</div>
             <div class="row">
                <div class="col-sm-12">
                  <fieldset class="schedule_campaign">
                     <legend>Schedule Blast</legend>
                       <div class="schedule_detail">
                         <p>Send this Blast ?</p> 
                         <div class="col-sm-12 pull-left">
							  
						 <div class="float-left margin-left-15"><label class="checkbox">
						  Now
						 	<div class="col-sm-3">
							
							<input type="radio" value="1" class="" id="chk_is_lead1" name="chk_is_lead" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '1' || (!empty($send_now) && $send_now == 'send')){ echo "checked=checked"; } ?> >  
							</div>
							 </label>
						</div> 
						 <div class="col-sm-12 pull-left"> 
						 <label class="checkbox">
						  Date Time :
						 	<div class="col-sm-3">
							
							<input type="radio" name="chk_is_lead" id="chk_is_lead2" class="" value="2"  <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2' && $send_now == ''){ echo "checked=checked"; } ?> > 
							</div>
							 </label>
							<div class="col-sm-5">
								<input name="send_date" id="send_date" class="form-control parsley-validated" type="text" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2') { ?>value="<?=!empty($editRecord[0]['email_send_date'])?$editRecord[0]['email_send_date']:''?>" <?php } ?> readonly="readonly" />
							  	
							</div>
							<div class="col-sm-4">
								<input name="send_time" id="send_time" type="text" <?php if(!empty($editRecord[0]['email_send_type']) && $editRecord[0]['email_send_type'] == '2') { ?> value="<?=!empty($editRecord[0]['email_send_time'])?$editRecord[0]['email_send_time']:''?>" <?php } ?> readonly="readonly" /><!--<i class="fa fa-clock-o"></i>-->
                                <button type="button" class="timepicker_disable_button_trigger timepick"><img src="http://192.168.0.14/livewire_crm/images/calendar.png" alt="..." title="..."></button>
                               
							</div>
						  </div>                       
						</div>
                  </fieldset>
                </div>
             </div>
          </div>
         <div class="col-sm-4">
            
               <label>Custom Field</label>
            <div class="custom_field">
               
              
               <select ondblclick="addfieldtoeditor()" id="slt_customfields" name="slt_customfields" size="15" multiple="multiple" class="selectBox">
             <?php if(isset($tablefield_data) && count($tablefield_data) > 0){ ?>
             <?php foreach($tablefield_data as $row){ 
					if(!empty($row['name'])){
					$pattern = $this->config->item('email_param_pattern');
					if(empty($pattern))
						$pattern = "{(%s)}";
					$fieldval = !empty($row['name'])?$row['name']:$row['name'];
															 ?>
             <option title="<?php echo $fieldval;?>" value="<?php echo sprintf($pattern, $fieldval);?>"> <?php echo $fieldval;?> </option>
             <?php }
				 } ?>
             <?php } ?>
            </select>
               
                </select>
                 <input class="btn btn-secondary" type="button" name="submitbtn1" onclick="addfieldtoeditor();" value="Insert Field">
               
            <div class="unsubscribe">
			 <div class="col-sm-12 pull-left"><label class="checkbox">
              Is Unsubscribe 
              <div class="float-left margin-left-15">
               <input type="checkbox" name="is_unsubscribe" id="is_unsubscribe" class="" value="1" <?php if(!empty($editRecord[0]['is_unsubscribe']) && $editRecord[0]['is_unsubscribe'] == '1'){ echo "checked=checked"; } ?>>
              </div>
              </label></div> 
          </div></div>
          </div>
               </div>
          <div class="col-sm-12 pull-left text-center margin-top-10">
		  <input type="hidden" id="email_to_contact_type" name="email_to_contact_type" value="<?=!empty($contact_type_to_selected)?$contact_type_to_selected:''?>" />
		  <input type="hidden" id="email_cc_contact_type" name="email_cc_contact_type" value="<?=!empty($contact_type_cc_selected)?$contact_type_cc_selected:''?>" />
		  <input type="hidden" id="email_bcc_contact_type" name="email_bcc_contact_type" value="<?=!empty($contact_type_bcc_selected)?$contact_type_bcc_selected:''?>" />
		  
		   <input type="hidden" id="email_contact_to" name="email_contact_to" value="<?=!empty($select_to)?$select_to:''?>" />
		    <input type="hidden" id="email_contact_cc" name="email_contact_cc" value="<?=!empty($select_cc)?$select_cc:''?>" />
			 <input type="hidden" id="email_contact_bcc" name="email_contact_bcc" value="<?=!empty($select_bcc)?$select_bcc:''?>" />
		  
<input type="hidden" id="contacttab" name="contacttab" value="1" />
<input type="hidden" name="last_id" value="" id="last_id" />
<input type="hidden" name="fileName" value="" id="fileName"  />
<input type="submit" class="btn btn-secondary" id="send_now" value="Send Now" title="Send Now" onclick="return validation_date()" name="submitbtn" />
<input type="submit" class="btn btn-secondary" id="save_campaign" value="Save Blast" title="Save Blast" onclick="return validation_date()" name="submitbtn" />
<input type="submit" class="btn btn-secondary" id="save_template" value="Save Template As" title="Save Template As" onclick="return validation_date()" name="submitbtn" />
 <a class="btn btn-primary" href="javascript:history.go(-1);" title="Close">Close</a>
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
</div>

 <?php
// print_r($subcategory);
 ?>

<script type="text/javascript">
function file_attachmentname()
{
	var inp = document.getElementById('file_attachment');
	var name = '';
	for (var i = 0; i < inp.files.length; ++i) {
	  name = name + inp.files.item(i).name + ",";
	}
	var cnt = name.lastIndexOf(",");
    name = name.substring(0,cnt);
	$("#selectedfile").html(name);
}
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
	$("select#template_name").multiselect({
		 multiple: false,
		 header: "Template Name",
		 noneSelectedText: "Template Name",
		 selectedList: 1
	}).multiselectfilter();
 
  
function selectsubcategory(id){
 if(id!="-1"){
   	$("#subcategory").html("<option value='-1'>Sub-Category</option>");
   	loadData('category',id);
  	setTimeout(function(){$('#subcategory').change()},1000);
 }else{
   $("#subcategory").html("<option value='-1'>Sub-Category</option>");
   $("select#subcategory").multiselect('refresh').multiselectfilter();
   setTimeout(function(){$('#subcategory').change()},1000);
 }
}

function loadData(loadType,loadId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_subcategory';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadId:loadId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php if(!empty($editRecord[0]['template_subcategory_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_subcategory_id']?>';
			
			<?php } ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.category);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#subcategory').append(option);
			});
		$("select#subcategory").multiselect('refresh').multiselectfilter();				
     }
   });
}

$("#subcategory").change(function() {
	var subcategory_id = $("#subcategory").val();
	if(subcategory_id!="-1"){
		var category_id = $("#category").val();
	   	$("#template_name").html("<option value='-1'>Template Name</option>");
	   	loadtemplateData('template',category_id,subcategory_id);
	 }else{
	   $("#template_name").html("<option value='-1'>Template Name</option>");
	   $("select#template_name").multiselect('refresh').multiselectfilter();
 	}
});
function loadtemplateData(loadType,loadcategoryId,loadsubcategoryId){
  $.ajax({
     type: "POST",
     url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatedata';?>",
     dataType: 'json',
	 data: {loadType:loadType,loadcategoryId:loadcategoryId,loadsubcategoryId:loadsubcategoryId},
     cache: false,
     success: function(result){
		 
		 var selectedsubcat = 0;
		 
		 <?php  if(!empty($editRecord[0]['template_name_id'])){ ?>
					
			selectedsubcat = '<?=$editRecord[0]['template_name_id']?>';
			
			<?php }  ?>
		 
		$.each(result,function(i,item){ 
					var option = $('<option />');
					option.attr('value', item.id).text(this.template_name);
					
					if(selectedsubcat == item.id)
						option.attr("selected","selected");
					
					$('#template_name').append(option);
			});
		$("select#template_name").multiselect('refresh').multiselectfilter();
						
     }
   });
}

<?php if(!empty($editRecord[0]['template_category_id'])){ ?>

selectsubcategory('<?=$editRecord[0]['template_category_id']?>');

<?php } ?>

$("#template_name").change(function() {
//alert($("#template_name").val());
	 $.ajax({
		 type: "POST",
		 dataType: 'json',
		 url: "<?php echo $this->config->item('user_base_url').$viewname.'/ajax_templatename';?>",
		 data: {template_id:$("#template_name").val()},
		 cache: false,
		 success: function(result){
		 	$.each(result,function(i,item){
			//alert(item.email_message);
			$("#txt_template_subject").val(item.template_subject);
			//editor.insertText("ckEditor");
			CKEDITOR.instances.email_message.setData(item.email_message);
			//CKEDITOR.instances.email_message.insertText(item.email_message);
				//$("#email_message").innerHTML(item.email_message);
			});
		 }
		});
});
	</script>
<script type="text/javascript">
function validation_date()
{
	if($('input:radio[name=chk_is_lead]:checked').val() == '2')
	{
		//alert($("#send_date").val());
		//alert($('input:radio[name=chk_is_lead]:checked').val() == '2');
		if($("#send_date").val() != '')
		{
			return true
		}
		else
		{
			$.confirm({'title': 'Alert','message': " <strong> Please Enter Day"+"<strong></strong>",
						'buttons': {'ok'	: {
						'class'	: 'btn_center alert_ok',	
						'action': function(){
						 $('#send_date').focus();
						}},  }});
			return false;
		}
	}
	else
		return true;
}

$(document).ready(function (){
	$(function() {
		$( "#send_date" ).datepicker({
			showOn: "button",
			changeMonth: true,
			changeYear: true,
			yearRange: "-100:+0",
			minDate: "0",
			buttonImage: "<?=base_url('images');?>/calendar.png",
			dateFormat:'yy-mm-dd',
			buttonImageOnly: false
		});
	});
	 $('#send_time').timepicker({
		showLeadingZero: false,
		showOn: 'both',
		button: '.timepicker_disable_button_trigger',
		showNowButton: true,
		showDeselectButton: true,
		defaultTime: '',  // removes the highlighted time for when the input is empty.
		showCloseButton: true
	});
	<?php /*?>$('#send_time').datetimepicker({
				showOn: "button",
				buttonImage: "<?=base_url('images');?>/calendar.png",
				buttonImageOnly: true,
				format:'H:i:s',
				datepicker:false,
				
			});<?php */?>
});
</script>
	


<script type="text/javascript">
/*function addfieldtoeditor()
{
	var a=CKEDITOR.instances['email_message'],b=document.<?php echo $viewname;?>.slt_customfields;
	if(b.options.length>0){
		sql_box_locked=true;
		for(var c="",d=0,e=0;e<b.options.length;e++)
			if(b.options[e].selected)
			{
				d++;
				if(d>1)
					c+=", ";
				c+=b.options[e].value
			}
			if(document.selection){
				a.focus();
				sel=document.selection.createRange();
				sel.text=c;document.<?php echo $viewname;?>.insert.focus()
			}
			else if(CKEDITOR.instances['email_message'].selectionStart||CKEDITOR.instances['email_message'].selectionStart=="0"){
				b=CKEDITOR.instances['email_message'].selectionEnd;
				d=CKEDITOR.instances['email_message'].value;
				a.insertText(d.substring(0,CKEDITOR.instances['email_message'].selectionStart)+c+d.substring(b,d.length));
			}
			else
			{ 
				a.insertText(c);
				sql_box_locked=false
			}
	}
}
	*/
</script>
<script type="text/javascript">
	var arraydatacount = 0;
	var popupcontactlist = Array();
	
	$('body').on('click','#selecctall',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex == -1)
				{
					popupcontactlist[arraydatacount++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
				
				if(arrayindex >= 0)
				{
					popupcontactlist.splice( arrayindex, 1 );
					arraydatacount--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_tb a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist.length;i++)
				{
					$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup').unblock();
		}
	});
	return false;
	});
	

	$('#search_contact_popup_ajax').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search();
			}
	});
	
	function clearfilter_contact()
	{
		$("#search_contact_popup_ajax").val("");
		contact_search();
	}
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist.length;i++)
					{
						$('.mycheckbox:checkbox[value='+popupcontactlist[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup').unblock(); 
			}
		});
		return false;
	}
	
	function addcontactstointeractionplan()
	{
		/*	$(document).ready(function() {
						
			$("#email_to").tokenInput("add", {id: 47, name: "Java"})
		}); */
		var i = 0;
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist
		},
		beforeSend: function() {
				$('.added_contacts_list').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_to_contact_type").val().trim() != '')
				{	
					var str = $("#email_to_contact_type").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						//alert(res[i]);
						$("#email_to").tokenInput("remove", {id: "CT-"+res[i]});
					}
				}
				//alert(i);
				//var i = 0;
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist );
				
					if(arrayindex == -1)
					{
						$('.mycheckbox:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontactlist[arraydatacount++] = parseInt(item.id);
					}
				
					$("#email_to").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					
					//$('.mycheckbox:checkbox[value='+item.id+']').attr('checked',true);
					
				});
				/*for(i=0;i<res.length;i++)
				{
					$("#email_to").tokenInput("remove", {id: "CT-"+res[i]});
				}*/
				//alert(popupcontactlist);
				$("#email_to_contact_type").val(popupcontactlist);
				$('.added_contacts_list').unblock(); 
			}
		});
	}	

	
	$('body').on('click','.mycheckbox',function(e){
		
		if($('.mycheckbox:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist );
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		
	});
	
<?php 
if(isset($contact_type_to)){
	foreach($contact_type_to as $row){?>
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
		if(arrayindex == -1)
		{
			$('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		//alert(myvalue);
		$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontactlist.splice( arrayindex, 1 );
		arraydatacount--;
	}
	//alert(popupcontactlist);
}

</script>

<!-- CC Contact type Script  -->

<script type="text/javascript">
	var arraydatacount_cc = 0;
	var popupcontactlist_cc = Array();
	
	$('body').on('click','#selecctall_cc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_cc').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
				
				if(arrayindex == -1)
				{
					popupcontactlist_cc[arraydatacount_cc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_cc').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
				
				if(arrayindex >= 0)
				{
					popupcontactlist_cc.splice( arrayindex, 1 );
					arraydatacount_cc--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_tb_cc a.paginclass_A',function(e){
	
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax_cc").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup1').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup1").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist_cc.length;i++)
				{
					$('.mycheckbox_cc:checkbox[value='+popupcontactlist_cc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup1').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_popup_ajax_cc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search_cc();
			}
	});
	
	function clearfilter_contact_cc()
	{
		$("#search_contact_popup_ajax_cc").val("");
		contact_search_cc();
	}
	
	function contact_search_cc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_ajax_cc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax_cc").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup1').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup1").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist_cc.length;i++)
					{
						$('.mycheckbox_cc:checkbox[value='+popupcontactlist_cc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup1').unblock(); 
			}
		});
		return false;
	}
	
	$('body').on('click','.mycheckbox_cc',function(e){
		
		if($('.mycheckbox_cc:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
			if(arrayindex == -1)
			{				
				popupcontactlist_cc[arraydatacount_cc++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_cc );
			if(arrayindex >= 0)
			{
				popupcontactlist_cc.splice( arrayindex, 1 );
				arraydatacount_cc--;
			}
		}
		
	});
	
	function addcontactstointeractionplan_cc()
	{
		//$("#email_cc_contact_type").val(popupcontactlist_cc);
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist_cc
		},
			beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
					  },
				success: function(result){
					if($("#email_cc_contact_type").val().trim() != '')
					{	
						var str = $("#email_cc_contact_type").val();	
						var res = str.split(",");
						for(i=0;i<res.length;i++)
						{
							$("#email_cc").tokenInput("remove", {id: "CT-"+res[i]});
						} 
					}
					$.each(result,function(i,item){
						var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist_cc );
					
						if(arrayindex == -1)
						{
							$('.mycheckbox_cc:checkbox[value='+parseInt(item.id)+']').attr('checked',true)
							popupcontactlist_cc[arraydatacount_cc++] = parseInt(item.id);
						}
						$("#email_cc").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					});
					$("#email_cc_contact_type").val(popupcontactlist_cc);
					$('.added_contacts_list').unblock(); 
				}
			});
	}
	
<?php 
if(isset($contact_type_cc)){
	foreach($contact_type_cc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist_cc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_cc:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist_cc[arraydatacount_cc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_cc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist_cc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_cc:checkbox[value='+myvalue+']').attr('checked',false);
		popupcontactlist_cc.splice( arrayindex, 1 );
		arraydatacount_cc--;
	}
}	
	
</script>
<!-- End -->

<!-- BCC Contact type Script  -->
<script type="text/javascript">
	var arraydatacount_bcc = 0;
	var popupcontactlist_bcc = Array();
	
	$('body').on('click','#selecctall_bcc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_bcc').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
				
				if(arrayindex == -1)
				{
					popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_bcc').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
				
				if(arrayindex >= 0)
				{
					popupcontactlist_bcc.splice( arrayindex, 1 );
					arraydatacount_bcc--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_tb_bcc a.paginclass_A',function(e){
	
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_popup_ajax_bcc").val()
	},
	beforeSend: function() {
				$('.add_new_contact_popup_bcc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".add_new_contact_popup_bcc").html(html);
			
			//alert(JSON.stringify(popupcontactlist));
			try
			{
				for(i=0;i<popupcontactlist_bcc.length;i++)
				{
					$('.mycheckbox_bcc:checkbox[value='+popupcontactlist_bcc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.add_new_contact_popup_bcc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_popup_ajax_bcc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_search_bcc();
			}
	});
	
	function clearfilter_contact_bcc()
	{
		$("#search_contact_popup_ajax_bcc").val("");
		contact_search_bcc();
	}
	
	function contact_search_bcc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_ajax_bcc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax_bcc").val()
		},
		beforeSend: function() {
					$('.add_new_contact_popup_bcc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".add_new_contact_popup_bcc").html(html);
				
				try
				{
					for(i=0;i<popupcontactlist_bcc.length;i++)
					{
						$('.mycheckbox_bcc:checkbox[value='+popupcontactlist_bcc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.add_new_contact_popup_bcc').unblock(); 
			}
		});
		return false;
	}
	
	$('body').on('click','.mycheckbox_bcc',function(e){
		
		if($('.mycheckbox_bcc:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
			if(arrayindex == -1)
			{				
				popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(this.value);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontactlist_bcc );
			if(arrayindex >= 0)
			{
				popupcontactlist_bcc.splice( arrayindex, 1 );
				arraydatacount_bcc--;
			}
		}
		
	});
	
	function addcontactstointeractionplan_bcc()
	{
		//$("#email_bcc_contact_type").val(popupcontactlist_bcc);
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/add_contacts_to_email/",
			data: {
			result_type:'ajax',contacts_type:popupcontactlist_bcc
		},
			beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
					  },
				success: function(result){
					if($("#email_bcc_contact_type").val().trim() != '')
					{	
						var str = $("#email_bcc_contact_type").val();	
						var res = str.split(",");
						for(i=0;i<res.length;i++)
						{
							$("#email_bcc").tokenInput("remove", {id: "CT-"+res[i]});
						} 
					}
					$.each(result,function(i,item){
						var arrayindex = jQuery.inArray( parseInt(item.id), popupcontactlist_bcc );
				
						if(arrayindex == -1)
						{
							$('.mycheckbox_bcc:checkbox[value='+parseInt(item.id)+']').attr('checked',true)
							popupcontactlist_bcc[arraydatacount_bcc++] = parseInt(item.id);
						}
						$("#email_bcc").tokenInput("add", {id: "CT-"+item.id, name: item.name});
					});
					$("#email_bcc_contact_type").val(popupcontactlist_bcc);
					$('.added_contacts_list').unblock(); 
				}
			});
	}
	
<?php 
if(isset($contact_type_bcc)){
	foreach($contact_type_bcc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist_bcc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_bcc:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontactlist_bcc[arraydatacount_bcc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_bcc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontactlist_bcc);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_bcc:checkbox[value='+myvalue+']').attr('checked',false);
		popupcontactlist_bcc.splice( arrayindex, 1 );
		arraydatacount_bcc--;
	}
}		
	
</script>
<!-- End -->

					<!-------   insert custom field in Sibject and message    ------>
<script type="text/javascript">

CKEDITOR.on( 'instanceReady', function( event ) {
    event.editor.on( 'focus', function() {
	console.log($(this).attr('id') + ' just got focus!!');
	window.last_focus = $(this).attr('id');
	$('#last_id').val($(this).attr('id'));
    });
});

$('input:text').on('focus', function() { 
	console.log($(this).attr('id') + ' just got focus!!');
	window.last_focus = $(this).attr('id');
	$('#last_id').val($(this).attr('id'));
	});
	  
function addfieldtoeditor() {
var a=$('#last_id').val();
		//alert(a);
		if(a == 'txt_template_subject')
		{
			var text_length = $('#txt_template_subject').val().length;
			if(text_length < 180){
			var a=document.<?php echo $viewname;?>.txt_template_subject,b=document.<?php echo $viewname;?>.slt_customfields;
			if(b.options.length>0){
				sql_box_locked=true;
				for(var c="",d=0,e=0;e<b.options.length;e++)
					if(b.options[e].selected)
					{
						d++;
						if(d>1)
							c+=", ";
						c+=b.options[e].value
					}
					if(document.selection){
						a.focus();
						sel=document.selection.createRange();
						sel.text=c;document.<?php echo $viewname;?>.insert.focus()
					}
					else if(document.<?php echo $viewname;?>.txt_template_subject.selectionStart||document.<?php echo $viewname;?>.txt_template_subject.selectionStart=="0"){
						b=document.<?php echo $viewname;?>.txt_template_subject.selectionEnd;
						d=document.<?php echo $viewname;?>.txt_template_subject.value;
						a.value = d.substring(0,document.<?php echo $viewname;?>.txt_template_subject.selectionStart)+c+d.substring(b,d.length);
						//a.insertText(d.substring(0,document.sms_texts.sms_message.selectionStart)+c+d.substring(b,d.length));
					}
					else
					{ 
						a.insertText(c);
						sql_box_locked=false
					}
				}
				$('#txt_template_subject').keyup();
		}
	
	}
	 if(a == 'cke_1'  || a == '')
	{
				var a=CKEDITOR.instances['email_message'],b=document.<?php echo $viewname;?>.slt_customfields;
		if(b.options.length>0){
			sql_box_locked=true;
			for(var c="",d=0,e=0;e<b.options.length;e++)
				if(b.options[e].selected)
				{
					d++;
					if(d>1)
						c+=", ";
					c+=b.options[e].value
				}
				if(document.selection){
					a.focus();
					sel=document.selection.createRange();
					sel.text=c;document.<?php echo $viewname;?>.insert.focus()
				}
				else if(CKEDITOR.instances['email_message'].selectionStart||CKEDITOR.instances['email_message'].selectionStart=="0"){
					b=CKEDITOR.instances['email_message'].selectionEnd;
					d=CKEDITOR.instances['email_message'].value;
					a.insertText(d.substring(0,CKEDITOR.instances['email_message'].selectionStart)+c+d.substring(b,d.length));
				}
				else
				{ 
					a.insertText(c);
					sql_box_locked=false
				}
		}

	}

}

</script>
						<!-------  END insert custom field in Sibject and message    ------>
						
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
		setTimeout(function(){
			$('.AjaxUploaderQueueTable tbody tr:last').find('td:last').after( "<td><a href='javascript:void(0);' value='"+task.FileName+"' ><img src='<?=base_url()?>images/stop.png' /></a></td>" );
		}, 1000);
			
		//$('#fileName').val(task.FileName);
	}
	
	$('.AjaxUploaderQueueTable tbody tr td a').live( "click", function() {
			//var file_name = $(this).attr("value");
			 $('.AjaxUploaderQueueTable tbody tr:last').remove();
		    $.ajax({
                type: "POST",
                url: '<?=base_url()?>user/<?=$viewname;?>/delete_attachment',
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
	});	*/
</script>

<script type='text/javascript'>
	//this is to show the header..
	ShowAttachmentsTable();
</script>

<!-------  Start the Contact To selection in popup   ------>

<script type="text/javascript">

	var arraydata_to = 0;
	var popupcontact_to = Array();
	
	$('body').on('click','#select_contact_to',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_to').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				
				if(arrayindex == -1)
				{
					popupcontact_to[arraydata_to++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_to').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
				
				if(arrayindex >= 0)
				{
					popupcontact_to.splice( arrayindex, 1 );
					arraydata_to--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_contact_to a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_to").val()
	},
	beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_to").html(html);
			try
			{
				for(i=0;i<popupcontact_to.length;i++)
				{
					$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_to').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_to').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_to();
			}
	});
	
	function clear_contact_to()
	{
		$("#search_contact_to").val("");
		contact_ser_to();
	}
	
	function contact_ser_to()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_to/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_to").val()
		},
		beforeSend: function() {
					$('.contact_to').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_to").html(html);
				
				try
				{
					for(i=0;i<popupcontact_to.length;i++)
					{
						$('.mycheckbox_to:checkbox[value='+popupcontact_to[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_to').unblock(); 
			}
		});
		return false;
	}
	
	$('body').on('click','.mycheckbox_to',function(e){
		
		if($('.mycheckbox_to:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			if(arrayindex == -1)
			{				
				popupcontact_to[arraydata_to++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_to );
			
			if(arrayindex >= 0)
			{
				popupcontact_to.splice( arrayindex, 1 );
				arraydata_to--;
			}
		}
		
	});
	
	function addcontactstoemailplan()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_to
		},
		beforeSend: function() {
				$('.contact_to').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_to").val().trim() != '')
				{	
					var str = $("#email_contact_to").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_to").tokenInput("remove", {id: parseInt(res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontact_to );
					if(arrayindex == -1)
					{
						$('.mycheckbox_to:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontact_to[arraydata_to++] = parseInt(item.id);
					}
					$("#email_to").tokenInput("add", {id: parseInt(item.id), name: item.contact_name + '(' + item.email_address + ')'});
				});
				$("#email_contact_to").val(popupcontact_to);
				$('.contact_to').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_to) && count($email_to) > 0){
	foreach($email_to as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontact_to );
		if(arrayindex == -1)
		{
			$('.mycheckbox_to:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontact_to[arraydata_to++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_contact_to(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontact_to);
	//alert(arrayindex);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_to:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontact_to.splice( arrayindex, 1 );
		arraydata_to--;
	}
}	
function add_contact_to(myvalue)
{
	//alert(myvalue);
	var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontact_to );
	//alert(arrayindex);
	if(arrayindex == -1)
	{
		popupcontact_to[arraydata_to++] = parseInt(myvalue);
		$('.mycheckbox_to:checkbox[value='+myvalue+']').attr('checked',true);
		if($("#email_contact_to").val().trim() != '')
		{
			var str = $("#email_contact_to").val();
			$("#email_contact_to").val(str+","+myvalue);
		}
		else
			$("#email_contact_to").val(myvalue);
		//alert(popupcontact_to);
	}
}		

</script>

<!-------             END                  -->

<!-------  Start the Contact CC selection in popup   ------>
<script type="text/javascript">

	var arraydata_cc = 0;
	var popupcontact_cc = Array();
	
	$('body').on('click','#select_contact_cc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_cc1').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_cc );
				
				if(arrayindex == -1)
				{
					popupcontact_cc[arraydata_cc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_cc1').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_cc );
				
				if(arrayindex >= 0)
				{
					popupcontact_cc.splice( arrayindex, 1 );
					arraydata_cc--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_contact_cc a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_cc").val()
	},
	beforeSend: function() {
				$('.contact_cc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_cc").html(html);
			try
			{
				for(i=0;i<popupcontact_cc.length;i++)
				{
					$('.mycheckbox_cc1:checkbox[value='+popupcontact_cc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_cc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_cc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_cc();
			}
	});
	
	function clear_contact_cc()
	{
		$("#search_contact_cc").val("");
		contact_ser_cc();
	}
	
	function contact_ser_cc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_cc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_cc").val()
		},
		beforeSend: function() {
					$('.contact_cc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_cc").html(html);
				
				try
				{
					for(i=0;i<popupcontact_cc.length;i++)
					{
						$('.mycheckbox_cc1:checkbox[value='+popupcontact_cc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_cc').unblock(); 
			}
		});
		return false;
	}
	
	$('body').on('click','.mycheckbox_cc1',function(e){
		
		if($('.mycheckbox_cc1:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_cc );
			if(arrayindex == -1)
			{				
				popupcontact_cc[arraydata_cc++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_cc );
			
			if(arrayindex >= 0)
			{
				popupcontact_cc.splice( arrayindex, 1 );
				arraydata_cc--;
			}
		}
		
	});
	
	function addcontactstoemailplan_cc()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_cc
		},
		beforeSend: function() {
				$('.contact_cc').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_cc").val().trim() != '')
				{	
					var str = $("#email_contact_cc").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_cc").tokenInput("remove", {id: parseInt(res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontact_cc );
					if(arrayindex == -1)
					{
						$('.mycheckbox_cc1:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontact_cc[arraydata_cc++] = parseInt(item.id);
					}

					$("#email_cc").tokenInput("add", {id: parseInt(item.id), name: item.contact_name + '(' + item.email_address + ')'});
				});
				$("#email_contact_cc").val(popupcontact_cc);
				$('.contact_cc').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_cc) && count($email_cc) > 0){
	foreach($email_cc as $row){?>
		
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontact_cc );
		if(arrayindex == -1)
		{
			$('.mycheckbox_cc1:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontact_cc[arraydata_cc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_contact_cc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontact_cc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_cc1:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontact_cc.splice( arrayindex, 1 );
		arraydata_cc--;
	}
}	
function add_contact_cc(myvalue)
{
	var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontact_cc );
	if(arrayindex == -1)
	{
		popupcontact_cc[arraydata_cc++] = parseInt(myvalue);
		$('.mycheckbox_cc1:checkbox[value='+myvalue+']').attr('checked',true);
		if($("#email_contact_cc").val().trim() != '')
		{
			var str = $("#email_contact_cc").val();
			$("#email_contact_cc").val(str+","+myvalue);
		}
		else
			$("#email_contact_cc").val(myvalue);
	}
}
</script>
<!-------             END                  -->

<!-------  Start the Contact BCC selection in popup   ------>
<script type="text/javascript">

	var arraydata_bcc = 0;
	var popupcontact_bcc = Array();
	
	$('body').on('click','#select_contact_bcc',function(e){	
     
	 	if(this.checked) { // check select status
         $('.mycheckbox_bcc1').each(function() { //loop through each checkbox

                this.checked = true;  //select all checkboxes with class "mycheckbox" 
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
				
				if(arrayindex == -1)
				{
					popupcontact_bcc[arraydata_bcc++] = parseInt(this.value);
				}
				             
            });
        }else{
            $('.mycheckbox_bcc1').each(function() { //loop through each checkbox
				
                this.checked = false; //deselect all checkboxes with class "mycheckbox"
				
				var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
				
				if(arrayindex >= 0)
				{
					popupcontact_bcc.splice( arrayindex, 1 );
					arraydata_bcc--;
				}
				
            });        
        }
    });
	
	$('body').on('click','#common_contact_bcc a.paginclass_A',function(e){
	$.ajax({
		type: "POST",
		url: $(this).attr('href'),
		data: {
		result_type:'ajax',searchtext:$("#search_contact_bcc").val()
	},
	beforeSend: function() {
				$('.contact_bcc').block({ message: 'Loading...' });
			  },
		success: function(html){
		   
			$(".contact_bcc").html(html);
			try
			{
				for(i=0;i<popupcontact_bcc.length;i++)
				{
					$('.mycheckbox_bcc1:checkbox[value='+popupcontact_bcc[i]+']').attr('checked',true)
				}
			}
			catch(e){}
			
			$('.contact_bcc').unblock();
		}
	});
	return false;
	});
	
	$('#search_contact_bcc').keyup(function(event) 
	{
			if (event.keyCode == 13) {
				contact_ser_bcc();
			}
	});
	
	function clear_contact_bcc()
	{
		$("#search_contact_bcc").val("");
		contact_ser_bcc();
	}
	
	function contact_ser_bcc()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/emails/search_contact_bcc/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_bcc").val()
		},
		beforeSend: function() {
					$('.contact_bcc').block({ message: 'Loading...' }); 
				  },
			success: function(html){
				
				$(".contact_bcc").html(html);
				
				try
				{
					for(i=0;i<popupcontact_bcc.length;i++)
					{
						$('.mycheckbox_bcc1:checkbox[value='+popupcontact_bcc[i]+']').attr('checked',true);
					}
				}
				catch(e){}
				
				$('.contact_bcc').unblock(); 
			}
		});
		return false;
	}
	
	$('body').on('click','.mycheckbox_bcc1',function(e){
		
		if($('.mycheckbox_bcc1:checkbox[value='+parseInt(this.value)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
			if(arrayindex == -1)
			{				
				popupcontact_bcc[arraydata_bcc++] = parseInt(this.value);
			}
		}
		else
		{
		
			var arrayindex = jQuery.inArray( parseInt(this.value), popupcontact_bcc );
			
			if(arrayindex >= 0)
			{
				popupcontact_bcc.splice( arrayindex, 1 );
				arraydata_bcc--;
			}
		}
		
	});
	
	function addcontactstoemailplan_bcc()
	{
		$.ajax({
			type: "POST",
			dataType: 'json',
			url: "<?php echo base_url();?>user/emails/contacts_to_email/",
			data: {
			result_type:'ajax',contacts_id:popupcontact_bcc
		},
		beforeSend: function() {
				$('.contact_bcc').block({ message: 'Loading...' }); 
				$('.close_contact_select_popup').trigger('click');
				  },
			success: function(result){
				var res = Array();
				if($("#email_contact_bcc").val().trim() != '')
				{	
					var str = $("#email_contact_bcc").val();	
					res = str.split(",");
					for(i=0;i<res.length;i++)
					{
						$("#email_bcc").tokenInput("remove", {id: parseInt(res[i])});
					}
				}
				$.each(result,function(i,item){
					var arrayindex = jQuery.inArray( parseInt(item.id), popupcontact_bcc );
					if(arrayindex == -1)
					{
						$('.mycheckbox_bcc1:checkbox[value='+parseInt(item.id)+']').attr('checked',true);				
						popupcontact_bcc[arraydata_bcc++] = parseInt(item.id);
					}

					$("#email_bcc").tokenInput("add", {id: parseInt(item.id), name: item.contact_name + '(' + item.email_address + ')'});
				});
				$("#email_contact_bcc").val(popupcontact_bcc);
				$('.contact_bcc').unblock(); 
			}
		});
	}
	
<?php 
if(isset($email_bcc) && count($email_bcc) > 0){
	foreach($email_bcc as $row){?>
		var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontact_bcc );
		//alert(arrayindex);
		if(arrayindex == -1)
		{
			$('.mycheckbox_bcc1:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
			popupcontact_bcc[arraydata_bcc++] = <?=!empty($row['id'])?$row['id']:''?>;
		}
	
<?php }
}
?>

function remove_contact_bcc(myvalue)
{
	var arrayindex = jQuery.inArray(parseInt(myvalue),popupcontact_bcc);
	if(arrayindex >= 0)
	{
		$('.mycheckbox_bcc1:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
		popupcontact_bcc.splice( arrayindex, 1 );
		arraydata_bcc--;
	}
}	
function add_contact_bcc(myvalue)
{
	var arrayindex = jQuery.inArray( parseInt(myvalue), popupcontact_bcc );
	if(arrayindex == -1)
	{
		popupcontact_bcc[arraydata_bcc++] = parseInt(myvalue);
		$('.mycheckbox_bcc1:checkbox[value='+parseInt(myvalue)+']').attr('checked',true);
		if($("#email_contact_bcc").val().trim() != '')
		{
			var str = $("#email_contact_bcc").val();
			$("#email_contact_bcc").val(str+","+myvalue);
		}
		else
			$("#email_contact_bcc").val(myvalue);
	}
}
</script>

<!-------             END                  -->

<script type="script">
	
	function isNumberKey(evt)
	{
		var abc = $("#txt_template_subject").val().length;
		
		var charCode = (evt.which) ? evt.which : evt.keyCode
		if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
			alert(evt);
			return false;
		}
		return true;
	}	
			</script>>>>>>>