<?php

$succ = function ($n) {
    return function ($s) use ($n) {
        return function ($z) use ($s, $n) {
            return $s($n($s)($z));
        };
    };
};

$fn_inc = function ($n) {
    return $n + 1;
};

$add = function ($x) {
    return function ($y) use ($x) {
        return function ($s) use ($x, $y) {
            return function ($z) use ($s, $x, $y) {
                return $x($s)($y($s)($z));
            };
        };
    };
};

$fn_dec = function ($n) {
    return $n - 1;
};

$zero = function ($s) {
    return function ($z) {
        return $z;
    };
};

$fn_mul3_mod7 = function ($n) {
    return $n * 3 % 7;
};

$mul = function ($x) use ($add, $zero) {
    return function ($y) use ($x, $add, $zero) {
        return $x($add($y))($zero);
    };
};

$one = function ($s) {
    return function ($z) use ($s) {
        return $s($z);
    };
};

$fn_inc_mod1000000007 = function ($n) {
    return ($n + 1) % 1000000007;
};

$mat_arr = [
    [$add($succ($add($add($one)($add($one)($add($succ($one))($one))))($one)))($one), $add($add($one)($succ($succ($one))))($add($one)($add($succ($one))($add($one)($one)))), $succ($add($add($succ($add($one)($add($add($one)($add($one)($one)))($one))))($one))($one)), $mul($succ($one))($succ($one)), $zero, $mul($succ($one))($add($one)($succ($succ($one)))), $add($succ($one))($add($one)($mul($add($one)($one))($succ($add($one)($one))))), $succ($succ($add($one)($add($one)($one)))), $add($one)($add($one)($one))],[$add($add($one)($add($one)($one)))($add($one)($add($add($one)($add($one)($one)))($one))), $zero, $succ($succ($one)), $add($add($mul($add($succ($one))($one))($add($one)($one)))($one))($one), $zero, $succ($add($add($one)($one))($succ($one))), $add($add($add($add($one)($succ($one)))($add($one)($one)))($one))($add($one)($succ($one))), $add($add($one)($add($one)($one)))($add($add($succ($one))($add($one)($one)))($add($one)($one))), $mul($succ($one))($add($one)($add($one)($succ($one))))],
    [$add($add($one)($one))($one), $succ($one), $zero, $succ($add($mul($succ($one))($add($one)($add($one)($one))))($one)), $add($one)($one), $add($one)($add($succ($add($one)($add($one)($one))))($one)), $zero, $one, $add($one)($add($add($add($one)($succ($one)))($add($one)($add($one)($one))))($succ($one)))],
    [$succ($add($add($succ($add($one)($one)))($succ($add($one)($one))))($add($one)($one))), $add($succ($add($add($one)($one))($one)))($succ($add($one)($one))), $succ($succ($one)), $add($one)($one), $one, $zero, $add($one)($mul($add($one)($one))($add($one)($one))), $add($succ($mul($add($add($one)($one))($one))($add($one)($one))))($add($one)($one)), $add($add($add($one)($add($one)($one)))($add($add($succ($one))($one))($one)))($add($one)($one))],
    [$add($add($add($one)($one))($one))($add($one)($add($add($one)($one))($add($one)($one)))), $add($add($one)($succ($add($one)($succ($one)))))($add($one)($succ($one))), $mul($add($succ($one))($one))($add($succ($one))($one)), $add($one)($add($one)($add($one)($one))), $zero, $add($succ($one))($one), $add($add($one)($succ($succ($one))))($one), $one, $add($one)($add($add($one)($one))($add($one)($one)))],
    [$add($one)($one), $succ($add($succ($one))($mul($add($succ($one))($one))($succ($one)))), $succ($one), $zero, $add($mul($succ($one))($succ($one)))($add($add($one)($one))($add($add($one)($one))($one))), $add($add($one)($one))($succ($add($one)($succ($add($one)($one))))), $add($add($add($succ($one))($one))($one))($one), $zero, $add($succ($succ($add($succ($add($one)($succ($one))))($one))))($one)],
    [$zero, $add($one)($add($one)($one)), $add($succ($add($one)($one)))($mul($succ($one))($add($one)($one))), $add($one)($add($one)($succ($one))), $zero, $add($add($add($add($one)($one))($one))($succ($mul($add($one)($one))($add($one)($one)))))($one), $zero, $add($add($add($succ($one))($succ($add($one)($add($one)($one)))))($one))($one), $succ($one)],
    [$add($one)($one), $add($one)($add($one)($add($succ($add($one)($succ($one))))($add($one)($one)))), $one, $add($succ($one))($one), $add($one)($add($one)($one)), $mul($succ($one))($add($one)($add($add($one)($one))($one))), $zero, $add($add($add($one)($add($one)($one)))($add($one)($one)))($add($one)($one)), $mul($succ($one))($add($one)($add($one)($add($one)($one))))],
];

$mat_a = $mat_arr[0];
for ($i = $zero; $i($fn_inc)(998244353) < $i($fn_dec)(6755399441055744); $i = $succ($i)) {
    $mat_b = $mat_arr[$i($fn_mul3_mod7)(1)];
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

echo "flag{";
for ($i = $one; $i($fn_inc)(-9); $i = $add($one)($i)) {
    echo "{$mat_a[$i($fn_dec)(9)]($fn_inc_mod1000000007)(0)}-";
}
echo "{$mat_a[$i($fn_dec)(9)]($fn_inc_mod1000000007)(0)}}\n";
