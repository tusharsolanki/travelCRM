<?php
class MY_Session extends CI_Session {

    public function __construct()
    {
        parent::__construct();
    }

    function _serialize($data)
    {
        $data = $this->_serialize_backslash_recursive($data);

        return serialize($data);
    }

    function _unserialize($data)
    {
        $data = @unserialize(strip_slashes($data));

        return $this->_unserialize_backslash_recursive($data);
    }

    function _serialize_backslash_recursive($data)
    {

        if (is_array($data))
        {
            return array_map(array($this,'_serialize_backslash_recursive'), $data);
        }
        else
        {
            if (is_string($data))
            {
                return str_replace('\\', '{{slash}}', $data);
            }
        }

        return $data;

    }

    function _unserialize_backslash_recursive($data)
    {

        if (is_array($data))
        {
            return array_map(array($this,'_unserialize_backslash_recursive'), $data);
        }
        else
        {
            if (is_string($data))
            {
                return str_replace('{{slash}}', '\\', $data);
            }
        }

        return $data;

    }

}