<?php

namespace cal13m\App\model;

class Day {

  /**
   * @var string $day
   */
  protected $day;

  /**
   * @var string $month
   */
  protected $month;

  /**
   * @var string $dayName
   */
  protected $dayName;

  /**
   * @var string $monthName
   */
  protected $monthName;

  /**
   * Constructor
   *
   * @param string $day
   * @param string $month
   * @param string $dayName
   * @param string $monthName
   */
  public function __construct(string $day, string $month, string $dayName, string $monthName) {
    $this->day = $day;
    $this->month = $month;
    $this->dayName = $dayName;
    $this->monthName = $monthName;
  }

  /**
   * Getter day
   *
   * @return string
   */
  public function getDay(): string {
    return $this->day;
  }

  /**
   * Setter day
   *
   * @param string $day
   * @return Day
   */
  public function setDay(string $day): Day {
    $this->day = $day;
    return $this;
  }

  /**
   * Getter month
   *
   * @return string
   */
  public function getMonth(): string {
    return $this->month;
  }

  /**
   * Setter month
   *
   * @param string $month
   * @return Day
   */
  public function setMonth(string $month): Day {
    $this->month = $month;
    return $this;
  }

  /**
   * Getter dayName
   *
   * @return string
   */
  public function getDayName(): string {
    return $this->dayName;
  }

  /**
   * Setter dayName
   *
   * @param bool $dayName
   * @return Day
   */
  public function setDayName(string $dayName): Day {
    $this->dayName = $dayName;
    return $this;
  }

  /**
   * Getter monthName
   *
   * @return string
   */
  public function getMonthName(): string {
    return $this->monthName;
  }

  /**
   * Setter monthName
   *
   * @param bool $monthName
   * @return Day
   */
  public function setMonthName(string $monthName): Day {
    $this->monthName = $monthName;
    return $this;
  }

}
