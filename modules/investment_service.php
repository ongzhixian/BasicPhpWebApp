<?php
namespace PineappleFinance\Services;

require_once "base_data_service.php";

interface IInvestmentService {
    
    public function GetInvestmentList();
    public function GetInvestment($investmentId = null);
    public function GetTotalInvestmentAmount();
    
    // public function GetSanitisedInput($input, $forNew = false);

    public function RegisterInvestment($data);  // ADD
    public function UpdateInvestment($data);    // UPDATE
}

class InvestmentService extends BaseDataService implements IInvestmentService
{
    public function GetInvestmentList() {
        $tsql = <<<EOT
        select *, 'investment' as type from [PineappleFinance].[dbo].[investment];
        EOT;
        $params = array();
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

    public function GetTotalInvestmentAmount() {
        $tsql = <<<EOT
        select sum(investment_amount) as totalInvestmentAmount 
        from [PineappleFinance].[dbo].[investment];
        EOT;
        $params = array();        
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

        // [description] [varchar](128) NOT NULL,
        // [reference_code] [varchar](128) NOT NULL,
        // [investment_amount] [decimal](18, 2) NOT NULL,
        // [effective_date] [datetime2](7) NOT NULL,
        // [current_value] [decimal](18, 2) NOT NULL,
        
        if ( !$forNew && empty($data->id) ) return null;
        if (empty($data->description)) return null;
        if (empty($data->reference_code)) return null;
        if (!is_numeric($data->investment_amount)) return null;
        if (empty($data->effective_date)) return null;
        if (!is_numeric($data->current_value)) return null;
        
        $input = new \stdClass();
        if ( !$forNew ) $input->id = trim($data->id);
        $input->description = trim($data->description);
        $input->reference_code = trim($data->reference_code);
        $input->investment_amount = trim($data->investment_amount);
        $input->effective_date = trim($data->effective_date);
        $input->current_value = trim($data->current_value);

        return $input;
    }

    public function RegisterInvestment($data) {
        $tsql = "insert into investment (description, reference_code, investment_amount, effective_date, current_value) values (?, ?, ?, ?, ?);";
        $params = array(&$data->description, &$data->reference_code, &$data->investment_amount, &$data->effective_date, &$data->current_value);
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
        , update_at = CURRENT_TIMESTAMP
        where id = ?;
        EOT;
        $params = array(&$data->description, &$data->reference_code, &$data->investment_amount, &$data->effective_date, &$data->current_value, &$data->id);
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