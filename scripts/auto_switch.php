<?php
	require('config.php');

	class auto_switch
	{
		public $dust_collect_enabled = TRUE; // Mine for specific time some of the least mined coins instead of only the most profitable
		public $dust_collect_start = 23; // Time Range For Dust Collect ( START )
		public $dust_collect_end = 4; // Time Range For Dust Collect ( END )
		public $wtm_api_url = 'http://whattomine.com/coins.json?utf8=?&adapt_q_280x=0&adapt_q_380=0&adapt_q_fury=0&adapt_q_470=0&adapt_q_480=0&adapt_q_570=0&adapt_q_580=0&adapt_q_vega56=0&adapt_q_vega64=0&adapt_q_750Ti=0&adapt_q_1050Ti=0&adapt_q_10606=0&adapt_q_1070=6&adapt_1070=true&adapt_q_1070Ti=0&adapt_q_1080=13&adapt_q_1080Ti=0&eth=true&factor%5Beth_hr%5D=180.0&factor%5Beth_p%5D=720.0&grof=true&factor%5Bgro_hr%5D=201.0&factor%5Bgro_p%5D=780.0&x11gf=true&factor%5Bx11g_hr%5D=69.0&factor%5Bx11g_p%5D=720.0&cn=true&factor%5Bcn_hr%5D=3780.0&factor%5Bcn_p%5D=600.0&eq=true&factor%5Beq_hr%5D=2580.0&factor%5Beq_p%5D=720.0&lre=true&factor%5Blrev2_hr%5D=213000.0&factor%5Blrev2_p%5D=780.0&ns=true&factor%5Bns_hr%5D=6000.0&factor%5Bns_p%5D=780.0&lbry=true&factor%5Blbry_hr%5D=1620.0&factor%5Blbry_p%5D=720.0&bk2bf=true&factor%5Bbk2b_hr%5D=9600.0&factor%5Bbk2b_p%5D=720.0&bk14=true&factor%5Bbk14_hr%5D=14400.0&factor%5Bbk14_p%5D=750.0&pas=true&factor%5Bpas_hr%5D=5700.0&factor%5Bpas_p%5D=720.0&skh=true&factor%5Bskh_hr%5D=165.0&factor%5Bskh_p%5D=720.0&n5=true&factor%5Bn5_hr%5D=57.0&factor%5Bn5_p%5D=345.0&factor%5Bl2z_hr%5D=420.0&factor%5Bl2z_p%5D=300.0&factor%5Bcost%5D=0.1&sort=Profit&volume=0&revenue=current&factor%5Bexchanges%5D%5B%5D=&factor%5Bexchanges%5D%5B%5D=abucoins&factor%5Bexchanges%5D%5B%5D=bitfinex&factor%5Bexchanges%5D%5B%5D=bittrex&factor%5Bexchanges%5D%5B%5D=bleutrade&factor%5Bexchanges%5D%5B%5D=cryptopia&factor%5Bexchanges%5D%5B%5D=hitbtc&factor%5Bexchanges%5D%5B%5D=poloniex&factor%5Bexchanges%5D%5B%5D=yobit&dataset=Main&commit=Calculate';
		public $coin_timers;
		
		private $home_path = '';
		private $ethos_path = '';
		
		private $coins;
		private $timers;
		
		function __construct()
		{
			// PATH TO SCRIPT
			$this->home_path = dirname(dirname(__FILE__)).'/'; // e.g. /home/ethos/
			$this->ethos_path = '/home/ethos/';
			
			// Change dir to root folder
			chdir($this->home_path);
		}
		
		public function run()
		{
			if($this->auto_switch_disabled())
				return;
			
			$this->load_coins();
			$this->load_timers();
			
			// Coins for specific time set?
			if($this->timers)
			{
				$this->timer_switch();
				return;
			}
			
			$hour = date('H', time());
			// Mine least mined coins ( e.g. between 12 - 16 ) -- Dust Collection
			if($this->dust_collect_enabled && ($hour >= $this->dust_collect_start && $hour < $this->dust_collect_end))
				$this->dust_collect();
			else
				$this->profit_switch();
		}
		
		/// Switch to the most profitable coin
		private function profit_switch()
		{
			$json_coins = file_get_contents($this->wtm_api_url); //');
			$data_coins = json_decode($json_coins, true);
			$profits = FALSE;

			if(isset($data_coins['coins']) && count($data_coins['coins']) > 0)
			{			
				foreach($data_coins['coins'] as $label => $coin)
				{
					if(!isset($this->coins[$coin['tag']]))
						continue; // Skip unsupported coins.
					if($coin['lagging'])
						continue; // Skip lagging coins.
					
					$tag = $coin['tag'];
					$coin_id = $coin['id'];
					
					$profits[$tag] = floatval($coin['profitability']);
				}
			}
			// Output list	
			var_dump($profits);
			
			if($profits && count($profits) > 0)
			{
				// Sort by profit (reverse)
				uasort($profits, array($this, 'cb_float_rsort'));
				$new_coin = key($profits);
				$new_profit = current($profits); 
				
				if($this->switch_coin($new_coin))
					$this->write_log(date('m/d/Y H:i:s')." - Switched to $new_coin ($new_profit)\r\n");
			}
		}
		
		private function dust_collect()
		{
			$current_coin = file_get_contents($this->home_path.'scripts/current_coin.txt');
			unset($this->coins[$current_coin]); // Remove the current active coin from list
			
			////////
			//// Get the least mined coin
			
			$coin_distance = array();
			$log = file($this->home_path.'scripts/log');
			$log = array_reverse($log); // Sort: Newest Log To Oldest
			
			foreach($this->coins as $COIN => $COIN_VALUE)
			{
				$coin_distance[$COIN] = 10000; // Unmined coins start the furthest away
				
				// Find distance since last mine
				for($i = 0; $i<count($log); $i++)
				{
					if(strpos($log[$i], ' '.$COIN.' '))
					{
						$coin_distance[$COIN] = $i; // Found distance for coin
						break;
					}
				}
			}
			uasort($coin_distance, array($this, 'cb_float_rsort')); // Sort by distance
			
			$new_coin = key($coin_distance);
			if($this->switch_coin($new_coin))
				$this->write_log(date('m/d/Y H:i:s')." - Switched to $new_coin (Dust Collect)\r\n");
		}
		
		private function timer_switch()
		{
			$current_coin = file_get_contents($this->home_path.'scripts/current_coin.txt');
			unset($this->timers[$current_coin]); // Remove the current active coin from list
			
			////////
			//// Get the least mined coin
			
			$coin_distance = array();
			$log = file($this->home_path.'scripts/log');
			$log = array_reverse($log); // Sort: Newest Log To Oldest
			
			foreach($this->timers as $COIN => $COIN_VALUE)
			{
				$coin_distance[$COIN] = 10000; // Unmined coins start the furthest away
				
				// Find distance since last mine
				for($i = 0; $i<count($log); $i++)
				{
					if(strpos($log[$i], ' '.$COIN.' '))
					{
						$coin_distance[$COIN] = $i; // Found distance for coin
						break;
					}
				}
			}
			uasort($coin_distance, array($this, 'cb_float_rsort')); // Sort by distance
			
			$new_coin = key($coin_distance);
			if($new_coin === NULL) // Nothing to do.. No new coin found..
				return;
			if($this->switch_coin($new_coin))
				$this->write_log(date('m/d/Y H:i:s')." - Switched to $new_coin (TIMED COIN)\r\n");
		}
		
		// Load all configs ( Coins )
		private function load_coins()
		{
			$this->coins = FALSE;
			$files = scandir($this->home_path.'configs/');
			foreach($files as $filename)
			{
				if(strpos($filename, '-') === FALSE)
					continue;
				
				$split = explode('-', $filename);
				$pool = $split[0];
				$tag = $split[1];
				if(strpos($tag, '.'))
					$tag = substr($tag, 0, strpos($tag, '.'));
				
				$this->coins[strtoupper($tag)] = array('config' => $filename, 'folder_path' => '');
			}
		}
		
		// Load coins that are set for specifc times
		private function load_timers()
		{
			$this->timers = FALSE;
			foreach($this->coin_timers as $timer)
			{
				if(!is_numeric($timer['START_HOUR']) || !is_numeric($timer['END_HOUR'])) // Not a valid format ( not a number )
					continue;
				if($timer['START_HOUR'] < 0 && $timer['START_HOUR'] > 24) // Not a valid format ( exceeds or below standard 24h format )
					continue;
				if($timer['END_HOUR'] < 0 && $timer['END_HOUR'] > 24) // Not a valid format ( exceeds or below standard 24h format )
					continue;
				
				$current_hour = (int)date('H', time());
				if($current_hour < $timer['START_HOUR'] || $current_hour >= $timer['END_HOUR']) // Not set for current time
					continue;
				
				$tag = strtoupper($timer['COIN']);
				if(!isset($this->coins[$tag]))
					continue;
				
				$this->timers[$tag] = $timer;
			}
		}
		
		// Check if auto_switch was disabled with a file
		private function auto_switch_disabled()
		{
			return file_exists($this->home_path.'scripts/no-autoswitch');
		}
			
		private function switch_coin($new_coin)
		{
			if(file_exists($this->home_path.'scripts/current_coin.txt'))
			{
				// Coin already active? Nothing to do..
				$current_coin = file_get_contents($this->home_path.'scripts/current_coin.txt');
				if($new_coin == $current_coin)
					return FALSE;
			
				// Log mining output from previous mining
				copy('/var/run/miner.output', $this->home_path.'logs/'.$current_coin.'.log');
			}
			
			// Switch coin
			file_put_contents($this->home_path.'scripts/current_coin.txt', $new_coin, LOCK_EX);
			$config_file = $this->coins[$new_coin]['config'];
			copy($this->home_path."configs/".$config_file, $this->ethos_path.'local.conf');
			sleep(5);
			shell_exec('/opt/ethos/bin/minestop');
			sleep(5);
			$output = shell_exec('/opt/ethos/bin/restart-proxy 2>&1');
			$this->output("Restarting Proxy: $output");
			shell_exec('/opt/ethos/sbin/ethos-overclock > /dev/null 2>&1 &'); // ethos-overclock log at: /var/log/ethos-overclock.log
			$this->output("Started ethos-overclock");
			$this->output("Switch complete");
			
			return TRUE;
		}
		
		/////////////////
		//// HELPERS
		
		private function output($message)
		{
			echo "$message\r\n";
		}
		private function write_log($log)
		{
			file_put_contents($this->home_path.'scripts/log', $log, FILE_APPEND | LOCK_EX);
		}
		public function cb_float_rsort($a, $b)
		{
			if ($a == $b) {
				return 0;
			}
			return ($a > $b) ? -1 : 1;
		}
	}
	
	$app = new auto_switch();
	$app->dust_collect_enabled = DUST_COLLECT_ENABLED;
	$app->dust_collect_start = DUST_COLLECT_START;
	$app->dust_collect_end = DUST_COLLECT_END;
	$app->wtm_api_url = WTM_API_URL;
	$app->coin_timers = $COIN_TIMERS;
	$app->run();

?>