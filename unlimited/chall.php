<?php

ini_set ('memory_limit', '8G');

// λs.λz. z
$zero = function ($s) {
    return function ($z) {
        return $z;
    };
};

// λs.λz. s (s z)
$one = function ($s) {
    return function ($z) use ($s) {
        return $s($z);
    };
};

// λs.λz. s (s (s z))
$two = function ($s) {
    return function ($z) use ($s) {
        return $s($s($z));
    };
};

// λs.λz. s (s (s (s z)))
$three = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($z)));
    };
};

// λs.λz. s (s (s (s (s z))))
$four = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($z))));
    };
};

// λs.λz. s (s (s (s (s (s z)))))
$five = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($s($z)))));
    };
};

// λs.λz. s (s (s (s (s (s (s z))))))
$six = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($s($s($z))))));
    };
};

// λs.λz. s (s (s (s (s (s (s (s z)))))))
$seven = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($s($s($s($z)))))));
    };
};

// λs.λz. s (s (s (s (s (s (s (s (s z))))))))
$eight = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($s($s($s($s($z))))))));
    };
};

// λs.λz. s (s (s (s (s (s (s (s (s (s z)))))))))
$nine = function ($s) {
    return function ($z) use ($s) {
        return $s($s($s($s($s($s($s($s($s($z)))))))));
    };
};

// Defining the successor of a number according to the orders
// It combines a numeral $n and returns another church numeral. Yields a
// function (s)(z) -> s (... z ...) * $n
// We can define as:
// λn.λs.λz. s (n s z)
$succ = function ($n) {
    return function ($s) use ($n) {
        return function ($z) use ($s, $n) {
            // $sub_call = $n($s);
            // return $s($sub_call($z));
            return $s($n($s)($z));
        };
    };
};

// Arithmetic operations start here
// (+) is just $succ applied $x times to a church encoded number $n
// λx.λy.λs.λz. x s (y s z)
$add = function ($x) {
    return function ($y) use ($x) {
        return function ($s) use ($x, $y) {
            return function ($z) use ($s, $x, $y) {
                // $subcall_1 = $y($s);
                // $subcall_2 = $x($s);
                // return $subcall_2($subcall_1($z));
                return $x($s)($y($s)($z));
            };
        };
    };
};

// We can define multiplication (*) as repeated applications of plus.
// λx. λy. x (plus y) zero
$mul = function ($x) use ($add, $zero) {
    return function ($y) use ($x, $add, $zero) {
        // $call = $x($plus($y));
        // return $call($zero);
        return $x($add($y))($zero);
    };
};

$fn_inc = function ($n) {
    return $n + 1;
};

$fn_dec = function ($n) {
    return $n - 1;
};

// 1 3 2 6 4 5
$fn_mul3_mod7 = function ($n) {
    return $n * 3 % 7;
};

$fn_inc_mod1000000007 = function ($n) {
    return ($n + 1) % 1000000007;
};

