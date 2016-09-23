<?php
/*
    @Description: Lead Capturing 
    @Author: Mohit Trivedi
    @Date: 17-09-2014

*/?>
<?php //pr($formdata);exit;?>
<?php 
$viewname = $this->router->uri->segments[2];
if(!empty($this->router->uri->segments[5]))
	$tabid = $this->router->uri->segments[5];
?>
<div id="content">
  <div id="content-header">
   <h1></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=ucwords($form[0]['form_title']).':'.Lead?></h3>
	  <!-- <span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary">Back</a> </span>-->
	  <span class="pull-right"><a title="Back" class="btn btn-secondary" href="javascript:void(0)" onclick="history.go(-1)""><?php echo $this->lang->line('common_back_title')?></a> </span>       
       
      </div>
      <!-- /.portlet-header -->
      <div class="portlet-content">
       <div class="table-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
		 <?php if(!empty($msg)){?>
			<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
			$newdata = array('msg'  => '');
			$this->session->set_userdata('message_session', $newdata);?> 
			</div><?php } ?>
          <div class="col-sm-1"></div>
          <div class="col-sm-12">
           <div class="dataTables_filter" id="DataTables_Table_0_filter">
            <label>
           <input type="text" name="searchtext" id="searchtext" aria-controls="DataTables_Table_0" placeholder="Search..." />
			<button class="btn howler" data-type="danger" onclick="contact_search();" title="Search">Search</button>
			<button class="btn howler" data-type="danger" onclick="clearfilter_contact();" title="View All">View All</button>
		</label>
           </div>
          </div>
         </div>
         <div class="row dt-rt">
         </div>
         <div id="common_div">
         <?=$this->load->view('user/'.$viewname.'/ajax_list_view')?>
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
 </div>

