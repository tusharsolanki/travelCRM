<?php

	/*
    @Description: Contact Model
    @Author: Mit Makwana
    @Input: 
    @Output: 
    @Date: 06-05-14
	*/
class contact_type_master_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'contact__type_master';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Mit Makwana
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 06-05-14
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$totalrow='')
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
		if(!empty($totalrow))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
   
   	public function contact_type_in_query($contact_type)
   	{
   		$this->db->where_in('id',$contact_type);
		$result = $this->db->get('contact__type_master')->result_array();
		return $result;
	}
	
	public function contact_type_id($contact_type)
   	{
		$this->db->select('id');
   		$this->db->where_in('id',$contact_type);
		$result = $this->db->get('contact__type_master')->result_array();
		return $result;
	}
}