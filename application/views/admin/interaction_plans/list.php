<?php 
    /*
        @Description: Admin InterCommunications list
        @Author: Mit Makwana
        @Date: 14-07-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$viewname = $this->router->uri->segments[2];
$admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));

?>

<script language="javascript">
<?php if(!empty($premium_plan_update)) {/* ?>
$.confirm({'title': 'CONFIRM','message': " <strong>  Are you sure ? <strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
	window.location = '<?php echo $this->config->item('admin_base_url').$viewname;?>/premium_plan_update';
}},'No'	: {'class'	: 'special'}}});
<?php*/ } ?>

$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	//$.unblockUI();
	$.unblockUI();
});
</script>
<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('interaction_plans_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('interaction_plans');?></h3>
       <span class="pull-right"><a title="Back" class="btn btn-secondary" onclick="history.go(-1)" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      <div class="portlet-content">
          <ul class="nav nav-tabs" id="myTab1">
           		
                <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?> > <a title="My Plan" data-toggle="tab" href="#my_plan" onclick="load_view('my_plan');">My Plan</a> </li>
                <?php if(!empty($this->modules_unique_name) && in_array('premium_plans',$this->modules_unique_name)){?>
                <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Premium Plan" data-toggle="tab" href="#premium_plan" onclick="load_view('premium_plan');">Premium Plan</a> </li>
                <? } ?>
                <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="Default Plan" data-toggle="tab" href="#default_plan" onclick="load_view('default_plan');">Default Plan</a> </li>
            </ul>
          <input type ="hidden" id="selected_view" name="selected_view">
        <div class="tab-content" id="myTab1Content"> 

            <div <?php if($tabid == '' || $tabid == 1){ ?> class="tab-pane fade in active" <?php }?> id="my_plan">
				<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
              <div class="row dt-rt">
                     <?php if(!empty($msg)){?>
                                             <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                                             $newdata = array('msg'  => '');
                                             $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
               
               <div class="col-sm-12">
                <div class="dataTables_filter pull-right" id="DataTables_Table_0_filter">
                 <label>
                     <input class="" type="hidden" name="uri_segment" id="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:'0'?>">
                    <input class="" type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext)?$searchtext:''?>">
                    <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search('changesearch');" title="Search">Search</button>
                    <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All" title="View All">View All</button>
                     </label>
                </div>
               </div>
              </div>
              <div class="row dt-rt">
              
               <div class="col-sm-4 col-xs-6 col-lg-6">
               <?php if(!empty($this->modules_unique_name) && in_array('communications_delete',$this->modules_unique_name)){?>
                <button class="btn btn-danger howler" data-type="danger" onclick="active_plan('0');" title="Add To Archive List">Add To Archive List</button>
                  <? } ?>
               </div>
             
               <div class="col-sm-8 col-xs-6 col-lg-6">
<?php if(!empty($this->modules_unique_name) && in_array('communications_delete',$this->modules_unique_name)){?>
						<a title="View Archive" class="btn  pull-right btn-success howler margin-left-5px" href="<?=base_url('admin/'.$viewname.'/view_archive');?>">View Archive</a>
 <? } ?>
 <?php if(!empty($this->modules_unique_name) && in_array('communications_add',$this->modules_unique_name)){?>
                       <a title="Add Communication" class="btn  pull-right btn-secondary-green howler " href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add Communication</a>
 <? } ?>                       
                       
                       

               </div>
              </div>
              <div id="common_div" class="table_large-responsive">
              <?=$this->load->view('admin/'.$viewname.'/ajax_list')?>
              </div>
             </div>
            </div>
            <!-- Premium Plan Tab -->
            <?php if(!empty($this->modules_unique_name) && in_array('premium_plans',$this->modules_unique_name)){?>
            <div <?php if($tabid == '2'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="premium_plan">
                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                    <?php if(!empty($msg)){?>
                        <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                        $newdata = array('msg'  => '');
                        $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
                        <div class="col-sm-1">

                        </div>
                        <div class="col-sm-12">
                            <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                <label>
                                    <input class="" type="hidden" name="uri_segment1" id="uri_segment1" value="<?=!empty($uri_segment1)?$uri_segment1:'0'?>">
                                    <input class="" type="text" name="searchtext1" id="searchtext1" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext1)?$searchtext1:''?>">
                                    <button class="btn howler" data-type="danger" onclick="contact_search1('changesearch');" title="Search">Search</button>
                                    <button class="btn howler" data-type="danger" onclick="clearfilter_contact1();" title="View All" title="View All">View All</button>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row dt-rt">
                    <?php if(!empty($this->modules_unique_name) && in_array('premium_plans_delete',$this->modules_unique_name)){?>
                        <div class="col-sm-6 col-xs-6">
                            <button class="btn btn-danger howler" data-type="danger" onclick="active_plan1('0');" title="Add To Archive List">Add To Archive List</button>
                        </div>
                       
                        
                        <div class="col-sm-6 col-xs-6">
                           <!-- <a title="Add Communication" class="btn  pull-right btn-success howler margin-left-5px" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add Communication</a>-->
                            <a title="View Archive" class="btn  pull-right btn-success howler" href="<?=base_url('admin/'.$viewname.'/view_archive');?>">View Archive</a>
                        </div>
                         <? } ?>
                    </div>
                    <div id="premium_common_div" class="table_large-responsive">
                        <?=$this->load->view('admin/'.$viewname.'/premium_ajax_list')?>
                    </div>
                </div>
            </div> <!-- End Premium Plan Tab -->
            <? } ?>
             <div <?php if($tabid == '3'){ ?> class="tab-pane fade in active" <?php } else {?> class="tab-pane fade in" <?php } ?> id="default_plan">
                <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                    <?php if(!empty($msg)){?>
                        <div class="col-sm-12 text-center" id="div_msg1"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
                        $newdata = array('msg'  => '');
                        $this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
                        <div class="col-sm-1">

                        </div>
                        <div class="col-sm-12">
                            <div class="dataTables_filter" id="DataTables_Table_0_filter">
                                <label>
                                    <input class="" type="hidden" name="uri_segment2" id="uri_segment2" value="<?=!empty($uri_segment2)?$uri_segment2:'0'?>">
                                    <input class="" type="text" name="searchtext2" id="searchtext2" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext2)?$searchtext2:''?>">
                                    <button class="btn howler" data-type="danger" onclick="contact_search2('changesearch');" title="Search">Search</button>
                                    <button class="btn howler" data-type="danger" onclick="clearfilter_contact2();" title="View All" title="View All">View All</button>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row dt-rt">
                        <div class="col-sm-6 col-xs-6">
                        <?php if(!empty($this->modules_unique_name) && in_array('communications_delete',$this->modules_unique_name)){?>
                            <button class="btn btn-danger howler" data-type="danger" onclick="active_plan2('0');" title="Add To Archive List">Add To Archive List</button>
                            <? } ?>
                        </div>
                        <div class="col-sm-6 col-xs-6">
                           <!-- <a title="Add Communication" class="btn  pull-right btn-success howler margin-left-5px" href="<?=base_url('admin/'.$viewname.'/add_record');?>">Add Communication</a>-->
                           <?php if(!empty($this->modules_unique_name) && in_array('communications_delete',$this->modules_unique_name)){?>
                            <a title="View Archive" class="btn  pull-right btn-success howler" href="<?=base_url('admin/'.$viewname.'/view_archive');?>">View Archive</a>
                             <? } ?>
                        </div>
                    </div>
                    <div id="default_common_div" class="table_large-responsive">
                        <?=$this->load->view('admin/'.$viewname.'/default_ajax_list')?>
                    </div>
                </div>
            </div> <!-- End Premium Plan Tab -->
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
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
         $("#div_msg1").fadeOut(4000);
         load_view(<?=$tabid?>);
    });
    function load_view(id)
    {
        if(id == 'my_plan' || id == '1') {
            $('#selected_view').val('1');
            $("#premium_plan").hide();
			 $("#default_plan").hide();
        }
        else if(id == 'premium_plan' || id == '2') {
            $('#selected_view').val('2');
            $("#my_plan").hide();
			$("#default_plan").hide();
			
        }
		 else if(id == 'default_plan' || id == '3') {
            $('#selected_view').val('3');
            $("#my_plan").hide();
			$("#premium_plan").hide();
        }
        
        var selected_view = $('#selected_view').val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/selectedview_session';?>",
            data: {selected_view:$('#selected_view').val()},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			},
            success: function(html){
                if(selected_view == '2')
                {
                    $("#premium_plan").show();
                    $("#div_msg1").fadeOut(4000);
                }
				else if(selected_view == '3')
                {
                    $("#default_plan").show();
                    $("#div_msg1").fadeOut(4000);
                }
				else {
                    $("#my_plan").show();
                    $("#div_msg").fadeOut(4000); 
                }
				$.unblockUI(); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
					$.unblockUI(); 
                    //$(".view_contact_popup").html('Something went wrong.');
            }
        });
        
    }
    function active_plan(id,name)
    {      
        var boxes = $('input[name="check[]"]:checked');
        if(boxes.length == '0')
        {
        $.confirm({'title': 'Alert','message': " <strong> Please select communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        //alert('Select Contacts');
        return false;}
        else
        {
            $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans();
            }},'No'	: {'class'	: 'special'}}});
        }
    } 
	 
	function active_plan_single(name,id)
    {
				if(id.length > 50)
				{
					var msg = unescape(id).substr(0, 50)+'...';
				}
				else
				{
					var msg = unescape(id);
				}
	
           $.confirm({'title': 'CONFIRM','message': ' <strong> Are you sure want to archive "'+msg+'" '+'<strong>?</strong>','buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans(name);
            }},'No'	: {'class'	: 'special'}}});
    } 
    function active_all_plans(name)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check[]"]:checked');
        $(boxes).each(function(){
            myarray[i]=this.value;
            i++;
        });

        if(name != '0')
        {
            var single_active_id = name;
			//alert(single_active_id);
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_Inactive_all';?>",
            dataType: 'json',
          //  async: false,
            data: {'myarray':myarray,'single_active_id':name,selected_view:$('#selected_view').val()},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
            success: function(data){
				$.unblockUI();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>admin/interaction_plans/"+data,
                    data: {
                        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),selected_view:$('#selected_view').val()
                    },
                    beforeSend: function() {
                        $('#common_div').block({ message: 'Loading...' }); 
                    },
                    success: function(html){
                            $("#common_div").html(html);
                            $('#common_div').unblock(); 
                    }
                });
                return false;
            },error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
        });
    }

    function active_plan1(name)
    {      
        var boxes = $('input[name="check1[]"]:checked');
        if(boxes.length == '0')
        {
        $.confirm({'title': 'Alert','message': " <strong> Please select communication "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
        //alert('Select Contacts');
        return false;}
        else
        {
            $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive record(s) "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans1();
            }},'No'	: {'class'	: 'special'}}});
        }
    } 
	 function active_plan_single1(name,id)
    {   
			if(id.length > 50)
			id = unescape(id).substr(0, 50)+'...'; 
			
			if(id.length > 50)
				{
					name = unescape(id).substr(0, 50)+'...';
					var msg = 'Are you sure want to delete '+name+'?';
				}
				else
				{
					var msg = 'Are you sure want to delete '+unescape(name)+'?';
				}
			
           $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive "+'"'+id+'"'+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
            'action': function(){
                    active_all_plans1(name);
            }},'No'	: {'class'	: 'special'}}});
    } 
	function active_plan_single2(name,id)
    {
		
				if(id.length > 50)
				{
					var msg = unescape(id).substr(0, 50)+'...';
				}
				else
				{
					var msg = unescape(id);
				}
			
	   $.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to archive "+'"'+msg+'"'+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
		'action': function(){
				active_all_plans2(name);
		}},'No'	: {'class'	: 'special'}}});
    } 
    function active_all_plans1(name)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check1[]"]:checked');
        $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
        });

        if(name != '0')
        {
                var single_active_id = name;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_Inactive_all';?>",
            dataType: 'json',
            //async: false,
            data: {'myarray':myarray,'single_active_id':name,selected_view:$('#selected_view').val()},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
            success: function(data){
				$.unblockUI();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>admin/interaction_plans/"+data,
                    data: {
                        result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),selected_view:$('#selected_view').val()
                    },
                    beforeSend: function() {
                        $('#premium_common_div').block({ message: 'Loading...' }); 
                    },
                    success: function(html){
                        $("#premium_common_div").html(html);
                        $('#premium_common_div').unblock(); 
                    }   
                });
                return false;
            },error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
        });
    }
	
    function pubunpub_data(count1,id)
{
	if(count1 == 1)
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/publish_record/"+id;
		
	}
	else
	{
		url = "<?php echo  $this->config->item('admin_base_url').$viewname; ?>/unpublish_record/"+id;
		
	}
	$.ajax({
			type: "POST",
			url :url,
			async: false,
			success: function(data){
			
				$("#view_archive_"+id).hide();
				
			
			}
	});
}

