<?php
class interaction_plan_masters_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'interaction_plan__plan_type_master';
        $this->table_name_status = 'interaction_plan__status_master';
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
	
	
    public function select_records1($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$tbl_name='',$where_clause='')
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
		if(!empty($where) && !empty($where_clause))
		{
			$where = ltrim($where,'WHERE');
			$where = 'WHERE '.$where_clause.' AND '.$where;
		}
		elseif(!empty($where_clause))
			$where = 'WHERE '.$where_clause;
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
        $email_type = $data['name'];
        for($i=0;$i<count($email_type);$i++)
        {
            $data['name'] = $email_type[$i];
            if(trim($data['name']) != "")
                    $result =  $this->db->insert($this->table_name,$data);
            $lastId = mysql_insert_id();
        }
        return $lastId;
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
        $website_type = $data['name'];
        for($i=0;$i<count($website_type);$i++)
        {
            $data['name'] = $website_type[$i];
            if(trim($data['name']) != "")
                    $result =  $this->db->insert($this->table_name_status,$data);
            $lastId = mysql_insert_id();
        }
        return $lastId;
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
        $query = $this->db->update($this->table_name_status,$data); 
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
        $this->db->delete($this->table_name_status);
    }
}