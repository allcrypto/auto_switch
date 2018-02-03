# This script switches to the most profitable coin on whattomine.com

## Installation

Run:
`git clone https://github.com/allcrypto/auto_switch.git`

`sudo su`

`crontab -e`

 Add following line to your crontabs for an hourly coin switch:

`0 * * * * /home/ethos/auto_switch/scripts/main.sh`

## Usage
To enable a new coin you'll have to place the config file in the configs folder.
The script will automatically use the tag in the filename to associate the config for the specific coin.

E.g. poolname-ETH.conf = ETH

poolname-KMD.conf = KMD

### Logs
After every coin switch the miner log is being copied to the /logs/ folder. That way you can more easily track if a config file wasn't working.

There's also a main log of the script itself which is written to scripts/log

#### Donations welcome
Consider sending me a cup of joe if you want to support further development of this script.

BTC: 1By4eLJuRu18iQG2GpazsxzvnAbLSvsNv9

ETH: 0x723929ab2da99BaF5EAC9EEAaF650E0770A9d6C0

BCC: 1QFDDn2XA4GHcUXe7pUHtAPKvUQEVdgxVc
