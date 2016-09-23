<!--/*
        @Description: View for add Contact
        @Author: Mohit Trivedi
        @Input: -
        @Output: -
        @Date: 25-09-14
        */
-->
<?php
    //pr($editRecord);exit;
    $viewname = $this->router->uri->segments[2]; 
    isset($editRecord) ? $loadcontroller='update_email' : $loadcontroller='insert_department';
        $path_email = $viewname."/".$loadcontroller;

    //pr($editPhoneRecord);
    //$path = $viewname."/".$loadcontroller;
?>
<div id="content" class="contact-masters">
  <div id="content-header">
   <h1>Department Masters</h1>
  </div>
  <div id="content-container">
  
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
      
       <h3> <i class="fa fa-tasks"></i>Department Masters</h3>   
        <span class="float-right margin-top--15"><a class="btn btn-secondary" onclick="history.go(-1)" title="Back" href="javascript:void(0)"><?php echo $this->lang->line('common_back_title')?></a> </span>      
      </div>
      <!-- /.portlet-header -->
      
      <div class="portlet-content" style="max-height:none;">
        
        <div class="">
          <div class="chart_bg1 tbl_border">
            <!-- EMAIL TYPE-->
            

            <form enctype="multipart/form-data" name="<?php echo $viewname;?>" id="email_form_<?php echo $viewname;?>" method="post" accept-charset="utf-8" action="<?php echo $this->config->item('admin_base_url')?><?php echo $path_email?>" class="form parsley-form" >
              <div class="col-md-6">
              <div class="mrg-bottom-40">
              <div class="portlet-header">
                    <h3>
                        <!--<i class="fa fa-tasks"></i>-->
                         <?php echo $this->lang->line('common_label_department_type')?>
                    </h3>
              </div>
              <div class="portlet-content">
              <table width="100%" class="iconment_title_in" >
                <tr >
                  <th><?php echo $this->lang->line('common_label_title')?></th>
                  <th><?php echo $this->lang->line('common_label_action')?></th>
                </tr>
                <?php
                    if(!empty($email_type) && count($email_type)>0){
                    foreach($email_type as $row)
                    {   
                    ?>
                <tr>
                  <td colspan="2">
                    <div class="space"></div>
                    <div id="flash"></div>
                    <div id="show"></div>
                  </td>
                </tr>
                <tr>
                  <td class="text_capitalize" width="70%">
                    <input type="text" class="form-control parsley-validated" name="email_update[]" id="email_<?=$row['id']?>" value="<?=htmlentities($row['name'])?>" <?php if($row['user_type']=='1'){ ?> readonly <?php }?>/> 
                    
                    <input type="hidden" class="form-control parsley-validated" name="email_idd[]" id="" value="<?php echo  $row['id'] ?>"/> 
                    
                  </td>
                  <td>
                    <?php if($row['user_type']!='1'){ 
                        if($row['name']!='CEO' && $row['name']!='Sales'){?>
                    <a href="javascript:void(0);" onclick="getsubmit('<?=$row['id']?>')" title="Update record" class="btn btn-xs btn-success"><i class="fa fa-pencil-square-o"></i></a>
                    <a href="javascript:void(0);" class="btn btn-xs btn-primary" onclick="deletepopup('<?=rawurlencode(ucfirst(strtolower($row['name'])))?>','<?php echo $this->lang->line('contact_head_submodel')?>','<?php echo $this->config->item('admin_base_url').$viewname;?>/delete_department_record/<?php echo  $row['id'] ?>');"> <i class="fa fa-times"></i> </a> 
                    <?php }}?>
                  </td>
                </tr>
                <?php }}?>
              <input type="hidden" id="email_id" name="email_id" class="email_id" value="<?=isset($editRecord) ? $editRecord[0]['id']:''?>" />
                <tr>
                  <td colspan="2"><div id="p_scents" class="form-group">
                  <?php if(empty($email_type) || count($email_type) == 0){?>
                      <p>
                        <label for="p_scnts">
                        <input type="text" class="form-control parsley-validated" data-required="required" name="email_type[0]" id="email_type[0]" value="<?=isset($editRecord) ? $editRecord[0]['name'] : ''?>" />
                        </label>
                        
                      </p>
                      <?php } ?> 
                    </div></td>
                </tr>
                <tr>
                    <td><a href="javascript:void(0)" id="addScnt" title="Add Department" class="text_color_red text_size add_new_ta"><i class="fa fa-plus-square"></i> Add Department</a></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><input type="submit" style="width:auto;" onclick="setdefaultdata('email_form_<?php echo $viewname;?>');" class="btn btn-primary margin_tops" value="Save" name="type"></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
              </div>
              </div>
              </div>
               
            </form>


          </div>
        </div>
        
      </div>
    </div>
    
  </div>
