<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once (APPPATH . "controllers/Demo.php");

class Demotest extends Demo
{

    public function __construct()

    {
        parent::__construct();

        $this->load->library('unit_test');
    }

    function testGetPeriod()
    {
        $dt = new DateTime();
        $currentYear = $dt->format('Y');
        $months = 12;

        $expected = [
            'Jan/22',
            'Feb/22',
            'Mar/22',
            'Apr/22',
            'May/22',
            'Jun/22',
            'Jul/22',
            'Aug/22',
            'Sep/22',
            'Oct/22',
            'Nov/22',
            'Dec/22'
        ];

        for ($month = 1; $month <= $months; $month ++) {
            $i = $month - 1;

            $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            $dt->setDate($currentYear, $month, $maxDays);

            $actual = $this->getPeriod($dt);

            $this->unit->run($expected[$i], $actual, $actual);
        }

        print_r($this->unit->result());
    }

    function testGetBasicPaymentDates()
    {
        $dt = new DateTime();
        $currentYear = $dt->format('Y');
        $months = 12;

        /**
         * Expected Payment Dates
         *
         * @var array $expected
         */
        $expected = [
            '2022-01-31',
            '2022-02-28',
            '2022-03-31',
            '2022-04-29', // 30th is Saturday
            '2022-05-31',
            '2022-06-30',
            '2022-07-29', // 31 is Sunday
            '2022-08-31',
            '2022-09-30',
            '2022-10-31',
            '2022-11-30',
            '2022-12-30' // 31 is Saurday
        ];
        $actual = $this->getBasicPaymentDates($dt);

        for ($month = 1; $month <= $months; $month ++) {
            $i = $month - 1;

            $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            $dt->setDate($currentYear, $month, $maxDays);

            $actual = $this->getBasicPaymentDates($dt);

            $this->unit->run($expected[$i], $actual, $actual);
        }

        print_r($this->unit->result());
    }

    function testGetBonusDates()
    {
        $dt = new DateTime();
        $currentYear = $dt->format('Y');
        $months = 12;

        /**
         * Expected Payment Dates
         *
         * @var array $expected
         */
        $expected = [
            '2022-01-10',
            '2022-02-10',
            '2022-03-10',
            '2022-04-11', // 10th is Sunday
            '2022-05-10',
            '2022-06-10',
            '2022-07-11', // 10th is Sunday
            '2022-08-10',
            '2022-09-10',
            '2022-10-10',
            '2022-11-10',
            '2022-12-12' // 10th is Saurday
        ];
        $actual = $this->getBasicPaymentDates($dt);

        for ($month = 1; $month <= $months; $month ++) {
            $i = $month - 1;

            $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            $dt->setDate($currentYear, $month, $maxDays);

            $actual = $this->getBonusDates($dt);

            $this->unit->run($expected[$i], $actual, $actual);
        }

        print_r($this->unit->result());
    }
}