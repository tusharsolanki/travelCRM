<?php

	/*
    @Description: contacts Model
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	*/

class user_management_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'user_master';
		$this->table_name_user_contact = 'user_contact_trans';
        $this->table_name_department = 'transaction_user';
		$this->table_name_email_trans = 'user_emails_trans';
		$this->table_name_phone_trans = 'user_phone_trans';
		$this->table_name_website_trans = 'user_website_trans';
		$this->table_name_social_trans = 'user_social_trans';
		$this->table_name_address_trans = 'user_address_trans';
		$this->table_name_user_contact_trans = 'user_contact_trans';
		$this->table_name_login_master = 'login_master';
		$this->table_name_user_right_trans_master = 'user_rights_trans';
		$this->table_name_contact_conve_trans = 'contact_conversations_trans';
		$this->table_name_contact = 'contact_master';
        $this->table_user_rr_weightage_trans = 'user_rr_weightage_trans';
        $this->table_lender_rr_weightage_trans = 'lender_rr_weightage_trans';
        $this->table_name_transaction_user = 'transaction_user';       
		//$this->table_name_communication_trans = 'contact_communication_plan_trans';
		//$this->table_name_doc_trans = 'contact_documents_trans';
		//$this->table_name_contact_csv = 'contact_csv_master';
		//$this->table_name_contact_source_master = 'contact__source_master';
		//$this->table_name_contact_csv_mapping_master = 'contact__csv_mapping_master';
		//$this->table_name_contact_csv_mapping_trans = 'contact__csv_mapping_trans';
		
		
    }

    /*
    @Description: Function for get Module Lists
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name1='',$totalrow='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		
		if(empty($db_name1))
	        $sql .= ' FROM '.$this->table_name;
		else
			$sql .= ' FROM '.$db_name1.".".$this->table_name;
		
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
		if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." LIKE '%".$val."%' OR ";
			}
			$where = rtrim($where,'OR ');
			$where .= ')';
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
		//echo $this->db->last_query();
		if(!empty($totalrow))
			return $query->num_rows();
		else
			return $query->result_array();
    }
    function select_transaction_user($userid)
    {

        $this->db->select('department_id');
        $this->db->from($this->table_name_transaction_user);
        $this->db->where('user_id',$userid);
        $result = $this->db->get()->result_array();
        
        return $result;
    }
	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$where='',$contact_id='',$or_where='',$totalrow='',$wherestring='')
    {  
		//pr($sort);
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
                if(!empty($or_where))
                {
                    
                    $this->db->where($or_where);
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
		//echo $this->db->last_query();
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
  			return $query_FC->result_array();
	
		
		/*//echo $num."hi".$offset;
		foreach($fields as $coll => $value)
		{
			$this->db->select($value,false);
		}
			$this->db->from($table);
		
		foreach($join_tables as $coll => $value)
		{
			$this->db->join($coll, $value,$join_type);
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
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
		
		//pr($query_FC->result_array());
		//echo $this->db->last_query();exit;
		
  		return $query_FC->result_array();*/
  
	}
     /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 23-08-2014
    */
   
    public function select_login_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name1='')
    {
		$fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		
		if(empty($db_name1))
	        $sql .= ' FROM '.$this->table_name_login_master;
		else
			$sql .= ' FROM '.$db_name1.".".$this->table_name_login_master;
		
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_login_master.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }

    /*
    @Description: Function is for Insert contacts details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-07-2014
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
    function insert_user_contact_trans_record($data,$db_name='')
    {
        if(!empty($db_name)) {
            $result =  $this->db->insert($db_name.'.'.$this->table_name_user_contact_trans,$data);	
        } else {
            $result =  $this->db->insert($this->table_name_user_contact_trans,$data);	
        }
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function is for Insert user rr weightage details
        @Author     : Sanjay Moghariya
        @Input      : Weightage Data
        @Output     : Insert record into DB
        @Date       : 31-10-2014
    */
    function insert_user_rr_weightage_trans_record($data,$db_name='')
    {
        if(!empty($db_name)) {
            $result =  $this->db->insert($db_name.'.'.$this->table_user_rr_weightage_trans,$data);	
        } else {
            $result =  $this->db->insert($this->table_user_rr_weightage_trans,$data);	
        }
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Update user rr weightage details
        @Author     : Sanjay Moghariya
        @Input      : Weightage Data
        @Output     : Update record
        @Date       : 12-11-2014
    */
    function update_user_rr_weightage_trans_record($data,$db_name='')
    {
        $this->db->where('user_id',$data['user_id']);
        if(!empty($db_name)) {
            $query = $this->db->update($db_name.'.'.$this->table_user_rr_weightage_trans,$data); 
        } else {
            $query = $this->db->update($this->table_user_rr_weightage_trans,$data); 
        }
    }
    
    /*
        @Description: Function is for Insert Lender rr weightage details
        @Author     : Sanjay Moghariya
        @Input      : Weightage Data
        @Output     : Insert record into DB
        @Date       : 05-01-2015
    */
    function insert_lender_rr_weightage_trans_record($data,$db_name='')
    {
        if(!empty($db_name)) {
            $result =  $this->db->insert($db_name.'.'.$this->table_lender_rr_weightage_trans,$data);	
        } else {
            $result =  $this->db->insert($this->table_lender_rr_weightage_trans,$data);	
        }
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Update lender rr weightage details
        @Author     : Sanjay Moghariya
        @Input      : Weightage Data
        @Output     : Update record
        @Date       : 05-01-2015
    */
    function update_lender_rr_weightage_trans_record($data,$db_name='')
    {
        $this->db->where('user_id',$data['user_id']);
        if(!empty($db_name)) {
            $query = $this->db->update($db_name.'.'.$this->table_lender_rr_weightage_trans,$data); 
        } else {
            $query = $this->db->update($this->table_lender_rr_weightage_trans,$data); 
        }
    }
    
	function insert_user_record($data,$db_name='')
    {
        if(!empty($db_name))
        {
            $result =  $this->db->insert($db_name.'.'.$this->table_name_login_master,$data);     
        }
        else
        {
            $result =  $this->db->insert($this->table_name_login_master,$data); 
        }
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_parent_user_record($parent_db='',$child_db='',$lastId='')
    {
        $sql_ins = "INSERT INTO ".$parent_db.".login_master (user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, twilio_account_sid, twilio_auth_token, twilio_number,phone, created_date, created_by, modified_date, modified_by, status, fb_api_key, fb_secret_key) SELECT user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, twilio_account_sid, twilio_auth_token, twilio_number,phone, created_date, created_by, modified_date, modified_by, status, fb_api_key, fb_secret_key FROM ".$child_db.".login_master where id  = ".$lastId.";";
		$query = $this->db->query($sql_ins);
    }
	/*function insert_parent_user_record($parent_db='',$child_db='',$lastId='')
    {
        $sql_ins = "INSERT INTO ".$parent_db.".login_master (user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status, twilio_contact_no) SELECT user_type, user_id, admin_name, email_id, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status,twilio_contact_no FROM ".$child_db.".login_master where id  = ".$lastId.";";
		$query = $this->db->query($sql_ins);
    }*/
	
	function update_parent_user_record($parent_db='',$data='')
    {
        $this->db->where(array('email_id'=>$data['email_id'],'db_name'=>$data['db_name']));
        $query = $this->db->update($parent_db.".login_master",$data); 
		//echo $this->db->last_query();exit;
    }
	
	/*
    @Description: Function is for Insert contacts details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-07-2014
    */
	
  
	
	/*
    @Description: Function is for Insert contacts transaction
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 10-07-2014
    */
	function insert_contact_converaction_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_contact_conve_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	function insert_user_rights_trans_record($data)
    {
			
        $result =  $this->db->insert($this->table_name_user_right_trans_master,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_email_trans_record($data)
    {
			
        $result =  $this->db->insert($this->table_name_email_trans,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_phone_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_phone_trans,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }

    function insert_dept_user_record($data)
    {

        $result =  $this->db->insert($this->table_name_department,$data);  
        $lastId = mysql_insert_id();
        return $lastId;
    }

	
	function insert_website_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_website_trans,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_social_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_social_trans,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	
	function insert_address_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_address_trans,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	
	
	
	/* Select Transaction records */
	/*
    @Description: Select Transaction records
    @Author: Nishit Modi
    @Input: contact id
    @Output: - select records from DB
    @Date: 08-07-2014
    */
	function select_contact_converaction_trans_record($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_conve_trans);
		$this->db->where('contact_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	function select_user_agent_list_record($data)
    {
		
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('status >=',$data['status']);
		$this->db->where('user_type',$data['user_type']);
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		return $result;
    }
	function select_user_contact_trans_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_email_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	function select_user_rights_trans_record($user_id='',$right_id='')
    {
		
		//echo $where;exit;
		//$this->db->where($where);
		$this->db->select('*');
		$this->db->from($this->table_name_user_right_trans_master);
		$this->db->where('user_id',$user_id);
		$this->db->where('rights_id',$right_id);
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		return $result;
    }
	function select_user_rights_trans_edit_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_user_right_trans_master);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	function select_user_rights($user_id)
    {
		$this->db->select('rights_id');
		$this->db->from($this->table_name_user_right_trans_master);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		foreach ($result as $res)
		{
			$right[] =  $res['rights_id'];
		}
		if(!empty($right))
			$right_lists = array_values($right);
		else
			$right_lists = array();
		
		return $right_lists;
		//return $result;
    }
	
	function select_user_login_record_by_userid($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_login_master);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_user_login_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_login_master);
		$this->db->where('id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_email_trans_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_email_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_phone_trans_record($user_id)
    {
        $this->db->select('*');
		$this->db->from($this->table_name_phone_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_website_trans_record($user_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_website_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_social_trans_record($user_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_social_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	
	function select_address_trans_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_address_trans);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
       
    }
    function select_user_trans_record($user_id)
    {
        $this->db->select('*');
        $this->db->from($this->table_name_department);
        $this->db->where('user_id',$user_id);
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
        return $result;
       
    }
	
	/* ////////////////////////// */	

    /*
    @Description: Function is for update customer details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 04-07-2014
    */
	
	
	public function update_user_record($data)
    {
        $this->db->where('user_id',$data['user_id']);
        $query = $this->db->update($this->table_name_login_master,$data); 
		
    }
    function update_dept_user_record($data,$user)
    {
       
        $this->db->where('user_id',$user);
        $query = $this->db->insert($this->table_name_department,$data);
        //echo $this->db->last_query();
       // exit;


       
    }
	public function update_user_password($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_login_master,$data); 
		
    }
    public function update_parent_user($data,$parent_table)
    {
        $this->db->where('user_id',$data['user_id']);
        $query = $this->db->update($parent_table.".".$this->table_name_login_master,$data); 
        
    }
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }
	function update_user_rights_trans_record($data)
    { 
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_user_right_trans_master,$data); 
		echo $this->db->last_query();
    }
	public function update_email_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_email_trans,$data); 
    }
	
	public function update_phone_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_phone_trans,$data); 
    }
	
	public function update_address_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_address_trans,$data); 
    }
	
	public function update_website_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_website_trans,$data); 
    }
	
	public function update_social_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_social_trans,$data); 
    }
	
	
    /*
    @Description: Function for Delete contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - contacts id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 04-07-2014
    */
    public function delete_table_trans_user_record($id='',$data='')
    {

        if(!empty($data))
            $this->db->where($data);
        else
            $this->db->where('user_id',$id);
        $this->db->delete($this->table_name_department);
        echo $this->db->last_query();
        //exit;
    }
    public function delete_record($id,$db_name1='')
    {
        $this->db->where('id',$id);
		if($db_name1 != '')
			$this->db->delete($db_name1.".".$this->table_name);
		else
        	$this->db->delete($this->table_name);
    }
	
	/*
    @Description: Function for Delete  for all transaction  table data data
    @Author: Kaushik Valiya
	@Input: - id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 12-07-2014
    */
	
	public function delete_user_rights_trans_record1($data)
    {
		
		$this->db->where('user_id',$data['user_id']); 
		$this->db->where('rights_id',$data['rights_id']); 
        $this->db->delete($this->table_name_user_right_trans_master);
		//echo $this->db->last_query();exit;
		
    }
	public function delete_user_rights_trans_record($user_id)
    {
		
		$this->db->where('user_id',$user_id); 
        $this->db->delete($this->table_name_user_right_trans_master);
		
    }
	public function delete_user_contact_trans_record($id)
    {
        $this->db->where('contact_id',$id);
        $this->db->delete($this->table_name_user_contact);
    }
	public function delete_user_contact_trans_record1($data)
    {
        $this->db->where('contact_id',$data['contact_id']);
        $this->db->delete($this->table_name_user_contact);
    }

	public function delete_table_trans_record($user_id,$table)
    {
        $this->db->where('user_id',$user_id);
        $this->db->delete($table);
		
    }
	
	public function delete_email_trans_record($id)
    {
        $this->db->where('id',$id);
		$this->db->delete($this->table_name_email_trans);
		
    }
	
	public function delete_phone_trans_record($id)
    {
        $this->db->where('id',$id);
		$this->db->delete($this->table_name_phone_trans);
    }
	
	public function delete_address_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_address_trans);
    }
	
	public function delete_website_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_website_trans);
    }
	
	public function delete_social_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_social_trans);
    }
	
	
	public function delete_communication_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_communication_trans);
    }
	
	
	/*public function merge_search_contacts_counter($field)
	{
		if(!empty($field) && count($field) > 0)
		{	
			
			$selectfieldsmain = 'c.id,c.first_name,c.middle_name,c.last_name,CONCAT_WS(" ",c.first_name,c.middle_name,c.last_name) as contact_name,cet.email_address,cpt.phone_no,';
			$selectfieldsmainsub = '';
			$tempjoindata = '';
		
			foreach($field as $row)
			{
				if($row == 'email_address')
				{
					//$selectfieldsmain .= 'cet.'.$row.",";
					$tempjoindata .= 'temp.email_address = cet.email_address AND ';
				}
				elseif($row == 'phone_no')
				{
					//$selectfieldsmain .= 'cpt.'.$row.",";
					$tempjoindata .= 'temp.phone_no = cpt.phone_no AND ';
				}
				else
				{
					//$selectfieldsmain .= 'c.'.$row.",";
					$tempjoindata .= 'temp.'.$row.' = c.'.$row.' AND ';
				}
					
				$selectfieldsmainsub .= $row.',';	
				
			}
			
			$selectfieldsmain = rtrim($selectfieldsmain, ",");
			$selectfieldsmainsub = rtrim($selectfieldsmainsub, ",");
			$tempjoindata = rtrim($tempjoindata, "AND ");
			
			$query = 'SELECT '.$selectfieldsmainsub.',COUNT(*)
			FROM contact_master';
			
			if(in_array('email_address',$field))
				$query .= ' JOIN contact_emails_trans ON contact_emails_trans.contact_id = contact_master.id AND contact_emails_trans.email_address != \'\' AND contact_emails_trans.is_default = "1" ';
				
			if(in_array('phone_no',$field))
				$query .= ' JOIN contact_phone_trans ON contact_phone_trans.contact_id = contact_master.id AND contact_phone_trans.phone_no != \'\' AND contact_phone_trans.is_default = "1" ';
			
			$query .= ' GROUP BY '.$selectfieldsmainsub.'
			HAVING COUNT(*) > 1';
			
			$query = $this->db->query($query);
			
			//pr($query->result_array());
			
			return $query->result_array();
			
		}
		else
		{}
		
	}*/
	
	public function merge_search_contacts($field)
	{
		if(!empty($field) && count($field) > 0)
		{	
			
			$selectfieldsmain = 'c.id,c.first_name,c.middle_name,c.last_name,CONCAT_WS(" ",c.first_name,c.middle_name,c.last_name) as contact_name,cet.email_address,cpt.phone_no,';
			$selectfieldsmainsub = '';
			$tempjoindata = '';
		
			foreach($field as $row)
			{
				if($row == 'email_address')
				{
					//$selectfieldsmain .= 'cet.'.$row.",";
					$tempjoindata .= 'temp.email_address = cet.email_address AND ';
				}
				elseif($row == 'phone_no')
				{
					//$selectfieldsmain .= 'cpt.'.$row.",";
					$tempjoindata .= 'temp.phone_no = cpt.phone_no AND ';
				}
				else
				{
					//$selectfieldsmain .= 'c.'.$row.",";
					$tempjoindata .= 'temp.'.$row.' = c.'.$row.' AND ';
				}
					
				$selectfieldsmainsub .= $row.',';	
				
			}
			
			$selectfieldsmain = rtrim($selectfieldsmain, ",");
			$selectfieldsmainsub = rtrim($selectfieldsmainsub, ",");
			$tempjoindata = rtrim($tempjoindata, "AND ");
			
			
			///////////////////////////////////////////////////////////
			
			
			$query = 'SELECT '.$selectfieldsmainsub.',COUNT(*) rowspan
			FROM contact_master';
			
			if(in_array('email_address',$field))
				$query .= ' JOIN contact_emails_trans ON contact_emails_trans.user_id = contact_master.id AND contact_emails_trans.email_address != \'\' AND contact_emails_trans.is_default = "1" ';
				
			if(in_array('phone_no',$field))
				$query .= ' JOIN contact_phone_trans ON contact_phone_trans.user_id = contact_master.id AND contact_phone_trans.phone_no != \'\' AND contact_phone_trans.is_default = "1" ';
			
			$query .= ' GROUP BY '.$selectfieldsmainsub.'
			HAVING COUNT(*) > 1';
			
			$query = $this->db->query($query);
			
			$counter_data = $query->result_array();			
			
			$data['counter_data'] = $query->result_array();
			
			//echo $this->db->last_query();
			//pr($data['counter_data']);
			//exit;
			
			///////////////////////////////////////////////////////////
			
			
			
			///////////////////////////////////////////////////////////		
			
			
			/*$query = 'SELECT '.$selectfieldsmain.' FROM contact_master c';
			
			//if(in_array('email_address',$field))
				$query .= ' JOIN contact_emails_trans AS cet ON cet.contact_id = c.id AND cet.email_address != \'\' AND cet.is_default = "1" ';
			
			//if(in_array('phone_no',$field))
				$query .= ' JOIN contact_phone_trans AS cpt ON cpt.contact_id = c.id AND cpt.phone_no != \'\' AND cpt.is_default = "1" ';
			
			$query .= ' INNER JOIN (
			SELECT '.$selectfieldsmainsub.',COUNT(*)
			FROM contact_master';
			
			if(in_array('email_address',$field))
				$query .= ' JOIN contact_emails_trans ON contact_emails_trans.contact_id = contact_master.id AND contact_emails_trans.email_address != \'\' AND contact_emails_trans.is_default = "1" ';
				
			if(in_array('phone_no',$field))
				$query .= ' JOIN contact_phone_trans ON contact_phone_trans.contact_id = contact_master.id AND contact_phone_trans.phone_no != \'\' AND contact_phone_trans.is_default = "1" ';
			
			$query .= ' GROUP BY '.$selectfieldsmainsub.'
			HAVING COUNT(*) > 1) temp
			ON ';
						
			$query .= $tempjoindata;
			
			$query .= ' ORDER BY '.$selectfieldsmainsub;
			
			$query = $this->db->query($query);
			
			//pr($query->result_array());
			
			$data['datalist'] = $query->result_array();*/
			
			//////////////////////////////////////////////////////
			
			if(count($counter_data) > 0)
			{
				for($i=0;$i < count($counter_data);$i++)
				{
					$query = 'SELECT '.$selectfieldsmain.' FROM contact_master c';
					
					$query .= ' LEFT JOIN contact_emails_trans AS cet ON cet.user_id = c.id AND cet.email_address != \'\' AND cet.is_default = "1" ';
					
					$query .= ' LEFT JOIN contact_phone_trans AS cpt ON cpt.user_id = c.id AND cpt.phone_no != \'\' AND cpt.is_default = "1" ';
					
					$wherecond = ' WHERE ';
					
					foreach($field as $row)
					{
						if($row == 'first_name' || $row == 'middle_name' || $row == 'last_name')
							$wherecond .= ' c.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
						if($row == 'email_address')
							$wherecond .= ' cet.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
						if($row == 'phone_no')
							$wherecond .= ' cpt.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
					}
					
					$wherecond = rtrim($wherecond, "AND ");
					
					$query .= $wherecond;
					
					$query .= ' GROUP BY c.id';
					
					$query .= ' ORDER BY '.$selectfieldsmainsub;
					
					$query = $this->db->query($query);
					
					//echo $this->db->last_query();
					//exit;
					
					//pr($query->result_array());
					
					$data['datalist'][$i] = $query->result_array();
				}
			}
			
			//////////////////////////////////////////////////////
			
			//pr($data['datalist']);exit;
			
			return $data;
			
		}
		else
		{}
		
	}
	
	public function get_record_where_in($contacts)
	{
		$this->db->select('cm.*,GROUP_CONCAT(DISTINCT cet.email_address ORDER BY cet.is_default DESC) email,GROUP_CONCAT(DISTINCT cpt.phone_no ORDER BY cpt.is_default DESC) phone,cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country,csm.name AS sourcename,GROUP_CONCAT(DISTINCT ctm.name) contacttype,GROUP_CONCAT(DISTINCT ctm.id) user_id_list');
		$this->db->from($this->table_name.' as cm');
		$this->db->join('contact_emails_trans cet','cet.user_id = cm.id','left');
		$this->db->join('contact_phone_trans cpt','cpt.user_id = cm.id','left');
		$this->db->join('contact_address_trans cat','cat.user_id = cm.id','left');
		$this->db->join('contact__source_master csm','csm.id = cm.contact_source','left');
		$this->db->join('contact_contacttype_trans ctt','ctt.user_id = cm.id','left');
		$this->db->join('contact__type_master ctm','ctm.id = ctt.contact_type_id','left');
		$this->db->where_in('cm.id',$contacts);
		$this->db->group_by('cm.id');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();
		//pr($result);
		return $result;
	}
	
	public function get_record_where_in_contact_master($contacts)
	{
		$this->db->select('cm.*,cm.first_name,cm.middle_name,cm.last_name,CONCAT_WS(" ",cm.first_name,cm.middle_name,cm.last_name) as contact_name',false);
		$this->db->from($this->table_name.' as cm');
		$this->db->where_in('cm.id',$contacts);
		$result = $this->db->get()->result_array();
		return $result;
	}
	
	public function get_user_id_from_login($assigned_user_id='')
	{
		$this->db->select('*');
		$this->db->from('login_master');
		$this->db->where('user_id',$assigned_user_id);
		$result = $this->db->get()->row();
		
		//echo $this->db->last_query();
		
		//pr($result);
		
		if(!empty($result->id))
			return $result->id;
		else
			return 0;
	}
	/*
        @Description: Function For pagination in contact
        @Author     : Kaushik Valiya
        @Input      : contact id
        @Output     : Unique DB Name
        @Date       : 11-09-14
    */


	
	public function getemailpagingid2($email_id='')
	{
			
		$this->db->select('*');
		$this->db->from($this->table_name_user_contact);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;

		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
			
				if($row['user_id'] == $email_id)
				{
				
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*5;
				}
			}
		}
		
		return $op;
	}
	
	/*
        @Description: Function For pagination
        @Author     : Kaushik Valiya
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 11-09-14
    */
	

	public function getemailpagingid($email_id='')
	{
			
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $email_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
	
		return $op;
	}
	
	 /*
    @Description: Function is For Update password in USer side
    @Author: Kaushik Valiya
    @Input: Email Id  for Update into DB
    @Output: - Update records into DB with give id
    @Date: 18-09-2014
    */
	public function update_user_password_record($data)
    {
        $this->db->where('email_id',$data['email_id']);
        $query = $this->db->update($this->table_name_login_master,$data); 
		
    }
	public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='',$where_cond='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$tbl_name;
        $where='';
        
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE (';
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
			
			$where .= ')';
			
			if($where_cond)
        	{
				foreach($where_cond as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' = "'.$value.'")';
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$tbl_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
 	
	public function insert_parent_assistant_record($lastId='',$parent_db='')   
	{
		$sql_ins = "INSERT INTO ".$parent_db.".login_master (user_type, user_id, admin_name, email_id, address, phone,admin_pic ,brokerage_pic, user_license_no,number_of_users_allowed, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status, twilio_account_sid, twilio_auth_token, twilio_number) SELECT user_type, user_id, admin_name, email_id, address, phone,admin_pic ,  brokerage_pic, user_license_no,number_of_users_allowed, password, sha_key, db_name, host_name, db_user_name, db_user_password, created_date, created_by, modified_date, modified_by, status, twilio_account_sid, twilio_auth_token, twilio_number FROM login_master where id  = ".$lastId.";";
		$query = $this->db->query($sql_ins);
		//echo $this->db->last_query();exit;
	}
    public function update_user($data,$db_name='')
    {
        $this->db->where('user_id',$data['user_id']);
        if(!empty($db_name))
            $query = $this->db->update($db_name.".".$this->table_name_login_master,$data);
        else
            $query = $this->db->update($this->table_name_login_master,$data); 
    }
    
    /*
        @Description: Function for insert cron data
        @Author     : Sanjay Moghariya
        @Input      : cron data, table name
        @Output     : insert record
        @Date       : 23-05-2015
    */
    function insert_cron_data($data,$table_name)
    {
        $result =  $this->db->insert($table_name,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Update cron data
        @Author     : Sanjay Moghariya
        @Input      : cron data, db name, table name
        @Output     : update record
        @Date       : 23-05-2015
    */
    public function update_cron_data($data,$table_name)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($table_name,$data); 
    }
    
    /*
        @Description: Function for update property status change flag
        @Author     : Sanjay Moghariya
        @Input      : data, table name
        @Output     : update record
        @Date       : 23-05-2015
    */
    function update_property_status_flag($data,$table_name)
    {
        $this->db->where('ID',$data['ID']);
        $query = $this->db->update($table_name,$data); 
    }
    
    /*
        @Description: Function for insert domain transaction
        @Author     : Sanjay Moghariya
        @Input      : Domain data
        @Output     : Insert record into DB
        @Date       : 09-06-2015
    */
    function insert_domain_record($data,$tablename)
    {
        $result =  $this->db->insert($tablename,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
    
    /*
        @Description: Function for Delete domain trans
        @Author     : Sanjay Moghariya
        @Input      : domain id, user id
        @Output     : Delete record(s)
        @Date       : 09-06-2015
    */
    public function delete_domain_record($data,$tablename,$dbname='')
    {
        $this->db->where('domain_id',$data['domain_id']);
        $this->db->where('user_id',$data['user_id']);
        if(!empty($dbname))
            $this->db->delete($dbname.'.'.$tablename);
        else
            $this->db->delete($tablename);
    }
}