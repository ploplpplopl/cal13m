<?php

namespace cal13m\App\model;

class Cell {

  /**
   * @var bool $currentDay
   */
  protected $currentDay;

  /**
   * @var Day $new
   */
  protected $new;

  /**
   * @var Day $old
   */
  protected $old;

  /**
   * Constructor
   *
   * @param bool $currentDay
   * @param Day $new
   * @param Day $old
   */
  public function __construct(bool $currentDay, Day $new, Day $old) {
    $this->currentDay = $currentDay;
    $this->new = $new;
    $this->old = $old;
  }

  /**
   * Getter currentDay
   *
   * @return bool
   */
  public function getCurrentDay(): bool {
    return $this->currentDay;
  }

  /**
   * Setter currentDay
   *
   * @param bool $currentDay
   * @return Cell
   */
  public function setCurrentDay(bool $currentDay): Cell {
    $this->currentDay = $currentDay;
    return $this;
  }

  /**
   * Getter new
   *
   * @return Day
   */
  public function getNew(): Day {
    return $this->new;
  }

  /**
   * Setter new
   *
   * @param Day $new
   * @return Cell
   */
  public function setNew(Day $new): Cell {
    $this->new = $new;
    return $this;
  }

  /**
   * Getter old
   *
   * @return Day
   */
  public function getOld(): Day {
    return $this->old;
  }

  /**
   * Setter old
   *
   * @param Day $old
   * @return Cell
   */
  public function setOld(Day $old): Cell {
    $this->old = $old;
    return $this;
  }

}
