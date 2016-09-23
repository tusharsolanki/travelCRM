<?php
/*
    @Description: Template add/edit page
    @Author: Mohit Trivedi
    @Date: 13-08-2014

*/?>
<?php 
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
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog modal-dialog_lg modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title add_title">Add Data</h3>
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
    <h1>
      Add Data
    </h1>
  </div>
  <div id="content-container" class="addnewcontact">
    <div class="">
      <div class="col-md-12">
        
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i>
              <?php /*?><?php if(empty($editRecord)){ echo $this->lang->line('label_templete_add_head');}
	   else if(!empty($insert_data)){ echo $this->lang->line('label_templete_add_head'); } 
	   else{ echo $this->lang->line('label_templete_edit_head'); }?><?php */?>
       MLS Data
            </h3>
				<span class="float-right margin-top--15"><a href="javascript:void(0)" onclick="history.go(-1)" class="btn btn-secondary" title="Back" id="back">Back</a> </span>
          </div>
          <div class="portlet-content">
            <div class="col-sm-12">
              <div class="tab-content" id="myTab1Content">
               	<div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_amenity_data')?>" title="Retrieve Amenity Data">Retrieve Amenity Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_area_community_data')?>" title="Retrieve Area Community Data">Retrieve Area Community Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_listing_data')?>" title="Retrieve Listing Data">Retrieve Listing Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_image_data')?>" title="Retrieve Image Data">Retrieve Image Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_listing_history_data')?>" title="Retrieve Listing History Data">Retrieve Listing History Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_member_data')?>" title="Retrieve Member Data">Retrieve Member Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_office_data')?>" title="Retrieve Office Data">Retrieve Office Data</a> 
                </div>
                <div class="col-sm-12 margin-top-10">
                	<a class="btn btn-secondary-green" href="<?=base_url('superadmin/mls/retrieve_school_data')?>" title="Retrieve School Data">Retrieve School Data</a> 
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
<script>
$('.tab-content a').click(function(){
	setdefaultdata();
});
function setdefaultdata()
{
	$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
		
}
</script>