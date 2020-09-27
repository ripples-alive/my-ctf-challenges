#!/usr/bin/env python3
# coding:utf-8

import random

d = [
    [(0, 0, 0), (0, 1, 3), (0, 2, 6)],
    [(1, 0, 1), (1, 1, 4), (1, 2, 7)],
    [(2, 0, 2), (2, 1, 5), (2, 2, 8)],
    [(3, 3, 0), (3, 4, 3), (3, 5, 6)],
    [(4, 3, 1), (4, 4, 4), (4, 5, 7)],
    [(5, 3, 2), (5, 4, 5), (5, 5, 8)],
    [(6, 6, 0), (6, 7, 3), (6, 8, 6)],
    [(7, 6, 1), (7, 7, 4), (7, 8, 7)],
    [(8, 6, 2), (8, 7, 5), (8, 8, 8)],
]

for x in d:
    random.shuffle(x)

while d:
    r = random.choice(d)
    v = random.choice(r)

    dst = '$mat_c[{}]'.format(v[0])
    src1 = '$mat_a[{}]'.format(v[1])
    src2 = '$mat_b[{}]'.format(v[2])
    if len(r) == 3:
        if random.randint(0, 1):
            cmd = '{0} = $mul({1})({2});'
        else:
            cmd = '{0} = $mul({1})({2});'
    else:
        if random.randint(0, 1):
            if random.randint(0, 1):
                cmd = '{0} += $add({0})($mul({1})({2}));'
            else:
                cmd = '{0} += $add({0})($mul({2})({1}));'
        else:
            if random.randint(0, 1):
                cmd = '{0} += $add($mul({1})({2}))({0});'
            else:
                cmd = '{0} += $add($mul({2})({1}))({0});'
    cmd = cmd.format(dst, src1, src2)
    print(cmd)

    r.remove(v)
    if not r:
        d.remove(r)
