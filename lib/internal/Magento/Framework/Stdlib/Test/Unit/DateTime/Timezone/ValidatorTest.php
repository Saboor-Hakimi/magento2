<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Framework\Stdlib\Test\Unit\DateTime\Timezone;

class ValidatorTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\Timezone\Validator
     */
    protected $_validator;

    /**
     * @dataProvider validateWithTimestampOutOfSystemRangeDataProvider
     */
    public function testValidateWithTimestampOutOfSystemRangeThrowsException($range, $validateArgs)
    {
        $this->expectException(\Magento\Framework\Exception\ValidatorException::class);

        $this->_validator = new \Magento\Framework\Stdlib\DateTime\Timezone\Validator($range['min'], $range['max']);
        $this->_validator->validate($validateArgs['timestamp'], $validateArgs['to_date']);

        $this->expectExceptionMessage(
            "The transition year isn't included in the system date range. Verify the year date range and try again."
        );
    }

    /**
     */
    public function testValidateWithTimestampOutOfSpecifiedRangeThrowsException()
    {
        $this->expectException(\Magento\Framework\Exception\ValidatorException::class);
        $this->expectExceptionMessage('Transition year is out of specified date range.');

        $this->_validator = new \Magento\Framework\Stdlib\DateTime\Timezone\Validator();
        $this->_validator->validate(mktime(1, 2, 3, 4, 5, 2007), mktime(1, 2, 3, 4, 5, 2006));
    }

    /**
     * @return array
     */
    public function validateWithTimestampOutOfSystemRangeDataProvider()
    {
        return [
            [['min' => 2000, 'max' => 2030], ['timestamp' => PHP_INT_MAX, 'to_date' => PHP_INT_MAX]],
            [['min' => 2000, 'max' => 2030], ['timestamp' => 0, 'to_date' => PHP_INT_MAX]]
        ];
    }
}