$mat_arr = [
    // [$two, $seven, $six, $nine, $five, $one, $four, $three, $eight],
    // [$one, $two, $three, $four, $five, $six, $seven, $eight, $nine],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    // [$four, $three, $eight, $nine, $five, $one, $two, $seven, $six],
    [$add($succ($add($add($one)($add($one)($add($succ($one))($one))))($one)))($one), $add($add($one)($succ($succ($one))))($add($one)($add($succ($one))($add($one)($one)))), $succ($add($add($succ($add($one)($add($add($one)($add($one)($one)))($one))))($one))($one)), $mul($succ($one))($succ($one)), $zero, $mul($succ($one))($add($one)($succ($succ($one)))), $add($succ($one))($add($one)($mul($add($one)($one))($succ($add($one)($one))))), $succ($succ($add($one)($add($one)($one)))), $add($one)($add($one)($one))],[$add($add($one)($add($one)($one)))($add($one)($add($add($one)($add($one)($one)))($one))), $zero, $succ($succ($one)), $add($add($mul($add($succ($one))($one))($add($one)($one)))($one))($one), $zero, $succ($add($add($one)($one))($succ($one))), $add($add($add($add($one)($succ($one)))($add($one)($one)))($one))($add($one)($succ($one))), $add($add($one)($add($one)($one)))($add($add($succ($one))($add($one)($one)))($add($one)($one))), $mul($succ($one))($add($one)($add($one)($succ($one))))],
    [$add($add($one)($one))($one), $succ($one), $zero, $succ($add($mul($succ($one))($add($one)($add($one)($one))))($one)), $add($one)($one), $add($one)($add($succ($add($one)($add($one)($one))))($one)), $zero, $one, $add($one)($add($add($add($one)($succ($one)))($add($one)($add($one)($one))))($succ($one)))],
    [$succ($add($add($succ($add($one)($one)))($succ($add($one)($one))))($add($one)($one))), $add($succ($add($add($one)($one))($one)))($succ($add($one)($one))), $succ($succ($one)), $add($one)($one), $one, $zero, $add($one)($mul($add($one)($one))($add($one)($one))), $add($succ($mul($add($add($one)($one))($one))($add($one)($one))))($add($one)($one)), $add($add($add($one)($add($one)($one)))($add($add($succ($one))($one))($one)))($add($one)($one))],
    [$add($add($add($one)($one))($one))($add($one)($add($add($one)($one))($add($one)($one)))), $add($add($one)($succ($add($one)($succ($one)))))($add($one)($succ($one))), $mul($add($succ($one))($one))($add($succ($one))($one)), $add($one)($add($one)($add($one)($one))), $zero, $add($succ($one))($one), $add($add($one)($succ($succ($one))))($one), $one, $add($one)($add($add($one)($one))($add($one)($one)))],
    [$add($one)($one), $succ($add($succ($one))($mul($add($succ($one))($one))($succ($one)))), $succ($one), $zero, $add($mul($succ($one))($succ($one)))($add($add($one)($one))($add($add($one)($one))($one))), $add($add($one)($one))($succ($add($one)($succ($add($one)($one))))), $add($add($add($succ($one))($one))($one))($one), $zero, $add($succ($succ($add($succ($add($one)($succ($one))))($one))))($one)],
    [$zero, $add($one)($add($one)($one)), $add($succ($add($one)($one)))($mul($succ($one))($add($one)($one))), $add($one)($add($one)($succ($one))), $zero, $add($add($add($add($one)($one))($one))($succ($mul($add($one)($one))($add($one)($one)))))($one), $zero, $add($add($add($succ($one))($succ($add($one)($add($one)($one)))))($one))($one), $succ($one)],
    [$add($one)($one), $add($one)($add($one)($add($succ($add($one)($succ($one))))($add($one)($one)))), $one, $add($succ($one))($one), $add($one)($add($one)($one)), $mul($succ($one))($add($one)($add($add($one)($one))($one))), $zero, $add($add($add($one)($add($one)($one)))($add($one)($one)))($add($one)($one)), $mul($succ($one))($add($one)($add($one)($add($one)($one))))],
];

for ($i = 0; $i < 8; ++$i) {
    for ($j = 0; $j < 9; ++$j) {
        echo $mat_arr[$i][$j]($fn_inc)(0);
        echo " ";
    }
    echo "\n";
}

