#!/usr/bin/php
<?php
include '../public_html/epass_home/hash.php';

$HASH_salt='random_salt_valu';

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
function randomString($size) {
    $max_size = 10 + ($size % 10);
    $alpha = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle(str_repeat($alpha, mt_rand(1,$max_size))),1,$max_size);
}

$answer = array(
  0 => "T0lXNHJLNlVrUzI=",
  1 => "Xip6dXJoeEAjYGQ+Y199",
  2 => "bS04JSdeSXlPbTtsRiB7VX4=",
  3 => "SGdiZlxId3FIP3EgTA==",
  4 => "NCU6fWEwczYpN24tcnE=",
  5 => "VnBJMEdHUlBZNXxYIWJnPFk=",
  6 => "LSg2JTtwMThwIA==",
  7 => "XlclMzF7IjgxIg==",
  8 => "Pys5azEwN3U1K14qIV0=",
  9 => "ZVprSUJFZWBdLHpP",
  10 => "KWI8Im8tcXcla216dShrOms=",
  11 => "MVluQHdAWDcsPw==",
  12 => "MjE2ODE3NDY5OTc2Nw==",
  13 => "SkJ7K1dZfUwvczE5Nw==",
  14 => "bG9TOSUtMk5yXyU4Tnla",
  15 => "UTZ5N1toM21KTGQgJUE0Wg==",
  16 => "b0BQa156RitTXC1Uag==",
  17 => "KUVgUW8gc30lRFlAX2pxQyxVdw==",
  18 => "U0xmeTU5MDJmbGIxTEp6eVFVNTc=",
  19 => "MVVAKHN4WGI/bEQgeWxQfA==",
  20 => "O0FfQTY2KjIwO3g=",
  21 => "el54PUEsT3JQKXxX",
  22 => "JDtrN1pddXtILw==",
  23 => "MV06KyhfXDBpaV8=",
  24 => "S0FxTkZia3F3bjBmczFNcGM5",
  25 => "dGhuTXdzNzlZazk5WFp6TFpw",
  26 => "cmREXWdDImdZPmcwYCg1TCVxLTQ=",
  27 => "IXBPbWJJI2xNeGNMbFlcOyJq",
  28 => "SEVzd2VQbWd0YkhSRG9HTg==",
  29 => "KSMnLU0nQX1CTlA=",
  30 => "V1lBYzYvOi5FXnA=",
  31 => "M3xrfD8xbzluMXt3",
  32 => "OSorLiAhLTM+KSkiKA==",
  33 => "eyMzZTpNKikkL248QVZjTlQ3Nnw=",
  34 => "MGIjfmhCLyk1ICBCKTcxR29sWQ==",
  35 => "JD47akpRMkc+NDU4bChHTGAzYQ==",
  36 => "KXw1Nk9RKH02Ri5gTzk2O0Jt",
  37 => "IyZHRFpsTmNDY3pc",
  38 => "VDhuNWV+XH42c0VJM3tqQ35N",
  39 => "NFBddGI1Slg3PG5OdWA5eyJw",
  40 => "NS5jO28hNDBeQ1xPeTc=",
  41 => "aFBiT0RaTUhOekJoWHNTbUVtSg==",
  42 => "SkJYWEtLTkxZREU=",
  43 => "MndveGVyZWw2YWpkMXpzcmg=",
  44 => "OTU2RiEzUjc2KT1yeUxEUw==",
  45 => "fl5fbmgsaG15KG1ELmpCW1Mj",
  46 => "Q1h9PjY4SCd9fEAqW3xVPDZCfV0=",
  47 => "aDwxd1JZP3MhXkA3TTgzdE1oWTg=",
  48 => "LGt6R2BDJVNtc2NoaSpxZjo=",
  49 => "R0NWeVZzb3hkaEI0ODU1UnFpTw==",
  50 => "UExlZyt0aEt+JmZIaEM=",
  51 => "NDEyMTI2NTMxNTIwOQ==",
  52 => "WVYkcDNFS3M/Nm8=",
  53 => "SVBGQlhQV1pMSFFDVVhaVQ==",
  54 => "cm9eXz4zLD5jZA==",
  55 => "VzNYW0BHL30qNUFCPjEqWn5ZRQ==",
  56 => "dTtpMWJrXnM1Pis=",
  57 => "c2VtKD9XfW5LbQ==",
  58 => "NWZzeWV4OHA5dTVraHEzMGI=",
  59 => "UWdGUFpXZUhlV2dzZQ==",
  60 => "bz5hUnN7YTkjXGkjempVIV1qUlY=",
  61 => "ZWNwMThPOEhQTzA1YWxLVg==",
  62 => "Qlp1c3F5QElhKWckSnhpUWpuRw==",
  63 => "MXduWHw2QTRiSGo3",
  64 => "e3g5PTA3SlBEKl5l",
  65 => "VUUrVTc1OTJaIGxlIw==",
  66 => "fDlCNW9hNShRITVI",
  67 => "L3ZVWUwwNDt1SDhnc0o2aA==",
  68 => "OyogVkVFQl5BT11g",
  69 => "MmVGOyNAYUd6TTA4Nl9Bc3Q=",
  70 => "PzZHekMxPiJQMC9jbVpUdncx",
  71 => "MSgzd1chNUBxXmxaLThhQkM=",
  72 => "aDQ/YGNSJTZgNnBofDBJRWRTKw==",
  73 => "VWQ4VTNzWEpkZnVNWEJkZTZMM24=",
  74 => "PT80QX4oWndPSXAu",
  75 => "MVF7KHJeem8wMnJMVF1oVEM=",
  76 => "cHhMNXVaLSpIST5KK1lgRmIs",
  77 => "NWVQbHYxM05LOUpHR1Js",
  78 => "O2ApNS8gfiBbLl5AMDc5fA==",
  79 => "eGs3OGowaHJyZDY2Zg==",
  80 => "MTI2azoxVl16P2hH",
  81 => "VnYtfUo0VjFTODllbg==",
  82 => "UXZ3UlVxcFhHUE9Jd1ZGaXBCTHc=",
  83 => "figgXFwqJDwpfCYvJF0gOyg=",
  84 => "N1JbTj0mQTE5QUgnRDg=",
  85 => "bDNhd2Q1MjFueDltMmNxbDh1",
  86 => "fDBtZDcyOWg+c3wvdSo=",
  87 => "UyRfTUhSVDlMeml5KVxrQlgkfjw=",
  88 => "Q1k4KTNHcWQ3JF05c2FtWg==",
  89 => "QyFCX1d9SyZoMzhz",
  90 => "N0JINldkTy53cnQ4Lg==",
  91 => "PE5vTSs7dkpfXl9jJEZp",
  92 => "WXYkcDNlL2VfNjc=",
  93 => "elZjMCw5Wk5PSA==",
  94 => "ei5teHM5Y1k4Q1on",
  95 => "ezpGXUQ7KzQ0Kzk4",
  96 => "aDNROHJzODFFUnh3",
  97 => "LHEsZGw0XVo4Jn5GMXR9Y1M0",
  98 => "NlBxNWQgcFVgSDlnJkEr",
  99 => "VDM2XnNnaGM/UDlnXGtkSDp7Mw=="
);

$index = 0;
mt_srand(13);
for ($index=0;$index<100;$index++) {
    $upper=mt_rand(0,4);
    $lower=mt_rand(0,4);
    $number=mt_rand(0,4);
    $special=mt_rand(0,4);
    $size=mt_rand(10,20);
    $version=mt_rand(0,100);
    $str = "joe" . "test.com" . "abcd" . $version;
    $hash = bcrypt($str);
    $res = doHashRaw(True,$upper,$lower,$number,$special,$size,$hash);
    $res = base64_encode($res);
    echo "  " . $index . ' => "' . $res . '",' . "\n";
    assert($answer[$index] == $res);
}

for ($hcount=0;$hcount<10;$hcount++) {
    $user_str = randomString($hcount);
    $hash  = bcrypt($user_str);
    echo "" . $hcount . "/10 : " . $user_str . "\n";
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
echo "Done.";
?>
