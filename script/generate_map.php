<?php
/**
 * This is used to generate contents in convert_emoji.php. Aside from it, it has
 * no other use.
 */

// load conversion table
$raw = file_get_contents('Any_SoftbankSMS.txt');
$map = array();
$lines = split(PHP_EOL, $raw);
foreach ($lines as $line) {
  $line = trim(preg_replace('/#.*/', '', $line));
  $line = trim(preg_replace('/;$/', '', $line));
  if (!$line) {
    continue;
  }

  $tokens = preg_split('/ [^\\\\]+/', $line);
  if (count($tokens) != 2) {
    continue;
  }

  $key = str_replace(array('\U', '\u'), '', $tokens[0]);
  $map[$key] = substr($tokens[1], 2);
}
$content = file_get_contents('symbols.txt');
$count = 0;
for ($i = 0; $i < strlen($content); ) {
  $length = 3;
  if (substr($content, $i, 1) == "\xc2") {
    $length = 2;
  } else if (substr($content, $i, 1) == "\xf0") {
    $length = 4;

    // special case regional flag
    if (substr($content, $i, 3) == "\xf0\x9f\x87") {
      $length = 8;
    }
  } else if (substr($content, $i + 1, 3) == "\xe2\x83\xa3") {
    // another special case
    $length = 4;
  }

  $char = substr($content, $i, $length);
  $utf32 = strtoupper(utf8_to_utf32_str($char));

  // special cases
  if (substr($content, $i, 1) == "\xc2") {
    $utf32 = substr($utf32, 4, 4);
  } else if (substr($content, $i + 1, 3) == "\xe2\x83\xa3") {
    $utf32 = substr($utf32, 4, 4) . substr($utf32, 12, 4);
  }

  $zeroes_trimmed = ltrim($utf32, '0');
  $filename = strtolower($zeroes_trimmed);
  if (isset($map[$utf32])) {
    $filename = strtolower($map[$utf32]);
  } else if (isset($map[$zeroes_trimmed])) {
    $filename = strtolower($map[$zeroes_trimmed]);
  }

  /*
  $filename .= '.png';
  if (!file_exists('unicode-emoji-images/' . $filename)) {
    echo 'File not found: #' . $count . ' ' . $utf32 . ' ' . $char . ' '
      . utf8_to_str($char) . ' ' . $filename  . PHP_EOL;
    die();
  }*/

  /*
  echo "\t\t'";
  echo 'symbols/' . $filename;
  echo "'," . PHP_EOL;
  copy('unicode-emoji-images/' . $filename, 'symbols/' . $filename);*/

  /*
  echo "\t\t\"";
  echo utf8_to_str($char);
  echo '",' . PHP_EOL;*/
  echo "'" . urlencode($char) . "': 'symbols/$filename.png'," . PHP_EOL;

  $i += $length;
  $count++;
}

function utf8_to_str($utf8) {
  $result = '';
  for ($i = 0; $i < strlen($utf8); $i++) {
    $result .= sprintf('\x%x', ord(substr($utf8, $i, 1)));
  }
  return $result;
}

function utf8_to_utf32_str($utf8) {
  $utf32 = mb_convert_encoding($utf8, 'utf-32', 'utf-8');
  $result = '';
  for ($i = 0; $i < strlen($utf32); $i++) {
    $result .= sprintf('%02x', ord(substr($utf32, $i, 1)));
  }
  return $result;
}

echo 'Count: ' . $count . PHP_EOL;