</script>
</script>
<script>
		 //function for search data
		 function delete_record()
		 {
		 	/*$.confirm({
			'title': 'Logout','message': " <strong> Are you sure you want to logout?",'buttons': {'Yes': {'class': 'special',
			'action': function(){
					$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>admin/contact/",
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
			},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					  },
				success: function(html){
				 	$("#common_div").html(html);
					$('#common_div').unblock(); 
				}
			});
			}},'No'	: {'class'	: ''}}});*/	 
		 }
		function clearfilter_contact()
		{
			$("#searchtext").val("");
			contact_search('all');
		}
		function changepages()
		{
			contact_search('');
		}
                function applysortfilte_contact(sortfilter,sorttype)
		{
			$("#sortfield").val(sortfilter);
			$("#sortby").val(sorttype);
			contact_search('changesorting');
		}
		function contact_search(allflag)
		{
                    var uri_segment = $("#uri_segment").val();
                
			$.ajax({
				type: "POST",
				url: "<?php echo base_url();?>admin/interaction_plans/"+uri_segment,
				data: {
				result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag,selected_view:$('#selected_view').val()
			},
			beforeSend: function() {
						$('#common_div').block({ message: 'Loading...' }); 
					  },
				success: function(html){
				 	$("#common_div").html(html);
					$('#common_div').unblock(); 
				}
			});
			return false;
		}
		 $(document).ready(function(){
		  $('#searchtext').keyup(function(event) 
		  {
			  /*if($("#searchtext").val().trim() != '')
				{
					contact_search();
				
				}
				else
				{
					clearfilternoresponse();	
				}*/
				
				if (event.keyCode == 13) {
						contact_search('changesearch');
				}
			//return false;
			});
			
			});
         $('body').on('click','#common_tb a.paginclass_A',function(e){
		// $("#common_tb a.paginclass_A").click(function() {
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:'',selected_view:$('#selected_view').val()
            },
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
                success: function(html){
                   
                    $("#common_div").html(html);
					$.unblockUI();
                }
            });
            return false;

        });
	$('body').on('click','#selecctall',function(e){		
	//$('#selecctall').click(function(event) {  //on click
     if(this.checked) { // check select status
         $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	function delete_all(id)
		{
			var myarray = new Array;
			var i=0;
			var boxes = $('input[name="check[]"]:checked');
			$(boxes).each(function(){
  				  myarray[i]=this.value;
				  i++;
			});
			if(id != '0')
			{
				var single_remove_id = id;
			}
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
			dataType: 'json',
			async: false,
			data: {'myarray':myarray,'single_remove_id':id},
			success: function(data){
				$.ajax({
					type: "POST",
					url: "<?php echo base_url();?>admin/interaction_plans/"+data,
					data: {
					result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:'',selected_view:$('#selected_view').val()
				},
				beforeSend: function() {
							$('#common_div').block({ message: 'Loading...' }); 
						  },
					success: function(html){
						$("#common_div").html(html);
						$('#common_div').unblock(); 
					}
				});
				return false;
			}
		});
	}
	
	function deletepopup1(id,name)
	{      
			
			var boxes = $('input[name="check[]"]:checked');
			if(boxes.length == '0' && id == '0')
			{return false;}
			
	   		if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				
				
				if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
					var msg = 'Are you sure want to delete '+name+'?';
				}
				else
				{
					var msg = 'Are you sure want to delete '+unescape(name)+'?';
				}
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	'action': function(){
				delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	} 

	$('body').on('click','.view_contacts_btn',function(e){
	
			$(".view_contact_popup").html('<div class="text-center"><img src="<?=base_url()?>images/ajaxloader.gif" /></div>');
	
			planid = $(this).attr('data-id');
			
			$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/view_contacts_of_interaction_plan';?>",
			data: {'interaction_plan':planid},
			success: function(html){
				$(".view_contact_popup").html(html);	
			},
			error: function(jqXHR, textStatus, errorThrown) {
			  	//console.log(textStatus, errorThrown);
			  	$(".view_contact_popup").html('Something went wrong.');
			}
			});
	});
	
	$('body').on('click','.pause_interaction_plan',function(e){
	
			planid = $(this).attr('data-id');
			
			$.confirm({
			'title': 'Confirm Message','message': " <strong>Warning!</strong> Are you sure want to pause communication? <br> This function will pause communication plan. Schedule will be adjusted by number of paused days!",'buttons': {'Yes': {'class': '',
			'action': function(){
			
				$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/pause_interaction_plan';?>",
				data: {'interaction_plan':planid},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					contact_search();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
				});
			
			}},'No'	: {'class'	: 'special'}}});
	});
	
	$('body').on('click','.stop_interaction_plan',function(e){
	
	planid = $(this).attr('data-id');
	
	$.confirm({
		'title': 'Confirm Message','message': " <strong> Warning! </strong>Are you sure you want to reset your communication?<br>This function will stop your communication plan, and when restarted, it will start  from the beginning!",'buttons': {'Yes': {'class': '',
		'action': function(){
		
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/stop_interaction_plan';?>",
				data: {'interaction_plan':planid},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					contact_search();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
			});
			
		}},'No'	: {'class'	: 'special'}}});
			
	});
	
	$('body').on('click','.play_interaction_plan',function(e){
	
			planid = $(this).attr('data-id');
			
			if_stop = $(this).attr('data-group');
			
			$.confirm({
			'title': 'Confirm Message','message': " <strong> Are you sure want to play communication?",'buttons': {'Yes': {'class': '',
			'action': function(){
			
				if(if_stop == 'stop')
				{
					//alert('show popup');
					$('#complate_interaction_plan_a').trigger('click');
					$('#hid_current_plan_id').val(planid);
				}
				else
				{
					$.ajax({
					type: "POST",
					url: "<?php echo $this->config->item('admin_base_url').$viewname.'/play_interaction_plan';?>",
					data: {'interaction_plan':planid},
					success: function(html){
						//$(".view_contact_popup").html(html);	
						contact_search();
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);
						//$(".view_contact_popup").html('Something went wrong.');
					}
					});
				}
			
			}},'No'	: {'class'	: 'special'}}});
	});
	
	$('body').on('click','.save_interaction_plan_popup',function(e){
		
		planid = $('#hid_current_plan_id').val();
		startdate = $('#r_next_interaction_start_date').val();
		
		$('#basicModal_for .modal-body').block({ message: 'Loading...' }); 
		
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('admin_base_url').$viewname.'/play_interaction_plan';?>",
			data: {'interaction_plan':planid,'startdate':startdate},
			success: function(html){
				//$(".view_contact_popup").html(html);	
				$('.close_plan_popup').trigger('click');
				contact_search();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				$('.close_plan_popup').trigger('click');
				console.log(textStatus, errorThrown);
				//$(".view_contact_popup").html('Something went wrong.');
			}
		});
		
		$('#basicModal_for .modal-body').unblock();
		
	});

