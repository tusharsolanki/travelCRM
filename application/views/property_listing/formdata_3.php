<?php 
$viewname = $this->uri->segment(1);
$uri_segment = $this->uri->segment(2);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Property</title>
<link media="screen" rel="stylesheet" href="<?=$this->config->item('listing_path')?>theme3/css/livewire_crm.css" type="text/css" />
<link media="screen" rel="stylesheet" href="<?=$this->config->item('listing_path')?>theme3/css/bootstrap.css" type="text/css" />
<link media="screen" rel="stylesheet" href="<?=$this->config->item('listing_path')?>theme3/css/font-awesome.css" type="text/css" />
<link media="screen" rel="stylesheet" href="<?=$this->config->item('listing_path')?>theme3/css/datepicker.css" type="text/css" />
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>crm.css" type="text/css">
<script type="text/javascript" src="<?=$this->config->item('listing_path')?>theme3/js/jquery.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('listing_path')?>theme3/js/bootstrap.js"></script>
<script type="text/javascript" src="<?=$this->config->item('listing_path')?>theme3/js/bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?=$this->config->item('listing_path')?>theme1/css/multiselect/jquery-ui.css" />
<script type="text/javascript" src="<?=$this->config->item('listing_path')?>theme1/js/multiselect/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.maskedinput.js"></script> 
<?php $this->load->view('google_analytics'); ?>
</head>


