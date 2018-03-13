<?php
	//////////
	///// General Config
	//////////
	define('RUN_ETHOS_OVERCLOCK', FALSE); // Run ethos-overclock after config switch
	
	//////////
	///// Mining specific coins in specific times
	//////////
	$COIN_TIMERS = FALSE;
	//$COIN_TIMERS[] = array('COIN' => 'CBS', 'START_HOUR' => 06, 'END_HOUR' => 10);
	//$COIN_TIMERS[] = array('COIN' => 'TZC', 'START_HOUR' => 06, 'END_HOUR' => 10);

	//////////
	///// Mine some of the coins which haven't been mined for a long time.
	///// For those coins you see gaining a lot in value in the future.
	//////////
	define('DUST_COLLECT_ENABLED', TRUE);
	define('DUST_COLLECT_START', 23);
	define('DUST_COLLECT_END', 4);
	
	//////////
	///// API URL used for looking up current prices
	//////////
	define('WTM_API_URL', 'http://whattomine.com/coins.json?utf8=?&adapt_q_280x=0&adapt_q_380=0&adapt_q_fury=0&adapt_q_470=0&adapt_q_480=0&adapt_q_570=0&adapt_q_580=0&adapt_q_vega56=0&adapt_q_vega64=0&adapt_q_750Ti=0&adapt_q_1050Ti=0&adapt_q_10606=0&adapt_q_1070=6&adapt_1070=true&adapt_q_1070Ti=0&adapt_q_1080=13&adapt_q_1080Ti=0&eth=true&factor%5Beth_hr%5D=180.0&factor%5Beth_p%5D=720.0&grof=true&factor%5Bgro_hr%5D=201.0&factor%5Bgro_p%5D=780.0&x11gf=true&factor%5Bx11g_hr%5D=69.0&factor%5Bx11g_p%5D=720.0&cn=true&factor%5Bcn_hr%5D=3780.0&factor%5Bcn_p%5D=600.0&eq=true&factor%5Beq_hr%5D=2580.0&factor%5Beq_p%5D=720.0&lre=true&factor%5Blrev2_hr%5D=213000.0&factor%5Blrev2_p%5D=780.0&ns=true&factor%5Bns_hr%5D=6000.0&factor%5Bns_p%5D=780.0&lbry=true&factor%5Blbry_hr%5D=1620.0&factor%5Blbry_p%5D=720.0&bk2bf=true&factor%5Bbk2b_hr%5D=9600.0&factor%5Bbk2b_p%5D=720.0&bk14=true&factor%5Bbk14_hr%5D=14400.0&factor%5Bbk14_p%5D=750.0&pas=true&factor%5Bpas_hr%5D=5700.0&factor%5Bpas_p%5D=720.0&skh=true&factor%5Bskh_hr%5D=165.0&factor%5Bskh_p%5D=720.0&n5=true&factor%5Bn5_hr%5D=57.0&factor%5Bn5_p%5D=345.0&factor%5Bl2z_hr%5D=420.0&factor%5Bl2z_p%5D=300.0&factor%5Bcost%5D=0.1&sort=Profit&volume=0&revenue=current&factor%5Bexchanges%5D%5B%5D=&factor%5Bexchanges%5D%5B%5D=abucoins&factor%5Bexchanges%5D%5B%5D=bitfinex&factor%5Bexchanges%5D%5B%5D=bittrex&factor%5Bexchanges%5D%5B%5D=bleutrade&factor%5Bexchanges%5D%5B%5D=cryptopia&factor%5Bexchanges%5D%5B%5D=hitbtc&factor%5Bexchanges%5D%5B%5D=poloniex&factor%5Bexchanges%5D%5B%5D=yobit&dataset=Main&commit=Calculate');

?>