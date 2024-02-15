<?php

namespace cal13m\App\model;

class Month {

  /**
   * @var string $monthName
   */
  protected $monthName;

  /**
   * @var int $monthNum
   */
  protected $monthNum;

  /**
   * @var Days[] $days
   */
  protected $days;

  /**
   * Constructor
   *
   * @param string $monthName
   * @param int $monthNum
   * @param Day[] $days
   */
  public function __construct(string $monthName, int $monthNum, array $days) {
    $this->monthName = $monthName;
    $this->monthNum = $monthNum;
    $this->days = $days;
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
   * @param string $monthName
   * @return Month
   */
  public function setMonthName(string $monthName): Month {
    $this->monthName = $monthName;
    return $this;
  }

  /**
   * Getter monthNum
   *
   * @return int
   */
  public function getMonthNum(): int {
    return $this->monthNum;
  }

  /**
   * Setter monthNum
   *
   * @param int $monthNum
   * @return Month
   */
  public function setMonthNum(int $monthNum): Month {
    $this->monthNum = $monthNum;
    return $this;
  }

  /**
   * Getter days
   *
   * @return Day[]
   */
  public function getDays(): array {
    return $this->days;
  }

  /**
   * Setter days
   *
   * @param Day[] $days
   * @return Month
   */
  public function setDays(array $days): Month {
    $this->days = $days;
    return $this;
  }

}
