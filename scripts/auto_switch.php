<?php
	chdir('/home/ethos/');
	$hour = date('H', time());
	$COINS = FALSE;
	$COINS['ETH'] = array('config' => 'suprnova-eth.conf'); // Ethash
	$COINS['ZCL'] = array('config' => 'suprnova-zcl.conf'); // Equihash
	$COINS['HUSH'] = array('config' => 'suprnova-hush.conf'); // Equihash
	$COINS['ORB'] = array('config' => 'theblocksfactory-orb.conf'); // Neoscrypt
	$COINS['LBC'] = array('config' => 'suprnova-lbc.conf'); // LBRY
	$COINS['ZEC'] = array('config' => 'suprnova-zec.conf'); // Equihash
	$COINS['DGB'] = array('config' => 'suprnova-dgb.conf'); // Myriad-Groestl
	$COINS['DCR'] = array('config' => 'suprnova-dcr.conf'); // Decred
	$COINS['PXC'] = array('config' => 'theblocksfactory-pxc.conf'); // Neoscrypt
	$COINS['SIB'] = array('config' => 'suprnova-sib.conf'); // Neoscrypt
	$COINS['KMD'] = array('config' => 'suprnova-kmd.conf'); // Equihash
	$COINS['ZEN'] = array('config' => 'suprnova-zen.conf'); // Equihash
	$COINS['FTC'] = array('config' => 'miningpoolhub-ftc.conf'); // Neoscrypt
	$COINS['MONA'] = array('config' => 'suprnova-mona.conf'); // Lyra2REv2
	$COINS['VTC'] = array('config' => 'miningpoolhub-vtc.conf'); // Lyra2REv2
	$COINS['BTG'] = array('config' => 'suprnova-btg.conf'); // Equihash
	$COINS['UBQ'] = array('config' => 'ubiqpool-ubq.conf'); // Ethash
	
	// File to disable the auto switch
	if(file_exists('scripts/no-autoswitch'))
		die();
	
	// Mine random coins between 12 - 16 ( dust collection )
	if($hour >= 12 && $hour < 16)
	{
		$current_coin = file_get_contents('scripts/current_coin.txt');
		$new_coin = array_rand($COINS);
		if($new_coin == $current_coin)
			$new_coin = array_rand($COINS);
		if($new_coin == $current_coin)
			return;
		
		// Switch coin
		file_put_contents('scripts/current_coin.txt', $new_coin, LOCK_EX);
		$config_file = $COINS[$new_coin]['config'];
		copy("configs/".$config_file, "local.conf");
		sleep(5);
		shell_exec('/opt/ethos/bin/minestop');
		sleep(5);
		$output = shell_exec('/opt/ethos/bin/restart-proxy 2>&1');
		file_put_contents('scripts/log', date('m/d/Y H:i:s')." - Switching to $new_coin (Dust Collect): $output.\r\n", FILE_APPEND | LOCK_EX);
	}
	else
	{
		$json_coins = file_get_contents('http://whattomine.com/coins.json?adapt_q_280x=0&adapt_q_380=0&adapt_q_fury=0&adapt_q_470=0&adapt_q_480=0&adapt_q_570=0&adapt_q_580=0&adapt_q_750Ti=0&adapt_q_10606=0&adapt_q_1070=6&adapt_1070=true&adapt_q_1080=0&adapt_q_1080Ti=0&eth=true&factor%5Beth_hr%5D=180.0&factor%5Beth_p%5D=720.0&grof=true&factor%5Bgro_hr%5D=213.0&factor%5Bgro_p%5D=780.0&x11gf=true&factor%5Bx11g_hr%5D=69.0&factor%5Bx11g_p%5D=720.0&cn=true&factor%5Bcn_hr%5D=3000.0&factor%5Bcn_p%5D=600.0&eq=true&factor%5Beq_hr%5D=2580.0&factor%5Beq_p%5D=720.0&lre=true&factor%5Blrev2_hr%5D=14700.0&factor%5Blrev2_p%5D=390.0&ns=true&factor%5Bns_hr%5D=1950.0&factor%5Bns_p%5D=450.0&lbry=true&factor%5Blbry_hr%5D=315.0&factor%5Blbry_p%5D=525.0&bk2bf=true&factor%5Bbk2b_hr%5D=3450.0&factor%5Bbk2b_p%5D=630.0&bk14=true&factor%5Bbk14_hr%5D=5910.0&factor%5Bbk14_p%5D=570.0&pas=true&factor%5Bpas_hr%5D=2100.0&factor%5Bpas_p%5D=405.0&skh=true&factor%5Bskh_hr%5D=54.0&factor%5Bskh_p%5D=345.0&factor%5Bl2z_hr%5D=420.0&factor%5Bl2z_p%5D=300.0&factor%5Bcost%5D=0.1&sort=Revenue&volume=0&revenue=current&factor%5Bexchanges%5D%5B%5D=&factor%5Bexchanges%5D%5B%5D=bittrex&factor%5Bexchanges%5D%5B%5D=bleutrade&factor%5Bexchanges%5D%5B%5D=bter&factor%5Bexchanges%5D%5B%5D=c_cex&factor%5Bexchanges%5D%5B%5D=cryptopia&factor%5Bexchanges%5D%5B%5D=hitbtc&factor%5Bexchanges%5D%5B%5D=poloniex&factor%5Bexchanges%5D%5B%5D=yobit&dataset=Main&commit=Calculate');
		$data_coins = json_decode($json_coins, true);
		$profits = FALSE;

		if(isset($data_coins['coins']) && count($data_coins['coins']) > 0)
		{			
			foreach($data_coins['coins'] as $label => $coin)
			{
				if(!isset($COINS[$coin['tag']]))
					continue; // Skip unsupported coins.
				if($coin['lagging'])
					continue; // Skip lagging coins.
				
				$tag = $coin['tag'];
				$hash_rate = $COINS[$tag]['hash_rate'];
				$coin_id = $coin['id'];
				
				$profits[$tag] = floatval($coin['profitability']);
			}
		}
		// Output list	
		var_dump($profits);
		
		if($profits && count($profits) > 0)
		{
			// Sort by profit (reverse)
			uasort($profits, 'float_rsort');
			$new_coin = key($profits);
			$new_profit = current($profits); 
			
			// Get current active coin
			$current_coin = file_get_contents('scripts/current_coin.txt');
			if($new_coin == $current_coin)
				return;
			
			// Switch coin
			file_put_contents('scripts/current_coin.txt', $new_coin, LOCK_EX);
			$config_file = $COINS[$new_coin]['config'];
			copy("configs/".$config_file, "local.conf");
			sleep(5);
			shell_exec('/opt/ethos/bin/minestop');
			sleep(5);
			$output = shell_exec('/opt/ethos/bin/restart-proxy 2>&1');
			file_put_contents('scripts/log', date('m/d/Y H:i:s')." - Switching to $new_coin ($new_profit): $output.\r\n", FILE_APPEND | LOCK_EX);
		}
	}
	
	function float_rsort($a, $b) {
		if ($a == $b) {
			return 0;
		}
		return ($a > $b) ? -1 : 1;
	}

?>
