<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IBankAccountService {
    
    public function GetBankAccountList($user_id);
    public function GetBankAccount($accountCode = null);
    public function GetTotalBankBalance($user_id);
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterBankAccount($data); // ADD
    public function UpdateBankAccount($data); // UPDATE

}

class BankAccountService extends BaseDataService implements IBankAccountService
{
    public function GetBankAccountList($user_id) {
        $tsql = <<<EOT
        select	ba.id
                , b.name
                , ba.account_code
                , ba.description
                , ba.balance
                , ba.update_at
        from	bank_account ba
        inner join bank b on ba.bank_id = b.id
        where b.create_by = ?
        order by b.name;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetBankAccount($accountCode = null) {
        $tsql = <<<EOT
        select	ba.id
                , b.name
                , ba.account_code
                , ba.description
                , ba.balance
                , ba.update_at
        from	bank_account ba
        inner join bank b on ba.bank_id = b.id
        where ba.account_code = ?
        order by b.name;
        EOT;
        $params = array($accountCode);        
        return $this->query($tsql, $params);
    }

    public function GetTotalBankBalance($user_id) {
        $tsql = <<<EOT
        select sum(balance) as balance from bank_account ba
        inner join bank b on ba.bank_id = b.id
        where b.create_by = ?;
        EOT;
        $params = array(&$user_id);        
        return $this->query($tsql, $params);
    }

    public function GetSanitisedInput($data, $forNew = false) {
        
        if ( $forNew && empty($data->bank_code) ) return null;
        if (empty($data->account_number)) return null;
        if (!is_numeric($data->account_balance)) return null;

        $input = new \stdClass();
        if ( $forNew ) $input->bank_code = trim($data->bank_code);
        $input->account_number = trim($data->account_number);
        $input->account_description = trim($data->account_description);
        $input->account_balance = trim($data->account_balance);

        return $input;
    }

    public function RegisterBankAccount($data) {
        $tsql = "insert into bank_account (bank_id, account_code, description, balance) values (?, ?, ?, ?);";
        $params = array(&$data->bank_code, &$data->account_number, &$data->account_description, &$data->account_balance);
        return $this->execute($tsql, $params);
    }

    public function UpdateBankAccount($data) {
        $tsql = <<<EOT
        update ba
        set ba.description = ?
        , ba.balance = ?
        , ba.update_at = CURRENT_TIMESTAMP
        from bank_account ba
        where ba.account_code = ?;
        EOT;
        $params = array(&$data->account_description, &$data->account_balance, &$data->account_number);
        return $this->execute($tsql, $params);
    }
}

?>