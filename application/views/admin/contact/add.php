<?php
/*
    @Description: Contact add
    @Author: Niral Patel
    @Date: 30-06-2014

*/?>
<?php 
$viewname = $this->router->uri->segments[2];
$formAction = !empty($editRecord)?'update_data':'insert_data'; 
$path = $viewname.'/'.$formAction;
?>

<div id="content">
  <div id="content-header">
    <h1>Add New Contact</h1>
  </div>
  <div id="content-container">
    <div class="row">
      <div class="col-md-12">
        <div class="portlet">
          <div class="portlet-header">
            <h3> <i class="fa fa-tasks"></i> New Contact </h3>
          </div>
          <!-- /.portlet-header -->
          
          <div class="portlet-content">
            <div class="col-sm-8">
              <form class="form parsley-form" enctype="multipart/form-data" name="<?php echo $viewname;?>" id="<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?= $this->config->item('admin_base_url')?><?php echo $path?>" >
                <div class="form-group">
                  <label for="select-multi-input">First Name</label>
                  <input id="first_name" name="first_name" class="form-control parsley-validated" type="text" data-required="required" value="<?= !empty($editRecord[0]['first_name'])?$editRecord[0]['first_name']:'';?>">
                </div>
                <div class="form-group">
                  <label for="select-multi-input">Last Name</label>
                  <input id="last_name" name="last_name" class="form-control parsley-validated" type="text" data-required="required" value="<?= !empty($editRecord[0]['last_name'])?$editRecord[0]['last_name']:'';?>">
                </div>
                <div class="form-group">
                  <label for="select-multi-input">Email</label>
                  <input id="email" class="form-control parsley-validated" type="text" data-required="required" name="email" value="<?= !empty($editRecord[0]['email'])?$editRecord[0]['email']:'';?>">
                </div>
                <div class="form-group">
                  <label for="select-multi-input">Phone</label>
                  <input id="phone_no" class="form-control parsley-validated" type="text" data-required="required" name="phone_no" value="<?= !empty($editRecord[0]['phone_no'])?$editRecord[0]['phone_no']:'';?>">
                </div>
                <div class="form-group">
                  <input type="hidden" name="id" value="<?= !empty($editRecord[0]['id'])?$editRecord[0]['id']:'';?>" />
                  <button type="submit" class="btn btn-primary">Validate</button>
                </div>
              </form>
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
<!-- #content --> 

