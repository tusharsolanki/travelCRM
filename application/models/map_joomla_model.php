<?php
/*
	@Description: Superadmin Map Joomla
	@Author: Ami Bhatti
	@Date: 08-10-14
*/

class map_joomla_model extends CI_Model
{
    function __construct()
    {
        parent::__construct(); 
        $this->table_name = 'joomla_mapping';
		$this->table_name1 = 'contact_master';
    }
    /*
        @Description: Check Login is valid or not
        @Author     : Mohit Trivedi
        @Input      : Superadmin Email id and Password
        @Output     : If validate then go to home page else login error
        @Date       : 30-08-14
    */  
    
    public function check_email($email, $id)
    {
			$param_selfold = array('email_id'=>$email);
            $this->db->select();
            $this->db->from($this->table_name);
            $this->db->where($param_selfold);
			$this->db->where('id !=',$id);
            $query= $this->db->get();
		    return $query->result_array();
	}
    /*
        @Description: Function for get Superadmin List (Customer)
        @Author     : Mohit Trivedi
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Superadmin details
        @Date       : 30-08-14
    */
   
    public function get_user($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='',$not_where='')
    {
		//pr($where);
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
                        if(!empty($not_where))
        	{
				foreach($not_where as $key=>$value)
				{   
					$where .= ' AND ('.$key.' ';
					$where .= ' != '.$value.')';
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
	
	
	
	public function get_admin($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_cond='')
    {
		//pr($where);
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
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
		
		if($condition != null )
		$this->db->where($condition);
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
			}
		}
			
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if($orderby != null && $sort != null)
			$this->db->order_by($orderby,$sort);
		elseif($orderby != null )
		{
			if($orderby == 'special_case')
				$this->db->order_by('is_done asc,task_date asc');
			elseif($orderby == 'special_case_task')
				$this->db->order_by('log_type asc,is_completed_task desc');
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
   
    /*
        @Description: Function is for Insert Superadmin details
        @Author     : Mohit Trivedi
        @Input      : Superadmin details
        @Output     : Insert record into DB
        @Date       : 30-08-14
    */
    function insert_record($data)
    {
        $this->db->insert($this->table_name,$data);	
	}

    /*
        @Description: Function is for update Superadmin details by Superadmin
        @Author     : Mohit Trivedi
        @Input      : Superadmin details
        @Output     : Update record into db
        @Date		: 30-08-14
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();exit;
    }
    /*
        @Description: Function for Delete Customer Profile By Superadmin
        @Author     : Mohit Trivedi
        @Input      : Superadmin id
        @Output     : Delete record from db
        @Date       : 30-08-14
    */
    public function delete_record($id='',$admin_id = '')
    {
        if(!empty($admin_id))
            $this->db->where('lw_admin_id',$admin_id);
        else
            $this->db->where('id',$id);
        $this->db->delete($this->table_name);            
    } 

	/*
        @Description: Function For pagination
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : Unique DB Name
        @Date       : 09-09-14
    */

	public function getsuperadminpagingid($superadmin_id='')
	{
		$this->db->select('*');
		$this->db->from($this->table_name);
		//$this->db->where('user_type','1');
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		$op = 0;
		if(count($result) > 0)
		{
			foreach($result as $key=>$row)
			{
				if($row['id'] == $superadmin_id)
				{
					$op = $key;
					$op1 = strlen($op);
					$op = substr($op,0,$op1-1)*10;
				}
			}
		}
		
		return $op;
	}
	
	
	
	   
}