<?php

namespace cal13m\App\model;

class Year {

  /**
   * @var Month[] $months
   */
  protected $months;

  /**
   * Constructor
   *
   * @param Month[] $months
   */
  public function __construct(array $months) {
    $this->months = $months;
  }

  /**
   * Getter months
   *
   * @return Month[]
   */
  public function getMonths(): array {
    return $this->months;
  }

  /**
   * Setter currentDay
   *
   * @param Month[] $months
   * @return Year
   */
  public function setMonths($months): Year {
    $this->months = $months;
    return $this;
  }

}