<body>
<div class="container-fluid">
  <div class="row">
    <div class="header0">
      <div class="header_center">
        <div class="container">
          <div class="row">
            <div class="col-lg-6 col-xs-7 pull-left">
              <div class="logo">
              <!--<a href="#">
                <img class="img-responsive margin-left-null" src="<?php echo $this->config->item('image_path');?>logo.png" alt="logo">
              </a>-->
              <div class="Brokerage">
              	<?php if(!empty($editRecord[0]['brokerage_pic']) && file_exists($this->config->item('broker_small_img_path').$editRecord[0]['brokerage_pic'])) {
        ?>
        			<img src="<?=$this->config->item('broker_upload_img_small').$editRecord[0]['brokerage_pic']?>">
				<?php } else { ?>
                        <img src="<?=$this->config->item('listing_path')?>theme2/images/ban.png">
                <?php } ?>

              </div>
              </div>
            </div>
            <!--<div class="col-lg-1 pull-right">
              <div class="login"><a href="#">Login</a></div>
            </div>-->
          </div>
        </div>
      </div>
    </div>
    <?php if(!empty($editRecord[0]['is_visible_to_public']) && $editRecord[0]['is_visible_to_public'] == '1') { ?>
    <div class="top_bg">
      <div class="container">
        <div class="tops_center">
          <div class="row">
            <div class="col-md-8 col-lg-8 col-sm-8 col-xs-12">
              <div class="banner">
                <div data-ride="carousel" class="carousel slide" id="carousel-example-generic"> 
                  
                  <!-- Wrapper for slides -->
                  <div role="listbox" class="carousel-inner">
           			<?php
					   $i = 1;
						if(!empty($photos_trans_data)) {
							foreach($photos_trans_data as $row) {
								if(!empty($row['photo']) && file_exists($this->config->item('listing_small_img_path').$row['photo'])) 
								{
					?>
                    				<div class="item <?php if($i==1){ ?>active<?php } ?>"> <img alt="banner" src="<?=base_url().$this->config->item('listing_small_upload_img_path').$row['photo']?>" class="img-responsive">
                      <div class="carousel-caption"> </div>
                    </div>
                   <?php			$i++;
								}
								if($i > 4)
									break;
							}
						}
						else
						{
				   ?>
                   			<div class="item active"> <img alt="banner" src="<?=base_url()?>images/no-img-banner.jpg" class="img-responsive">
                              <div class="carousel-caption"> </div>
                            </div>
                   <?php } ?>
                    <!--<div class="item"> <img alt="banner" src="<?=$this->config->item('listing_path')?>theme3/images/3banner01.png" class="img-responsive">
                      <div class="carousel-caption"> </div>
                    </div>
                    <div class="item"> <img alt="banner" src="<?=$this->config->item('listing_path')?>theme3/images/3banner02.png" class="img-responsive">
                      <div class="carousel-caption"> </div>
                    </div>
                    <div class="item"> <img alt="1banner" src="<?=$this->config->item('listing_path')?>theme3/images/3banner03.png" class="img-responsive">
                      <div class="carousel-caption"> </div>
                    </div>
                    <div class="item active"> <img alt="1banner" src="<?=$this->config->item('listing_path')?>theme3/images/3banner04.png" class="img-responsive">
                      <div class="carousel-caption"> </div>
                    </div>-->
                  </div>
                  <div class="container hei_box"> 
                    
                    <!-- Indicators -->
                    <ol class="carousel-indicators2">
                    	<?php
							$i = 0;
							if(!empty($photos_trans_data)) {
								foreach($photos_trans_data as $row) {
									if(!empty($row['photo']) && file_exists($this->config->item('listing_small_img_path').$row['photo']))
									{
							?>
										<li class="<?php if($i==0){ ?>active<?php } ?>" data-slide-to="<?=$i?>" data-target="#carousel-example-generic"><img alt="thumbs_pic1" src="<?=base_url().$this->config->item('listing_small_upload_img_path').$row['photo']?>" class="img-responsive"></li>
						   <?php    $i++;
									}
									if($i >= 4)
										break;
								}
							}
							else
							{
						?>
                        		<li class="active" data-slide-to="<?=$i?>" data-target="#carousel-example-generic"><img alt="thumbs_pic1" src="<?=base_url()?>images/no-img-banner-small.jpg" class="img-responsive"></li>
                        <?php } ?>
                      <!--<li class="" data-slide-to="0" data-target="#carousel-example-generic"><img alt="thumbs_pic1" src="<?=$this->config->item('listing_path')?>theme3/images/3thumbs_pic04.png" class="img-responsive"></li>
                      <li data-slide-to="1" data-target="#carousel-example-generic" class=""><img alt="thumbs_pic1" src="<?=$this->config->item('listing_path')?>theme3/images/3thumbs_pic03.png" class="img-responsive"></li>
                      <li data-slide-to="2" data-target="#carousel-example-generic" class=""><img alt="thumbs_pic1" src="<?=$this->config->item('listing_path')?>theme3/images/3thumbs_pic02.png" class="img-responsive"></li>
                      <li data-slide-to="3" data-target="#carousel-example-generic" class="active"><img alt="thumbs_pic1" src="<?=$this->config->item('listing_path')?>theme3/images/3thumbs_pic01.png" class="img-responsive"></li>-->
                    </ol>
                    
                    <!-- Controls --> 
                    <!--  <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>--> 
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
              <div class="Santa_box">
                <h1> <?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?> <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> <?=!empty($editRecord[0]['country'])?$editRecord[0]['country']:''?>
                </h1>
                <p class="location1"><?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></p>
              </div>
              <p class="locationbtn1">
                  <a href="#">$ 
						<?php
							if(!empty($editRecord[0]['price']))
							{
								$explode = explode('.',$editRecord[0]['price']);
								if(!empty($explode[1]) && $explode[1] != '00')
									echo $editRecord[0]['price'];
								elseif(!empty($explode[0]))
									echo $explode[0];
								else
									echo '0';
									
							}
							else
								echo '0';
						?>
                  </a>
              </p>
              <div class="icon_bann">
                <ul>
                  <li><?php if(!empty($editRecord[0]['facebook'])) { ?><a href="<?=!empty($editRecord[0]['facebook'])?$editRecord[0]['facebook']:'javascript:void(0);'?>" <?php if(!empty($editRecord[0]['facebook'])) { ?> target="_blank" <?php } ?> ><img class="img-responsive" src="<?=$this->config->item('listing_path')?>theme3/images/facebook.png" alt="Facebook"></a>
                  	  <?php } ?>
                  </li>
                  <li><?php if(!empty($editRecord[0]['twitter'])) { ?><a href="<?=!empty($editRecord[0]['twitter'])?$editRecord[0]['twitter']:'javascript:void(0);'?>" <?php if(!empty($editRecord[0]['twitter'])) { ?> target="_blank" <?php } ?>><img class="img-responsive" src="<?=$this->config->item('listing_path')?>theme3/images/twitter-512.png" alt="Twitter"></a>
                  	  <?php } ?>
                  </li>
                  <li><a href="<?=!empty($editRecord[0]['email_id'])?'mailto:'.$editRecord[0]['email_id']:'javascript:void(0);'?>"><img class="img-responsive" src="<?=$this->config->item('listing_path')?>theme3/images/mail-512.png" alt="message "></a></li>
                </ul>
              </div>
              <div class="For_saleul_righ">
                <ul>
                  <li><b>FOR</b> : <?=!empty($editRecord[0]['status_name'])?$editRecord[0]['status_name']:''?></li>
                  <li class="last1"><b>
                  <?php 
					if(!empty($editRecord[0]['total_area']) && $editRecord[0]['total_area'] != '0.00') 
						echo $editRecord[0]['total_area'].' '.$editRecord[0]['total_area_name'];
					else 
						echo '0';
			  	  ?>
                  </b> : AREA</li>
                  <li><b><?=!empty($editRecord[0]['bathrooms_count'])?$editRecord[0]['bathrooms_count']:'0'?></b> : BATHS</li>
                  <?php if(!empty($editRecord[0]['half_bathrooms_count'])) { ?>
                			<li class="last1"><b><?=$editRecord[0]['half_bathrooms_count']?></b> : HALF BATHS</li>
              	  <?php } ?>
                  <li class="last1"><b><?=!empty($editRecord[0]['bedrooms_count'])?$editRecord[0]['bedrooms_count']:'0'?></b> : BEDS</li>
                  <li> <b><?=!empty($editRecord[0]['parking_count'])?$editRecord[0]['parking_count']:'0'?></b> PARKING</li>
                  <li><b><?=!empty($editRecord[0]['kitchen_count'])?$editRecord[0]['kitchen_count']:'0'?></b> kitchen</li>
                  <li class="last"> <b><?=!empty($editRecord[0]['floor_count'])?$editRecord[0]['floor_count']:'0'?></b> Floors</li>
                </ul>
              </div>
              <div class="Nam_liber">
                <h2> <span><img src="<?=$this->config->item('listing_path')?>theme3/images/Property1.png"></span> Property Detials</h2>
                <div class="Nam_liber_Property"> Property ID :<span> <?=!empty($editRecord[0]['mls_no'])?$editRecord[0]['mls_no']:''?></span></div>
                <div class="Nam_liber_Property">Property Address :<span> <?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?> <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> <?=!empty($editRecord[0]['country'])?$editRecord[0]['country']:''?></span></div>
                <div class="Nam_liber_Property"> Property City :<span> <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?></span></div>
              </div>
            </div>
            <div class="left_bar">
              <div class="Nam_liber">
                <h2><span><img src="<?=$this->config->item('listing_path')?>theme3/images/Property1.png"></span> Property Description</h2>
                <p><?=!empty($editRecord[0]['remarks'])?$editRecord[0]['remarks']:''?></p>
              </div>
              <div class="Additional">
                <h2> <span><img src="<?=$this->config->item('listing_path')?>theme3/images/Additional1.png"></span> Additional Amenities</h2>
                <div class="roomdetails2">
                  <ul>
                  <?php 
					$i = 1;
					if(!empty($editRecord[0]['sewer_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Sewer</b> : <?=$editRecord[0]['sewer_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['basement_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b> Basement</b> : <?=$editRecord[0]['basement_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['parking_type_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Parking Type</b> : <?=$editRecord[0]['parking_type_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['parking_spaces'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Parking Spaces</b> : <?=$editRecord[0]['parking_spaces']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['builder_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Builder</b> : <?=$editRecord[0]['builder_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['style_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Style</b> : <?=$editRecord[0]['style_name']?></span></li>
                   <?php $i++; } 
                    if(!empty($editRecord[0]['exterior_finish_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Exterior Finish</b> : <?=$editRecord[0]['exterior_finish_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['foundation_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Foundation</b> : <?=$editRecord[0]['foundation_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['roof_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Roof</b> : <?=$editRecord[0]['roof_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['architecture_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Architecture</b> : <?=$editRecord[0]['architecture_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['green_certification_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Green Certification</b> : <?=$editRecord[0]['green_certification_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['fireplace_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Fireplace</b> : <?=$editRecord[0]['fireplace_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['energy_source_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Energy Source</b> : <?=$editRecord[0]['energy_source_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['heating_cooling_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Heating/Cooling</b> : <?=$editRecord[0]['heating_cooling_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['floor_covering_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Floor Covering</b> : <?=$editRecord[0]['floor_covering_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['interior_feature_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Interior Features</b> : <?=$editRecord[0]['interior_feature_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['water_company_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Water Company</b> : <?=$editRecord[0]['water_company_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['power_company_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Power Company</b> : <?=$editRecord[0]['power_company_name']?></span></li>
                    <?php $i++; } 
                    if(!empty($editRecord[0]['sewer_company_name'])) {
					?>
                	<li>
                    <input type="checkbox" class="checkbox3" id="<?=$i?>">
                    <label for="1"></label>
                    <span><b>Sewer Company</b> : <?=$editRecord[0]['sewer_company_name']?></span></li>
                    <?php $i++; } 
					if($i == 1) {
					?>
                    	<span><b>No Amenities</b></span></li>
				   <?php } ?>
                    <!--<li>
                      <input type="checkbox" class="checkbox5" id="1">
                      <label for="1"></label>
                      <span>Air Conditioning</span></li>
                    <li>
                      <input type="checkbox" checked="" class="checkbox5" id="2">
                      <label for="2"></label>
                      <span>Balcony</span></li>
                    <li>
                      <input type="checkbox" class="checkbox5" id="3">
                      <label for="3"></label>
                      <span>Pool</span></li>
                    <li>
                      <input type="checkbox" class="checkbox5" id="4">
                      <label for="4"></label>
                      <span>Microwave</span></li>
                    <li>
                      <input type="checkbox" class="checkbox5" id="5">
                      <label for="5"></label>
                      <span>Security Camera</span></li>
                    <li>
                      <input type="checkbox" class="checkbox5" id="6">
                      <label for="6"></label>
                      <span>Furnished</span></li>
                    <li>
                      <input type="checkbox" class="checkbox5" id="7">
                      <label for="7"></label>
                      <span>Gym</span></li>-->
                  </ul>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="container">
                <div class="cent">
                  <div class="col-md-3 col-lg-3 col-sm-3 col-xs-12">
                    <div class="Agent top_padd30"><span><img src="<?=$this->config->item('listing_path')?>theme3/images/Agent_icom.png"></span>Agent</div>
                    <div class="emma_img1">
                    	<?php if(!empty($editRecord[0]['admin_pic']) && file_exists($this->config->item('admin_small_img_path').$editRecord[0]['admin_pic'])) { ?>
                                 <img src="<?=$this->config->item('admin_upload_img_small')?>/<?=$editRecord[0]['admin_pic']?>">
                        <?php } elseif(!empty($editRecord[0]['contact_pic']) && file_exists($this->config->item('contact_small_img_path').$editRecord[0]['admin_pic'])) {?>
                                 <img src="<?=$this->config->item('user_upload_img_small')?>/<?=$editRecord[0]['contact_pic']?>">
                        <?php } else { ?>
                                 <img src="<?=base_url()?>images/no-img-banner-small.jpg">
                        <?php } ?>
                    </div>
                  </div>
                  <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12">
                    <div class="Emma_dumas1">
                      <h1>
					  	<?php
							if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
								echo $editRecord[0]['admin_name'];
							else
								echo $editRecord[0]['user_name'];
						?>
                      </h1>
                      <p><span>Telephone Number : </span>
						<?php 
                            if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2' && !empty($editRecord[0]['phone'])) 
                                echo $editRecord[0]['phone'];
                            elseif(!empty($editRecord[0]['phone_no']))
                                echo $editRecord[0]['phone_no'];
							else 
								echo '-';
                        ?>
                      </p>
                      <p><span>Email : </span><?=!empty($editRecord[0]['email_id'])?$editRecord[0]['email_id']:''?></p>
                      <?php 
							if(!empty($editRecord[0]['website_name']))
							{
						?>
							<p><span>Website : </span><a href="#"><?=$editRecord[0]['website_name']?></a> </p>
					  <?php } ?>
                      <p><span>License Number : </span><?=!empty($editRecord[0]['user_license_no'])?$editRecord[0]['user_license_no']:'-'?> </p>
                    </div>
                  </div>
                  <div class="col-md-5 col-lg-5 col-sm-5 col-xs-12">
                    <div class="Emma_dumas3">
                      <h1>Request a Showing</h1>
                      <p><?=!empty($editRecord[0]['address_line_1'])?$editRecord[0]['address_line_1']:''?> <?=!empty($editRecord[0]['address_line_2'])?$editRecord[0]['address_line_2']:''?> <?=!empty($editRecord[0]['district'])?$editRecord[0]['district']:''?> , <?=!empty($editRecord[0]['state'])?$editRecord[0]['state']:''?> <?=!empty($editRecord[0]['zip_code'])?$editRecord[0]['zip_code']:''?> <?=!empty($editRecord[0]['city'])?$editRecord[0]['city']:''?> <?=!empty($editRecord[0]['country'])?$editRecord[0]['country']:''?> | MLS # <?=$editRecord[0]['mls_no']?></p>
                      <form class="form parsley-form" enctype="multipart/form-data" name="" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?=base_url().$viewname.'/send_mail'?>" novalidate >
                      	<div class="form-group">
                            <input name="name" placeholder="Name" id="name" class="form-control parsley-validated" type="text" data-required="true" />
                       </div>
                       <div class="form-group">
                            <input name="email" placeholder="Email" id="email" class="form-control parsley-validated" type="email" data-required="true" data-parsley-type="email" />
                       </div>
                       <div class="form-group">
                            <input id="txt_phone_no" name="txt_phone_no"  maxlength="12"  data-maxlength="12" class="form-control parsley-validated mask_apply_class" type="text" value="" placeholder="e.g. 123-456-7890">
                       </div>
                        <div class="form-group">
                          	<input name="datetime" placeholder="Specific Date" id="datetime" type="text" class="form-control parsley-validated" data-required="true">
                            <input type='hidden' id='email_id' name="email_id" value="<?=!empty($editRecord[0]['email_id'])?$editRecord[0]['email_id']:''?>" />
                            <input type='hidden' id='uri_segment' name="uri_segment" value="<?=!empty($uri_segment)?$uri_segment:''?>" />
                        </div>
                        <div class="form-group">
                          <input type="text" class="form-control" name="message" id="message" placeholder="Morning" data-required="true">
                        </div>
                        <div class="form-group">
                          <button type="submit" class="btn btn-default lets_go-btnbob ">Let's Go See It!</button>
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
    <?php } else { ?>
    <div class="text-center margin_top">
   		<b>This Property is not visible to public.</b>
   	</div>
    <?php } ?>
  </div>
</div>
<?php if(!empty($editRecord[0]['is_visible_to_public']) && $editRecord[0]['is_visible_to_public'] == '1') { ?>
<div class="mp_center"> 
	<div id="googleMap"  class="map"></div>
	  <script type="text/javascript">
            function changeaddress(passaddress)
            {
                var mp = '<iframe scrolling="no" width="100%" style="height:300px" frameborder="0" src="https://maps.google.com/maps?q='+passaddress+'&amp;output=embed&amp; z=10" marginwidth="0" marginheight="0"></iframe>';
                $('#googleMap').html(mp);
            }
            <?php if(!empty($editRecord[0]['address'])){ ?>
                changeaddress('<?=$editRecord[0]['address']?>');
            <?php }else{ ?>
                changeaddress('');
            <?php } ?>
      </script>
<!--<img class="img-responsive" src="<?=$this->config->item('listing_path')?>theme3/images/mp_center.png">-->
</div>
<div class="footer_main">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="footer_left" style="padding:20px 0 0 0;">
          <ul>
            <p>COPYRIGHT © 2014 Livewire CRM</p>
            <!--<li></li>
            <li><a class="first-child" href="#">CRM </a></li>
            <li><a href="#">POLICIES </a></li>
            <li><a href="#">SUPPORT </a></li>
            <li><a href="#">NEWS </a></li>
            <li><a href="#">ABOUT </a></li>-->
          </ul>
        </div>
        <div class="footer_center">
          <p><span>Name : </span>
		  		<?php
            		if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2') 
						echo $editRecord[0]['admin_name'];
					else
						echo $editRecord[0]['user_name'];
				?> 
                <?php if(!empty($editRecord[0]['admin_address']) || !empty($editRecord[0]['user_address'])) { ?>
                <span> Address : </span> 
                <?php 
					if(!empty($editRecord[0]['user_type']) && $editRecord[0]['user_type'] == '2')
						echo $editRecord[0]['admin_address'];
					else
					{
						echo trim($editRecord[0]['user_address']).'.';
					}
				} ?>
         </p>
        </div>
        <div class="footer_righ">
          <ul>
            <li><?php if(!empty($editRecord[0]['facebook'])) { ?><a href="<?=!empty($editRecord[0]['facebook'])?$editRecord[0]['facebook']:'javascript:void(0);'?>" <?php if(!empty($editRecord[0]['facebook'])) { ?> target="_blank" <?php } ?> ><img src="<?=$this->config->item('listing_path')?>theme3/images/facebook.png"></a>
            	<?php } ?>
            </li>
            <li><?php if(!empty($editRecord[0]['twitter'])) { ?><a href="<?=!empty($editRecord[0]['twitter'])?$editRecord[0]['twitter']:'javascript:void(0);'?>" <?php if(!empty($editRecord[0]['twitter'])) { ?> target="_blank" <?php } ?>><img src="<?=$this->config->item('listing_path')?>theme3/images/twitter-512.png"></a>
            	<?php } ?>
            </li>
            <li><a href="<?=!empty($editRecord[0]['email_id'])?'mailto:'.$editRecord[0]['email_id']:'javascript:void(0);'?>"><img src="<?=$this->config->item('listing_path')?>theme3/images/mail-512.png"></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<?=!empty($editRecord[0]['google_analytics_code'])?$editRecord[0]['google_analytics_code']:''?>
</body>
</html>

<script type="text/javascript">
$(document).ready(function () {
	$('.mask_apply_class').mask('999-999-9999');
	$( "#datetime" ).datepicker({
		minDate: "0",
		dateFormat:'yy-mm-dd',
	});
});
</script>
<script src="<?=$this->config->item('js_path')?>App.js"></script> 
<script src="<?=$this->config->item('js_path')?>parsley.js"></script> 