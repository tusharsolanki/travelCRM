<?php if (!defined('BASEPATH'))exit('No direct script access allowed'); ?>
<?php $this->load->helper('form'); ?>
<style>
	.drag-lasso {	POSITION: absolute; 	FILTER: alpha(opacity=50); 	LINE-HEIGHT: 0; 	BACKGROUND-COLOR: #c3d9ff !important;	FONT-SIZE: 0px; 	opacity: 0.5; 	-khtml-opacity: 0.5}
</style>
<?php
	$d_session_array = $this->session->userdata('d_session_array');
	$user_session_array_array = $this->session->userdata('user_session_array_admin');
	
	 $type=$user_session_array_array['type'];
	 $access=$user_session_array_array['access'];
	 $admin_type = $this->router->uri->segments[1]; 
	//$agentid=(isset($did))?$did:$d_session_array['agentid'];
	//$agentname=(isset($dname))?$dname:$d_session_array['agentname'];
?>

<!-- for Calendar Start -->
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery-migrate-1.2.1.js"></script>   
<link href="<?=$this->config->item('wdcalendar')?>css/dailog.css" rel="stylesheet" type="text/css" />
<link href="<?=$this->config->item('wdcalendar')?>css/calendar.css" rel="stylesheet" type="text/css" /> 
<link href="<?=$this->config->item('wdcalendar')?>css/dp.css" rel="stylesheet" type="text/css" />   
<link href="<?=$this->config->item('wdcalendar')?>css/alert.css" rel="stylesheet" type="text/css" /> 
<link href="<?=$this->config->item('wdcalendar')?>css/main.css" rel="stylesheet" type="text/css" />
<!-- for Calendar End -->

<!-- for Calendar Start -->
  
<script>
<?php
if(!empty($this->modules_unique_name) && in_array('calendar_add',$this->modules_unique_name))
{
	?>
	var add_calendar='true';
	<?
}
else
{
	?>
	var add_calendar='false';
	<?
}
if(!empty($this->modules_unique_name) && in_array('calendar_edit',$this->modules_unique_name))
{
	?>
	var edit_calendar='true';
	<?
}
else
{
	?>
	var edit_calendar='false';
	<?
}
if(!empty($this->modules_unique_name) && in_array('calendar_delete',$this->modules_unique_name))
{
	?>
	var delete_calendar='true';
	<?
}
else
{
	?>
	var delete_calendar='false';
	<?
}
?>
</script>

<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/Common.js" type="text/javascript"></script>    
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>     
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.datepicker.js" type="text/javascript"></script>

<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.alert.js" type="text/javascript"></script>    
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.ifrmdailog.js" defer="defer" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/wdCalendar_lang_US.js" type="text/javascript"></script>    
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.calendar.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/jquery.blockUI.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/moment.js" type="text/javascript"></script>

<!-- for Calendar End -->
<div id="content">
  <div id="content-header">
   <h1><?=$this->lang->line('contact_header');?></h1>
  </div>
  <div id="content-container">
   <div class="">
    <div class="col-md-12">
     <div class="portlet">
      <div class="portlet-header">
       <h3> <i class="fa fa-table"></i>Calendar - My Appointment    <label><input type="radio" name="is_public" id="is_public" checked="checked" value="0" onclick="gridcontainer_refresh();" /> My Calendar &nbsp;&nbsp;  </label> <label><input type="radio" name="is_public" id="is_public" value="1" onclick="gridcontainer_refresh();" /> Public Calendar</</h3>
      </div>
      <!-- /.portlet-header -->
          <div class="table_large-responsive_cale overflow_auto">
          <div class="table_large-responsive_cale_in">
      <div class="portlet-content" style="padding:0px; padding-bottom:10px;">
   
        <div role="grid" class="dataTables_wrapper" id="DataTables_Table_0_wrapper">
         
         <div class="row dt-rt">
				<?php if(!empty($msg)){?>
					<div class="col-sm-12 text-center" id="div_msg"><?php echo '<label class="error">'.urldecode ($msg).'</label>';
					$newdata = array('msg'  => '');
					$this->session->set_userdata('message_session', $newdata);?> </div><?php } ?>
         </div>
 
		<div class="onecolumn">
	<script type="text/javascript">
	//var is_public = '0';
	function gridcontainer_refresh()
	{
		$("#gridcontainer").reload();	
	}
    $(document).ready(function() {
      var view="week";
	
      var DATA_FEED_URL = "<?=base_url('admin/calendar')?>";
      var op = {
        view: view,
		is_public:$('input:radio[name=is_public]:checked').val(),
        theme:3,
        showday: new Date(),
        EditCmdhandler:Edit,
        DeleteCmdhandler:Delete,
        ViewCmdhandler:View,    
        onWeekOrMonthToDay:wtd,
        onBeforeRequestData: cal_beforerequest,
        onAfterRequestData: cal_afterrequest,
        onRequestDataError: cal_onerror, 
        autoload:true,
        url: DATA_FEED_URL + "/view_record",
        quickAddUrl: DATA_FEED_URL + "/insert_calender",
        quickUpdateUrl: DATA_FEED_URL + "/update_appointment",
        quickDeleteUrl: DATA_FEED_URL + "/delete_calender"
      };
      var $dv = $("#calhead");
      var _MH = document.documentElement.clientHeight;
      var dvH = $dv.height() + 2;
      op.height = _MH - dvH;
      op.eventItems =[];
      
      var p = $("#gridcontainer").bcalendar(op).BcalGetOp();
      if (p && p.datestrshow) {
        $("#txtdatetimeshow").text(p.datestrshow);
      }
      $("#caltoolbar").noSelect();
      
      $("#hdtxtshow").datepicker({
        picker: "#txtdatetimeshow", showtarget: $("#txtdatetimeshow"),
        onReturn:function(r){                          
          var p = $("#gridcontainer").gotoDate(r).BcalGetOp();
          if (p && p.datestrshow) {
            $("#txtdatetimeshow").text(p.datestrshow);
          }
        }
      });
      
      function cal_beforerequest(type)
      {
        $.blockUI({ message: '<?='<img src="'.$this->config->item('wdcalendar').'ajax-loader.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
        var t="Loading data...";
        switch(type)
        {
          case 1:
              t="Loading data...";
              break;
          case 2:
          case 3:
          case 4:
              t="The request is being processed ...";                                   
              break;
        }
        $("#errorpannel").hide();
        $("#loadingpannel").html(t).show();    
      }
      
      function cal_afterrequest(type)
      {
        $.unblockUI();
        switch(type)
        {
          case 1:
              $("#loadingpannel").hide();
              break;
          case 2:
          case 3:
          case 4:
              $("#loadingpannel").html("Success!");
              $("#gridcontainer").reload();
              window.setTimeout(function(){ $("#loadingpannel").hide();},2000);
              break;
        }
      }
      
      function cal_onerror(type,data)
      {
        $("#errorpannel").show();
      }
      
      function Edit(data)
      {
		  var type=$('#type').val();
		  var acc=$('#acc').val();
        /*var eurl="<?=$this->config->item('wdcalendar')?>edit.php?agentid=<?=!empty($agentid)?$agentid:''?>&agentname=<?=!empty($agentname)?$agentname:''?>&id={0}&start={2}&end={3}&isallday={4}&title={1}";*/
				var eurl='<?=base_url()?>admin/calendar/edit_calender/?id={0}&start={2}&end={3}&isallday={4}&title={1}&type='+type+'&access='+acc;
        if(data)
        {
          var url = StrFormat(eurl,data);
          OpenModelWindow(url,{ width: 600, height: 400, caption:"Appointment",onclose:function(){
            $("#gridcontainer").reload();
          }});
        }
      }
      
      function View(data)
      {
        var str = "";
        
        var stdt=moment(data[2]).format('LLLL');
        var etdt=moment(data[3]).format('LLLL');
        
        var str = data[9];
        str += " has appointment on "+ "\n" + stdt ;
        str +=" to "+ etdt +"\n for "+data[1];
        
        alert(str);
      }
      
      /*Code for changing time to 12 hours Start*/
      function get12hrstime(h) {
        var mt="";
        if(h<12)
        {
          mt=h < 10 ? "0" + h + ":00" : h + ":00";
          mt+=" AM";
        }
        else
        {
          if(h!=12)
          {
            h-=12;
          }
          mt=h < 10 ? "0" + h + ":00" : h + ":00";
          mt+=" PM";
        }
        return mt;
      }
      /*Code for changing time to 12 hours End*/
         
      function Delete(data,callback)
      {
				$.alerts.okButton="Ok";
				$.alerts.cancelButton="Cancel";
				if(data[6]==1)
				{
					if(confirm("This appointment has recurring appointment's deleting this will delete all recurring appointment!"))
					{
						hiConfirm("Are you sure to cancel this appointment", 'Confirm',function(r){ r && callback(0);});
					}
				}
				else
				{
					hiConfirm("Are You sure to cancel this appointment", 'Confirm',function(r){ r && callback(0);});
				}
      }
      
      function wtd(p)
      {
        if (p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
        $("#caltoolbar div.fcurrent").each(function() {
          $(this).removeClass("fcurrent");
        })
        $("#showdaybtn").addClass("fcurrent");
      }
      
      //to show day view
      $("#showdaybtn").click(function(e) {
        //document.location.href="#day";
        $("#caltoolbar div.fcurrent").each(function() {
          $(this).removeClass("fcurrent");
        })
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("day").BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      //to show week view
      $("#showweekbtn").click(function(e) {
        //document.location.href="#week";
        $("#caltoolbar div.fcurrent").each(function() {
          $(this).removeClass("fcurrent");
        })
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("week").BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      //to show month view
      $("#showmonthbtn").click(function(e) {
        //document.location.href="#month";
        $("#caltoolbar div.fcurrent").each(function() {
          $(this).removeClass("fcurrent");
        })
        $(this).addClass("fcurrent");
        var p = $("#gridcontainer").swtichView("month").BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      $("#showreflashbtn").click(function(e){
        $("#gridcontainer").reload();
      });
      
      //Add a new event
      $("#faddbtn").click(function(e) {
	  
        var url ="<?=base_url('admin/calendar/edit_calender')?>";
        OpenModelWindow(url,{ width: 600, height: 400, caption: "Appointment"});
      });
      
      //Default Loading Function
      $(function(e) {
        var p = $("#gridcontainer").gotoDate().BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      //go to today
      $("#showtodaybtn").click(function(e) {
        var p = $("#gridcontainer").gotoDate().BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      //previous date range
      $("#sfprevbtn").click(function(e) {
        var p = $("#gridcontainer").previousRange().BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
      
      //next date range
      $("#sfnextbtn").click(function(e) {
        var p = $("#gridcontainer").nextRange().BcalGetOp();
        if(p && p.datestrshow) {
          $("#txtdatetimeshow").text(p.datestrshow);
        }
      });
    });
  </script>
  
	<input type="hidden" id="baseurl" value="<?=base_url($admin_type).'/'?>" />
  	<input type="hidden" id="type" value="<?=$user_session_array_array['type']?>" name="type"/>
    <input type="hidden" id="acc" value="<?=$user_session_array_array['access']?>" name="access"/>
  <div class="cal_table_hit8">
    <div id="calhead" style="padding-left:1px;padding-right:1px;"> 
      <?php /*?><div class="cHead">
        <!--<div class="ftitle" style="float:left;">My Appointment</div> -->
        
        <div style="float:right;">
        	<!--<font style="background-color:#4CB052; padding:4px 5px; border-radius:5px; color:#FFF;">Book Appointment</font>--><!-- color=6 -->
        </div>
        
        <!-- Float-right End -->
        
        <div id="loadingpannel" class="ptogtitle loadicon" style="display: none;">Loading data...</div>
        
        <div id="errorpannel" class="ptogtitle loaderror" style="display: none;">Sorry, could not load your data, please try again later</div>
      </div><?php */?>
      
      <!-- cHead End -->
    	
      <div id="caltoolbar" class="ctoolbar">
       <?php if(!empty($this->modules_unique_name) && in_array('calendar_add',$this->modules_unique_name)){?>
        <div id="faddbtn" class="fbutton">
        	<div><span title='Click to Create New Appointment' class="addcal">New Appointment</span></div>
        </div>
        <? } ?>
        <div class="btnseparator"></div>
        
        <div id="showtodaybtn" class="fbutton" >
          <div><span title='Click to back to today ' style="padding:0px 3px 0 0px; line-height:20px; font-size:10px; font-weight:bold; text-align:center; color:#000; width:16px;" class="showtoday"><?=date('d')?></span>Today</div>
        </div>
        
        <div class="btnseparator"></div>
        
        <div id="showdaybtn" class="fbutton"><div><span title='Day' class="showdayview">Day</span></div></div>
        
        <div id="showweekbtn" class="fbutton fcurrent">
        	<div><span title='Week' class="showweekview">Week</span></div>
        </div>
        
        <div id="showmonthbtn" class="fbutton">
        	<div><span title='Month' class="showmonthview">Month</span></div>
        </div>
        
        <div class="btnseparator"></div>
        
        <div id="showreflashbtn" class="fbutton">
        	<div><span title='Refresh view' class="showdayflash">Refresh</span></div>
        </div>
		
		<div id="showreflashbtn" class="fbutton pull-right">
        	<div>
            <?php if(!empty($this->modules_unique_name) && in_array('google_calendar',$this->modules_unique_name)){?>
           <?php /* <a href="https://www.google.com/accounts/AuthSubRequest?next=<?=base_url('admin/calendar/google_connection')?>
&scope=https://www.google.com/calendar/feeds/&secure=&session=1">Fetch events from Google Calendar</a>
            * 
            */ ?>
                    <a href="<?=base_url('admin/calendar/google_connection')?>">Fetch events from Google Calendar</a>
			<? } ?>
			</div>
        </div>
        
        <div class="btnseparator"></div>
        <div id="sfprevbtn" title="Prev"  class="fbutton"><span class="fprev"></span></div>
        <div id="sfnextbtn" title="Next" class="fbutton"><span class="fnext"></span></div>
        
        <div class="fshowdatep fbutton">
          <div>
	          <input type="hidden" name="txtshow" id="hdtxtshow" />
  	        <span id="txtdatetimeshow">Loading</span>
          </div>
        </div>
        
        <div class="clear"></div>
      </div><!-- caltoolbar End -->
    </div><!-- calhead End -->
		
    <div style="padding:1px; padding-bottom:15px;">
    	<div class="t1 chromeColor">&nbsp;</div>
    	<div class="t2 chromeColor">&nbsp;</div>
      <div id="dvCalMain" class="calmain printborder">
	      <div id="gridcontainer" style="overflow-y: visible;"></div>
      </div>
    	<div class="t2 chromeColor">&nbsp;</div>
    	<div class="t1 chromeColor">&nbsp;</div> 
    </div>
	</div><!-- cal_table_hit8 End -->
  
  <div class="clear"></div>
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