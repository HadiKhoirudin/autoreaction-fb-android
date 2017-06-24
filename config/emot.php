<?php
// like = 1, love = 2, wow = 3
// haha = 4, sad = 7, angry = 8

$random_female = rand(1, 4);
$r_female	= $random_female;

$random_male = rand(1, 4);
if ($random_male =='2')
{
$r_male = '1';
}
else
{
$r_male = $random_male;
}