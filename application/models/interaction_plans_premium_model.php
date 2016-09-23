<?php

	/*
    @Description: tips Model
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/

class interaction_plans_premium_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'interaction_plan_master_premium';
		$this->table_name1 = 'interaction_plan_contacts_trans';
		$this->table_name2 = 'interaction_plan_contact_communication_plan';
		$this->table_name3 = 'interaction_plan_time_trans';
		$this->table_name4 = 'interaction_plan_adminuser_trans';
		$this->table_name_contact_conve_trans = 'contact_conversations_trans';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Jayesh Rojasara
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
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
		//echo $this->db->last_query();
        return $query->result_array();
    }
	
	
	
	public function select_records_plan_contact_trans($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name1;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	
	
	
	public function select_records_plan_time_trans($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name3;
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name3.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	
    

    public function select_records_intraction_plan()
    {
        /*$select_intraction_plan = $this->db->query("SELECT ipm.*,ipsm.name As plan_status_name FROM `interaction_plan_master` ipm
JOIN interaction_plan__status_master ipsm ON ipsm.id = ipm.plan_status ORDER BY ipm.created_date DESC");*/
		$this->db->select('ipm.*,ipsm.name As plan_status_name');
		$this->db->from('interaction_plan_master ipm');
		$this->db->join('interaction_plan__status_master ipsm','ipsm.id = ipm.plan_status');
		$this->db->order_by('ipm.created_date','DESC');
		//$query = $select_intraction_plan->result_array();
        $query = $this->db->get()->result_array();
        return $query;
    }
	
	/*public function select_contact_intraction_plan($plan_arr=array())
    {
		$this->db->select('ipm.*');
		$this->db->from('interaction_plan_master ipm');
		$this->db->where_not_in('ipm.id',$plan_arr);
		$this->db->order_by('ipm.created_date','DESC');
        $query = $this->db->get()->result_array();
        return $query;
    }*/
	
	public function select_contact_intraction_plan($contact_id='')
    {
	
		$this->db->select('DISTINCT ipct.interaction_plan_id',false);
		$this->db->from('interaction_plan_contacts_trans ipct');
		$this->db->where('ipct.contact_id',$contact_id);
		$this->db->order_by('ipct.created_date','DESC');
        $this->db->get();
		$sub_query = $this->db->last_query();
	
		$this->db->select('ipm.*');
		$this->db->from('interaction_plan_master ipm');
		if(!empty($plan_arr))
			$this->db->where_not_in('ipm.id',$plan_arr);
		$this->db->where("ipm.id NOT IN ($sub_query)", NULL, FALSE);
		$this->db->order_by('ipm.created_date','DESC');
        $query = $this->db->get()->result_array();
		//echo $this->db->last_query();
        return $query;
    }
	
	public function select_assigned_contact_intraction_plan($contact_id='')
    {
		$this->db->select('DISTINCT ipct.interaction_plan_id',false);
		$this->db->from('interaction_plan_contacts_trans ipct');
		$this->db->where('ipct.contact_id',$contact_id);
		$this->db->order_by('ipct.created_date','DESC');
        $query = $this->db->get()->result_array();
        return $query;
    }

	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$where='',$wherestring='',$totalrow='')
    {  
		//echo pr($match_values)." ".$where."".$compare_type.''.$orderby.''.$sort;exit; 
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
		//echo $this->db->last_query();exit;
		if(!empty($totalrow))
			return $query_FC->num_rows();
		else
  			return $query_FC->result_array();
	
		/*pr($condition);
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
		
  		return $query_FC->result_array();
  */
	}
	
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
	
	function getmultiple_tables_records_custom_join($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$where='')
    {  
		//echo pr($match_values)." ".$where."".$compare_type.''.$orderby.''.$sort;exit; 
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
		}
		else
		{
			if(!empty($where))
				$this->db->where($where); 
			
			if($match_values != null &&  $compare_type != null )
				$this->db->or_like($match_values, $compare_type);
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
		//echo $this->db->last_query();exit;
  		return $query_FC->result_array();
	}
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_time_record($data)
    {
        $result =  $this->db->insert($this->table_name3,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_contact_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name1,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	function insert_user_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name4,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	function insert_contact_converaction_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_contact_conve_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	function select_contact_conversation_trans_record($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name_contact_conve_trans);
		$this->db->where('contact_id',$id);
		$result = $this->db->get()->row();
		
		return $result;
    }

    /*
    @Description: Function is for update customer details by Admin
    @Author: Jayesh Rojasara
    @Input: tips details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record($data)
    {
        //pr($data);exit;
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_record_interaction_contact($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name1,$data); 
    }
	
	public function update_record_active($data)
    {
        //pr($data);exit;
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_interaction_plan_contact_trans($data,$newcontact)
    {
        if(!empty($newcontact))
		{
			$newdata['contact_id'] = $newcontact;
			$this->db->where('interaction_plan_id',$data['plan']);
			$this->db->where('contact_id',$data['contact_id']);
			$query = $this->db->update($this->table_name1,$newdata); 
			echo $this->db->last_query();
		}
    }
	
	public function update_interaction_plan_interaction_contact_trans($data,$newcontact)
    {
        if(!empty($newcontact))
		{
			$newdata['contact_id'] = $newcontact;
			$this->db->where('interaction_plan_id',$data['plan']);
			$this->db->where('contact_id',$data['contact_id']);
			$query = $this->db->update($this->table_name2,$newdata); 
			echo $this->db->last_query();
		}
    }
	
    /*
    @Description: Function for Delete tips Profile By Admin
    @Author: Jayesh Rojasara
    @Input: - tips id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
	
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	public function delete_contact_trans_record($id)
    {
        $this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name1);
    }
	
	public function delete_contact_trans_record_indi($id,$cid)
    {
        $this->db->where('contact_id',$cid);
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name1);
    }
	public function delete_adminuser_trans_record_indi($id,$cid)
    {
        $this->db->where('user_id',$cid);
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name4);
    }
	public function delete_adminuser_record_array($id,$arr)
    {
        $this->db->where_in('user_id',$arr);
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name4);
    }
	public function delete_contact_trans_record_array($id,$arr)
    {
        $this->db->where_in('contact_id',$arr);
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name1);
    }
	public function delete_adminuser_trans_record_array($id,$arr)
    {
        $this->db->where_in('user_id',$arr);
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name4);
    }
	
	public function delete_contact_communication_plan_trans_record_indi($id,$cid)
    {
        $this->db->where_in('contact_id',$cid);
		$this->db->where('is_done','0');
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name2);
    }
	
	public function delete_contact_communication_plan_trans_record_array($id,$arr)
    {
        $this->db->where_in('contact_id',$arr);
		$this->db->where('is_done','0');
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name2);
    }
	
	public function delete_contact_communication_plan_trans_record_not_done($id)
    {
		$this->db->where('is_done','0');
		$this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name2);
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
	
	public function insert_template_record($db_name,$data)
	{
		$result =  $this->db->insert($db_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
	}

}