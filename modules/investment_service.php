<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IInvestmentService {
    
    public function GetInvestmentList($user_id);
    public function GetInvestment($investmentId = null);
    public function GetTotalInvestmentAmount($user_id);
    
    public function GetSanitisedInput($data, $forNew = false);

    public function RegisterInvestment($data);  // ADD
    public function UpdateInvestment($data);    // UPDATE
}

class InvestmentService extends BaseDataService implements IInvestmentService
{
    public function GetInvestmentList($user_id) {
        $tsql = <<<EOT
        select *, 'investment' as type from [PineappleFinance].[dbo].[investment]
        where create_by = ?;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetInvestment($investmentId = null) {
        $tsql = <<<EOT
        select *, 'investment' as type from [PineappleFinance].[dbo].[investment]
        where id = ?;
        EOT;
        $params = array(&$investmentId);
        return $this->query($tsql, $params);
    }

    public function GetTotalInvestmentAmount($user_id) {
        $tsql = <<<EOT
        select sum(investment_amount) as totalInvestmentAmount 
        from [PineappleFinance].[dbo].[investment]
        where create_by = ?;
        EOT;
        $params = array(&$user_id);        
        return $this->query($tsql, $params);
    }

    // public function GetTotalFixedDepositPlacementAmount() {
    //     $tsql = <<<EOT
    //     select sum(placement_amount) as totalPlacementAmount 
    //     from [PineappleFinance].[dbo].[fixed_deposit];
    //     EOT;
    //     $params = array();        
    //     return $this->query($tsql, $params);
    // }

    public function GetSanitisedInput($data, $forNew = false) {

        if (empty($data->description)) return null;
        if (empty($data->reference_code)) return null;
        if (!is_numeric($data->investment_amount)) return null;
        if (empty($data->effective_date)) return null;
        if (!is_numeric($data->current_value)) return null;
        if (empty($data->session_user_id)) return null;
        
        if ( !$forNew && empty($data->id) ) return null;

        $timestamp = date("Y-m-d H:i:s");
        $session_user_id = trim($data->session_user_id);
        
        $input = new \stdClass();
        $input->description = trim($data->description);
        $input->reference_code = trim($data->reference_code);
        $input->investment_amount = trim($data->investment_amount);
        $input->effective_date = trim($data->effective_date);
        $input->current_value = trim($data->current_value);
        $input->update_by = $session_user_id;
        $input->update_at = $timestamp;

        if ( $forNew ) {
            $input->create_by = $session_user_id;
            $input->create_at = $timestamp;
        } else {
            $input->id = trim($data->id);
        }

        return $input;
    }

    public function RegisterInvestment($data) {
        $tsql = "insert into investment (description, reference_code, investment_amount, effective_date, current_value, create_by, create_at, update_by, update_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $params = array(&$data->description, &$data->reference_code, &$data->investment_amount, &$data->effective_date, &$data->current_value, &$data->create_by, &$data->create_at, &$data->update_by, &$data->update_at);
        return $this->execute($tsql, $params);
    }

    public function UpdateInvestment($data) {
        $tsql = <<<EOT
        update investment
        set description = ?
        , reference_code = ?
        , investment_amount = ?
        , effective_date = ?
        , current_value = ?
        , update_by = ?
        , update_at = ?
        where id = ?;
        EOT;
        $params = array(&$data->description, &$data->reference_code, &$data->investment_amount, &$data->effective_date, &$data->current_value, &$data->update_by, &$data->update_at, &$data->id);
        return $this->execute($tsql, $params);
    }

    // public function UpdateFixedDeposit($data) {
    //     // print_r($data);
    //     // reference_code, placement_amount, interest_per_annum_percentage, effective_date, tenor_in_days
    //     $tsql = <<<EOT
    //     update fd
    //     set fd.description = ?
    //     , fd.reference_code = ?
    //     , fd.placement_amount = ?
    //     , fd.interest_per_annum_percentage = ?
    //     , fd.effective_date = ?
    //     , fd.tenor_in_days = ?
    //     , fd.update_at = CURRENT_TIMESTAMP
    //     from fixed_deposit fd
    //     where fd.id = ?;
    //     EOT;
    //     $params = array(&$data->description, &$data->reference_code
    //         , &$data->placement_amount, &$data->interest_rate_percentage
    //         , &$data->effective_date, &$data->tenor_in_days, &$data->id);
    //     return $this->execute($tsql, $params);
    // }
}

?>