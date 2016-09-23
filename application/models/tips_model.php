<?php

	/*
    @Description: tips Model
    @Author: Jayesh Rojasara
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/

class tips_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'tips';
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
        return $query->result_array();
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

}