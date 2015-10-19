<?php
/**
 * Main code file for wrap function.
 */

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

  for ($start = 0; $start < $stringLength;) {

    // Initialise loop variables.
    $end = $length;
    $part = mb_substr($string, $start, $end);
    $partLength = mb_strlen($part);
    $skip = false;

    // If the last remaining characters skip rounding word
    // splitting.
    if ($partLength < $length) {
      $skip = true;
    }

    // If splitting a word make sure the splitting starts in a new
    // line.

    // Check if sentence does not end in a space and there are spaces
    // within it and if the skip flag is set to false.
    if (!$skip && mb_substr($part, -1) !== $needle && mb_substr_count($part, $needle) > 0) {

      // A character of ahead of current selection.
      $ahead = mb_substr($string, $start + 1, 1);

      // Look at a character ahead and make sure that it is not a
      // space. Then go back find the position of the last space
      // and use it to calculate where to split the sentence so
      // that splitting a word starts in a new sentence. Then
      // use $kappa to update pointers accordingly.
      if ($ahead != $needle) {
        $kappa = mb_strrpos($part, $needle);
        $part = mb_substr($part, 0, $kappa);
        $end = $kappa + 1;
      }

    }

    // Update pointers.
    $start += $end;

    // Add string part to wrapped
    $wrapped[] = $part;

  }

  // Trim all whitespace from beggining and end of strings.
  for ($i = 0; $i < count($wrapped); $i++) {
    $wrapped[$i] = trim($wrapped[$i]);
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
$string = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean vel mattis lectus, vitae aliquam nibh. Mauris nec malesuada est, vitae ornare tellus. Aenean vitae ex dui. Donec rutrum sapien ut convallis aliquam. Nam tincidunt pretium ante, nec porttitor sem. Praesent nibh velit, maximus vel tincidunt eu, facilisis id massa. Vivamus a enim vitae sem vulputate facilisis id ut nisi. Aliquam erat volutpat.";

// Run showcase.
showcase($string);
