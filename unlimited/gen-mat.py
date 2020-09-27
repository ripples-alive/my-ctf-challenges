#!/usr/bin/env python3
# coding:utf-8

import random

d = {
    '$two': ['$succ($one)', '$add($one)($one)'],
    '$three': ['$succ($two)', '$add($one)($two)', '$add($two)($one)'],
    '$four': ['$succ($three)', '$add($one)($three)', '$add($three)($one)', '$add($two)($two)', '$mul($two)($two)'],
    '$five': ['$succ($four)', '$add($one)($four)', '$add($four)($one)', '$add($two)($three)', '$add($three)($two)'],
    '$six': ['$succ($five)', '$add($one)($five)', '$add($five)($one)', '$add($two)($four)', '$add($four)($two)', '$add($three)($three)', '$mul($two)($three)', '$mul($three)($two)'],
    '$seven': ['$succ($six)', '$add($one)($six)', '$add($six)($one)', '$add($two)($five)', '$add($five)($two)', '$add($three)($four)', '$add($four)($three)'],
    '$eight': ['$succ($seven)', '$add($one)($seven)', '$add($seven)($one)', '$add($two)($six)', '$add($six)($two)', '$add($three)($five)', '$add($five)($three)', '$add($four)($four)', '$mul($two)($four)', '$mul($four)($two)'],
    '$nine': ['$succ($eight)', '$add($one)($eight)', '$add($eight)($one)', '$add($two)($seven)', '$add($seven)($two)', '$add($three)($six)', '$add($six)($three)', '$add($four)($five)', '$add($five)($four)', '$mul($three)($three)'],
}

c = ['$zero', '$one', '$two', '$three', '$four', '$five', '$six', '$seven', '$eight', '$nine']

for i in range(8):
    res = []
    for j in range(9):
        r = random.choice(c)
        for k in c[:1:-1]:
            v = d[k]
            while k in r:
                r = r.replace(k, random.choice(v), 1)
        res.append(r)
    print('[{}],'.format(', '.join(res)))
