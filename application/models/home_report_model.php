<?php

/*
  @Description: home Report  for child website
  @Author: Ishita
  @Input:
  @Output:
  @Date: 27-05-15
 */

class home_report_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /*
      @Description: Function for get Module Lists
      @Author: Jayesh Rojasara
      @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
      @Output: Assignmodule list
      @Date: 06-05-14
     */

    public function select_records($getfields = '', $match_values = '', $condition = '', $compare_type = '', $count = '', $num = '', $offset = '', $orderby = '', $sort = '', $and_match_value = '', $totalrow = '')
    {
        $fields = $getfields ? implode(',', $getfields) : '';
        $sql = 'SELECT ';

        $sql .= $fields ? $fields : '*';
        $sql .= ' FROM ' . $this->table_name;
        $where = '';
        $and_condition = '';
        if ($match_values)
        {
            $keys = array_keys($match_values);
            $compare_type = $compare_type ? $compare_type : 'like';
            if ($condition != '')
                $and_or = $condition;
            else
                $and_or = ($compare_type == 'like') ? ' OR ' : ' AND ';

            $where = 'WHERE ';

            if ($and_match_value)
            {
                $where .= '(';
            }
            switch ($compare_type)
            {
                case 'like':
                    $where .= $keys[0] . ' ' . $compare_type . '"%' . $match_values[$keys[0]] . '%" ';
                    break;

                case '=':
                default:
                    $where .= $keys[0] . ' ' . $compare_type . '"' . $match_values[$keys[0]] . '" ';
                    break;
            }
            $match_values = array_slice($match_values, 1);

            foreach ($match_values as $key => $value)
            {
                $where .= $and_or . ' ' . $key . ' ';
                switch ($compare_type)
                {
                    case 'like':
                        $where .= $compare_type . '"%' . $value . '%"';
                        break;

                    case '=':
                    default:
                        $where .= $compare_type . '"' . $value . '"';
                        break;
                }
            }
        }
        if ($and_match_value)
        {
            foreach ($and_match_value as $key => $value)
            {
                $and_condition .= "AND" . " " . $key . "=" . "'" . $value . "'";
                $where .= ')';
            }
        }
        /* if(!empty($where_clause))
          {
          $where .= ' AND (';

          foreach($where_clause as $key=>$val)
          {
          $where .= $key." = ".$val." OR ";
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

          $query = ($count) ? 'SELECT count(*) FROM '.$this->table_name.' '.$where.$orderby : $sql;  // exit;
         */
        $orderby = ($orderby != '') ? ' order by ' . $orderby . ' ' . $sort . '' : '';
        if ($offset == "" && $num == "")
            $sql .= ' ' . $where . ' ' . $and_condition . ' ' . $orderby;
        elseif ($offset == "")
            $sql .= ' ' . $where . ' ' . $and_condition . ' ' . $orderby . ' ' . 'limit ' . $num;
        else
            $sql .= ' ' . $where . ' ' . $and_condition . ' ' . $orderby . ' ' . 'limit ' . $offset . ',' . $num;

        $query = ($count) ? 'SELECT count(*) FROM ' . $this->table_name . ' ' . $where . ' ' . $and_condition . $orderby : $sql;

        $query = $this->db->query($query);
        if (!empty($totalrow))
            return $query->num_rows();
        else
            return $query->result_array();
    }

    /*
      @Description: Function for get User List (Customer)
      @Author     : Ruchi Shahu
      @Input      : Table(main table for connetct with another tables  ),Fieldl list(id,name..),join table(another tables want to fetch records) match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
      @Output     : User details
      @Date       : 17-07-2014
     */

    function getmultiple_tables_records($table = '', $fields = '', $join_tables = '', $join_type = '', $match_values = '', $condition = '', $compare_type = '', $num = '', $offset = '', $orderby = '', $sort = '', $group_by = '')
    {
        if (!empty($fields))
        {
            foreach ($fields as $coll => $value)
            {
                $this->db->select($value, false);
            }
        }

        $this->db->from($table);

        if (!empty($join_tables))
        {
            foreach ($join_tables as $coll => $value)
            {
                $this->db->join($coll, $value, $join_type);
            }
        }


        if ($condition != null)
            $this->db->where($condition);

        if ($group_by != null)
            $this->db->group_by($group_by);

        if ($orderby != null && $sort != null)
            $this->db->order_by($orderby, $sort);

        elseif ($orderby != null)
            $this->db->order_by($orderby);

        if ($match_values != null && $compare_type != null)
            $this->db->like($match_values, $compare_type);

        if ($num != null)
            $this->db->limit($num);

        if ($offset != null && $num != null)
            $this->db->limit($num, $offset);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query_FC->result_array();
    }

    function select_query($field, $table, $where = array(), $num = "", $offset = "", $back_tick = TRUE)
    {
        $this->db->select($field, $back_tick);
        $this->db->from($table);

        if (!empty($where))
            $this->db->where($where);

        if (!empty($num))
            $this->db->limit($num, $offset);

        $query_FC = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query_FC->result_array();
    }

    function insert_select($data)
    {
        extract($data);
        $sql = "INSERT INTO $insert_table
                    ($insert_columns)
                    SELECT $select_columns
                    FROM $select_table WHERE $where";
        $this->db->query($sql);
    }

    /*
      @Description: Function is for Insert tips details by Admin
      @Author: Jayesh Rojasara
      @Input: tips details for Insert into DB
      @Output: - Insert record into DB
      @Date: 07-05-14
     */

    function insert_record($table, $data)
    {
        $this->db->insert($table, $data);
        return mysql_insert_id();
    }

    /*
      @Description: Function is for update customer details by Admin
      @Author: Jayesh Rojasara
      @Input: tips details for Update into DB
      @Output: - Update records into DB with give id
      @Date: 07-05-14
     */

    public function update_record($data, $table)
    {
        $this->db->where('id', $data['id']);
        $this->db->update($table, $data);
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
        $this->db->select('*');
        $this->db->from('team_player_trans');
        $this->db->where('player_id', $id);
        $resultdata = $this->db->get()->result();

        if (count($resultdata) > 0)
        {
            return 'fail';
        }
        else
        {
            $this->db->where('player_id', $id);
            $this->db->delete('team_player_trans');

            $this->db->where('id', $id);
            $this->db->delete($this->table_name);
        }
    }

    /*
      @Description: Function to find Median
      @Author: Ishita
      @Input: -
      @Output: -
      @Date: 26-05-2015

     * Another Query - SELECT AVG(display_price) median, nofitems FROM(
      SELECT x.display_price, SUM(SIGN(1.0-SIGN(y.display_price-x.display_price))) diff, count(*) nofitems, floor(count(*)+1/2)
      FROM mls_property_list_master x, mls_property_list_master y
      GROUP BY x.display_price
      HAVING SUM(SIGN(1.0-SIGN(y.display_price-x.display_price))) = floor((COUNT(*)+1)/2)
      OR SUM(SIGN(1.0-SIGN(y.display_price-x.display_price))) = ceiling((COUNT(*)+1)/2)
      ) x;
     */

    public function find_median($where,$from_table)
    {
        $sql = 'SELECT
    IF(count%2=1,
       SUBSTRING_INDEX(substring_index(data_str,",",pos),",",-1),
       (SUBSTRING_INDEX(substring_index(data_str,",",pos),",",-1) 
         + SUBSTRING_INDEX(substring_index(data_str,",",pos+1),",",-1))/2) 
    as median 
FROM (SELECT group_concat(display_price order by display_price) data_str,
      CEILING(count(*)/2) pos,
      count(*) as count from '.$from_table.' where ' . $where . ')temp;';
        $query_FC = $this->db->query($sql);
        return $query_FC->row_array();
    }

}