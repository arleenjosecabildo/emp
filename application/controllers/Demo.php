<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Demo extends CI_Controller

{

    /**
     *
     * @var integer
     */
    const DEFAULT_BONUS_DAY = 10;

    /**
     *
     * @var string
     */
    const CSV_FILENAME = 'emporium.csv';

    /**
     * Default constructor
     */
    public function __construct()

    {
        parent::__construct();
    }

    /**
     * main method
     */
    public function index()

    {
        $this->generateCsv();
    }

    /**
     * Generates the csv
     */
    public function generateCsv()

    {
        $dt = new DateTime();
        $dt->setTimezone(new DateTimeZone('Europe/London'));
        $currentYear = $dt->format('Y');
        $data = [];
        for ($month = 1; $month <= 12; $month ++) {
           
            /**
             * Set new date starting with the max date
             */

            $maxDays = cal_days_in_month(CAL_GREGORIAN, $month, $currentYear);
            $dt->setDate($currentYear, $month, $maxDays);

            $period = $this->getPeriod($dt);
            $basicPayDate = $this->getBasicPaymentDates($dt);
            $bonusDate = $this->getBonusDates($dt);

            $data[] = [
                $period,
                $basicPayDate,
                $bonusDate
            ];
        }

        if (file_exists(self::CSV_FILENAME)) {

            echo '== Deleting existing ' . self::CSV_FILENAME . '==' . PHP_EOL;
            unlink(self::CSV_FILENAME);
        } else {
            echo '== No existing' . self::CSV_FILENAME . '==' . PHP_EOL;
        }

        // open csv file for writing
        $f = fopen(self::CSV_FILENAME, 'w');

        if ($f === false) {
            die('Error opening the file ' . self::CSV_FILENAME);
        }

        echo "== Writing Data  ==" . PHP_EOL;
        foreach ($data as $row) {
            fputcsv($f, $row);
        }

        // close the file
        fclose($f);

        echo "== DONE  ==" . PHP_EOL;
    }

    /**
     * Generates the Period with M/y foramat
     *
     * @param DateTime $dt
     * @return string
     */
    protected function getPeriod(DateTime $dt)

    {

        /**
         * format the period/s
         */
        return $dt->format('M/y');

    }

    /**
     * Creates the Basic Payments Dates
     *
     * @param DateTime $dt
     * @return string
     */
    protected function getBasicPaymentDates(DateTime $dt)

    {

        /**
         *
         * get the Day
         *
         * @var string $day
         *
         */
        $day = $dt->format('D');

        if ($day == 'Sat') {

            $dt->modify('- 1 day');

            $lastDate = $dt->format('Y-m-d');
        } else if ($day == 'Sun') {
            // echo '(' . $day . ')' . "\t";
            $dt->modify('- 2 days');

            $lastDate = $dt->format('Y-m-d');
        } else {
            $lastDate = $dt->format('Y-m-t');
        }

        return $lastDate;
    }

    /**
     * Generates the bonus date
     *
     * @param DateTime $dt
     * @return string
     */
    protected function getBonusDates(DateTime $dt)

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

        return $dt->format('Y-m-d');
    }
}