</script>
<a style="display:none;" id="complate_interaction_plan_a" href="#basicModal_for" data-toggle="modal" ></a>
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Assigned Contacts</h3>
      </div>
      <div class="modal-body">
        <div class="cf"></div>
        <div class="col-sm-12 view_contact_popup">
          
		  <div class="text-center">
		  	<img src="<?=base_url()?>images/ajaxloader.gif" />
		  </div>
		  
		  <?php /*?><?php $this->load->view('admin/interaction_plans/view_contact_popup');?><?php */?>
		  
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<div aria-hidden="true" style="display: none;" id="basicModal_for" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_plan_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Reschedule Communication</h3>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
		
		<input type="hidden" value="" id="hid_current_plan_id" name="hid_current_plan_id" />
		
          <table class="pdn11" width="100%" border="0" cellspacing="0" cellpadding="0">
		  	
			<tr>
              <td></td>
              <td class="form-group">
			   Plan Start Date:
               <input id="r_next_interaction_start_date" name="r_next_interaction_start_date" class="form-control parsley-validated" readonly="readonly" type="text" value="">
              </td>
            </tr>
			
          </table>
		 
        </div>
		<div class="col-sm-12 text-center mrgb4">
			<input type="submit" value="Save" class="btn btn-secondary save_interaction_plan_popup">
		  </div>
      </div>
      
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<script type="text/javascript">
$(function(){
	$( "#r_next_interaction_start_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		minDate: 0,
		changeYear: true,
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
/*Premium Plan*/
function clearfilter_contact1()
{
    $("#searchtext1").val("");
    contact_search1('all');
}
function changepages1()
{
    contact_search1('');
}
function applysortfilte_contact1(sortfilter,sorttype)
{
    $("#sortfield1").val(sortfilter);
    $("#sortby1").val(sorttype);
    contact_search1('changesorting');
}
function contact_search1(allflag)
{
    var uri_segment = $("#uri_segment1").val();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/interaction_plans/"+uri_segment,
        data: {
            result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:allflag,selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
                $('#premium_common_div').block({ message: 'Loading...' }); 
        },
        success: function(html){
                $("#premium_common_div").html(html);
                $('#premium_common_div').unblock(); 
        }
    });
    return false;
}
$(document).ready(function(){
    $('#searchtext1').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search1('changesearch');
        }
        //return false;
    });
});
$('body').on('click','#common_tb1 a.paginclass_A',function(e){
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:'',selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#premium_common_div").html(html);
            $.unblockUI();
        }
    });
    return false;
});
$('body').on('click','#selecctall1',function(e){		
    if(this.checked) { // check select status
        $('.mycheckbox1').each(function() { //loop through each checkbox
               this.checked = true;  //select all checkboxes with class "mycheckbox"              
        });
    }else{
        $('.mycheckbox1').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
        });        
    }
});
function delete_all1(id)
{
    var myarray = new Array;
    var i=0;
    var boxes = $('input[name="check[]"]:checked');
    $(boxes).each(function(){
        myarray[i]=this.value;
        i++;
    });
    if(id != '0')
    {
        var single_remove_id = id;
    }

    $.ajax({
    type: "POST",
    url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
    dataType: 'json',
    async: false,
    data: {'myarray':myarray,'single_remove_id':id},
    success: function(data){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/interaction_plans/"+data,
            data: {
                result_type:'ajax1',searchreport1:$("#searchreport1").val(),perpage1:$("#perpage1").val(),searchtext1:$("#searchtext1").val(),sortfield1:$("#sortfield1").val(),sortby1:$("#sortby1").val(),allflag1:'',selected_view:$('#selected_view').val()
            },
            beforeSend: function() {
                    $('#premium_common_div').block({ message: 'Loading...' }); 
              },
            success: function(html){
                    $("#premium_common_div").html(html);
                    $('#premium_common_div').unblock(); 
            }
        });
        return false;
    }
});
}
function deletepopup2(id,name)
{      
    var boxes = $('input[name="check[]"]:checked');
    if(boxes.length == '0' && id == '0')
    {return false;}

    if(id == '0')
    {
        var msg = 'Are you sure want to delete record(s)';
    }
    else
    {
		
		
		if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
					var msg = 'Are you sure want to delete '+name+'?';
				}
				else
				{
					var msg = 'Are you sure want to delete '+unescape(name)+'?';
				}
		
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            delete_all1(id);
        }},'No'	: {'class'	: 'special'}}
    });
}

