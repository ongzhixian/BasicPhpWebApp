<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface ILoginService {
    
    public function GetLoginList();
    public function GetValidatedLogin($input);
    // public function GetBankAccount($accountCode = null);
    // public function GetTotalBankBalance();
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterLogin($data); // ADD
    // public function UpdateBankAccount($data); // UPDATE

}

class LoginService extends BaseDataService implements ILoginService
{
    public function GetLoginList() {
        $tsql = <<<EOT
        select	username, update_at 
        from	login 
        order by username desc;
        EOT;
        $params = array();
        return $this->query($tsql, $params);
    }

    public function GetLogin($username) {
        $tsql = <<<EOT
        select	username, update_at 
        from	login 
        where   username = ?;
        EOT;
        $params = array($username);
        return $this->query($tsql, $params)[0];
    }

    public function GetValidatedLogin($input) {
        $tsql = <<<EOT
        select	username, password_hash, id
        from	login 
        where   username = ?;
        EOT;
        $params = array($input->username);
        $result = $this->query($tsql, $params);

        if ($result && password_verify($input->password, $result[0]['password_hash'])) {
            return [
                "username" => $result[0]['username'],
                "user_id" => $result[0]['id'],
            ];
        } else {
            return null;
        }
    }

    public function GetSanitisedInput($data, $forNew = false) {
        
        if (empty($data->username) ) return null;
        if (empty($data->password)) return null;
        if (empty($data->session_user_id)) return null;

        $input = new \stdClass();
        $input->username = trim($data->username);
        $input->password = password_hash(trim($data->password), PASSWORD_DEFAULT);

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

    public function RegisterLogin($data) {
        $tsql = "insert into login (username, password_hash, create_at, create_by, update_at, update_by) values (?, ?, ?, ?, ?, ?);";
        $params = array(&$data->username, &$data->password
            , &$data->create_at, &$data->create_by, &$data->update_at, &$data->update_by);
        return $this->execute($tsql, $params);
    }

    public function UpdateLoginPassword($data) {
        $tsql = "update login set password_hash = ?, update_at = ?, update_by = ? where username = ?;";
        $params = array(&$data->password, &$data->update_at, &$data->update_by, &$data->username);
        return $this->execute($tsql, $params);
    }

    // public function UpdateBankAccount($data) {
    //     $tsql = <<<EOT
    //     update ba
    //     set ba.description = ?
    //     , ba.balance = ?
    //     , ba.update_at = CURRENT_TIMESTAMP
    //     from bank_account ba
    //     where ba.account_code = ?;
    //     EOT;
    //     $params = array(&$data->account_description, &$data->account_balance, &$data->account_number);
    //     return $this->execute($tsql, $params);
    // }
}

?>