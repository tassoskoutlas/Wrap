<?php
/**
 * Main code file for wrap function.
 */

/**
 * Returns the first character in a string.
 *
 * @param string $string
 *   A user specified string.
 *
 * @return string
 *   The first character of user string.
 */
function get_first_char($string) {
  return mb_substr($string, 0, 1);
}

/**
 * Returns the last character in a string.
 *
 * @param string $string
 *   A user specified string.
 *
 * @return string
 *   The last character of user string.
 */
function get_last_char($string) {
  return mb_substr($string, -1);
}

/**
 * Returns a string wrapped with new line characters length.
 *
 * @param string $string
 *   A string to be wrapped.
 *
 * @param int $length.
 *   The lenght at which each string will wrap.
 *
 * @return string
 *   Returns a wrapped string.
 */
function wrap($string, $length)
{

  // Initialise variables.
  $wrapped = '';
  $break = PHP_EOL;
  $needle = ' ';
  $stringLength = mb_strlen($string);

  // If emtpy return empty.
  if (empty($string)) {
    return $wrapped;
  }

  for ($start = 0; $start < $stringLength;) {

    // Initialise loop variables.
    $end = $length;
    $part = mb_substr($string, $start, $end);
    $partLength = mb_strlen($part);

    // Setup first and last characters of string.
    $firstChar = get_first_char($part);
    $lastChar  = get_last_char($part);

    // Setup total number of spaces and EOL characters.
    $numberOfSpaces = mb_substr_count($part, $needle);
    $numberOfEol    = mb_substr_count($part, PHP_EOL);

    // Setup helpers to determine first and last lines.
    $firstLine = false;
    $lastLine  = false;

    // Flag the first pass on the string.
    if ($start == 0) {
      $firstLine = true;
    }

    // Flag the last pass on the string.
    if ($partLength < $length) {
      $lastLine = true;
    }

    // Trim the last line only of any whitespace.
    if ($lastLine && !$firstLine) {
      $part = ltrim($part);
    }

    // Handle word splitting.

    // Check if it is not the last line, if the last character is not
    // a space and if there are one or more spaces.
    if (!$lastLine && $lastChar !== $needle && $numberOfSpaces > 0) {

      // Get character ahead of current selection.
      $ahead = mb_substr($string, $start + $end, 1);

      // Look at a character ahead and make sure that it is not a
      // space. Then go back find the position of the last space
      // and use it to calculate where to split the sentence so
      // that splitting a word starts in a new sentence. Then
      // use $kappa to update pointers accordingly.
      if ($ahead !== $needle) {
        $kappa = mb_strrpos($part, $needle);
        $part = mb_substr($part, 0, $kappa);
        $end = $kappa + 1;
      }

    }

    // Handle whitespace

    // If first character is a space. Calculate how many there are if
    // more than two re-assign the string and start over.
    if (!$lastLine && !$firstLine && $firstChar === $needle) {

      $new_part = ltrim($part);
      $delta = mb_strlen($part) - mb_strlen($new_part);

      if ($delta > 1) {
        $part = '';
        $end = $delta;
      } else {
        $part = $new_part;
      }

    }

    // If the last character is a space. Then whitespace is split
    // already.
    if (!$lastLine && $lastChar  === $needle) {
      $part = rtrim($part);
    }

    // Handle PHP_EOL

    // Get the first instace of PHP_EOL, calculate its index and get
    // the part from the beggining to the index as the string. Update
    // the pointers so next string just after the index.
    if (!$lastLine && $numberOfEol) {
      $kappa = mb_strpos($part, PHP_EOL);
      $part = mb_substr($part, 0, $kappa);
      $end = $kappa + 1;
    }

    // Update pointers.
    $start += $end;

    // Add string part to wrapped
    if (!empty($part)) {
      $wrapped[] = $part;
    }

  }

  // Return a string with new line characters.
  return implode($wrapped, PHP_EOL);
  
}

/**
 * Showcases the wrap functionality.
 *
 * @param string $string
 *   A string to be wrapped
 */ 
function showcase($string) {

  echo "BEFORE\n";
  echo "------\n";
  echo $string;
  echo "\n";

  for ($i = 10; $i < 100; $i += 10) {

    $wrapped = wrap($string, $i);

    echo "\n";
    echo "Wrap at: " . $i . "\n";
    echo "-----\n";
    echo $wrapped;
    echo "\n";
    
  }
}

/**
 * Sample lorem ipsum string.
 */ 
$string = "Alice was beginning to get very tired of sitting by her sister on the bank, and of having nothing to do:\nonce or twice she had peeped into the book her sister was reading, but it had no pictures or conversations in it, 'and what is the use of a book,' thought Alice 'without pictures or conversation?'";

// Run showcase.
showcase($string);

$one = wrap($string, 60);
$two = wrap('anew   test     withh    lots of whitespace', 4);

$wrap1 = wrap("    test", 10);
$wrap2 = wrap('', 5);
$wrap3 = wrap("test\ntest", 4);

echo '1. ' .$wrap1 ."\n";
echo '2. ' .$wrap2 ."\n";
echo '3. ' .$wrap3 ."\n";
//echo '4. ' .$one . "\n";
echo '5. ' .$two . "\n";
