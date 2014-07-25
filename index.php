<?php
require_once 'XML/RPC2/Client.php';
//must be of the main account used on the OpenX installation.
$oxLogin = array("username"=>"adm","password"=>"admpass");

//The “ox.” prefix for the RPC service is default and does not need to be changed.
$opts = array('prefix' => 'ox.');
$advertiserId = 2;

$client = XML_RPC2_Client::create('http://localhost/revive/www/api/v2/xmlrpc/', $opts);

try {

    $sessionId = $client->logon($oxLogin['username'],$oxLogin['password']);

    echo "<pre>";
    echo "advertiserId - 2";
    $result = $client->getAdvertiser($sessionId,$advertiserId);
    print_r($result);


    // The advertiserDailyStatistics function returns an array of
    // all days in which the advertiser was active, if there is no
    // specific date passed as one of the parameters.

    $result = $client->advertiserDailyStatistics($sessionId,$advertiserId);
    print_r($result);


    $advertiserId = 1;
    $agencyId = "1";
    $accountId = "5";

    /* Create new advertiser for $accountId and $agencyId */
    $advertiserData = array(
                        "accountId"=> (int) $accountId,
                        "agencyId"=> (int) $agencyId,
                        "advertiserName"=>"Henry Fonda",
                        "contactName"=>"Mr. Fonda",
                        "emailAddress"=>"hfonda@hfondafoundation.com",
                        "comments"=>"VIP Client");

    $result = $client->addAdvertiser($sessionId,$advertiserData);
    print_r($result);

    /* Delete advertiser $advertiserId */
    //$result = $client->deleteAdvertiser($sessionId,$advertiserId);
    print_r($result);

    /* Get advertiser daily stats for $advertiserId */
    $result = $client->advertiserDailyStatistics($sessionId,$advertiserId);
    print_r($result);

    $client->logoff($sessionId);

} catch (XML_RPC2_FaultException $e) {

    // The XMLRPC server returns a XMLRPC error
    die('Exception #' . $e->getFaultCode() . ' : ' . $e->getFaultString());

} catch (Exception $e) {

    // Other errors (HTTP or networking problems...)
    die('Exception : ' . $e->getMessage());

}


?>
