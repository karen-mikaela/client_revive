<?php

/*
* The API service is based on the RPC protocol, use the latest PHP XML_RPC2 library provided
* by PEAR (http://pear.php.net/package/XML_RPC2/).
* The installation is fairly straight forward, if you have PEAR already installed, simply
* run pear install XML_RPC2
* Notes: the XML_RPC2 service is data type sensitive, So to resolve this issue, you need to make
* sure all of your variable have a defined typ
*/
require_once 'XML/RPC2/Client.php';
require_once 'config.php';

//must be of the main account used on the OpenX installation.
$oxLogin = array("username"=>ADM_USERNAME,"password"=>ADM_PASSWORD);

//The “ox.” prefix for the RPC service is default and does not need to be changed.
$opts = array('prefix' =>PREFIX);
$client = XML_RPC2_Client::create(URL_XML_RPC2_CLIENT, $opts);



try {
    /*
    * Authenticate with the Revive API and retrieve the sessionId required
    * for all other function calls
    */
    $sessionId = $client->logon($oxLogin['username'],$oxLogin['password']);

    echo "<pre>";
    echo "get getAdvertiser \n";
    $advertiserId = 2;
    /* Get advertiser info for $advertiserId */
    $result = $client->getAdvertiser($sessionId,$advertiserId);
    print_r($result);


    // The advertiserDailyStatistics function returns an array of
    // all days in which the advertiser was active, if there is no
    // specific date passed as one of the parameters.
    /* Get advertiser daily stats for $advertiserId */
    $result = $client->advertiserDailyStatistics($sessionId,$advertiserId);
    echo "get advertiserDailyStatistics \n";
    print_r($result);


    /* Create new advertiser */
    $advertiserData = array(
                        "advertiserName"=>"Karen LTDA 3",
                        "contactName"=>"Karen",
                        "emailAddress"=>"karen@karen.com",
                        "comments"=>"blabla");

    $new_advertiserId = false;//$client->addAdvertiser($sessionId,$advertiserData);
    if($new_advertiserId){
        $result_new_getAdvertiser = $client->getAdvertiser($sessionId,$new_advertiserId);
        echo "print new_advertiserId \n";
        print_r($result_new_getAdvertiser);
    }else{
        echo "couldnt create new_advertiserId \n";
    }

    /* Delete advertiser $advertiserId */
    $deleted_advertiser = false;//$client->deleteAdvertiser($sessionId,13);
    if($deleted_advertiser){
        echo "print deleted_advertiser \n";
    }else{
        echo "couldnt deleted new_advertiserId \n";
    }

    $campaignData = array(
                        "advertiserId"=>2,
                        "campaignName"=>"Campaign xpto 2",
                        "impressions"=>1000);

    /* Create new campaign */
    $newCampaignId = $client->addCampaign($sessionId,$campaignData);
    if($newCampaignId){
        $result_new_getCampaign = $client->getCampaign($sessionId,$newCampaignId);
        echo "print new_getCampaign \n";
        print_r($result_new_getCampaign);
    }else{
        echo "couldnt create _new_getCampaign \n";
    }

    /* Delete campaign $campaignId */
    $deleted_acampaign = false;//$client->deleteCampaign($sessionId,9);
    if($deleted_acampaign){
        echo "print deleted_acampaign \n";
    }else{
        echo "couldnt deleted deleted_acampaign \n";
    }

    /* Modify campaign $campaignId */
    $x_campaignData = array(
                    "campaignId"=>7,
                    "campaignName"=>"Campaign xpto blbla",
                    "impressions"=>50);
    $modified_acampaign = $client->modifyCampaign($sessionId,$x_campaignData);
    if($modified_acampaign){
        echo "print modified_acampaign \n";
    }else{
        echo "couldnt modified_acampaign \n";
    }



    $client->logoff($sessionId);

} catch (XML_RPC2_FaultException $e) {

    // The XMLRPC server returns a XMLRPC error
    die('Exception #' . $e->getFaultCode() . ' : ' . $e->getFaultString());

} catch (Exception $e) {

    // Other errors (HTTP or networking problems...)
    die('Exception : ' . $e->getMessage());

}


?>
