<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
<title>Calendar Details</title>
<link href="<?=$this->config->item('wdcalendar')?>css/main.css" rel="stylesheet" type="text/css" />
<link href="<?=$this->config->item('wdcalendar')?>css/dp.css" rel="stylesheet" />
<link href="<?=$this->config->item('wdcalendar')?>css/dropdown.css" rel="stylesheet" />
<link href="<?=$this->config->item('wdcalendar')?>css/colorselect.css" rel="stylesheet" />
<link rel="stylesheet" href="<?=$this->config->item('css_path')?>jquery.confirm.css" type="text/css" />

<?php /*?><link href="<?=$this->config->item('wdcalendar')?>css/bootsrap.css" rel="stylesheet" /><?php */

?>

<style>
<?php if(empty($event)) {
?>  
	.tr_repeat_hourly, .tr_repeattype, .tr_repeat_daily, .tr_repeat_yearly, .tr_repeat_weekly, .tr_repeat_monthly, .tr_end_template {
 display: none;}
 <?php
} else {
	
	 /*?>if($event[0]['repeatType'] == '1')
	{ ?>
		.tr_repeat_daily, .tr_repeat_weekly, .tr_repeat_monthly {
 display: none;}
	
	<?php } <?php */
	if($event[0]['repeatType'] == '2')
	{ ?>
		.tr_repeat_hourly, .tr_repeat_weekly, .tr_repeat_monthly, .tr_repeat_yearly {
 display: none;}
	
	<?php } 
	if($event[0]['repeatType'] == '3')
	{ ?>
		.tr_repeat_hourly, .tr_repeat_daily, .tr_repeat_monthly, .tr_repeat_yearly {
 display: none;}
	
	<?php } 
	if($event[0]['repeatType'] == '4')
	{ ?>
		.tr_repeat_hourly, .tr_repeat_daily, .tr_repeat_weekly, .tr_repeat_yearly {
 display: none;}
	
	<?php } 
        if($event[0]['repeatType'] == '5')
	{ ?>
		.tr_repeat_hourly, .tr_repeat_daily, .tr_repeat_weekly, .tr_repeat_monthly  {
 display: none;}
	
	<?php }
        }?>
                
<?php
    if(!empty($event[0]['edit_flag']) && $event[0]['edit_flag'] == 1)
    { ?>
        .tr_sendtemplate, .tr_repeat_hourly, .tr_repeattype, .tr_repeat_daily, .tr_repeat_yearly, .tr_repeat_weekly, .tr_repeat_monthly, .tr_end_template {
 display: none;}  
    <?php } ?>
        
<?php
    if(empty($event[0]['ifRepeat']))
    { ?>
       .tr_repeat_hourly, .tr_repeattype, .tr_repeat_daily, .tr_repeat_yearly, .tr_repeat_weekly, .tr_repeat_monthly, .tr_end_template {
 display: none;}  
    <?php } ?>

