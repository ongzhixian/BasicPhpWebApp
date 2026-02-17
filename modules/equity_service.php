<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IEquityService {
    
    public function GetEquityList($user_id);
    public function GetEquity($equityId = null);
    public function GetTotalEquityInvestmentAmount($user_id);
    // public function GetFixedDeposit($fixedDepositId = null);
    // public function GetTotalFixedDepositPlacementAmount();
    
    public function GetSanitisedInput($data, $forNew = false);

    public function RegisterEquity($data); // ADD
    public function UpdateEquity($data); // UPDATE
}

class EquityService extends BaseDataService implements IEquityService
{
    public function GetEquityList($user_id) {
        $tsql = <<<EOT
        select  id
                , description
                , symbol as reference_code
                , quantity * buy_price as investment_value
                , buy_date as effective_date
                , quantity * current_price as current_value
                , create_at
                , update_at
                , 'equity' as type
        from [PineappleFinance].[dbo].[equity]
        where create_by = ?;
        EOT;
        $params = array(&$user_id);
        return $this->query($tsql, $params);
    }

    public function GetEquity($equityId = null) {
        $tsql = <<<EOT
        select  id
                , symbol
                , description
                , quantity
                , buy_price
                , buy_date
                , current_price
                , create_at
                , update_at
                , 'equity' as type
        from [PineappleFinance].[dbo].[equity]
        where id = ?;
        EOT;
        $params = array(&$equityId);
        return $this->query($tsql, $params);
    }

    public function GetTotalEquityInvestmentAmount($user_id) {
        $tsql = <<<EOT
        select sum(quantity * buy_price) as totalEquityInvestmentAmount 
        from [PineappleFinance].[dbo].[equity]
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
        if ( !$forNew && empty($data->id) ) return null;
        if (empty($data->symbol)) return null;
        if (empty($data->description)) return null;
        if (!is_numeric($data->quantity)) return null;
        if (!is_numeric($data->buy_price)) return null;
        if (!is_numeric($data->current_price)) return null;
        if (empty($data->buy_date)) return null;
        if (empty($data->session_user_id)) return null;

        $timestamp = date("Y-m-d H:i:s");
        $session_user_id = trim($data->session_user_id);

        $input = new \stdClass();
        $input->symbol = trim($data->symbol);
        $input->description = trim($data->description);
        $input->quantity = trim($data->quantity);
        $input->buy_price = trim($data->buy_price);
        $input->buy_date = trim($data->buy_date);
        $input->current_price = trim($data->current_price);
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

    public function RegisterEquity($data) {
        $tsql = "insert into equity (symbol, description, quantity, buy_price, buy_date, current_price, create_by, create_at, update_by, update_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
        $params = array(&$data->symbol, &$data->description, &$data->quantity, &$data->buy_price, &$data->buy_date, &$data->current_price, &$data->create_by, &$data->create_at, &$data->update_by, &$data->update_at);
        return $this->execute($tsql, $params);
    }

    public function UpdateEquity($data) {
        $tsql = <<<EOT
        update e
        set e.symbol = ?
        , e.description = ?
        , e.quantity = ?
        , e.buy_price = ?
        , e.buy_date = ?
        , e.current_price = ?
        , e.update_at = ?
        , e.update_by = ?
        from equity e
        where e.id = ?;
        EOT;
        $params = array(&$data->symbol, &$data->description, &$data->quantity, &$data->buy_price, &$data->buy_date, &$data->current_price, &$data->update_at, &$data->update_by, &$data->id);
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