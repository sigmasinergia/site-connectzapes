<?php
function generate_string($input, $strength = 16)
{
	$input_length = strlen($input);
	$random_string = '';
	for ($i = 0; $i < $strength; $i++) {
		$random_character = $input[mt_rand(0, $input_length - 1)];
		$random_string .= $random_character;
	}

	return strtoupper($random_string);
}
