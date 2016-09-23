<?php

	/*
    @Description: tips Model
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/

class interaction_premium_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'interaction_plan_interaction_master_premium';
		$this->table_name1 = 'interaction_plan_contact_communication_plan';
		$this->table_name2 = 'interaction_plan_contact_communication_plan';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Jayesh Rojasara
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='')
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
    
	
    public function select_records_intraction_plan()
    {
        $select_intraction_plan = $this->db->query("SELECT ipm.*,ipsm.name As plan_status_name FROM `interaction_plan_master` ipm
JOIN interaction_plan__status_master ipsm ON ipsm.id = ipm.plan_status ORDER BY ipm.created_date DESC");
        $query = $select_intraction_plan->result_array();
        return $query;
    }

   
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Nishit Modi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-07-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$where='',$where_in='',$totalrow='')
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
		
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
			}
		}
		
		if($condition != null )
			$this->db->where($condition); 
		
		if(!empty($where) && $match_values != null &&  $compare_type != null)
		{
			//pr($where);
			//pr($match_values);
			
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
	
		/*foreach($fields as $coll => $value)
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
		echo $this->db->last_query();exit;
		
  		return $query_FC->result_array();*/
		
		 
  
	}
    
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_contact_communication_record($data)
    {
        $result =  $this->db->insert($this->table_name1,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	
	function get_contact_interaction_task_date($interaction_plan_interaction_id,$contact_id)
	{
		$this->db->where('interaction_plan_interaction_id',$interaction_plan_interaction_id);
		$this->db->where('contact_id',$contact_id);
		$this->db->order_by('id','desc');
		return $this->db->get($this->table_name2)->row();
	}
	
	function get_contact_interaction_task_date_not_done($interaction_plan_interaction_id,$contact_id)
	{
		$this->db->where('interaction_plan_interaction_id',$interaction_plan_interaction_id);
		$this->db->where('contact_id',$contact_id);
		$this->db->where('is_done','0');
		$this->db->order_by('id','desc');
		return $this->db->get($this->table_name2)->row();
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
	
	public function update_contact_communication_record($data)
    {
        //pr($data);exit;
		if(!empty($data['id']))
		{
			$this->db->where('id',$data['id']);
			$query = $this->db->update($this->table_name2,$data); 
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
	/*
    @Description: Function for Delete interaction  By Admin
    @Author: Kaushik Valiya
    @Input: - Delete id which interaction plan id(all Recored remove) want to delete
    @Output: - New contacts list after record is deleted.
    @Date: 23-07-2014
    */
    function delete_record_interaction($id)
    {
        $this->db->where('interaction_plan_id',$id);
        $this->db->delete($this->table_name);
    }
	
	function drop_interaction($id)
	{
		$this->db->where('is_done','0');
		$this->db->where('interaction_plan_interaction_id',$id);
        $this->db->delete($this->table_name1);
	}
	
	function get_previous_interaction($id)
	{
		$this->db->where('id',$id);
		$current_interaction = $this->db->get($this->table_name)->row();
		
		//pr($current_interaction);exit;
		
		$prev_interaction = array();
		
		if(!empty($current_interaction->id))
		{
			$this->db->where('interaction_plan_id',$current_interaction->interaction_plan_id);
			$this->db->where('interaction_sequence_date < ',$current_interaction->interaction_sequence_date);
			$this->db->order_by('interaction_sequence_date','desc');
			$prev_interaction = $this->db->get($this->table_name)->row();
		}
		
		//pr($prev_interaction);exit;
		
		return $prev_interaction;
		
	}
	
	function get_next_interaction($id)
	{
		$this->db->where('id',$id);
		$current_interaction = $this->db->get($this->table_name)->row();
		
		//pr($current_interaction);exit;
		
		$next_interaction = array();
		
		if(!empty($current_interaction->id))
		{
			$this->db->where('interaction_plan_id',$current_interaction->interaction_plan_id);
			$this->db->where('interaction_sequence_date >= ',$current_interaction->interaction_sequence_date);
			$this->db->where('id != ',$current_interaction->id);
			$this->db->order_by('interaction_sequence_date','asc');
			$next_interaction = $this->db->get($this->table_name)->row();
		}
		
		//pr($next_interaction);exit;
		
		return $next_interaction;
		
	}
	/*
        @Description: Function For pagination
        @Author     : Kaushik Valiya
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 11-09-14
    */

	public function getemailpagingid($email_id='',$plan_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('interaction_plan_id',$plan_id);
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

	public function get_all_assigned_interaction_plans($user_id = '')
	{
		if($user_id != '')
		{
			$this->db->select('DISTINCT interaction_plan_id',false);
			$this->db->from('interaction_plan_interaction_master');
			$this->db->where('assign_to',$user_id);
			$result = $this->db->get()->result_array();
			return $result;
		}
		else
			return array();
	}
	
	public function current_interaction_plan($plan_id)
	{
		$qry = $this->db->query('SELECT *
FROM (SELECT * FROM interaction_plan_contact_communication_plan  ORDER BY interaction_plan_interaction_id ASC , is_done ASC ) ipccp
join  interaction_plan_interaction_master ipim ON ipim.id = ipccp.interaction_plan_interaction_id
LEFT JOIN contact_master cm ON cm.id = ipccp.contact_id
WHERE ipccp.`interaction_plan_id` = "'.$plan_id.'" AND cm.id IS NOT NULL
GROUP BY ipccp.interaction_plan_interaction_id,ipccp.contact_id
ORDER BY ipim.interaction_sequence_date ASC
');
		return $qry->result_array();
	}

}