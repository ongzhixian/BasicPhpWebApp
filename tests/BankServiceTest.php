<?php
require_once "_config.php"; // NOSONAR: Manual auto-loader
use PineappleFinance\Includes\DefaultPageHeader;

require_once "modules/bank_service.php";
use PineappleFinance\Services\BankService;
$bankService = new BankService();

use PHPUnit\Framework\TestCase;

// if (!defined('SQLSERVER')) define('SQLSERVER', 'localhost');
// if (!defined('PINEAPPLE_DB')) define('PINEAPPLE_DB', 'test_db');
// if (!defined('DEBUG')) define('DEBUG', true);

class BankServiceTest extends TestCase
{
    private $bankService;

    protected function setUp(): void
    {
        // We use getMockBuilder to mock the parent methods (query/execute) 
        // so we don't actually hit a real SQL Server during unit tests.
        $this->bankService = $this->getMockBuilder(BankService::class)
            ->onlyMethods(['query', 'execute'])
            ->getMock();
    }

    /**
     * Test data sanitization logic (Logic Test)
     */
    public function testGetSanitisedInputReturnsObjectOnValidData()
    {
        $rawData = new \stdClass();
        $rawData->bank_code = " CHASE ";
        $rawData->full_name = "JP Morgan Chase";
        $rawData->session_user_id = "user123";

        $result = $this->bankService->GetSanitisedInput($rawData, true);

        $this->assertNotNull($result);
        $this->assertEquals("CHASE", $result->bank_code); // Tests trim
        $this->assertEquals("user123", $result->create_by);
        $this->assertObjectHasProperty('create_at', $result);
    }

    public function testGetSanitisedInputReturnsNullOnMissingData()
    {
        $rawData = new \stdClass();
        $rawData->bank_code = ""; // Empty code
        
        $result = $this->bankService->GetSanitisedInput($rawData);
        $this->assertNull($result);
    }

    /**
     * Test GetBankList (Database Interaction Mock)
     */
    public function testGetBankListCallsQueryWithCorrectParams()
    {
        $userId = 99;
        $mockResponse = [['name' => 'Bank A'], ['name' => 'Bank B']];

        // Ensure the query method is called once with the right SQL and user ID
        $this->bankService->expects($this->once())
            ->method('query')
            ->with(
                $this->stringContains('select * from bank where create_by = ?'),
                $this->callback(function($params) use ($userId) {
                    return $params[0] === $userId;
                })
            )
            ->willReturn($mockResponse);

        $result = $this->bankService->GetBankList($userId);
        $this->assertEquals($mockResponse, $result);
    }

    /**
     * Test RegisterBank (Database Interaction Mock)
     */
    public function testRegisterBankCallsExecute()
    {
        $data = new \stdClass();
        $data->bank_code = "TEST";
        $data->full_name = "Test Bank";
        $data->create_by = "admin";
        $data->create_at = "2023-01-01";
        $data->update_by = "admin";
        $data->update_at = "2023-01-01";

        $this->bankService->expects($this->once())
            ->method('execute')
            ->willReturn(['success' => true, 'message' => 'Operation completed successfully.']);

        $result = $this->bankService->RegisterBank($data);
        $this->assertTrue($result['success']);
    }

    /**
     * Test behavior when the database connection fails
     */
    // public function testGetBankListThrowsExceptionOnConnectionFailure()
    // {
    //     // 1. Create a mock of the service, specifically targeting the connection logic
    //     $mockService = $this->getMockBuilder(BankService::class)
    //         ->onlyMethods(['getConnection']) // Only mock the connection attempt
    //         ->getMock();

    //     // 2. Set the expectation: getConnection should throw an exception
    //     $mockService->method('getConnection')
    //         ->willThrowException(new \RuntimeException("Connection timeout"));

    //     // 3. Inform PHPUnit that we expect an exception to be thrown
    //     $this->expectException(\RuntimeException::class);
    //     $this->expectExceptionMessage("Connection timeout");

    //     // 4. Trigger the call
    //     $mockService->GetBankList(1);
    // }
}

?>