</style>
<script src="<?=$this->config->item('wdcalendar')?>src/jquery.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/Common.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.form.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.validate.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.datepicker.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.dropdown.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/jquery.colorselect.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/jquery.blockUI.js" type="text/javascript"></script>
<script src="<?=$this->config->item('wdcalendar')?>src/Plugins/moment.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$this->config->item('js_path')?>jquery.confirm.js"></script> 
<script language="javascript" type="text/javascript">
	
	
    if (!DateAdd || typeof (DateDiff) != "function") {
        var DateAdd = function(interval, number, idate) {
            number = parseInt(number);
            var date;
            if (typeof (idate) == "string") {
                date = idate.split(/\D/);
                eval("var date = new Date(" + date.join(",") + ")");
            }
            if (typeof (idate) == "object") {
                date = new Date(idate.toString());
            }
            switch (interval) {
                case "y": date.setFullYear(date.getFullYear() + number); break;
                case "m": date.setMonth(date.getMonth() + number); break;
                case "d": date.setDate(date.getDate() + number); break;
                case "w": date.setDate(date.getDate() + 7 * number); break;
                case "h": date.setHours(date.getHours() + number); break;
                case "n": date.setMinutes(date.getMinutes() + number); break;
                case "s": date.setSeconds(date.getSeconds() + number); break;
                case "l": date.setMilliseconds(date.getMilliseconds() + number); break;
            }
            return date;
        }
    }
    function getHM(date)
    {
         var hour =date.getHours();
         var minute= date.getMinutes();
         var ret= (hour>9?hour:"0"+hour)+":"+(minute>9?minute:"0"+minute) ;
         return ret;
    }
    $(document).ready(function() {
                    //debugger;

        var DATA_FEED_URL = "<?=base_url()?>";
        var arrT = [];
        var tt = "{0}:{1}";
        for (var i = 0; i < 24; i++) {
            arrT.push({ text: StrFormat(tt, [i >= 10 ? i : "0" + i, "00"]) }, { text: StrFormat(tt, [i >= 10 ? i : "0" + i, "30"]) });
        }
        $("#timezone").val(new Date().getTimezoneOffset()/60 * -1);
        $("#stparttime").dropdown({
            dropheight:0,
            dropwidth:0,
            selectedchange: function() { },
            items: arrT
        });
        $("#etparttime").dropdown({
            dropheight:0,
            dropwidth:0,
            selectedchange: function() { },
            items: arrT
        });



                                            /* IsAllDayEvent Code Commented Start */
        /*var check = $("#IsAllDayEvent").click(function(e) {
            if (this.checked) {
                $("#stparttime").val("00:00").hide();
                $("#etparttime").val("00:00").hide();
            }
            else {
                var d = new Date();
                var p = 60 - d.getMinutes();
                if (p > 30) p = p - 30;
                d = DateAdd("n", p, d);
                $("#stparttime").val(getHM(d)).show();
                $("#etparttime").val(getHM(DateAdd("h", 1, d))).show();
            }
        });
        if(check[0].checked) {
            $("#stparttime").val("00:00").hide();
            $("#etparttime").val("00:00").hide();
        }*/
                                            /* IsAllDayEvent Code Commented End */

        $("#Savebtn").click(function(){
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            var errflag = 0;
            var userid = $('#userid').val();
            sttime=$("#stparttime").val();
            ettime=$("#etparttime").val();
            stdate=$("#stpartdate").val();
            etdate=$("#etpartdate").val();
            endtdt = $('#endTemplateDate').val();
            endttime=$("#endTemplateTime").val();

            type=$("#type").val();
            cur_date= Date();
            cur_date=moment(cur_date).format('MM/DD/YYYY');

            if(stdate == etdate && sttime>ettime) {
                errflag = 1;
            }
            else if((new Date(stdate).getTime() > new Date(etdate).getTime())) {
                errflag = 2;
            }
            else if($('input[name=repeatType]:checked', '#fmEdit').val() == 3)
            {
                if($("[name='everyWeekonMon']:checked").length == 1 || $("[name='everyWeekonTue']:checked").length == 1 || $("[name='everyWeekonWed']:checked").length == 1 || $("[name='everyWeekonThu']:checked").length == 1 || $("[name='everyWeekonFri']:checked").length == 1 || $("[name='everyWeekonSat']:checked").length == 1 || $("[name='everyWeekonSun']:checked").length == 1) { }
                else {
                    errflag = 3;
                }
            }
            else if($('input[name=endTemplateType]:checked', '#fmEdit').val() == 3)
            {
                if(stdate == endtdt && sttime>endttime) {
                    errflag = 4;
                }
                else if((new Date(stdate).getTime() > new Date(endtdt).getTime())) {
                    errflag = 5;
                }
            }

            if(errflag == 1)
            {
                $.confirm({'title': 'Alert','message': " <strong> End time should be greater than start time<strong></strong>",'buttons': {'ok'	: {'class'	: 'uibutton large btn_center alert_ok'}}});
			    $.unblockUI();
				return false;
            }
            else if(errflag == 2 && $("#title").val().trim() != '')
            {
				$.confirm({'title': 'Alert','message': " <strong> End date should be greater than start date<strong></strong>",'buttons': {'ok'	: {'class'	: 'uibutton large btn_center alert_ok'}}});
                $.unblockUI();
				return false;
            }
            else if(errflag == 3 && $("#title").val().trim() != '')
            {
              	$.confirm({'title': 'Alert','message': " <strong> Please select atleast one weekday<strong></strong>",'buttons': {'ok'	: {'class'	: 'uibutton large btn_center alert_ok'}}});
                $.unblockUI();
				return false;
            }
            else if(errflag == 4 && $("#title").val().trim() != '')
            {
              $.confirm({'title': 'Alert','message': " <strong> End on time should be greater than start time<strong></strong>",'buttons': {'ok'	: {'class'	: 'uibutton large btn_center alert_ok'}}});
				$.unblockUI();
				return false;
            }
            else if(errflag == 5 && $("#title").val().trim() != '')
            {
             	 $.confirm({'title': 'Alert','message': " <strong> End on date should be greater than start date<strong></strong>",'buttons': {'ok'	: {'class'	: 'uibutton large btn_center alert_ok'}}});
				 $.unblockUI();
				 return false;
            }
            else
            {

                var date = document.getElementById('stpartdate').value+ ' ' +document.getElementById('stparttime').value;
                var end_date = document.getElementById('stpartdate').value+ ' ' +document.getElementById('etparttime').value;
                <?php if(!empty($id) && $id!=0){ ?>
                        apptid = <?=$id?>;
                <?php }else{ ?>
                        apptid = "";
                <?php } ?>

                date=moment(date).format('YYYY-MM-DD HH:mm:ss');
                end_date=moment(end_date).format('YYYY-MM-DD HH:mm:ss');
                
                $("#fmEdit").submit();
                setTimeout(function(){ $.unblockUI(); }, 5000);
                
            }
            return false;
        });

        $("#Savebtn1").click(function(){
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});

            var Agent=$("#Agent").val();

            if(Agent=="")
            {
                alert("Please Select Agent");
                $.unblockUI();
            }
            else
            {
                <?php if(!empty($id) && $id!=0){ ?>
                        apptid = <?=$id?>;
                <?php }else{ ?>
                        apptid = "";
                <?php } ?>

                /*var file = "php/getuser.php?date="+date+"&end_date="+end_date+"&apptid="+apptid;

                $.ajax({
                    url:file,
                    cache:false,
                    success: function(response) 
                    {
                        if(response==0)
                        {
                            alert("Appointment already there on this Date and Time.");
                            $.unblockUI();
                        }
                        else
                        {*/
                            $("#fmEdit").submit();
                            setTimeout(function(){ $.unblockUI(); }, 10000);
                        /*}
                    }
                });*/
            }
            return false;
        });

        $("#Closebtn").click(function(){ CloseModelWindow(); });
        $("#Deletebtn").click(function(){
            $.blockUI({ message: '<?='<img src="'.base_url('images').'/loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
            if(confirm("Do you confirm to cancel this appointment")){
                var param = [{ "name": "calendarId", value: <?=(!empty($id))?$id:1?>}];
                $.post(DATA_FEED_URL + "user/calendar/delete_calender",
                param,
                function(data){
                        if(data.IsSuccess)
                        {
                            alert(data.Msg);
                            CloseModelWindow(null,true);
                        }
                        else
                            alert("Error occurs.\r\n" + data.Msg);
                }
                ,"json");
            }
            setTimeout(function(){$.unblockUI();}, 5000);
        });

        var dateToday = new Date();
        $("#stpartdate,#etpartdate,#endTemplateDate").datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            changeYear: true,
            minDate: dateToday,
            maxDate: 365,
            numberOfMonths: 1,
            picker: "<button class='calpick'></button>",
        });



        var cv =$("#colorvalue").val() ;
        if(cv=="")
        {
            cv="-1";
        }
        $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
        //to define parameters of ajaxform
        var options = {
            beforeSubmit: function() {
                return true;
            },
            dataType: "json",
            success: function(data) {
                alert(data.Msg);
                if (data.IsSuccess) {
                    CloseModelWindow(null,true);
                }
            }
        };
        $.validator.addMethod("date", function(value, element) {
            var arrs = value.split(i18n.datepicker.dateformat.separator);
            var year = arrs[i18n.datepicker.dateformat.year_index];
            var month = arrs[i18n.datepicker.dateformat.month_index];
            var day = arrs[i18n.datepicker.dateformat.day_index];
            var standvalue = [year,month,day].join("-");
            return this.optional(element) || /^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3-9]|1[0-2])[\/\-\.](?:29|30))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1,3,5,7,8]|1[02])[\/\-\.]31)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:16|[2468][048]|[3579][26])00[\/\-\.]0?2[\/\-\.]29)(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?: \d{1,3})?)?$|^(?:(?:1[6-9]|[2-9]\d)?\d{2}[\/\-\.](?:0?[1-9]|1[0-2])[\/\-\.](?:0?[1-9]|1\d|2[0-8]))(?: (?:0?\d|1\d|2[0-3])\:(?:0?\d|[1-5]\d)\:(?:0?\d|[1-5]\d)(?:\d{1,3})?)?$/.test(standvalue);
        }, "Invalid date format");
        $.validator.addMethod("time", function(value, element) {
            return this.optional(element) || /^([0-1]?[0-9]|2[0-3]):([0-5][0-9])$/.test(value);
        }, "Invalid time format");
        $.validator.addMethod("safe", function(value, element) {
            return this.optional(element) || /^[^$\<\>]+$/.test(value);
        }, "$<> not allowed");
        $("#fmEdit").validate({
            submitHandler: function(form) { $("#fmEdit").ajaxSubmit(options); },
            errorElement: "div",
            errorClass: "cusErrorPanel",
            errorPlacement: function(error, element) {
                showerror(error, element);
            }
        });
        function showerror(error, target) {
            var pos = target.position();
            var height = target.height();
            var newpos = { left: pos.left, top: pos.top + height + 2 }
            var form = $("#fmEdit");
            error.appendTo(form).css(newpos);
        }
    });
		
    var cv =$("#colorvalue").val() ;
    if(cv=="")
    {
        cv="-1";
    }
    $("#calendarcolor").colorselect({ title: "Color", index: cv, hiddenid: "colorvalue" });
</script>
<style type="text/css">
.calpick { width:16px; height:16px; border:none; cursor:pointer; background:url("<?=base_url('images/cal.gif')?>") no-repeat center 2px; margin-left:-20px; margin-right:3px; position:absolute; margin-top:7px;/* top:-5px;*/ }
.mrg12 {margin:10px 0; display:block;}
.mrg13 {margin:7px 0 5px 0; display:block;}
input {margin-left:0px !important;}
.tr_repeat_daily label {width: auto;}
.tr_repeat_yearly label {width: auto;}
.tr_repeat_monthly label {width: auto;}
.day {width:78px;}
.day1 {width:105px;}

