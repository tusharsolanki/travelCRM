<?php

	/*
    @Description: nwmls Model
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
	*/

class mls_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name 			= 'mls_property';
		$this->table_name1 			= 'mls_amenity_data';
		$this->table_name2			= 'mls_area_community_data';
		$this->table_name3			= 'mls_property_list_master';
		
		$this->table_name5			= 'mls_property_history_data';
		$this->table_name6			= 'mls_member_data';
		$this->table_name7			= 'mls_office_data';
		$this->table_name8			= 'mls_school_data';
		
		$this->table_name_tran 		= 'mls_property_type';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
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
    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name1;}
		else
		{$sql .= ' FROM '.$this->table_name1;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name1.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records2($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name2;}
		else
		{$sql .= ' FROM '.$this->table_name2;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name2.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records3($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name3;}
		else
		{$sql .= ' FROM '.$this->table_name3;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name3.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records5($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name5;}
		else
		{$sql .= ' FROM '.$this->table_name5;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name5.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records6($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name6;}
		else
		{$sql .= ' FROM '.$this->table_name6;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name6.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records7($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name7;}
		else
		{$sql .= ' FROM '.$this->table_name7;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name7.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
	/*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records8($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name8;}
		else
		{$sql .= ' FROM '.$this->table_name8;}
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
	 /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
   
     public function select_records_tran($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
		{$sql .= ' FROM '.$db_name.'.'.$this->table_name_tran;}
		else
		{$sql .= ' FROM '.$this->table_name_tran;}
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
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name_tran.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
    /*
    @Description: Function is for Insert mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
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
    @Description: Function is for Insert amenities details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record1($data)
    {
        $result =  $this->db->insert($this->table_name1,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area community details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record2($data)
    {
        $result =  $this->db->insert($this->table_name2,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area property details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record3($data)
    {
        $result =  $this->db->insert($this->table_name3,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert area community details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record5($data)
    {
        $result =  $this->db->insert($this->table_name5,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert member details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record6($data)
    {
        $result =  $this->db->insert($this->table_name6,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert office details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record7($data)
    {
        $result =  $this->db->insert($this->table_name7,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }
	/*
    @Description: Function is for Insert school details by Admin
    @Author: Niral Patel
    @Input: mls details for Insert into DB
    @Output: - Insert record into DB
    @Date: 07-05-14
    */
	
    function insert_record8($data)
    {
        $result =  $this->db->insert($this->table_name8,$data);	
	  
		$lastId = mysql_insert_id();
		return $lastId;
    }

    /*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record1($data)
    {
        $this->db->where('code',$data['code']);
		$this->db->where('value_code',$data['value_code']);
        $query = $this->db->update($this->table_name1,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record2($data)
    {
        $this->db->where('area',$data['area']);
		$this->db->where('community',$data['community']);
        $query = $this->db->update($this->table_name2,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record3($data)
    {
        $this->db->where('LN',$data['LN']);
        $query = $this->db->update($this->table_name3,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record5($data)
    {
        $this->db->where('ml_number',$data['ml_number']);
        $query = $this->db->update($this->table_name5,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record6($data)
    {
        $this->db->where('member_mls_id',$data['member_mls_id']);
        $query = $this->db->update($this->table_name6,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record7($data)
    {
        $this->db->where('office_mls_id',$data['member_mls_id']);
        $query = $this->db->update($this->table_name7,$data); 
    }
	/*
    @Description: Function is for update mls details by Admin
    @Author: Niral Patel
    @Input: mls details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 07-05-14
    */
    public function update_record8($data)
    {
        $this->db->where('school_district_code',$data['member_mls_id']);
        $query = $this->db->update($this->table_name8,$data); 
    }
    /*
    @Description: Function for Delete mls Profile By Admin
    @Author: Niral Patel
    @Input: - mls id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 22-11-2013
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	public function mls_delete_record($id='',$table='')
    {
        $this->db->where('id',$id);
        $this->db->delete($table);
    }

}