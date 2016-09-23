<?php

	/*
    @Description: Properties Viewed model
    @Author: Ami Bhatti
    @Input: 
    @Output: 
    @Date: 09-10-14 
	*/

class properties_viewed_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'joomla_rpl_track';

    }

    /*
    @Description: Function for get Module Lists
    @Author: Jayesh Rojasara
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$and_match_value='',$totalrow='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name;
        $where='';
        $and_condition='';
        if($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if($condition!='')
                $and_or=$condition;
            else 
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND '; 
          
            $where = 'WHERE ';
            
            if($and_match_value)
            {
                $where .= '(';
            }
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
        if($and_match_value)
        {
            foreach($and_match_value as $key=>$value)
            {                
                $and_condition .= "AND"." ".$key."="."'".$value."'";
                $where .= ')';
            }
        }
		/*if(!empty($where_clause))
		{
			$where .= ' AND (';
			
			foreach($where_clause as $key=>$val)
			{
				$where .= $key." = ".$val." OR ";
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;  // exit;
        */
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.'':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.' '.$and_condition.$orderby : $sql;
        
        $query = $this->db->query($query);
		if(!empty($totalrow))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
    
/*
        @Description: Function for get User List (Customer)
        @Author     : Ruchi Shahu
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : User details
        @Date       : 17-07-2014
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
		
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null && $sort != null)
		$this->db->order_by($orderby,$sort);
		
		elseif($orderby != null )
		$this->db->order_by($orderby);
		
		if($match_values != null &&  $compare_type != null )
		$this->db->like($match_values, $compare_type);
			
		if($num != null )
		$this->db->limit($num);
		
		if($offset != null && $num != null)
		$this->db->limit($num,$offset);
		
		$query_FC = $this->db->get();
		//echo $this->db->last_query();exit;
  		return $query_FC->result_array();
		
  		
	}
	
	public function select_player_trans($player_id)
	{
		$this->db->where('player_id',$player_id);
		$query = $this->db->get('team_player_trans');
		return $query->result_array();
		//echo $this->db->last_query();exit;
	}

    /*
    @Description: Function is for Insert tips details by Admin
    @Author: Jayesh Rojasara
    @Input: tips details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
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
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
	public function update_player_trans($data)
    {
		$this->db->where('player_id',$data['player_id']);
        $query = $this->db->update($this->table_player_trans,$data); 
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
		$this->db->select('*');
		$this->db->from('team_player_trans');
		$this->db->where('player_id',$id);
		$resultdata = $this->db->get()->result();
		
		if(count($resultdata) > 0)
		{
			return 'fail';
		}
		else
		{
			$this->db->where('player_id',$id);
			$this->db->delete('team_player_trans');
			
			$this->db->where('id',$id);
			$this->db->delete($this->table_name);
		}
    }
	
	public function getplayerpagingid($player_id='')
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
				if($row['id'] == $player_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
		
	}
	
	public function player_trans_records()
	{
		$this->db->select('DISTINCT player_id',false);
		$this->db->from('team_player_trans');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		
		return $result;
	}
	
	public function not_select_records($playerlist='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		
		if(!empty($playerlist)){
			$this->db->where_not_in('id',$playerlist);
		}
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		//echo $this->db->last_query();exit;
		
		//pr($result); exit;
		
		return $result;
	}

}