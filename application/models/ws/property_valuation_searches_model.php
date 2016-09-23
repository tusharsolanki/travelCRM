<?php
/*
    @Description: Property Valuation Saved Searches model
    @Author     : Sanjay Moghariya
    @Input      : 
    @Output     : 
    @Date       : 02-12-2014
*/

class property_valuation_searches_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'joomla_rpl_property_valuation_searches';
    }
    
    /*
        @Description: Function for valuation searched list
        @Author     : Sanjay Moghariya
        @Input      : Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Property valuation searched list
        @Date       : 03-12-2014
    */
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$and_match_value='',$totalrow='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM '.$this->table_name;
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
            
            if($and_match_value)
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
        }
        if($and_match_value)
        {
            foreach($and_match_value as $key=>$value)
            {                
                $and_condition .= "AND"." ".$key."="."'".$value."'";
                $where .= ')';
            }
        }

        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.'':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.' '.$and_condition.' '.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.' '.$and_condition.$orderby : $sql;
        
        $query = $this->db->query($query);
		if(!empty($totalrow))
			return $query->num_rows();
		else
        	return $query->result_array();
    }

    /*
        @Description: Function for get List 
        @Author     : Sanjay Moghariya
        @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
        @Output     : Property valuation listing
        @Date       : 02-12-2014
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
            $this->db->like($match_values, $compare_type);

        if($num != null )
            $this->db->limit($num);

        if($offset != null && $num != null)
            $this->db->limit($num,$offset);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query_FC->result_array();
    }
	
    /*
        @Description: Function is for Insert property valuation data
        @Author     : Sanjay Moghariya
        @Input      : property valuation data
        @Output     : Insert record
        @Date       : 02-12-2014
    */
    function insert_record($data,$db_name='')
    {
        if(!empty($db_name))
            $result =  $this->db->insert($db_name.".".$this->table_name,$data);	
        else
            $result =  $this->db->insert($this->table_name,$data);	
        $lastId = mysql_insert_id();
        return $lastId;
    }

    /*
        @Description: Function is for update valuation details
        @Author     : Sanjay Moghariya
        @Input      : Update Details
        @Output     : Update records
        @Date       : 02-12-2014
    */
    public function update_record($data)
    {
        $this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name,$data); 
    }
	
    /*
        @Description: Function for Delete valuation report data
        @Author     : Sanjay Moghariya
        @Input      : record id
        @Output     : Delete recodrs from DB with match ID
        @Date       : 02-12-2014
    */
    public function delete_record($id)
    {
        $this->db->select('*');
        $this->db->from('team_player_trans');
        $this->db->where('player_id',$id);
        $resultdata = $this->db->get()->result();

        if(count($resultdata) > 0)
        {
            return 'fail';
        }
        else
        {
            $this->db->where('player_id',$id);
            $this->db->delete('team_player_trans');

            $this->db->where('id',$id);
            $this->db->delete($this->table_name);
        }
    }
}