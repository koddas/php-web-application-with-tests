<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require dirname(__DIR__) . '/src/Utils.php';

final class UtilsTest extends TestCase
{
    public function testGetNameWithValidValue()
    {
        $utils = new Utils();
        $this->assertEquals("Axel", $utils->get_name(1981, 6, 16));
    }
}
