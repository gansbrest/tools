<?php

// Examples
//
// showGroupedStats($nginxLogPath);
//
// showDetailedStats($nginxLogPath, '213.138.68.93');

$nginxLogPath = '';
$apacheLogPath = '';


function showGroupedStats($logFilepath, $linesNumToProcess = 0) {
  $logLinesArr = file($logFilepath);

  $i = 0;
  foreach($logLinesArr as $lineStr) {

    if (!empty($linesNumToProcess) && $i > $linesNumToProcess) break;

    preg_match('|(\d+\.\d+\.\d+\.\d+)|is', $lineStr, $pock);
    $ipLines[$pock[1]] += 1;
    $i++;
  }

  arsort($ipLines);
}

function showDetailedStats($logFilepath, $ip, $linesToShow = 0) {
  $logLinesArr = file($logFilepath);

  $i = 1;
  foreach($logLinesArr as $lineStr) {

    preg_match('|(\d+\.\d+\.\d+\.\d+)|is', $lineStr, $pock);

    if ($pock[1] == $ip) {

      if (!empty($linesToShow) && $i > $linesToShow) break;

      $ipLines[$pock[1]][] = $lineStr;

      $i++;
    }
  }
}

?>
