<?php
/*
    @Description: View Task 
    @Author: Mohit Trivedi
    @Date: 05-08-2014

*/
    //pr($datalist);exit;
    ?>

  <div id="content-header">
   <h1></h1>
  </div>
  <div id="" class="addnewcontact">
   <div class="">
    <div class="col-md-12">
	
     <div class="">
      <!-- /.portlet-header -->
      <div class="portlet-content">
       <div class="col-sm-12">
        <div class="tab-content" id="myTab1Content">
         <? if(!empty($datalist[0]['first_name'])) { ?>
          <div class="row tab-pane fade in active" id="home">
            <div class="col-sm-12 form-group">
              <div class="row">
             	  <div class="col-sm-4">
                  <label>
                    <?php if(!empty($datalist[0]['first_name_title']))
                            echo str_replace("{^}",',',($datalist[0]['first_name_title']));
                          else 
                            echo 'First name';
                    ?>
                  </label>
                </div>
			      <div class="col-sm-1">:</div>
			      <div class="col-sm-7">
        			  <?php if(!empty($datalist[0]['first_name_data']))
        			  			  echo str_replace("{^}",',',($datalist[0]['first_name_data']));
                      else 
                          echo '--';
        					?>
             </div>
          </div>
          <? } ?>  
          <? if(!empty($datalist[0]['last_name'])) { ?>
            <div class="row">
             	 <div class="col-sm-4">
                <label>
                    <?php if(!empty($datalist[0]['last_name_title']))
                        echo str_replace("{^}",',',($datalist[0]['last_name_title']));
                      else 
                        echo 'Last name';
                    ?>
                </label>
                </div>
        			  <div class="col-sm-1">:</div>
        			  <div class="col-sm-7">
        			  <?php if(!empty($datalist[0]['last_name_data']))
        			  			  echo str_replace("{^}",',',($datalist[0]['last_name_data']));
                      else 
                          echo '--';
        					?>
                     </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['phone_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['phone_title']))
                          echo str_replace("{^}",',',($datalist[0]['phone_title']));
                        else 
                          echo 'Phone';
                    
                  ?>
                 </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['phone_data']))
          			  			 echo str_replace("{^}",',',($datalist[0]['phone_data']));
          					    else 
                          echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['email_field'])) { ?>
              <div class="row">
               	 <div class="col-sm-4">
                  <label>
                    <?php if(!empty($datalist[0]['email_title']))
                          echo str_replace("{^}",',',($datalist[0]['email_title']));
                        else 
                          echo 'Email';
                    
                  ?>

                  </label>
                  </div>
            			  <div class="col-sm-1">:</div>
            			  <div class="col-sm-7">
            			  <?php if(!empty($datalist[0]['email_data']))
            			  			  echo str_replace("{^}",',',($datalist[0]['email_data']));
            					    else 
                            echo '--';
            				?>
                 </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['single_line_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['single_line_title']))
                          echo str_replace("{^}",',',($datalist[0]['single_line_title']));
                        else 
                          echo 'Line Text';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['single_line_data']))
          			  			  echo str_replace("{^}",',',($datalist[0]['single_line_data']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['paragraph_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['paragraph_title']))
                          echo str_replace("{^}",',',($datalist[0]['paragraph_title']));
                        else 
                          echo 'Paragraph Text';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['paragraph_data']))
          			  			  echo str_replace("{^}",',',($datalist[0]['paragraph_data']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['address_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label> 
                  <?php if(!empty($datalist[0]['address_title']))
                          echo str_replace("{^}",',',($datalist[0]['address_title']));
                        else 
                          echo 'Address';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['address_data']))
          			  			  echo str_replace("{^}",',',($datalist[0]['address_data']));
          					    else 
                            echo '--'; 
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['date_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label> 
                  <?php if(!empty($datalist[0]['date_title']))
                          echo str_replace("{^}",',',($datalist[0]['date_title']));
                        else 
                          echo 'Date';
                  ?>
                 </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['date_data']))
          			  			  echo str_replace("{^}",',',($datalist[0]['date_data']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['website_field'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label> 
                  <?php if(!empty($datalist[0]['website_title']))
                          echo str_replace("{^}",',',($datalist[0]['website_title']));
                        else 
                          echo 'Website';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['website_data']))
          			  			  echo str_replace("{^}",',',($datalist[0]['website_data']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['area_of_interest_lead'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['area_of_interest_title']))
                          echo str_replace("{^}",',',($datalist[0]['area_of_interest_title']));
                        else 
                          echo 'Area of Interest';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['area_of_interest']))
          			  			  echo str_replace("{^}",',',($datalist[0]['area_of_interest']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['price_range'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['price_range_from_title']))
                          echo str_replace("{^}",',',($datalist[0]['price_range_from_title']));
                        else 
                          echo 'Price Range From (In $)';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['price_range_from']))
          			  			  echo str_replace("{^}",',',($datalist[0]['price_range_from']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['price_range'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['price_range_to_title']))
                          echo str_replace("{^}",',',($datalist[0]['price_range_to_title']));
                        else 
                          echo 'Price Range To (In $)';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['price_range_to']))
          			  			  echo str_replace("{^}",',',($datalist[0]['price_range_to']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['bedrooms'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label> 
                  <?php if(!empty($datalist[0]['no_of_bedrooms_title']))
                          echo str_replace("{^}",',',($datalist[0]['no_of_bedrooms_title']));
                        else 
                          echo 'Bedrooms';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['no_of_bedrooms']))
          			  			  echo str_replace("{^}",',',($datalist[0]['no_of_bedrooms']));
          					    else 
                          echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['bathrooms'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label> 
                  <?php if(!empty($datalist[0]['no_of_bathrooms_title']))
                          echo str_replace("{^}",',',($datalist[0]['no_of_bathrooms_title']));
                        else 
                          echo 'Bathrooms';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['no_of_bathrooms']))
          			  			  echo str_replace("{^}",',',($datalist[0]['no_of_bathrooms']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['buyer_preferences_notes_lead'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['buyer_preferences_notes_title']))
                          echo str_replace("{^}",',',($datalist[0]['buyer_preferences_notes_title']));
                        else 
                          echo 'Buyer Preference Notes';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['buyer_preferences_notes']))
          			  			  echo str_replace("{^}",',',($datalist[0]['buyer_preferences_notes']));
        					      else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['house_style_lead'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['house_style_title']))
                          echo str_replace("{^}",',',($datalist[0]['house_style_title']));
                        else 
                          echo 'House Style';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['house_style']))
          			  			  echo str_replace("{^}",',',($datalist[0]['house_style']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
              <? if(!empty($datalist[0]['square_footage_lead'])) { ?>
              <div class="row">
             	 <div class="col-sm-4">
                <label>
                  <?php if(!empty($datalist[0]['square_footage_title']))
                          echo str_replace("{^}",',',($datalist[0]['square_footage_title']));
                        else 
                          echo 'Square Footage';
                  ?>
                </label>
                </div>
          			  <div class="col-sm-1">:</div>
          			  <div class="col-sm-7">
          			  <?php if(!empty($datalist[0]['square_footage']))
          			  			  echo str_replace("{^}",',',($datalist[0]['square_footage']));
          					    else 
                            echo '--';
          				?>
               </div>
              </div>
              <? } ?>
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

