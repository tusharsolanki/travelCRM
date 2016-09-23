<?php

	/*
    @Description: Contact Model
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/
class department_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name ='department_master';
		

    }

    /*
    @Description: Function for get Module Lists
    @Author: Mit Makwana
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
		
        return $query->result_array();
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
    

    /*
    @Description: Function is for Insert Contact details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_department($data)
    {

		$idata['created_by'] = !empty($data['created_by'])?$data['created_by']:0;
		$idata['created_date'] = date('Y-m-d H:i:s');;
		$idata['status'] = '1';
		if(!empty($data['name']))
		{
			$department = $data['name'];

			foreach($department as $row)
			{
				$idata['name'] = $row;
				if(trim($idata['name']) != "")
				{
					$result =  $this->db->insert($this->table_name,$idata);
				}
				$lastId = mysql_insert_id();
			}
			return $lastId;
		}
    }
    /*
    @Description: Function is for update customer details by Admin
    @Author: Mit Makwana
    @Input: Contact details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_department($data)
    {
		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
    /*
    @Description: Function for Delete Contact Profile By Admin
    @Author: Mit Makwana
    @Input: - Contact id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_department_record($id)
    {

        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
        
    }
	/*
    @Description: Function for get Module Lists Multiple tables
    @Author: Mohit Trivedi
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 02-09-2014
    */
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$not_where_in='',$where_in='',$order_flag='',$min_price='',$max_price='',$price_flag='',$price_null='',$weightage_null='',$min_area='',$max_area='',$area_flag='',$area_null='')
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
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		if(!empty($not_where_in)){
                    foreach($not_where_in as $key => $value){
                        $this->db->where_not_in($key,$value);
                    }
		}
                
                if(!empty($where_in)){
                    $ids = '';
                    foreach($where_in as $key => $value){
                        $this->db->where($key.' IN ('.$value.')', NULL, FALSE);
                        $ids .= $value.",";
                        //$this->db->where_in($key,$value);
                    }
                    $ids = trim($ids,',');
                    //$this->db->where('`user_id` IN ('.$ids.')', NULL, FALSE);
		}
                
                if(!empty($price_flag)) {
                    //$this->db->where('minimum_price BETWEEN '.$min_price.' and '.$max_price, NULL, FALSE);  
                    //$this->db->where('maximum_price BETWEEN '.$min_price.' and '.$max_price, NULL, FALSE);  
                    
                    //$this->db->where('minimum_price >= '.$min_price.' and maximum_price <= '.$min_price, NULL, FALSE);  
                    //$this->db->where('minimum_price >= '.$max_price.' and maximum_price <= '.$max_price, NULL, FALSE);  
                    
                    $this->db->where($min_price.' BETWEEN minimum_price and maximum_price', NULL, FALSE);  
                    $this->db->where($max_price.' BETWEEN minimum_price and maximum_price', NULL, FALSE);  
                }
                
                if(!empty($area_flag)) {
                    $this->db->where($min_area.' BETWEEN min_area and max_area', NULL, FALSE);  
                    $this->db->where($max_area.' BETWEEN min_area and max_area', NULL, FALSE);  
                }
                
                if(!empty($price_null)) {
                    $this->db->where('minimum_price >= 0', NULL, FALSE);  
                    $this->db->where('maximum_price > 0', NULL, FALSE);  
                }
                
                if(!empty($area_null)) {
                    $this->db->where('min_area >= 0', NULL, FALSE);  
                    $this->db->where('max_area > 0', NULL, FALSE);  
                }
                
                if(!empty($weightage_null)) {
                    $this->db->where('um.user_weightage > 0', NULL, FALSE);  
                }
                
		if($group_by != null)
		$this->db->group_by($group_by);
		
                if(empty($order_flag))
                {
                    if($orderby != null && $sort != null)
                            $this->db->order_by($orderby,$sort);
                    elseif($orderby != null )
                            $this->db->order_by($orderby);
                }
                else
                {
                    $this->db->order_by('urwt.round_value desc, um.id asc');
                }
				
		if($match_values != null &&  $compare_type != null )
		$this->db->or_like($match_values);
		
		if($offset != null && $num != null)
			$this->db->limit($num,$offset);
		elseif($num != null )
			$this->db->limit($num);
		
		$query_FC = $this->db->get();
  		return $query_FC->result_array();
	}
	
	
}