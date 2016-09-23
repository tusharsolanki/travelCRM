<?php

/*
    @Description: Country Model
    @Author     : Sanjay Moghariya
    @Input      :  
    @Output     : 
    @Date       : 26-12-2014
*/

class country_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'country';
    }

    /*
        @Description: Function for get country list
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : country list
        @Date       : 26-12-2014
    */
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause = '',$not_where='')
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
		
		if(!empty($where_clause))
		{
                    $where_field = "WHERE ";
                    foreach($where_clause as $key=>$value) {
                        $where_field .= $key.' = '.$value.' AND ';
                    }
                    if(!empty($where))
                        $where = $where_field.'('.ltrim($where,'WHERE ').')';	
                    else
                        $where = rtrim($where_field,'AND ');		
		}
                
                if(!empty($not_where))
        	{
                    foreach($not_where as $key=>$value)
                    {   
                        $where .= ' AND ('.$key.' ';
                        $where .= ' != '.$value.')';
                    }
                }
		
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
        @Description: Function for get country list
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : country list
        @Date       : 26-12-2014
    */
    function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$match3='')
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

        if($match3 != null &&  $compare_type != null )
        $this->db->or_like($match3);

        if($offset != null && $num != null)
                $this->db->limit($num,$offset);
        elseif($num != null )
                $this->db->limit($num);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();
        return $query_FC->result_array();
    }
    
    /*
        @Description: Function is for Insert country
        @Author     : Sanjay Moghariya
        @Input      : Country details for Insert into DB
        @Output     : Insert record into DB
        @Date       : 26-12-2014
    */
    function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }
	
    /*
        @Description: Function is for update country
        @Author     : Sanjay Moghariya
        @Input      : country details for Update into DB
        @Output     : Update records into DB with give id
        @Date       : 26-12-2014
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
		
    }

    /*
        @Description: Function for Delete country
        @Author     : Sanjay Moghariya
        @Input      : id which is delete
        @Output     : Delete recodrs from DB with match ID
        @Date       : 26-12-2014
    */
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
}