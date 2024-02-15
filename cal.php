<?php

define('NUM_DAYS_IN_MONTH', 28);
define('NUM_DAYS_IN_YEAR', 365);
define('NUM_DAYS_IN_LEAP_YEAR', 366);
define('MIN_YEAR', 1970);
define('MAX_YEAR', 2050);
define('EOL', "\n");

$months13 = [
  1 => 'March',
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

$days13 = [
  'Sun',
  'Mon',
  'Tue',
  'Wed',
  'Thu',
  'Fri',
  'Sat',
];

$currentYear = date('Y');

$start = !empty($_GET['start']) ? 1 : 0;
$year = !empty($_GET['year']) ? (int) $_GET['year'] : $currentYear;
if ($year < MIN_YEAR || $year > MAX_YEAR) {
  header('Location: ' . getLink(['year' => $currentYear]));
}

$today = $year == $currentYear ? date('d/m') : null;

$isLeapYear = (bool) date('L', strtotime($year . '/01/01'));
$maxDaysInYear = $isLeapYear ? NUM_DAYS_IN_YEAR : NUM_DAYS_IN_LEAP_YEAR;
$nthDayOfYear = !empty($start) ? (!$isLeapYear ? 59 : 60) : 0; // 31 + 28 or 31 + 29


function getCalendar() {
  global $months13, $days13, $today, $isLeapYear, $nthDayOfYear, $maxDaysInYear, $year;

  $out = [];
  $out[] = '<table>' . EOL;
  $out[] = '<tr>' . EOL;
  foreach ($months13 as $monthNum => $monthName) {
    if ($monthNum == 7) {
      $out[] = '</tr>' . EOL;
      $out[] = '<tr>' . EOL;
    }
    $out[] = '<td>' . EOL;
    $out[] = getMonth($monthName);
    $out[] = '<table>' . EOL;

    $flagDayName = 0;
    $numDays = NUM_DAYS_IN_MONTH;
    if (13 == $monthNum) {
      $numDays++;
      if ($isLeapYear) {
        $numDays++;
      }
    }
    for ($d = 1; $d <= $numDays; $d++) {
      if ($nthDayOfYear > $maxDaysInYear) {
        $nthDayOfYear = 0;
      }
      if ($flagDayName > 6) {
        $flagDayName = 0;
      }
      $out[] = getCell($year, $monthNum, $d, $days13[$flagDayName], $nthDayOfYear, $today);
      $flagDayName++;
      $nthDayOfYear++;
    }

    $out[] = '</table>' . EOL;
    $out[] = '</td>' . EOL;

  }
  $out[] = '</tr>' . EOL;
  $out[] = '</table>' . EOL;

  return implode("\n", $out);
}


function getMonth($name) {
  return sprintf("<h2>%s</h2>\n", $name);
}


function getCell($year, $month, $day, $dayName, $nthDayOfYear, $today = null) {
  if (1 == strlen($month)) {
    $month = '0' . $month;
  }
  if (1 == strlen($day)) {
    $day = '0' . $day;
  }
  if ($day > NUM_DAYS_IN_MONTH) {
    $dayName = '---';
  }
  $doy = DateTime::createFromFormat('Y z', strval($year) . ' ' . strval($nthDayOfYear));
  $currentDayClass = $today == $doy->format('d/m') ? ' current-day' : '';
  return sprintf("<tr><td class=\"new%s\">%s/%s %s</td><td class=\"old%s\">%s</td></tr>",
    $currentDayClass, $day, $month, $dayName, $currentDayClass, $doy->format('d/m D'));
}


function getLink(array $params = null): string {
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
    html{font-family: monospace, sans-serif; background: #eee;}
    h1 span{margin-right: 2em;}
    .disabled{color: #888;}
    td{vertical-align: top; padding: 0 .2rem;}
    td td{border: 1px solid #888;}
    td.new{background: #fff;}
    td.old{background: #e9e9e9;}
    td.old.current-day{background: #444; color: #fff;}
    td.new.current-day{background: #fc0;}
  </style>
</head>
<body>
  <h1>
    <span><a href="<?php echo getLink(); ?>">13 Months Calendar</a></span>
    <?php if ($year - 1 >= MIN_YEAR): ?>
      <a href="<?php echo getLink(['year' => $year - 1]); ?>">&lt;</a>
    <?php else: ?>
      <span class="disabled">&lt;</span>
    <?php endif; ?>
    <?php echo $year; ?>
    <?php if ($year + 1 <= MAX_YEAR): ?>
      <a href="<?php echo getLink(['year' => $year + 1]); ?>">&gt;</a>
    <?php else: ?>
      <span class="disabled">&gt;</span>
    <?php endif; ?>
  </h1>
  <p>Blah blah</p>
  <p>Start the old calendar in <a href="<?php echo getLink(['start' => (int) !$start]); ?>"><?php echo ($start ? 'january' : 'march'); ?></a>.</p>
  <?php echo getCalendar(); ?>
</body>
</html>
