<?php 
    /*
        @Description: superadmin Envelope list
        @Author: Mit Makwana
        @Date: 12-08-2014
    */
	
if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script language="javascript">
$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
$(document).ready(function(){
	try{
		var id = parent.$("#slt_interaction_type").val();
		//parent.selecttemplate(id,'selected')	
		parent.selectcategory(id,'selected');
	 }
	 catch(err) {
        // Handle error(s) here
    }
	parent.parent.$('.close_contact_select_popup').trigger('click');
	$.unblockUI();
});
</script>
<?php

$viewname = $this->router->uri->segments[2];
$superadmin_session = $this->session->userdata($this->lang->line('common_superadmin_session_label'));
?>
<style type="text/css">
.search_property div label{margin-right: 10px;}
</style>
 <div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('mls_data');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i><?=$this->lang->line('mls_data');?></h3>
        <?php /*?><span class="float-right margin-top--15"> <a title="Add Admin" class="btn pull-right btn-secondary-green howler " href="<?=base_url('superadmin/'.$viewname.'/add_record');?>">Add Property Data</a>
         </span> <?php */?> 
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content">
       <div class="table_large-responsive">
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         <div class="row dt-rt">
				<?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
    	 
         </div>
         <div class="portlet-content">
            <ul class="nav nav-tabs" id="myTab1">
              <li <?php if($tabid == '' || $tabid == 1){?> class="active" <?php } ?> > <a title="MLS Amenity" data-toggle="tab" href="#mls_amenity" onclick ="contact_search('changesearch','1');">MLS Amenity</a> </li>      
               <li <?php if($tabid == 2){?> class="active" <?php } ?>> <a title="MLS Area Community" data-id="calls" data-toggle="tab" href="#mls_area_community" onclick ="contact_search('changesearch','2');">MLS Area Community</a> </li>
               <li <?php if($tabid == 3){?> class="active" <?php } ?>> <a title="MLS Office" data-toggle="tab" href="#mls_office" onclick ="contact_search('changesearch','3');">MLS Office</a> </li>
               <li <?php if($tabid == 6){?> class="active" <?php } ?>> <a title="MLS Member" data-toggle="tab" href="#mls_member" onclick ="contact_search('changesearch','6');">MLS Member</a> </li>
               <li <?php if($tabid == 5){?> class="active" <?php } ?>> <a title="MLS School" data-toggle="tab" href="#mls_school" onclick ="contact_search('changesearch','5');">MLS School</a> </li>
               <li <?php if($tabid == 4){?> class="active" <?php } ?>> <a title="MLS Property History" data-toggle="tab" href="#mls_property_history" onclick ="contact_search('changesearch','4');">MLS Property History</a> </li>
               <li <?php if($tabid == 7){?> class="active" <?php } ?>> <a title="MLS Property Listing" data-toggle="tab" href="#mls_property_list" onclick ="contact_search('changesearch','7');">MLS Property Listing</a> </li>
           </ul>
         <div class="tab-content" id="myTab1Content">
         	<!-- MLS Amenity Tab -->
         	<div class="<?php if($tabid == '' || $tabid == 1){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_amenity" >
                <div class="row dt-rt">
                  <div class="col-sm-12 search_property">
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_1" id="mls_type_1" onchange="return mls_search('1')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="property_type_1" id="property_type_1" onchange="return mls_search('1')">
                              <option value="">Please Select Property Type</option>
                              <option <?if(!empty($property_type) && $property_type == 'BUSO'){ echo 'selected="selected"';}?> value="BUSO">BUSO</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'RESI'){ echo 'selected="selected"';}?> value="RESI">RESI</option>
                              <option <?if(!empty($property_type) && $property_type == 'COND'){ echo 'selected="selected"';}?> value="COND">COND</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'MULT'){ echo 'selected="selected"';}?> value="MULT">MULT</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'VACL'){ echo 'selected="selected"';}?> value="VACL">VACL</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'FARM'){ echo 'selected="selected"';}?> value="FARM">FARM</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'MANU'){ echo 'selected="selected"';}?> value="MANU">MANU</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'COMI'){ echo 'selected="selected"';}?> value="COMI">COMI</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Equi'){ echo 'selected="selected"';}?> value="Equi">EQUI</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Vaca'){ echo 'selected="selected"';}?> value="Vaca">VACA</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Land'){ echo 'selected="selected"';}?> value="Land">LAND</option>  
                              <option <?if(!empty($property_type) && $property_type == 'Prec'){ echo 'selected="selected"';}?> value="Prec">PREC</option>  
                            </select>
                        </label>
                    </div>
                  </div>
                </div>
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_1" id="searchtext_1" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 1) !empty($searchtext)?$searchtext:'' ?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','1');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <!--<div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Amenity" onclick="deletepopup1('0');">Delete MLS Amenity</button>
                  </div>-->
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_1">
                 	<?php 
						if($tabid == '' || $tabid == 1)
							echo $this->load->view('superadmin/'.$viewname.'/ajax_list');
					?>
                 </div>
            </div>
            <!-- END Tab -->
            
            <!-- MLS Area Community -->
            <div class="<?php if($tabid == 2){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_area_community" >
                <div class="row dt-rt">
                  <div class="col-sm-12 search_property">
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_2" id="mls_type_2" onchange="return mls_search('2')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                    </div>
                  </div>
                </div>
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_2" id="searchtext_2" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 2) !empty($searchtext)?$searchtext:'' ?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','2');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                 <!-- <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Area Community" onclick="deletepopup1('0');">Delete MLS Area Community</button>
                  </div>-->
                 </div>
                 <div id="common_div_2">
                 	<?php 
						if($tabid == 2)
				 			echo $this->load->view('superadmin/'.$viewname.'/mls_area_community_ajax_list');
					?>
                 </div>
            </div>
            <!-- END Tab -->
            
            <!-- MLS Office -->
            <div class="<?php if($tabid == 3){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_office" >
                <div class="row dt-rt">
                  <div class="col-sm-12 search_property">
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_3" id="mls_type_3" onchange="return mls_search('3')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                    </div>
                  </div>
                </div>
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_3" id="searchtext_3" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 3) !empty($searchtext)?$searchtext:'' ?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','3');" value="">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                 <!-- <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Office" onclick="deletepopup1('0');">Delete MLS Office</button>
                  </div>-->
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_3">
                 <?php 
					if($tabid == 3)
                 		echo $this->load->view('superadmin/'.$viewname.'/mls_office_ajax_list');
				 ?>
                 </div>
            </div>
           	<!-- END Tab -->
            <!-- MLS Memmber -->
            <div class="<?php if($tabid == 6){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_member" >
                 <div class="row dt-rt">
                  <div class="col-sm-12 search_property">
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_6" id="mls_type_6" onchange="return mls_search('6')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                    </div>
                  </div>
                </div>
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_6" id="searchtext_6" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 6) !empty($searchtext)?$searchtext:'' ?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','6');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <!--<div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Member" onclick="deletepopup1('0');">Delete MLS Member</button>
                  </div>-->
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_6">
         <?php 
          if($tabid == 6)
                    echo $this->load->view('superadmin/'.$viewname.'/mls_member_ajax_list');
         ?>
                 </div>
            </div>
            <!-- END Tab -->
            <!-- MLS School -->
            <div class="<?php if($tabid == 5){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_school" >
                <div class="row dt-rt">
                  <div class="col-sm-12 search_property">
                    <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_5" id="mls_type_5" onchange="return mls_search('5')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                    </div>
                  </div>
                </div>
                 <div class="row dt-rt">
                  <div class="col-sm-12">
                   <div class="dataTables_filter" id="DataTables_Table_0_filter">
                    <label>
                        <input class="searchtext" type="text" name="searchtext_5" id="searchtext_5" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 5) !empty($searchtext)?$searchtext:'' ?>">
                            <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','6');">Search</button>
                            <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                    </label>
                   </div>
                  </div>
                 </div>
                 <div class="row dt-rt">
                  <!--<div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS School" onclick="deletepopup1('0');">Delete MLS School</button>
                  </div>-->
                  <div class="col-sm-6">
                  </div>
                 </div>
                 <div id="common_div_5">
                 <?php 
					if($tabid == 5)
                 		echo $this->load->view('superadmin/'.$viewname.'/mls_school_ajax_list');
				?>
                 </div>
            </div>
            <!-- END Tab -->
            
            <!-- MLS Property History -->
            <div class="<?php if($tabid == 4){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_property_history" >
             <div class="row dt-rt">
              <div class="col-sm-12 search_property">
                <div class="dataTables_filter" id="">
                  <label>
                    <select class="form-control parsley-validated" name="mls_type_4" id="mls_type_4" onchange="return mls_search('4')">
                     <option value="">Select MLS</option>
                     <?php foreach($mls_type_data as $row){
                      ?>
                      <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                      <? }?>
                      
                    </select>
                  </label>
                </div>
                <div class="dataTables_filter" id="">
                  <label>
                    <select class="form-control parsley-validated" name="property_type_4" id="property_type_4" onchange="return mls_search('4')">
                      <option value="">Please Select Property Type</option>
                      <option <?if(!empty($property_type) && $property_type == 'BUSO'){ echo 'selected="selected"';}?> value="BUSO">BUSO</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'RESI'){ echo 'selected="selected"';}?> value="RESI">RESI</option>
                      <option <?if(!empty($property_type) && $property_type == 'COND'){ echo 'selected="selected"';}?> value="COND">COND</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'MULT'){ echo 'selected="selected"';}?> value="MULT">MULT</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'VACL'){ echo 'selected="selected"';}?> value="VACL">VACL</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'FARM'){ echo 'selected="selected"';}?> value="FARM">FARM</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'MANU'){ echo 'selected="selected"';}?> value="MANU">MANU</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'COMI'){ echo 'selected="selected"';}?> value="COMI">COMI</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'Equi'){ echo 'selected="selected"';}?> value="Equi">EQUI</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'Vaca'){ echo 'selected="selected"';}?> value="Vaca">VACA</option>                                           
                      <option <?if(!empty($property_type) && $property_type == 'Land'){ echo 'selected="selected"';}?> value="Land">LAND</option>  
                      <option <?if(!empty($property_type) && $property_type == 'Prec'){ echo 'selected="selected"';}?> value="Prec">PREC</option>  
                    </select>
                  </label>
                </div>
              </div>
            </div>
            <div class="row dt-rt">
              <div class="col-sm-12">
               <div class="dataTables_filter" id="DataTables_Table_0_filter">
                <label>
                  <input class="searchtext" type="text" name="searchtext_4" id="searchtext_4" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 4) !empty($searchtext)?$searchtext:'' ?>">
                  <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','4');">Search</button>
                  <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                </label>
              </div>
            </div>
          </div>
          <div class="row dt-rt">
                 <!-- <div class="col-sm-6">
                   <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Property History" onclick="deletepopup1('0');">Delete MLS Property History</button>
                 </div>-->
                 <div class="col-sm-6">
                 </div>
               </div>
               <div id="common_div_4">
                 <?php 
                 if($tabid == 4)
                  echo $this->load->view('superadmin/'.$viewname.'/mls_property_history_ajax_list');
                 ?>
               </div>
             </div>
            <!-- END Tab -->
            <!-- MLS Property Listing -->
            <div class="<?php if($tabid == 7){?> tab-pane fade in active<?php }else{ ?> tab-pane fade in <?php } ?>" id="mls_property_list" >
                  <div class="row dt-rt">
                    <div class="row dt-rt">
                    <div class="col-sm-12 search_property">
                      <div class="dataTables_filter" id="DataTables_Table_0_filter">
                        <label>
                          
                          <select class="form-control parsley-validated" name="max_price_7" id="max_price_7" onchange="return mls_search('7')">
                              <option selected="selected" value="">Maximum Price</option>
                              <?php $i=50000;
                              while($i <= 500000){ ?>
                                  <option value="<?=number_format($i)?>" <?php if(!empty($max_price) && $max_price == number_format($i)) echo "selected=selected"; ?> > $<?=number_format($i)?> </option>
                              <?php
                              if(100000 > $i)
                                  $i = $i + 10000;
                             else
                                  $i = $i + 25000;
                              } ?>
                              <option value="5000000" <?php if(!empty($max_price) && $max_price == $i) echo "selected=selected"; ?> > $5,000,000 </option>
                          </select>
                        </label>
                      </div>
                     <div class="dataTables_filter" id="DataTables_Table_0_filter">
                        <label>
                        <select class="form-control parsley-validated" name="min_price_7" id="min_price_7" onchange="return mls_search('7')">
                            <option selected="selected" value="">Minimum Price</option>
                            <?php $i=50000;
                            while($i <= 500000){ ?>
                                <option value="<?=number_format($i)?>" <?php if(!empty($min_price) && $min_price == number_format($i)) echo "selected=selected"; ?> > $<?=number_format($i)?> </option>
                            <?php
                            if(100000 > $i)
                                $i = $i + 10000;
                           else
                                $i = $i + 25000;
                            } ?>
                            <option value="5000000" <?php if(!empty($min_price) && $min_price == $i) echo "selected=selected"; ?> > $5,000,000 </option>
                        </select>        
                        </label>
                      </div>
                      <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="property_type_7" id="property_type_7" onchange="return mls_search('7')">
                              <option value="">Please Select Property Type</option>
                              <option <?if(!empty($property_type) && $property_type == 'BUSO'){ echo 'selected="selected"';}?> value="BUSO">BUSO</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'RESI'){ echo 'selected="selected"';}?> value="RESI">RESI</option>
                              <option <?if(!empty($property_type) && $property_type == 'COND'){ echo 'selected="selected"';}?> value="COND">COND</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'MULT'){ echo 'selected="selected"';}?> value="MULT">MULT</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'VACL'){ echo 'selected="selected"';}?> value="VACL">VACL</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'FARM'){ echo 'selected="selected"';}?> value="FARM">FARM</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'MANU'){ echo 'selected="selected"';}?> value="MANU">MANU</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'COMI'){ echo 'selected="selected"';}?> value="COMI">COMI</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Equi'){ echo 'selected="selected"';}?> value="Equi">EQUI</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Vaca'){ echo 'selected="selected"';}?> value="Vaca">VACA</option>                                           
                              <option <?if(!empty($property_type) && $property_type == 'Land'){ echo 'selected="selected"';}?> value="Land">LAND</option>  
                              <option <?if(!empty($property_type) && $property_type == 'Prec'){ echo 'selected="selected"';}?> value="Prec">PREC</option>  
                            </select>
                        </label>
                      </div>
                      <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="property_status_7" id="property_status_7" onchange="return mls_search('7')">
                              <option value="">Please Select Property Status</option>
                              <option <?if(!empty($property_status) && $property_status == 'A'){ echo 'selected="selected"';}?> value="A">A</option>                                           
                              <option <?if(!empty($property_status) && $property_status == 'S'){ echo 'selected="selected"';}?>  value="S">S</option>
                              <option <?if(!empty($property_status) && $property_status == 'Close'){ echo 'selected="selected"';}?>  value="Close">Close</option>                                           
                              <option <?if(!empty($property_status) && $property_status == 'Activ'){ echo 'selected="selected"';}?>  value="Activ">Active</option>                                           
                              <option <?if(!empty($property_status) && $property_status == 'In Du'){ echo 'selected="selected"';}?>  value="In Du">In Due Diligence</option>                                           
                            </select>
                        </label>
                      </div>
                      <div class="dataTables_filter" id="">
                        <label>
                            <select class="form-control parsley-validated" name="mls_type_7" id="mls_type_7" onchange="return mls_search('7')">
                                 <option value="">Select MLS</option>
                                  <?php foreach($mls_type_data as $row){
                                    ?>
                                 <option <?if(!empty($mls_type) && $mls_type == $row['id']){ echo 'selected="selected"';}?> value="<?php echo $row['id'];?>"><?php echo $row['mls_name'];?></option>
                                  <? }?>
                                  
                            </select>
                        </label>
                      </div>

                    </div>
                  </div>
                    <div class="col-sm-12">
                    <div class="dataTables_filter" id="DataTables_Table_0_filter">
                        <label>
                          <button class="btn btn-secondary howler" data-type="danger" title="Search" onclick="contact_search('changesearch','7');">Search</button>
                          <button class="btn btn-secondary howler" data-type="danger" title="View All" onclick="clearfilter_contact();">View All</button>
                        </label>
                      </div>
                      <div class="dataTables_filter" id="DataTables_Table_0_filter">
                        <label>
                          <input class="searchtext" type="text" name="searchtext_7" id="searchtext_7" title="Search Text" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?php if($tabid == 7) {echo !empty($searchtext)?$searchtext:'';} ?>">
                        </label>
                     </div>
                      
                    </div>
                  </div>
                  
                 <div class="row dt-rt">
                  <div class="col-sm-4">
                   <!-- <button class="btn btn-danger howler" data-type="danger" title="Delete MLS Property" onclick="deletepopup1('0');">Delete MLS Property</button> -->
                  </div>
                  <div class="col-sm-8">
                     <div class="dataTables_filter" id="DataTables_Table_0_filter">
                      <label> 
                  <? /* <a title="Import Property" class="btn btn-secondary-green howler" href="<?=base_url('superadmin/'.$viewname.'/import');?>">Import Property</a> */ ?>
                 
                      </label>
                     </div>
                    </div>
                 </div>
                 <div id="common_div_7">
                 <?php 
        					if($tabid == 7)
                         		echo $this->load->view('superadmin/'.$viewname.'/mls_property_list_ajax_list');
        				 ?>
                 </div>
            </div>
            <!-- END Tab -->
         </div>
         <input type="hidden" id="tab_id" name="tab_id" value="<?=!empty($tabid)?$tabid:'1'?>" />
         <input type="hidden" id="url" name="url" value="<?php echo base_url('superadmin/'.$viewname);?>" />
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
  <!-- .-header --> 
  
  <!-- /#content-container --> 
  
 </div>
 
