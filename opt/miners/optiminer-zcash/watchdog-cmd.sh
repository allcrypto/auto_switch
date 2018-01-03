## This script gets executed by mine.sh when a GPU is stuck on the miner.
## Uncomment any of the following actions to perform. You might need to run
## the miner as root for them to work. 

echo "Uncomment any of the actions in 'watchdog-cmd.sh' to perform on stuck GPU!"

## Reboot:

# /sbin/shutdown -r now

## Reboot using sudo:

# sudo /sbin/shutdown -r now

## Hard reset: (Requires 'Magic SysRq key (CONFIG_MAGIC_SYSRQ)' enabled in kernel.)

# echo 1 > /proc/sys/kernel/sysrq
# echo u > /proc/sysrq-trigger 
# sleep 1
# echo b > /proc/sysrq-trigger