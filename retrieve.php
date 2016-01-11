<?php
// function to retrieve information from db
function retrieve_token(){
  global $oauth_db_version;
  global $infusionsoft;
  global $tokenExpiration;
  global $unserializedIsToken;
  global $tokenObject;
  global $newToken;
  global $tokenID;
  global $wpdb;

  // grab the db prefix, add the table name
  $table_name = $wpdb->prefix . "infusionWholesaler";

  // sql statement
  $sql="SELECT expiration, token, id FROM `$table_name`";

  // run the query, set it to variables
  $tokenStuff = $wpdb->get_results($sql);

  // expiration
  $tokenExpiration = $tokenStuff[0]->expiration;
  // serilzed token
  $tokenObject = $tokenStuff[0]->token;
  // id
  $tokenID = $tokenStuff[0]->id;

  // unserlized token
  $unserializedIsToken = unserialize($tokenObject);
  $newToken = $unserializedIsToken->accessToken;
}

// check expiration
function check_token_expiration($tokenExpiration, $unserializedIsToken, $infusionsoft, $tokenID){
  global $wpdb;
  global $table_name;
  global $unserializedIsToken;
  global $infusionsoft;

  // grab the db prefix, add the table name
  $table_name = $wpdb->prefix . "infusionWholesaler";

  // get current time, subtract 10 minutes
  $needsNewToken = time() - 600 ;

  // if we need a new token, refresh it
  if($needsNewToken > $tokenExpiration){
    echo 'You need a new token, please refresh the page. <br />';

    // refresh it
    $newISToken = $infusionsoft->refreshAccessToken();

    // get end of life
    $newEndOfLife = $newISToken->endOfLife;

    // serialize object for db storage
    $newSerialToken = serialize($newISToken);

    // update the db
    $wpdb->query( $wpdb->prepare("
  		UPDATE $table_name
  		SET token = %s,
  		    expiration = %s
      WHERE id = 1;
      ",
      $newSerialToken, $newEndOfLife
  	));

    // return true
    return true;
  } else {
    return true;
  }
}
