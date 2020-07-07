<?php

namespace Tests;

abstract class TestCase extends \PHPUnit\Framework\TestCase {

    public function assertNormalizedTextEquals(string $expected, string $test) {
        $normalizedExpected = str_replace("\r\n","\n", trim($expected));
        $normalizedTest = str_replace("\r\n","\n", trim($test));

        $this->assertEquals($normalizedExpected, $normalizedTest);
    }

}
