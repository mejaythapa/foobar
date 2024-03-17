<?php
/**
 * Iterates through numbers from 1 to 100 and prints "foo" for numbers divisible by 3,
 * "bar" for numbers divisible by 5, and "foobar" for numbers divisible by both 3 and 5.
 *
 * @return void
 */
for ($i = 1; $i <= 100; $i++) {
    $output = '';

    // Check if the number is divisible by 3 and append 'foo' to $output if true
    if ($i % 3 === 0) {
        $output .= 'foo';
    }

    // Check if the number is divisible by 5 and append 'bar' to $output if true
    if ($i % 5 === 0) {
        $output .= 'bar';
    }

    // If $output is empty, print the number; otherwise, print $output
    echo ($output !== '') ? $output : $i;

    // Add a comma and space if it's not the last number
    if ($i < 100) {
        echo ', ';
    }
}

?>