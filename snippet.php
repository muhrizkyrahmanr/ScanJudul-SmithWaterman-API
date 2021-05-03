<?php
class Snippet
{
private $str1;
private $str2;
private $html;

public function __construct($str1, $str2)
{
$this->str1 = $str1;
$this->str2 = $str2;

$this->snippet_tool();
}

/**
* Remove terminal punctuation, such as full stops
*/
public function clean_token($token)
{
$token = preg_replace('/\.$/', '', $token);
return $token;
}

public function tokenise_string($str)
{
return preg_split("/[\s]+/", $str);
}

/**
* Strings are split into words, and the resulting arrays are aligned using Smith-Waterman algorithm
* which finds a local alignment of the two strings. Aligning words rather than characters saves
* memory
*/
public function snippet_tool(){

// Weights
$match = 2;
$mismatch = -1;
$deletion = -1;
$insertion = -1;

// Tokenise input strings, and convert to lower case
$X = $this->tokenise_string($this->str1);
$Y = $this->tokenise_string($this->str2);

// Lengths of strings
$m = count($X);
$n = count($Y);

// Create and initialise matrix for dynamic programming
$H = array ();

for ($i = 0; $i <= $m; $i++)
{
$H[$i][0] = 0;
}

for ($j = 0; $j <= $m; $j++)
{
$H[0][$j] = 0;
}

$max_i = 0;
$max_j = 0;
$max_H = 0;

for ($i = 1; $i <= $m; $i++)
{
for ($j = 1; $j <= $n; $j++)
{
$a = $H[$i -1][$j -1];
$s1 = $this->clean_token($X[$i -1]);
$s2 = $this->clean_token($Y[$j -1]);

// Compute score of four possible situations (match, mismatch, deletion, insertion
if (strcasecmp($s1, $s2) == 0)
{
// Strings are identical
$a += $match;
}
else
{
// Strings are different
//$a -= levenshtein($X[$i-1], $Y[$i-1]); // allow approximate string match
$a += $mismatch; // you're either the same or you're not
}

$b = $H[$i -1][$j] + $deletion;
$c = $H[$i][$j -1] + $insertion;
$H[$i][$j] = max(max($a, $b), $c);

if ($H[$i][$j] > $max_H)
{
$max_H = $H[$i][$j];
$max_i = $i;
$max_j = $j;
}
}
}


/**
* Traceback to recover alignment
*/
$alignment = array ();
$value = $H[$max_i][$max_j];
$i = $max_i -1;
$j = $max_j -1;
while (($value != 0) && (($i != 0) && ($j != 0)))
{
$s1 = $this->clean_token($X[$i]);
$s2 = $this->clean_token($Y[$j]);

if ($s2 != '')
{
array_unshift($alignment, array (
'pos' => $i,
'match' => ((strcasecmp($s1, $s2) == 0) ? 1 : 0),
'token' => $X[$i]
));
}

$up = $H[$i -1][$j];
$left = $H[$i][$j -1];
$diag = $H[$i -1][$j -1];

if ($up > $left)
{
if ($up > $diag)
{
$i -= 1;
}
else
{
$i -= 1;
$j -= 1;
}
}
else
{
if ($left > $diag)
{
$j -= 1;
}
else
{
$i -= 1;
$j -= 1;
}
}
}

// Store last token in alignment
$s1 = $this->clean_token($X[$i]);
$s2 = $this->clean_token($Y[$j]);
array_unshift($alignment, array (
'pos' => $i,
'match' => ((strcasecmp($s1, $s2) == 0) ? 1 : 0),
'token' => $X[$i]
));

/**
* HTML snippet showing alignment
* Local alignment
*/
$snippet = '';
$last_pos = -1;
foreach ($alignment as $a)
{
if ($a['pos'] != $last_pos)
{
if ($a['match'] == 1)
{
$snippet .= '<b><font color="#FF0000" >';
}
$snippet .= $a['token'] . ' '; //$Z[$a['pos']] . ' ';
$snippet .= '</font></b>';
}
$last_pos = $a['pos'];
}

/**
* Embed this in haystack string
* Before alignment
*/
$start_pos = $alignment[0]['pos'] - 1;
$prefix_start = max(0, $start_pos -10);
$prefix = '';
while ($start_pos > $prefix_start)
{
$prefix = $X[$start_pos] . ' ' . $prefix;
$start_pos--;
}

if ($start_pos > 0)
{
$prefix = '.' . $prefix;
}

// After alignment
$end_pos = $alignment[count($alignment) - 1]['pos'] + 1;
$suffix_end = min(count($X), $end_pos +10);
$suffix = '';
while ($end_pos < $suffix_end)
{
$suffix .= ' ' . $X[$end_pos];
$end_pos++;
}

if ($end_pos < count($X))
{
$suffix .= '.';
}

$this->html = $prefix . $snippet . $suffix;
}

public function get_html()
{
return $this->html;
}

} // Class
?>
