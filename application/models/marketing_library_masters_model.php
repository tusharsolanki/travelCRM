<?php
class marketing_library_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'marketing_master_lib__category_master';
       // $this->table_name_status = 'interaction_plan__status_master';
    }

    /*
        @Description: Function for get interaction plan Lists
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : interaction plan list
        @Date       : 09-07-2014
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
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 01-09-2014
    */
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='')
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
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
		
  		return $query_FC->result_array();
  
	}

	
	
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='')
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
        @Description: Function is for Insert interaction plan types
        @Author     : Sanjay Moghariya
        @Input      : Interaction plan details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 09-07-2014
    */
    function insert_plan_type($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');		
        $idata['status'] = '1';
       	if(!empty($data['category']))
		{
			$email_type = $data['category'];
			foreach($email_type as $row)
			{
				$idata['category'] = $row;
				if(trim($idata['category']) != "")
					$result =  $this->db->insert($this->table_name,$idata);
				$lastId[] = mysql_insert_id();
			}
			return $lastId;
		}
    }
	
	/*
        @Description: Function is for Insert interaction plan types
        @Author     : Nishit Modi
        @Input      : Interaction plan details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 09-02-2015
    */
	function insert_plan_type_parent($data)
    {
		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');		
        $idata['status'] = '1';
       	if(!empty($data['category']))
		{
			$lastId = array();
			$email_type = $data['category'];
			$count=0;
			foreach($email_type as $row)
			{
				$idata['category'] = $row;
				if(trim($idata['category']) != "")
				{
					$result =  $this->db->insert($this->table_name,$idata);
					$lastId[$count]['category'] = $idata['category'];
					$lastId[$count]['id'] = mysql_insert_id();
					$count++;
				}
			}
			return $lastId;
		}
    }
	
	
    
    /*
        @Description: Function is for Insert interaction plan status
        @Author     : Sanjay Moghariya
        @Input      : Interaction plan status details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 09-07-2014
    */
    function insert_status($data)
    {
        $this->db->insert($this->table_name,$data);
        $lastId = mysql_insert_id();
    }
	 /*
        @Description: Function is for Insert category
        @Author     : Sanjay Moghariya
        @Input      : Interaction plan status details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 09-07-2014
    */
    function insert_category_record($child_db,$data)
    {
        $idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');
		$idata['superadmin_cat_id']	= $data['superadmin_cat_id'];
        $idata['status'] = '1';
       	if(!empty($data['category']))
		{
			$email_type = $data['category'];
			foreach($email_type as $row)
			{
				//echo 'foreach';
				$idata['category'] = $row;
				if(trim($idata['category']) != "")
					$result =  $this->db->insert($child_db.'.'.$this->table_name,$idata);
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
		//echo 'insert_end';
    }
	
	/*
        @Description: Function is for Insert category
        @Author     : Nishit Modi
        @Input      : Interaction plan status details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 09-02-2015
    */
	function insert_category_record_child($child_db,$data)
    {
        $idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');
		$idata['superadmin_cat_id']	= $data['superadmin_cat_id'];
        $idata['status'] = '1';
       	if(!empty($data['category']))
		{
			/*$email_type = $data['category'];
			foreach($email_type as $row)
			{*/
				//echo 'foreach';
				$idata['category'] = $data['category'];
				if(trim($idata['category']) != "")
				{
					$result =  $this->db->insert($child_db.'.'.$this->table_name,$idata);
					//echo $this->db->last_query();
					$lastId = mysql_insert_id();
					return $lastId;
				}
			//}
		}
		//echo 'insert_end';
    }
	
	
    /*
        @Description: Function is for update interaction plan details
        @Author     : Sanjay Moghariya
        @Input      : interaction plan details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 11-07-2014
    */
    public function update_plan_type($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
    /*
        @Description: Function is for update interaction status details
        @Author     : Sanjay Moghariya
        @Input      : interaction plan status details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 11-07-2014
    */
    public function update_status($data)
    {		
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
    /*
        @Description: Function for Delete interaction plan type
        @Author     : Sanjay Moghariya
        @Input      : interaction plan type id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 11-07-2014
    */
    public function delete_plan_type_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	public function delete_parent_category_record($id)
    {
        $this->db->where('parent',$id);
        $this->db->delete($this->table_name);
    }
	
    /*
        @Description: Function for Delete interaction plan status
        @Author     : Sanjay Moghariya
        @Input      : interaction plan status id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 11-07-2014
    */
    public function delete_status_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
}