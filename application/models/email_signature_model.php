<?php

	/*
    @Description: email Signature Model
    @Author: Ruchi Shahu
    @Date: 02-08-2014
	*/

class email_signature_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'email_signature_master';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Ruchi Shahu
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$and_match_value='',$db_name='',$totalrow='')
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
            if(!empty($and_match_value))
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
            if(!empty($and_match_value))
            {
                foreach($and_match_value as $key=>$value)
                {
					$colldata = explode('IN_query',$key);
					if(!empty($colldata[0]) && !empty($colldata[1]) && trim($colldata[1]) == 'IN')
						$and_condition .= "AND ".$colldata[0]." IN (".$value.") ";
					else
                    	$and_condition .= "AND ".$key."="."'".$value."' ";
					//if(!empty($explode[0]))
					
                }
				//$and_condition = rtrim($and_condition,'AND ');
				$where .= ')';
            }
        }
        else {
            
            if(!empty($and_match_value))
            {
				$where = 'WHERE ';
                foreach($and_match_value as $key=>$value)
                {
					$colldata = explode('IN_query',$key);
					if(!empty($colldata[0]) && !empty($colldata[1]) && trim($colldata[1]) == 'IN')
						$and_condition .= $colldata[0]." IN (".$value.") AND ";
					else
                    	$and_condition .= $key."="."'".$value."' AND ";
					
					/*$explode = explode('IN_query',$key);
					pr($explode);exit; */
                }
				$and_condition = rtrim($and_condition,'AND ');
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
			//echo $where;exit;
			$where .= ')';
		}
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        
        if($offset=="" && $num=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$table.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();exit;
		if(!empty($totalrow))
			return $query->num_rows();
		else
        	return $query->result_array();
    }
    

    /*
    @Description: Function is for Insert email Signature by Admin
    @Author: Ruchi Shahu
    @Input: email signature details for Insert into DB
    @Output: - Insert record into DB
    @Date: 04-08-14
    */
	
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

    /*
    @Description: Function is for update email Signature details by Admin
    @Author: Ruchi Shahu
    @Input: email Signature details for Update into DB
    @Output: - Update records into DB with give id
    @Date: 04-08-14
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		//echo $this->db->last_query();
    }
    /*
    @Description: Function for Delete email Signature By Admin
    @Author: Ruchi Shahu
    @Input: - email Signature id which is delete by admin
    @Output: - Delete recodrs from DB with match ID
    @Date: 04-08-14
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }

}