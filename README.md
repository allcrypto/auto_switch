# This script switches to the most profitable coin on whattomine.com

## Installation

Put the configs and scripts folder in your /home/ethos/ folder

Run:
`sudo su`
`crontab -e`

 Add following line to your crontabs for an hourly coin switch:
`0 * * * * /home/ethos/scripts/main.sh`

## ethOS system changes might be needed for ccminer support!

CCMiner did not come with the default installation and the upgrade to 1.2.6. Many of my config files I provided are using ccminer as the global miner.

I had to manually modify parts of the ethOS system to get additional miners supported ( e.g. ccminer, ewbf-zcash ).

The modified ethos files can probably copied without issues to your own 1.2.6 ethOS system but will most likely not allow ethOS to be upgraded without corrupting the system in one way or another.

Backup of your /opt/ folder before applying the modified files!

This has not been tested on a new system: I don't take responsibility in case the modifications on your system lead to any problems.

### Donations welcome
BTC: 1By4eLJuRu18iQG2GpazsxzvnAbLSvsNv9

ETH: 0x723929ab2da99BaF5EAC9EEAaF650E0770A9d6C0

BCC: 1QFDDn2XA4GHcUXe7pUHtAPKvUQEVdgxVc