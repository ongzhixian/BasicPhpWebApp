<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IRoleAssignmentService {
    
    public function GetRoleAssignmentList();
    public function GetRoleAssignment($login_id, $role_id);

    public function GetLoginIdNamePairList();
    public function GetRoleIdNamePairList();
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterRoleAssignment($data); // ADD
    public function RemoveRoleAssignment($data); // REMOVE
}

class RoleAssignmentService extends BaseDataService implements IRoleAssignmentService
{
    public function GetRoleAssignmentList() {
        $tsql = <<<EOT
        select lo.id as login_id, lo.username, ro.id as role_id, ro.name as role_name, ra.update_at
        from role_assignment ra
        inner join login lo
        on ra.login_id = lo.id
        inner join role ro
        on ra.role_id = ro.id
        order by lo.username;
        EOT;
        $params = array();
        return $this->query($tsql, $params);
    }

    public function GetRoleAssignment($login_id, $role_id) {
        $tsql = <<<EOT
        select lo.id as login_id, lo.username, ro.id as role_id, ro.name as role_name, ra.update_at
        from role_assignment ra
        inner join login lo
        on ra.login_id = lo.id
        inner join role ro
        on ra.role_id = ro.id
        where lo.id = ? and ro.id = ?;
        EOT;
        $params = array($login_id, $role_id);
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

    public function GetLoginIdNamePairList() {
        $tsql = <<<EOT
        select id, username from login order by username;
        EOT;
        $params = array();
        return $this->query($tsql, $params);    
    }

    public function GetRoleIdNamePairList() {
        $tsql = <<<EOT
        select id, name from role order by name;
        EOT;
        $params = array();
        return $this->query($tsql, $params);    
    }

    public function GetSanitisedInput($data, $forNew = false) {
        
        if (empty($data->username) ) return null;
        if (empty($data->role) ) return null;
        if (empty($data->session_user_id)) return null;

        $input = new \stdClass();
        $input->username = trim($data->username);
        $input->role = trim($data->role);

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

    public function RegisterRoleAssignment($data) {
        $tsql = "insert into role_assignment (login_id, role_id, create_at, create_by, update_at, update_by) values (?, ?, ?, ?, ?, ?);";
        $params = array(&$data->username, &$data->role
            , &$data->create_at, &$data->create_by, &$data->update_at, &$data->update_by);
        return $this->execute($tsql, $params);
    }

    public function RemoveRoleAssignment($data) {
        $tsql = "delete from role_assignment where login_id = ? and role_id = ?;";
        $params = array(&$data->username, &$data->role);
        return $this->execute($tsql, $params);
    }

}

?>