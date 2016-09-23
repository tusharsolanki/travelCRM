<?php

    /*
        @Description: Model for get work time Lists.
        @Author     : Mohit Trivedi
        @Input      : 
        @Output     : work time config master model.
        @Date       : 14-08-2014
    */

class work_time_config_master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name ='work_time_config_master';
       	$this->table_name1='user_leave_data';
		$this->table_name2='work_time_special_rules';
    }

    /*
        @Description: Function for get work time Lists
        @Author     : Mohit Trivedi
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : work time list
        @Date       : 14-08-2014
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
        @Description: Function for get work time Lists from multiple tables
        @Author     : Mohit Trivedi
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : work time list
        @Date       : 14-08-2014
    */
	
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
        return $query->result_array();
    }
  
    /*
        @Description: Function is for Insert work time 
        @Author     : Mohit Trivedi
        @Input      : work time  details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 14-08-2014
    */

    function insert_data($data)
    {
        $this->db->insert($this->table_name,$data);
        $lastId = mysql_insert_id();
    }

    /*
        @Description: Function is for Insert work time leave 
        @Author     : Mohit Trivedi
        @Input      : work time leave  details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 19-08-2014
    */
	
	function insert_data1($data)
    {
        $this->db->insert($this->table_name1,$data);
        $lastId = mysql_insert_id();
    }

    /*
        @Description: Function is for Insert work time rules 
        @Author     : Mohit Trivedi
        @Input      : work time rules details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 19-08-2014
    */
	
	function insert_data2($data)
    {
        $this->db->insert($this->table_name2,$data);
        $lastId = mysql_insert_id();
    }
	
    /*
        @Description: Function is for update work time details
        @Author     : Mohit Trivedi
        @Input      : work time details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 14-08-2014
    */

    public function update_data($data)
    {
        $this->db->where('user_id',$data['user_id']);
		$query = $this->db->update($this->table_name,$data); 
    }


    /*
        @Description: Function is for update work time leave details
        @Author     : Mohit Trivedi
        @Input      : work time leave details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 19-08-2014
    */

	public function update_leave($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name1,$data); 
    }

    /*
        @Description: Function is for update work time rules details
        @Author     : Mohit Trivedi
        @Input      : work time rules details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 19-08-2014
    */
	
	public function update_rules($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name2,$data); 
    }
    
    /*
        @Description: Function for Delete work time 
        @Author     : Mohit Trivedi
        @Input      : work time id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 19-08-2014
    */

    public function delete_data($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
    /*
        @Description: Function for Delete work time leave
        @Author     : Mohit Trivedi
        @Input      : work time leave id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 19-08-2014
    */
    
	public function delete_leave_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name1);
    }
	
	 /*
        @Description: Function for Delete work time leave
        @Author     : Mohit Trivedi
        @Input      : work time leave id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 19-08-2014
    */

    public function delete_rules_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name2);
    }

}