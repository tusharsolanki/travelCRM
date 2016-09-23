<?php 
    /*
        @Description: user task list
        @Author: Mohit Trivedi
        @Date: 06-08-14
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	$.unblockUI();
});
</script>
<?php
$viewname = $this->router->uri->segments[2];
$viewlist = $this->router->uri->segments[3];
$viewname1='emails';
$user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
?>

 <div id="content">
  <div id="content-header">
   <h1><?=htmlentities(ucfirst($list[0]['plan_name']))?> : Queued Emails</h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=!empty($list[0]['plan_name'])?htmlentities(ucfirst($list[0]['plan_name'])):''?> Queued Emails</h3>
	   <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back"><?php echo $this->lang->line('common_back_title')?></a> </span>
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
            <!--<ul class="nav nav-tabs" id="myTab1">
              <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?>> <a title="Contact Information" data-toggle="tab" href="#mydata">
                Action Plan Queued List
                </a> </li>
              <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="Contact Photo and Documents" data-toggle="tab" href="#defaultdata">
                Email Campaign Queued List
                </a> </li>
            </ul>-->
            <?php if(!empty($viewlist) && $viewlist == 'queued_list') { ?>
            <div class="row dt-rt">
              <?php if(!empty($msg)){?>
              <div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div>
              <?php } ?>
            </div>
            <div class="tab-content" id="myTab1Content">
              <div class="" id="mydata" >
                <div class="table_large-responsive">
                  <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                      <div class="col-sm-1"> </div>
                      <div class="col-sm-11">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                          <label>
                            <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search...">
                            <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row dt-rt">
                      <div class="col-sm-6">
                      
                      </div>
                      <div class="col-sm-6"> </div>
                    </div>
                    <div id="common_div">
                      <?=$this->load->view('user/'.$viewname1.'/ajax_queued_list')?>
                    </div>
                  </div>
                </div>
              </div>
               <?php }elseif(!empty($viewlist) && $viewlist == 'interaction_queued_list') { ?>
              
              <div class="tab-content" id="myTab1Content">
              <div class="" id="mydata" >
                <div class="table_large-responsive">
                  <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                      <div class="col-sm-1"> </div>
                      <div class="col-sm-11">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                          <label>
                            <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search...">
                            <button class="btn btn-secondary howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row dt-rt">
                      <div class="col-sm-6">
                      
                      </div>
                      <div class="col-sm-6"> </div>
                    </div>
                    <div id="common_div">
                      <?=$this->load->view('user/'.$viewname1.'/ajax_interaction_queued_list')?>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>
              <!--End tab 1 -->
              <!--<div class="<?php if($tabid == 2){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="defaultdata" >
                <div class="table_large-responsive">
                  <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
                    <div class="row dt-rt">
                      <div class="col-sm-1"> </div>
                      <div class="col-sm-11">
                        <div class="dataTables_filter" id="DataTables_Table_0_filter">
                          <label>
                            <input type="text" name="default_searchtext" id="default_searchtext" aria-controls="DataTables_Table_0" title="Search Text" placeholder="Search...">
                            <button class="btn btn-secondary howler" data-type="danger" onclick="default_search();" title="Search">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" onclick="default_clearfilter_contact();" title="View All">View All</button>
                          </label>
                        </div>
                      </div>
                    </div>
                    
                    <div id="default_common_div">
                      <?php //$this->load->view('user/'.$viewname.'/ajax_queued_list')?>
                    </div>
                  </div>
                </div>
              </div>-->
              <!--End tab 2 --> 
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
    });
	
	function default_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/<?=$viewname?>/queued_list/",
			data: {
			result_type:'ajax',perpage:$("#default_perpage").val(),searchtext:$("#default_searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
		  $('#default_searchtext').keyup(function(event) 
		  {	
				if (event.keyCode == 13) {
						default_search();
				}
		  });
	});
	
	function default_clearfilter_contact()
	{
		$("#default_searchtext").val("");
		default_search();
	}
	
	function default_changepages()
	{
		default_search();	
	}
	
  	function default_applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		default_search();
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',perpage:$("#default_perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val()
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
		
	//$('#selecctall').click(function(event) {  //on click
	$('body').on('click','#selecctall',function(e){
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

</script>

<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	
	function contact_search()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>user/<?=$viewname?>/<?=$viewlist?>/<?=$this->uri->segment(4)?>",
			data: {
			result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_name").val(),sortby:$("#sort").val()
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
						contact_search();
				}
			//return false;
		  });
	});
	
	function clearfilter_contact()
	{
		$("#searchtext").val("");
		contact_search();
	}
	
	function changepages()
	{
		contact_search();	
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield_name").val(sortfilter);
		$("#sort").val(sorttype);
		contact_search();
	}
	
	//$("#common_tb a.paginclass_A").click(function() {
	$('body').on('click','#common_tb_interaction a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield_name").val(),sortby:$("#sort").val()
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
		
	//$('#selecctall').click(function(event) {  //on click
	$('body').on('click','#selecctall',function(e){
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

</script>