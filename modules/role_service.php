<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IRoleService {
    
    public function GetRoleList();
    public function GetRole($name);
    // public function GetTotalBankBalance();
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterRole($data); // ADD
    // public function UpdateBankAccount($data); // UPDATE

}

class RoleService extends BaseDataService implements IRoleService
{
    public function GetRoleList() {
        $tsql = <<<EOT
        select	name, description, update_at 
        from	role 
        order by name desc;
        EOT;
        $params = array();
        return $this->query($tsql, $params);
    }

    public function GetRole($name) {
        $tsql = <<<EOT
        select	name, description, update_at 
        from	role 
        where   name = ?;
        EOT;
        $params = array($name);
        return $this->query($tsql, $params)[0];
    }

    public function GetSanitisedInput($data, $forNew = false) {
        
        if (empty($data->name) ) return null;
        if (empty($data->description)) return null;
        if (empty($data->session_user_id)) return null;

        $input = new \stdClass();
        $input->name = trim($data->name);
        $input->description = trim($data->description);

        $timestamp = date("Y-m-d H:i:s");
        $session_user_id = trim($data->session_user_id);
        
        $input->update_at = $timestamp;
        $input->update_by = $session_user_id;

        if ($forNew) {
            $input->create_at = $timestamp;
            $input->create_by = $session_user_id;
        }

        return $input;
    }

    public function RegisterRole($data) {
        $tsql = "insert into role (name, description, create_at, create_by, update_at, update_by) values (?, ?, ?, ?, ?, ?);";
        $params = array(&$data->name, &$data->description
            , &$data->create_at, &$data->create_by, &$data->update_at, &$data->update_by);
        return $this->execute($tsql, $params);
    }

    public function UpdateRole($data) {
        $tsql = "update role set description = ?, update_at = ?, update_by = ? where name = ?;";
        $params = array(&$data->description, &$data->update_at, &$data->update_by, &$data->name);
        return $this->execute($tsql, $params);
    }

}

?>