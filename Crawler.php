<?php
class Crawler 
{
	
	function __construct() 
	{
		
	}
	
	function getbody( $path ) 
	{
		//$pageFullContent = file_get_contents( $path );


		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $path);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$pageFullContent = curl_exec($curl);
		curl_close($curl);

		preg_match("/<body[^>]*>(.*?)<\/body>/is", $pageFullContent, $bodyMatches);
		if( isset( $bodyMatches[1] ) ) {
			return $bodyMatches[1];
		} else {
			return '';
		}
	}
	
	function anchorTag( $bodyContent, $pageUrl ) 
	{
		$anchorTagArray = array();
		$regexp = "<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*)<\/a>"; 
		if(preg_match_all("/$regexp/siU", $bodyContent, $matches, PREG_SET_ORDER)) { 
			foreach($matches as $match) { 
				$fetchedUrl = $match[2];				
				if (preg_match('%^https?://[^\s]+$%', $fetchedUrl )) {
					if( !in_array( $fetchedUrl,  $anchorTagArray ) ) {
						if( $this->checkIsImageUrl( $fetchedUrl ) ) {
							$anchorTagArray[] = $fetchedUrl;
						}
					}
				} else {
			    	if($this->firstCharCheck( $fetchedUrl, '/')){
				    	$newUpdatedUrl = $pageUrl.$match[2];
				    	if( !in_array( $match[2],  $anchorTagArray ) ) {
				    		$anchorTagArray[] = $newUpdatedUrl;
				    	}
			    	}
				}
			} 
		}
		return $anchorTagArray;
	}

	function checkIsImageUrl( $url )
	{
		$extension = pathinfo( $url, PATHINFO_EXTENSION );
		if ( $extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif' ) {
			return false;
		} else {
			return true;
		}

	}

	function firstCharCheck($haystack, $needle)
	{
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	
	function imgTagCount( $bodyContent ) 
	{
		preg_match_all('/<img[^>]+>/i',$bodyContent, $result);
		return count( $result[0] );
	}
}

?>