$mat_a = $mat_arr[0];
for ($i = $zero; $i($fn_inc)(1337) < $i($fn_dec)(1345); $i = $succ($i)) {
    // var_dump([$i($fn_inc)(0), $i($fn_mul3_mod7)(1)]);
    $mat_b = $mat_arr[$i($fn_mul3_mod7)(1)];
    // $mat_a = [
    //     $add($mul($mat_a[0])($mat_b[0]))($add($mul($mat_a[1])($mat_b[3]))($mul($mat_a[2])($mat_b[6]))),
    //     $add($mul($mat_a[0])($mat_b[1]))($add($mul($mat_a[1])($mat_b[4]))($mul($mat_a[2])($mat_b[7]))),
    //     $add($mul($mat_a[0])($mat_b[2]))($add($mul($mat_a[1])($mat_b[5]))($mul($mat_a[2])($mat_b[8]))),
    //     $add($mul($mat_a[3])($mat_b[0]))($add($mul($mat_a[4])($mat_b[3]))($mul($mat_a[5])($mat_b[6]))),
    //     $add($mul($mat_a[3])($mat_b[1]))($add($mul($mat_a[4])($mat_b[4]))($mul($mat_a[5])($mat_b[7]))),
    //     $add($mul($mat_a[3])($mat_b[2]))($add($mul($mat_a[4])($mat_b[5]))($mul($mat_a[5])($mat_b[8]))),
    //     $add($mul($mat_a[6])($mat_b[0]))($add($mul($mat_a[7])($mat_b[3]))($mul($mat_a[8])($mat_b[6]))),
    //     $add($mul($mat_a[6])($mat_b[1]))($add($mul($mat_a[7])($mat_b[4]))($mul($mat_a[8])($mat_b[7]))),
    //     $add($mul($mat_a[6])($mat_b[2]))($add($mul($mat_a[7])($mat_b[5]))($mul($mat_a[8])($mat_b[8]))),
    // ];
    $mat_c = $mat_arr[$mul($i)($i)($fn_mul3_mod7)(1)];
    $mat_c[0] = $mul($mat_a[0])($mat_b[0]);
    $mat_c[0] = $add($mul($mat_b[3])($mat_a[1]))($mat_c[0]);
    $mat_c[8] = $mul($mat_a[8])($mat_b[8]);
    $mat_c[8] = $add($mat_c[8])($mul($mat_a[6])($mat_b[2]));
    $mat_c[7] = $mul($mat_a[7])($mat_b[4]);
    $mat_c[7] = $add($mul($mat_b[1])($mat_a[6]))($mat_c[7]);
    $mat_c[1] = $mul($mat_a[1])($mat_b[4]);
    $mat_c[2] = $mul($mat_a[1])($mat_b[5]);
    $mat_c[8] = $add($mul($mat_a[7])($mat_b[5]))($mat_c[8]);
    $mat_c[4] = $mul($mat_a[4])($mat_b[4]);
    $mat_c[7] = $add($mul($mat_a[8])($mat_b[7]))($mat_c[7]);
    $mat_c[1] = $add($mul($mat_b[1])($mat_a[0]))($mat_c[1]);
    $mat_c[1] = $add($mat_c[1])($mul($mat_b[7])($mat_a[2]));
    $mat_c[5] = $mul($mat_a[3])($mat_b[2]);
    $mat_c[5] = $add($mul($mat_b[5])($mat_a[4]))($mat_c[5]);
    $mat_c[3] = $mul($mat_a[4])($mat_b[3]);
    $mat_c[0] = $add($mul($mat_a[2])($mat_b[6]))($mat_c[0]);
    $mat_c[5] = $add($mul($mat_b[8])($mat_a[5]))($mat_c[5]);
    $mat_c[3] = $add($mat_c[3])($mul($mat_b[0])($mat_a[3]));
    $mat_c[2] = $add($mul($mat_a[0])($mat_b[2]))($mat_c[2]);
    $mat_c[2] = $add($mul($mat_a[2])($mat_b[8]))($mat_c[2]);
    $mat_c[4] = $add($mul($mat_a[5])($mat_b[7]))($mat_c[4]);
    $mat_c[4] = $add($mul($mat_b[1])($mat_a[3]))($mat_c[4]);
    $mat_c[6] = $mul($mat_a[7])($mat_b[3]);
    $mat_c[3] = $add($mat_c[3])($mul($mat_a[5])($mat_b[6]));
    $mat_c[6] = $add($mul($mat_b[0])($mat_a[6]))($mat_c[6]);
    $mat_c[6] = $add($mat_c[6])($mul($mat_a[8])($mat_b[6]));
    $mat_a = $mat_c;
}

for ($i = 0; $i < 3; ++$i) {
    for ($j = 0; $j < 3; ++$j) {
        echo $mat_a[$i * 3 + $j]($fn_inc)(0);
        echo " ";
    }
    echo "\n";
}

echo "flag{";
for ($i = $one; $i($fn_inc)(-9); $i = $add($one)($i)) {
    echo "{$mat_a[$i($fn_dec)(9)]($fn_inc_mod1000000007)(0)}-";
}
echo "{$mat_a[$i($fn_dec)(9)]($fn_inc_mod1000000007)(0)}}\n";

