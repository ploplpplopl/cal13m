<?php

namespace cal13m\App;

use cal13m\App\model\Year;
use cal13m\App\model\Month;
use cal13m\App\model\Day;
use cal13m\App\model\Cell;

class Calendar {

  /**
   * Number of days per month in a 13-month calendar.
   *
   * @var int
   */
  private const NUM_DAYS_IN_MONTH = 28;

  /**
   * Number of days in a non leap year.
   *
   * @var int
   */
  private const NUM_DAYS_IN_YEAR = 365;

  /**
   * Number of days in a leap year.
   *
   * @var int
   */
  private const NUM_DAYS_IN_LEAP_YEAR = 366;

  /**
   * Year min to display.
   *
   * @var int
   */
  private const MIN_YEAR = 1970;

  /**
   * Year max to display.
   *
   * @var int
   */
  private const MAX_YEAR = 2050;

  /**
   * Months of the 13-month calendar.
   *
   * @var array
   */
  private const MONTHS = [
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
    'January',
    'February',
    'Serpentary',
  ];

  /**
   * Days of the 13-month calendar.
   *
   * @var array
   */
  private const DAYS = [
    'Sun',
    'Mon',
    'Tue',
    'Wed',
    'Thu',
    'Fri',
    'Sat',
  ];

  /**
   * Pattern to find the equivalent day of the 12-month calendar.
   *
   * @var string
   */
  private const PATTERN_DAY_OF_YEAR = 'd/m';

  /**
   * Get the min year.
   *
   * @return int
   */
  public function getMinYear(): int {
    return self::MIN_YEAR;
  }

  /**
   * Get the max year.
   *
   * @return int
   */
  public function getMaxYear(): int {
    return self::MAX_YEAR;
  }

  /**
   * Getting the current year.
   *
   * @return int
   */
  public function getCurrentYear(): int {
    return (int) (new \DateTime())->format("Y");
  }

  /**
   * Get the calendar with all 13 months.
   *
   * @param int $year The year to display, if null the current year is returned.
   * @param bool $start Offset for the 12-month calendar to start in january or march.
   *
   * @return Year
   */
  public function getCalendar(int $year = null, bool $start = false): Year {
    if (is_null($year)) {
      $year = $this->getCurrentYear();
    }

    if (!$this->isValidYear($year)) {
      throw new \OutOfRangeException('Not a valid year.', 1);
    }

    $flagDayName = 0;
    $numDaysInMonth = self::NUM_DAYS_IN_MONTH;
    $isLeapYear = $this->isLeapYear($year);
    $maxDaysInYear = $isLeapYear ?
      self::NUM_DAYS_IN_LEAP_YEAR : self::NUM_DAYS_IN_YEAR;
    $nthDayOfYear = $this->getOffset($start, $isLeapYear);
    $today = $year === $this->getCurrentYear() ?
      (new \DateTime())->format(self::PATTERN_DAY_OF_YEAR) : null;

    $months = [];
    foreach (self::MONTHS as $index => $monthName) {
      $monthNum = ++$index;

      if (13 == $monthNum) {
        $numDaysInMonth++;
        if ($isLeapYear) {
          $numDaysInMonth++;
        }
      }

      $days = [];
      for ($day = 1; $day <= $numDaysInMonth; $day++) {
        if ($nthDayOfYear > $maxDaysInYear) {
          $nthDayOfYear = 0;
        }
        if ($flagDayName > 6) {
          $flagDayName = 0;
        }

        $days[] = $this->getCell(
          $year, $monthNum, $day,
          $monthName, self::DAYS[$flagDayName],
          $nthDayOfYear, $today
        );

        $flagDayName++;
        $nthDayOfYear++;
      }

      $months[] = new Month($monthName, $monthNum, $days);
    }

    return new Year($months);
  }

  /**
   * Creates then returns a Cell object.
   *
   * @param int $year
   * @param int $month
   * @param int $day
   * @param string $monthName
   * @param string $dayName
   * @param int $nthDayOfYear
   * @param string|null $today
   * @return Cell
   */
  private function getCell(int $year, int $month, int $day, string $monthName,
    string $dayName, int $nthDayOfYear, string $today = null) {

    if (1 == strlen($month)) {
      $month = '0' . $month;
    }
    if (1 == strlen($day)) {
      $day = '0' . $day;
    }
    if ($day > self::NUM_DAYS_IN_MONTH) {
      $dayName = '---';
    }
    // Find the date by num day of year.
    $dayOfYear = \DateTime::createFromFormat('Y z', strval($year) . ' ' . strval($nthDayOfYear));

    $newDay = new Day($day, $month, $dayName, $monthName);
    $oldDay = new Day($dayOfYear->format('d'), $dayOfYear->format('m'),
      $dayOfYear->format('D'), $dayOfYear->format('M'));

    return new Cell($today == $dayOfYear->format(self::PATTERN_DAY_OF_YEAR), $newDay, $oldDay);
  }

  /**
   * Determines if the year passed as a parameter is a leap year.
   *
   * @param int $year
   * @return bool
   */
  private function isLeapYear(int $year): bool {
    return \DateTime::createFromFormat('Y', $year)->format('L') === '1';
  }

  /**
   * Returns the offset to start the 12-month year.
   * Offset to start in january: 0
   * Offset to start in march: 31 (january) + 28|29 (february)
   *
   * @param bool $start True to add an offset and start in march.
   * @param bool $isLeapYear
   * @return int
   */
  private function getOffset(bool $start = false, bool $isLeapYear = false): int {
    return $start ? (!$isLeapYear ? 59 : 60) : 0;
  }

  /**
   * Determines whether the year is out of range or not.
   *
   * @param int $year
   * @return bool True if in range.
   */
  private function isValidYear(int $year): bool {
    return self::MIN_YEAR <= $year && $year <= self::MAX_YEAR;
  }

}