</div>
<script type="text/javascript">
    $(document).ready(function (){
            //$('#<?php echo $viewname;?>').bValidator();
    });
    
    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if(charCode > 31 && (charCode < 48 || charCode > 57))
            return false;

        return true;
    }
    function getemail()
    {
        var emailid = $("#email").val();
        $.ajax({
            type: "post",
            data: {'email':emailid,
            },
            url: '<?php echo $this->config->item('admin_base_url')?>/user/getemail', 
            success: function(msg1) 
            {
                if(msg1 != '')
                {
                    $("#emailexist").val(msg1);
                    $("#email").focus();
                }
                else
                    $("#emailexist").val(msg1);
            }
        }); 
    return false;
    }
</script>

<!-- ================== START Multipal Input Box Script ================== -->
<!-- ==== Email INPUT ADD ===== -->
<script type="text/javascript">

$(function() {
        var scntDiv = $('#p_scents');
        var i = $('#p_scents p').size();
        $('body').on('click', '#addScnt', function(){
        $('<p><label for="p_scnts"><input type="text" class="form-control parsley-validated" data-required="required" name="email_type[' + i +']" id="email_type[' + i +']"/></label>&nbsp;<a href="javascript:void(0)" id="remScnt" class="btn btn-xs btn-primary margin_tops"><i class="fa fa-times"></i></a></p>').appendTo(scntDiv);
                i++;
                return false;
        });
        
        $('body').on('click', '#remScnt', function(){
                if( i > 0 ) {
                        $(this).parents('p').remove();
                        //i--;
                }
                return false;
        });
});
</script>


