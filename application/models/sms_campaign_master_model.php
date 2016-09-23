<?php

	/*
    @Description: SMS campaign Model
    @Author: Sanjay Chabhadiya
    @Date: 02-08-2014
	*/

class sms_campaign_master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'sms_campaign_master';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Sanjay Chabhadiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';

        if(!empty($db_name))
			$table = $db_name.".".$this->table_name;
		else
			$table = $this->table_name;
		
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$table;
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
    
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='')
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
		
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
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
		//return $query_FC->result_array();
  
	}

    /*
    @Description: Function is for Insert email Signature by Admin
    @Author: Sanjay Chabhadiya
    @Input: email signature details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-08-14
    */
	
	function select_sms_campaign_recepient_trans($data)
	{
		$this->db->select('*');
		$this->db->where('sms_campaign_id',$data);
		$result = $this->db->get('sms_campaign_recepient_trans');
		return $result->result_array();
	}
	
	function select_sms_campaign_trans_data_by_type($contact_id)
	{
		$this->db->where('contact_id',$contact_id);
		$result = $this->db->get('sms_campaign_recepient_trans');
		return $result->result_array();
	}
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	 /*
		@Description: Function is for Insert sms campaign trans
		@Author: Sanjay Chabhadiya
		@Input: insert SMS campaign trans details for Update into DB
		@Output: - Insert records into DB
		@Date: 28-08-14
    */
	
	function insert_sms_campaign_recepient_trans($data,$db_name='')
    {
		if(!empty($db_name))
		{$result =  $this->db->insert($db_name.'.sms_campaign_recepient_trans',$data);	}
		else
		{$result =  $this->db->insert('sms_campaign_recepient_trans',$data);	}
        $lastId = mysql_insert_id();
		return $lastId;
    }
	
    /*
		@Description: Function for update SMS campaign details by Admin
		@Author: Sanjay Chabhadiya
		@Input: SMS campaign details for Update into DB
		@Output: - Update records into DB with give id
		@Date: 04-08-14
    */
    public function update_record($data,$db_name='')
    {
        $this->db->where('id',$data['id']);
		if(!empty($db_name))
			$query = $this->db->update($db_name.".".$this->table_name,$data);
		else
        	$query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();
    }
	
	public function update_sms_campaign_trans($data)
	{
		$this->db->where('id',$data['id']);
        $result = $this->db->update('sms_campaign_recepient_trans',$data);
	}
	
    /*
		@Description: Function for Delete SMS campaign By Admin
		@Author: Sanjay Chabhadiya
		@Input: - SMS campaign id which is delete by admin
		@Output: - Delete recodrs from DB with match ID
		@Date: 28-08-14
    */
	
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	/*
		@Description: Function for fetch data from in query
		@Author: Sanjay Chabhadiya
		@Input: SMS campaign details for specific contact id wise
		@Output: - 
		@Date: 04-08-14
    */
	public function in_query($data)
    {
		$this->db->where_in('id',$data);	
		$result = $this->db->get('contact_master');
        //$result = $this->db->query('SELECT * FROM contact_master WHERE id in('.$id.')');
		return $result->result_array();
    }
	
	function in_query_data($data)
    {	
		if(!empty($data))
		{
			$this->db->select('cm.*');
			$this->db->from('contact_master cm');
			$this->db->where_in('cm.id',$data);
			$result = $this->db->get();
			return $result->result_array();
		}
		/*foreach($join_tables as $coll => $value)
		{
			$this->db->join($coll, $value,$join_type);
		}
		if(!empty($match))
		{
			$this->db->select('ecm.id as ID,ecm.template_subject');
			$this->db->where($match);
		}*/
	}
	
	/*
		@Description: Function for count total sms
		@Author: Sanjay Chabhadiya
		@Input: 
		@Output: 
		@Date: 28-08-2014
	
	*/
	
	public function total_sms($data,$db_name='')
	{
		$this->db->select_sum('pm.sms_counter');
		$this->db->from($db_name.'.user_package_trans up');
		$this->db->join($db_name.'.package_master pm','up.package_id = pm.id','left');
		$this->db->where('login_id',$data);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	public function delete_sms_campaign_recepient_trans($id)
    {
        $this->db->where('sms_campaign_id',$id);
        $this->db->delete('sms_campaign_recepient_trans');
    }
	
	public function delete_interaction_campaign($data)
	{
		$this->db->where($data);
        $this->db->delete('sms_campaign_recepient_trans');
	}
	
	/*
        @Description: Function For pagination
        @Author     : Sanjay Chabhadiya
        @Input      : 
        @Output     : 
        @Date       : 10-09-14
    */

	public function getpagingid($id='')
	{
		$this->db->select('*');
		$this->db->where('sms_type','Campaign');
		$this->db->from($this->table_name);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $id)
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
	
	public function getpaging($id='',$campaign_id='')
	{
		$this->db->select('*');
		if(!empty($campaign_id))
			$this->db->where('sms_campaign_id',$campaign_id);
		else
			$this->db->where('is_send','1');
		$this->db->from('sms_campaign_recepient_trans');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $id)
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
	
	function check_mail_send_or_no($data)
	{
		$this->db->select('*');
		$this->db->where($data);
		$result = $this->db->get('sms_campaign_recepient_trans');
		return $result->result_array();
		
	}
	
	
}