$('body').on('click','.pause_interaction_plan1',function(e){
	
    planid = $(this).attr('data-id');

    $.confirm({
    'title': 'Confirm Message','message': " <strong>Warning!</strong> Are you sure want to pause communication? <br> This function will pause communication plan. Schedule will be adjusted by number of paused days!",'buttons': {'Yes': {'class': '',
    'action': function(){

            $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/pause_interaction_plan';?>",
            data: {'interaction_plan':planid},
            success: function(html){
                    //$(".view_contact_popup").html(html);	
                    contact_search1('');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                    //$(".view_contact_popup").html('Something went wrong.');
            }
            });

    }},'No'	: {'class'	: 'special'}}});
});
	
$('body').on('click','.stop_interaction_plan1',function(e){
	
    planid = $(this).attr('data-id');

    $.confirm({
        'title': 'Confirm Message','message': " <strong> Warning! </strong>Are you sure you want to reset your communication?<br>This function will stop your communication plan, and when restarted, it will start  from the beginning!",'buttons': {'Yes': {'class': '',
        'action': function(){

            $.ajax({
                type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/stop_interaction_plan';?>",
                data: {'interaction_plan':planid},
                success: function(html){
                        //$(".view_contact_popup").html(html);	
                        contact_search1('');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        //$(".view_contact_popup").html('Something went wrong.');
                }
            });

        }},'No'	: {'class'	: 'special'}}
    });
});
	
