# OptiminerZ/Zcash

GPU miner for Zcash.

## v1.7.0
[Download Linux 64bit](https://github.com/Optiminer/OptiminerZcash/raw/master/optiminer-zcash-1.7.0.tar.gz)

[Download Windows 64bit](https://github.com/Optiminer/OptiminerZcash/raw/master/optiminer-zcash-1.7.0.zip)

**Important: Versions from 1.3.0 need GPU_FORCE_64BIT_PTR=1**

### Recommended Drivers

#### Linux
- fgrlx 15.30.3 for all GCN 1st-3rd gen cards
- amdgpu-pro 16.40.5 for GCN 4th gen cards (RX4\*0)

#### Windows
- Full speed can only be achieved under Catalyst 15.12 drivers! See
  [below](#installing-catalyst-1512-on-windows) for how to install the older
  driver version.
- RX4\*0 cards are not supported by Catalyst 15.12, **I strongly recommend
  to use linux for mining on them!**

### Features

Supports:
- Windows and Linux 64bit only.
- AMD GCN cards only.

Expected speed (stock card):
- R9 Nano: 450 S/s (with powertune +50)
- R9 290X: 311 S/s
- RX 480:  290 S/s

NVIDIA support planned in the future.

The miner contains a 2.5% fee for supporting the developer. All shown hash rates 
are net rate, i.e., with fee deducted. What you see is what YOU get!

## Usage:
Run from the archive root directory:
```
$ ./optiminer-zcash -s eu1-zcash.flypool.org:3333 -u t1Yszagk1jBjdyPfs2GxXx1GWcfn6fdTuFJ.example -p password
```

For a list of all options run with `-h`:
```
$ ./optiminer-zcash -h
```

There are also 'mine.sh' and 'start.bat' scripts for running it under
Windows and Unix. Just edit the pool and user settings before running!

### Secure connection
Since version 1.0.0, the miner supports ZStratum protocol over TLS to
encrypt the connection to the mining pool. Currently, this is only supported
with some pools, e.g., flypool and supernova.

Use `zstratum+tls://` as prefix to the pool address, e.g.,
```
$ ./optiminer-zcash -s zstratum+tls://eu1-zcash.flypool.org:3443 -u t1Yszagk1jBjdyPfs2GxXx1GWcfn6fdTuFJ.example -p password
```

## Troubleshooting

### Intensity
Starting with version 0.5.0, there is an intensity option (-i). Higher
intensity generally means faster hashing. But if it is too high, the miner
might crash or have very poor performance. The miner tries to auto-detect
the best intensity for your card but you can experiment with different
values.

E.g., adding `-i 2` to command line sets intensity to 2 for all cards. If
you have multiple card you can specify one `-i` for each card, e.g., if you
have four cards `-i 3 -i 4 -i 4 -i 3` (same order as `-d`). An intensity value
of 0 means auto-detect.

### `GLIBCXX_3.4.20' not found on Ubuntu 14.04
Install the required libstc++:
```shell
sudo add-apt-repository ppa:ubuntu-toolchain-r/test 
sudo apt-get update
sudo apt-get install libstdc++6
```

### Failed to read bin/base.bin
You need to run the miner from the directroy where optiminer-zcash is in
otherwise it will not find the opencl kernel.

### libOpenCL.so.1 cannot open object
There is a problem with your OpenCL installation. Make sure that there is a
symlink /usr/lib/libOpenCL.so.1 that points to the OpenCL library on your
system.

### [error] OpenCL error: cl::Context::Context() (CL_DEVICE_NOT_FOUND)
Either you have specified a wrong device / platform combination or there is
a problem with your OpenCL setup.

By default platform id 0 is used. You can specify a different platform by
adding '-c N' to the command line where N is a small number (try 0,1,2).

Restarting X might help to re-initialize the graphic driver under Linux.

### Internal error: Link failed
This can happen if you use an unsupported version of the graphic driver.
Try updating to the newest driver or use `--force-generic-kernel` to get a
slower implementation that also runs on older drivers.

### Installing catalyst 15.12 on Windows
- Download and run the [AMD driver
  cleanup](http://support.amd.com/en-us/kb-articles/Pages/AMD-Clean-Uninstall-Utility.aspx)
- Download and install "Download Windows 10 64-bit (Desktop)" from
  [here](http://www.guru3d.com/files-details/amd-radeon-software-crimson-15-12-driver-download.html).
  You need to scroll down to find the download links.
- Reboot. 

## Changelog
- [1.7.0] New --pci-modes (0-3). Try if you see GPU freezes.
- [1.7.0] Reduced CPU utilization.
- [1.7.0] Small performance improvement ~1%.
- [1.6.2] Implement second pci mode (--pci-mode 1).
- [1.6.1] Print warning when running on non-optimal driver/platform.
- [1.6.1] Fix: Don't try to run on non-AMD GPUs.
- [1.6.0] Asm support for GCN 1 devices.
- [1.6.0] Reduced CPU utilization.
- [1.6.0] Fix segfault on reconnect.
- [1.6.0] Version and Os exported in monitoring.
- [1.5.0] Support for more pools.
- [1.5.0] Allow again extranonces up to 28 bytes (fixed mining problems with
  nicehash).
- [1.5.0] 1-2% increase in hash speed for device specific kernels.
- [1.4.0] Experimental asm support for GCN 1 devices. Enable with
  '--experimental-kernel'
- [1.4.0] 1-2% increase in hash speed.
- [1.4.0] Fix race condition that could lead to invalid solutions.
- [1.3.2] Support older versions of fgrlx again.
- [1.3.2] Fix abort when failing to list devices of platform.
- [1.3.2] Fix no reconnect after 'No such host' errors.
- [1.3.2] Add --benchmark options.
- [1.3.1] Automatically select AMD platform when not specified.
- [1.3.1] Print error when 32bit addressing is used.
- [1.3.1] Try to select best kernel for used driver.
- [1.3.1] Add option to list devices (--list-devices).
- [1.3.0] Further device specific optimizations bringing up to 30% increase in hash rate!
- [1.3.0] Fix crahes with optimized kernel under Windows.
- [1.2.0] Add custom optimization for GCN1.1 and GCN1.2 cards (requires fglrx).
- [1.2.0] Fix memory leek and potential race condition.
- [1.1.0] Improved hash rate.
- [1.1.0] Fix potential crashes in stratum code.
- [1.0.1] Change default directory for openssl certificates.
- [1.0.0] Add --nodevfee option.
- [1.0.0] Minor optimizations.
- [0.9.1] Add support for zstratum+tls protocol.
- [0.9.1] Multi-threading issues fixes.
- [0.9.0] Switched to new async I/O communication.
- [0.9.0] Switched to different logging library. Now supports log rotation.
- [0.6.0] 20-30% speed improvements.
- [0.5.0] Add intensity for increased hash rates.
- [0.4.0] Async solution validation and reporting.
- [0.4.0] Added monitoring port (see -m).
- [0.3.4] Add GPU watchdog (--watchdog-timeout and --watchdog-cmd).
- [0.3.4] Fix deadlock in stratum client.
- [0.3.3] Fix VM_CONTEXT1_PROTECTION_FAULT_ADDR.
- [0.3.2] Re-enable file logging though --log-file.
- [0.3.2] Fix bug in extranonce subscription.
- [0.3.2] Improve stratum client stability.
- [0.3.2] Reduced dev fee.
- [0.3.1] Slight improvement on hashing speed on some cards.
- [0.3.1] Turn off writing to log file.
- [0.3.1] Enable thread-safe mode for logging library.
- [0.3.1] Support for extranonce.subscribe for improved compatibility with
  NiceHash
- [0.3.0] New way of distributing kernels.
- [0.2.1] Fix invalid machine instruction error.
- [0.2.0] Filter invalid solutions on GPU.
- [0.1.1] Fix startup crash.
