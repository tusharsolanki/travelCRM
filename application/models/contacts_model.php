<?php

	/*
    @Description: contacts Model
    @Author: Nishit Modi
    @Input: 
    @Output: 
    @Date: 04-07-2014
	*/

class contacts_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'contact_master';
		$this->table_name_archive = 'contact_archive_master';
		$this->table_name_contact_type = 'contact_contacttype_trans';
		$this->table_name_email_trans = 'contact_emails_trans';
		$this->table_name_phone_trans = 'contact_phone_trans';
		$this->table_name_website_trans = 'contact_website_trans';
		$this->table_name_social_trans = 'contact_social_trans';
		$this->table_name_tag_trans = 'contact_tag_trans';
		$this->table_name_address_trans = 'contact_address_trans';
		$this->table_name_communication_trans = 'interaction_plan_contacts_trans';
		$this->table_name_doc_trans = 'contact_documents_trans';
		$this->table_name_contact_csv = 'contact_csv_master';
		$this->table_name_contact_source_master = 'contact__source_master';
		$this->table_name_contact_csv_mapping_master = 'contact__csv_mapping_master';
		$this->table_name_contact_csv_mapping_trans = 'contact__csv_mapping_trans';
		$this->table_name_user_contact_trans = 'user_contact_trans';	
		$this->table_name_interaction_plan_interaction_trans='interaction_plan_contact_communication_plan';
		$this->table_name_interaction_plan_per_touches='interaction_plan_contact_personal_touches';
		$this->table_name_contact_conversations_trans_touches='contact_conversations_trans';
		$this->table_name_contact_field_trans='contact_additionalfield_trans';
		$this->table_name_chat_history='contact_chat_history';
		$this->table_name_login = 'login_master';
		$this->table_name_contact__status_trans = 'contact_contact_status_trans';
		$this->table_chat_last_sync='fb_chat_last_sync';
		$this->table_name_user_right_trans_master = 'user_rights_trans';
		$this->linkedin_invitation='contact_invitation_transcation';
		$this->table_linkedin = 'contact_linkedin_trasection';
		$this->table_twitter = 'contact_twitter_trasection';
		$this->table_notes = 'contact_notes_trans';
		$this->table_last_seen = 'contact_listing_last_seen';
        $this->table_status_master = 'contact__status_master';
		}

    /*
    @Description: Function for get Module Lists
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name;}
		else
		{$sql .= ' FROM '.$this->table_name;}
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
        return $query->result_array();
    }
	




    public function select_records5($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_email_trans;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_email_trans.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }

	
	/*
    @Description: Function for get Module Lists
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
   
    public function select_archive_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name_archive;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_archive.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
	}
	/*
		@Description: Function for get Module Lists Multiple tables
		@Author: Nishit Modi
		@Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
		@Output: Assignmodule list
		@Date: 04-07-2014
	*/
	
	    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='')
    {
        
		//pr($match_values);exit;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$tbl_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
        return $query->result_array();
    }
    

	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='',$having='',$or_where='')
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
				//$this->db->join($coll, $value,$join_type);
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
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
			}
		}
                
                if(!empty($or_where)){
			foreach($or_where as $key => $value){
				$this->db->or_where($key,$value);
			}
		}
			
		if($group_by != null)
		$this->db->group_by($group_by);
		if($having != null)
			$this->db->having($having);
		if($orderby != null && $sort != null)
			$this->db->order_by($orderby,$sort);
		elseif($orderby != null )
		{
			if($orderby == 'special_case')
				$this->db->order_by('is_done asc,task_date asc');
			elseif($orderby == 'special_case_task')
				$this->db->order_by('id desc');
			else
				$this->db->order_by($orderby);
		}
		
				
		if($match_values != null &&  $compare_type != null )
		$this->db->or_like($match_values);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
               //echo $this->db->last_query();exit;
                if(!empty($totalrow))
                     
                    return $query_FC->num_rows();
		else
                   
                    return $query_FC->result_array();
  
	}
     
	public function contact_select_records($data)
	{
		if(!empty($data['interaction_contacts']))
		{
			$query = "SELECT * FROM contact_master WHERE id IN (".$data['interaction_contacts']. ") ORDER BY ".$data['sort_by']." ASC" ;
			$result = $this->db->query($query);
			return $result->result_array();
		}
		else
		{
			return "";
		}
	}

    /*
    @Description: Function is for Insert contacts details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-07-2014
    */
	
    function insert_record($data,$db_name='')
    {
		if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.'.$this->table_name,$data);}
		else
		{$result =  $this->db->insert($this->table_name,$data);}
		$lastId = $this->db->insert_id();
		return $lastId;
    }

    function insert_linkedin_invitation($data)
    {
        $result =  $this->db->insert($this->linkedin_invitation,$data);
		$lastId = $this->db->insert_id();
		return $lastId;
    }

    function get_graph_data($date1='',$date2='')
    {
	  //   SELECT COUNT(id),DATE_FORMAT(created_date,'%m/%d/%Y') FROM contact_master GROUP BY DATE_FORMAT(created_date, '%Y%m%d');
		//$query=$this->db->query("SELECT DATE_FORMAT(created_date,'%m/%d/%Y')created_date,count(id) as contact_count FROM ".$this->table_name." GROUP BY DATE_FORMAT(created_date, '%Y%m%d')");
		
		$sql = "SELECT DATE_FORMAT(v.selected_date,'%m/%d/%Y') created_date,COUNT(cm.id) contact_count FROM 
(SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
LEFT JOIN contact_master cm ON DATE_FORMAT(cm.created_date,'%Y-%m-%d') = v.selected_date WHERE ";
if(!empty($date1) && !empty($date2))
	$sql .= "selected_date BETWEEN '".$date1."' AND '".$date2."'";
else
	$sql .= "selected_date BETWEEN '".date('Y-m-d', strtotime('-30 days'))."' AND '".date('Y-m-d')."'";
//$sql .= "selected_date BETWEEN '2014-06-10' AND '2014-10-10'";
$sql .= " GROUP BY selected_date";
		
		$query = $this->db->query($sql);
		//echo $this->db->last_query(); exit;

		//"SELECT * FROM ".$this->table_name." WHERE created_date BETWEEN '2014-10-08' AND '2014-10-10'"
		//$this->db->select('id As total_contacts');
		//$this->db->from($this->table_name);
		//$this->db->where('created_date');
        //$this->db->between('')
		//echo $this->db->last_query();exit;
		
		/*$sql = "SELECT DATE_FORMAT(v.selected_date,'%m/%d/%Y') created_date,COUNT(cm.id) contact_count FROM 
(SELECT ADDDATE('1970-01-01',t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i) selected_date FROM
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t0,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t3,
 (SELECT 0 i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t4) v
LEFT JOIN contact_master cm ON DATE_FORMAT(cm.created_date,'%Y-%m-%d') = v.selected_date WHERE ";
if(!empty($date1) && !empty($date2))
	$sql .= "selected_date BETWEEN '".$date1."' AND '".$date2."'";
else
	$sql .= 'selected_date >= (DATE_SUB(CURDATE(),INTERVAL 1 MONTH))';
$sql .= "selected_date BETWEEN '2014-09-10' AND '2014-10-10'";
$sql .= " GROUP BY selected_date";
		
		$query=$this->db->query($sql);*/
		
	    return $query->result_array();

		//$result = $this->db->get()->result_array();
		
    
	}


	
	function get_monthly_graph_data($date1='',$date2='')
    {
		$sql = "select DATE_FORMAT(m1, '%b-%Y') created_date,count(cm.id) contact_count from(select ('".$date1."' - INTERVAL DAYOFMONTH('".$date1."')-1 DAY) +INTERVAL m MONTH as m1 from(select @rownum:=@rownum+1 as m from(select 1 union select 2 union select 3 union select 4) t1,(select 1 union select 2 union select 3 union select 4) t2,(select 1 union select 2 union select 3 union select 4) t3,(select 1 union select 2 union select 3 union select 4) t4,(select @rownum:=-1) t0) d1) d2 LEFT JOIN contact_master cm ON DATE_FORMAT(cm.created_date,'%b-%Y') = DATE_FORMAT(m1, '%b-%Y') where m1<='".$date2."' group by m1
order by m1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	
	function get_yearly_graph_data($date1='',$date2='')
    {
		$sql = "select DATE_FORMAT(m1, '%Y') created_date,count(cm.id) contact_count from(select ('".$date1."' - INTERVAL DAYOFMONTH('".$date1."')-1 DAY) +INTERVAL m YEAR as m1 from(select @rownum:=@rownum+1 as m from(select 1 union select 2 union select 3 union select 4) t1,(select 1 union select 2 union select 3 union select 4) t2,(select 1 union select 2 union select 3 union select 4) t3,(select 1 union select 2 union select 3 union select 4) t4,(select @rownum:=-1) t0) d1) d2 LEFT JOIN contact_master cm ON DATE_FORMAT(cm.created_date,'%Y') = DATE_FORMAT(m1, '%Y') where m1<='".$date2."' group by m1
order by m1";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	
	
	/*
    @Description: Function is for Insert contacts details by Admin
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-07-2014
    */
	
    function insert_contact_csv($data)
    {
        $result =  $this->db->insert($this->table_name_contact_csv,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	/*
    @Description: Function is for Insert contacts transaction
    @Author: Nishit Modi
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 10-07-2014
    */
	function insert_contact_conversation($data,$db_name='')
    {
	  	if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.'.$this->table_name_contact_conversations_trans_touches,$data);	}
		else
		{$result =  $this->db->insert($this->table_name_contact_conversations_trans_touches,$data);	}
		$lastId = $this->db->insert_id();
		return $lastId;
    }

	function insert_chat_history($data)
    {
	    $result =  $this->db->insert($this->table_name_chat_history,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	function insert_interaction_plan_contact_per_touches($data)
    {
        $result =  $this->db->insert($this->table_name_interaction_plan_per_touches,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	function insert_user_contact_trans_record($data,$db_name='')
    {
        if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.'.$this->table_name_user_contact_trans,$data);	}
		else
		{$result =  $this->db->insert($this->table_name_user_contact_trans,$data);	}
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_contact_mapping_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_contact_csv_mapping_trans,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }

	function insert_contact_mapping_record($data)
    {
        $result =  $this->db->insert($this->table_name_contact_csv_mapping_master,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_contact_type_record($data,$db_name='')
    {
        if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.'.$this->table_name_contact_type,$data);}
		else
		{$result =  $this->db->insert($this->table_name_contact_type,$data);}	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_email_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.'.$this->table_name_email_trans,$data);}
		else
		{$result =  $this->db->insert($this->table_name_email_trans,$data);}	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_phone_trans_record($data,$db_name='')
    {
        if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_phone_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_phone_trans,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_website_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_website_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_website_trans,$data);
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_social_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_social_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_social_trans,$data);
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_tag_record($data,$db_name='')
    {
		if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_tag_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_tag_trans,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_address_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_address_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_address_trans,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_communication_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_communication_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_communication_trans,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_doc_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_doc_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_doc_trans,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }

	function insert_field_trans_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_contact_field_trans,$data);	
        else
            $result =  $this->db->insert($this->table_name_contact_field_trans,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	
	function insert_notes_record($data,$db_name='')
    {
	  	if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name_contact_conversations_trans_touches,$data);	
        else
            $result =  $this->db->insert($this->table_name_contact_conversations_trans_touches,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert contacts transaction
    @Author: Kaushik Valiya
    @Input: contacts details for Insert into DB
    @Output: - Insert record into DB
    @Date: 80-10-2014
    */
	function insert_contact_contact_status_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_contact__status_trans,$data);	
	  
		$lastId = $this->db->insert_id();
		return $lastId;
    }
	function insert_chat_last_sync($data)
    {
        $result =  $this->db->insert($this->table_chat_last_sync,$data);	
	  
		$lastId = $this->db->insert_id();
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
	function select_contact_chat_message_list($id='',$part1='',$part2='',$user='')
    {
		$this->db->select('*');
		$this->db->from($this->table_chat_last_sync);
		$where = 'contact_id = '.$id.' AND created_by = '.$user.' AND ((participent1 = '.$part1.' AND participent2 = '.$part2.') OR (participent1 = '.$part2.' AND participent2 = '.$part1.'))';
		$this->db->where($where);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		return $result;
    }

	function select_contact_conversation_trans_record1($cont_id,$user_id)
    {
		$this->db->select('max(id)');
		$this->db->from($this->table_name_contact_conversations_trans_touches);
		$result1 = $this->db->get()->result_array();
		$last_id=$result1[0]['max(id)'];
		
		$this->db->select('*');
		$this->db->from($this->table_name_contact_conversations_trans_touches);
		$this->db->where('contact_id',$cont_id);
		$this->db->where('id',$last_id);
		$this->db->where('assign_to',$user_id);
		$result = $this->db->get()->row();
		return $result;
    }
	
	function select_contact_conversation_trans_record($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_conversations_trans_touches);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		
		return $result;
    }

	function select_field_trans_record($contact_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_field_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		
		return $result;
    }

	
	function select_contact_conversation_trans_record_by_contact_id($contact_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_conversations_trans_touches);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	function select_user_contact_trans_record($contact_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_user_contact_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		
		return $result;
    }
	function select_user_contact_trans_record_user_side($contact_id,$user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_user_contact_trans);
		$this->db->where('contact_id',$contact_id);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		
		return $result;
    }
	function select_contact_type_record($contact_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_type);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_email_trans_record($contact_id,$orderby='',$ordertype='')
    {
		$this->db->select('*');
		$this->db->from($this->table_name_email_trans);
		$this->db->where('contact_id',$contact_id);
		
		if(!empty($orderby) && !empty($ordertype))
			$this->db->order_by($orderby,$ordertype);
		
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_phone_trans_record($contact_id,$orderby='',$ordertype='')
    {
        $this->db->select('*');
		$this->db->from($this->table_name_phone_trans);
		$this->db->where('contact_id',$contact_id);
		
		if(!empty($orderby) && !empty($ordertype))
			$this->db->order_by($orderby,$ordertype);
		
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_website_trans_record($contact_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_website_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	function select_social_trans_record($contact_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_social_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	function select_social_name_twitter($id)
    {
		//echo 1;exit;
		$this->db->select('distinct(website_name) as website_name');
		$this->db->from($this->table_name_social_trans.' as sm');
		$this->db->join('contact_master cm','cm.id = sm.contact_id','left');
		if(!empty($id))
		{
			$this->db->where('cm.created_by',$id);
		}	
		$this->db->where('sm.profile_type','2');
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	/*function select_social_trans_record($contact_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_social_trans);
		$this->db->where('contact_id',$contact_id);
		$this->db->where('(profile_type = 1 OR profile_type = 2)');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		return $result;
		
    }*/
	
	function select_tag_record($contact_id='',$id='',$flag='')
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_tag_trans);
		if(!empty($flag) && !empty($contact_id))
		{
			$this->db->where('is_default = "2" AND tag NOT IN (SELECT tag FROM '.$this->table_name_tag_trans.' WHERE contact_id = '.$contact_id.')');
		}
		elseif(!empty($contact_id))
			$this->db->where('contact_id',$contact_id);
		elseif(!empty($id))
			$this->db->where('id',$id);
		else
			$this->db->where('is_default','2');
		$this->db->group_by('tag');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		return $result;
		//SELECT * FROM contact_tag_trans WHERE `is_default` = '2' AND tag NOT IN ( SELECT tag FROM `contact_tag_trans` WHERE `contact_id` =702 )GROUP BY `tag`

    }
	
	function select_address_trans_record($contact_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_address_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
       
    }
	
	function select_communication_trans_record($contact_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_communication_trans);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	
	function select_document_trans_record($contact_id)
    {
	  	$this->db->select($this->table_name_doc_trans.'.*,cdtm.name');
		$this->db->from($this->table_name_doc_trans);
		$this->db->join('contact__document_type_master cdtm','cdtm.id = '.$this->table_name_doc_trans.'.doc_type','left');
		$this->db->where('contact_id',$contact_id);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		return $result;
    }
	
	function select_personal_touches_trans_record($contact_id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_interaction_plan_per_touches);
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	
	function select_document_trans_record_ajax($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_doc_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
		
    }
	function select_document_trans_record_contact_id($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_doc_trans);
		$this->db->where('contact_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	function select_csv_record($id)
    {
		
	  	$this->db->select('*');
		$this->db->from($this->table_name_contact_csv);
		$this->db->where('id',$id);
		$result = $this->db->get()->result_array();
		return $result;
		
    }
	function select_contact_status_trans_record_contact_id($id,$contact_status)
    {
	  	
		$this->db->select('max(id)');
		$this->db->from($this->table_name_contact__status_trans);
		$result1 = $this->db->get()->result_array();
		$last_id=$result1[0]['max(id)'];
		
		$this->db->select('*');
		$this->db->from($this->table_name_contact__status_trans);
		$this->db->where('contact_id',$id);
		$this->db->where('id',$last_id);
		$this->db->where('contact_status_id',$contact_status);
		$result = $this->db->get()->result_array();

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
	public function update_converstion_tran_record($data)
    {
        $this->db->where('id',$data['id']);
		$query = $this->db->update($this->table_name_contact_conversations_trans_touches,$data); 
		
	}

	public function update_field_tran_record($data)
    {
 		$this->db->where('id',$data['id']);
		$query = $this->db->update($this->table_name_contact_field_trans,$data); 
		
	}
	
	public function update_contact_per_tou_tran_record($data)
    {
        $this->db->where('id',$data['id']);
		$query = $this->db->update($this->table_name_interaction_plan_per_touches,$data); 
		
	}
	public function update_interaction_plan_interaction_transtrans_record($data,$icdata='',$db_name='')
    {
		if(!empty($db_name) && !empty($icdata))
		{
			$this->db->where('contact_id',$icdata['contact_id']);
			$this->db->where('interaction_plan_interaction_id',$icdata['interaction_plan_interaction_id']);
			$this->db->where('is_done','0');
			$query = $this->db->update($db_name.'.'.$this->table_name_interaction_plan_interaction_trans,$icdata);
		}
		if(!empty($icdata))
		{
			$this->db->where('contact_id',$icdata['contact_id']);
			$this->db->where('interaction_plan_interaction_id',$icdata['interaction_plan_interaction_id']);
			$this->db->where('is_done','0');
			$query = $this->db->update($this->table_name_interaction_plan_interaction_trans,$icdata);
		}
		else
		{
        	$this->db->where('id',$data['id']);
			//pr($data);exit;
			$query = $this->db->update($this->table_name_interaction_plan_interaction_trans,$data); 
			//echo $this->db->last_query();exit;
		}
		
    }
	public function update_user_contact_trans_record($data)
    {
        $this->db->where('contact_id',$data['id']);
        $query = $this->db->update($this->table_name_user_contact_trans,$data); 
    }
	
	public function update_user_contact_trans_record_by_id($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_user_contact_trans,$data); 
    }
	
    public function update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
        if(!empty($db_name))
            $query = $this->db->update($db_name.'.'.$this->table_name,$data);
        else
            $query = $this->db->update($this->table_name,$data);

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
    
    public function update_defualt_phone_trans_record($data)
    {
        $this->db->where('contact_id',$data['contact_id']);
        $query = $this->db->update($this->table_name_phone_trans,$data); 
    }
	
	public function update_tag_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_tag_trans,$data); 
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
	
	public function update_communication_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_communication_trans,$data); 
    }
	
	public function update_doc_trans_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_doc_trans,$data); 
    }
	
	public function update_chat_last_sync($data)
    {
        $this->db->where('id',$data['id']);
		$query = $this->db->update($this->table_chat_last_sync,$data); 
		
	}

	/*
    @Description: Function for Update contacts FB to Admin
    @Author: Kaushik Valiya
    @Input: - contacts id which is update FB to admin
    @Output: - Update recodrs from DB with match ID
    @Date: 04-07-2014
    */
	
	
	public function update_address_trans_FB_record($data)
    {
        $this->db->where('contact_id',$data['contact_id']);
        $query = $this->db->update($this->table_name_address_trans,$data); 
    }
	public function update_email_trans_FB_record($data)
    {
        $this->db->where('contact_id',$data['contact_id']);
        $query = $this->db->update($this->table_name_email_trans,$data); 
    }
	
    /*
    @Description: Function for Delete contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - contacts id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 04-07-2014
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	/*
    @Description: Function for Delete contacts Profile By Admin
    @Author: Nishit Modi
    @Input: - contacts id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 19-09-2014
    */
    public function delete_archive_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_archive);
    }
	
	/*
    @Description: Function for Delete  for all transaction  table data data
    @Author: Kaushik Valiya
	@Input: - id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 12-07-2014
    */
	public function delete_table_conversations($id)
    {
        $this->db->where('id',$id);
 		$this->db->delete($this->table_name_contact_conversations_trans_touches);
	}
	public function delete_table_user_contact_record($id='',$data='')
    {
		if(!empty($data))
			$this->db->where($data);
		else
        	$this->db->where('contact_id',$id);
 		$this->db->delete($this->table_name_user_contact_trans);
	}
	public function delete_table_trans_record($contact_id,$table)
    {
        $this->db->where('contact_id',$contact_id);
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

	public function delete_field_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_contact_field_trans);
    }
	
	public function delete_social_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_social_trans);
    }
	
	public function delete_tag_trans_record($id='',$contact_id='')
    {
		if(!empty($id))
        	$this->db->where('id',$id);
		elseif(!empty($contact_id))
			$this->db->where('contact_id',$contact_id);
        $this->db->delete($this->table_name_tag_trans);
    }
	
	public function delete_not_in_tag_trans_record($data,$contact_id)
    {
		$this->db->where('contact_id',$contact_id);
		$this->db->where_not_in('id',$data);
        $this->db->delete($this->table_name_tag_trans);
    }
	
	public function delete_communication_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_communication_trans);
    }
	
	public function delete_contact_type_record($id)
    {
        $this->db->where('contact_id',$id);
        $this->db->delete($this->table_name_contact_type);
    }
	
	public function delete_document_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_doc_trans);
    }
	
	/*
    @Description: Function for Delete  for all transaction  table data 
    @Author: Kaushik Valiya
	@Input: - id and Table name which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 09-09-2014
    */
    public function delete_all_trans_table_record($id='',$table_name_trans,$joomla_id='')
    {
        if(!empty($id))
            $this->db->where('contact_id',$id);
        else
            $this->db->where('lw_admin_id',$joomla_id);
        $this->db->delete($table_name_trans);
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
	
	public function merge_search_contacts($field,$login_id='',$user_id='')
	{
		if(!empty($field) && count($field) > 0)
		{	
			
			$selectfieldsmain = 'c.id,c.first_name,c.middle_name,c.last_name,CONCAT_WS(" ",c.first_name,c.middle_name,c.last_name) as contact_name,cet.email_address,cpt.phone_no,CONCAT_WS(",",cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country) as full_address';
			$selectfieldsmainsub = '';
			//$tempjoindata = '';
		
			foreach($field as $row)
			{
				if($row != '')
				{
					/*if($row == 'email_address')
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
					}*/
						
					$selectfieldsmainsub .= $row.',';	
				}
			}
			
			$selectfieldsmain = rtrim($selectfieldsmain, ",");
			$selectfieldsmainsub = rtrim($selectfieldsmainsub, ",");
			//$tempjoindata = rtrim($tempjoindata, "AND ");
			
			
			///////////////////////////////////////////////////////////
			
			
			$query = 'SELECT '.$selectfieldsmainsub.',COUNT(*) rowspan
			FROM contact_master';
			
			if(in_array('email_address',$field))
				$query .= ' JOIN contact_emails_trans ON contact_emails_trans.contact_id = contact_master.id AND contact_emails_trans.email_address != \'\' AND contact_emails_trans.is_default = "1" ';
				
			if(in_array('phone_no',$field))
				$query .= ' JOIN contact_phone_trans ON contact_phone_trans.contact_id = contact_master.id AND contact_phone_trans.phone_no != \'\' AND contact_phone_trans.is_default = "1" ';
				
			if(in_array('address_line1',$field))
				$query .= ' JOIN contact_address_trans ON contact_address_trans.contact_id = contact_master.id AND contact_address_trans.address_line1 != \'\' ';
				
			if(!empty($login_id) && !empty($user_id))
			{
				$query .= ' LEFT JOIN user_contact_trans ON user_contact_trans.contact_id = contact_master.id';
				$query .= ' where contact_master.created_by IN ('.$login_id.') OR user_contact_trans.user_id = '.$user_id;
				/*$query .= ' where ';
				if(!empty($login_id))
					$query .= 'contact_master.created_by IN ('.$login_id.')';
				if(!empty($user_id))
					 $query .= ' OR user_contact_trans.user_id = '.$user_id;*/
				
			}
			$query .= ' GROUP BY '.$selectfieldsmainsub.'
			HAVING COUNT(*) > 1';
			
			$query = $this->db->query($query);
			
			$counter_data = $query->result_array();			
			
			$data['counter_data'] = $query->result_array();
			
			//echo $this->db->last_query();exit;
			//pr($data['counter_data']);exit;
			
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
					
					$query .= ' LEFT JOIN contact_emails_trans AS cet ON cet.contact_id = c.id AND cet.email_address != \'\' AND cet.is_default = "1" ';
					
					$query .= ' LEFT JOIN contact_phone_trans AS cpt ON cpt.contact_id = c.id AND cpt.phone_no != \'\' AND cpt.is_default = "1" ';
					
					$query .= ' LEFT JOIN contact_address_trans AS cat ON cat.contact_id = c.id AND cat.address_line1 != \'\' ';
					
					$query .= ' LEFT JOIN user_contact_trans as uct ON uct.contact_id = c.id';
					
					$wherecond = ' WHERE ';
					
					foreach($field as $row)
					{
						if($row != '')
						{
							if($row == 'first_name' || $row == 'middle_name' || $row == 'last_name')
								$wherecond .= ' c.'.$row.' = "'.$counter_data[$i][$row].'" AND c.'.$row.' != "" AND ';
							if($row == 'email_address')
								$wherecond .= ' cet.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
							if($row == 'phone_no')
								$wherecond .= ' cpt.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
							if($row == 'address_line1')
								$wherecond .= ' cat.'.$row.' = "'.$counter_data[$i][$row].'" AND ';
						}
					}
					
					$wherecond = rtrim($wherecond, "AND ");
					
					$query .= $wherecond;
					if(!empty($login_id) && !empty($user_id))
					{
						$query .= ' AND (c.created_by IN ('.$login_id.') OR uct.user_id = '.$user_id.')';
					}
					$query .= ' GROUP BY c.id';
					
					$query .= ' ORDER BY '.$selectfieldsmainsub;
					
					$query = $this->db->query($query);
					
					$data['datalist'][$i] = $query->result_array();
				}
			}
			
			//////////////////////////////////////////////////////
			
			return $data;
			
		}
		else
		{}
		
	}
	
	public function get_record_where_in($contacts)
	{
		$this->db->select('cm.*,GROUP_CONCAT(DISTINCT cet.email_address ORDER BY cet.is_default DESC) email,GROUP_CONCAT(DISTINCT cpt.phone_no ORDER BY cpt.is_default DESC) phone,cat.address_line1,cat.address_line2,cat.city,cat.state,cat.zip_code,cat.country,csm.name AS sourcename,GROUP_CONCAT(DISTINCT ctm.name) contacttype,GROUP_CONCAT(DISTINCT ctm.id) contact_id_list');
		$this->db->from($this->table_name.' as cm');
		$this->db->join('contact_emails_trans cet','cet.contact_id = cm.id','left');
		$this->db->join('contact_phone_trans cpt','cpt.contact_id = cm.id','left');
		$this->db->join('contact_address_trans cat','cat.contact_id = cm.id','left');
		$this->db->join('contact__source_master csm','csm.id = cm.contact_source','left');
		$this->db->join('contact_contacttype_trans ctt','ctt.contact_id = cm.id','left');
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
	public function get_record_where_in_user_master($contacts)
	{
		$this->db->select('cm.*,cm.admin_name as contact_name',false);
		$this->db->from($this->table_name_login.' as cm');
		$this->db->where_in('cm.id',$contacts);
		$result = $this->db->get()->result_array();
		return $result;
	}
	public function select_contacts_in_query($data)
	{
		$this->db->where_in('id',$id);
		$result = $this->db->get('contact_master');
      //$result = $this->db->query('SELECT * FROM contact_master WHERE id in('.$id.')');
		return $result->result_array();
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
		//echo $email_id;exit;
		$this->db->select('contact_master.id');
		$this->db->from('contact_master');
		$this->db->order_by('id','desc');
		$query_FC = $this->db->get();
		
  		$result = $query_FC->result_array();
		
		//echo $this->db->last_query();exit;
		
		//pr($result);
		
		//echo count($result);
		
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
		
		//echo $op;exit;
	
		return $op;
	}
	
	/*
        @Description: Function For pagination by User Side
        @Author     : Kaushik Valiya
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 13-09-14
    */

	
	public function getemailpagingid_user($email_id='',$user_id='')
	{
			
		$this->db->select('cm.*','uct.contact_id');
		$this->db->from($this->table_name.' as cm');
		$this->db->order_by('cm.id','desc');
		$this->db->join('user_contact_trans as uct','uct.user_id = cm.id','left');
		$this->db->where('cm.created_by',$user_id);
		$this->db->or_where('uct.user_id',$user_id);
		
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

	public function archive_record($cid='')
	{
		$this->db->select($this->table_name.'.*');
		$this->db->from($this->table_name);
		$this->db->where('id',$cid);
		
		$result = $this->db->get()->result_array();
		
		foreach($result as $row)
		{
			$acdata = array();
			foreach($row as $key=>$value)
				$acdata[$key] = $value;
			
			$result =  $this->db->insert('contact_archive_master',$acdata);
			
			$this->db->where('id',$cid);
	        $this->db->delete($this->table_name);
		}
		
	}
	
	public function add_to_list_record($cid='')
	{
		$this->db->select('contact_archive_master.*');
		$this->db->from('contact_archive_master');
		$this->db->where('id',$cid);
		
		$result = $this->db->get()->result_array();
		
		foreach($result as $row)
		{
			$acdata = array();
			foreach($row as $key=>$value)
				$acdata[$key] = $value;
			
			$result =  $this->db->insert('contact_master',$acdata);
			
			$this->db->where('id',$cid);
	        $this->db->delete('contact_archive_master');
		}
		
	}

	
	public function get_all_contacts_count()
	{
		$this->db->select('COUNT(*) AS total_contacts');
		$this->db->from('contact_master');
		
		$result = $this->db->get()->result_array();
		if(count($result) > 0)
			return $result[0]['total_contacts'];
		else
			return 0;
	}
	
	public function get_assigned_contacts_count()
	{
		$this->db->select('COUNT(*) AS total_contacts');
		$this->db->from('contact_master');
		//$this->db->where_in('id','SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans');
		$this->db->where("id IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans WHERE is_completed = '0')", NULL, FALSE);
		
		$result = $this->db->get()->result_array();
		if(count($result) > 0)
			return $result[0]['total_contacts'];
		else
			return 0;
	}
	
	public function get_not_assigned_contacts_count()
	{
		$this->db->select('COUNT(*) AS total_contacts');
		$this->db->from('contact_master');
		//$this->db->where_not_in('id','SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans');
		$this->db->where("id NOT IN (SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans WHERE is_completed = '0')", NULL, FALSE);
		
		$result = $this->db->get()->result_array();
		if(count($result) > 0)
			return $result[0]['total_contacts'];
		else
			return 0;
	}
	
	public function get_last_month_contacts_count($date1='',$date2='')
	{
		$this->db->select('COUNT(*) AS total_contacts');
		$this->db->from('contact_master');
		//$this->db->where_not_in('id','SELECT DISTINCT contact_id FROM interaction_plan_contacts_trans');
		//$this->db->where('created_date >=', '2014-09-08');
        //$this->db->where('created_date <=', '2014-10-08');
		if(!empty($date1) && !empty($date2))
			$this->db->where("DATE_FORMAT(created_date,'%Y-%m-%d') BETWEEN '".$date1."' AND '".$date2."'", NULL, FALSE);
		else
			$this->db->where("created_date >= (DATE_SUB(CURDATE(),INTERVAL 1 MONTH))", NULL, FALSE);
		
		$result = $this->db->get()->result_array();
		//echo date();
		//echo $this->db->last_query();exit;
		if(count($result) > 0)
			return $result[0]['total_contacts'];
		else
			return 0;
	}
	
	public function check_sync_exist($data)
	{
		$this->db->select('*');
		$this->db->where($data);
		$result = $this->db->get('fb_chat_last_sync');
		return $result->result_array();
	}
	
	
	/*
    @Description: Check User Login In Which Right 
    @Author: Kaushik Valiya	
    @Input: Login Id
    @Output: - Right List
    @Date: 13-10-2014
    */
	
	function select_user_rights_trans_edit_record($user_id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_user_right_trans_master);
		$this->db->where('user_id',$user_id);
		$result = $this->db->get()->result_array();
		return $result;
    }
	/*
    @Description: Function is for Insert Task details by Admin
    @Author: Kaushik Valiya
    @Input: User list details for Insert into DB
    @Output: - List Type 3 User
    @Date: 13-10-2014
    */
	 function get_admin_users_list()
    {
		//'lm.id','lm.admin_name','lm.user_id','lm.status','lm.user_type','um.agent_id','CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name'
        	$this->db->select('lm.id as master_id,lm.user_id,lm.status,lm.user_type,um.agent_id,CONCAT_WS(" ",um.first_name,um.middle_name,um.last_name) as user_name,um.status as user_status',false);
		$this->db->from('user_master as um');
		$this->db->join('login_master as lm','um.id = lm.user_id','left');
		$this->db->where("lm.user_type = '3' AND um.status = '1'");

	//	$this->db->group_by('lm.id');
	
		$this->db->group_by('lm.id');
		$query_FC = $this->db->get();
		
		//echo $this->db->last_query();
		
		//pr($query_FC->result_array());
		
		return $query_FC->result_array();
    }
	
	function update_interaction_plan_contacts_trans($data)
	{
		$this->db->where('interaction_plan_id',$data['interaction_plan_id']);
		$this->db->where('contact_id',$data['contact_id']);
		$this->db->update($this->table_name_communication_trans,$data);
		//echo $this->db->last_query(); exit;
	}
	
	function get_all_client_contact($totalrow='')
	{
		$this->db->select('cm.id');
		$this->db->from('contact_master as cm');
		$this->db->join('contact_contact_status_trans as ccst','ccst.contact_id = cm.id');
		$this->db->where('cm.contact_status','4');
		$this->db->group_by('cm.id');
		$this->db->having('count(ccst.contact_id) > 1');
		//$this->db->or_where('uct.user_id',$user_id);
		$query = $this->db->get();
		if(!empty($totalrow))
			return $query->num_rows();
		else
			return $query->result_array();	
		//return $result;			//exit;
	}
	
	public function delete_interaction_plan_interaction_communication($data='',$cdata='')
    {
		if(!empty($cdata))
			$this->db->where($cdata);
		else
        	$this->db->where('id',$data['id']);
        $this->db->delete($this->table_name_interaction_plan_interaction_trans);
    }
	
	public function select_contact_disposition_master()
	{
		$this->db->select('*');
		$result = $this->db->get('contact__disposition_master')->result_array();
		return $result;
	}
	
	public function get_interaction_plan_contact_communication_plan($data)
	{
		$this->db->where($data);
		$result = $this->db->get($this->table_name_interaction_plan_interaction_trans);
		return $result->result_array();
	}
	//contact_linkedin_trasection
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 28-11-14
    */
   
    public function select_records3($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_linkedin;
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
	function insert_linkedin_trasection($data)
	{
		$result =  $this->db->insert($this->table_linkedin,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
	}
	//contact_twitter_trasection
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 28-11-14
    */
   
    public function select_records4($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_twitter;
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
	function insert_twitter_trasection($data)
	{
		$result =  $this->db->insert($this->table_twitter,$data);	
		$lastId = $this->db->insert_id();
		return $lastId;
	}

	public function insert_last_seen($data)
	{
		$this->db->insert($this->table_last_seen,$data);
		$lastId = $this->db->insert_id();
		return $lastId;
	}
	
	public function update_last_seen($data)
	{
		$this->db->where('login_id',$data['login_id']);
		$this->db->update($this->table_last_seen,$data);
	}
}