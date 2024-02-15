<?php

error_reporting(E_ALL);

define('DS', DIRECTORY_SEPARATOR);

spl_autoload_register(function($className) {
  $path = implode(DS, explode('\\', $className));
  $filePath = __DIR__ . DS . '..' . DS . $path . '.php';
  if (is_readable($filePath)) {
    require_once $filePath;
  }
});

use cal13m\App\Calendar;

$calendar13 = new Calendar();
$year = !empty($_GET['year']) ? $_GET['year'] : $calendar13->getCurrentYear();
$start = isset($_GET['start']) && in_array($_GET['start'], ['0', '1']) ? $_GET['start'] : 0;
$displayDays = isset($_GET['dayname']) && in_array($_GET['dayname'], ['0', '1']) ? $_GET['dayname'] : 1;
$displayCal12 = isset($_GET['cal12']) && in_array($_GET['cal12'], ['0', '1']) ? $_GET['cal12'] : 1;

try {
  $calendar = $calendar13->getCalendar((int) $year, (bool) $start);
}
catch(\Exception $e) {
  // todo: manage exception
  header('Location: ' . getLink(['year' => $currentYear]));
}

function getLink($params = null) {
  $parsedUrl = parse_url($_SERVER['REQUEST_URI']);

  if (is_null($params)) {
    return $parsedUrl['path'];
  }

  if (!empty($parsedUrl['query'])) {
    parse_str($parsedUrl['query'], $output);
    $params = array_merge($output, $params);
  }
  return $parsedUrl['path'] . '?' . http_build_query($params);
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>13 Months Calendar - <?php echo $year; ?></title>
  <meta name="author" content="">
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex, nofollow">
  <style>
    html{font-family: monospace, sans-serif; background: #eee; color: #222;}
    a{text-decoration: none; color: #06c;}
    a:hover{color: #60f;}
    h1 .heading{margin-right: 2em;}
    .disabled{color: #888;}
    .calendar{display: flex; flex-wrap: wrap; align-items: stretch; align-content: stretch;}
    .calendar-month{flex: 1;}
    table{width: 100%;margin: 1rem 0;}
    th, td{text-align: left; vertical-align: top; border: 1px solid #888; padding: 0 .2rem;}
    td.month{background: #ddd;}
    td.new{background: #fff;}
    td.old{background: #e9e9e9; color: #444;}
    td.old.current-day{background: #60f; color: #fff;}
    td.new.current-day{background: #06c; color: #fff;}
  </style>
</head>
<body>
  <h1>
    <span class="heading"><a href="<?php echo getLink(); ?>">13 Months Calendar</a></span>
    <?php if ($year - 1 >= $calendar13->getMinYear()): ?>
      <a href="<?php echo getLink(['year' => $year - 1]); ?>">&lt;</a>
    <?php else: ?>
      <span class="disabled">&lt;</span>
    <?php endif; ?>
    <?php echo $year; ?>
    <?php if ($year + 1 <= $calendar13->getMaxYear()): ?>
      <a href="<?php echo getLink(['year' => $year + 1]); ?>">&gt;</a>
    <?php else: ?>
      <span class="disabled">&gt;</span>
    <?php endif; ?>
  </h1>
  <p>The white cells represent the days of the 13-month calendar.<br>
  The grey cells indicate the equivalent of a 12-month year.<br>
  Colored cells indicate the current date.</p>
  <p>Start the 12-month calendar in <a href="<?php echo getLink(['start' => (int) !$start]); ?>"><?php echo ($start ? 'january' : 'march'); ?></a>.</p>
  <p>Show/hide the day of the week: <a href="<?php echo getLink(['dayname' => (int) !$displayDays]); ?>"><?php echo ($displayDays ? 'hide' : 'show'); ?></a>.<br>
    Show/hide the 12-month calendar: <a href="<?php echo getLink(['cal12' => (int) !$displayCal12]); ?>"><?php echo ($displayCal12 ? 'hide' : 'show'); ?></a>.</p>
  <div class="calendar">
    <?php foreach ($calendar->getMonths() as $month): ?>
      <div class="calendar-month">
        <table>
          <tr>
            <th <?php if ($displayCal12): ?>colspan="2" <?php endif; ?>class="month">
              <?php echo $displayCal12 ? $month->getMonthName() : substr($month->getMonthName(), 0, 3); ?>
            </th>
          </tr>
          <?php foreach ($month->getDays() as $day): ?>
            <?php $classCurrentDay = $day->getCurrentDay() ? ' current-day' : ''; ?>
            <tr>
              <td class="new<?php echo $classCurrentDay; ?>">
                <?php echo "{$day->getNew()->getDay()}/{$day->getNew()->getMonth()}"; ?><br>
                <?php if ($displayDays): ?>
                  <?php echo " {$day->getNew()->getDayName()}"; ?>
                <?php endif; ?>
              </td>
              <?php if ($displayCal12): ?>
                <td class="old<?php echo $classCurrentDay; ?>">
                  <?php echo "{$day->getOld()->getDay()}/{$day->getOld()->getMonth()}"; ?><br>
                  <?php if ($displayDays): ?>
                    <?php echo " {$day->getOld()->getDayName()}"; ?>
                  <?php endif; ?>
                </td>
              <?php endif; ?>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>
