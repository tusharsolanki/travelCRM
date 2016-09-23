<?php

	/*
    @Description: Task Model
    @Author: Mohit Trivedi
    @Input: 
    @Output: 
    @Date: 02-08-2014
	*/

class task_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'task_master';
		$this->table_name_transcation='task_user_transcation';
		$this->table_name_calendar='calendar_master';
		$this->table_name_calendar_tran ='calendar_repeat_trans';
	}

    /*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause = '',$totalrow='')
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
		
		if(!empty($where_clause))
		{
			$where_field = "WHERE ";
			foreach($where_clause as $key=>$value)
			{
				$colldata = explode('IN_query',$key);
				
				if(!empty($colldata[0]) && !empty($colldata[1]) && trim($colldata[1]) == 'IN')
					$where_field .= trim($colldata[0]).' IN ('.$value.') AND ';
				else
					$where_field .= $key.' = '.$value.' AND ';
			}
			if(!empty($where))
				$where = $where_field.'('.ltrim($where,'WHERE ').')';	
			else
				$where = rtrim($where_field,'AND ');		
		}
		
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
		if(!empty($totalrow))
			return $query->num_rows();
		else
			return $query->result_array();
    }
	
	
	/*
    @Description: Function for get Module Lists
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-08-2014
    */
   
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name1='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		
		if(empty($db_name1))
	        $sql .= ' FROM '.$this->table_name_transcation;
		else
			$sql .= ' FROM '.$db_name1.".".$this->table_name_transcation;
        
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_transcation.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
	    return $query->result_array();
    }
	
	
	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-08-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$match3='',$totalrow='')
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
				$colldata = explode('jointype',$coll);
				$coll = trim($colldata[0]);
				
				if(!empty($colldata[1]))
				{	
					$join_type1 = trim($colldata[1]);
					if($join_type1 == 'direct')
						$join_type1 = '';
				}
				
				if(isset($join_type1))
					$this->db->join($coll, $value,$join_type1);
				else
					$this->db->join($coll, $value,$join_type);
				
				unset($join_type1);
			}
		
		}
		
		if($condition != null )
		$this->db->where($condition);
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null && $sort != null)
			$this->db->order_by($orderby,$sort);
		elseif($orderby != null )
			$this->db->order_by($orderby);	
		if($match_values != null &&  $compare_type != null )
		$this->db->or_like($match_values);
		
		if($match3 != null &&  $compare_type != null )
		$this->db->or_like($match3);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
		
		//echo $this->db->last_query();
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
			return $query_FC->result_array();
		
  
	}
    
	/*
    @Description: Function is for Insert Task details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Insert into DB
    @Output: - Insert record into DB
    @Date: 02-08-2014
    */
	
    function get_admin_users_list()
    {
		//'lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name'
        $this->db->select('lm.id,lm.admin_name,lm.email_id,lm.user_id,lm.status,lm.user_type,um.agent_id,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.status as user_status',false);
		$this->db->from('login_master as lm');
		$this->db->join('user_master as um','um.id = lm.user_id','left');
		$this->db->where("(lm.user_type = '2' OR lm.user_type = '3') AND lm.status = '1' AND (um.status = '1' OR lm.user_type = '2')");
		$this->db->group_by('lm.id');
		$query_FC = $this->db->get();
		
		//echo $this->db->last_query();
		
		//pr($query_FC->result_array());
		
		return $query_FC->result_array();
    }

    /*
    @Description: Function is for Insert Task details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Insert into DB
    @Output: - Insert record into DB
    @Date: 02-08-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	  /*
    @Description: Function is for Insert Task details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Insert into DB
    @Output: - Insert record into DB
    @Date: 02-08-2014
    */
	
    function insert_record1($data)
    {
		$result =  $this->db->insert($this->table_name_transcation,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }
	  /*
    @Description: Function is for Insert Task details in calendar master
    @Author: Mohit Trivedi
    @Input: Task details for Insert into DB of calendar master
    @Output: - Insert record into DB
    @Date: 20-08-2014
    */
	
    function insert_record2($data)
    {
		$result =  $this->db->insert($this->table_name_calendar,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }

	/*
    @Description: Function is for update customer details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 02-08-2014
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	/*
    @Description: Function is for update customer details by Admin
    @Author: Mohit Trivedi
    @Input: Task details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 02-08-2014
    */
    public function update_task($data)
    {
        $this->db->where('user_id',$data['user_id']);
		$this->db->where('task_id',$data['task_id']);
        $query = $this->db->update($this->table_name_transcation,$data); 
		
    }

    public function update_task1($data,$db_name1='')
    {
       	//pr($data);exit;
	    $this->db->where('id',$data['id']);
		
		if(!empty($db_name1))
			$query = $this->db->update($db_name1.".".$this->table_name_transcation,$data); 
		else
	        $query = $this->db->update($this->table_name_transcation,$data);
    }

	/*
    @Description: Function is for update task details in calendar master 
    @Author: Mohit Trivedi
    @Input: Task details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 20-08-2014
    */
    public function update_record1($data)
    {
        $this->db->where('task_id',$data['task_id']);
        $this->db->update($this->table_name_calendar,$data); 
		
		$this->db->select('*');
		$this->db->from($this->table_name_calendar);
		$this->db->where('task_id',$data['task_id']);
		$result = $this->db->get()->result_array();
		return $result;
		
    }

	  public function update_calendar_tran($data)
    {
        $this->db->where('calendar_id',$data['calendar_id']);
        $query = $this->db->update($this->table_name_calendar_tran,$data); 
	}	
	
	/*
    @Description: Function for Delete Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Task id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 02-08-2014
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	 /*
    @Description: Function for Delete Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Task id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 02-08-2014
    */
    public function delete_record1($data)
    {
	
		$this->db->where('task_id',$data['task_id']);
		$this->db->where('user_id',$data['user_id']);
        $this->db->delete($this->table_name_transcation);
    }
	 /*
    @Description: Function for Delete Task Profile By Admin
    @Author: Mohit Trivedi
    @Input: - Task id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 02-08-2014
    */
    public function delete_user_task($id)
    {
		$this->db->where('task_id',$id);
		$this->db->delete($this->table_name_transcation);
    }
	
	public function delete_single_user_task($data)
    {
		$this->db->where($data);
		$this->db->delete($this->table_name_transcation);
    }
	
	 /*
    @Description: Function for Delete Task Profile from calendar master
    @Author: Mohit Trivedi
    @Input: - Task id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 20-08-2014
    */
    public function delete_record2($data)
    {
		//// Calendar trans tables Remove Events ///
		//pr($data);exit;
		$this->db->select('*');
		$this->db->from($this->table_name_calendar);
		$this->db->where('task_id',$data['task_id']);
		$this->db->where('task_user_id',$data['task_user_id']);
		$result = $this->db->get()->result_array();
		
		for($i=0; $i< count($result); $i++)
		{
		$this->db->where('calendar_id',$result[$i]['id']);
        $this->db->delete($this->table_name_calendar_tran);
		}
		///////End //////////
	
        $this->db->where('task_id',$data['task_id']);
		$this->db->where('task_user_id',$data['task_user_id']);
        $this->db->delete($this->table_name_calendar);
    }

	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function gettaskpagingid($task_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		if(!empty($iscompleted))
			$this->db->where('is_completed','0');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $task_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		return $op;
		/*$total_record = count($result);
		if($total_record % 10 == 0)
			echo $total_record - 10;
		else
			echo $op;
	
		exit;*/
	}

	/*
        @Description: Function For cron task fetch
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 28-10-14
    */

	public function cron_fetch_task($db_name1='')
	{
		$curdate=date('Y-m-d H:i:s');
		$this->db->select('*');
		
		if(!empty($db_name1))
			$this->db->from($db_name1.".".$this->table_name);
		else
			$this->db->from($this->table_name);
		
		$this->db->where('is_email','1');
		$this->db->where('task_date >',$curdate);
		$this->db->where('reminder_email_date <=',$curdate);
		return $this->db->get()->result_array();
	}
}