</style>
</head>
<body>
<?php
	if(isset($_GET['flag']) && $_GET['flag']==1)
	{ 
	?>
<div id="mainfrm1">
  
  <!-- Infocontainer End -->
  <div style="clear: both"></div>
  <div class="toolBotton btn-hit-654"> <a id="Savebtn1" class="imgbtn" href="javascript:void(0);"> <span class="Save" title="Save the calendar">Save</span> </a>
    <?php if(isset($event)){ ?>
    <a id="Deletebtn" class="imgbtn" href="javascript:void(0);"> <span class="Delete" title="Cancel the calendar">Delete</span> </a>
    <?php } ?>
    <a id="Closebtn" class="imgbtn" href="javascript:void(0);"> <span class="Close" title="Close the window">Close</span> </a> </div>
  <!-- toolbooton End --> 
</div>
<!-- Mainfrm1 End -->
<?php
	}
	else
	{
	?>
<div id="mainfrm1">
  <div class="infocontainer">
  
    <form action="<?=base_url('user/calendar/update_calender')?>" class="fform" id="fmEdit" method="post">
      
      <!--<input id="userid" name="userid" type="hidden" value="<?=isset($event)?$event->userid:"0"?>" />-->
      <input id="agentid" name="agentid" type="hidden" value="<?=!empty($event[0]['admin_id'])?$event[0]['admin_id']:'';?>" />
      <input id="id" name="id" type="hidden" value="<?=!empty($event[0]['id'])?$event[0]['id']:'';?>" />
	  <input id="calendar_id" name="calendar_id" type="hidden" value="<?=!empty($event[0]['calendar_id'])?$event[0]['calendar_id']:'';?>" />
      <input id="cal_id" name="cal_id" type="hidden" value="<?=!empty($event[0]['cal_id'])?$event[0]['cal_id']:'';?>" />
      <!--<input id="appconf" name="appconf" type="hidden" value="<?=isset($event)?$event->accepted:"0"?>" />-->
      
      <label style="width:570px;"> <span>Title<b>*</b></span>
        <input MaxLength="100" class="required safe form-control" style="width:360px;" id="title" name="title" type="text" value="<?=!empty($event[0]['event_title'])?$event[0]['event_title']:'';?>"/>
      </label>
      <label style="width:570px;"> <span>Notes:</span>
        <textarea cols="20" id="Description" class="textarea-hit-new654 form-control " name="Description" rows="2"><?=!empty($event[0]['event_notes'])?$event[0]['event_notes']:'';?>
</textarea>
      </label>
      
        <!--<label> 
      <span>Reason<b>*</b></span>--> 
        <!--<div id="calendarcolor"></div>--> 
        <!--<input MaxLength="200" class="required safe" id="Subject" style="width:245px;" name="Subject" type="hidden" value="<?=isset($event)?$event->reason:"Open Appointment"?>" />
      </label>--> 
      <label style="width:570px;">
      <span>Start Time<b>*</b></span>
      <div class="date-hit-new654">
      <div class="date-hit-new654">
        <?php if(isset($event)){
				  $start_date=!empty($event[0]['start_date'])?$event[0]['start_date']:'';
				  $end_date=!empty($event[0]['end_date'])?$event[0]['end_date']:'';
				  $sarr = explode(" ", php2JsTime(mySql2PhpTime($start_date)));
                  $earr = explode(" ", php2JsTime(mySql2PhpTime($end_date)));
              }
               if(!empty($start) && !empty($end))
              {
				$sarr1 = explode(" ", php2JsTime(mySql2PhpTime($start)));
                $earr1 = explode(" ", php2JsTime(mySql2PhpTime($end)));
              }
              else
              {
                $sarr1[0]="";
                $sarr1[1]="";
                $earr1[0]="";
                $earr1[1]="";
              }
              ?>
        <input MaxLength="10" class="required date form-control" id="stpartdate" name="stpartdate" style="padding-left:2px; width:90px;" type="text" value="<?=isset($event)?$sarr[0]:$sarr1[0]?>" readonly />
        <input type="hidden" name="stparttime_hid" id="stparttime_hid" value="<?=isset($event)?$sarr[1]:$sarr1[1]?>" />
        <select id="stparttime" name="stparttime" style="width:115px; min-height:33px !important;" class="select_box form-control">
          <option value="00:00">12:00 AM</option>
          <option value="00:15">12:15 AM</option>
          <option value="00:30">12:30 AM</option>
          <option value="00:45">12:45 AM</option>
          <option value="01:00">01:00 AM</option>
          <option value="01:15">01:15 AM</option>
          <option value="01:30">01:30 AM</option>
          <option value="01:45">01:45 AM</option>
          <option value="02:00">02:00 AM</option>
          <option value="02:15">02:15 AM</option>
          <option value="02:30">02:30 AM</option>
          <option value="02:45">02:45 AM</option>
          <option value="03:00">03:00 AM</option>
          <option value="03:15">03:15 AM</option>
          <option value="03:30">03:30 AM</option>
          <option value="03:45">03:45 AM</option>
          <option value="04:00">04:00 AM</option>
          <option value="04:15">04:15 AM</option>
          <option value="04:30">04:30 AM</option>
          <option value="04:45">04:45 AM</option>
          <option value="05:00">05:00 AM</option>
          <option value="05:15">05:15 AM</option>
          <option value="05:30">05:30 AM</option>
          <option value="05:45">05:45 AM</option>
          <option value="06:00">06:00 AM</option>
          <option value="06:15">06:15 AM</option>
          <option value="06:30">06:30 AM</option>
          <option value="06:45">06:45 AM</option>
          <option value="07:00">07:00 AM</option>
          <option value="07:15">07:15 AM</option>
          <option value="07:30">07:30 AM</option>
          <option value="07:45">07:45 AM</option>
          <option value="08:00">08:00 AM</option>
          <option value="08:15">08:15 AM</option>
          <option value="08:30">08:30 AM</option>
          <option value="08:45">08:45 AM</option>
          <option value="09:00">09:00 AM</option>
          <option value="09:15">09:15 AM</option>
          <option value="09:30">09:30 AM</option>
          <option value="09:45">09:45 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="10:15">10:15 AM</option>
          <option value="10:30">10:30 AM</option>
          <option value="10:45">10:45 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="11:15">11:15 AM</option>
          <option value="11:30">11:30 AM</option>
          <option value="11:45">11:45 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="12:15">12:15 PM</option>
          <option value="12:30">12:30 PM</option>
          <option value="12:45">12:45 PM</option>
          <option value="13:00">01:00 PM</option>
          <option value="13:15">01:15 PM</option>
          <option value="13:30">01:30 PM</option>
          <option value="13:45">01:45 PM</option>
          <option value="14:00">02:00 PM</option>
          <option value="14:15">02:15 PM</option>
          <option value="14:30">02:30 PM</option>
          <option value="14:45">02:45 PM</option>
          <option value="15:00">03:00 PM</option>
          <option value="15:15">03:15 PM</option>
          <option value="15:30">03:30 PM</option>
          <option value="15:45">03:45 PM</option>
          <option value="16:00">04:00 PM</option>
          <option value="16:15">04:15 PM</option>
          <option value="16:30">04:30 PM</option>
          <option value="16:45">04:45 PM</option>
          <option value="17:00">05:00 PM</option>
          <option value="17:15">05:15 PM</option>
          <option value="17:30">05:30 PM</option>
          <option value="17:45">05:45 PM</option>
          <option value="18:00">06:00 PM</option>
          <option value="18:15">06:15 PM</option>
          <option value="18:30">06:30 PM</option>
          <option value="18:45">06:45 PM</option>
          <option value="19:00">07:00 PM</option>
          <option value="19:15">07:15 PM</option>
          <option value="19:30">07:30 PM</option>
          <option value="19:45">07:45 PM</option>
          <option value="20:00">08:00 PM</option>
          <option value="20:15">08:15 PM</option>
          <option value="20:30">08:30 PM</option>
          <option value="20:45">08:45 PM</option>
          <option value="21:00">09:00 PM</option>
          <option value="21:15">09:15 PM</option>
          <option value="21:30">09:30 PM</option>
          <option value="21:45">09:45 PM</option>
          <option value="22:00">10:00 PM</option>
          <option value="22:15">10:15 PM</option>
          <option value="22:30">10:30 PM</option>
          <option value="22:45">10:45 PM</option>
          <option value="23:00">11:00 PM</option>
          <option value="23:15">11:15 PM</option>
          <option value="23:30">11:30 PM</option>
          <option value="23:45">11:45 PM</option>
        </select>
      </div>
	 </div>
      </label>
      <label>
      <span>End Time<b>*</b></span>
      <div class="date-hit-new654">
        <input type="text" class="required date form-control" id="etpartdate" name="etpartdate" readonly="" value="<?=isset($event)?$earr[0]:$earr1[0]?>" style="padding-left:2px; width:90px;"  />
        
        <!--<input MaxLength="5" class="required time" id="stparttime" name="stparttime" type="text" style="width:40px;" value="<?=isset($event)?$earr[1]:$earr1[1]?>" readonly />-->
        <input type="hidden" name="etparttime_hid" id="etparttime_hid" value="<?=isset($event)?$earr[1]:$earr1[1]?>" />
        <select id="etparttime" name="etparttime" style="width:115px; min-height:33px !important;" class="select_box form-control">
          <option value="00:00">12:00 AM</option>
          <option value="00:15">12:15 AM</option>
          <option value="00:30">12:30 AM</option>
          <option value="00:45">12:45 AM</option>
          <option value="01:00">01:00 AM</option>
          <option value="01:15">01:15 AM</option>
          <option value="01:30">01:30 AM</option>
          <option value="01:45">01:45 AM</option>
          <option value="02:00">02:00 AM</option>
          <option value="02:15">02:15 AM</option>
          <option value="02:30">02:30 AM</option>
          <option value="02:45">02:45 AM</option>
          <option value="03:00">03:00 AM</option>
          <option value="03:15">03:15 AM</option>
          <option value="03:30">03:30 AM</option>
          <option value="03:45">03:45 AM</option>
          <option value="04:00">04:00 AM</option>
          <option value="04:15">04:15 AM</option>
          <option value="04:30">04:30 AM</option>
          <option value="04:45">04:45 AM</option>
          <option value="05:00">05:00 AM</option>
          <option value="05:15">05:15 AM</option>
          <option value="05:30">05:30 AM</option>
          <option value="05:45">05:45 AM</option>
          <option value="06:00">06:00 AM</option>
          <option value="06:15">06:15 AM</option>
          <option value="06:30">06:30 AM</option>
          <option value="06:45">06:45 AM</option>
          <option value="07:00">07:00 AM</option>
          <option value="07:15">07:15 AM</option>
          <option value="07:30">07:30 AM</option>
          <option value="07:45">07:45 AM</option>
          <option value="08:00">08:00 AM</option>
          <option value="08:15">08:15 AM</option>
          <option value="08:30">08:30 AM</option>
          <option value="08:45">08:45 AM</option>
          <option value="09:00">09:00 AM</option>
          <option value="09:15">09:15 AM</option>
          <option value="09:30">09:30 AM</option>
          <option value="09:45">09:45 AM</option>
          <option value="10:00">10:00 AM</option>
          <option value="10:15">10:15 AM</option>
          <option value="10:30">10:30 AM</option>
          <option value="10:45">10:45 AM</option>
          <option value="11:00">11:00 AM</option>
          <option value="11:15">11:15 AM</option>
          <option value="11:30">11:30 AM</option>
          <option value="11:45">11:45 AM</option>
          <option value="12:00">12:00 PM</option>
          <option value="12:15">12:15 PM</option>
          <option value="12:30">12:30 PM</option>
          <option value="12:45">12:45 PM</option>
          <option value="13:00">01:00 PM</option>
          <option value="13:15">01:15 PM</option>
          <option value="13:30">01:30 PM</option>
          <option value="13:45">01:45 PM</option>
          <option value="14:00">02:00 PM</option>
          <option value="14:15">02:15 PM</option>
          <option value="14:30">02:30 PM</option>
          <option value="14:45">02:45 PM</option>
          <option value="15:00">03:00 PM</option>
          <option value="15:15">03:15 PM</option>
          <option value="15:30">03:30 PM</option>
          <option value="15:45">03:45 PM</option>
          <option value="16:00">04:00 PM</option>
          <option value="16:15">04:15 PM</option>
          <option value="16:30">04:30 PM</option>
          <option value="16:45">04:45 PM</option>
          <option value="17:00">05:00 PM</option>
          <option value="17:15">05:15 PM</option>
          <option value="17:30">05:30 PM</option>
          <option value="17:45">05:45 PM</option>
          <option value="18:00">06:00 PM</option>
          <option value="18:15">06:15 PM</option>
          <option value="18:30">06:30 PM</option>
          <option value="18:45">06:45 PM</option>
          <option value="19:00">07:00 PM</option>
          <option value="19:15">07:15 PM</option>
          <option value="19:30">07:30 PM</option>
          <option value="19:45">07:45 PM</option>
          <option value="20:00">08:00 PM</option>
          <option value="20:15">08:15 PM</option>
          <option value="20:30">08:30 PM</option>
          <option value="20:45">08:45 PM</option>
          <option value="21:00">09:00 PM</option>
          <option value="21:15">09:15 PM</option>
          <option value="21:30">09:30 PM</option>
          <option value="21:45">09:45 PM</option>
          <option value="22:00">10:00 PM</option>
          <option value="22:15">10:15 PM</option>
          <option value="22:30">10:30 PM</option>
          <option value="22:45">10:45 PM</option>
          <option value="23:00">11:00 PM</option>
          <option value="23:15">11:15 PM</option>
          <option value="23:30">11:30 PM</option>
          <option value="23:45">11:45 PM</option>
        </select>
      </div>
      </label>
      <label>
      <span>Event Color:</span>
      <input id="colorvalue" name="event_color" type="hidden" value="<?php echo !empty($event)?$event[0]['event_color']:"" ?>" />
      <div id="calendarcolor"> </div>
      </label>
        <?php /*
      <label> <span>Post to Public calendar</span>
        <input type="checkbox"  id="is_public" <?=!empty($event[0]['is_public'])?'checked=checked':'';?> class="" name="is_public" />
      </label>
         */
        ?>
	  <div class="clear"></div>
      <table width="395" style="float:left;">
        <tr>
          <td colspan="2"><b class="mrg13">Reminders:</b></td>
        </tr>
        <tr>
          <td width="127">
            <input type="checkbox" <?=!empty($event[0]['is_email']) && $event[0]['is_email']=='1'?'checked=checked':'';?>  id="is_email" name="is_email" />
            Email Before</td>
          <td width="107" align="right">
            <input class="form-control hours" type="text" id="email_time_before" value="<?=!empty($event[0]['email_time_before'])?$event[0]['email_time_before']:'';?>"  name="email_time_before"/>
          </td>
          <td width="113">
            <select name="email_time_type" class="form-control hours">
              <option value="1" <?=!empty($event[0]['email_time_type']) && $event[0]['email_time_type']=='1'?'selected=selected':'';?> >Hours</option>
              <option value="2" <?=!empty($event[0]['email_time_type']) && $event[0]['email_time_type']=='2'?'selected=selected':'';?>>Day</option>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <input type="checkbox" <?=!empty($event[0]['is_popup']) && $event[0]['is_popup']=='1'?'checked=checked':'';?> cols="20" id="is_popup" name="is_popup" rows="2"/>
            Pop-Up Before</td>
          <td align="right">
            <input class="form-control hours" type="text" id="popup_time_before" value="<?=!empty($event[0]['popup_time_before'])?$event[0]['popup_time_before']:'';?>"   name="popup_time_before"/>
          </td>
          <td>
            <select name="popup_time_type"  id="popup_time_type" class="form-control hours">
              <option value="1" <?=!empty($event[0]['popup_time_type']) && $event[0]['popup_time_type']=='1'?'selected=selected':'';?> >Hours</option>
              <option value="2" <?=!empty($event[0]['popup_time_type']) && $event[0]['popup_time_type']=='2'?'selected=selected':'';?>>Day</option>
            </select>
          </td>
        </tr>
        <tr>
          <td colspan="2"><b class="mrg12">Pop-By/Gift</b></td>
        </tr>
        <tr>
          <td height="30">
            <input type="checkbox"  <?=!empty($event[0]['is_pop_by']) && $event[0]['is_pop_by']=='1'?'checked=checked':'';?>  id="pop_by" class="" name="pop_by" />
            Pop-By
          </td>
        </tr>
        <tr>
          <td height="30">
            <input type="checkbox" cols="20" <?=!empty($event[0]['is_gift']) && $event[0]['is_gift']=='1'?'checked=checked':'';?> id="gift" name="gift" rows="2"/>
            Gift </td>
        </tr>
      </table>
      <table class="recbor" width="97%" border="0">
        <tr>
          <td colspan="2">
            <table width="100%">
              <tr class="tr_sendtemplate">
                <td height="25">
                  <label>
                      <?php
                      $edit_flag = 0;$repeat_flag = 0;
                      if(!empty($event[0]['edit_flag']) && $event[0]['edit_flag'] == 1)
                          $edit_flag = 1;
                      else if(!empty($event[0]['ifRepeat']) && $event[0]['ifRepeat'] == 1)
                          $repeat_flag = 1;
                      ?>
                    <input type="checkbox" name="ifRepeat" id="ifRepeat" value="1" class="cls_ifRepeat" <?php if($edit_flag == 0 && $repeat_flag == 1){ echo 'checked="checked"'; ?><?php } ?> />
                    Recurrence</label>
                    <input type="hidden" id="edit_flag" name="edit_flag" value="<?=$edit_flag?>" />
                    <input type="hidden" id="old_ifrepeat_flag" name="old_ifrepeat_flag" value="<?=$repeat_flag?>" />
                </td>
              </tr>
			  <tr>
			  	<td valign="top">
					<table>
						<tr>
							<td valign="top"  style="border-right:#CCC solid 1px; width:135px; margin-right:10px;">
							<table>
						<tr class="tr_repeattype">
							<td>
							  <?php /*?><label>
								<input type="radio" name="repeatType" class="cls_repeatType" value="1" <?php if(isset($event[0]['repeatType'])){ if($event[0]['repeatType'] == 1){ echo 'checked="checked"';} }else{ echo 'checked="checked"';}?> />
								Hourly </label><?php */?>
								
							  <label>
								<input style="float:none;" type="radio" name="repeatType" class="cls_repeatType" value="2" <?php if(!empty($event[0]['repeatType']) && $event[0]['repeatType'] == '2'){ echo 'checked="checked"'; } else { echo 'checked="checked"';}?>  />
								Daily </label>
							  <label>
								<input type="radio" name="repeatType" class="cls_repeatType" value="3" <?php if(!empty($event[0]['repeatType']) && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
								Weekly </label>
							  <label>
								<input type="radio" name="repeatType" class="cls_repeatType" value="4" <?php if(!empty($event[0]['repeatType']) && $event[0]['repeatType'] == '4'){ echo 'checked="checked"'; ?><?php } ?> />
								Monthly </label>
                                                            <label>
								<input type="radio" name="repeatType" class="cls_repeatType" value="5" <?php if(!empty($event[0]['repeatType']) && $event[0]['repeatType'] == '5'){ echo 'checked="checked"'; ?><?php } ?> />
								Yearly </label>
							</td>
						  </tr>
					</table>
							</td>
							<td valign="top">
							<table>
						<tr class="tr_repeat_hourly">
							<td width="115" align="right">Every </td>						 
							<td width="71"> <input type="text" id="everyHours" class="numeric_value form-control everyh" name="everyHours" value="<?=!empty($event[0]['everyHours']) && $event[0]['repeatType'] == '1'?$event[0]['everyHours']:''?>" /></td>
							<td width="218" align="left">Hours </td>
						  </tr>
						  <tr class="tr_repeat_daily">
							<td colspan="3">
							  <label>
							 
								<input type="radio" name="dailyType" class="cls_dailyType" value="1" <?php if(!empty($event[0]['dailyType']) && $event[0]['dailyType'] == '1' && $event[0]['repeatType'] == '2'){ echo 'checked="checked"';} else{ echo 'checked="checked"';}?>/>
								Every </label>
							  <input style="float:none;" type="text" id="everyDays" class="number form-control" onkeypress='return isNumberKey(event);' name="everyDays" value="<?=!empty($event[0]['everyDays']) && $event[0]['dailyType']== '1' && $event[0]['repeatType'] == '2'?$event[0]['everyDays']:''?>" />
							  Days </td>
						  </tr>
						  <tr class="tr_repeat_daily">
							<td colspan="3">
							  <label>
								<input type="radio" name="dailyType" class="cls_dailyType" value="2" <?php if(!empty($event[0]['dailyType']) && $event[0]['dailyType'] == '2'){ echo 'checked=checked'; ?><?php } ?> />
								Every Weekdays </label>
							</td>
						  </tr>
						  <tr class="tr_repeat_daily">
							<td colspan="3">
							  <label>
								<input type="radio" name="dailyType" class="cls_dailyType" value="3" <?php if(!empty($event[0]['dailyType']) && $event[0]['dailyType'] == '3'){ echo 'checked=checked'; ?><?php } ?> />
								Every Weekends </label>
							</td>
						  </tr>
						  <tr class="tr_repeat_weekly">
							<td colspan="3"> Repeat at Every
							  <input style="float:none;"type="text" id="everyWeeks" name="everyWeeks" class="number form-control" onkeypress='return isNumberKey(event);' value="<?=!empty($event[0]['everyWeeks']) && $event[0]['repeatType'] == '3'?$event[0]['everyWeeks']:''?>" />
							  Weeks on </td>
						  </tr>
						  <tr class="tr_repeat_weekly">
							<td colspan="3">
							  <table>
								<tr>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonMon" value="1" <?php if(!empty($event[0]['everyWeekonMon']) && $event[0]['everyWeekonMon'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Monday</label>
								  </td>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonTue" value="1" <?php if(!empty($event[0]['everyWeekonTue']) && $event[0]['everyWeekonTue'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Tuesday</label>
								  </td>
								</tr>
								<tr>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonWed" value="1" <?php if(!empty($event[0]['everyWeekonWed']) && $event[0]['everyWeekonWed'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Wednesday</label>
								  </td>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonThu" value="1" <?php if(!empty($event[0]['everyWeekonThu']) && $event[0]['everyWeekonThu'] == 1  && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Thursday</label>
								  </td>
								</tr>
								<tr>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonFri" value="1" <?php if(!empty($event[0]['everyWeekonFri']) && $event[0]['everyWeekonFri'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Friday</label>
								  </td>
								  <td>
									<label>
									  <input type="checkbox" name="everyWeekonSat" value="1" <?php if(!empty($event[0]['everyWeekonSat']) && $event[0]['everyWeekonSat'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Saturday</label>
								  </td>
								</tr>
								<tr>
								  <td>
									<label>
									  <input style="float:none;" type="checkbox" name="everyWeekonSun" value="1" <?php if(!empty($event[0]['everyWeekonSun']) && $event[0]['everyWeekonSun'] == 1 && $event[0]['repeatType'] == '3'){ echo 'checked="checked"'; ?><?php } ?> />
									  Sunday</label>
								  </td>
								  <td>&nbsp;</td>
								</tr>
							  </table>
							</td>
						  </tr>
						  
						  
						  
						   <tr class="tr_repeat_monthly">	
					
						  <td width="auto">Repeat at Every<!-- <input style="float:none;" type="text" id="everyWeeks" name="everyWeeks" class="numeric_value form-control" value="<?=!empty($event[0]['everyWeeks']) && $event[0]['repeatType'] == '3'?$event[0]['everyWeeks']:''?>" />--></td>
							  <td width="auto" style="display:block;">Weeks on </td>
						  
						  
							</tr>
						  <tr class="tr_repeat_monthly">
							<td style="float:left;">
						<label style="width:50px; display:block; margin-top:4px; margin-bottom:0px;">
								<input type="radio" name="monthlyType" class="cls_monthlyType" value="1" <?php if(isset($event[0]['monthlyType'])){ if($event[0]['monthlyType'] == 1 && $event[0]['repeatType'] == '4'){ echo 'checked="checked"';} }else{ echo 'checked="checked"';}?> />
								Date 
                                </label>
							  <input type="text" id="monthDate" name="monthDate" max="31" min="1"  class="number form-control mrg1" onkeypress='return isNumberKey(event);' style="width:132px;" value="<?=!empty($event[0]['monthDate']) && $event[0]['repeatType'] == '4' && $event[0]['monthlyType'] == 1?$event[0]['monthDate']:''?>" />
							  <td>
							 <label style="width:45px;"> of every</label>
							  <input type="text" id="monthCount" name="monthCount" class="number form-control" onkeypress='return isNumberKey(event);' style="width:45px;" value="<?=!empty($event[0]['monthCount']) && $event[0]['repeatType'] == '4' && $event[0]['monthlyType'] == 1?$event[0]['monthCount']:''?>" />
							  </td>
							 </td>
							<td>month </td>
						  </tr>
						  <tr class="tr_repeat_monthly">
							<td >
						<label style="width:50px; display:block; margin-top:4px; margin-bottom:0px;">
								<input type="radio" name="monthlyType" class="cls_monthlyType" value="2" <?php if(!empty($event[0]['monthlyType']) && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'checked="checked"'; ?><?php } ?> />
								The 
                                </label>
							  <select id="nthDay" name="nthDay" class="form-control day">
								<option <?php if(!empty($event[0]['nthDay']) && $event[0]['nthDay'] == 1 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="1">First</option>
								<option <?php if(!empty($event[0]['nthDay']) && $event[0]['nthDay'] == 2 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="2">Second</option>
								<option <?php if(!empty($event[0]['nthDay']) && $event[0]['nthDay'] == 3 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="3">Third</option>
								<option <?php if(!empty($event[0]['nthDay']) && $event[0]['nthDay'] == 4 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="4">Fourth</option>
								<option <?php if(!empty($event[0]['nthDay']) && $event[0]['nthDay'] == 5 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="5">Last</option>
							  </select>
							  <select id="nthDate" name="nthDate" class="form-control day">
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 1 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="1">Day</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 2 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="2">Weekday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 3 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="3">Weekend</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 4 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="4">Monday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 5 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="5">Tuesday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 6 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="6">Wednesday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 7 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="7">Thursday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 8 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="8">Friday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 9 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="9">Saturday</option>
								<option <?php if(!empty($event[0]['nthDate']) && $event[0]['nthDate'] == 10 && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'){ echo 'selected="selected"';} ?> value="10">Sunday</option>
							  </select>
							  
							 <td> <label style="width:45px;">of every</label>
							  <input type="text" id="nthMonthCount" name="nthMonthCount" class="number form-control" onkeypress='return isNumberKey(event);' style="width:45px;" value="<?=!empty($event[0]['nthMonthCount']) && $event[0]['monthlyType'] == 2 && $event[0]['repeatType'] == '4'?$event[0]['nthMonthCount']:''?>" /></td>
							  </td>
							  	<td>month </td>
						  </tr>
                                                    <tr class="tr_repeat_yearly">
							<td colspan="3">
							  <label>
							 
								<input type="radio" name="yearlyType" class="cls_yearlyType" value="1" <?php if(!empty($event[0]['repeatType']) && $event[0]['repeatType'] == '5'){ echo 'checked="checked"';} else{ echo 'checked="checked"';}?>/>
								Every </label>
							  <input style="float:none;" type="text" id="everyYears" class="number form-control" onkeypress='return isNumberKey(event);' name="everyYears" value="<?=!empty($event[0]['everyYears']) && $event[0]['repeatType'] == '5'?$event[0]['everyYears']:''?>" />
							  Years </td>
						  </tr>
					</table>
							</td>
						</tr>
					</table>
				</td>
			  </tr>
              <tr class="tr_end_template">
			  	<td>
					<table width="100%">
						<tr>
							<td height="10" style="padding-top:20px;" colspan="6"> <b>End Date</b> </td>
						</tr>
						<tr>
							<td width="12%" valign="middle">
							
								<input type="radio" name="endTemplateType" class ="cls_endTemplateType" value="1" <?php if(isset($event[0]['endTemplateType'])){ if($event[0]['endTemplateType'] == 1 && $event[0]['ifRepeat'] == '1'){ echo 'checked="checked"';} }else{ echo 'checked="checked"';}?> />
								Never							</td>
							<td width="12%" valign="middle">
						
								<input type="radio" name="endTemplateType" class ="cls_endTemplateType" value="2" <?php if(!empty($event[0]['endTemplateType']) && $event[0]['endTemplateType'] == 2 && $event[0]['ifRepeat'] == '1'){ echo 'checked="checked"'; ?><?php } ?> />
								After							</td>
							<td width="30%" valign="middle"> <input type="text" id="endCounter" name="endCounter" class="number form-control inputbox" onkeypress='return isNumberKey(event);' value="<?=!empty($event[0]['endCounter'])?$event[0]['endCounter']:''?>" style="float:none;" />
						  Occurence </td>
							<td width="15%" valign="middle">
							
							 <input type="radio" name="endTemplateType" class ="cls_endTemplateType" value="3" <?php if(!empty($event[0]['endTemplateType']) && $event[0]['endTemplateType'] == 3 && $event[0]['ifRepeat'] == '1'){ echo 'checked="checked"'; ?><?php } ?> /> End on 
							 
													
						  </td>
					      <td width="9%" valign="middle"> <input class="form-control" type="text" id="endTemplateDate" name="endTemplateDate" readonly="" value="<?=(!empty($event[0]['endTemplateDate']) && $event[0]['endTemplateType'] == '3' && $event[0]['endTemplateDate'] != '0000-00-00')?date('m/d/Y',strtotime($event[0]['endTemplateDate'])):''?>" style="padding-left:2px; width:80px;"  /></td>
						    <td width="45%" valign="middle"><?php /*?><input type="text" id="endTemplateTime" name="endTemplateTime" readonly="" value="<?=(!empty($editRecord[0]['endTemplateTime']) && $editRecord[0]['endTemplateDate'] != '0000-00-00')?$editRecord[0]['endTemplateTime']:''?>" /><?php */?>
							  <input type="hidden" name="endTemplateTime_hid" id="endTemplateTime_hid" value="<?=isset($event)?$earr[1]:$earr1[1]?>" />
							  <select id="endTemplateTime" name="endTemplateTime" style="width:112px; min-height:33px !important;" class="select_box form-control inputbox">
								<option value="00:00">12:00 AM</option>
								<option value="00:15">12:15 AM</option>
								<option value="00:30">12:30 AM</option>
								<option value="00:45">12:45 AM</option>
								<option value="01:00">01:00 AM</option>
								<option value="01:15">01:15 AM</option>
								<option value="01:30">01:30 AM</option>
								<option value="01:45">01:45 AM</option>
								<option value="02:00">02:00 AM</option>
								<option value="02:15">02:15 AM</option>
								<option value="02:30">02:30 AM</option>
								<option value="02:45">02:45 AM</option>
								<option value="03:00">03:00 AM</option>
								<option value="03:15">03:15 AM</option>
								<option value="03:30">03:30 AM</option>
								<option value="03:45">03:45 AM</option>
								<option value="04:00">04:00 AM</option>
								<option value="04:15">04:15 AM</option>
								<option value="04:30">04:30 AM</option>
								<option value="04:45">04:45 AM</option>
								<option value="05:00">05:00 AM</option>
								<option value="05:15">05:15 AM</option>
								<option value="05:30">05:30 AM</option>
								<option value="05:45">05:45 AM</option>
								<option value="06:00">06:00 AM</option>
								<option value="06:15">06:15 AM</option>
								<option value="06:30">06:30 AM</option>
								<option value="06:45">06:45 AM</option>
								<option value="07:00">07:00 AM</option>
								<option value="07:15">07:15 AM</option>
								<option value="07:30">07:30 AM</option>
								<option value="07:45">07:45 AM</option>
								<option value="08:00">08:00 AM</option>
								<option value="08:15">08:15 AM</option>
								<option value="08:30">08:30 AM</option>
								<option value="08:45">08:45 AM</option>
								<option value="09:00">09:00 AM</option>
								<option value="09:15">09:15 AM</option>
								<option value="09:30">09:30 AM</option>
								<option value="09:45">09:45 AM</option>
								<option value="10:00">10:00 AM</option>
								<option value="10:15">10:15 AM</option>
								<option value="10:30">10:30 AM</option>
								<option value="10:45">10:45 AM</option>
								<option value="11:00">11:00 AM</option>
								<option value="11:15">11:15 AM</option>
								<option value="11:30">11:30 AM</option>
								<option value="11:45">11:45 AM</option>
								<option value="12:00">12:00 PM</option>
								<option value="12:15">12:15 PM</option>
								<option value="12:30">12:30 PM</option>
								<option value="12:45">12:45 PM</option>
								<option value="13:00">01:00 PM</option>
								<option value="13:15">01:15 PM</option>
								<option value="13:30">01:30 PM</option>
								<option value="13:45">01:45 PM</option>
								<option value="14:00">02:00 PM</option>
								<option value="14:15">02:15 PM</option>
								<option value="14:30">02:30 PM</option>
								<option value="14:45">02:45 PM</option>
								<option value="15:00">03:00 PM</option>
								<option value="15:15">03:15 PM</option>
								<option value="15:30">03:30 PM</option>
								<option value="15:45">03:45 PM</option>
								<option value="16:00">04:00 PM</option>
								<option value="16:15">04:15 PM</option>
								<option value="16:30">04:30 PM</option>
								<option value="16:45">04:45 PM</option>
								<option value="17:00">05:00 PM</option>
								<option value="17:15">05:15 PM</option>
								<option value="17:30">05:30 PM</option>
								<option value="17:45">05:45 PM</option>
								<option value="18:00">06:00 PM</option>
								<option value="18:15">06:15 PM</option>
								<option value="18:30">06:30 PM</option>
								<option value="18:45">06:45 PM</option>
								<option value="19:00">07:00 PM</option>
								<option value="19:15">07:15 PM</option>
								<option value="19:30">07:30 PM</option>
								<option value="19:45">07:45 PM</option>
								<option value="20:00">08:00 PM</option>
								<option value="20:15">08:15 PM</option>
								<option value="20:30">08:30 PM</option>
								<option value="20:45">08:45 PM</option>
								<option value="21:00">09:00 PM</option>
								<option value="21:15">09:15 PM</option>
								<option value="21:30">09:30 PM</option>
								<option value="21:45">09:45 PM</option>
								<option value="22:00">10:00 PM</option>
								<option value="22:15">10:15 PM</option>
								<option value="22:30">10:30 PM</option>
								<option value="22:45">10:45 PM</option>
								<option value="23:00">11:00 PM</option>
								<option value="23:15">11:15 PM</option>
								<option value="23:30">11:30 PM</option>
								<option value="23:45">11:45 PM</option>
						  </select></td>
						</tr>
					</table>
				</td>
			  </tr>
            </table>
          </td>
        </tr>
      </table>
    <?php
    if(!empty($event))
    {
        if(empty($event[0]['edit_flag']) && $event[0]['edit_flag'] != 1 && !empty($event[0]['ifRepeat']))
        {   
    ?>
            <table class='table_edittype'>
            <tr>
            <td> <input type="radio" value="1"  id="event_type" class="required" name="event_type" /> Only this event</td>
            <td> <input type="radio" value="2" id="event_type" class="required" name="event_type" />Following events </td>
            <td> <input type="radio" value="3" id="event_type" class="required" name="event_type" />All events</td>
            </tr>
            </table>
    <?php }
    } ?>
      <input type="hidden" id="timezone" name="timezone" value="" />
    </form>
  </div>
  <!-- Infocontainer End -->
  <div style="clear: both"></div>
  <div class="toolBotton btn-hit-654"> <a id="Savebtn" class="savebtn1" href="javascript:void(0);"> <span class="Save"  title="Save the calendar">Save</span> </a>
    <?php /*?> <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Delete" title="Cancel the calendar">Cancel</span>
        </a>
        <?php } ?><?php */?>
    <a id="Closebtn" class="closebtn" href="javascript:void(0);"> <span class="Close" title="Close the window">Close</span> </a> </div>
  <!-- toolbooton End --> 
</div>
<!-- Mainfrm1 End -->
<?php
  }
	?>
</body>
</html>
<script language="javascript" type="text/javascript">
	$(document).ready(function()
	{
		var stparttime_hid = $("#stparttime_hid").val();
		if(stparttime_hid!='00:00')
		{
                    $("#stparttime").val(stparttime_hid);
		}
		else
		{
			
			var options = document.getElementById('stparttime').options;
                        <?php if(!empty($event)) { ?>
                            for (var i = 0, len = options.length; i < len; ++i) {
                                    if (options[i].value == '<?=date('H:i',strtotime($event[0]['start_time']))?>') {
                                            document.getElementById('stparttime').selectedIndex = i;
                                            break;
                                    }
                            }
                        <?php } ?>
		}
		
		var etparttime_hid = $("#etparttime_hid").val();
		if(etparttime_hid!='00:00')
		{
			$("#etparttime").val(etparttime_hid);
		}
		else
		{
			var options = document.getElementById('etparttime').options;
                        <?php if(!empty($event)) { ?>
                            for (var i = 0, len = options.length; i < len; ++i) {
                                    if (options[i].value == '<?=date('H:i',strtotime($event[0]['end_time']))?>') {
                                            document.getElementById('etparttime').selectedIndex = i;
                                            break;
                                    }
                            }
                        <?php } ?>
		}
		var endTemplateTime_hid = $("#endTemplateTime_hid").val();
		if(endTemplateTime_hid!='00:00')
		{
			$("#endTemplateTime").val(endTemplateTime_hid);
		}
		else
		{
			var options = document.getElementById('endTemplateTime').options;
                        <?php if(!empty($event)) { ?>
                            for (var i = 0, len = options.length; i < len; ++i) {
                                    if (options[i].value == '<?=date('H:i',strtotime($event[0]['endTemplateTime']))?>') {
                                            document.getElementById('endTemplateTime').selectedIndex = i;
                                            break;
                                    }
                            }
                        <?php } ?>
		}
                
                /*var dailytype = $('input[name=dailyType]:checked', '#fmEdit').val();
                $('.cls_dailyType').trigger( "click" );*/
                <?php 
                if(!empty($event)) { ?>
                    var ifrepeat = $('input[name=ifRepeat]:checked', '#fmEdit').val();
                    var repeatval = $('input[name=repeatType]:checked', '#fmEdit').val();
                    var dailytype = $('input[name=dailyType]:checked', '#fmEdit').val();
                    var monthlytype = $('input[name=monthlyType]:checked', '#fmEdit').val();
                    var yearlytype = $('input[name=yearlyType]:checked', '#fmEdit').val();
                    var endtemplatetype = $('input[name=endTemplateType]:checked', '#fmEdit').val();
                    
                    if(ifrepeat == 1)
                    {
                        if(repeatval == 2) //Daily
                        {
                            if(dailytype == 1) {
                                $("#everyDays").addClass('required');
                            }
                        }
                        else if(repeatval == 3) //Weekly
                        {
                            $("#everyWeeks").addClass('required');
                        }
                        else if(repeatval == 4) //Monthly
                        {
                            if(monthlytype == 1)
                            {
                                $('#monthDate').addClass('required');
                                $('#monthCount').addClass('required');
                            }
                            else if(monthlytype == 2) {
                                $('#nthMonthCount').addClass('required');
                            }
                        }
                        else if(repeatval == 5) //Daily
                        {
                            $("#everyYears").addClass('required');
                        }
                        
                        if(endtemplatetype == 2) {
                            $('#endCounter').addClass('required');
                        }
                        else if(endtemplatetype == 3) {
                            $('#endTemplateDate').addClass('required')
                        }
                    }
                
        <?php } ?>
	});
</script>
<script type="text/javascript">
        var dailytype = 0;
        var monthlytype = 0;
	$('.cls_ifRepeat').die('click').live('click', function() { 
		remove_validatorclass();
		var repeatval = $('input[name=ifRepeat]:checked', '#fmEdit').val();
		var dailytype = $('input[name=dailyType]:checked', '#fmEdit').val();
                var yearlytype = $('input[name=yearlyType]:checked', '#fmEdit').val();
		if(repeatval == 1)
		{
			$(".tr_repeattype").show();
			//$(".tr_repeat_hourly").show();
			
			$(".cls_repeatType:radio[value=1]").attr('checked',true);
			
			$(".tr_repeat_daily").show();
			$(".tr_repeat_weekly").hide();
			$(".tr_repeat_monthly").hide();
                        $(".tr_repeat_yearly").hide();
			$(".tr_end_template").show();
                        $(".table_edittype").show();
                        $('#event_type').addClass('required');
                        if(dailytype == 1)
                            $("#everyDays").addClass('required');
		}
		else
		{
			$(".tr_repeattype").hide();
			$(".tr_repeat_hourly").hide();
			$(".tr_repeat_daily").hide();
			$(".tr_repeat_weekly").hide();
			$(".tr_repeat_monthly").hide();
                        $(".tr_repeat_yearly").hide();
			$(".tr_end_template").hide();
                        $(".table_edittype").hide();
                        $("#event_type").removeClass('required');
                        $("#everyDays").removeClass('required');
                        $("#everyYears").removeClass('required');
		}
		
	});
	$('.cls_repeatType').die( 'click').live('click', function() { 
            remove_validatorclass();
            var repeatval = $('input[name=repeatType]:checked', '#fmEdit').val();
            var dailytype = $('input[name=dailyType]:checked', '#fmEdit').val();
            var yearlytype = $('input[name=yearlyType]:checked', '#fmEdit').val();
            var monthlytype = $('input[name=monthlyType]:checked', '#fmEdit').val();
            <?php /*?>if(repeatval == 1)
            {
                $(".tr_repeat_hourly").show();
                $(".tr_repeat_daily").hide();
                $(".tr_repeat_weekly").hide();
                $(".tr_repeat_monthly").hide();
                $(".tr_end_template").show();
            }
            else <?php */?>
            if(repeatval == 2) /*Daily*/
            {
                $(".tr_repeat_hourly").hide();
                $(".tr_repeat_daily").show();
                $(".tr_repeat_weekly").hide();
                $(".tr_repeat_monthly").hide();
                $(".tr_repeat_yearly").hide();
                $(".tr_end_template").show();
                $('input[name=dailyType]:checked', '#fmEdit').val();
                if(dailytype == 1) {
                    $("#everyDays").addClass('required');
                }
                $("#everyWeeks").removeClass('required');
                $("#everyYears").removeClass('required');
                $('#monthDate').removeClass('required');
                $('#monthCount').removeClass('required');
                $('#nthMonthCount').removeClass('required');
            }
            else if(repeatval == 3) /*Weekly*/
            {
                $(".tr_repeat_hourly").hide();
                $(".tr_repeat_daily").hide();
                $(".tr_repeat_weekly").show();
                $(".tr_repeat_monthly").hide();
                $(".tr_repeat_yearly").hide();
                $(".tr_end_template").show();
                $("#everyDays").removeClass('required');
                $("#everyWeeks").addClass('required');
                $('#monthDate').removeClass('required');
                $('#monthCount').removeClass('required');
                $('#nthMonthCount').removeClass('required');
                $("#everyYears").removeClass('required');
            }
            else if(repeatval == 4) /*Monthly*/
            {
                $(".tr_repeat_hourly").hide();
                $(".tr_repeat_daily").hide();
                $(".tr_repeat_weekly").hide();
                $(".tr_repeat_monthly").show();
                $(".tr_repeat_yearly").hide();
                $(".tr_end_template").show();
                $("#everyDays").removeClass('required');
                $("#everyWeeks").removeClass('required');
                $("#everyYears").removeClass('required');
                if(monthlytype == 1)
                {
                    $('#monthDate').addClass('required');
                    $('#monthCount').addClass('required');
                    $('#nthMonthCount').removeClass('required');
                }
                else if(monthlytype == 2)
                {
                    $('#monthDate').removeClass('required');
                    $('#monthCount').removeClass('required');
                    $('#nthMonthCount').addClass('required');
                }
            }
            if(repeatval == 5) /*Yearly*/
            {
                $(".tr_repeat_hourly").hide();
                $(".tr_repeat_daily").hide();
                $(".tr_repeat_weekly").hide();
                $(".tr_repeat_monthly").hide();
                $(".tr_repeat_yearly").show();
                $(".tr_end_template").show();
                $('input[name=yearlyType]:checked', '#fmEdit').val();
                
                $("#everyYears").addClass('required');
                
                $("#everyDays").removeClass('required');
                $("#everyWeeks").removeClass('required');
                $('#monthDate').removeClass('required');
                $('#monthCount').removeClass('required');
                $('#nthMonthCount').removeClass('required');
            }
		
	});
        function isNumberKey(evt)
        {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if(charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;
        }
        $('.cls_dailyType').die( 'click').live('click', function() { 
            remove_validatorclass();
            var dailytype = $('input[name=dailyType]:checked', '#fmEdit').val();
            if(dailytype == 1) {
                $("#everyDays").addClass('required');
            } else {
                $("#everyDays").removeClass('required');
            }
        });
        
        $('.cls_monthlyType').die( 'click').live('click', function() { 
            remove_validatorclass();
            var monthlytype = $('input[name=monthlyType]:checked', '#fmEdit').val();
            if(monthlytype == 1) {
                $('#monthDate').addClass('required');
                $('#monthCount').addClass('required');
                $('#nthMonthCount').removeClass('required');
            } else if(monthlytype == 2) {
                $('#monthDate').removeClass('required');
                $('#monthCount').removeClass('required');
                $('#nthMonthCount').addClass('required');
            } else {
                $('#monthDate').removeClass('required');
                $('#monthCount').removeClass('required');
                $('#nthMonthCount').removeClass('required');
            }
        });
        
        $('.cls_endTemplateType').die( 'click').live('click', function() { 
            remove_validatorclass();
            var endtemplatetype = $('input[name=endTemplateType]:checked', '#fmEdit').val();
            if(endtemplatetype == 2) {
                $('#endCounter').addClass('required');
                $('#endTemplateDate').removeClass('required')
            }
            else if(endtemplatetype == 3) {
                $('#endCounter').removeClass('required');
                $('#endTemplateDate').addClass('required')
            } else {
                $('#endCounter').removeClass('required');
                $('#endTemplateDate').removeClass('required')
            }
                
        });
        function remove_validatorclass()
        {
            /*$("#fmEdit").data('validator').resetForm();*/
            $(".cusErrorPanel").html('');
            $(".cusErrorPanel").removeClass('cusErrorPanel');
            /*$("#everyDays").removeClass('cusErrorPanel');
            $("#everyWeeks").removeClass('cusErrorPanel');
            $('#monthDate').removeClass('cusErrorPanel');
            $('#monthCount').removeClass('cusErrorPanel');
            $('#nthMonthCount').removeClass('cusErrorPanel');
            $('#endCounter').removeClass('cusErrorPanel');
            $('#endTemplateDate').removeClass('cusErrorPanel')*/
        }
	
</script>
<script src="<?=$this->config->item('js_path')?>jquery.simple-color.js" type="text/javascript" ></script>
<script type="text/javascript">
$('.simple_color').simpleColor({
		cellWidth: 9,
		cellHeight: 9,
		callback: function(hex, element) {
			alert("color picked! " + hex + " for input #" + element.attr('class'));
		}
	});
	
</script>