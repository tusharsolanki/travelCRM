<?php
/*
    @Description: Joomla property cron setting add/edit page
    @Author     : Sanjay Moghariya
    @Date       : 18-11-2014
*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$editRecordId = !empty($this->router->uri->segments[4])?$this->router->uri->segments[4]:'';
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
<script type="text/javascript" src="<?php echo base_url();?>js/autocomplete/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input.css" type="text/css" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles/token-input-facebook.css" type="text/css" />
<style>
.ui-multiselect{width:100% !important;}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/multiselect/jquery.multiselect.filter.css" />
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/multiselect/jquery.multiselect.filter.js"></script>

<div id="content">
    <div id="content-header">
        <h1><?=$this->lang->line('joomla_property_cron_header');?></h1>
    </div>
    <div id="content-container" class="addnewcontact">
        <div class="">
            <div class="col-md-12">
	
                <div class="portlet">
                    <div class="portlet-header">
                        <h3> <i class="fa fa-tasks"></i> <?php if(empty($editRecord)){ echo $this->lang->line('joomla_property_cron_add_head');}
                         else if(!empty($insert_data)){ echo $this->lang->line('joomla_property_cron_add_head'); } 
                         else{ echo $this->lang->line('joomla_property_cron_edit_head'); }?> </h3>
                         <span class="float-right margin-top--15">
                        <?php
                        if(!empty($editRecord))
                        {?>
                                <a title="View Contact" class="btn btn-secondary" href="<?= $this->config->item('admin_base_url').$viewname; ?>/view_record/<?=$editRecordId?>"><?php echo $this->lang->line('common_view_title')?></a>
                        <?php }
                         ?>
                        <a href="javascript:void(0)" onclick="history.go(-1)" title="Back" class="btn btn-secondary">Back</a> </span>

                    </div>
                    <!-- /.portlet-header -->

                    <div class="portlet-content">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTab1Content">
                                <div class="row tab-pane fade in active" id="home">
                                    <form class="form parsley-form" data-validate="parsley" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path?>" novalidate >
                                        <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                        <input id="data_from" name="data_from" type="hidden" value="1">
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_property_cron_name');?><span class="val">*</span></label>
                                                    <input id="name" name="name" class="form-control parsley-validated" type="text" value="<?php 
                                                        if(!empty($editRecord[0]['name'])){ echo htmlentities($editRecord[0]['name']);}?>" data-required="true" placeholder="e.g. Market Report Name">
                                                </div>
                                            </div>
                                            <?php /*
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_property_cron_country');?><span class="val">*</span></label>
                                                    <input id="country" name="country" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['country'])){ echo $editRecord[0]['country']; }?>" data-required="true" placeholder="Country">
                                                </div>
                                            </div>
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_property_cron_state');?><span class="val">*</span></label>
                                                    <input id="state" name="state" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['state'])){ echo $editRecord[0]['state']; }?>" data-required="true" placeholder="State">
                                                </div>
                                            </div>
                                             */?>
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_state');?><span class="val">*</span></label>
                                                    <input id="city" name="city" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['city'])){ echo $editRecord[0]['city']; }?>" data-required="true" placeholder="State" autocomplete="off"> <!--onkeyup="getcitylist(this.value);"-->
                                                    <div id="city_suggestions" style="display: none;"> <div id="city_suggestionsList" class="token-input-dropdown-facebook"> &nbsp; </div></div>
                                                </div>
                                                <script type="text/javascript">
                                                    var common = 0;
                                                    $(document).ready(function() {
                                                        //$("#city").tokenInput("clear");
                                                        $("#city").tokenInput('<?php echo $this->config->item('admin_base_url').$viewname.'/getcitylist';?>',
                                                        {
                                                            prePopulate:[
                                                                    <?php 
                                                                    if(!empty($editRecord[0]['city'])){ ?>
                                                                            {id: "<?=$editRecord[0]['city']?>", name: "<?=$editRecord[0]['city']?>"},
                                                                    <?php } ?>
                                                            ],
                                                            preventDuplicates: true,
                                                            hintText: "Enter State",
                                                            noResultsText: "No State Found",
                                                            searchingText: "Searching...",
                                                            theme: "facebook",
                                                            tokenLimit: 1
                                                        });
                                                    });
                                                 </script>
                                                <?php /*
                                                <script type="text/javascript">
                                                    var common = 0;
                                                    $(document).ready(function() {
                                                           $("#city").tokenInput([ 
                                                           <?php 
                                                                   if(!empty($editRecord[0]['city'])){ ?>
                                                                           {name: "<?=$editRecord[0]['city']?>"},
                                                                   <?php } ?>
                                                                   <?php 
                                                                   if(!empty($city_response)){
                                                                   foreach($city_response as $rowtrans){ ?>
                                                                           {name: "<?=$rowtrans['city']?>"},
                                                                   <?php } } ?>
                                                           ],
                                                           {prePopulate:[
                                                                   <?php 
                                                                   if(!empty($city_response)){
                                                                   foreach($city_response as $rowtrans){ ?>
                                                                           {name: "<?=$rowtrans['city']?>"},
                                                                   <?php } } ?>
                                                           ],onAdd: function (item) {
                                                                   common++;
                                                                   //alert(common);
                                                           },
                                                           onResult: function (item) {
                                                                   try{
                                                                           if($.isEmptyObject(item)){
                                                                                     //return [{id:'NEWTAG-'+common+'{^}'+$("tester").text(),name: $("tester").text()}]
                                                                           }else{
                                                                                     return item
                                                                           }
                                                                   }
                                                                   catch(e)
                                                                   {

                                                                   }

                                                           },
                                                           preventDuplicates: true,
                                                           hintText: "Enter City",
                                                            noResultsText: "City Not Found",
                                                            searchingText: "Searching...",
                                                           theme: "facebook"}
                                                           );
                                                   //$("#email_to").attr("placeholder","Enter Contact Name");
                                           });
                                           </script>
                                                 */ ?>
                                            </div>
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('common_label_address');?><span class="val">*</span></label>
                                                    <input id="neighborhood" name="neighborhood" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['neighborhood'])){ echo $editRecord[0]['neighborhood']; }?>" data-required="true" placeholder="Address" autocomplete="off"> <!--onkeyup="getneighborlist(this.value);"-->
                                                    <div id="neighbor_suggestions" style="display: none;"> <div id="neighbor_suggestionsList" class="token-input-dropdown-facebook"> &nbsp; </div></div>
                                                </div>
                                                <script type="text/javascript">
                                                    var common = 0;
                                                    $(document).ready(function() {
                                                        //$("#neighborhood").tokenInput("clear");
                                                        $("#neighborhood").tokenInput('<?php echo $this->config->item('admin_base_url').$viewname.'/getneighborlist';?>',
                                                        {
                                                            prePopulate:[
                                                                <?php 
                                                                if(!empty($editRecord[0]['neighborhood'])){ ?>
                                                                        {id: "<?=$editRecord[0]['neighborhood']?>", name: "<?=$editRecord[0]['neighborhood']?>"},
                                                                <?php } ?>
                                                            ],
                                                            preventDuplicates: true,
                                                            hintText: "Enter Address",
                                                            noResultsText: "No Address Found",
                                                            searchingText: "Searching...",
                                                            theme: "facebook",
                                                            tokenLimit: 1
                                                        });
                                                    });
                                                 </script>
                                            </div>
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_property_cron_zip');?><span class="val">*</span></label>
                                                    <input id="zip_code" maxlength="6" onkeypress="return isNumberKey(event);" name="zip_code" class="form-control parsley-validated" type="text" value="<?php if(!empty($editRecord[0]['zip_code'])){ echo $editRecord[0]['zip_code']; }?>" data-required="true" placeholder="Zip Code"  autocomplete="off" > <!-- onkeyup="getzipcodelist(this.value);" -->
                                                    <div id="zipcode_suggestions" style="display: none;"> <div id="zipcode_suggestionsList" class="token-input-dropdown-facebook"> &nbsp; </div></div>
                                                </div>
                                                <script type="text/javascript">
                                                    var common = 0;
                                                    $(document).ready(function() {
                                                        $("#zip_code").tokenInput('<?php echo $this->config->item('admin_base_url').$viewname.'/getzipcodelist';?>',
                                                        {
                                                            prePopulate:[
                                                                <?php 
                                                                if(!empty($editRecord[0]['zip_code'])){ ?>
                                                                        {id: "<?=$editRecord[0]['zip_code']?>", name: "<?=$editRecord[0]['zip_code']?>"},
                                                                <?php } ?>
                                                            ],
                                                            preventDuplicates: true,
                                                            hintText: "Enter Zipcode",
                                                            noResultsText: "Zipcode Not Found",
                                                            searchingText: "Searching...",
                                                            theme: "facebook",
                                                            tokenLimit: 1
                                                        });
                                                    });
                                                 </script>
                                            </div>
                                            <div class='row'>
                                                <div class="col-sm-12 form-group">
                                                    <label for="text-input"><?=$this->lang->line('joomla_property_cron_radius');?> <?=$this->lang->line('joomla_property_cron_radius_unit');?></label>
                                                    <input id="radius_limit" name="radius_limit" class="form-control parsley-validated" maxlength="5" type="text" value="<?php if(!empty($editRecord[0]['radius_limit'])){ echo $editRecord[0]['radius_limit']; }?>" onkeypress="return isNumberKey(event)" placeholder="Radius Limit (e.g. 1)">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                   
                                                    <div class="col-sm-12 form-group">
                                                        <label for="text-input"><?=$this->lang->line('joomla_property_cron_assign_contact');?><span class="val">*</span></label>
                                                        <div class="col-sm-12 topnd_margin1"> <a data-toggle="modal" class="text_color_red text_size" href="#basicModal"><i class="fa fa-plus-square"></i> Select Contacts</a> </div>
                                                        <div class="col-sm-12 added_contacts_list">

                                                            <?php $this->load->view('admin/'.$viewname.'/selected_contact_ajax')?>

                                                        </div>
                                                    </div>
                                                    
                                                    <?php /*
                                                    <select class="form-control parsley-validated ui-widget-header" multiple="multiple" name='slt_user[]' id='slt_user'>
                                                      <?php if(isset($userlist) && count($userlist) > 0){
                                                                foreach($userlist as $row){
                                                                    if(!empty($row['id'])){?>
                                                                        <option value='<?php echo $row['id'];?>' <?php if(isset($slt_user) && is_array($slt_user) && in_array($row['id'],$slt_user)){ echo "selected";}?> ><?php if($row['admin_name']!='') { echo ucwords($row['admin_name']." (".$row['email_id'].")");}else{ echo ucwords($row['user_name']." (".$row['email_id'].")");}?></option>
                                                        <?php 		}
                                                                }
                                                            } ?>
                                                    </select>
                                                     * 
                                                     */?>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="">
                                                    <div class="col-sm-6"> <label for="text-input"><?= $this->lang->line('joomla_property_cron_crontype')?></label></div>
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            Weekly
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="Weekly" class=""  id="cron_type" name="cron_type" <?php if(!empty($editRecord[0]['cron_type']) && $editRecord[0]['cron_type'] == 'Weekly'){ echo 'checked="checked"'; }?> <?php if(empty($editRecord[0]['cron_type'])){ echo "checked='checked'"; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="clear">
                                                    <div class="col-sm-6">
                                                    </div>         
                                                    <div class="col-sm-6 checkbox">
                                                        <label class="">
                                                            Monthly
                                                            <div class="float-left margin-left-15">
                                                                <input type="radio" value="Monthly" class=""  id="cron_type1" name="cron_type" <?php if(!empty($editRecord[0]['cron_type']) && $editRecord[0]['cron_type'] == 'Monthly'){ echo 'checked="checked"'; }?>>
                                                            </div>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <input id="id" name="id" type="hidden" value="<?php if(!empty($editRecord[0]['id'])){ echo $editRecord[0]['id']; }?>">
                                               </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 pull-left text-center margin-top-10">
                                            <!--<a class="btn btn-secondary" href="#">Save Contact</a>-->
                                            <input type="hidden" id="contacttab" name="contacttab" value="1" />
                                            <input type="hidden" id="finalcontactlist" name="finalcontactlist" value="" />
                                            <input type="submit" class="btn btn-secondary-green" value="Save" title="Save" onclick="return checkcontactcount();" name="submitbtn" id="jpc_submitbtn" />
                                             <a class="btn btn-primary" title="Cancel" href="javascript:history.go(-1);" id="jpc_cancel">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.portlet-content --> 
      
    </div>
</div>
</div>
</div>
  <!-- #content-header --> 
  
  <!-- /#content-container --> 
  
 </div>
 
<div aria-hidden="true" style="display: none;" id="basicModal" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close_contact_select_popup" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i></button>
        <!--   <button type="button" data-dismiss="modal" aria-hidden="true" class="close btn btn-xs btn-primary"> <i class="fa fa-times"></i> </button>-->
        <h3 class="modal-title">Contact Select</h3>
      </div>
      <div class="modal-body">
      
        <div class="con_srh">
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_contact_popup_ajax" class="form-control inputsrh pull-left" name="search_contact_popup_ajax">
		   <a class="btn btn-success a_search_contacts mrg13" href="javascript:void(0);">Search Contacts</a>
		   <button class="btn btn-secondary howler pull-right" data-type="danger" onclick="clearfilter_contact();">View All</button>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	<div class="col-sm-4">
            	<select class="form-control parsley-validated" name='contact_type' id='contact_type' onchange="contact_search();">
                	<option value="">Contact Type</option>
                    <?php if(!empty($contact_type)){
                    		foreach($contact_type as $row){ ?>
                            	<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
                           	<?php } 
						 } ?>
             	</select>
            </div>
            <div class="col-sm-4">
           	 	<select class="form-control parsley-validated" name='slt_contact_source' id='slt_contact_source' onchange="contact_search();">
                	<option value="">Contact Source</option>
                    <?php if(!empty($source_type)){
							foreach($source_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				    <?php } ?>
             	</select>
            </div>
            <div class="col-sm-4">
             <select class="form-control parsley-validated" name="slt_contact_status" id="slt_contact_status" onchange="contact_search();">
				   <option value="">Contact Status</option>
                    <?php if(!empty($status_type)){
							foreach($status_type as $row){?>
								<option value="<?=$row['id']?>"><?=ucwords($row['name']);?></option>
							<?php } ?>
				   <?php } ?>
			 </select>
            </div>
		   </div>
        </div>
        
        <div class="con_srh clear">
        <div class="col-sm-3">Search Tag</div>
          <div class="main">
            <input type="text" placeholder="Search Contacts" id="search_tag" class="form-control inputsrh pull-left" name="search_tag">
            <script type="text/javascript">
			 $(document).ready(function() {
				$("#search_tag").tokenInput([ 
				<?php 
					if(!empty($all_tag_trans_data) && count($all_tag_trans_data) > 0){
			 		foreach($all_tag_trans_data as $row){ ?>
						{id: '<?=$row['tag']?>', name: "<?=$row['tag']?>"},
					<?php } } ?>
				],
				{
						onAdd: function (item) {
						contact_search();
					},onDelete: function (item) {
						contact_search();
					},
					preventDuplicates: true,
					hintText: "Enter Tag Name",
					noResultsText: "No Tag Found",
					searchingText: "Searching...",
					theme: "facebook"
				}
				);
				
				//$("#token-input-search_tag").attr("placeholder","Search Tag");
				
			//$("#email_to").attr("placeholder","Enter Contact Name");
			});
			</script>
		   </div>
        </div>
        
        <div class="row dt-rt">
          <div class="col-sm-12 table-responsive">
          	 <div class="col-sm-10">
          	  <label id="count_selected_to"></label> | 
                  <a class="text_color_red text_size add_email_address" onclick="remove_selection_to();" title="Remove Selected" href="javascript:void(0);">Remove Selected</a>
             </div>
          </div>
        </div>
        
        <div class="cf"></div>
        <div class="col-sm-12 add_new_contact_popup">
          <div class="table-responsive">
		  <?php $this->load->view('admin/'.$viewname.'/add_contact_popup_ajax');?>
		  </div>
        </div>
      </div>
      <div class="col-sm-12 text-center mrgb4">
        <button type="button" class="btn btn-success" onclick="addcontactstovaluation();">Assign Contacts</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
 
<script type="text/javascript">
    
$(document).ready(function(){
    $('#count_selected_to').text(popupcontactlist.length + ' Record selected');
});
	var arraydatacount = 0;
	var popupcontactlist = Array();
        var err_msg = '0';
	
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
		$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
    });

	$('body').on('click','#common_tb a.paginclass_A',function(e){
		    $.ajax({
                type: "POST",
                url: $(this).attr('href'),
				data: {
                result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),search_tag:$("#search_tag").val()
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
	
	$('body').on('click','.a_search_contacts',function(e){
		contact_search();
	});
	// view All Recored
	function clearfilter_contact()
	{
		$("#search_tag").tokenInput("clear");
		$("#search_contact_popup_ajax").val("");
		$('#slt_contact_status').val("");
		$('#slt_contact_source').val("");
		$('#contact_type').val("");
		contact_search();
	}
	function changepages()
	{
		contact_search('');
	}
	
	function applysortfilte_contact(sortfilter,sorttype)
	{
		$("#sortfield").val(sortfilter);
		$("#sortby").val(sorttype);
		contact_search();
	}


	function contact_search()
	{
	
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/<?=$viewname?>/search_contact_ajax/",
			data: {
			result_type:'ajax',searchtext:$("#search_contact_popup_ajax").val(),perpage:$("#perpage").val(),contact_status:$('#slt_contact_status').val(),contact_source:$('#slt_contact_source').val(),contact_type:$('#contact_type').val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),search_tag:$("#search_tag").val()
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
	
	function addcontactstovaluation()
	{
		$.ajax({
			type: "POST",
			url: "<?php echo base_url();?>admin/<?=$viewname?>/add_contacts_to_valuation/",
			data: {
			result_type:'ajax',contacts:popupcontactlist
		},
		beforeSend: function() {
					$('.added_contacts_list').block({ message: 'Loading...' }); 
					$('.close_contact_select_popup').trigger('click');
				  },
			success: function(html){
				
				$(".added_contacts_list").html(html);
				$('.added_contacts_list').unblock(); 
			}
		});
	}
        
        function checkbox_checked(contact_id)
	{
		if($('.mycheckbox:checkbox[value='+parseInt(contact_id)+']:checked').length)
		{		
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex == -1)
			{				
				popupcontactlist[arraydatacount++] = parseInt(contact_id);
			}
		}
		else
		{
			var arrayindex = jQuery.inArray( parseInt(contact_id), popupcontactlist );
			//alert(this.value+'-'+JSON.stringify(popupcontactlist));
			if(arrayindex >= 0)
			{
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
			}
		}
		$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
	}
	
	//function removeempfromlist(contactid)
	$('body').on('click','.remove_selected_contact',function(e){
		var myvalue = $(this).data("group");
		var trans_id = $(this).data("id");
		var mytr = $(this).closest("tr");
		
		$.confirm({
		'title': 'Delete','message': " <strong> Are you sure want to remove contact?",'buttons': {'Yes': {'class': '',
		'action': function(){
			
			/*alert(myvalue);*/
			var arrayindex = jQuery.inArray( myvalue, popupcontactlist );
			//alert(myvalue+'-'+JSON.stringify(popupcontactlist));
			//alert(arrayindex);
			if(arrayindex >= 0)
			{
				$('.mycheckbox:checkbox[value='+parseInt(myvalue)+']').attr('checked',false);
				popupcontactlist.splice( arrayindex, 1 );
				arraydatacount--;
				mytr.remove();
			}
			$('#count_selected_to').text(popupcontactlist.length + ' Record selected');
			
			$.ajax({
				type: "POST",
				url: "<?php echo $this->config->item('admin_base_url').$viewname.'/delete_contact_from_valuation';?>",
				data: {'trans_id':trans_id,'contact_id':myvalue},
				success: function(html){
					//$(".view_contact_popup").html(html);	
					//contact_search();
					//window.location.reload();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log(textStatus, errorThrown);
					//$(".view_contact_popup").html('Something went wrong.');
				}
			});
				
		}},'No'	: {'class'	: 'special'}}});
		
		
		
		return false;
	});
	
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if(charCode > 31 && (charCode < 48 || charCode > 57))
                return false;

            return true;
        }
	/*$("#interaction_plans").submit(function(e) {
	  
	  	checkcontactcount();
	  
	});*/
        function checkcontactcount()
	{
            //alert(JSON.stringify(popupemplist));
            if(popupcontactlist == '')
            {
                $('#jpc_submitbtn').attr('disabled','disabled');
                    $.confirm({'title': 'Alert','message': " <strong> Please select atleast one contact. "+"<strong></strong>",
                        'buttons': {'ok'	: {'class'	: 'btn_center alert_ok',
                            'action': function(){
                            $('#jpc_submitbtn').removeAttr('disabled'); }
                    }}});
                return false;
            }
            else {
                if ($('#<?php echo $viewname?>').parsley().isValid()) {
                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                    var city = $('#city').val();
                    var neighborhood = $('#neighborhood').val();
                    var zip_code = $('#zip_code').val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo $this->config->item('admin_base_url').$viewname.'/check_address';?>",
                        data: {'city':city,'neighbor':neighborhood,'zipcode':zip_code},
                        success: function(html){
                            $.unblockUI();
                            if(html == '1')
                            {
                                $('#jpc_submitbtn').attr('disabled','disabled');
                                $.confirm({'title': 'Alert','message': " <strong> Please input proper state or select from suggestion box. "+"<strong></strong>",
                                    'buttons': {'ok'	: {'class'	: 'btn_center alert_ok',
                                        'action': function(){
                                            $('#jpc_submitbtn').removeAttr('disabled');
                                            $('#city').focus();
                                        }
                                }}});
                                return false;
                            }
                            else if(html == '2')
                            {
                                $('#jpc_submitbtn').attr('disabled','disabled');
                                $.confirm({'title': 'Alert','message': " <strong> Please input proper address or select from suggestion box. "+"<strong></strong>",
                                    'buttons': {'ok'	: {'class'	: 'btn_center alert_ok',
                                        'action': function(){
                                            $('#jpc_submitbtn').removeAttr('disabled'); 
                                            $('#neighborhood').focus();
                                        }
                                }}});
                                return false;
                            }
                            else if(html == '3')
                            {
                                $('#jpc_submitbtn').attr('disabled','disabled');
                                $.confirm({'title': 'Alert','message': " <strong> Please input proper zipcode or select from suggestion box. "+"<strong></strong>",
                                    'buttons': {'ok'	: {'class'	: 'btn_center alert_ok',
                                        'action': function(){
                                            $('#jpc_submitbtn').removeAttr('disabled'); 
                                            $('#zip_code').focus();
                                        }
                                }}});
                                return false;
                            }
                            else
                            {
                                if ($('#<?php echo $viewname?>').parsley().isValid()) {
                                    $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                                }
                                $('#finalcontactlist').val(popupcontactlist);
                                $('#<?php echo $viewname?>').submit();
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                    //$('#finalcontactlist').val(popupcontactlist);
                    //return true;
                    return false;
                }
            }
	}
        
        <?php 
        if(!empty($editRecord[0]['id']) && !empty($contacts_data)){
            foreach($contacts_data as $row){?>
                var arrayindex = jQuery.inArray( "<?=!empty($row['id'])?$row['id']:''?>", popupcontactlist );
                if(arrayindex == -1)
                {
                    $('.mycheckbox:checkbox[value='+<?=!empty($row['id'])?$row['id']:''?>+']').attr('checked',true);				
                    popupcontactlist[arraydatacount++] = <?=!empty($row['id'])?$row['id']:''?>;
                }
        <?php }
        }
        ?>

	
        
$(document).ready(function(){
	$( "#txt_task_date" ).datepicker({
		showOn: "button",
		changeMonth: true,
		changeYear: true,
		yearRange: "-100:+2",
		//minDate: "0",
		buttonImage: "<?=base_url('images');?>/calendar.png",
		dateFormat:'mm/dd/yy',
		buttonImageOnly: false
	});
});
</script>
<script type="text/javascript">
	   $("select#slt_user").multiselect({
		}).multiselectfilter();
		
function select_box()
{
	var abc = $("#slt_user").multiselect("widget").find(":checkbox").filter(':checked').length;
	/*if(popupcontactlist != '')
	{
		if(abc > 1)
		{
			$.confirm({'title': 'Alert','message': " <strong> Contacts assign to only one user. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;
		}
	}*/
	if(abc > 0)
	{
            if ($('#<?php echo $viewname?>').parsley().isValid()) {
                $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
                /*$('#jpc_submitbtn').attr('disabled','disabled');
                $('#jpc_cancel').attr('disabled','disabled');*/
            }
            $('.parsley-form').submit();
	}
	else
	{
		$.confirm({'title': 'Alert','message': " <strong> Please select atleast one user "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
					return false;

	}
}
var xhr = null;
function getcitylist(city_name){
    if(city_name.length == 0) {
        $('#city_suggestions').fadeOut();
    } else {
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        xhr = $.ajax({
            type: "POST",
            //crossDomain: true, // enable this
            //dataType: 'json',
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/getcity_nei_ziplist';?>",
            data: {city:city_name,search_name:'city'},
            beforeSend: function() {
                $('#city').block({ message: 'Loading...' }); 
            },
            success: function(msg){
                if(msg.length >0) {
                    $('#city_suggestions').fadeIn();
                    $('#city_suggestionsList').html(msg);
                    $('#city').unblock();
                }
            }
        });
    }
}
function fill(thisValue) {
    $('#city').val(thisValue);
    setTimeout("$('#city_suggestions').fadeOut();", 400);
}
function fillId(thisValue) {
    //$('#country_id').val(thisValue);
    setTimeout("$('#city_suggestions').fadeOut();", 400);
}

function getneighborlist(neighbor){
    if(neighbor.length == 0) {
        $('#neighbor_suggestions').fadeOut();
    } else {
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        xhr = $.ajax({
            type: "POST",
            //crossDomain: true, // enable this
            //dataType: 'json',
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/getcity_nei_ziplist';?>",
            data: {neighbor:neighbor,search_name:'neighbor'},
            beforeSend: function() {
                $('#neighborhood').block({ message: 'Loading...' }); 
            },
            success: function(msg){
                if(msg.length >0) {
                    $('#neighbor_suggestions').fadeIn();
                    $('#neighbor_suggestionsList').html(msg);
                    $('#neighborhood').unblock();
                }
            }
        });
    }
}
function filln(thisValue) {
    $('#neighborhood').val(thisValue);
    setTimeout("$('#neighbor_suggestions').fadeOut();", 400);
}
function fillIdn(thisValue) {
    //$('#country_id').val(thisValue);
    setTimeout("$('#neighbor_suggestions').fadeOut();", 400);
}

function getzipcodelist(zipcode){
    if(zipcode.length == 0) {
        $('#zipcode_suggestions').fadeOut();
    } else {
        if( xhr != null ) {
            xhr.abort();
            xhr = null;
        }
        xhr = $.ajax({
            type: "POST",
            //crossDomain: true, // enable this
            //dataType: 'json',
            url: "<?php echo $this->config->item('admin_base_url').$viewname.'/getcity_nei_ziplist';?>",
            data: {zipcode:zipcode,search_name:'zipcode'},
            beforeSend: function() {
                $('#zip_code').block({ message: 'Loading...' }); 
            },
            success: function(msg){
                if(msg.length >0) {
                    $('#zipcode_suggestions').fadeIn();
                    $('#zipcode_suggestionsList').html(msg);
                    $('#zip_code').unblock();
                }
            }
        });
    }
}
function fillz(thisValue) {
    $('#zip_code').val(thisValue);
    setTimeout("$('#zipcode_suggestions').fadeOut();", 400);
}
function fillIdz(thisValue) {
    //$('#country_id').val(thisValue);
    setTimeout("$('#zipcode_suggestions').fadeOut();", 400);
}

</script>