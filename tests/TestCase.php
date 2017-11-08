<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function __construct($name = null, array $data = [], $dataName = '')
    {

        if (!class_exists('PHPUnit_Framework_Constraint')) {
            class_alias(\PHPUnit\Framework\Constraint\Constraint::class, 'PHPUnit_Framework_Constraint');
        }

        if (!class_exists('PHPUnit_Framework_TestCase')) {
            class_alias(\PHPUnit\Framework\TestCase::class, 'PHPUnit_Framework_TestCase');
        }

        if (!class_exists('PHPUnit_Framework_Assert')) {
            class_alias(\PHPUnit\Framework\Assert::class, 'PHPUnit_Framework_Assert');
        }


        parent::__construct($name, $data, $dataName);
    }
}
