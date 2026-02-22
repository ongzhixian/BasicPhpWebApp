<?php
namespace PineappleFinance\Tests\Services;

use PHPUnit\Framework\TestCase;
use PineappleFinance\Services\BaseDataService;

// Define constants for testing if not already defined
if (!defined('SQLSERVER')) define('SQLSERVER', 'test_server');
if (!defined('PINEAPPLE_DB')) define('PINEAPPLE_DB', 'test_db');

/**
 * Create a concrete class because BaseDataService is abstract
 */
class ConcreteDataService extends BaseDataService {
    // No changes needed, just making it instantiable
}

class BaseDataServiceTest extends TestCase
{
    private $service;

    protected function setUp(): void
    {
        $this->service = new ConcreteDataService();
    }

    /**
     * Test that the constructor correctly sets protected properties
     */
    public function testConstructorSetsProperties()
    {
        $reflection = new \ReflectionClass($this->service);
        
        $serverName = $reflection->getProperty('serverName');
        $serverName->setAccessible(true);
        
        $connectionInfo = $reflection->getProperty('connectionInfo');
        $connectionInfo->setAccessible(true);

        $this->assertEquals('test_server', $serverName->getValue($this->service));
        $this->assertEquals(['Database' => 'test_db'], $connectionInfo->getValue($this->service));
    }

    /**
     * This test demonstrates how you would handle an execution failure.
     * Note: In a real CI environment, you'd mock the 'sqlsrv_connect' function.
     */
    public function testExecuteThrowsExceptionOnConnectionFailure()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/Database connection failed/');

        // If sqlsrv extension isn't loaded or server is fake, it will throw
        $this->service->execute("SELECT 1");
    }

    /**
     * Testing logic flow with a Mock of the service itself
     */
    public function testExecuteReturnsSuccessArrayOnValidRun()
    {
        // We create a partial mock to bypass the private getConnection() 
        // if we wanted to avoid real DB hits. 
        // However, since getConnection is private, we'd typically 
        // refactor it to protected or use a Bridge pattern for better testability.
        
        $this->markTestSkipped('Requires sqlsrv extension and active connection to test full integration.');
    }
}