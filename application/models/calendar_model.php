<?php

	/*
    @Description: calendar Model
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 06-06-14
	*/

class calendar_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
		$this->user_session = $this->session->userdata($this->lang->line('common_user_session_label'));
		$this->admin_session = $this->session->userdata($this->lang->line('common_admin_session_label'));
        $this->table_name = 'calendar_master';
        $this->table_name_calendar_tran = 'calendar_repeat_trans';
		
    }

    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-06-14
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE ';
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0].' '.$compare_type .'"%'.$match_values[$keys[0]].'%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0].' '.$compare_type .'"'.$match_values[$keys[0]].'" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);
            
            foreach($match_values as $key=>$value)
            {                
                $where .= $and_or.' '.$key.' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type .'"%'.$value.'%"';
                        break;
                    
                    case '=':
                    default:
                        $where .= $compare_type .'"'.$value.'"';
                        break;
                }
            }
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		return $query->result_array();
    }
	

    /*
        @Description: Function for get User List (Customer)
        @Author     : Niral Patel
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : User details
        @Date       : 23-05-2014
    */
    
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='')
    {  
		if(!empty($fields))
		{
			foreach($fields as $coll => $value)
			{
				$this->db->select($value,false);
			}
		}
		
		$this->db->from($table);
		
		if(!empty($join_tables))
		{
			foreach($join_tables as $coll => $value)
			{
				$this->db->join($coll, $value,$join_type);
			}
		}
		
		
		if($condition != null )
		$this->db->where($condition);
		
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null )
		$this->db->order_by($orderby);
		
		if($orderby != null && $sort != null)
		$this->db->order_by($orderby,$sort);
				
		if($match_values != null &&  $compare_type != null )
		$this->db->like($match_values, $compare_type);
			
		if($num != null )
		$this->db->limit($num);
		
		if($offset != null && $num != null)
		$this->db->limit($offset,$num);
		
		$query_FC = $this->db->get();
		
		//echo $this->db->last_query();exit;
  		return $query_FC->result_array();
  
	}
	function getmultiple_tables_records1($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$where='',$contact_id='')
    {  
		//pr($match_values);exit;
		if(!empty($fields))
		{
			foreach($fields as $coll => $value)
			{
				$this->db->select($value,false);
			}
		}
		
		$this->db->from($table);
		
		if(!empty($join_tables))
		{
			foreach($join_tables as $coll => $value)
			{
				$this->db->join($coll, $value,$join_type);
			}
		}
		
		
		if($condition != null )
			$this->db->where($condition); 
		
		if(!empty($where) && $match_values != null &&  $compare_type != null)
		{
			$wherestr = '';
			
			foreach($where as $key=>$val)
			{
				$wherestr .= $key." = '".$val."' AND ";
			}
			
			$wherestr .= '(';
			
			foreach($match_values as $key=>$val)
			{
				$wherestr .= $key." ".$compare_type." '%".$val."%' OR ";
			}
			
			$wherestr = rtrim($wherestr,'OR ');
			
			$wherestr .= ')';
			
			$this->db->where($wherestr); 
			
			//echo $wherestr;
			
			//exit;
		}
		else
		{
			if(!empty($where))
				$this->db->where($where); 
			
			if($match_values != null &&  $compare_type != null )
				$this->db->or_like($match_values, $compare_type);
		}
		if(!empty($contact_id))
		{
			$this->db->or_where_not_in('cm.id',$contact_id);
		}
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null && $sort != null)
		$this->db->order_by($orderby,$sort);
		
		elseif($orderby != null )
		$this->db->order_by($orderby);
			
		if($num != null )
		$this->db->limit($num);
		
		if($offset != null && $num != null)
		$this->db->limit($num,$offset);
		
		
		$query_FC = $this->db->get();
	//	echo $this->db->last_query();exit;
  		return $query_FC->result_array();
	}

    /*
    @Description: Function is for Insert calendar details by Admin
    @Author: Niral Patel
    @Input: calendar details for Insert into DB
    @Output: - Insert record into DB
    @Date: 23-05-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	function insert_calendar_tran($data)
    {
        $result =  $this->db->insert($this->table_name_calendar_tran,$data);	
	 // echo $this->db->last_query();exit;
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function select_calendar_tran($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_calendar_tran);
		$this->db->where('calendar_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
    
    }

    /*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: calendar details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 23-05-2014
    */
    public function update_record($data,$db_name1='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name1))
			$query = $this->db->update($db_name1.".".$this->table_name,$data); 
		else
	        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
    }
	public function update_record1($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_calendar_tran,$data); 
		//echo $this->db->last_query();exit;
    }
	 /*
    @Description: Function is for update Event by Admin
    @Author: Kaushik Valiya
    @Input: calendar details for Update into DB trandaction tables
    @Output: - Update records into DB with give id
    @Date: 23-05-2014
    */
    public function update_record_tran($data,$id='')
    {
        if(!empty($id))
            $this->db->where('calendar_id',$id);
        else
            $this->db->where('calendar_id',$data['id']);
        $query = $this->db->update($this->table_name_calendar_tran,$data); 
        //echo $this->db->last_query();exit;
    }
	/*
    @Description: Function is for update Event by Admin
    @Author: Kaushik Valiya
    @Input: calendar details for Update into DB trandaction tables
    @Output: - Update records into DB with give id
    @Date: 23-05-2014
    */
    public function update_record_trandata($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_calendar_tran,$data); 
        //echo $this->db->last_query();exit;
    }
    
    /*
        @Description: Function is for update Event by Admin
        @Author     : Sanjay Moghariya
        @Input      : calendar details for Update into DB transaction tables
        @Output     : Update records into DB with give id
        @Date       : 09-09-2014
    */
    public function update_following_trandata($data,$cmid,$ctid,$event_type='')
    {
        $this->db->where('id >=',$ctid);
        //if(!empty($event_type) && $event_type == 2)
        $this->db->where('calendar_id ',$cmid);
        $query = $this->db->update($this->table_name_calendar_tran,$data); 
        //echo $this->db->last_query();exit;
    }
	public function update_calandar($data)
    {
        $this->db->where('googleEventId',$data['googleEventId']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
    }
	public function update_calandar_color($data)
    {
        $this->db->where('event_title',$data['event_title']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
    }
	
	/*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: calendar dates
    @Output: - get details
    @Date: 23-05-2014
    */
    public function get_records($start_date='',$end_date='',$check_end_date='',$match)
    {
      	$this->db->from($this->table_name);
		
		if(!empty($check_end_date))
		{$dateRange = "(start_date BETWEEN '$start_date%' AND '$end_date%') AND (end_date BETWEEN '$start_date%' AND '$end_date%')";}
		else
		{$dateRange = "start_date BETWEEN '$start_date%' AND '$end_date%'";}
	  	if(!empty($match))
		{
			$this->db->where($match, NULL, FALSE); 		
		}
	  	$this->db->where($dateRange, NULL, FALSE); 
		$query_FC = $this->db->get();
		$numrows = $query_FC->num_rows();
		
		if ($numrows > 0) 
		{
			$result_array_FC = $query_FC->result_array();
		}
		else
		{
			$result_array_FC = $query_FC->result_array();
		}
		//echo $this->db->last_query();exit;
		return $result_array_FC;
    }
	 
	 
	 public function get_records1($start_date='',$end_date='',$check_end_date='',$match='')
    {
		/*
		$query_sql = 'SELECT concat( cc.calendar_id, " ", cc.event_start_date, " ", cc.event_start_time ) unique_cal, c.*, cc.calendar_id, cc.event_title, cc.event_notes, cc.event_color, cc.edit_flag, cc.event_start_date, cc.event_start_time, cc.event_end_date, cc.event_end_time, cc.id as cal_id 
		FROM '.$this->table_name.' as c 
		LEFT JOIN `calendar_repeat_trans` as cc ON `cc`.`calendar_id` = `c`.`id` WHERE ';
		if(!empty($match))
			$query_sql .= $match.' AND ';
		$query_sql .= ' ((`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.task_user_id = '.$this->admin_session['id'].')
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND FIND_IN_SET( '.$this->admin_session['id'].', c.assigned_user_id ))
		OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'") 
		OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND (CASE WHEN c.task_id <= 0 THEN c.created_by = '.$this->admin_session['id'].' END))
                OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND (CASE WHEN c.assigned_user_id = "" THEN c.created_by = '.$this->admin_session['id'].' END)))
		GROUP BY CASE WHEN c.task_id > 0 THEN c.task_id ELSE unique_cal END';
		*/
             
                $query_sql = 'SELECT concat( cc.calendar_id, " ", cc.event_start_date, " ", cc.event_start_time ) unique_cal,case WHEN um.first_name IS NOT NULL THEN group_concat(CONCAT_WS("(",CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name),CONCAT_WS(")",lm.email_id,"")) separator \', \') ELSE "" END as agent_name,case WHEN tum.first_name IS NOT NULL OR tlm.admin_name IS NOT NULL THEN group_concat(CONCAT_WS("(",CONCAT_WS(" ",CASE WHEN tlm.admin_name IS NULL OR tlm.admin_name = "" THEN CONCAT_WS(" ",tum.first_name,tum.middle_name,tum.last_name) ELSE tlm.admin_name END),CONCAT_WS(")",tlm.email_id,"")) separator \', \') ELSE "" END as task_user_name, c.*, cc.calendar_id, cc.event_title, cc.event_notes, cc.event_color, cc.edit_flag, cc.event_start_date, cc.event_start_time, cc.event_end_date, cc.event_end_time, cc.id as cal_id ,lm.email_id
		FROM '.$this->table_name.' as c 
                LEFT JOIN `calendar_repeat_trans` as cc ON `cc`.`calendar_id` = `c`.`id`
                
                LEFT JOIN `login_master` as tlm ON tlm.id = c.task_user_id
                LEFT JOIN `user_master` as tum ON tum.id = tlm.user_id
                
                LEFT JOIN `user_master` as um ON FIND_IN_SET (um.id, c.assigned_user_id) 
                LEFT JOIN `login_master` as lm ON lm.user_id = um.id WHERE ';
                
                //LEFT JOIN `user_master` as um ON `um`.`id` = `lm`.`user_id`  WHERE ';
		
		if(!empty($match))
			$query_sql .= $match.' AND ';
		$query_sql .= ' ((`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.task_user_id = '.$this->admin_session['id'].')
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND FIND_IN_SET( '.$this->admin_session['id'].', c.assigned_user_id ))
		OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'") 
		OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND (CASE WHEN c.task_id <= 0 THEN c.created_by = '.$this->admin_session['id'].' END))
                OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND (CASE WHEN c.assigned_user_id IS NULL OR c.assigned_user_id = "" THEN c.created_by = '.$this->admin_session['id'].' END)))
		GROUP BY CASE WHEN c.task_id > 0 THEN c.task_id ELSE unique_cal END';
		$query_FC = $this->db->query($query_sql,false);
                
		 //echo $this->db->last_query();exit;
		
		return $query_FC->result_array();
	}
	
	
	public function get_records2($start_date='',$end_date='',$check_end_date='',$match='',$user_id='')
    {
		$query_sql = 'SELECT concat( cc.calendar_id, " ", cc.event_start_date, " ", cc.event_start_time ) unique_cal, c.*, cc.calendar_id, cc.event_title, cc.event_notes, cc.event_color, cc.edit_flag, cc.event_start_date, cc.event_start_time, cc.event_end_date, cc.event_end_time, cc.id as cal_id 
		FROM '.$this->table_name.' as c 
		LEFT JOIN `calendar_repeat_trans` as cc ON `cc`.`calendar_id` = `c`.`id` 
                LEFT JOIN `user_master` as um ON FIND_IN_SET (um.id, c.assigned_user_id)  WHERE ';

                if(!empty($match))
                $query_sql .= $match.' AND ';
		/*$query_sql .= '((`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.task_user_id = '.$this->user_session['id'].')
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND FIND_IN_SET( '.$this->user_session['id'].', c.assigned_user_id ))
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.is_public = "1") 
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.created_by = '.$this->user_session['id'].'))
                    GROUP BY CASE WHEN c.task_id > 0 THEN c.task_id ELSE unique_cal END';
                */
                $query_sql .= '((`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.task_user_id IN('.$this->user_session['agent_id'].'))
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND FIND_IN_SET( '.$this->user_session['agent_user_id'].', c.assigned_user_id ))
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.is_public = "1") 
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND c.created_by IN( '.$this->user_session['agent_id'].'))
                    OR (`cc`.`event_start_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" AND (CASE WHEN um.agent_id != 0 THEN um.agent_id = '.$this->user_session['agent_user_id'].' END)))
                    GROUP BY CASE WHEN c.task_id > 0 THEN c.task_id ELSE unique_cal END';
                
		$query_FC = $this->db->query($query_sql,false);
		//echo $this->db->last_query(); exit;
		//pr($query_FC->result_array());exit;
		return $query_FC->result_array();
	}
	/*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: calendar details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 23-05-2014
    */
    public function get_date_records($start_date='')
    {
      	$this->db->from($this->table_name);
		$dateRange = "'".$start_date."' between start_date AND end_date";
		
	  	$this->db->where($dateRange, NULL, FALSE); 
		$query_FC = $this->db->get();
		$numrows = $query_FC->num_rows();
		
		if ($numrows > 0) 
		{
			$result_array_FC = $query_FC->result_array();
		}
		else
		{
			$result_array_FC = $query_FC->result_array();
		}
		return $result_array_FC;
    }
	/*
    @Description: Function is for update customer details by Admin
    @Author: Niral Patel
    @Input: calendar details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 23-05-2014
    */
    public function get_edit_record($id='id')
    {
      	$this->db->from($this->table_name);
	  	$this->db->where('id',$id, FALSE); 
		$query_FC = $this->db->get();
		$numrows = $query_FC->num_rows();
		
		if ($numrows > 0) 
		{
			$result_array_FC = $query_FC->result();
		}
		else
		{
			$result_array_FC = $query_FC->result();
		}
		return $result_array_FC;
    }
	
    /*
    @Description: Function for Delete calendar Profile By Admin
    @Author: Niral Patel
    @Input: - calendar id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
    public function delete_calendar_record($id)
    {
        $this->db->where('calendar_id',$id);
        $this->db->delete($this->table_name_calendar_tran);
    }
    
    /*
        @Description: Function for Delete task calendar event
        @Author     : Sanjay Moghariya
        @Input      : task id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 28-02-2015
    */
    public function delete_caltask_record($id)
    {
        $this->db->where('task_id',$id);
        $this->db->delete($this->table_name);
    }
    
    /*
        @Description: Function for Delete calendar transaction
        @Author     : Sanjay Moghariya
        @Input      : Calendar id and calendar master id
        @Output     : Delete recodrs
        @Date       : 09-09-2014
    */
    public function delete_calendar_trans($cmid,$ctid)
    {
        $this->db->where('id >=',$ctid);
        $this->db->where('calendar_id ',$cmid);
        $this->db->delete($this->table_name_calendar_tran);
    }
    
    /*
        @Description: Function for Delete calendar transaction
        @Author     : Sanjay Moghariya
        @Input      : Calendar id and calendar master id
        @Output     : Delete recodrs
        @Date       : 10-09-2014
    */
    public function delete_calendar_trans_norepeat($cmid,$ctid)
    {
        $this->db->where('id !=',$ctid);
        $this->db->where('calendar_id ',$cmid);
        $this->db->delete($this->table_name_calendar_tran);
    }
	 /*
    @Description: Function for get Module Lists
    @Author: Kaushik Valiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 14-10-14
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_calendar_tran;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE ';
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0].' '.$compare_type .'"%'.$match_values[$keys[0]].'%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0].' '.$compare_type .'"'.$match_values[$keys[0]].'" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);
            
            foreach($match_values as $key=>$value)
            {                
                $where .= $and_or.' '.$key.' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type .'"%'.$value.'%"';
                        break;
                    
                    case '=':
                    default:
                        $where .= $compare_type .'"'.$value.'"';
                        break;
                }
            }
        }
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_calendar_tran.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		return $query->result_array();
    }
		
	 /*
    @Description: Function for Get User Permission to delete ot not
    @Author: Kaushik Valiya
    @Input: calendar id,created by and task_user_id 
    @Output: permission to detele events list
    @Date: 20-10-2014
    */	
	function select_calendar_master($data)
    {
		$query_sql = 'SELECT * FROM '.$this->table_name.' 
		WHERE (id = '.$data['id'].' AND  created_by = '.$this->user_session['id'].')';// OR task_user_id = '.$this->user_session['id'].')';
		
		$query_FC = $this->db->query($query_sql,false);
		
		return $query_FC->result_array();
		
    }

	/*
        @Description: Function For cron event fetch
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 29-10-14
    */

	public function cron_fetch_event($db_name1='')
	{
		$curdate=date('Y-m-d H:i:s');
		$this->db->select('*');
		
		if(!empty($db_name1))
			$this->db->from($db_name1.".".$this->table_name);
		else
			$this->db->from($this->table_name);
		
		$this->db->where('is_email','1');
		$this->db->where("CONCAT(start_date, ' ', start_time) >", $curdate);
		$this->db->where('reminder_email_date <=',$curdate);
		$this->db->where('task_id',0);
		$this->db->where('is_mail_sent',0);
		//$this->db->get()->result_array();
		return $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
	}

}