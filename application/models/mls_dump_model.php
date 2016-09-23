<?php

	/*
    @Description: nwmls Model
    @Author: Niral Patel
    @Input: 
    @Output: 
    @Date: 20-02-2015
	*/

class mls_dump_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        
    }

    /*
    @Description: Function for get Module Lists
    @Author: Niral Patel
    @Input: Fieldl list(id,name..), match value(id=id,..), condition(and,or),compare type(=,like),count,limit per page, starting row number
    @Output: Assignmodule list
    @Date: 20-02-2015
    */
    
    function create_table($table_name='')
    {
        //$query="CREATE TABLE db2.table LIKE db1.table";
        $query="CREATE TABLE db2.".$table_name." like db1.".$table_name;
        //$query="ALTER TABLE ".$this->table_name3."  ADD ".$field_name." ".$field_type."(".$field_size.") NOT NULL AFTER ".$last_field."";
        $query = $this->db->query($query);
        return $query->result_array();
    }
   
}