$('body').on('click','.play_interaction_plan',function(e){

    planid = $(this).attr('data-id');

    if_stop = $(this).attr('data-group');

    $.confirm({
        'title': 'Confirm Message','message': " <strong> Are you sure want to play communication?",'buttons': {'Yes': {'class': '',
        'action': function(){

            if(if_stop == 'stop')
            {
                    //alert('show popup');
                    $('#complate_interaction_plan_a').trigger('click');
                    $('#hid_current_plan_id').val(planid);
            }
            else
            {
                $.ajax({
                type: "POST",
                url: "<?php echo $this->config->item('admin_base_url').$viewname.'/play_interaction_plan';?>",
                data: {'interaction_plan':planid},
                success: function(html){
                        //$(".view_contact_popup").html(html);	
                        contact_search1('');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                        console.log(textStatus, errorThrown);
                        //$(".view_contact_popup").html('Something went wrong.');
                }
                });
            }

        }},'No'	: {'class'	: 'special'}}
    });
});

function update_plans(id)
{
	$.confirm({'title': 'CONFIRM','message': " <strong>  Are you sure want to update the plan ? ",'buttons': {'Yes': {'class': '',
	   'action': function(){
	window.location = '<?php echo $this->config->item('admin_base_url').$viewname;?>/premium_plan_update/'+id;
}},'No'	: {'class'	: 'special'}}});

}
</script>
<script>
/*default Plan*/
function active_plan2(name)
	{      
			var boxes = $('input[name="check2[]"]:checked');
			if(boxes.length == '0')
			{
			$.confirm({'title': 'Alert','message': " <strong> Please select communication plan"+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});	
			//alert('Select Contacts');
			return false;}
			else
			{
				$.confirm({'title': 'CONFIRM','message': " <strong> Are you sure want to add record(s) to list "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							active_all_plans2();
						}},'No'	: {'class'	: 'special'}}});
				//active_all_plans();
			}
	} 
	 function active_all_plans2(name)
    {
        var myarray = new Array;
        var i=0;
        var boxes = $('input[name="check2[]"]:checked');
        $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
        });

        if(name != '0')
        {
                var single_active_id = name;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_Inactive_all';?>",
            dataType: 'json',
            //async: false,
            data: {'myarray':myarray,'single_active_id':name,selected_view:$('#selected_view').val()},
			beforeSend: function() {
				$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
			  },
            success: function(data){
				$.unblockUI();
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url();?>admin/interaction_plans/"+data,
                    data: {
                        result_type:'ajax2',searchreport2:$("#searchreport2").val(),perpage2:$("#perpage2").val(),searchtext2:$("#searchtext2").val(),sortfield2:$("#sortfield2").val(),sortby2:$("#sortby2").val(),selected_view:$('#selected_view').val()
                    },
                    beforeSend: function() {
                        $('#default_common_div').block({ message: 'Loading...' }); 
                    },
                    success: function(html){
                        $("#default_common_div").html(html);
                        $('#default_common_div').unblock(); 
                    }   
                });
                return false;
            },error: function(jqXHR, textStatus, errorThrown) {
				$.unblockUI();
			}
        });
    }
