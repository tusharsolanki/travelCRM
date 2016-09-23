<?php

	/*
    @Description: email Signature Model
    @Author: Sanjay Chabhadiya
    @Date: 02-08-2014
	*/

class listing_manager_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->table_name = 'property_listing_master';
		$this->table_name_doc_trans='property_listing_document_trans';
		$this->table_name_offers_trans='property_listing_offers_trans';
		$this->table_name_price_trans='property_listing_price_change_trans';
		$this->table_name_houses_trans='property_listing_open_houses_trans';
		$this->table_name_showings_trans='property_listing_showings_trans';
		$this->table_name_photos_trans='property_listing_photo_trans';
		$this->table_name_contacts_trans='property_listing_contact_trans';
    }

    /*
    @Description: Function for get Module Lists
    @Author: Sanjay Chabhadiya
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 04-08-2014
    */
   
    public function select_records($getfields='', $match_values = '', $condition ='', $compare_type = '', $count = '',$num = '',$offset='',$orderby='',$sort='',$where_clause='',$db_name='')
    {
        $fields =  $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';
        
        $sql .= $fields ? $fields : '*';
		if(!empty($db_name))
			$sql .= ' FROM '.$db_name.'.'.$this->table_name;
		else
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
		
		if(!empty($where_clause) && !empty($where))
			$where .= ' AND '.$where_clause;
		elseif(!empty($where_clause))
			$where = 'WHERE '.$where_clause;
		
        $orderby = ($orderby !='')?' order by '.$orderby.' '.$sort.' ':'';
        if($offset=="" && $num=="")
            $sql .= ' '.$where.$orderby;
        elseif($offset=="")
            $sql .= ' '.$where.$orderby.' '.'limit '.$num;
        else
             $sql .= ' '.$where.$orderby.' '.'limit '.$offset .','.$num;
        
        $query = ($count) ? 'SELECT count(*) as total_count FROM '.$this->table_name.' '.$where.$orderby : $sql;
        $query = $this->db->query($query);
		//echo $this->db->last_query();
        return $query->result_array();
    }
    
	
	function getmultiple_tables_records($table='',$fields='',$join_tables='',$join_type='',$match_values = '',$condition ='', $compare_type = '', $num = '',$offset='',$orderby='',$sort='',$group_by='',$wherestring='',$where_in='',$totalrow='',$having_clause='')
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
		
		if(!empty($where_in)){
			foreach($where_in as $key => $value){
				$this->db->where_in($key,$value);
			}
		}
		
		if($condition != null )
		$this->db->where($condition);
		
		if($wherestring != '')
			$this->db->where($wherestring, NULL, FALSE);
		
		if($group_by != null)
		$this->db->group_by($group_by);
		
		if(!empty($having_clause))
			$this->db->having($having_clause);
		
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
		
		if(!empty($totalrow))
             return $query_FC->num_rows();
		else
             return $query_FC->result_array();
	}
	
	function insert_record($data)
    {
        $result =  $this->db->insert($this->table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_doc_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_doc_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_photos_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_photos_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_offers_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_offers_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_price_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_price_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_houses_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_houses_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

	function insert_showings_trans_record($data)
    {
	    $result =  $this->db->insert($this->table_name_showings_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }
	
	function insert_contact_trans_record($data)
    {
        $result =  $this->db->insert($this->table_name_contacts_trans,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }


    /*
		@Description: Function is for update email Signature details by Admin
		@Author: Sanjay Chabhadiya
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
	
	public function update_doc_trans_record($data)
    {
 		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_doc_trans,$data); 
    }

	public function update_offers_trans_record($data)
    {
 		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_offers_trans,$data); 
    }

	public function update_price_trans_record($data)
    {
 		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_price_trans,$data); 
    }

	public function update_houses_trans_record($data)
    {
 		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_houses_trans,$data); 
    }
	public function update_showings_trans_record($data)
    {
 		$this->db->where('id',$data['id']);
        $query = $this->db->update($this->table_name_showings_trans,$data); 
    }

	
    /*
		@Description: Function for Delete email Signature By Admin
		@Author: Sanjay Chabhadiya
		@Input: - email Signature id which is delete by admin
		@Output: - Delete recodrs from DB with match ID
		@Date: 04-08-14
    */
	
    public function delete_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name);
    }
	
	public function delete_document_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_doc_trans);
    }

	public function delete_offers_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_offers_trans);
    }

	public function delete_price_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_price_trans);
    }

	public function delete_houses_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_houses_trans);
    }

	public function delete_showings_trans_record($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_showings_trans);
    }
    public function delete_photos($id)
    {
        $this->db->where('id',$id);
        $this->db->delete($this->table_name_photos_trans);
    }
	public function delete_contact_trans_record_indi($id,$cid)
    {
        $this->db->where('contact_id',$cid);
		$this->db->where('property_id',$id);
        $this->db->delete($this->table_name_contacts_trans);
    }
	
	public function delete_contact_trans_record_array($id,$arr)
    {
        $this->db->where_in('contact_id',$arr);
		$this->db->where('property_id',$id);
        $this->db->delete($this->table_name_contacts_trans);
    }


    /*
		@Description: Function for For transction record of listing manager
		@Author: Mohit Trivedi
		@Input: - id of user 
		@Output: - Fetch transction record which is created by user
		@Date: 29-10-14
    */
	
	function select_document_trans_record($property_id)
    {
		$this->db->select($this->table_name_doc_trans.'.*,pldtm.name');
		$this->db->from($this->table_name_doc_trans);
		$this->db->join('property_listing__document_type_master pldtm','pldtm.id = '.$this->table_name_doc_trans.'.document_type_id','left');
		$this->db->where('property_id',$property_id);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_offers_trans_record($property_id)
    {
		$this->db->select($this->table_name_offers_trans.'.*,plum.unit_title');
		$this->db->from($this->table_name_offers_trans);
		$this->db->join('property_listing__unit_master plum','plum.id = '.$this->table_name_offers_trans.'.offer_price_unit_id','left');
		$this->db->where('property_id',$property_id);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_price_trans_record($property_id)
    {
		$this->db->select($this->table_name_price_trans.'.*,plum.unit_title');
		$this->db->from($this->table_name_price_trans);
		$this->db->join('property_listing__unit_master plum','plum.id = '.$this->table_name_price_trans.'.new_price_unit_id','left');
		$this->db->where('property_id',$property_id);
		$this->db->order_by('id','desc');
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_document_trans_record_ajax($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_doc_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
    }

	function select_offers_trans_record_ajax($id)
    {
	  	$this->db->select('*,DATE_FORMAT(offer_date,"%m/%d/%Y") as offer_date',false);
		$this->db->from($this->table_name_offers_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
    }

	function select_price_trans_record_ajax($id)
    {
	  	$this->db->select('*,DATE_FORMAT(price_change_date,"%m/%d/%Y") as price_change_date',false);
		$this->db->from($this->table_name_price_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
    }
	function select_houses_trans_record($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_houses_trans);
		$this->db->where('property_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_photos_trans_record($id,$order_by='',$sortfields='',$db_name='')
    {
	  	$this->db->select('*');
		if(!empty($db_name))
			$this->db->from($db_name.'.'.$this->table_name_photos_trans);
		else
			$this->db->from($this->table_name_photos_trans);
		$this->db->where('property_id',$id);
		if(!empty($order_by))
			$this->db->order_by($order_by,$sortfields);
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_showings_trans_record($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_showings_trans);
		$this->db->where('property_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_old_contact($id)
    {
	  	$this->db->select('*');
		$this->db->from($this->table_name_contacts_trans);
		$this->db->where('property_id',$id);
		$result = $this->db->get()->result_array();
		return $result;
    }

	function select_houses_trans_record_ajax($id)
    {
	  	$this->db->select('*,DATE_FORMAT(open_house_time,"%l:%i %p") as open_house_time,DATE_FORMAT(open_house_end_time,"%l:%i %p") as open_house_end_time,DATE_FORMAT(open_house_date,"%m/%d/%Y") as open_house_date',false);
		$this->db->from($this->table_name_houses_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
    }

	function select_showings_trans_record_ajax($id)
    {
	  	$this->db->select('*,DATE_FORMAT(showings_time,"%l:%i %p") as showings_time,DATE_FORMAT(showings_date,"%m/%d/%Y") as showings_date',false);
		$this->db->from($this->table_name_showings_trans);
		$this->db->where('id',$id);
		$result = $this->db->get()->row();
		return $result;
    }
	
	function property_listing_master_insert($data='',$table_name='')
    {
        $result =  $this->db->insert($table_name,$data);	
		$lastId = mysql_insert_id();
		return $lastId;
    }

}