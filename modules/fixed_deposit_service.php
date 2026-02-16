<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IFixedDepositService {
    
    public function GetFixedDepositList();
    public function GetFixedDeposit($fixedDepositId = null);
    public function GetTotalFixedDepositPlacementAmount();
    
    public function GetSanitisedInput($input, $forNew = false);

    public function RegisterFixedDeposit($data); // ADD
    // public function UpdateFixedDeposit($data); // UPDATE

}

class FixedDepositService extends BaseDataService implements IFixedDepositService
{
    public function GetFixedDepositList() {
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
                order by fd.maturity_date desc;
        EOT;
        $params = array();
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

    public function GetTotalFixedDepositPlacementAmount() {
        $tsql = <<<EOT
        select sum(placement_amount) as totalPlacementAmount 
        from [PineappleFinance].[dbo].[fixed_deposit];
        EOT;
        $params = array();        
        return $this->query($tsql, $params);
    }

    public function GetSanitisedInput($data, $forNew = false) {

        if ( $forNew && empty($data->bank_code) ) return null;
        if (empty($data->description)) return null;
        if (empty($data->reference_code)) return null;
        if (!is_numeric($data->placement_amount)) return null;
        if (!is_numeric($data->interest_rate_percentage)) return null;
        if (empty($data->effective_date)) return null;
        if (!is_numeric($data->tenor_in_days)) return null;

        $input = new \stdClass();
        if ( !$forNew )$input->id = trim($data->id);
        if ( $forNew ) $input->bank_code = trim($data->bank_code);
        $input->description = trim($data->description);
        $input->reference_code = trim($data->reference_code);
        $input->placement_amount = trim($data->placement_amount);
        $input->interest_rate_percentage = trim($data->interest_rate_percentage);
        $input->effective_date = trim($data->effective_date);
        $input->tenor_in_days = trim($data->tenor_in_days);

        return $input;
    }

    public function RegisterFixedDeposit($data) {
        $tsql = "insert into fixed_deposit (bank_id, description, reference_code, placement_amount, interest_per_annum_percentage, effective_date, tenor_in_days) values (?, ?, ?, ?, ?, ?, ?);";
        $params = array(&$data->bank_code, &$data->description, &$data->reference_code, &$data->placement_amount, &$data->interest_rate_percentage, &$data->effective_date, &$data->tenor_in_days);
        return $this->execute($tsql, $params);
    }

    public function UpdateFixedDeposit($data) {
        // print_r($data);
        // reference_code, placement_amount, interest_per_annum_percentage, effective_date, tenor_in_days
        $tsql = <<<EOT
        update fd
        set fd.description = ?
        , fd.reference_code = ?
        , fd.placement_amount = ?
        , fd.interest_per_annum_percentage = ?
        , fd.effective_date = ?
        , fd.tenor_in_days = ?
        , fd.update_at = CURRENT_TIMESTAMP
        from fixed_deposit fd
        where fd.id = ?;
        EOT;
        $params = array(&$data->description, &$data->reference_code
            , &$data->placement_amount, &$data->interest_rate_percentage
            , &$data->effective_date, &$data->tenor_in_days, &$data->id);
        return $this->execute($tsql, $params);
    }
}

?>