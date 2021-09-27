#!/usr/bin/env python3
# coding:utf-8
# flag{432734187-186275980-552238391-407500134-680581127-536698178-262495339-821428559-850467550}

import numpy as np
from pwn import *


MAX_V = 1000000007


def mat_exp(x, n):
    if n == 0:
        return np.eye(3, dtype=np.uint64)
    if n == 1:
        return x
    h = mat_exp(x, n // 2)
    if n & 1 == 1:
        return h * h % MAX_V * x % MAX_V
    else:
        return h * h % MAX_V


cnt = (6755399441055744 - 998244353 + 1) // 2

mat_arr = '''
8 9 9 4 0 8 9 5 3
8 0 3 8 0 5 9 9 8
3 2 0 8 2 6 0 1 9
9 7 3 2 1 0 5 9 9
8 8 9 4 0 3 5 1 5
2 9 2 0 9 7 5 0 8
0 3 7 4 0 9 0 8 2
2 8 1 3 3 8 0 7 8
'''
mat_arr = [np.mat(group(3, list(map(int, x.split()))), dtype=np.uint64)
           for x in mat_arr.strip().split('\n')]

mat_a = mat_arr[0]
i = 1
mat_combine = mat_arr[i]
for _ in range(5):
    i = i * 3 % 7
    mat_combine = mat_combine * mat_arr[i] % MAX_V

mat_a = mat_a * mat_exp(mat_combine, cnt // 6) % MAX_V

i = 1
for _ in range(cnt % 6):
    mat_a = mat_a * mat_arr[i] % MAX_V
    i = i * 3 % 7

print('flag{' + '-'.join(map(str, concat(np.asarray(mat_a))[::-1])) + '}')
