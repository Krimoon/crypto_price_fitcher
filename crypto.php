<?php


//Get crypto current price v1.0
//krimoon:krimoon@gmail.com



function fetch_data( $url, $z=null ) {
	
	
	$ch =  curl_init();

	$useragent = isset($z['useragent']) ? $z['useragent'] : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:10.0.2) Gecko/20100101 Firefox/10.0.2';

	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt( $ch, CURLOPT_POST, isset($z['post']) );

	if( isset($z['post']) )         curl_setopt( $ch, CURLOPT_POSTFIELDS, $z['post'] );
	if( isset($z['refer']) )        curl_setopt( $ch, CURLOPT_REFERER, $z['refer'] );

	curl_setopt( $ch, CURLOPT_USERAGENT, $useragent );
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); 
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout in seconds
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

	$result = curl_exec( $ch );
	
	$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	$header = substr($result, 0, $header_size);
	$body = substr($result, $header_size);
	
	
	curl_close( $ch );
	
	
	return $result;
}


function get_coin_current_price($coin="BTC"){

	$url = 'https://api.binance.com/api/v1/ticker/24hr?symbol='.$coin.'USDT';
	
	$vals = json_decode(fetch_data($url), true);
	
	if(!is_array($vals) and !is_object($vals)){
		sleep(3);
		$vals = json_decode(fetch_data($url), true);
	}

	if(!isset($vals["bidPrice"])){
		echo "Error: couldn't fetch data...Retry in 5 seconds";
		echo $refresh = '<meta http-equiv="refresh" content="5">';
		exit;
	}
	
	$current_price=$vals["bidPrice"];

	return $current_price;
	
}


$coin = "BTC";
$current_price = get_coin_current_price($coin);
echo "$coin: $current_price";


