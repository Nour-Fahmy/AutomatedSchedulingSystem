<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DatabaseSingleton;
use PDO;

class databaseTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    // public function test_example(): void
    // {
    //     $this->assertTrue(true);
    // }
    public function test_database_connection(): void
    {
        $conn = DatabaseSingleton::getInstance()->getConnection();
        $this->assertNotNull($conn);
    }
}
