#include <linux/seccomp.h>
#include <seccomp.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/mman.h>
#include <time.h>
#include <unistd.h>

int readline(char *s, int n) {
    int i;
    for (i = 0; i < n; ++i) {
        char c;
        if (read(0, &c, 1) != 1) {
            break;
        }
        if (c == '\n') {
            break;
        }
        s[i] = c;
    }
    return i;
}

int read_int() {
    char buf[8];
    memset(buf, 0, sizeof(buf));
    readline(buf, sizeof(buf) - 1);
    return atoi(buf);
}

void crack() {
    unsigned int size = read_int();
    if (size < 0x10) {
        return;
    }
    if (size > 0x1000) {
        return;
    }
    char *s = (char *)malloc(size);
    readline(s, size);
    if (memcmp(s, "secret", 6) == 0) {
        _exit(0);
    }
    free(s);
}

void gift() {
    static long long flag = 0x1337733113377331;
    if (flag == 0x1337733113377331) {
        flag = 0;
        free(*((void **)free + read_int()));
    } else {
        puts("no gift");
    }
}

int add_seccomp() {
    scmp_filter_ctx ctx;
    ctx = seccomp_init(SCMP_ACT_ALLOW);
    seccomp_rule_add(ctx, SCMP_ACT_KILL, SCMP_SYS(execve), 0);
    seccomp_load(ctx);
    // clear tcache
    int x;
    for (int i = 2; i < 0x10; ++i) {
        for (int j = 0; j < 0x8; ++j) {
            x ^= (long long)malloc(i * 0x10);
        }
    }
    for (int i = 0; i < 0x18; ++i) {
        x ^= (long long)malloc(0x18);
    }
    return x;
}

int main() {
    add_seccomp();
    while (1) {
        if (read_int()) {
            crack();
        } else {
            gift();
        }
    }
    return 0;
}
