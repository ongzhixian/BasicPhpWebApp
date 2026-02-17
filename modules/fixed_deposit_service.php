<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IFixedDepositService {
    
    public function GetFixedDepositList($user_id);
    public function GetFixedDeposit($fixedDepositId = null);
    public function GetTotalFixedDepositPlacementAmount($user_id);
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterFixedDeposit($data); // ADD
    // public function UpdateFixedDeposit($data); // UPDATE

}

class FixedDepositService extends BaseDataService implements IFixedDepositService
{
    public function GetFixedDepositList($user_id) {
        $tsql = <<<EOT
        SELECT fd.id
            , b.name
            , fd.description
            , fd.reference_code
            , fd.placement_amount
            , fd.interest_per_annum_percentage
            , fd.effective_date
            , fd.tenor_in_days
            , fd.maturity_date
            , fd.create_at
            , fd.update_at
        FROM fixed_deposit fd
        inner join bank b on fd.bank_id = b.id
        where fd.create_by = ?
        order by fd.maturity_date desc;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetFixedDeposit($fixedDepositId = null) {
        $tsql = <<<EOT
        SELECT fd.id
            , b.name
            , fd.description
            , fd.reference_code
            , fd.placement_amount
            , fd.interest_per_annum_percentage
            , fd.effective_date
            , fd.tenor_in_days
            , fd.maturity_date
            , fd.create_at
            , fd.update_at
        FROM fixed_deposit fd
        inner join bank b on fd.bank_id = b.id
        where fd.id = ?;
        EOT;
        $params = array(&$fixedDepositId);
        return $this->query($tsql, $params);
    }

    public function GetTotalFixedDepositPlacementAmount($user_id) {
        $tsql = <<<EOT
        select sum(placement_amount) as totalPlacementAmount 
        from [PineappleFinance].[dbo].[fixed_deposit]
        where create_by = ?;
        EOT;
        $params = array(&$user_id);        
        return $this->query($tsql, $params);
    }

    public function GetSanitisedInput($data, $forNew = false) {
        if (empty($data->description)) return null;
        if (empty($data->reference_code)) return null;
        if (!is_numeric($data->placement_amount)) return null;
        if (!is_numeric($data->interest_rate_percentage)) return null;
        if (empty($data->effective_date)) return null;
        if (!is_numeric($data->tenor_in_days)) return null;
        if (empty($data->session_user_id)) return null;

        if ( $forNew ) { 
            if (empty($data->bank_code)) return null;
        } else {
            if (empty($data->id)) return null;
        }

        $timestamp = date("Y-m-d H:i:s");
        $session_user_id = trim($data->session_user_id);

        $input = new \stdClass();
        $input->description = trim($data->description);
        $input->reference_code = trim($data->reference_code);
        $input->placement_amount = trim($data->placement_amount);
        $input->interest_rate_percentage = trim($data->interest_rate_percentage);
        $input->effective_date = trim($data->effective_date);
        $input->tenor_in_days = trim($data->tenor_in_days);
        $input->update_by = $session_user_id;
        $input->update_at = $timestamp;

        if ( $forNew ) {
            $input->bank_code = trim($data->bank_code);
            $input->create_by = $session_user_id;
            $input->create_at = $timestamp;
        } else {
            $input->id = trim($data->id);
        }

        return $input;
    }

    public function RegisterFixedDeposit($data) {
        $tsql = "insert into fixed_deposit (bank_id, description, reference_code, placement_amount, interest_per_annum_percentage, effective_date, tenor_in_days, create_by, create_at, update_by, update_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $params = array(&$data->bank_code, &$data->description, &$data->reference_code, &$data->placement_amount, &$data->interest_rate_percentage, &$data->effective_date, &$data->tenor_in_days
            , &$data->create_by, &$data->create_at, &$data->update_by, &$data->update_at);
        return $this->execute($tsql, $params);
    }

    public function UpdateFixedDeposit($data) {
        $tsql = <<<EOT
        update fd
        set fd.description = ?
        , fd.reference_code = ?
        , fd.placement_amount = ?
        , fd.interest_per_annum_percentage = ?
        , fd.effective_date = ?
        , fd.tenor_in_days = ?
        , fd.update_by = ?
        , fd.update_at = ?
        from fixed_deposit fd
        where fd.id = ?;
        EOT;
        $params = array(&$data->description, &$data->reference_code
            , &$data->placement_amount, &$data->interest_rate_percentage
            , &$data->effective_date, &$data->tenor_in_days
            , &$data->update_by, &$data->update_at, &$data->id);
        return $this->execute($tsql, $params);
    }
}

?>