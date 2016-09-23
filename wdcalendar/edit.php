<?php

include_once("../application/config/database.php");

include_once("../application/config/config.php");

$hostname =  $db['default']['hostname'];

$database =  $db['default']['database'];

$username =  $db['default']['username'];

$password =  $db['default']['password'];
include_once("php/dbconfig.php");
include_once("php/functions.php");
$db = new DBConnection();

$db->getConnection($hostname,$database,$username,$password);

$strtm_time = (isset($_REQUEST['start']))?date("H:i", strtotime($_REQUEST['start'])):"00:00";
$endtm_time = (isset($_REQUEST['end']))?date("H:i", strtotime($_REQUEST['end'])):"00:00";
$type =$_REQUEST['type'];
//print_r($_REQUEST);
$access =$_REQUEST['access'];
function getCalendarByRange($id){
  try{
		$sql = "SELECT * FROM appointment WHERE id=".$id;
		$handle = mysql_query($sql);
    $row = mysql_fetch_object($handle);
	}catch(Exception $e){
  }
  return $row;
}
if(isset($_GET["id"]) && $_GET["id"]!=0){
	$event = getCalendarByRange($_GET["id"]);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Calendar Details</title>
    <link href="css/main.css" rel="stylesheet" type="text/css" />
    <link href="css/dp.css" rel="stylesheet" />
    <link href="css/dropdown.css" rel="stylesheet" />
    <link href="css/colorselect.css" rel="stylesheet" />
    
    <script src="src/jquery.js" type="text/javascript"></script>
    <script src="src/Plugins/Common.js" type="text/javascript"></script>   
    <script src="src/Plugins/jquery.form.js" type="text/javascript"></script>
    <script src="src/Plugins/jquery.validate.js" type="text/javascript"></script>
    <script src="src/Plugins/datepicker_lang_US.js" type="text/javascript"></script>
    <script src="src/Plugins/jquery.datepicker.js" type="text/javascript"></script>
    <script src="src/Plugins/jquery.dropdown.js" type="text/javascript"></script>
    <script src="src/Plugins/jquery.colorselect.js" type="text/javascript"></script>
    <script src="src/jquery.blockUI.js" type="text/javascript"></script>
    <script src="src/Plugins/moment.js" type="text/javascript"></script>
     
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
						
            var DATA_FEED_URL = "php/datafeed.php";
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
							$.blockUI({ message: '<?='<img src="loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
							
							var userid = $('#userid').val();
							sttime=$("#stparttime").val();
							ettime=$("#etparttime").val();
							stdate=$("#stpartdate").val();
							type=$("#type").val();
							cur_date= Date();
							cur_date=moment(cur_date).format('MM/DD/YYYY');
							
							if(cur_date>stdate)
							{
								alert("Please select Date Greater than Equal to Today's Date");
								$.unblockUI();
							}
							else if(sttime>ettime)
							{
								alert("End Time should be Greater than Start Time");
								$.unblockUI();
							}
							else
							{
							
								var date = document.getElementById('stpartdate').value+ ' ' +document.getElementById('stparttime').value;
								var end_date = document.getElementById('stpartdate').value+ ' ' +document.getElementById('etparttime').value;
								<?php if(isset($_GET["id"]) && $_GET["id"]!=0){ ?>
									apptid = <?=$_GET['id']?>;
								<?php }else{ ?>
									apptid = "";
								<?php } ?>
								
								date=moment(date).format('YYYY-MM-DD HH:mm:ss');
								end_date=moment(end_date).format('YYYY-MM-DD HH:mm:ss');
								//alert(type);
								var file = "php/getuser.php?date="+date+"&end_date="+end_date+"&type="+type+"&apptid="+apptid;
								//alert(file);
								$.ajax({
									url:file,
									cache:false,
									success: function(response) 
									{
										if(response==0)
										{
											alert("Appointment already there on this Date and Time and type.");
											$.unblockUI();
										}
										else
										{
											$("#fmEdit").submit();
											setTimeout(function(){ $.unblockUI(); }, 5000);
										}
									}
								});
							}
							return false;
						});
						
						$("#Savebtn1").click(function(){
							$.blockUI({ message: '<?='<img src="loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
							
							var Agent=$("#Agent").val();
							
							if(Agent=="")
							{
								alert("Please Select Agent");
								$.unblockUI();
							}
							else
							{
								<?php if(isset($_GET["id"]) && $_GET["id"]!=0){ ?>
									apptid = <?=$_GET['id']?>;
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
							$.blockUI({ message: '<?='<img src="loading.gif" border="0" align="absmiddle"/>'?> Please Wait...'});
							if(confirm("Do you confirm to Cancel this Appointment")){
									var param = [{ "name": "calendarId", value: <?=(isset($_GET['id']))?$_GET['id']:1?>}];
									$.post(DATA_FEED_URL + "?method=remove",
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
					 $("#stpartdate,#etpartdate").datepicker({
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
		</script>
    
<style type="text/css">
.calpick{width:16px; height:16px; border:none; cursor:pointer; background:url("sample-css/cal.gif") no-repeat center 2px; margin-left:-20px; margin-right:3px; position:absolute; margin-top:2px;/* top:-5px;*/}
</style>
  </head>
<body>
	<?php
	if(isset($_GET['flag']) && $_GET['flag']==1)
	{
	?>
    <div id="mainfrm1">
      <div class="infocontainer">
      	<form action="php/datafeed.php?method=addagent<?=isset($event)?"&id=".$event->id:""?>" class="fform" id="fmEdit" method="post">
          <label>
            <span>Title</span>
						<?=$event->title?>
          </label>
          
          <label>
          	<span>Reason</span>
            <?=$event->reason?>
          </label>
          
          <label>
          	<span>Username</span>
          
			
              <?php
			  $uid=$event->userid;
            $sql = "SELECT * FROM property_owner_information WHERE PO_ID=".$uid;
		$handle = mysql_query($sql);
    $row = mysql_fetch_object($handle);?>
	<?=$row->fname?>
	
          </label>
          
          <label>
            <span>Time<b>*</b></span>
            <div class="date-hit-new654">
              <?php
              if(isset($event))
							{
								$sarr = explode(" ", php2JsTime(mySql2PhpTime($event->date)));
								$earr = explode(" ", php2JsTime(mySql2PhpTime($event->end_date)));
              }
              echo $sarr[0]."	AT ".date("g:i a", strtotime($sarr[1]))." To ". $earr[0]." AT ".date("g:i a", strtotime($earr[1]));
							?>
            </div>
          </label>
          
          <label>
            <span>Select Agent<b>*</b></span>
            <?php
            $qry="SELECT * FROM agent";
            $getagent = mysql_query($qry);
            ?>
            <select id="Agent" name="Agent" class="select-pcp987 required safe" style="width:245px;">
              <option value=""> Select Agent </option>
              <?php
              while($agt=mysql_fetch_array($getagent))
              {
              ?>
                <option value="<?=$agt['agentid']?>"><?=$agt['agentname']?></option>
              <?php
              }
              ?>
            </select>
          </label>
          
          <input type="hidden" id="timezone" name="timezone" value="" />
        </form>
      </div><!-- Infocontainer End -->
      <div style="clear: both"></div>
      <div class="toolBotton btn-hit-654">
        <a id="Savebtn1" class="imgbtn" href="javascript:void(0);">
          <span class="Save" title="Save the calendar">Save</span>
        </a>
        <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Delete" title="Cancel the calendar">Delete</span>
        </a>
        <?php } ?>
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Close" title="Close the window">Close</span>
        </a>
      </div><!-- toolbooton End -->
    </div><!-- Mainfrm1 End -->
  <?php
	}
	else
	{
	?>
    <div id="mainfrm1">
      <div class="infocontainer">
        <form action="php/datafeed.php?method=adddetails<?=isset($event)?"&id=".$event->id:""?>" class="fform" id="fmEdit" method="post">
          
          <input id="userid" name="userid" type="hidden" value="<?=isset($event)?$event->userid:"0"?>" />
          <input id="agentid" name="agentid" type="hidden" value="<?=isset($event)?$event->agentid:"0"?>" />
          <input id="appconf" name="appconf" type="hidden" value="<?=isset($event)?$event->accepted:"0"?>" />
          
          <label>
            <span>Title<b>*</b></span>
            <input MaxLength="100" class="required safe" style="width:280px;" id="title" name="title" type="text" value="<?=isset($event)?$event->title:""?>"/>
          </label>
          
          <label>
            <!--<span>Reason<b>*</b></span>-->
            <!--<div id="calendarcolor"></div>-->
            <input MaxLength="200" class="required safe" id="Subject" style="width:245px;" name="Subject" type="hidden" value="<?=isset($event)?$event->reason:"Open Appointment"?>" />
          </label>
          
          <label>
            <span>Time<b>*</b></span>
            <div class="date-hit-new654">
              <?php if(isset($event)){
                  $sarr = explode(" ", php2JsTime(mySql2PhpTime($event->date)));
                  $earr = explode(" ", php2JsTime(mySql2PhpTime($event->end_date)));
              }
              if(isset($_GET["start"]) && isset($_GET["end"]))
              {
                $sarr1 = explode(" ", php2JsTime(mySql2PhpTime($_GET["start"])));
                $earr1 = explode(" ", php2JsTime(mySql2PhpTime($_GET["end"])));
              }
              else
              {
                $sarr1[0]="";
                $sarr1[1]="";
                $earr1[0]="";
                $earr1[1]="";
              }
              ?>
              <input MaxLength="10" class="required date" id="stpartdate" name="stpartdate" style="padding-left:2px; width:90px;" type="text" value="<?=isset($event)?$sarr[0]:$sarr1[0]?>" readonly />
              <!--<input MaxLength="5" class="required time" id="stparttime" name="stparttime" type="text" style="width:40px;" value="<?=isset($event)?$sarr[1]:$sarr1[1]?>" readonly />-->
              <input type="hidden" name="stparttime_hid" id="stparttime_hid" value="<?=isset($event)?$sarr[1]:$sarr1[1]?>" />
              
              <select id="stparttime" name="stparttime" style="width:80px; height:20px !important;" class="select_box">
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
              
              To
              
              <input MaxLength="10" class="date" id="etpartdate1" name="etpartdate1" style="padding-left:2px; width:90px;" type="hidden" value="<?=isset($event)?$earr[0]:$earr1[0]?>" readonly />
              <!--<input MaxLength="50" class="required time" id="etparttime" name="etparttime" style="width:40px;" type="text" value="<?=isset($event)?$earr[1]:$earr1[1]?>" readonly />-->
              <input type="hidden" name="etparttime_hid" id="etparttime_hid" value="<?=isset($event)?$earr[1]:$earr1[1]?>" />
              
              <select id="etparttime" name="etparttime" style="width:80px; height:20px !important;" class="select_box">
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
          
          <label style="display:none;">
            <span>Email Reminder<b>*</b></span>
            <select id="erem" name="erem">
              <option value="1" <?php if(isset($event) && $event->email_reminder==1){ echo "selected"; } ?>>1</option>
              <option value="2" <?php if(isset($event) && $event->email_reminder==2){ echo "selected"; } ?>>2</option>
              <option value="5" <?php if(isset($event) && $event->email_reminder==5){ echo "selected"; } ?>>5</option>
              <option value="10" <?php if(isset($event) && $event->email_reminder==10){ echo "selected"; } ?>>10</option>
            </select> Days
          </label>
          <label>
          <?php 
		  		//$this->load->library('session');
		  		//$user_session_array_array = $this->session->userdata('user_session_array_admin');
				//$user_id = $user_session_array_array['user_id'];
				
				//$type=$user_session_array_array['type'];
				//session_start();
				//$_SESSION['foo'] = 'bar';
			  //@session_start();
			  //print_r($_SESSION['type']);exit;
			  
		  ?>
            <span>Type<b>*</b></span>
            <?php if($type == 'admin'){ ?>
            <select id="type" name="type">
              <option value="1" <?php if(isset($event) && $event->type==1){ echo "selected"; } ?>>Energy evaluation</option>
              <option value="2" <?php if(isset($event) && $event->type==2){ echo "selected"; } ?>>Energy assessment</option>
              <option value="3" <?php if(isset($event) && $event->type==3){ echo "selected"; } ?>>Online meeting</option>
              <option value="4" <?php if(isset($event) && $event->type==4){ echo "selected"; } ?>>Retrofit</option>
            </select>
            <?php } else { ?>
           <select id="type" name="type">
             <?php if($access == 2 || $access == 3){ ?>
              <option value="1" <?php if(isset($event) && $event->type==1){ echo "selected"; } ?>>Energy evaluation</option>
           	 <?php } if($access == 3){?>
              <option value="2" <?php if(isset($event) && $event->type==2){ echo "selected"; } ?>>Energy assessment</option>
              <?php } if($access == 2){ ?>
              <option value="3" <?php if(isset($event) && $event->type==3){ echo "selected"; } ?>>Online meeting</option>
              <?php } if($access == 1){ ?>
              <option value="4" <?php if(isset($event) && $event->type==4){ echo "selected"; } ?>>Retrofit</option>
              <?php } ?>
            </select>
            <?php } ?>
          </label>
          <label>
            <span>Notes:</span>
            <textarea cols="20" id="Description" class="textarea-hit-new654" name="Description" rows="2"><?=isset($event)?$event->notes:""?></textarea>
          </label>
          
          <input type="hidden" id="timezone" name="timezone" value="" />
        </form>
      </div><!-- Infocontainer End -->
      <div style="clear: both"></div>
      <div class="toolBotton btn-hit-654">
        <a id="Savebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Save"  title="Save the calendar">Save</span>
        </a>
        <?php if(isset($event)){ ?>
        <a id="Deletebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Delete" title="Cancel the calendar">Cancel</span>
        </a>
        <?php } ?>
        <a id="Closebtn" class="imgbtn" href="javascript:void(0);">
          <span class="Close" title="Close the window">Close</span>
        </a>
      </div><!-- toolbooton End -->
    </div><!-- Mainfrm1 End -->
  <?php
  }
	?>
</body>
</html>

<script language="javascript" type="text/javascript">
	$(document).ready(function()
	{
		/* Code Added by Swapnil for Start time and end time Start */
		var stparttime_hid = $("#stparttime_hid").val();
		if(stparttime_hid!='00:00')
		{
			$("#stparttime").val(stparttime_hid);
		}
		else
		{
			var options = document.getElementById('stparttime').options;
			for (var i = 0, len = options.length; i < len; ++i) {//alert(options[i].value); alert('<?=$strtm_time?>');
				if (options[i].value == '<?=$strtm_time?>') {
					document.getElementById('stparttime').selectedIndex = i;
					break;
				}
			}
		}
		
		var etparttime_hid = $("#etparttime_hid").val();
		if(etparttime_hid!='00:00')
		{
			$("#etparttime").val(etparttime_hid);
		}
		else
		{
			var options = document.getElementById('etparttime').options;
			for (var i = 0, len = options.length; i < len; ++i) {
				if (options[i].value == '<?=$endtm_time?>') {
					document.getElementById('etparttime').selectedIndex = i;
					break;
				}
			}
		}
		/* Code Added by Swapnil for Start time and end time Start */
	});
</script>