<!--<script type="text/javascript" src="<?=$this->config->item('js_path')?>script.js"></script> -->
<script>
    $(document).ready(function(){
	 $("#div_msg").fadeOut(4000); 
    });
	//Search by mls
  function mls_search(tab)
  {
      contact_search('changesearch',tab);
  }
	function contact_search(allflag,tab)
	{
		$("#tab_id").val(tab);
		$.ajax({
			type: "POST",
			url: $("#url").val(),
			data: {
			result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val(),allflag:allflag,tab:tab,
      mls_type:$("#mls_type_"+tab).val(),property_type:$("#property_type_"+tab).val(),property_status:$("#property_status_"+tab).val(),
      max_price:$("#max_price_"+tab).val(),min_price:$("#min_price_"+tab).val()
		},
		beforeSend: function() {
					//$('#common_div_'+tab).block({ message: 'Loading...' }); 
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				  },
			success: function(html){
				$("#common_div_"+tab).html(html);
				//$('#common_div_'+tab).unblock(); 
				$.unblockUI();
			}
		});
		return false;
	}
	/*$('body').on('click','#myTab1 li a',function(e){
		contact_search('changesearch',$(this).attr('data-id'));
	});*/
	
	
	$('body').on('click','.common_tb a.paginclass_A',function(e){
		//alert($(this).attr('href'));
	 	//alert($("#uri_segment_5").val());
	   	sanjaytest($(this).attr('href'));
	   	return false;
    });
	
	
	function sanjaytest(url)
	{
		var tab = $("#tab_id").val();
		 $.ajax({
			type: "POST",
			url: url,
			data: {
			result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val(),tab:tab,
      mls_type:$("#mls_type_"+tab).val(),property_type:$("#property_type_"+tab).val(),property_status:$("#property_status_"+tab).val(),
      max_price:$("#max_price_"+tab).val(),min_price:$("#min_price_"+tab).val()
		},
		beforeSend: function() {
					$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
				  },
			success: function(html){
				$("#common_div_"+tab).html(html);
				$.unblockUI();
			}
		});
		return false;
	}
	
	 $(document).ready(function(){
		  $('.searchtext').keyup(function(event) 
		  {	
				if (event.keyCode == 13) {
						contact_search('changesearch',$("#tab_id").val());
				}
		  });
	});
	
	function clearfilter_contact()
	{
		var tab = $("#tab_id").val();
		$("#searchtext_"+tab).val("");
    $("#mls_type_"+tab).val("");
    $("#property_type_"+tab).val("");
    $("#property_status_"+tab).val("");
    $("#max_price_"+tab).val("");
    $("#min_price_"+tab).val("");
    
		contact_search('all',tab);
	}
	
	function changepages()
	{
		contact_search('',$("#tab_id").val());
	}
	
  	function applysortfilte_contact(sortfilter,sorttype)
	{
		var tab = $("#tab_id").val();
		$("#sortfield_"+tab).val(sortfilter);
		$("#sortby_"+tab).val(sorttype);
		contact_search('changesorting',tab);
	}
	
	$('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.mycheckbox_'+$("#tab_id").val()).each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "mycheckbox"              
            });
        }else{
            $('.mycheckbox_'+$("#tab_id").val()).each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "mycheckbox"                      
            });        
        }
    });
	
	function delete_all(id)
	{
		//alert($("#url").val());
		var tab = $("#tab_id").val();
		var myarray = new Array;
		var i=0;
		var boxes = $('input[name="check_'+$("#tab_id").val()+'[]"]:checked');
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
		url: "<?=base_url('superadmin/'.$viewname.'/ajax_delete_all')?>",
		dataType: 'json',
		async: false,
		data: {'myarray':myarray,single_remove_id:id,tab:tab},
		success: function(data){
			$.ajax({
				type: "POST",
				url: $("#url").val()+'/'+data,
				data: {
				result_type:'ajax',perpage:$("#perpage_"+tab).val(),searchtext:$("#searchtext_"+tab).val(),sortfield:$("#sortfield_"+tab).val(),sortby:$("#sortby_"+tab).val(),allflag:'',tab:tab
			},
			beforeSend: function() {
						$.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
					  },
				success: function(html){
					$("#common_div_"+tab).html(html);
					$.unblockUI();
				}
			});
			return false;
		}
		});
	}
	
	function deletepopup1(id,name)
	{
			var boxes = $('input[name="check_'+$("#tab_id").val()+'[]"]:checked');
			if(boxes.length == '0' && id== '0')
			{
				$.confirm({'title': 'Alert','message': " <strong> Please select record(s) to delete. "+"<strong></strong>",'buttons': {'ok'	: {'class'	: 'btn_center alert_ok'}}});
				$('#selecctall').focus();
				return false;
				
			}
			if(id == '0')
			{
				var msg = 'Are you sure want to delete record(s)';
			}
			else
			{
				name  = unescape(name);
				if(name.length > 50)
					name = name.substr(0, 50)+'...';
				var msg = 'Are you sure want to delete "'+name+'"';
			}
				$.confirm({'title': 'CONFIRM','message': " <strong> "+msg+" "+"<strong>?</strong>",'buttons': {'Yes': {'class': '',
	   'action': function(){
							delete_all(id);
						}},'No'	: {'class'	: 'special'}}});
	}
  //price
  $(function(){

        var all_values=[];

        var initial_options=$('#min_price_7').get(0).options;
        for(i=0; i<initial_options.length; i++)
        {
            var val=initial_options[i].value;
            var lbl=initial_options[i].label;       
            all_values[i]={value: val, label: lbl };

        }

        document.getElementById('min_price_7').options;//$('#sf-size-min').get(0).options;
        
        $('#min_price_7').change(function(){
            var $src=$('#min_price_7');
            var $target=$('#max_price_7');
            var prev_max_value=$target.val();
            var current_min_value=$src.val();
            if(!current_min_value)
            {
                $target.get(0).options[0]=new Option("Maximum Price", "");
                for(i=1; i<all_values.length;  i++)
                {
                    $target.get(0).options[i]=new Option(all_values[i].label, all_values[i].value);
                }
            }
            else
            {
                //clear max drop down list
                $target.get(0).options.length=0;
                $target.get(0).options[0]=new Option("Maximum Price", "");
                var j=1;
                for(i=$src.get(0).selectedIndex; i<all_values.length; i++)
                {
                    $target.get(0).options[j++]=new Option(all_values[i].label, all_values[i].value);
                }
            }
            $target.val(prev_max_value);
        });

        $('#max_price_7').change(function(){
            var $src=$('#max_price_7');
            var $target=$('#min_price_7');
            var prev_min_value=$target.val();
            var current_max_value=$src.val();
            if(!current_max_value)
            {
                for(i=0; i<all_values.length;  i++)
                {
                    $target.get(0).options[i]=new Option(all_values[i].label, all_values[i].value);
                }
            }
            else
            {
                //clear min drop down list
                $target.get(0).options.length=0;
                $target.get(0).options[0]=new Option("Minimum Price", "");
                var j=1;
                for(i=0; i<all_values.length;  i++)
                {
                    if (parseInt(current_max_value) >= parseInt(all_values[i].value))
                        $target.get(0).options[j++]=new Option(all_values[i].label, all_values[i].value);
                }
            }
            $target.val(prev_min_value);
        });


    });
</script>