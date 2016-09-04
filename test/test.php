#!/usr/bin/php
<?php
include '../public_html/epass_home/hash.php';
$patch = True;

$upper_map = selectAllowable(1,0,0,0);
$lower_map = selectAllowable(0,1,0,0);
$number_map = selectAllowable(0,0,1,0);
$special_map = selectAllowable(0,0,0,1);

$upper_string = implode("",$upper_map);
$lower_string = implode("",$lower_map);
$number_string = implode("",$number_map);
$special_string = implode("",$special_map);

$upper_dist = array_fill(0,count($upper_map),0);
$lower_dist = array_fill(0,count($lower_map),0);
$number_dist = array_fill(0,count($number_map),0);
$special_dist = array_fill(0,count($special_map),0);

// echo $upper_string . "\n";
// echo $lower_string . "\n";
// echo $number_string . "\n";
// echo $special_string . "\n";

//
// It looks like the last character of the hash is not well distributed ..
//
$last_char = "";
$frst_char = "";
for ($hcount=0;$hcount<100;$hcount++) {
  $hash  = bcrypt($hcount . "random_string" . $hcount);
  $last_char .= substr($hash,-1);
  $frst_char .= substr($hash,0,1);
  echo "hash :" . $hash . "\n";
  for ($upper=0;$upper<5;$upper++) {
    for ($lower=0;$lower<5;$lower++) {
      for ($number=0;$number<5;$number++) {
        for ($special=0;$special<5;$special++) {
          for ($size=0;$size<20;$size++) {
              $res = doHashRaw($patch,$upper,$lower,$number,$special,$size,$hash);
              $osize = $upper + $lower + $number + $special;
              $xsize = max($size,$osize);
              $rsize = strlen($res);
//            echo "(" . $rsize . "," . $xsize . ") " . $res . "\n";
              assert($rsize == $xsize);
              $lower_count = 0;
              $upper_count = 0;
              $number_count = 0;
              $special_count = 0;
              for ($i=0;$i<$rsize;$i++) {
                $char = substr($res,$i,1);
                if (strpos($lower_string,$char) !== False) {
                  $lower_count++;
                  $index = array_search($char,$lower_map);
                  $lower_dist[$index]++;
                } elseif (strpos($upper_string,$char) !== False) {
                  $upper_count++;
                  $index = array_search($char,$upper_map);
                  $upper_dist[$index]++;
                } elseif (strpos($number_string,$char) !== False) {
                  $number_count++;
                  $index = array_search($char,$number_map);
                  $number_dist[$index]++;
                } elseif (strpos($special_string,$char) !== False) {
                  $special_count++;
                  $index = array_search($char,$special_map);
                  $special_dist[$index]++;
                } else {
                  echo "Unfound : ";
                  echo $char . "\n";
                  assert(False);
                }
              }
              if (! assert($rsize == $lower_count + $upper_count + $number_count + $special_count)) {
                echo $res . "\n";
              }
              assert($lower_count >= $lower);
              assert($upper_count >= $upper);
              assert($number_count >= $number);
              assert($special_count >= $special);
          }    
        }
      }
    }
  }
}
echo "last : " . $last_char . "\n";
echo "frst : " . $frst_char . "\n";

$maxp = 0;
$sum = 0;
for ($i=0;$i<count($upper_dist);$i++) {
  $sum += $upper_dist[$i]; 
}
$avg = $sum/count($upper_dist);
for ($i=0;$i<count($upper_dist);$i++) {
  $upper_dist[$i] = abs(intval(100*($upper_dist[$i] - $avg)/$avg));
  $maxp = max($maxp,$upper_dist[$i]);
}

$sum = 0;
for ($i=0;$i<count($lower_dist);$i++) {
  $sum += $lower_dist[$i]; 
}
$avg = $sum/count($lower_dist);
for ($i=0;$i<count($lower_dist);$i++) {
  $lower_dist[$i] = abs(intval(100*($lower_dist[$i] - $avg)/$avg));
  $maxp = max($maxp,$lower_dist[$i]);
}

$sum = 0;
for ($i=0;$i<count($number_dist);$i++) {
  $sum += $number_dist[$i]; 
}
$avg = $sum/count($number_dist);
for ($i=0;$i<count($number_dist);$i++) {
  $number_dist[$i] = abs(intval(100*($number_dist[$i] - $avg)/$avg));
  $maxp = max($maxp,$number_dist[$i]);
}

$sum = 0;
for ($i=0;$i<count($special_dist);$i++) {
  $sum += $special_dist[$i]; 
}
$avg = $sum/count($special_dist);
for ($i=0;$i<count($special_dist);$i++) {
  $special_dist[$i] = abs(intval(100*($special_dist[$i] - $avg)/$avg));
  $maxp = max($maxp,$special_dist[$i]);
}
echo "max:" . $maxp . "\n";
//print_r($upper_dist);
//print_r($lower_dist);
//print_r($number_dist);
//print_r($special_dist);
?>
