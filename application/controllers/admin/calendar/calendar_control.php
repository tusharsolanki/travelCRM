<?php 
/*
    @Description: property controller
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 22-05-2014

*/

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class calendar_control extends CI_Controller
{	
    function __construct()
    {
        parent::__construct();
        $this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
        $this->message_session = $this->session->userdata('message_session');
        check_admin_login();
        $this->load->model('calendar_model');
        $this->load->model('common_function_model');

        $this->obj = $this->calendar_model;
        $this->viewName = $this->router->uri->segments[2];
    }
	

    /*
    @Description: Function for Get All calendar List
    @Author: Niral Patel
    @Input: - Search value or null
    @Output: - all calendar list
    @Date: 22-05-2014
    */
    public function index()
    {	
		//check user right
		check_rights('calendar');
                
        $data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template',$data);
    }
	/*
    @Description: Function for to load view page for google login
    @Author: Niral Patel
    @Input: - 
    @Output: -
    @Date: 22-08-2014
    */
    function google_login()
    {
        //$data['main_content'] = "admin/".$this->viewName."/google_login";
        $cdata['sucess']='';
        $this->load->view("admin/".$this->viewName."/google_login",$data);	
    }
    public function google_connection()
    {
        ///// Goolge Calendar API//////
       // error_reporting(E_ALL);
        //$this->load->library('gcalendar/Google_Client');
        //$this->load->library('gcalendar/contrib/Google_CalendarService');
        require_once 'gcalendar/Google_Client.php';
        require_once 'gcalendar/contrib/Google_CalendarService.php';
        session_start();
        $client = new Google_Client();
        $client->setApplicationName("Google Calendar Application");

        // Visit https://code.google.com/apis/console?api=calendar to generate your
        // client id, client secret, and to register your redirect uri.
        // $client->setClientId('insert_your_oauth2_client_id');
        // $client->setClientSecret('insert_your_oauth2_client_secret');
        // $client->setRedirectUri('insert_your_oauth2_redirect_uri');
        // $client->setDeveloperKey('insert_your_developer_key');
        $cal = new Google_CalendarService($client);
        
        if (isset($_GET['logout'])) {
          unset($_SESSION['token']);
        }

        if (isset($_GET['code'])) {
            $client->authenticate($_GET['code']);
            $_SESSION['token'] = $client->getAccessToken();
            header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        }

        if (isset($_SESSION['token'])) {
            $client->setAccessToken($_SESSION['token']);
        }

        if ($client->getAccessToken()) {
            //ini_set('display_errors', '1');
            
            $calList = $cal->calendarList->listCalendarList();
            //pr($calList);exit;
            $calendar_id=$calList['items'][0]['id'];
             //pr($calendar_id);exit;
            
            ////////// Event Sync from CRM To Google Calendar Sanjay Moghariya 22-04-2015/////////////
            /*$table = 'calendar_master cm';
            //$join_table = array('calendar_repeat_trans as crt'=>'crt.calendar_id = cm.id');
            $join_table = array();
            $fields = array('cm.*');
            $match = array('cm.created_by'=>$this->admin_session['id'],'cm.googleEventId'=> '');
            $calendar_data = $this->calendar_model->getmultiple_tables_records($table,$fields,$join_table,'left','',$match,'=');
            //echo $this->db->last_query();
            //pr($calendar_data);exit;
            
            if(!empty($calendar_data))
            {
                foreach($calendar_data as $row)
                {
                    //if($row[''])
                }
            }*/
            
            
            ////////// End Event Sync from CRM To Google Calendar Sanjay Moghariya 22-04-2015/////////////
            
            /* Nishit add event to calendar testing */
            
            /*$event = new Google_Event();
            $event->setSummary('Appointment 222 by Nishit');
            $event->setLocation('Ahmedabad');
            $start = new Google_EventDateTime();
            $start->setTimeZone('America/New_York');
            $start->setDateTime('2015-04-01T15:00:00.000-07:00');
            $event->setStart($start);
            $end = new Google_EventDateTime();
            $end->setTimeZone('America/New_York');
            $end->setDateTime('2015-04-01T17:25:00.000-07:00');
            $event->setEnd($end);
            $event->setRecurrence(array('RRULE:FREQ=WEEKLY;UNTIL=20150701T170000Z'));
            $attendee1 = new Google_EventAttendee();
            $attendee1->setEmail('sanjay.chabhadiya@tops-int.com');
            // ...
            $attendees = array($attendee1,
                               // ...
                              );
            $event->attendees = $attendees;
            $createdEvent = $cal->events->insert('primary', $event);
            pr($createdEvent);
            echo $createdEvent['id'];
            
            exit;*/
            
            /* END (Nishit add event to calendar testing) */
            
            
            
            if(!empty($calList['items']))
            {
                //$eventFeed = $cal->events->listEvents($calendar_id);
                // For all calendar created
                $eventFeed = array(); 
                foreach($calList['items'] as $calendar_id)
                {
                    $eventFeed[] = $cal->events->listEvents($calendar_id['id']);
                }
                $this->outputCalendar($eventFeed);
            }
            /*$calendarListEntry = $cal->calendarList->get('niral.patel@tops-int.com');
            print $calendarListEntry->getSummary();*/
            $_SESSION['token'] = $client->getAccessToken();
        } else {
            $authUrl = $client->createAuthUrl();
            print "<a class='login' href='$authUrl'>Connect to Google!</a>";
        }
    }
    
    /*
        @Description: Function for return timezone name from offset
        @Author     : Sanjay Moghariya
        @Input      :  Offset
        @Output     : Return Timezone name
        @Date       : 17-04-2015
    */
   function timezoneList($time_diff)
   {
        $timezoneIdentifiers = DateTimeZone::listIdentifiers();
        $utcTime = new DateTime('now', new DateTimeZone('UTC'));

        $tempTimezones = array();
        foreach ($timezoneIdentifiers as $timezoneIdentifier) {
            $currentTimezone = new DateTimeZone($timezoneIdentifier);

            $tempTimezones[] = array(
                'offset' => (int)$currentTimezone->getOffset($utcTime),
                'identifier' => $timezoneIdentifier
            );
        }

       // Sort the array by offset,identifier ascending
       usort($tempTimezones, function($a, $b) {
            return ($a['offset'] == $b['offset'])
                ? strcmp($a['identifier'], $b['identifier'])
                : $a['offset'] - $b['offset'];
       });

        $timezoneList = array();
        foreach ($tempTimezones as $tz) {
            $sign = ($tz['offset'] > 0) ? '+' : '-';
            $offset = gmdate('H:i', abs($tz['offset']));

            $new_dt = $sign.$offset;
            if($new_dt == $time_diff) {
                return $tz['identifier'];
            }
                   
            $timezoneList[$tz['identifier']] = '(UTC ' . $sign . $offset . ') ' .$tz['identifier'];
        }
       //return $timezoneList;
    }

    /*
        @Description: Function for sync google calendar events into CRM
        @Author     : 
        @Input      : 
        @Output     : 
        @Date       : 17-04-2015
    */
    function outputCalendar($eventFeed)
    {
        //pr($eventFeed);exit;
        ?>
        <style>
            body{ position:relative;}
        </style>
        <div style="width:100%; text-align:center; position:absolute; top:50%; margin:0 auto;" id="ajaxloader"><img src="<?=base_url('images/loading.gif')?>" /><br />Please wait... It will take some time to import Google calendar events...</div>

        <?php
        //$i=0;
        //pr($eventFeed);exit;
        $calendar_timezone = '';
        foreach($eventFeed as $caleve)
        {
            $calendar_timezone = $caleve['timeZone'];
            foreach ($caleve['items'] as $event) 
            //foreach ($eventFeed['items'] as $event) 
            {
                $data = array();
                $event_data = array();
                //pr($event['recurrence'][0]);
                //pr($event);exit;
                //////// NEW 14-02-2015 (USING GOOGLE API) SANAJY MOGHARIYA///////
                if(!empty($event['summary'])) {
                    $data['event_title']=$event['summary'];
                }
                if(!empty($event['description'])) {
                    $data['event_notes']=$event['description']; 
                }
                $data['googleEventId'] = $event['id'];

                $event_data['event_title']=!empty($event['summary'])?$event['summary']:'';
                $event_data['event_notes'] = !empty($event['description'])?$event['description']:'';

                $match = array('googleEventId'=>$event['id']);
                $field=array('id');
                $record = $this->obj->select_records($field,$match,'','=');
                if(!empty($record) && count($record) > 0)
                {
                    $this->obj->delete_record($record[0]['id']);
                    $this->obj->delete_calendar_record($record[0]['id']);
                }
                //pr($data);
                $eve_timezone = '';
                //$data['recurrencedata'] = '';
                if(!empty($event['start']['dateTime']))
                {
                    $eve_timezone = !empty($event['start']['timeZone'])?$event['start']['timeZone']:'';
                    $mydata['start_time'] = (string) $event['start']['dateTime'];
                    $mydata['end_time'] = (string) $event['end']['dateTime'];
                } else {
                    $mydata['start_time'] = (string) $event['start']['date'];
                    $mydata['end_time'] = (string) $event['end']['date'];
                }
               
                $start_time = explode('T',$mydata['start_time']);
                $start_date = date('Y-m-d',strtotime($start_time[0]));
                $monthDate = date('d',strtotime($start_time[0]));
                $dttm_flag = 0;
                if(!empty($start_time[1])) {
                    //$tm = explode('+',$start_time[1]);
                    //$data['start_time'] = $tm[0];
                    $dttm_flag = 1;
                    $data['start_time'] = substr($start_time[1], 0,8);
                }
                else {
                    $data['start_time'] = "00:00:00";
                }

                $end_time = explode('T',$mydata['end_time']);

                if(!empty($end_time[1])) {
                    $dttm_flag = 1;
                    $end_date = date('Y-m-d',strtotime($end_time[0]));
                    //$tm = explode('+',$end_time[1]);
                    //$data['end_time'] = $tm[0];
                    $data['end_time'] = substr($end_time[1], 0,8);
                }
                else {
                    $end_date = date('Y-m-d', strtotime($end_time[0]. ' -1 day'));
                    $data['end_time'] = "00:00:00";
                }

                $event_data['event_start_time'] = $data['start_time'];
                $event_data['event_end_time'] = $data['end_time'];

                $data['start_date'] = $start_date;
                $data['end_date']   = $end_date;
                
                ///////////Date conversion from one timezone to another timezone Sanjay Moghariya. 16-04-2015 ////////////////
                
                $sdate = date('Y-m-d H:i:s',strtotime($start_date.' '.$data['start_time']));
                $edate = date('Y-m-d H:i:s',strtotime($end_date.' '.$data['end_time']));
                
                // If timezone is selected while creating google calendar event
                if(!empty($eve_timezone))
                {
                    $admin_time_zone = !empty($this->admin_session['date_timezone'])?$this->admin_session['date_timezone']:$this->config->item('default_timezone');
                    
                    $fdate = new DateTime($sdate, new DateTimeZone($eve_timezone)); //This is  Server Time Zone
                    $fdate->setTimezone(new DateTimeZone($admin_time_zone)); //This is  system time zone(client)
                    $new_start_date = $fdate->format('Y-m-d H:i:s');
                    
                    $start_date = date('Y-m-d',strtotime($new_start_date));
                    $data['start_date'] = $start_date;
                    $data['start_time'] = date('H:i:s',strtotime($new_start_date));
                    
                    $fdate = new DateTime($edate, new DateTimeZone($eve_timezone)); //This is  Server Time Zone
                    $fdate->setTimezone(new DateTimeZone($admin_time_zone)); //This is  system time zone(client)
                    $new_end_date = $fdate->format('Y-m-d H:i:s');
                    
                    $end_date = date('Y-m-d',strtotime($new_end_date));
                    $data['end_date'] = $end_date;
                    $data['end_time'] = date('H:i:s',strtotime($new_end_date));
                    
                    $event_data['event_start_time'] = $data['start_time'];
                    $event_data['event_end_time'] = $data['end_time'];
                } 
                else if(!empty($dttm_flag)) // If timezone is not selected while creating google calendar event, fetch google calendar timezone from datetime offset
                {
                    if(!empty($start_time[1]))
                    {
                        $calendar_timezone = !empty($calendar_timezone)?$calendar_timezone:$this->config->item('default_timezone');
                        
                        $admin_time_zone = !empty($this->admin_session['date_timezone'])?$this->admin_session['date_timezone']:$this->config->item('default_timezone');

                        $fdate = new DateTime($sdate, new DateTimeZone($calendar_timezone)); //This is  Server Time Zone
                        $fdate->setTimezone(new DateTimeZone($admin_time_zone)); //This is  system time zone(client)
                        $new_start_date = $fdate->format('Y-m-d H:i:s');

                        $start_date = date('Y-m-d',strtotime($new_start_date));
                        $data['start_date'] = $start_date;
                        $data['start_time'] = date('H:i:s',strtotime($new_start_date));

                        $fdate = new DateTime($edate, new DateTimeZone($calendar_timezone)); //This is  Server Time Zone
                        $fdate->setTimezone(new DateTimeZone($admin_time_zone)); //This is  system time zone(client)
                        $new_end_date = $fdate->format('Y-m-d H:i:s');

                        $end_date = date('Y-m-d',strtotime($new_end_date));
                        $data['end_date'] = $end_date;
                        $data['end_time'] = date('H:i:s',strtotime($new_end_date));

                        $event_data['event_start_time'] = $data['start_time'];
                        $event_data['event_end_time'] = $data['end_time'];
                    }
                }
                ///////////End Date conversion from one timezone to another timezone ////////////////
                
                $data['created_by'] = $this->admin_session['id'];
                $data['created_date'] = date('Y-m-d H:i:s');		
                $data['status']='1';
                /*if($i == 0) {
                    $i++;
                    continue;
                }
                pr($data);*/
                $instart=date_create(date('Y-m-d', strtotime($data['start_date'])));
                $inend=date_create(date('Y-m-d',strtotime($data['end_date'])));
                $indaydiff = date_diff($instart, $inend);
                $total_inday = $indaydiff->format("%a");

                if(!empty($event['recurrence'][0]))
                {
                    $data['ifRepeat'] = 1;
                    $everyDays = 1;
                    $endCounter = '';
                    $endTemplateDate = '';
                    $rrule = str_replace("RRULE:", "",$event['recurrence'][0]);
                    $repeat_rules = explode(';',$rrule);
                    $everyWeekonMon = 0; $everyWeekonTue = 0; $everyWeekonWed = 0; $everyWeekonThu = 0; $everyWeekonFri = 0; $everyWeekonSat = 0; $everyWeekonSun;
                    $nthDate = '';$nthDay = '';
                    if(!empty($repeat_rules))
                    {
                        foreach($repeat_rules as $row)
                        {
                            $dt = explode('=',$row);
                            //pr($dt);
                            if(!empty($dt) && $dt[0] == 'FREQ')
                            {
                                if($dt[1] == 'DAILY') {
                                        $repeatType = '2';
                                        $data['dailyType'] = '1';
                                }
                                if($dt[1] == 'WEEKLY')
                                        $repeatType = '3';
                                if($dt[1] == 'MONTHLY') {
                                        $repeatType = '4';
                                        $monthlyType = '1';
                                }
                                if($dt[1] == 'YEARLY')
                                        $repeatType = '5';
                            }
                            else if(!empty($dt) && $dt[0] == 'INTERVAL')
                            {
                                $everyDays = $dt[1]; // Repeat after every n days interval
                            }
                            else if(!empty($dt) && $dt[0] == 'BYMONTHDAY')
                            {
                                $monthDate = $dt[1]; // Repeat on n day (Monthly))
                                $monthlyType = '1';
                            }
                            else if(!empty($dt) && $dt[0] == 'COUNT')
                            {
                                $endCounter = $dt[1]; // End date: After n occurance
                            }
                            else if(!empty($dt) && $dt[0] == 'UNTIL')
                            {
                                //pr($dt[0]);exit;
                                $edt = explode('T',$dt[1]);
                                $endTemplateDate = $edt[0]; // End date: End on n date
                            }
                            else if(!empty($dt) && $dt[0] == 'BYDAY') // Weekly / Monthly
                            {
                                $everyWeekonMon = 0; $everyWeekonTue = 0; $everyWeekonWed = 0; $everyWeekonThu = 0; $everyWeekonFri = 0; $everyWeekonSat = 0; $everyWeekonSun;

                                //if (strpos($dt[1],',') !== false) 
                                if($repeatType == '3')
                                {
                                    $weekdays = explode(',',$dt[1]);
                                    if(!empty($weekdays))
                                    {
                                        foreach($weekdays as $row) {
                                            if($row == 'SU')
                                                $everyWeekonSun = 1;
                                            if($row == 'MO')
                                                $everyWeekonMon = 1;
                                            if($row == 'TU')
                                                $everyWeekonTue = 1;
                                            if($row == 'WE')
                                                $everyWeekonWed = 1;
                                            if($row == 'TH')
                                                $everyWeekonThu = 1;
                                            if($row == 'FR')
                                                $everyWeekonFri = 1;
                                            if($row == 'SA')
                                                $everyWeekonSat = 1;
                                            $data['everyWeekonMon'] = !empty($everyWeekonMon)? $everyWeekonMon:0;
                                            $data['everyWeekonTue'] = !empty($everyWeekonTue)? $everyWeekonTue:0;
                                            $data['everyWeekonWed'] = !empty($everyWeekonWed)? $everyWeekonWed:0;
                                            $data['everyWeekonThu'] = !empty($everyWeekonThu)? $everyWeekonThu:0;
                                            $data['everyWeekonFri'] = !empty($everyWeekonFri)? $everyWeekonFri:0;
                                            $data['everyWeekonSat'] = !empty($everyWeekonSat)? $everyWeekonSat:0;
                                            $data['everyWeekonSun'] = !empty($everyWeekonSun)? $everyWeekonSun:0;
                                        }
                                    }
                                } else {
                                    if(strlen($dt[1]) == 4)
                                    {
                                        $nthDays = substr($dt[1],0,2);
                                        $nthDates = substr($dt[1],2);
                                    } else {
                                       $nthDays = substr($dt[1],0,1);
                                       $nthDates = substr($dt[1],1);
                                    }
                                    $nthDate = '';$nthDay = '';

                                    if(!empty($nthDates))
                                    {
                                        if($nthDates == 'MO')
                                            $nthDate = '4';
                                        if($nthDates == 'TU')
                                            $nthDate = '5';
                                        if($nthDates == 'WE')
                                            $nthDate = '6';
                                        if($nthDates == 'TH')
                                            $nthDate = '7';
                                        if($nthDates == 'FR')
                                            $nthDate = '8';
                                        if($nthDates == 'SA')
                                            $nthDate = '9';
                                        if($nthDates == 'SU')
                                            $nthDate = '10';
                                    }
                                    if(!empty($nthDays) && $nthDays == '-1')
                                        $nthDay = '5';
                                    else
                                        $nthDay = $nthDays;
                                    $monthlyType = '2';
                                }
                            }
                        }

                        $data['repeatType'] = $repeatType;
                        $data['endTemplateDate'] = '0000-00-00';
                        $data['endCounter'] = '0';

                        // Recurrence type = Daily Sanjay M.
                        if($repeatType == '2')
                        {	
                            $data['everyDays'] = $everyDays;
                            if(empty($endCounter) && empty($endTemplateDate)) //Never
                            {
                                $data['endTemplateType'] = '1';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_never($start_date,$total_inday,$everyDays,$event_data);
                            }
                            else if(!empty($endCounter)) // After n occurance
                            {
                                $data['endTemplateType'] = '2';
                                $data['endCounter'] = $endCounter;
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_after_n_occurance($start_date, $endCounter, $total_inday, $everyDays, $event_data);
                            }
                            else if(!empty($endTemplateDate)) // End on random date
                            {
                                $data['endTemplateType'] = '3';
                                $data['endTemplateDate'] = $endTemplateDate;
                                $data['endTemplateTime'] = '00:00:00';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_random_date($start_date, $endTemplateDate, $total_inday, $everyDays, $event_data);
                            }
                        }
                        // Recurrence Type = 'Weekly' Sanjay Moghariya 15-10-2014
                        else if($repeatType == '3')
                        {
                            $everyWeeks = $everyDays;
                            $data['everyWeeks'] = $everyDays;
                            $original_start_date = strtotime($start_date);
                            // If 'Never' selected in End Date (repeatType: Weekly)
                            if(empty($endCounter) && empty($endTemplateDate)) //Never
                            {
                                $data['endTemplateType'] = '1';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_never_weekly($start_date,$original_start_date,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                            // If 'After' selected in End Date (repeatType: Weekly)
                            else if(!empty($endCounter))
                            {
                                $data['endTemplateType'] = '2';
                                $data['endCounter'] = $endCounter;
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_after_n_occurance_weekly($start_date,$original_start_date,$endCounter,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                            // If 'End on' selected in End Date (repeatType: Weekly)
                            else if(!empty($endTemplateDate))
                            {
                                $data['endTemplateType'] = '3';
                                $data['endTemplateDate'] = $endTemplateDate;
                                $data['endTemplateTime'] = '00:00:00';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_random_date_weekly($start_date,$original_start_date,$everyWeeks,$endTemplateDate,$total_inday,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                        }
                        // Recurrence Type = 'Monthly' Sanjay Moghariya 15-10-2014
                        else if($repeatType == '4')
                        {
                            $monthCount = $everyDays;
                            $nthMonthCount = $everyDays;
                            $original_start_date = strtotime($start_date);
                            $data['monthlyType'] = $monthlyType;
                            ////   For Recurrence -> Monthly -> monthDate and MonthCount Selected ////
                            if($monthlyType == '1')
                            {
                                $data['monthCount'] = $everyDays;
                                if(!empty($monthDate) && !empty($monthCount))
                                {
                                    $data['monthDate'] = $monthDate;
                                    // If 'Never' selected in End Date (repeatType: Monthly)
                                    if(empty($endCounter) && empty($endTemplateDate)) //Never
                                    {
                                        $data['endTemplateType'] = '1';
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        $this->endon_never_monthly_day($start_date,$original_start_date,$total_inday,$event_data,$monthDate,$monthCount);
                                    }
                                    // If 'After' selected in End Date (repeatType: Monthly)
                                    else if(!empty($endCounter))
                                    {
                                        $data['endTemplateType'] = '2';
                                        $data['endCounter'] = $endCounter;
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        $this->endon_after_n_occurance_monthly_day($start_date,$original_start_date,$endCounter,$total_inday,$event_data,$monthDate,$monthCount);
                                    }
                                    // If 'End on' selected in End Date (repeatType: Monthly)
                                    else if(!empty($endTemplateDate))
                                    {
                                        $data['endTemplateType'] = '3';
                                        $data['endTemplateDate'] = $endTemplateDate;
                                        $data['endTemplateTime'] = '00:00:00';
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        $this->endon_random_date_monthly_day($start_date,$original_start_date,$endTemplateDate,$total_inday,$event_data,$monthDate,$monthCount);
                                    }
                                }
                            }
                            //
                            //   For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                            //
                            if($monthlyType == '2')
                            {
                                $data['nthMonthCount'] = $everyDays;
                                $data['nthDay'] = $nthDay;
                                $data['nthDate'] = $nthDate;
                                // If nthDay, nthDate and nthMonthCount Selected
                                if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                {
                                    // If 'Never' selected in End Date (repeatType: Monthly)
                                    if(empty($endCounter) && empty($endTemplateDate)) //Never
                                    {
                                        $data['endTemplateType'] = '1';
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Last
                                    } // End Monthly : 'Never'

                                    // If 'After' selected in End Date (repeatType: Monthly)
                                    else if(!empty($endCounter))
                                    {
                                        $data['endTemplateType'] = '2';
                                        $data['endCounter'] = $endCounter;
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Last
                                    }
                                    // If 'End on' selected in End Date (repeatType: Monthly)
                                    else if(!empty($endTemplateDate))
                                    {
                                        $data['endTemplateType'] = '3';
                                        $data['endTemplateDate'] = $endTemplateDate;
                                        $data['endTemplateTime'] = '00:00:00';
                                        $insert_id = $this->obj->insert_record($data);
                                        $event_data['calendar_id'] = $insert_id;
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Last
                                    }
                                }
                            }

                            ////***
                            ///   End For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                           //// ***
                        }
                        // Recurrence Type = 'Yearly' Sanjay Moghariya 16-10-2014
                        if($repeatType == '5')
                        {	
                            $data['everyYears'] = $everyDays;
                            if(empty($endCounter) && empty($endTemplateDate)) //Never
                            {
                                $data['endTemplateType'] = '1';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_never_yearly($start_date,$total_inday,$everyDays,$event_data);
                            }
                            else if(!empty($endCounter)) // After n occurance
                            {
                                $data['endTemplateType'] = '2';
                                $data['endCounter'] = $endCounter;
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_after_n_occurance_yearly($start_date, $endCounter, $total_inday, $everyDays, $event_data);
                            }
                            else if(!empty($endTemplateDate)) // End on random date
                            {
                                $data['endTemplateType'] = '3';
                                $data['endTemplateDate'] = $endTemplateDate;
                                $data['endTemplateTime'] = '00:00:00';
                                $insert_id = $this->obj->insert_record($data);
                                $event_data['calendar_id'] = $insert_id;
                                $this->endon_random_date_yearly($start_date, $endTemplateDate, $total_inday, $everyDays, $event_data);
                            }
                        }
                    }
                        //pr($repeat_rules);
                }
                else
                {
                    if(!empty($data['start_date']))
                    {
                        $event_data['event_start_date']= $data['start_date'];
                        $event_data['event_start_time'] = $data['start_time'];
                        $event_data['event_end_date'] = $data['end_date'];
                        $event_data['event_end_time'] = $data['end_time'];
                    }

                    if($data['start_time'] == '00:00:00' || empty($data['start_time']))
                        $data['is_all_day'] = '1';

                    $insert_id=$this->obj->insert_record($data);
                    $event_data['calendar_id']=$insert_id;
                    $this->obj->insert_calendar_tran($event_data);
                }

                //exit;
                //////// END NEW 14-02-2015 (GOOGLE API) SANAJY MOGHARIYA///////
            }	
        }
        $cdata['message']='Events import successfully.';
        //exit;
        ?>
        <script type="text/javascript">window.location.href = "<?=base_url()."admin/".$this->viewName?>"</script>
        <?php
        //redirect("admin/".$this->viewName);
    }

    public function view_record()
    {
        $showdate=$this->input->post('showdate');
        $viewtype=$this->input->post('viewtype');
        $type=$this->input->post('type');
        $access=$this->input->post('access');
		$public = $this->input->post('is_public');
		
        $phpTime = js2PhpTime($showdate);

        //echo $phpTime . "+" . $type;
        switch($viewtype){
            case "month":
            $st = mktime(0, 0, 0, date("m", $phpTime), 1, date("Y", $phpTime));
            $et = mktime(0, 0, -1, date("m", $phpTime)+1, 1, date("Y", $phpTime));
            break;
            case "week":
            //suppose first day of a week is monday 
            $monday  =  date("d", $phpTime) - date('N', $phpTime) + 1;
            //echo date('N', $phpTime);
            $st = mktime(0,0,0,date("m", $phpTime), $monday, date("Y", $phpTime));
            $et = mktime(0,0,-1,date("m", $phpTime), $monday+7, date("Y", $phpTime));
            break;
            case "day":
            $st = mktime(0, 0, 0, date("m", $phpTime), date("d", $phpTime), date("Y", $phpTime));
            $et = mktime(0, 0, -1, date("m", $phpTime), date("d", $phpTime)+1, date("Y", $phpTime));
            break;
        }
		
        $ret = array();
        $ret['events'] = array();
        $ret["issort"] =true;
        $ret["start"] = php2JsTime($st);
        $ret["end"] = php2JsTime($et);
        $ret['error'] = null;
        try{
            //get property images
            $start_date=php2MySqlTime($st);
            $start_date1=date('Y-m-d',strtotime($start_date));

            $end_date=php2MySqlTime($et);
            $end_date1=date('Y-m-d',strtotime($end_date));
			if(!empty($public) && $public == '1')
				$match = "is_public = '1'";
			else
				$match = "is_public = '0'";
            $calendar_data = $this->obj->get_records1($start_date,$end_date,'',$match);
				
//pr($calendar_data);exit;
            foreach($calendar_data as $row)
            {
                $agent_name = '';
                
                if(empty($row['event_start_date']))
                {
                    $starting_date=php2JsTime(mySql2PhpTime($row['start_date'].' '.$row['start_time']));
                    $ending_date=php2JsTime(mySql2PhpTime($row['end_date'].' '.$row['end_time']));
                }
                else
                {
                    $starting_date=php2JsTime(mySql2PhpTime($row['event_start_date'].' '.$row['event_start_time']));
                    $ending_date=php2JsTime(mySql2PhpTime($row['event_end_date'].' '.$row['event_end_time']));
                }
                if(!empty($row['agent_name']) && $row['agent_name'] != '(')
                    $agent_name = ' (Event for: '.ucwords ($row['agent_name']).' )';
                else if(!empty($row['task_user_name']) && $row['task_user_name'] != '(')
                    $agent_name = ' (Task for: '.ucwords ($row['task_user_name']).' )';
                $col=$row['event_color'];
                $ret['events'][] = array(
                    $row['cal_id'],
                    $row['event_title'].$agent_name,
                    $starting_date,
                    $ending_date,
                    0,//IsAllDayEvent
                    0,//more than one day event
                                    //$row->InstanceType,
                    0,//$row->is_recurr,//Recurring event
                    $col,
                    1,//editable
                    !empty($uname)?$uname:'',
                    $row['id'],
                    $row['event_title'],
                    $viewtype
                );

                        //pr($ret['events'][])
            }
        }catch(Exception $e){
            $ret['error'] = $e->getMessage();
        }
        //pr($ret);exit;
        echo json_encode($ret);
    }
    /*
    @Description: Function Add New property details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add property details
    @Date: 22-05-2014
    */
    public function add_record()
    {
       //check user right
		check_rights('calendar_add');
	    $fields = array('id,name');     

        //get property amenities
        $match = array('sub_cat_id'=>0,'status'=>1);
        $data['category'] = $this->property_category_model->select_records('',$match,'','=');
        $data['main_content'] = "admin/".$this->viewName."/add";
        $this->load->view('admin/include/template', $data);
    }
	/*
    @Description: Function Add New property details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add property details
    @Date: 22-05-2014
    */
    public function edit_calender()
    {
        $id=$this->input->get('id');
        $start=$this->input->get('start');
        $end=$this->input->get('end');
        $strtm_time = (isset($start))?date("H:i", strtotime($start)):"00:00";
        $endtm_time = (isset($end))?date("H:i", strtotime($end)):"00:00";
        $type =$this->input->get('type');
        $access =$this->input->get('access');
        $data['start']=$start;
        $data['end']=$end;
        $data['id']=$id;
        //get calender
        //pr($_GET);exit;
        if(isset($id) && $id !=0)
        {
            $where = array('crt.id'=>$id);
            $table = "calendar_master as cm";
            $fields = array('cm.event_title','cm.*','crt.event_title','crt.event_notes','crt.event_color','crt.edit_flag','cm.endTemplateDate','crt.event_start_date as start_date','crt.event_end_date as end_date','crt.calendar_id','crt.id as cal_id');
            $join_tables = array('calendar_repeat_trans as crt' => 'cm.id = crt.calendar_id');
            $data['event'] = $this->obj->getmultiple_tables_records1($table,$fields,$join_tables,'left','','','','','','','','',$where);
            //pr($data['event']);exit;
            //$data['event'] = $this->obj->select_records('',$match,'','=');
            //	pr($data['event']);exit;
            
            
            /*$where = array('crt.id'=>$id);
            $table = "calendar_master as cm";
            $fields = array('cm.event_title','cm.*','crt.event_title','crt.event_notes','crt.event_color','crt.edit_flag','cm.endTemplateDate','crt.event_start_date as start_date','crt.event_end_date as end_date','crt.calendar_id','crt.id as cal_id');
            $join_tables = array('calendar_repeat_trans as crt' => 'cm.id = crt.calendar_id');
            */
            
            if(!empty($data['event']) && $data['event'][0]['event_for'] == '2')
            {
                $auid = $data['event'][0]['assigned_user_id'];
                $app = explode(',',$auid);
                $data['slt_user']= $app;
            }
        }
        //pr($data['slt_user']);exit;
        $table = "user_master as um";
        $fields = array('um.*,lm.id as lmid,lm.email_id,lm.user_id,lm.agent_type');
        $join_tables = array('login_master as lm' => 'um.id = lm.user_id');

        $match=array('um.status'=> '1','lm.user_type' => '3');
        $data['user_list'] = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=','','','','asc');
        $this->load->view('admin/calendar/edit_calendar', $data);
    }
    function get_calender_detail()
    {
        $start_date=$this->input->post('start_date');
        $end_date=$this->input->post('end_date');
        $apptid=$this->input->post('apptid');
        $type=$this->input->post('type');

        $calendar_data = $this->obj->get_records1($start_date,$end_date,$check_end_date='end_date');
        //echo $num;
        if($calendar_data[0]['id']!=$apptid)
        {
            if(count($calendar_data)>0)
                $tab=0;
            else
                $tab=1;
        }
        else
        {
            $tab=1;
        }
        echo $tab;
    }
	
	/*
    @Description: Function Add New property details
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add property details
    @Date: 22-05-2014
    */
    function update_calender()
    {
        //pr($_POST);exit;
        $id=$this->input->post('id');
        $event_for = $this->input->post('create_event_for'); // 1: Own, 2: Agent
        $assigned_user_id = $this->input->post('slt_user_type'); // Assigned Agent ids if create_event_for = 2
        $flag = '';$is_task = 0; $task_id = 0;
        $task_result = array();
        if(!empty($id))
        {
            $table ="calendar_master";
            $fields = array('task_user_id,task_id,created_by');
            $match = array("id"=>$id,'created_by'=>$this->admin_session['id']);
            $transaction_result1 = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);
            
            /////// This loop check for ///////////
            for($i=0;$i < count($transaction_result1);$i++)
            {
                if($transaction_result1[$i]['task_user_id'] > 0)
                {
                    $is_task = 1;
                    if(($transaction_result1[$i]['task_user_id'] == $this->admin_session['id']) OR ($transaction_result1[$i]['created_by'] == $this->admin_session['id']))
                    {
                        $task_id = $transaction_result1[$i]['task_id'];
                        if(!empty($task_id))
                        {
                            $table ="calendar_master cm";
                            $fields = array('cm.id,cm.task_user_id,cm.task_id,cm.created_by,crt.id as cal_trans_id');
                            $match = array("task_id"=>$task_id);
                            $join_tables = array('calendar_repeat_trans as crt' => 'cm.id = crt.calendar_id');
                            //$data['event'] = $this->obj->getmultiple_tables_records1($table,$fields,$join_tables,'left','','','','','','','','',$where);
                            $task_result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match);
                        }
                        $flag = 1;
                        break;
                    }
                    else
                    {
                        $flag = '0';
                    }
                }
                else
                {
                    $flag = 1;
                    break;
                }
            }
        }	
        if($flag == 1 || empty($id))
        {
            $ifRepeat=$this->input->post('ifRepeat');
            $repeatType=$this->input->post('repeatType');
            $everyHours=$this->input->post('everyHours');
            $dailyType=$this->input->post('dailyType');
            $everyDays=$this->input->post('everyDays');
            $yearlyType=$this->input->post('yearlyType');
            $everyYears=$this->input->post('everyYears');
            $everyWeeks=$this->input->post('everyWeeks');

            $everyWeekonMon=$this->input->post('everyWeekonMon');
            $everyWeekonTue=$this->input->post('everyWeekonTue');
            $everyWeekonWed=$this->input->post('everyWeekonWed');
            $everyWeekonThu=$this->input->post('everyWeekonThu');
            $everyWeekonFri=$this->input->post('everyWeekonFri');
            $everyWeekonSat=$this->input->post('everyWeekonSat');
            $everyWeekonSun=$this->input->post('everyWeekonSun');

            $monthlyType=$this->input->post('monthlyType');
            $monthDate=$this->input->post('monthDate');
            $monthCount=$this->input->post('monthCount');

            $endTemplateType=$this->input->post('endTemplateType');
            $nthDay=$this->input->post('nthDay');
            $nthDate=$this->input->post('nthDate');
            $nthMonthCount=$this->input->post('nthMonthCount');

            $idata['event_title']=$this->input->post('title');
            $idata['event_notes'] = $this->input->post('Description');
            $idata['start_date'] = date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
            $idata['start_time'] = $this->input->post('stparttime');
            ////////////This Not Repeat Recurrence Events/////////////
            $trdata['event_title']=$this->input->post('title');
            $trdata['event_notes'] = $this->input->post('Description');
            $trdata['event_color'] = $this->input->post('event_color');
            $trdata['event_start_date'] = date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
            $trdata['event_start_time'] = $this->input->post('stparttime');
            $trdata['event_end_date'] = date('Y-m-d',strtotime($this->input->post('etpartdate')));
            $trdata['event_end_time'] = $this->input->post('etparttime');

            ////////// End  Repeat Event/////////////////////////////

            $idata['is_all_day'] = $this->input->post('is_all_day');
            $idata['end_date'] = date('Y-m-d',strtotime($this->input->post('etpartdate')));
            $idata['end_time'] = $this->input->post('etparttime');


            $idata['event_color'] = $this->input->post('event_color');
            $is_public = $this->input->post('is_public');

            $insert_id='';
            if(!empty($is_public))
            {
                $idata['is_public']='1';
            }
            else {
                $idata['is_public']='0';
            }

            $is_email = $this->input->post('is_email');
            $is_popup = $this->input->post('is_popup');

            if(!empty($is_email))
            {
                $idata['is_email'] = '1';
                $idata['email_time_before'] = $this->input->post('email_time_before');
                $idata['email_time_type'] = $this->input->post('email_time_type');
            }
            if(!empty($is_popup))
            {
                $idata['is_popup'] = '1';
                $idata['popup_time_before'] = $this->input->post('popup_time_before');
                $idata['popup_time_type'] = $this->input->post('popup_time_type');
            }
            /////////////// Open Popup In Dashboard //////////
            if(!empty($is_email))
            {
                //echo "1";
                if(!empty($idata['email_time_before']))
                {
                    if(!empty($idata['email_time_type']) && $idata['email_time_type']=='1')
                    {
                        //echo $cdata['task_date'].$cdata['email_time_before']."<br>";
                        $counttype='Hours';
                        $newtaskdate = date($this->config->item('log_date_format'),strtotime($idata['start_date']." ".$idata['start_time']." - ".$idata['email_time_before']." ".$counttype));
                        $idata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
                    }
                    if(!empty($idata['email_time_type']) && $idata['email_time_type']=='2')
                    {
                        $counttype='Days';
                        $newtaskdate = date($this->config->item('common_date_format'),strtotime($idata['start_date']." ".$idata['start_time']." - ".$idata['email_time_before']." ".$counttype));
                        $idata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
                    }	
                }
            }
			
            if(!empty($is_popup))
            {
                if(!empty($idata['popup_time_before']))
                {
                    if(!empty($idata['popup_time_type']) && $idata['popup_time_type']=='1')
                    {
                        $counttype='Hours';
                        $newtaskdate1 = date($this->config->item('log_date_format'),strtotime($idata['start_date']." ".$idata['start_time']."- ".$idata['popup_time_before']." ".$counttype));
                        $idata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	
                    }
                    if(!empty($idata['popup_time_type'])&& $idata['popup_time_type']=='2')
                    {
                        $counttype='Days';
                        $newtaskdate1 = date($this->config->item('common_date_format'),strtotime($idata['start_date']." ".$idata['start_time']."- ".$idata['popup_time_before']." ".$counttype));
                        $idata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	
                    }
                }
            }	
		
            ///////////////End /////////////
            $pop_by = $this->input->post('pop_by');
            $is_gift = $this->input->post('gift');
            if(!empty($pop_by))
            {
                $idata['is_pop_by'] = '1';
            }
            if(!empty($is_gift))
            {
                $idata['is_gift'] = '1';
            }
            $idata['endTemplateType']=$this->input->post('endTemplateType');
            if($endTemplateType == '2')
            {
                $idata['endCounter']=$this->input->post('endCounter');
            }
            else {
                $idata['endCounter']=0;
            }
            if($endTemplateType == '3')
            {
                $idata['endTemplateDate']=date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                $idata['endTemplateTime']=date('H:i:s',strtotime($this->input->post('endTemplateTime')));
            }
		
            $idata['event_for'] = $event_for;
            if($event_for == '2' && !empty($assigned_user_id))
            {
                $assigned_userid = implode(',',$assigned_user_id);
                $idata['assigned_user_id'] = $assigned_userid;
            } else {
                $idata['assigned_user_id'] = '';
            }
            
            $idata['ifRepeat']=$this->input->post('ifRepeat');
            if(!empty($ifRepeat) && $ifRepeat=='1')
            {
                $idata['ifRepeat']=$this->input->post('ifRepeat');
                // Insert Hourly
                 /*?>if($repeatType=='1')
                {	
                        $idata['repeatType']=$this->input->post('repeatType');
                        if(!empty($everyHours))
                        {	
                                if(!empty($everyHours)){$idata['everyHours']=$this->input->post('everyHours');}
                        }	
                }<?php */
                // Insert Daily

                if($repeatType=='2')
                {	
                    $idata['repeatType']=$this->input->post('repeatType');
                    if($dailyType=='1')
                    {	
                        if(!empty($everyDays)){$idata['everyDays']=$this->input->post('everyDays');}
                        if(!empty($dailyType)){$idata['dailyType']=$this->input->post('dailyType');}
                    }	
                    else
                    {
                        if(!empty($dailyType)){$idata['dailyType']=$this->input->post('dailyType');}
                    }
                }	
                // Insert Weekly

                if($repeatType=='3')
                {	
                    $idata['repeatType']=$this->input->post('repeatType');
                    !empty($everyWeeks)?$idata['everyWeeks']=$this->input->post('everyWeeks'):$idata['everyWeeks']=0;
                    !empty($everyWeekonMon)?$idata['everyWeekonMon']=$this->input->post('everyWeekonMon'):$idata['everyWeekonMon']=0;
                    !empty($everyWeekonTue)?$idata['everyWeekonTue']=$this->input->post('everyWeekonTue'):$idata['everyWeekonTue']=0;
                    !empty($everyWeekonWed)?$idata['everyWeekonWed']=$this->input->post('everyWeekonWed'):$idata['everyWeekonWed']=0;
                    !empty($everyWeekonThu)?$idata['everyWeekonThu']=$this->input->post('everyWeekonThu'):$idata['everyWeekonThu']=0;
                    !empty($everyWeekonFri)?$idata['everyWeekonFri']=$this->input->post('everyWeekonFri'):$idata['everyWeekonFri']=0;
                    !empty($everyWeekonSat)?$idata['everyWeekonSat']=$this->input->post('everyWeekonSat'):$idata['everyWeekonSat']=0;
                    !empty($everyWeekonSun)?$idata['everyWeekonSun']=$this->input->post('everyWeekonSun'):$idata['everyWeekonSun']=0;
                }	
                // Insert Monthly
                if($repeatType=='4')
                {	
                    $idata['repeatType']=$this->input->post('repeatType');
                    if($monthlyType == '1')
                    {	
                        $idata['monthlyType']=$this->input->post('monthlyType');
                        if(!empty($monthDate))
                        {	
                            if(!empty($monthDate)){$idata['monthDate']=$this->input->post('monthDate');}
                            if(!empty($monthDate)){$idata['monthCount']=$this->input->post('monthCount');}
                        }
                    }
                    if($monthlyType == '2')
                    {	
                        $idata['monthlyType']=$this->input->post('monthlyType');
                        //if(!empty($monthDate))
                        //{
                            if(!empty($nthDay)){$idata['nthDay']=$this->input->post('nthDay');}
                            if(!empty($nthDate)){$idata['nthDate']=$this->input->post('nthDate');}
                            if(!empty($nthMonthCount)){$idata['nthMonthCount']=$this->input->post('nthMonthCount');}                            
                        //}
                    }
                }

                 // Insert Yearly Sanjay M. 16-10-2104
                if($repeatType=='5')
                {	
                    $idata['repeatType']=$this->input->post('repeatType');
                    if(!empty($everyYears)){$idata['everyYears']=$this->input->post('everyYears');}
                }
                // 	End Date
            }
            $changetextflag = 0;
            if(!empty($id)) // Update
            {
                $event_type=$this->input->post('event_type');
                $cal_id=$this->input->post('cal_id');
                $ifRepeat = $this->input->post('ifRepeat');
                $ctid=$this->input->post('cal_id'); // Calendar trans id
                $cmid=$this->input->post('calendar_id'); // Calendar Master id
                $event_data['event_title'] = $this->input->post('title');
                $event_data['event_notes'] = $this->input->post('Description');
                $event_data['event_color'] = $this->input->post('event_color');
                $event_data['event_start_time'] = $this->input->post('stparttime');
                $event_data['event_end_time'] = $this->input->post('etparttime');

                $changetext['event_title'] = $event_data['event_title'];
                $changetext['event_notes'] = $event_data['event_notes'];
                $changetext['event_color'] = $event_data['event_color'];

                $changetextc['id'] = $cmid;
                $changetextc['event_title'] = $event_data['event_title'];
                $changetextc['event_notes'] = $event_data['event_notes'];
                $changetextc['event_color'] = $event_data['event_color'];
                $changetextc['modified_date'] = date('Y-m-d H:i:s');
                $changetextc['modified_by'] = $this->admin_session['id'];

                $inend=date_create(date('Y-m-d', strtotime($this->input->post('etpartdate'))));
                $instar=date_create(date('Y-m-d',strtotime($this->input->post('stpartdate'))));
                $indaydiff = date_diff($instar, $inend);
                $total_inday=$indaydiff->format("%a");

                if(!empty($ifRepeat) && $ifRepeat=='1')
                {
                    $st_tm = $this->input->post('stparttime');
                    $et_tm = $this->input->post('etparttime');

                    $match = array('id'=>$cmid);
                    $result=$this->obj->select_records('',$match,'','=');

                    $table ="calendar_repeat_trans";
                    $fields = array('*');
                    $match = array("id"=>$ctid);
                    $transaction_result = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);

                    if($this->input->post('old_ifrepeat_flag') == 0)
                    {
                        $event_type = 3;
                    }

                    /*Only This Event*/
                    if(!empty($event_type) && $event_type == 1)
                    {
                        $single['id']=$cal_id;
                        $single['calendar_id']=$id;
                        $single['event_title']=$this->input->post('title');
                        $single['event_notes'] = $this->input->post('Description');
                        $single['event_color'] = $this->input->post('event_color');
                        $single['event_start_date']=date('Y-m-d', strtotime($this->input->post('stpartdate')));
                        $single['event_start_time'] = $this->input->post('stparttime');
                        $single['event_end_date'] = date('Y-m-d', strtotime($this->input->post('etpartdate')));
                        $single['event_end_time'] = $this->input->post('etparttime');
                        $single['edit_flag'] = '1';
                        //pr($single);exit;	
                        $update_id = $this->obj->update_record_trandata($single);
                        $ddata['id']=$cmid;
                        $ddata['modified_by'] = $this->admin_session['id'];
                        $ddata['modified_date'] = date('Y-m-d H-i-s');
                        $ddata['event_for'] = $event_for;
                        /*if($event_for == '2' && !empty($assigned_user_id))
                        {
                            $assigned_userid = implode(',',$assigned_user_id);
                            $ddata['assigned_user_id'] = $assigned_userid;
                        $update_id = $this->obj->update_record($ddata);
                        } else {
                            $ddata['assigned_user_id'] = '';*/
                            $update_id = $this->obj->update_record($ddata);
                        //}
                    }
                    /*Following*/
                    else if(!empty($event_type) && $event_type == 2)
                    {
                        /*Sanjay Moghariya*/

                        $following['event_title']=$this->input->post('title');
                        $following['event_notes'] = $this->input->post('Description');
                        $following['event_color'] = $this->input->post('event_color');

                        // New code 24-09-2014
                        //$event_data['calendar_id']=$cmid;
                        $endTemplateType=$this->input->post('endTemplateType');
                        $start_date= date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
                        $end_date = date('Y-m-d',strtotime($this->input->post('etpartdate')));
                        $original_start_date = strtotime($start_date);
                        // Recurrence Type = 'Daily'
                        if($repeatType == '2')
                        {	
                            if($dailyType=='1') // Every
                            {	
                                $everyDays=$this->input->post('everyDays');
                                if($endTemplateType == '1') // Never
                                {
                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['everyDays'] != $everyDays || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];

                                            if(!empty($task_result))
                                            {
                                                foreach($task_result as $task_row)
                                                {
                                                    $newfollowing_lastid = $this->obj->insert_record($idata);

                                                    if($newfollowing_lastid > 0)
                                                    {
                                                        $event_data['calendar_id']=$newfollowing_lastid;
                                                        $this->obj->delete_calendar_trans($cmid,$ctid);
                                                        $this->endon_never($start_date,$total_inday,$everyDays,$event_data);
                                                    }
                                                }
                                            } else {
                                                $newfollowing_lastid = $this->obj->insert_record($idata);

                                                if($newfollowing_lastid > 0)
                                                {
                                                    $event_data['calendar_id']=$newfollowing_lastid;
                                                    $this->obj->delete_calendar_trans($cmid,$ctid);
                                                    $this->endon_never($start_date,$total_inday,$everyDays,$event_data);
                                                }
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid);
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $endCounter=$this->input->post('endCounter');

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        
                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['endCounter'] != $endCounter || $result[0]['everyDays'] != $everyDays || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];

                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_after_n_occurance($start_date,$endCounter,$total_inday,$everyDays,$event_data);
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = $this->input->post('endTemplateDate');
                                    $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));

                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['everyDays'] != $everyDays || strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt || strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_random_date($start_date,$endTemplateDate,$total_inday,$everyDays,$event_data);
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                            }
                            else if($dailyType=='2') // Every Weekdays
                            {
                                if($endTemplateType == '1') // Never
                                {
                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_never_weekdays($start_date,$total_inday,$event_data,'weekdays');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $endCounter=$this->input->post('endCounter');

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));

                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['endCounter'] != $endCounter || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];

                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_after_n_occurance_weekdays($start_date,$endCounter,$total_inday,$event_data,'weekdays');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = $this->input->post('endTemplateDate');
                                    $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));

                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt || strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_random_date_weekdays($start_date,$endTemplateDate,$total_inday,$event_data,'weekdays');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                            }
                            else if($dailyType=='3') // Every Weekends
                            {
                                if($endTemplateType == '1') // Never
                                {
                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_never_weekdays($start_date,$total_inday,$event_data,'weekends');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $endCounter=$this->input->post('endCounter');

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));

                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['endCounter'] != $endCounter)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];

                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_after_n_occurance_weekdays($start_date,$endCounter,$total_inday,$event_data,'weekends');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = $this->input->post('endTemplateDate');
                                    $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));

                                        if($result[0]['repeatType'] != $repeatType || $result[0]['dailyType'] != $dailyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt || strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_random_date_weekdays($start_date,$endTemplateDate,$total_inday,$event_data,'weekends');
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                            }
                        }
                        // End new code

                        // Recurrence Type = 'Weekly' Sanjay Moghariya 26-09-2014
                        else if($repeatType == '3')
                        {	
                            $everyWeeks = $this->input->post('everyWeeks');
                            // If 'Never' selected in End Date (repeatType: Weekly)
                            if($endTemplateType == '1') // Never
                            {
                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                    $weekdaysflag = 0;
                                    if($result[0]['everyWeekonMon'] != $everyWeekonMon || $result[0]['everyWeekonTue'] != $everyWeekonTue || $result[0]['everyWeekonWed'] != $everyWeekonWed || $result[0]['everyWeekonThu'] != $everyWeekonThu || $result[0]['everyWeekonFri'] != $everyWeekonFri || $result[0]['everyWeekonSat'] != $everyWeekonSat || $result[0]['everyWeekonSun'] != $everyWeekonSun) {
                                        $weekdaysflag = 1;
                                    }
                                    if(($result[0]['repeatType'] != $repeatType) || ($result[0]['endTemplateType'] != $endTemplateType) || (strtotime($start_date) != strtotime($oldstdate)) || (strtotime($end_date) != strtotime($oldetdate)) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm)))) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm)))) || ($result[0]['everyWeeks'] != $everyWeeks) || ($weekdaysflag == 1) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];
                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;

                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_never_weekly($start_date,$original_start_date,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                            // If 'After' selected in End Date (repeatType: Weekly)
                            else if($endTemplateType == '2') // After n Occurance
                            {
                                $endCounter=$this->input->post('endCounter');

                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                    $weekdaysflag = 0;
                                    if($result[0]['everyWeekonMon'] != $everyWeekonMon || $result[0]['everyWeekonTue'] != $everyWeekonTue || $result[0]['everyWeekonWed'] != $everyWeekonWed || $result[0]['everyWeekonThu'] != $everyWeekonThu || $result[0]['everyWeekonFri'] != $everyWeekonFri || $result[0]['everyWeekonSat'] != $everyWeekonSat || $result[0]['everyWeekonSun'] != $everyWeekonSun) {
                                        $weekdaysflag = 1;
                                    }
                                    if(($result[0]['repeatType'] != $repeatType) || ($result[0]['endTemplateType'] != $endTemplateType) || (strtotime($start_date) != strtotime($oldstdate)) || (strtotime($end_date) != strtotime($oldetdate)) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm)))) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm)))) || ($result[0]['everyWeeks'] != $everyWeeks) || ($weekdaysflag == 1) || ($result[0]['endCounter'] != $endCounter) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];

                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;
                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_after_n_occurance_weekly($start_date,$original_start_date,$endCounter,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                            // If 'End on' selected in End Date (repeatType: Weekly)
                            else if($endTemplateType == '3') // End on any random date
                            {
                                $endTemplateDate = $this->input->post('endTemplateDate');
                                $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                    $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));

                                    $weekdaysflag = 0;
                                    if($result[0]['everyWeekonMon'] != $everyWeekonMon || $result[0]['everyWeekonTue'] != $everyWeekonTue || $result[0]['everyWeekonWed'] != $everyWeekonWed || $result[0]['everyWeekonThu'] != $everyWeekonThu || $result[0]['everyWeekonFri'] != $everyWeekonFri || $result[0]['everyWeekonSat'] != $everyWeekonSat || $result[0]['everyWeekonSun'] != $everyWeekonSun) {
                                        $weekdaysflag = 1;
                                    }
                                    if(($result[0]['repeatType'] != $repeatType) || ($result[0]['endTemplateType'] != $endTemplateType) || (strtotime($start_date) != strtotime($oldstdate)) || (strtotime($end_date) != strtotime($oldetdate)) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm)))) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm)))) || ($result[0]['everyWeeks'] != $everyWeeks) || ($weekdaysflag == 1) || (strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt) || (strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];
                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;
                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_random_date_weekly($start_date,$original_start_date,$everyWeeks,$endTemplateDate,$total_inday,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid);
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                        }

                        // Recurrence Type = 'Monthly' Sanjay Moghariya 26-09-2014
                        else if($repeatType == '4')
                        {
                            // nday with repeat after n month interval
                            if($monthlyType == '1')
                            {
                                // If 'Never' selected in End Date (repeatType: Monthly)
                                if($endTemplateType == '1') // Never
                                {
                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        if($result[0]['repeatType'] != $repeatType || $result[0]['monthlyType'] != $monthlyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['monthDate'] != $monthDate || $result[0]['monthCount'] != $monthCount || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_never_monthly_day($start_date,$original_start_date,$total_inday,$event_data,$monthDate,$monthCount);
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                // If 'After' selected in End Date (repeatType: Monthly)
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $endCounter=$this->input->post('endCounter');
                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        if(($result[0]['repeatType'] != $repeatType) || ($result[0]['monthlyType'] != $monthlyType) || ($result[0]['endTemplateType'] != $endTemplateType) || (strtotime($start_date) != strtotime($oldstdate)) || (strtotime($end_date) != strtotime($oldetdate)) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm)))) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm)))) || ($result[0]['monthDate'] != $monthDate) || ($result[0]['monthCount'] != $monthCount) || ($result[0]['endCounter'] != $endCounter) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];

                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_after_n_occurance_monthly_day($start_date,$original_start_date,$endCounter,$total_inday,$event_data,$monthDate,$monthCount);
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                                // If 'End on' selected in End Date (repeatType: Monthly)
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = $this->input->post('endTemplateDate');
                                    $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                    if(!empty($result) && !empty($transaction_result))
                                    {
                                        $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                        $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));
                                        if(($result[0]['repeatType'] != $repeatType) || ($result[0]['monthlyType'] != $monthlyType) || ($result[0]['endTemplateType'] != $endTemplateType) || (strtotime($start_date) != strtotime($oldstdate)) || (strtotime($end_date) != strtotime($oldetdate)) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm)))) || (strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm)))) || ($result[0]['monthDate'] != $monthDate) || ($result[0]['monthCount'] != $monthCount) || (strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt) || (strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                        {
                                            if(!empty($this->admin_session))
                                            {
                                                $idata['status']='1';
                                                $idata['modified_date'] = date('Y-m-d H:i:s');
                                                $idata['modified_by'] = $this->admin_session['id'];		
                                            }
                                            $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                            $idata['created_by'] = $result[0]['created_by'];
                                            $newfollowing_lastid = $this->obj->insert_record($idata);

                                            if($newfollowing_lastid > 0)
                                            {
                                                $event_data['calendar_id']=$newfollowing_lastid;
                                                $this->obj->delete_calendar_trans($cmid,$ctid);
                                                $this->endon_random_date_monthly_day($start_date,$original_start_date,$endTemplateDate,$total_inday,$event_data,$monthDate,$monthCount);
                                            }
                                            $changetextflag = 2;
                                        }
                                        else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                            $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                            $this->obj->update_record($changetextc);
                                            $changetextflag = 1;
                                        }
                                    }
                                }
                            }
                            /*
                            For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                            */
                            else if($monthlyType == '2')
                            {
                                // If nthDay, nthDate and nthMonthCount Selected
                                if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                {
                                    // If 'Never' selected in End Date (repeatType: Monthly)
                                    if($endTemplateType == '1')
                                    {
                                        if(!empty($result) && !empty($transaction_result))
                                        {
                                            $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                            $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                            if($result[0]['repeatType'] != $repeatType || $result[0]['monthlyType'] != $monthlyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['nthDay'] != $nthDay || $result[0]['nthDate'] != $nthDate || $result[0]['nthMonthCount'] != $nthMonthCount || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                            {
                                                if(!empty($this->admin_session))
                                                {
                                                    $idata['status']='1';
                                                    $idata['modified_date'] = date('Y-m-d H:i:s');
                                                    $idata['modified_by'] = $this->admin_session['id'];		
                                                }
                                                $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                                $idata['created_by'] = $result[0]['created_by'];
                                                $newfollowing_lastid = $this->obj->insert_record($idata);

                                                if($newfollowing_lastid > 0)
                                                {
                                                    $event_data['calendar_id']=$newfollowing_lastid;
                                                    $this->obj->delete_calendar_trans($cmid,$ctid);

                                                    // nthDay: First
                                                    if($nthDay == '1')
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,1);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                                break;
                                                            default :
                                                                break;

                                                        }
                                                    } // End nthDay: First
                                                    else if($nthDay == '2') // nthDay: Second
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,2);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                                break;
                                                            default :
                                                                break;

                                                        }
                                                    } // End nthDay: Second
                                                    else if($nthDay == '3') // nthDay: Third
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,3);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                                break;
                                                            default :
                                                                break;

                                                        }
                                                    } // End nthDay: Third
                                                    else if($nthDay == '4') // nthDay: Fourth
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,4);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                                break;
                                                            default :
                                                                break;

                                                        }
                                                    } // End nthDay: Fourth
                                                    else if($nthDay == '5') // nthDay: Last
                                                    {
                                                            switch($nthDate)
                                                            {
                                                                case '1': // nthDate: Day
                                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,5);
                                                                    break;
                                                                case '2': // nthDate: Weekday
                                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                                    break;
                                                                case '3': // nthDate: Weekend
                                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                                    break;
                                                                case '4': // nthDate: Monday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                                    break;
                                                                case '5': // nthDate: Tuesday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                                    break;
                                                                case '6': // nthDate: Wednesday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                                    break;
                                                                case '7': // nthDate: Thursday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                                    break;
                                                                case '8': // nthDate: Friday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                                    break;
                                                                case '9': // nthDate: Saturday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                                    break;
                                                                case '10': // nthDate: Sunday
                                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                                    break;
                                                                default :
                                                                    break;

                                                            }
                                                        } // End nthDay: Last
                                                }
                                                $changetextflag = 2;
                                            }
                                            else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                                $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                                $this->obj->update_record($changetextc);
                                                $changetextflag = 1;
                                            }
                                        }
                                    } // End Monthly : 'Never'

                                    // If 'After' selected in End Date (repeatType: Monthly)
                                    else if($endTemplateType == '2')
                                    {
                                        $endCounter=$this->input->post('endCounter');

                                        if(!empty($result) && !empty($transaction_result))
                                        {
                                            $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                            $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                            if($result[0]['repeatType'] != $repeatType || $result[0]['monthlyType'] != $monthlyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['nthDay'] != $nthDay || $result[0]['nthDate'] != $nthDate || $result[0]['nthMonthCount'] != $nthMonthCount || $result[0]['endCounter'] != $endCounter || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                            {
                                                if(!empty($this->admin_session))
                                                {
                                                    $idata['status']='1';
                                                    $idata['modified_date'] = date('Y-m-d H:i:s');
                                                    $idata['modified_by'] = $this->admin_session['id'];		
                                                }
                                                $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                                $idata['created_by'] = $result[0]['created_by'];

                                                $newfollowing_lastid = $this->obj->insert_record($idata);

                                                if($newfollowing_lastid > 0)
                                                {
                                                    $event_data['calendar_id']=$newfollowing_lastid;
                                                    $this->obj->delete_calendar_trans($cmid,$ctid);

                                                    // nthDay: First
                                                    if($nthDay == '1')
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,1);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: First
                                                    else if($nthDay == '2') // nthDay: Second
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,2);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Second
                                                    else if($nthDay == '3') // nthDay: Third
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,3);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Third
                                                    else if($nthDay == '4') // nthDay: Fourth
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,4);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Fourth
                                                    else if($nthDay == '5') // nthDay: Last
                                                    {
                                                            switch($nthDate)
                                                            {
                                                                case '1': // nthDate: Day
                                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,5);
                                                                    break;
                                                                case '2': // nthDate: Weekday
                                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                                    break;
                                                                case '3': // nthDate: Weekend
                                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                                    break;
                                                                case '4': // nthDate: Monday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                                    break;
                                                                case '5': // nthDate: Tuesday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                                    break;
                                                                case '6': // nthDate: Wednesday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                                    break;
                                                                case '7': // nthDate: Thursday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                                    break;
                                                                case '8': // nthDate: Friday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                                    break;
                                                                case '9': // nthDate: Saturday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                                    break;
                                                                case '10': // nthDate: Sunday
                                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                                    break;
                                                                default :
                                                                    break;
                                                            }
                                                        } // End nthDay: Last
                                                }
                                                $changetextflag = 2;
                                            }
                                            else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                                $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                                $this->obj->update_record($changetextc);
                                                $changetextflag = 1;
                                            }
                                        }
                                    }

                                    // If 'End on' selected in End Date (repeatType: Monthly)
                                    else if($endTemplateType == '3')
                                    {
                                        $endTemplateDate = $this->input->post('endTemplateDate');
                                        $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                        if(!empty($result) && !empty($transaction_result))
                                        {
                                            $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                            $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                            $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));
                                            if($result[0]['repeatType'] != $repeatType || $result[0]['monthlyType'] != $monthlyType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['nthDay'] != $nthDay || $result[0]['nthDate'] != $nthDate || $result[0]['nthMonthCount'] != $nthMonthCount || (strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt) || (strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm) || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                            {
                                                if(!empty($this->admin_session))
                                                {
                                                    $idata['status']='1';
                                                    $idata['modified_date'] = date('Y-m-d H:i:s');
                                                    $idata['modified_by'] = $this->admin_session['id'];		
                                                }
                                                $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                                $idata['created_by'] = $result[0]['created_by'];
                                                $newfollowing_lastid = $this->obj->insert_record($idata);

                                                if($newfollowing_lastid > 0)
                                                {
                                                    $event_data['calendar_id']=$newfollowing_lastid;
                                                    $this->obj->delete_calendar_trans($cmid,$ctid);

                                                    // nthDay: First
                                                    if($nthDay == '1')
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,1);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: First
                                                    else if($nthDay == '2') // nthDay: Second
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,2);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Second
                                                    else if($nthDay == '3') // nthDay: Third
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,3);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Third
                                                    else if($nthDay == '4') // nthDay: Fourth
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,4);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Fourth
                                                    else if($nthDay == '5') // nthDay: Last
                                                    {
                                                        switch($nthDate)
                                                        {
                                                            case '1': // nthDate: Day
                                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,5);
                                                                break;
                                                            case '2': // nthDate: Weekday
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekday');
                                                                break;
                                                            case '3': // nthDate: Weekend
                                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekend');
                                                                break;
                                                            case '4': // nthDate: Monday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','mon');
                                                                break;
                                                            case '5': // nthDate: Tuesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','tue');
                                                                break;
                                                            case '6': // nthDate: Wednesday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','wed');
                                                                break;
                                                            case '7': // nthDate: Thursday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','thu');
                                                                break;
                                                            case '8': // nthDate: Friday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','fri');
                                                                break;
                                                            case '9': // nthDate: Saturday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sat');
                                                                break;
                                                            case '10': // nthDate: Sunday
                                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sun');
                                                                break;
                                                            default :
                                                                break;
                                                        }
                                                    } // End nthDay: Last
                                                }
                                                $changetextflag = 2;
                                            }
                                            else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                                $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                                $this->obj->update_record($changetextc);
                                                $changetextflag = 1;
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // Recurrence Type = 'Yearly' Sanjay Moghariya 16-10-2014
                        else if($repeatType == '5')
                        {	
                            $everyYears=$this->input->post('everyYears');
                            if($endTemplateType == '1') // Never
                            {
                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                    if($result[0]['repeatType'] != $repeatType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['everyYears'] != $everyYears || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];

                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;
                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_never_yearly($start_date,$total_inday,$everyYears,$event_data);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid);
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                            else if($endTemplateType == '2') // After n Occurance
                            {
                                $endCounter=$this->input->post('endCounter');

                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));

                                    if($result[0]['repeatType'] != $repeatType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['endCounter'] != $endCounter || $result[0]['everyYears'] != $everyYears)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];

                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;
                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_after_n_occurance_yearly($start_date,$endCounter,$total_inday,$everyYears,$event_data);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                            else if($endTemplateType == '3') // End on any random date
                            {
                                $endTemplateDate = $this->input->post('endTemplateDate');
                                $endtempdt = strtotime(date('Y-m-d',strtotime($endTemplateDate)));

                                if(!empty($result) && !empty($transaction_result))
                                {
                                    $oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                                    $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                                    $endtmptm = strtotime(date('H:i:s',strtotime($this->input->post('endTemplateTime'))));

                                    if($result[0]['repeatType'] != $repeatType || $result[0]['endTemplateType'] != $endTemplateType || strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['everyYears'] != $everyYears || strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt || strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm || $result[0]['event_for'] != $event_for || $result[0]['assigned_user_id'] != $assigned_user_id)
                                    {
                                        if(!empty($this->admin_session))
                                        {
                                            $idata['status']='1';
                                            $idata['modified_date'] = date('Y-m-d H:i:s');
                                            $idata['modified_by'] = $this->admin_session['id'];		
                                        }
                                        $idata['created_date'] = date('Y-m-d H:i:s',strtotime($result[0]['created_date']));
                                        $idata['created_by'] = $result[0]['created_by'];
                                        $newfollowing_lastid = $this->obj->insert_record($idata);

                                        if($newfollowing_lastid > 0)
                                        {
                                            $event_data['calendar_id']=$newfollowing_lastid;
                                            $this->obj->delete_calendar_trans($cmid,$ctid);
                                            $this->endon_random_date_yearly($start_date,$endTemplateDate,$total_inday,$everyYears,$event_data);
                                        }
                                        $changetextflag = 2;
                                    }
                                    else if(($event_data['event_title'] != $transaction_result[0]['event_title'] || $event_data['event_notes'] != $transaction_result[0]['event_notes'] || $event_data['event_color'] != $transaction_result[0]['event_color']) && $changetextflag != 2 ) {
                                        $this->obj->update_following_trandata($changetext,$cmid,$ctid); 
                                        $this->obj->update_record($changetextc);
                                        $changetextflag = 1;
                                    }
                                }
                            }
                        }

                        /*End Sanjay M.*/
                    }
                    // All Events
                    else if(!empty($event_type) && $event_type == 3)
                    {
                        // New code 22-09-2014  Sanjay Moghariya 
                        $event_data['calendar_id']=$cmid;
                        $endTemplateType=$this->input->post('endTemplateType');
                        $start_date= date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
                        $end_date = date('Y-m-d',strtotime($this->input->post('etpartdate')));

                        /*$oldstdate=date('Y-m-d',strtotime($transaction_result[0]['event_start_date']));
                        $oldetdate=date('Y-m-d', strtotime($transaction_result[0]['event_end_date']));
                        if(!empty($result))
                        {
                            if(strtotime($start_date) != strtotime($oldstdate) || strtotime($end_date) != strtotime($oldetdate) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_start_time']))) != strtotime(date('H:i:s',strtotime($st_tm))) || strtotime(date("H:i:s",strtotime($transaction_result[0]['event_end_time']))) != strtotime(date('H:i:s',strtotime($et_tm))) || $result[0]['everyYears'] != $everyYears || strtotime(date('Y-m-d',strtotime($result[0]['endTemplateDate']))) != $endtempdt || strtotime(date('H:i:s',strtotime($result[0]['endTemplateTime']))) != $endtmptm)
                            {

                            }
                        }*/



                        $original_start_date = strtotime($start_date);
                        // Recurrence Type = 'Daily'
                        if($repeatType == '2')
                        {	
                            $everyDays=$this->input->post('everyDays');
                            if($dailyType=='1') // Every
                            {	
                                $everyDays=$this->input->post('everyDays');
                                if($endTemplateType == '1') // Never
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_never($start_date,$total_inday,$everyDays,$event_data);
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $endCounter=$this->input->post('endCounter');
                                    $update_id = $this->endon_after_n_occurance($start_date,$endCounter,$total_inday,$everyDays,$event_data);
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                    $this->obj->delete_calendar_record($cmid);
                                    $update_id = $this->endon_random_date($start_date,$endTemplateDate,$total_inday,$everyDays,$event_data);
                                }
                            }
                            else if($dailyType=='2') // Every Weekdays
                            {
                                if($endTemplateType == '1') // Never
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_never_weekdays($start_date,$total_inday,$event_data,'weekdays');
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $endCounter=$this->input->post('endCounter');
                                    $update_id = $this->endon_after_n_occurance_weekdays($start_date,$endCounter,$total_inday,$event_data,'weekdays');
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                    $this->obj->delete_calendar_record($cmid);
                                    $update_id = $this->endon_random_date_weekdays($start_date,$endTemplateDate,$total_inday,$event_data,'weekdays');
                                }
                            }
                            else if($dailyType=='3') // Every Weekends
                            {
                                if($endTemplateType == '1') // Never
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_never_weekdays($start_date,$total_inday,$event_data,'weekends');
                                }
                                else if($endTemplateType == '2') // After n Occurance
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $endCounter=$this->input->post('endCounter');
                                    $update_id = $this->endon_after_n_occurance_weekdays($start_date,$endCounter,$total_inday,$event_data,'weekends');
                                }
                                else if($endTemplateType == '3') // End on any random date
                                {
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                    $this->obj->delete_calendar_record($cmid);
                                    $update_id = $this->endon_random_date_weekdays($start_date,$endTemplateDate,$total_inday,$event_data,'weekends');
                                }
                            }
                        }

                        // Recurrence Type = 'Weekly' Sanjay Moghariya 23-09-2014
                        else if($repeatType == '3')
                        {
                            $everyWeeks = $this->input->post('everyWeeks');
                            /*If 'Never' selected in End Date (repeatType: Weekly)*/
                            if($endTemplateType == '1')
                            {
                                $this->obj->delete_calendar_record($cmid);
                                $update_id = $this->endon_never_weekly($start_date,$original_start_date,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                            /*If 'After' selected in End Date (repeatType: Weekly)*/
                            else if($endTemplateType == '2')
                            {
                                $this->obj->delete_calendar_record($cmid);
                                $endCounter=$this->input->post('endCounter');
                                $update_id = $this->endon_after_n_occurance_weekly($start_date,$original_start_date,$endCounter,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                            /*If 'End on' selected in End Date (repeatType: Weekly)*/
                            else if($endTemplateType == '3')
                            {
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                $this->obj->delete_calendar_record($cmid);
                                $update_id = $this->endon_random_date_weekly($start_date,$original_start_date,$everyWeeks,$endTemplateDate,$total_inday,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                            }
                        }

                        // Recurrence Type = 'Monthly' Sanjay Moghariya 23-09-2014
                        else if($repeatType == '4')
                        {
                            // nday with repeat after n month interval
                            if($monthlyType == '1')
                            {
                                /*If 'Never' selected in End Date (repeatType: Monthly)*/
                                if($endTemplateType == '1')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_never_monthly_day($start_date,$original_start_date,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                                /*If 'After' selected in End Date (repeatType: Monthly)*/
                                else if($endTemplateType == '2')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $endCounter=$this->input->post('endCounter');
                                    $this->endon_after_n_occurance_monthly_day($start_date,$original_start_date,$endCounter,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                                /*If 'End on' selected in End Date (repeatType: Monthly)*/
                                else if($endTemplateType == '3')
                                {
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_random_date_monthly_day($start_date,$original_start_date,$endTemplateDate,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                            }
                            /*
                            For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                            */
                            else if($monthlyType == '2')
                            {
                                // If 'Never' selected in End Date (repeatType: Monthly)
                                if($endTemplateType == '1')
                                {
                                    // If nthDay, nthDate and nthMonthCount Selected
                                    if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                    {
                                        $this->obj->delete_calendar_record($cmid);
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekend_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;

                                            }
                                        } // End nthDay: Last
                                    }
                                } // End Monthly : 'Never'

                                // If 'After' selected in End Date (repeatType: Monthly)
                                else if($endTemplateType == '2')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    // If nthDay, nthDate and nthMonthCount Selected
                                    if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                    {
                                        $endCounter=$this->input->post('endCounter');
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Last
                                    }
                                }

                                // If 'End on' selected in End Date (repeatType: Monthly)
                                else if($endTemplateType == '3')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    /*if($total_day < 0)
                                    {
                                        $this->obj->delete_record($insert_id);
                                    }*/
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 

                                    // If nthDay, nthDate and nthMonthCount Selected
                                    if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                    {
                                        // nthDay: First
                                        if($nthDay == '1')
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,1);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: First
                                        else if($nthDay == '2') // nthDay: Second
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,2);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Second
                                        else if($nthDay == '3') // nthDay: Third
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,3);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Third
                                        else if($nthDay == '4') // nthDay: Fourth
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,4);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Fourth
                                        else if($nthDay == '5') // nthDay: Last
                                        {
                                            switch($nthDate)
                                            {
                                                case '1': // nthDate: Day
                                                    $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,5);
                                                    break;
                                                case '2': // nthDate: Weekday
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekday');
                                                    break;
                                                case '3': // nthDate: Weekend
                                                    $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekend');
                                                    break;
                                                case '4': // nthDate: Monday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','mon');
                                                    break;
                                                case '5': // nthDate: Tuesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','tue');
                                                    break;
                                                case '6': // nthDate: Wednesday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','wed');
                                                    break;
                                                case '7': // nthDate: Thursday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','thu');
                                                    break;
                                                case '8': // nthDate: Friday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','fri');
                                                    break;
                                                case '9': // nthDate: Saturday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sat');
                                                    break;
                                                case '10': // nthDate: Sunday
                                                    $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sun');
                                                    break;
                                                default :
                                                    break;
                                            }
                                        } // End nthDay: Last
                                    }
                                }
                            }
                        }

                        // Recurrence Type = 'Yearly' Sanjay Moghariya 16-10-2014
                        else if($repeatType == '5')
                        {	
                            $everyYears=$this->input->post('everyYears');
                            if($endTemplateType == '1') // Never
                            {
                                $this->obj->delete_calendar_record($cmid);
                                $this->endon_never_yearly($start_date,$total_inday,$everyYears,$event_data);
                            }
                            else if($endTemplateType == '2') // After n Occurance
                            {
                                $this->obj->delete_calendar_record($cmid);
                                $endCounter=$this->input->post('endCounter');
                                $update_id = $this->endon_after_n_occurance_yearly($start_date,$endCounter,$total_inday,$everyYears,$event_data);
                            }
                            else if($endTemplateType == '3') // End on any random date
                            {
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                $this->obj->delete_calendar_record($cmid);
                                $update_id = $this->endon_random_date_yearly($start_date,$endTemplateDate,$total_inday,$everyYears,$event_data);
                            }
                        }
                        
                        $idata['id']=$cmid;
                        $idata['modified_by'] = $this->admin_session['id'];
                        $idata['modified_date'] = date('Y-m-d H-i-s');
                        $update_id = $this->obj->update_record($idata);
                        // End New code
                    }
                    /*else
                    {
                        //echo 1;exit;
                        $idata['id']=$id;
                        $result=$this->obj->select_calendar_tran($id);
                        //pr($result[0]['calendar_id']);exit;
                        $idata['modified_by'] = $this->admin_session['id'];
                        $idata['modified_date'] = date('Y-m-d H-i-s');	
                        $update_id = $this->obj->update_record($idata);
                        $trdata['calendar_id']=$id;
                        //pr($trdata);
                        $update_id =$this->obj->update_record_tran($trdata);
                        //echo $this->db->last_query();exit;
                    }*/

                    if($update_id  == 0)
                    {
                        $msg="Appointment Rescheduled";
                        $ret['IsSuccess'] = true;
                        $ret['Msg'] = $msg;	
                    }
                    else
                    {
                        $ret['IsSuccess'] = false;	
                    }
                }
                else /*If recurrence is removed at edit time. Sanjay M. 10-09-2014*/
                {
                    $edit_flag = $this->input->post('edit_flag');
                    if(!empty($edit_flag))
                    {
                        $single['id']=$cal_id;
                        $single['calendar_id']=$id;
                        $single['event_title']=$this->input->post('title');
                        $single['event_notes'] = $this->input->post('Description');
                        $single['event_color'] = $this->input->post('event_color');
                        $single['event_start_date']=date('Y-m-d', strtotime($this->input->post('stpartdate')));
                        $single['event_start_time'] = $this->input->post('stparttime');
                        $single['event_end_date'] = date('Y-m-d', strtotime($this->input->post('etpartdate')));
                        $single['event_end_time'] = $this->input->post('etparttime');
                        $single['edit_flag'] = '1';
                        //pr($single);exit;	
                        if(!empty($task_result))
                        {
                            foreach($task_result as $task_row)
                            {
                                $single['id']=$task_row['cal_trans_id'];
                                $single['calendar_id'] = $task_row['id'];
                                $update_id = $this->obj->update_record_trandata($single);
                            }
                        } else {
                            $update_id = $this->obj->update_record_trandata($single);
                        }
                        //$update_id = $this->obj->update_record_trandata($single);
                        $ddata['id']=$cmid;
                        
                        $ddata['event_for'] = $idata['event_for'];
                        if($event_for == '2')
                            $ddata['assigned_user_id'] = $idata['assigned_user_id'];
                        else
                            $ddata['assigned_user_id'] = '';
                        $ddata['modified_by'] = $this->admin_session['id'];
                        $ddata['modified_date'] = date('Y-m-d H-i-s');
                        
                        if(!empty($task_result))
                        {
                            foreach($task_result as $task_row)
                            {
                                $ddata['event_title']=$this->input->post('title');
                                $ddata['start_date']=date('Y-m-d', strtotime($this->input->post('stpartdate')));
                                $ddata['start_time'] = $this->input->post('stparttime');
                                $ddata['end_date'] = date('Y-m-d', strtotime($this->input->post('etpartdate')));
                                $ddata['end_time'] = $this->input->post('etparttime');
                                $ddata['event_notes'] = $this->input->post('Description');
                                $ddata['event_color'] = $this->input->post('event_color');

                                $ddata['id'] = $task_row['id'];
                                $ddata['task_id']=$task_row['task_id'];	
				$ddata['event_inserted_type']='2';
				$ddata['task_user_id']=$task_row['task_user_id'];
                                $update_id = $this->obj->update_record($ddata);
                            }
                        } else {
                            $update_id = $this->obj->update_record($ddata);
                        }
                    }
                    else
                    {
                        $data_norepeat['id']=$ctid;
                        $data_norepeat['event_title']=$this->input->post('title');
                        $data_norepeat['event_notes'] = $this->input->post('Description');
                        $data_norepeat['event_color'] = $this->input->post('event_color');
                        $data_norepeat['event_start_date']=date('Y-m-d', strtotime($this->input->post('stpartdate')));
                        $data_norepeat['event_start_time'] = $this->input->post('stparttime');
                        $data_norepeat['event_end_date'] = date('Y-m-d', strtotime($this->input->post('etpartdate')));
                        $data_norepeat['event_end_time'] = $this->input->post('etparttime');
                        $update_id = $this->obj->update_record_trandata($data_norepeat);

                        $this->obj->delete_calendar_trans_norepeat($cmid,$ctid);
                        $idata['id'] = $cmid;
                        $idata['repeatType'] = 0;$idata['everyHours'] = 0;$idata['dailyType'] = 0;
                        $idata['everyDays'] = 0; $idata['everyYears'] = 0; $idata['everyWeeks'] = 0;$idata['everyWeekonMon'] = 0;
                        $idata['everyWeekonTue'] = 0;$idata['everyWeekonWed'] = 0;$idata['everyWeekonThu'] = 0;
                        $idata['everyWeekonFri'] = 0;$idata['everyWeekonSat'] = 0;$idata['everyWeekonSun'] = 0;
                        $idata['monthlyType'] = 0;$idata['monthDate'] = 0;$idata['monthCount'] = 0;$idata['nthDay'] = 0;
                        $idata['nthDate'] = 0;$idata['nthMonthCount'] = 0;$idata['endTemplateType'] = 0;
                        $idata['endCounter'] = 0;$idata['endTemplateDate'] = '';$idata['endTemplateTime'] = '';
                        $idata['status']='1';
                        $idata['modified_date'] = date('Y-m-d H-i-s');
                        $idata['modified_by'] = $this->admin_session['id'];

                        $this->obj->update_record($idata);
                    }
                    if($update_id  == 0)
                    {
                        $msg="Appointment Rescheduled";
                        $ret['IsSuccess'] = true;
                        $ret['Msg'] = $msg;	
                    }
                    else
                    {
                        $ret['IsSuccess'] = false;	
                    }
                }
            }
            else // Insert
            {
                if(!empty($this->admin_session))
                {
                    $idata['status']='1';
                    $idata['created_date'] = date('Y-m-d H:i:s');
                    $idata['created_by'] = $this->admin_session['id'];		
                }
                $insert_id=$this->obj->insert_record($idata);
                $trdata['calendar_id']=$insert_id;
        //	$this->obj->insert_calendar_tran($trdata);
                if(!empty($insert_id))
                {
                    $msg="Appointment Created";
                    $ret['Data'] = $insert_id;
                    $ret['IsSuccess'] = true;
                    $ret['Msg'] = $msg;	
                }
                else
                {
                    $ret['IsSuccess'] = false;	
                }
            }
        
            //echo $insert_id;exit;

            $data['endTemplateType']=$this->input->post('endTemplateType');
            if($endTemplateType == '2')
            {
                $data['endCounter']=$this->input->post('endCounter');
            }
            if($endTemplateType == '3')
            {
                $data['endTemplateDate']=date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                $data['endTemplateTime']=$this->input->post('endTemplateTime');
            }

            $data['start_date'] = date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
            $data['start_time'] = $this->input->post('stparttime');
            $data['end_date'] = date('Y-m-d',strtotime($this->input->post('etpartdate')));
            $data['end_time'] = $this->input->post('etparttime');

            $ifRepeat=$this->input->post('ifRepeat');
            $repeatType=$this->input->post('repeatType');
            $everyHours=$this->input->post('everyHours');
            $dailyType=$this->input->post('dailyType');
            $everyDays=$this->input->post('everyDays');
            $everyWeeks=$this->input->post('everyWeeks');

            $idata['ifRepeat']=$this->input->post('ifRepeat');

            if(empty($id)) // Insert
            {
                if(!empty($ifRepeat) && $ifRepeat=='1')
                {
                    $event_data['event_title']=$this->input->post('title');
                    $event_data['event_notes'] = $this->input->post('Description');
                    $event_data['event_color'] = $this->input->post('event_color');
                    $event_data['event_start_time'] = $this->input->post('stparttime');
                    $event_data['event_end_time'] = $this->input->post('etparttime');
                    $event_data['calendar_id']=$insert_id;

                    $inend=date_create(date('Y-m-d', strtotime($this->input->post('etpartdate'))));
                    $instar=date_create(date('Y-m-d',strtotime($this->input->post('stpartdate'))));
                    $indaydiff = date_diff($instar, $inend);
                    $total_inday=$indaydiff->format("%a");

                    $start_date= date('Y-m-d',strtotime($this->input->post('stpartdate'))); 
                    $end_date = date('Y-m-d',strtotime($this->input->post('etpartdate')));
                    $original_start_date = strtotime($start_date);
                    
                    if($repeatType=='2') // Daily
                    {	
                        $data['repeatType']=$this->input->post('repeatType');
                        if($dailyType == '1') // Every
                        {	
                            $everyDays=$this->input->post('everyDays');
                            if($data['endTemplateType'] == '1') //Never
                            {
                                $this->endon_never($start_date,$total_inday,$everyDays,$event_data);
                            }
                            else if($data['endTemplateType'] == '2') // After n occurance
                            {
                                $endCounter=$this->input->post('endCounter');
                                $this->endon_after_n_occurance($start_date, $endCounter, $total_inday, $everyDays, $event_data);
                            }
                            else if($data['endTemplateType']=='3') // End on random date
                            {
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                                $this->endon_random_date($start_date, $endTemplateDate, $total_inday, $everyDays, $event_data);
                            }
                        }

                        //
                        //Sanjay Moghariya 08-09-2014
                        //For Recurrence -> Daily -> Every Weekdays
                        //
                        else if($dailyType=='2')
                        {	
                            // If 'Never' selected in End Date
                            if($data['endTemplateType']=='1')
                            {
                                $this->endon_never_weekdays($start_date, $total_inday, $event_data, 'weekdays');
                            }
                            // If 'After' selected in End Date
                            else if($data['endTemplateType']=='2')
                            {
                                $endCounter=$this->input->post('endCounter');
                                $this->endon_after_n_occurance_weekdays($start_date, $endCounter, $total_inday, $event_data, 'weekdays');
                            }
                            //If 'End on' selected in End Date
                            else if($data['endTemplateType']=='3')
                            {
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                                $this->endon_random_date_weekdays($start_date, $endTemplateDate, $total_inday, $event_data, 'weekdays');
                            }
                        }

                       //
                       //    For Recurrence -> Daily -> Every Weekends
                       //
                        else if($dailyType=='3')
                        {	
                            // If 'Never' selected in End Date
                            if($data['endTemplateType']=='1')
                            {
                                $this->endon_never_weekdays($start_date, $total_inday, $event_data, 'weekends');
                            }
                            // If 'After' selected in End Date
                            else if($data['endTemplateType']=='2')
                            {
                                $endCounter=$this->input->post('endCounter');
                                $this->endon_after_n_occurance_weekdays($start_date, $endCounter, $total_inday, $event_data, 'weekends');
                            }
                            //If 'End on' selected in End Date
                            else if($data['endTemplateType']=='3')
                            {
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                                $this->endon_random_date_weekdays($start_date, $endTemplateDate, $total_inday, $event_data, 'weekends');
                            }
                        }
                    }
                    // Recurrence Type = 'Weekly' Sanjay Moghariya 12-09-2014
                    else if($repeatType == '3')
                    {
                        // If 'Never' selected in End Date (repeatType: Weekly)
                        if($data['endTemplateType']=='1')
                        {
                            $this->endon_never_weekly($start_date,$original_start_date,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                        }
                        // If 'After' selected in End Date (repeatType: Weekly)
                        else if($data['endTemplateType']=='2')
                        {
                            $endCounter=$this->input->post('endCounter');
                            $this->endon_after_n_occurance_weekly($start_date,$original_start_date,$endCounter,$total_inday,$everyWeeks,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                        }
                        // If 'End on' selected in End Date (repeatType: Weekly)
                        else if($data['endTemplateType']=='3')
                        {
                            $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                            $this->endon_random_date_weekly($start_date,$original_start_date,$everyWeeks,$endTemplateDate,$total_inday,$event_data,$everyWeekonSun,$everyWeekonMon,$everyWeekonTue,$everyWeekonWed,$everyWeekonThu,$everyWeekonFri,$everyWeekonSat);
                        }
                    }
                    // Recurrence Type = 'Monthly' Sanjay Moghariya 16-09-2014
                    else if($repeatType == '4')
                    {
                        //
                        //   For Recurrence -> Monthly -> monthDate and MonthCount Selected
                        //
                        if($monthlyType == '1')
                        {
                            if(!empty($monthDate) && !empty($monthCount))
                            {
                                // If 'Never' selected in End Date (repeatType: Monthly)
                                if($data['endTemplateType'] == '1')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_never_monthly_day($start_date,$original_start_date,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                                // If 'After' selected in End Date (repeatType: Monthly)
                                else if($data['endTemplateType'] == '2')
                                {
                                    $this->obj->delete_calendar_record($cmid);
                                    $endCounter=$this->input->post('endCounter');
                                    $this->endon_after_n_occurance_monthly_day($start_date,$original_start_date,$endCounter,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                                // If 'End on' selected in End Date (repeatType: Monthly)
                                else if($data['endTemplateType'] == '3')
                                {
                                    $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 
                                    $this->obj->delete_calendar_record($cmid);
                                    $this->endon_random_date_monthly_day($start_date,$original_start_date,$endTemplateDate,$total_inday,$event_data,$monthDate,$monthCount);
                                }
                            }
                        }
                        //
                        //   For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                        //
                        if($monthlyType == '2')
                        {
                            // If 'Never' selected in End Date (repeatType: Monthly)
                            if($data['endTemplateType']=='1')
                            {
                                // If nthDay, nthDate and nthMonthCount Selected
                                if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                {
                                    // nthDay: First
                                    if($nthDay == '1')
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,1);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                break;
                                            default :
                                                break;

                                        }
                                    } // End nthDay: First
                                    else if($nthDay == '2') // nthDay: Second
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,2);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                break;
                                            default :
                                                break;

                                        }
                                    } // End nthDay: Second
                                    else if($nthDay == '3') // nthDay: Third
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,3);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                break;
                                            default :
                                                break;

                                        }
                                    } // End nthDay: Third
                                    else if($nthDay == '4') // nthDay: Fourth
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,4);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                break;
                                            default :
                                                break;

                                        }
                                    } // End nthDay: Fourth
                                    else if($nthDay == '5') // nthDay: Last
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,5);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                break;
                                            default :
                                                break;

                                        }
                                    } // End nthDay: Last
                                }
                            } // End Monthly : 'Never'

                            // If 'After' selected in End Date (repeatType: Monthly)
                            if($data['endTemplateType']=='2')
                            {
                                // If nthDay, nthDate and nthMonthCount Selected
                                if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                {
                                    $endCounter=$this->input->post('endCounter');
                                    // nthDay: First
                                    if($nthDay == '1')
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,1);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'first','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: First
                                    else if($nthDay == '2') // nthDay: Second
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,2);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'second','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Second
                                    else if($nthDay == '3') // nthDay: Third
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,3);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'third','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Third
                                    else if($nthDay == '4') // nthDay: Fourth
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,4);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'fourth','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Fourth
                                    else if($nthDay == '5') // nthDay: Last
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,5);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,'last','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Last
                                }
                            }

                            // If 'End on' selected in End Date (repeatType: Monthly)
                            if($data['endTemplateType']=='3')
                            {
                                /*if($total_day < 0)
                                {
                                    $this->obj->delete_record($insert_id);
                                }*/
                                $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate'))); 

                                // If nthDay, nthDate and nthMonthCount Selected
                                if(!empty($nthDay) && !empty($nthDate) && !empty($nthMonthCount))
                                {
                                    // nthDay: First
                                    if($nthDay == '1')
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,1);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'first','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: First
                                    else if($nthDay == '2') // nthDay: Second
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,2);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'second','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Second
                                    else if($nthDay == '3') // nthDay: Third
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,3);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'third','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Third
                                    else if($nthDay == '4') // nthDay: Fourth
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,4);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'fourth','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Fourth
                                    else if($nthDay == '5') // nthDay: Last
                                    {
                                        switch($nthDate)
                                        {
                                            case '1': // nthDate: Day
                                                $this->monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,5);
                                                break;
                                            case '2': // nthDate: Weekday
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekday');
                                                break;
                                            case '3': // nthDate: Weekend
                                                $this->monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','weekend');
                                                break;
                                            case '4': // nthDate: Monday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','mon');
                                                break;
                                            case '5': // nthDate: Tuesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','tue');
                                                break;
                                            case '6': // nthDate: Wednesday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','wed');
                                                break;
                                            case '7': // nthDate: Thursday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','thu');
                                                break;
                                            case '8': // nthDate: Friday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','fri');
                                                break;
                                            case '9': // nthDate: Saturday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sat');
                                                break;
                                            case '10': // nthDate: Sunday
                                                $this->monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,'last','sun');
                                                break;
                                            default :
                                                break;
                                        }
                                    } // End nthDay: Last
                                }
                            }
                        }
                        ////***
                        ///   End For Recurrence -> Monthly -> nthDay, nthDate and nthMonthCount Selected
                       //// ***
                    }
                    // Recurrence Type = 'Yearly' Sanjay Moghariya 16-10-2014
                    else if($repeatType=='5')
                    {	
                        $data['repeatType']=$this->input->post('repeatType');

                        $everyYears=$this->input->post('everyYears');
                        if($data['endTemplateType'] == '1') //Never
                        {
                            $this->endon_never_yearly($start_date,$total_inday,$everyYears,$event_data);
                        }
                        else if($data['endTemplateType'] == '2') // After n occurance
                        {
                            $endCounter=$this->input->post('endCounter');
                            $this->endon_after_n_occurance_yearly($start_date, $endCounter, $total_inday, $everyYears, $event_data);
                        }
                        else if($data['endTemplateType']=='3') // End on random date
                        {
                            $endTemplateDate = date('Y-m-d',strtotime($this->input->post('endTemplateDate')));
                            $this->endon_random_date_yearly($start_date, $endTemplateDate, $total_inday, $everyYears, $event_data);
                        }
                    }                
                    // End Sanjay Moghariya
                }
                else
                {
                    $event_data['calendar_id']=$insert_id;
                    $event_data['event_title']=$this->input->post('title');
                    $event_data['event_notes'] = $this->input->post('Description');
                    $event_data['event_color'] = $this->input->post('event_color');
                    $event_data['event_start_date']=date('Y-m-d', strtotime($this->input->post('stpartdate')));
                    $event_data['event_start_time'] = $this->input->post('stparttime');
                    $event_data['event_end_date'] = date('Y-m-d', strtotime($this->input->post('etpartdate')));
                    $event_data['event_end_time'] = $this->input->post('etparttime');
                    $this->obj->insert_calendar_tran($event_data);
                    /*if($event_for == '2' && !empty($assigned_user_id))
                    {
                        $assigned_user_id = implode(',',$assigned_user_id);
                        
                        $i = 0;
                        foreach($assigned_user_id as $row)
                        {
                            if($i == 0) {
                                $this->obj->insert_calendar_tran($event_data);
                            } else {
                                $insert_id = $insert_id + 1;
                                $event_data['calendar_id']=$insert_id;
                                $this->obj->insert_calendar_tran($event_data);
                            }
                            $i++;
                        }
                    } else {
                        $this->obj->insert_calendar_tran($event_data);
                    }*/
                }
            }
        }
        else
        {
            $msg="You have no rights to edit this Appointment !";
            $ret['IsSuccess'] = true;
            $ret['Msg'] = $msg;	
        }
        echo json_encode($ret);
    }
        
    /*
        @Description: Function for insert Recurrence event (End Date: Never (Daily))
        @Author     : Sanjay Moghariya
        @Input      : start date, total days between start and end date, repeat event on every n day, insert data array
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_never($start_date,$total_inday,$everyDays,$event_data)
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        for($i=0;$i<$total_day;$i=$i+$everyDays)
        {
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

            $this->obj->insert_calendar_tran($event_data);
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyDays.' days'));
            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (End Date: After n occurance (Daily))
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, occurance, total days between start and end date, repeat event on every n day, insert data array
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_after_n_occurance($start_date,$endCounter,$total_inday,$everyDays,$event_data)
    {
        for($i=0;$i<$endCounter;$i++)
        {
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d', strtotime($start_date. ' + '.$total_inday.' days'));

            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyDays.' days'));

            $this->obj->insert_calendar_tran($event_data);
        }
    }

    /*
        @Description: Function for insert Recurrence event. (End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, total day, repeat on every n month, end date, total days between start and end date, repeat event on every n day , insert data array
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_random_date($start_date,$endTemplateDate,$total_inday,$everyDays,$event_data,$event_type='')
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");

        for($i=0;$i<=$total_day;$i=$i+$everyDays)
        {
            if(strtotime($endTemplateDate) < strtotime($start_date))
            {
                break;
            }
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

            $this->obj->insert_calendar_tran($event_data);
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyDays.' days'));
        }
    }
    
    /*
        @Description: Function for insert Recurrence event (End Date: Never (Yearly))
        @Author     : Sanjay Moghariya
        @Input      : start date, total days between start and end date, repeat event on every n year, insert data array
        @Output     : Insert calendar Event
        @Date       : 16-10-2014
    */
    function endon_never_yearly($start_date,$total_inday,$everyYears,$event_data)
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        for($i=0;$i<$total_day;$i=$i+$everyYears)
        {
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

            $this->obj->insert_calendar_tran($event_data);
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyYears.' years'));
            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (End Date: After n occurance (Yearly))
        @Author     : Sanjay Moghariya
        @Input      : start date, repeat occurance, total days between start and end date, repeat event on every n years, insert data array
        @Output     : Insert calendar Event
        @Date       : 16-10-2014
    */
    function endon_after_n_occurance_yearly($start_date,$endCounter,$total_inday,$everyYears,$event_data)
    {
        for($i=0;$i<$endCounter;$i++)
        {
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d', strtotime($start_date. ' + '.$total_inday.' days'));

            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyYears.' years'));

            $this->obj->insert_calendar_tran($event_data);
        }
    }

    /*
        @Description: Function for insert Recurrence event. (End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, end date, total days between start and end date, repeat event on every n years , insert data array
        @Output     : Insert calendar Event
        @Date       : 16-10-2014
    */
    function endon_random_date_yearly($start_date,$endTemplateDate,$total_inday,$everyYears,$event_data)
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");

        for($i=0;$i<=$total_day;$i=$i+$everyYears)
        {
            if(strtotime($endTemplateDate) < strtotime($start_date))
            {
                break;
            }
            $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
            $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

            $this->obj->insert_calendar_tran($event_data);
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$everyYears.' years'));
        }
    }

    /*
        @Description: Function for insert Recurrence event (End Date: Never (Daily))
        @Author     : Sanjay Moghariya
        @Input      : start date, total day (after 5 year),  end date, total days between start and end date, insert data , event type(Weekdays or Weekends)
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_never_weekdays($start_date,$total_inday,$event_data,$event_type='')
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        for($i=0;$i<$total_day;$i++)
        {
            if($event_type == 'weekdays')
            {    
                if((date('w', strtotime($start_date)) != 0) &&  (date('w', strtotime($start_date)) != 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                }
            }
            else if($event_type == 'weekends')
            {
                if((date('w', strtotime($start_date)) == 0) ||  (date('w', strtotime($start_date)) == 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                }
            }

            $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (End Date: After n occurance (Daily))
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, occurance, total days between start and end date, repeat event on every n day, insert data array
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_after_n_occurance_weekdays($start_date,$endCounter,$total_inday,$event_data,$event_type='')
    {
        for($i=0;$i<$endCounter;)
        {
            if($event_type == 'weekdays')
            {    
                if((date('w', strtotime($start_date)) != 0) &&  (date('w', strtotime($start_date)) != 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d', strtotime($start_date. ' + '.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                    $i++;
                }
            }
            else if($event_type == 'weekends')
            {
                if((date('w', strtotime($start_date)) == 0) ||  (date('w', strtotime($start_date)) == 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                    $i++;
                }
            }
            $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
        }
    }

    /*
        @Description: Function for insert Recurrence event. (End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, total day, repeat on every n month, end date, total days between start and end date, repeat event on every n day , insert data array
        @Output     : Insert calendar Event
        @Date       : 22-09-2014
    */
    function endon_random_date_weekdays($start_date,$endTemplateDate,$total_inday,$event_data,$event_type='')
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        
        for($i=0;$i<=$total_day;$i++)
        {
            if(strtotime($endTemplateDate) < strtotime($start_date))
            {
                break;
            }
            if($event_type == 'weekdays')
            {    
                if((date('w', strtotime($start_date)) != 0) &&  (date('w', strtotime($start_date)) != 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                }
            }
            else if($event_type == 'weekends')
            {
                if((date('w', strtotime($start_date)) == 0) ||  (date('w', strtotime($start_date)) == 6))
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                }
            }
            $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
        }
    }

    /*
        @Description: Function for insert Weekly Recurrence event. (End Date: Never)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, end date (after 5 year date), total days between start and end date, repeat event on every n week , insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 23-09-2014
    */
    function endon_never_weekly($start_date,$original_start_date,$total_inday,$everyWeeks,$event_data,$everyWeekonSun='',$everyWeekonMon='',$everyWeekonTue='',$everyWeekonWed='',$everyWeekonThu='',$everyWeekonFri='',$everyWeekonSat='')
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        $start = (date('w', $original_start_date) == 1) ? $original_start_date : strtotime('last monday', $original_start_date);
        $start_week = date('Y-m-d', $start);
        $end_week = date('Y-m-d', strtotime('next sunday', $start));
        $start_date = $start_week;

        for($i=1;$i<=7;$i++)
        {
            if(strtotime($start_date) >= $original_start_date)
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

                if(!empty($everyWeekonSun))
                {
                    if((date('w', strtotime($start_date)) == 0)) // 0: for Sunday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonMon))
                {
                    if((date('w', strtotime($start_date)) == 1)) // 1: for Monday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonTue))
                {
                    if((date('w', strtotime($start_date)) == 2)) // 2: for Tuesday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonWed))
                {
                    if((date('w', strtotime($start_date)) == 3)) // 3: for Wednesday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonThu))
                {
                    if((date('w', strtotime($start_date)) == 4)) // 4: for Thursday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonFri))
                {
                    if((date('w', strtotime($start_date)) == 5)) // 5: for Friday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonSat))
                {
                    if((date('w', strtotime($start_date)) == 6)) // 6: for Saturday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }

                if($i == 7)
                {
                    $start_date = strtotime(date('Y-m-d', strtotime($start_date)));
                    $start = (date('w', $start_date) == 1) ? $start_date : strtotime('last monday', $start_date);
                    $start_week = date('Y-m-d', $start);
                    $start_date = date('Y-m-d', strtotime($start_week. ' + '.$everyWeeks.' weeks'));

                    $i=0;
                }
                else
                {
                    $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
                }
                if(strtotime($end_yeardt) < strtotime($start_date))
                {
                    break;
                }
            } else {
                $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (End Date: After n occurance (Weekly))
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, occurance, total days between start and end date, repeat event on every n weeks, insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 23-09-2014
    */
    function endon_after_n_occurance_weekly($start_date,$original_start_date,$endCounter,$total_inday,$everyWeeks,$event_data,$everyWeekonSun='',$everyWeekonMon='',$everyWeekonTue='',$everyWeekonWed='',$everyWeekonThu='',$everyWeekonFri='',$everyWeekonSat='')
    {
        $start = (date('w', $original_start_date) == 1) ? $original_start_date : strtotime('last monday', $original_start_date);
        $start_week = date('Y-m-d', $start);
        $end_week = date('Y-m-d', strtotime('next sunday', $start));
        $start_date = $start_week;
        for($i=0;$i<$endCounter;)
        {
            for($wday=1;$wday<=7;$wday++)
            {
                if(strtotime($start_date) >= $original_start_date)
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonSun))
                        {
                            if((date('w', strtotime($start_date)) == 0)) /* 0: for Sunday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonMon))
                        {
                            if((date('w', strtotime($start_date)) == 1)) /* 1: for Monday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonTue))
                        {
                            if((date('w', strtotime($start_date)) == 2)) /* 2: for Tuesday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }

                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonWed))
                        {
                            if((date('w', strtotime($start_date)) == 3)) /* 3: for Wednesday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonThu))
                        {
                            if((date('w', strtotime($start_date)) == 4)) /* 4: for Thursday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonFri))
                        {
                            if((date('w', strtotime($start_date)) == 5)) /* 5: for Friday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i < $endCounter)
                    {
                        if(!empty($everyWeekonSat))
                        {
                            if((date('w', strtotime($start_date)) == 6)) /* 6: for Saturday*/
                            {
                                $this->obj->insert_calendar_tran($event_data);
                                $i++;
                            }
                        }
                    }
                    if($i > $endCounter)
                    {
                        break;
                    }
                }
                $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
            }
            $start_date = strtotime(date('Y-m-d', strtotime($start_date. ' -1 day')));
            $start = (date('w', $start_date) == 1) ? $start_date : strtotime('last monday', $start_date);
            $start_week = date('Y-m-d', $start);
            $start_date = date('Y-m-d', strtotime($start_week. ' + '.$everyWeeks.' weeks'));
            if($i > $endCounter)
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event. (End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, repeat on every n weeks, end date, total days between start and end date, insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 23-09-2014
    */
    function endon_random_date_weekly($start_date,$original_start_date,$everyWeeks,$endTemplateDate,$total_inday,$event_data,$everyWeekonSun='',$everyWeekonMon='',$everyWeekonTue='',$everyWeekonWed='',$everyWeekonThu='',$everyWeekonFri='',$everyWeekonSat='')
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        
        $start = (date('w', $original_start_date) == 1) ? $original_start_date : strtotime('last monday', $original_start_date);
        $start_week = date('Y-m-d', $start);
        $end_week = date('Y-m-d', strtotime('next sunday', $start));
        $start_date = $start_week;

        for($i=1;$i<=7;$i++)
        {
            if(strtotime($start_date) >= $original_start_date)
            {
                if(strtotime($start_date) > strtotime($endTemplateDate))
                {
                    break;
                }
                $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

                if(!empty($everyWeekonSun))
                {
                    if((date('w', strtotime($start_date)) == 0)) // 0: for Sunday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonMon))
                {
                    if((date('w', strtotime($start_date)) == 1)) // 1: for Monday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonTue))
                {
                    if((date('w', strtotime($start_date)) == 2)) // 2: for Tuesday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonWed))
                {
                    if((date('w', strtotime($start_date)) == 3)) // 3: for Wednesday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonThu))
                {
                    if((date('w', strtotime($start_date)) == 4)) // 4: for Thursday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonFri))
                {
                    if((date('w', strtotime($start_date)) == 5)) // 5: for Friday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }
                if(!empty($everyWeekonSat))
                {
                    if((date('w', strtotime($start_date)) == 6)) // 6: for Saturday
                    {
                        $this->obj->insert_calendar_tran($event_data);
                    }
                }

                if($i == 7)
                {
                    $start_date = strtotime(date('Y-m-d', strtotime($start_date)));
                    $start = (date('w', $start_date) == 1) ? $start_date : strtotime('last monday', $start_date);
                    $start_week = date('Y-m-d', $start);
                    $start_date = date('Y-m-d', strtotime($start_week. ' + '.$everyWeeks.' weeks'));

                    $i=0;
                }
                else
                {
                    $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
                }
            } else {
                $start_date = date('Y-m-d', strtotime($start_date. ' +1 day'));
            }
        }


    }

    /*
        @Description: Function for insert Monthly Recurrence event. (End Date: Never)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, end date (after 5 year date), total days between start and end date, repeat event on every n week , insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 24-09-2014
    */
    function endon_never_monthly_day($start_date,$original_start_date,$total_inday,$event_data,$monthDate,$monthCount)
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        for($i=0;$i<$total_day;$i++)
        {
            $num_days = date('t',strtotime($start_date));

            if($monthDate <= $num_days)
            {
                $stm = date('m',strtotime($start_date));
                $sty = date('Y',strtotime($start_date));
                $std = $sty.'-'.$stm.'-'.$monthDate;
                $start_date = date('Y-m-d', strtotime($std));

                if(strtotime($start_date) >= $original_start_date)
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

                    $this->obj->insert_calendar_tran($event_data);

                    if(strtotime($end_yeardt) < strtotime($start_date))
                    {
                        break;
                    }
                }
            }
            $start_date = date('Y-m-1', strtotime($start_date));
            $start_date = date('Y-m-1', strtotime($start_date. ' + '.$monthCount.' months'));

            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Monthly Recurrence event. (End Date: After)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, end date (after 5 year date), total days between start and end date, repeat event on every n week , insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 24-09-2014
    */
    function endon_after_n_occurance_monthly_day($start_date,$original_start_date,$endCounter,$total_inday,$event_data,$monthDate,$monthCount)
    {
        for($i=0;$i<$endCounter;)
        {
            $num_days = date('t',strtotime($start_date));

            if($monthDate <= $num_days)
            {
                $stm = date('m',strtotime($start_date));
                $sty = date('Y',strtotime($start_date));
                $std = $sty.'-'.$stm.'-'.$monthDate;
                $start_date = date('Y-m-d', strtotime($std));

                if(strtotime($start_date) >= $original_start_date)
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                    $i++;
                }
            }
            $start_date = date('Y-m-1', strtotime($start_date));
            $start_date = date('Y-m-1', strtotime($start_date. ' + '.$monthCount.' months'));
        }
    }

    /*
        @Description: Function for insert Monthly Recurrence event. (End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, end date (after 5 year date), total days between start and end date, repeat event on every n week , insert data array, event day(sunday to saturday)
        @Output     : Insert calendar Event
        @Date       : 24-09-2014
    */
    function endon_random_date_monthly_day($start_date,$original_start_date,$endTemplateDate,$total_inday,$event_data,$monthDate,$monthCount)
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        for($i=0;$i<=$total_day;$i++)
        {
            if(strtotime($start_date) > strtotime($endTemplateDate))
            {
                break;
            }
            $num_days = date('t',strtotime($start_date));

            if($monthDate <= $num_days)
            {
                $stm = date('m',strtotime($start_date));
                $sty = date('Y',strtotime($start_date));
                $std = $sty.'-'.$stm.'-'.$monthDate;
                $start_date = date('Y-m-d', strtotime($std));

                if(strtotime($start_date) >= $original_start_date)
                {
                    $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                    $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                    $this->obj->insert_calendar_tran($event_data);
                }
            }

            $start_date = date('Y-m-1', strtotime($start_date));
            $start_date = date('Y-m-1', strtotime($start_date. ' + '.$monthCount.' months'));
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> First -> Day -> End Date: Never)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day (after 5 year), repeat on every n month, end date, total days between start and end date, insert data array, day number
        @Output     : Insert calendar Event
        @Date       : 17-09-2014
    */
    function monthly_nth_day_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,$nth_day)
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        
        if($nth_day == 5) {
            $start_date = date('Y-m-t', strtotime($start_date)); // Last date
        } else {
            $start_date = date('Y-m-'.$nth_day, strtotime($start_date));
        }
        for($i=0;$i<=$total_day;$i++)
        {
            if(strtotime($start_date) >= $original_start_date)
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));

                $this->obj->insert_calendar_tran($event_data);
                //$start_date = date('Y-m-d', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            if($nth_day == 5) {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-t', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            } else {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-'.$nth_day, strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event. (Monthly -> First -> Day -> End Date: After)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, repeat after n occurance, repeat on every n month, total days between start and end date, insert data array, day number
        @Output     : Insert calendar Event
        @Date       : 17-09-2014
    */
    function monthly_nth_day_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,$nth_day)
    {
        if($nth_day == 5) {
            $start_date = date('Y-m-t', strtotime($start_date)); // Last date
        } else {
            $start_date = date('Y-m-'.$nth_day, strtotime($start_date));
        }
        for($i=0;$i<$endCounter;)
        {
            if(strtotime($start_date) >= $original_start_date)
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                $this->obj->insert_calendar_tran($event_data);
                $i++;
            }
            if($nth_day == 5) {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-t', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            } else {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-'.$nth_day, strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event. (Monthly -> First -> Day -> End Date: End on)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, repeat on every n month, end date, total days between start and end date, insert data array
        @Output     : Insert calendar Event
        @Date       : 17-09-2014
    */
    function monthly_nth_day_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,$nth_day)
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        
        if($nth_day == 5) {
            $start_date = date('Y-m-t', strtotime($start_date)); // Last date
        } else {
            $start_date = date('Y-m-'.$nth_day, strtotime($start_date));
        }
        for($i=0;$i<=$total_day;$i++)
        {
            if(strtotime($start_date) >= $original_start_date)
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($start_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($start_date.' +'.$total_inday.' days'));
                $this->obj->insert_calendar_tran($event_data);
            }
            if($nth_day == 5) {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-t', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            } else {
                $start_date = date('Y-m-1', strtotime($start_date));
                $start_date = date('Y-m-'.$nth_day, strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            if(strtotime($start_date) > strtotime($endTemplateDate))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth -> Weekday -> End Date: Never)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day (after 5 year), repeat on every n month, end date, total days between start and end date, insert data array, nthday val(First,second,third, forth,last)
        @Output     : Insert calendar Event
        @Date       : 19-09-2014
    */
    function monthly_nth_weekday_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,$nth_day,$event_type='')
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<=$total_day;$i++)
        {
            $weekday = array();

            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));
            
            if($event_type == 'weekday')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' mon of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' tue of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' wed of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' thu of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' fri of '.$month.' '.$year));
            }
            else if($event_type == 'weekend')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sat of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sun of '.$month.' '.$year));
            }
            sort($weekday);
            
            if(!empty($weekday))
            {
                foreach($weekday as $row)
                {
                    if(!empty($row) && $row != '0000-00-00' && $row != '1970-01-01')
                    {
                        if(strtotime($row) >= $original_start_date)
                        {
                            if(strtotime($end_yeardt) < strtotime($row))
                            {
                                break;
                            }
                            $event_data['event_start_date']=date('Y-m-d', strtotime($row));
                            $event_data['event_end_date'] = date('Y-m-d',strtotime($row.' +'.$total_inday.' days'));
                            $this->obj->insert_calendar_tran($event_data);
                        }
                    }
                }
            }
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$nthMonthCount.' months'));

            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth day -> Weekday -> End Date: After n dates)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, repeat till n occurance, repeat on every n month, total days between start and end date, insert data array, nthday val(First,second,third, forth,last)
        @Output     : Insert calendar Event
        @Date       : 19-09-2014
    */
    function monthly_nth_weekday_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,$nth_day,$event_type='')
    {
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<$endCounter;)
        {
            $weekday = array();

            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));

            if($event_type == 'weekday')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' mon of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' tue of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' wed of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' thu of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' fri of '.$month.' '.$year));
            }
            else if($event_type == 'weekend')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sat of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sun of '.$month.' '.$year));
            }
            sort($weekday);

            if(!empty($weekday))
            {
                foreach($weekday as $row)
                {
                    if(!empty($row) && $row != '0000-00-00' && $row != '1970-01-01')
                    {
                        if(strtotime($row) >= $original_start_date)
                        {
                            if($i < $endCounter)
                            {
                                $event_data['event_start_date']=date('Y-m-d', strtotime($row));
                                $event_data['event_end_date'] = date('Y-m-d',strtotime($row.' +'.$total_inday.' days'));
                                $this->obj->insert_calendar_tran($event_data);
                                //$i++;
                            }
                        }
                    }
                }
                $i++;
            }
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$nthMonthCount.' months'));
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth day -> Weekday -> End Date: After n dates)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, repeat on every n month, end date, total days between start and end date, insert data array, nthday val(First,second,third, forth,last)
        @Output     : Insert calendar Event
        @Date       : 19-09-2014
    */
    function monthly_nth_weekday_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,$nth_day,$event_type='')
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<=$total_day;$i++)
        {
            $weekday = array();

            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));

            if($event_type == 'weekday')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' mon of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' tue of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' wed of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' thu of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' fri of '.$month.' '.$year));
            }
            else if($event_type == 'weekend')
            {
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sat of '.$month.' '.$year));
                $weekday[] = date('Y-m-d', strtotime($nth_day.' sun of '.$month.' '.$year));
            }
            sort($weekday);

            if(!empty($weekday))
            {
                foreach($weekday as $row)
                {
                    if(!empty($row) && $row != '0000-00-00' && $row != '1970-01-01')
                    {
                        if(strtotime($row) >= $original_start_date)
                        {
                            if(strtotime($row) > strtotime($endTemplateDate))
                            {
                                break;
                            }
                            $event_data['event_start_date']=date('Y-m-d', strtotime($row));
                            $event_data['event_end_date'] = date('Y-m-d',strtotime($row.' +'.$total_inday.' days'));
                            $this->obj->insert_calendar_tran($event_data);
                        }
                    }
                }
            }
            $start_date = date('Y-m-d', strtotime($start_date. ' + '.$nthMonthCount.' months'));

            if(strtotime($start_date) > strtotime($endTemplateDate))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth day -> Monday to Sunday -> End Date: Never)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day (after 5 year), repeat on every n month, end date, total days between start and end date, insert data array,nthday val(First,second,third, forth,last), day name (0:Sunday,1:Monday,.. 6:Saturday)
        @Output     : Insert calendar Event
        @Date       : 18-09-2014
    */
    function monthly_nth_mon_sun_never($start_date,$original_start_date,$nthMonthCount,$total_inday,$event_data,$nth_day,$dayname)
    {
        $end_yeardt=date('Y-m-d', strtotime('+5 year', strtotime($start_date)));
        $end=date_create(date('Y-m-d', strtotime('+5 year', strtotime($start_date))));
        $star_date1=date_create(date('Y-m-d',strtotime($start_date)));
        $diff=date_diff($star_date1,$end);
        $total_day=$diff->format("%a");
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<=$total_day;$i++)
        {
            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));

            //$first_day_date = $this->get_first_month_day($month,$year,$daynumber);
            $first_day_date = date('Y-m-d', strtotime($nth_day.' '.$dayname.' of '.$month.' '.$year));

            if(strtotime($first_day_date) < $original_start_date)
            {
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            else 
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($first_day_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($first_day_date.' +'.$total_inday.' days'));

                $this->obj->insert_calendar_tran($event_data);
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            if(strtotime($end_yeardt) < strtotime($start_date))
            {
                break;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth day -> Monday to Sunday -> End Date: After n dates)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, repeat till n occurance, repeat on every n month, total days between start and end date, insert data array, day number (0:Sunday,1:Monday,.. 6:Saturday)
        @Output     : Insert calendar Event
        @Date       : 18-09-2014
    */
    function monthly_nth_mon_sun_after($start_date,$original_start_date,$endCounter,$nthMonthCount,$total_inday,$event_data,$nth_day,$dayname)
    {
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<$endCounter;)
        {
            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));
            //$first_day_date = $this->get_first_month_day($month,$year,$daynumber);
            $first_day_date = date('Y-m-d', strtotime($nth_day.' '.$dayname.' of '.$month.' '.$year));

            if(strtotime($first_day_date) < $original_start_date)
            {
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            else 
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($first_day_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($first_day_date.' +'.$total_inday.' days'));

                $this->obj->insert_calendar_tran($event_data);
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
                $i++;
            }
        }
    }

    /*
        @Description: Function for insert Recurrence event (Monthly -> nth day -> Monday to Sunday -> End Date: After n dates)
        @Author     : Sanjay Moghariya
        @Input      : start date, original start date, total day, repeat on every n month, end date, total days between start and end date, insert data array , day number (0:Sunday,1:Monday,.. 6:Saturday)
        @Output     : Insert calendar Event
        @Date       : 18-09-2014
    */
    function monthly_nth_mon_sun_endon($start_date,$original_start_date,$nthMonthCount,$endTemplateDate,$total_inday,$event_data,$nth_day,$dayname)
    {
        $start = date_create(date('Y-m-d',strtotime($start_date)));
        $end = date_create(date('Y-m-d',strtotime($endTemplateDate))); 
        $diff=date_diff($start,$end);
        $total_day=$diff->format("%a");
        $start_date = date('Y-m-1',strtotime($start_date));
        for($i=0;$i<=$total_day;$i++)
        {

            $month = date('M',strtotime($start_date));
            $year = date('Y',strtotime($start_date));
            //$first_day_date = $this->get_first_month_day($month,$year,$daynumber);
            $first_day_date = date('Y-m-d', strtotime($nth_day.' '.$dayname.' of '.$month.' '.$year));

            if(strtotime($first_day_date) < $original_start_date)
            {
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            else 
            {
                $event_data['event_start_date']=date('Y-m-d', strtotime($first_day_date));
                $event_data['event_end_date'] = date('Y-m-d',strtotime($first_day_date.' +'.$total_inday.' days'));

                $this->obj->insert_calendar_tran($event_data);
                $start_date = date('Y-m-1', strtotime($start_date. ' + '.$nthMonthCount.' months'));
            }
            if(strtotime($start_date) > strtotime($endTemplateDate))
            {
                break;
            }
        }
    }

    /*
        @Description: Function update appointment while rescheduled
        @Author: Niral Patel
        @Input: - id,dates
        @Output: - update data
        @Date: 22-05-2014
    */
    function update_appointment()
    {
	//pr($_POST);exit;
	$id=$this->input->post('calendarId');
        $flag = '';$is_task = 0; $task_id = 0;
        $task_result = array();
        
        $table ="calendar_repeat_trans";
        $fields = array('id,calendar_id');
        $match = array("id"=>$id);
        $transaction_result = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);

        $table ="calendar_master";
        $fields = array('task_user_id,task_id,created_by');
        $match = array("id"=>$transaction_result[0]['calendar_id'],'created_by'=>$this->admin_session['id']);
        $transaction_result1 = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);

        /////// This loop check for event created and event for///////////
        for($i=0;$i < count($transaction_result1);$i++)
        {
            if($transaction_result1[$i]['task_user_id'] > 0)
            {
                if(($transaction_result1[$i]['task_user_id'] == $this->admin_session['id']) OR ($transaction_result1[$i]['created_by'] == $this->admin_session['id']))
                {
                    $task_id = $transaction_result1[$i]['task_id'];
                    if(!empty($task_id))
                    {
                        $table ="calendar_master cm";
                        $fields = array('cm.id,cm.task_user_id,cm.task_id,cm.created_by,crt.id as cal_trans_id');
                        $match = array("task_id"=>$task_id);
                        $join_tables = array('calendar_repeat_trans as crt' => 'cm.id = crt.calendar_id');
                        //$data['event'] = $this->obj->getmultiple_tables_records1($table,$fields,$join_tables,'left','','','','','','','','',$where);
                        $task_result = $this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match);
                    }
                    $flag = 1;
                    break;
                }
                else
                {
                    $flag = '';
                }
            }
            else
            {
                $flag = 1;
                break;
            }
        }
        if($flag == 1 || empty($id))
        {
            $st = $this->input->post('CalendarStartTime');
            $start_date=date('Y-m-d',strtotime($st));
            $start_time=date('H:i:s',strtotime($st));

            $et = $this->input->post('CalendarEndTime');
            $end_date=date('Y-m-d',strtotime($et));
            $end_time=date('H:i:s',strtotime($et));

            $match = array('crt.id'=>$id);
            $table = "calendar_repeat_trans as crt";
            $fields = array('crt.id as cal_tran_id','crt.calendar_id,cm.email_time_before','cm.email_time_type','cm.popup_time_before','cm.popup_time_type','cm.is_email','cm.is_popup','cm.start_date','cm.start_time');
            $join_tables = array(
                'calendar_master as cm' => 'cm.id = crt.calendar_id'
            );
            $group_by='cm.id';
            $datalist=$this->obj->getmultiple_tables_records($table,$fields,$join_tables,'left','',$match,'=');

            //pr($datalist);exit;
            $stdt=php2MySqlTime(js2PhpTime($st));
            $et=php2MySqlTime(js2PhpTime($et));

            if(!empty($datalist))
            {
                try
                {
                    $idata['id']=$datalist[0]['calendar_id'];
                    $idata['start_date']=$start_date;
                    $idata['start_time']=$start_time;
                    $idata['end_date']=$end_date;
                    $idata['end_time']=$end_time;

                    $is_email = $datalist[0]['is_email'];
                    $is_popup = $datalist[0]['is_popup'];

                    if(!empty($is_email))
                    {
                        $idata['is_email'] = '1';
                        $idata['email_time_before'] = $datalist[0]['email_time_before'];
                        $idata['email_time_type'] = $datalist[0]['email_time_type'];
                    }
                    if(!empty($is_popup))
                    {
                        $idata['is_popup'] = '1';
                        $idata['popup_time_before'] = $datalist[0]['popup_time_before'];
                        $idata['popup_time_type'] = $datalist[0]['popup_time_type'];
                    }

                    if(!empty($is_email))
                    {
                        //echo "1";
                        if(!empty($idata['email_time_before']))
                        {

                            if(!empty($idata['email_time_type']) && $idata['email_time_type']=='1')
                            {
                                //echo $cdata['task_date'].$cdata['email_time_before']."<br>";
                                $counttype='Hours';
                                $newtaskdate = date($this->config->item('log_date_format'),strtotime($idata['start_date']." ".$idata['start_time']." - ".$idata['email_time_before']." ".$counttype));
                                $idata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	

                            }
                            if(!empty($idata['email_time_type']) && $idata['email_time_type']=='2')
                            {
                                $counttype='Days';
                                $newtaskdate = date($this->config->item('common_date_format'),strtotime($idata['start_date']." ".$idata['start_time']." - ".$idata['email_time_before']." ".$counttype));
                                $idata['reminder_email_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate));	
                            }	

                        }
                    }

                    if(!empty($is_popup))
                    {

                        if(!empty($idata['popup_time_before']))
                        {

                            if(!empty($idata['popup_time_type']) && $idata['popup_time_type']=='1')
                            {
                                $counttype='Hours';
                                $newtaskdate1 = date($this->config->item('log_date_format'),strtotime($idata['start_date']." ".$idata['start_time']."- ".$idata['popup_time_before']." ".$counttype));
                                $idata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	

                            }
                            if(!empty($idata['popup_time_type'])&& $idata['popup_time_type']=='2')
                            {
                                $counttype='Days';
                                $newtaskdate1 = date($this->config->item('common_date_format'),strtotime($idata['start_date']." ".$idata['start_time']."- ".$idata['popup_time_before']." ".$counttype));
                                $idata['reminder_popup_date'] = date('Y-m-d H:i:s',strtotime($newtaskdate1));	
                            }
                        }
                    }

                    //pr($idata);exit;

                    $cdata['edit_flag'] = '1';
                    $cdata['id']=$datalist[0]['cal_tran_id'];
                    $cdata['event_start_date']=$start_date;
                    $cdata['event_start_time']=$start_time;
                    $cdata['event_end_date']=$end_date;
                    $cdata['event_end_time']=$end_time;
                    if(!empty($task_result))
                    {
                        foreach($task_result as $task_row)
                        {
                            $idata['id'] = $task_row['id'];
                            $update_id = $this->obj->update_record($idata);
                            $cdata['id'] = $task_row['cal_trans_id'];
                            $this->obj->update_record1($cdata);
                        }
                    } else {
                        $update_id = $this->obj->update_record($idata);
                        $this->obj->update_record1($cdata);
                    }

                    if($update_id  == 0)
                    {
                        $msg="Appointment Rescheduled";
                        $ret['IsSuccess'] = true;
                        $ret['Msg'] = $msg;	
                    }
                    else
                    {
                        $ret['IsSuccess'] = false;
                        $ret['Msg'] = "Appointment Not Rescheduled,Please Try Again.";	
                    }

                }
                catch(Exception $e)
                {
                    $ret['IsSuccess'] = false;
                    $ret['Msg'] = $e->getMessage();
                }
            }
        }
        else
        {
            $msg="You have no rights to edit this Appointment !";
            $ret['IsSuccess'] = true;
            $ret['Msg'] = $msg;	
        }
	
 	echo json_encode($ret);
    }
	/*
    @Description: Function delete calender appoienment
    @Author: Niral Patel
    @Input: - 
    @Output: - Load Form for add property details
    @Date: 22-05-2014
    */
    function delete_calender()
    {
        $id=$this->input->post('id');
        /* OLD
        $this->obj->delete_calendar_record($id);
        $this->obj->delete_record($id);
         */
        // New 29-09-2014 Sanjay Moghariya
        $table ="calendar_repeat_trans";
        $fields = array('id,calendar_id');
        $match = array("id"=>$id);
        $transaction_result = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);
		
        $table ="calendar_master";
        $fields = array('task_user_id,task_id,created_by');
        $match = array("id"=>$transaction_result[0]['calendar_id'],'created_by'=>$this->admin_session['id']);
        $transaction_result1 = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);
        //pr($transaction_result1);exit;
        $flag = '';
        /////// This loop check for ///////////
        for($i=0;$i < count($transaction_result1);$i++)
        {
            if($transaction_result1[$i]['task_user_id'] > 0)
            {
                if(($transaction_result1[$i]['task_user_id'] == $this->admin_session['id']) OR ($transaction_result1[$i]['created_by'] == $this->admin_session['id']))
                {
                    $flag = 2;
                    break;
                }
                else
                {
                    $flag = '';
                }
            }
            else
            {
                $flag = 1;
                break;
            }
        }
        ///////End This loop check for ///////////
        if($flag == 1)
        {
            $this->obj->delete_calendar_record($transaction_result[0]['calendar_id']);
            //	echo $this->db->last_query();exit;
            $this->obj->delete_record($transaction_result[0]['calendar_id']);
            echo $tab="Appointment Has Been Deleted!";
        } else if($flag == 2) { // To remove task calendar event
            $tresult = array();
            $table ="calendar_master";
            $fields = array('task_user_id,task_id');
            $match = array("id"=>$transaction_result[0]['calendar_id'],'created_by'=>$this->admin_session['id']);
            $tresult = $this->obj->getmultiple_tables_records($table,$fields,'','','',$match);
            //pr($tresult);exit;
            if(!empty($tresult))
            {
                foreach($tresult as $trow) {
                    $fields = array('id');
                    $match = array("task_id"=>$trow['task_id']);
                    $calid = $this->calendar_model->select_records($fields,$match,'','=');
                    if(!empty($calid))
                    {
                        foreach($calid as $row)
                        {
                            $this->calendar_model->delete_calendar_record($row['id']);
                        }
                    }
                    $this->calendar_model->delete_caltask_record($trow['task_id']);
                }
            }
            echo $tab="Appointment Has Been Deleted!";
        }
        else
        {
            echo $tab="You have no rights to delete this Appointment !";
        }
        // End New
    }
}