<!-- ================== START Ajax Script ================== -->
<!-- ==== Email UPDATE AJAX ===== -->
<script type="text/javascript">
function getsubmit(id)
{
    var email = $("#email_"+id).val();
    if(email=='' && id=='')
    {
        alert("Enter text..");
        $("#email").focus();
    }
    else
    {
        $("#flash").show();
        $("#flash").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_email',
        data: { email_type:email,email_id:id },
        cache: true,
        success: function(html)
        {
            $("#show").after(html);
            $("#email").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== PHONE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_phone(id)
{
    var phone = $("#phone_"+id).val();
    if(phone=='' && id=='')
    {
        alert("Enter text..");
        $("#phone").focus();
    }
    else
    {
        $("#flash_phone").show();
        $("#flash_phone").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_phone',
        data: { phone_type:phone,phone_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_phone").after(html);
            $("#phone").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== ADDRESS UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_address(id)
{
    var address = $("#address_"+id).val();
    if(address=='' && id=='')
    {
        alert("Enter text..");
        $("#address").focus();
    }
    else
    {
        $("#flash_address").show();
        $("#flash_address").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_address',
        data: { address_type:address,address_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_address").after(html);
            $("#address").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== WEBSITE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_website(id)
{
    var website = $("#website_"+id).val();
    if(website=='' && id=='')
    {
        alert("Enter text..");
        $("#website").focus();
    }
    else
    {
        $("#flash_website").show();
        $("#flash_website").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_website',
        data: { website_type:website,website_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_website").after(html);
            $("#website").focus();
        }  
        });
    }
    return false;
}

function get_submit_status(id)
{
    var status = $("#status_"+id).val();
    if(status=='' && id=='')
    {
        alert("Enter text..");
        $("#status").focus();
    }
    else
    {
        $("#flash_status").show();
        $("#flash_status").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_status',
        data: { status_type:status,status_id:id },
        cache: true,
        success: function(html)
        {
            $("#flash_status").after(html);
            $("#status").focus();
        }  
        });
    }
    return false;
}

</script>

<!-- ==== PROFILE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_profile(id)
{
    var profile = $("#profile_"+id).val();
    if(profile=='' && id=='')
    {
        alert("Enter text..");
        $("#profile").focus();
    }
    else
    {
        $("#flash_profile").show();
        $("#flash_profile").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_profile',
        data: { profile_type:profile,profile_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_profile").after(html);
            $("#profile").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== CONACT UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_contact(id)
{
    var contact = $("#contact_"+id).val();
    if(contact=='' && id=='')
    {
        alert("Enter text..");
        $("#contact").focus();
    }
    else
    {
        $("#flash_contact").show();
        $("#flash_contact").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_contact',
        data: { contact_type:contact,contact_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_contact").after(html);
            $("#contact").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== DOCUMENT UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_document(id)
{
    var document = $("#document_"+id).val();
    if(document=='' && id=='')
    {
        alert("Enter text..");
        $("#document").focus();
    }
    else
    {
        $("#flash_document").show();
        $("#flash_document").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_document',
        data: { document_type:document,document_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_document").after(html);
            $("#document").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== SOURCE UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_source(id)
{
    var source = $("#source_"+id).val();
    if(source=='' && id=='')
    {
        alert("Enter text..");
        $("#source").focus();
    }
    else
    {
        $("#flash_source").show();
        $("#flash_source").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_source',
        data: { source_type:source,source_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_source").after(html);
            $("#source").focus();
        }  
        });
    }
    return false;
}
</script>

<!-- ==== DISPOSITION UPDATE AJAX ===== -->
<script type="text/javascript">
function get_submit_disposition(id)
{
    var disposition = $("#disposition_"+id).val();
    if(disposition=='' && id=='')
    {
        alert("Enter text..");
        $("#disposition").focus();
    }
    else
    {
        $("#flash_disposition").show();
        $("#flash_disposition").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_disposition',
        data: { disposition_type:disposition,disposition_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_disposition").after(html);
            $("#disposition").focus();
        }  
        });
    }
    return false;
}
</script>

<script type="text/javascript">
function get_submit_method(id)
{
    var method = $("#method_"+id).val();
    if(method=='' && id=='')
    {
        alert("Enter text..");
        $("#method").focus();
    }
    else
    {
        $("#flash_method").show();
        $("#flash_method").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_method',
        data: { method_type:method,method_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_method").after(html);
            $("#method").focus();
        }  
        });
    }
    return false;
}
</script>


<script type="text/javascript">
function get_submit_field(id)
{
    var field_name = $("#field_name_"+id).val();
    var field_type = $("#field_type_"+id).val();
    if(field_name=='' && field_type=='')
    {
        alert("Enter text..");
        $("#field_name_"+id).focus();
    }
    else
    {
        $("#flash_field").show();
        $("#flash_field").fadeOut(3000).html('<span class="load">Updated successfully..</span>');
        $.ajax({
        type: "POST",
        url: '<?=base_url()?>admin/<?=$viewname;?>/update_field',
        data: { field_type:field_type,field_name:field_name,field_id:id },
        cache: true,
        success: function(html)
        {
            $("#show_field").after(html);
            $("#field").focus();
        }  
        });
    }
    return false;
}
function setdefaultdata(id)
    {
         if ($('#'+id).parsley().isValid()) {
        $.blockUI({ message: '<?='<img src="'.base_url('images').'/ajaxloader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        
    }
    }
</script>

<!-- ================== END Ajax Script ================== -->
