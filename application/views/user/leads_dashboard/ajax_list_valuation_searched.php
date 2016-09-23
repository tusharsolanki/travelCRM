<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed'); 

$viewname = $this->router->uri->segments[2];
?>

<div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
            <div class="col-lg-12">
              <div class="table-responsive">
                <div class="table-in-responsive">
                    <!-- table code start-->
                    <div class="row dt-rt">
                       <div class="col-lg-12 col-sm-12 col-xs-12">
                           <div class="dataTables_filter" id="DataTables_Table_0_filter">
                               <label>
                                   <input class="" type="hidden" name="uri_segment5" id="uri_segment5" value="<?=!empty($uri_segment5)?$uri_segment5:'0'?>">
                                   <input class="" type="text" name="searchtext5" id="searchtext5" aria-controls="DataTables_Table_0" placeholder="Search..." value="<?=!empty($searchtext5)?$searchtext5:''?>" />
                                   <button class="btn howler" data-type="danger" onclick="contact_search5('changesearch');" title="Search Contacts">Search</button>
                                   <button class="btn howler" data-type="danger" onclick="clearfilter_contact5();" title="View All Contacts">View All</button>
                               </label>
                           </div>
                       </div>          
                   </div>
                   <div id="common_div_vs">
                    <?=$this->load->view('user/'.$viewname.'/view_valuation_searched')?>
                   </div>
                </div>
              </div>
            </div>
        </div>