function clearfilter_contact2()
{
    $("#searchtext2").val("");
    contact_search2('all');
}
function changepages2()
{
    contact_search2('');
}
function applysortfilte_contact2(sortfilter,sorttype)
{
    $("#sortfield2").val(sortfilter);
    $("#sortby2").val(sorttype);
    contact_search2('changesorting');
}
function contact_search2(allflag)
{
    var uri_segment = $("#uri_segment2").val();

    $.ajax({
        type: "POST",
        url: "<?php echo base_url();?>admin/interaction_plans/"+uri_segment,
        data: {
            result_type:'ajax2',searchreport2:$("#searchreport2").val(),perpage2:$("#perpage2").val(),searchtext2:$("#searchtext2").val(),sortfield2:$("#sortfield2").val(),sortby2:$("#sortby2").val(),allflag2:allflag,selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
                $('#default_common_div').block({ message: 'Loading...' }); 
        },
        success: function(html){
                $("#default_common_div").html(html);
                $('#default_common_div').unblock(); 
        }
    });
    return false;
}
$(document).ready(function(){
    $('#searchtext2').keyup(function(event) 
    {
        if (event.keyCode == 13) {
            contact_search2('changesearch');
        }
        //return false;
    });
});
$('body').on('click','#common_tb2 a.paginclass_A',function(e){
    $.ajax({
        type: "POST",
        url: $(this).attr('href'),
        data: {
            result_type:'ajax2',searchreport2:$("#searchreport2").val(),perpage2:$("#perpage2").val(),searchtext2:$("#searchtext2").val(),sortfield2:$("#sortfield2").val(),sortby2:$("#sortby2").val(),allflag2:'',selected_view:$('#selected_view').val()
        },
        beforeSend: function() {
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        },
        success: function(html){
            $("#default_common_div").html(html);
            $.unblockUI();
        }
    });
    return false;
});
$('body').on('click','#selecctall2',function(e){		
    if(this.checked) { // check select status
        $('.mycheckbox2').each(function() { //loop through each checkbox
               this.checked = true;  //select all checkboxes with class "mycheckbox"              
        });
    }else{
        $('.mycheckbox2').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
        });        
    }
});
function delete_all2(id)
{
    var myarray = new Array;
    var i=0;
    var boxes = $('input[name="check[]"]:checked');
    $(boxes).each(function(){
        myarray[i]=this.value;
        i++;
    });
    if(id != '0')
    {
        var single_remove_id = id;
    }

    $.ajax({
    type: "POST",
    url: "<?php echo $this->config->item('admin_base_url').$viewname.'/ajax_delete_all';?>",
    dataType: 'json',
    async: false,
    data: {'myarray':myarray,'single_remove_id':id},
    success: function(data){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url();?>admin/interaction_plans/"+data,
            data: {
                result_type:'ajax2',searchreport2:$("#searchreport2").val(),perpage2:$("#perpage2").val(),searchtext2:$("#searchtext2").val(),sortfield2:$("#sortfield2").val(),sortby2:$("#sortby2").val(),allflag2:'',selected_view:$('#selected_view').val()
            },
            beforeSend: function() {
                    $('#default_common_div').block({ message: 'Loading...' }); 
              },
            success: function(html){
                    $("#default_common_div").html(html);
                    $('#default_common_div').unblock(); 
            }
        });
        return false;
    }
});
}
function deletepopup2(id,name)
{      
    var boxes = $('input[name="check[]"]:checked');
    if(boxes.length == '0' && id == '0')
    {return false;}

    if(id == '0')
    {
        var msg = 'Are you sure want to delete record(s)';
    }
    else
    {
		
		if(name.length > 50)
				{
					name = unescape(name).substr(0, 50)+'...';
					var msg = 'Are you sure want to delete '+name+'?';
				}
				else
				{
					var msg = 'Are you sure want to delete '+unescape(name)+'?';
				}
		
    }
    $.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
        'action': function(){
            delete_all2(id);
        }},'No'	: {'class'	: 'special'}}
    });
}
</script>