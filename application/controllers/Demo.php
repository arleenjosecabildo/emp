<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demo extends CI_Controller

{

    const DEFAULT_BONUS_DAY = 10;

    public function __construct()

    {
        parent::__construct();
    }

    public function index()

    {
        $this->load->view('welcome_message');
    }

    public function generateCsv()

    {
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Europe/London'));
        $currentYear = $dt->format('Y');
        $data = [];
        for ($month = 1; $month <= 12; $month ++) {
            $period = $this->generatePeriod($dt, $currentYear, $month);
            $basicPayDate = $this->getBasicPaymentDates($dt);
            $bonusDate = $this->getBonusDates($dt);

            $data[] = [
                $period,
                $basicPayDate,
                $bonusDate
            ];
        }

        $filename = 'emporium.csv';

        // open csv file for writing
        $f = fopen($filename, 'w');

        if ($f === false) {
            die('Error opening the file ' . $filename);
        }

        // write each row at a time to a file
        foreach ($data as $row) {
            fputcsv($f, $row);
        }

        // close the file
        fclose($f);
    }

    private function generatePeriod(DateTime $dt, $currentYear, $month)

    {

        // for ($i = 1; $i <= 12; $i ++) {

        /**
         * Set new date starting with the
         */
        $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
        $dt->setDate($currentYear, $month, $maxDays);

        /**
         * format the period/s
         */

        $period = $dt->format('M/y');

        // echo $period . "\t";

        return $period;

    /**
     * get basic payment dates
     */

        // $this->getBasicPaymentDates($dt);
        // $this->getBonusDates($dt);
        // }
    }

    private function getBasicPaymentDates(DateTime $dt)

    {

        /**
         *
         * get the Day
         *
         *
         *
         * @var string $day
         *
         */
        $day = $dt->format('D');

        if ($day == 'Sat') {
            // echo '(' . $day . ')' . "\t";

            $dt->modify('- 1 day');

            $lastDate = $dt->format('Y-m-d');
        } else if ($day == 'Sun') {
            // echo '(' . $day . ')' . "\t";
            $dt->modify('- 2 days');

            $lastDate = $dt->format('Y-m-d');
        } else {
            $lastDate = $dt->format('Y-m-t');
        }

        // $day = $dt->format('D');

        /**
         */
        // echo $lastDate . '(' . $day . ')' . "\t";

        return $lastDate;
    }

    private function getBonusDates(DateTime $dt)

    {
        $year = $dt->format('Y');
        $month = $dt->format('m');
        // modify the date
        $dt->setDate($year, $month, self::DEFAULT_BONUS_DAY);
        $bonusDay = $dt->format('D');
        if ($bonusDay == 'Sat') {
            $dt->modify('+ 2 days');
        } else if ($bonusDay == 'Sun') {
            $dt->modify('+1 day');
        } else {
            // do nothing for now
        }
        // echo $dt->format('Y-m-d') . PHP_EOL;

        return $dt->format('Y-m-d');
    }
}