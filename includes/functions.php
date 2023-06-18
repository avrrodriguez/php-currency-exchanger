<?php

function getCurrencyCodeofCountry($country) {
    // Fetching JSON
    $req_url = "https://restcountries.com/v3.1/name/".$country."?fullText=true";
    $response_json = file_get_contents($req_url);
    // Continuing if we got a result
    if(false !== $response_json) {
        // Try/catch for json_decode operation
        try {
          // Decoding
          $response = json_decode($response_json);

          // Extracting currency from response
          $currency = $response[0]->currencies;

          return key((array)$currency);
  
        } catch(Exception $e) {
            // Handle JSON parse error...
            echo "Caught Error". $e->getMessage();
        }
  
    } else {
      echo "Not a valid country.\n";
      echo "Go back to previous page and try again.";
    }
}

function getExchangeRateOfUSDtoOtherCountry($newCountry){

  $newCurrencyName = getCurrencyCodeofCountry($newCountry);
  // Fetching JSON
  $req_url = "https://open.er-api.com/v6/latest/USD";
  $response_json = file_get_contents($req_url);

  // Continuing if we got a result
  if(false !== $response_json) {
      // Try/catch for json_decode operation
      try {
        // Decoding
        $response = json_decode($response_json);

        // Check for success
        if('success' === $response->result) {
          return $response->rates->$newCurrencyName;
        }

      } catch(Exception $e) {
          // Handle JSON parse error...
          echo "Caught Error". $e->getMessage();
      }

  }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $exchangeRate = getExchangeRateOfUSDtoOtherCountry($_POST["exchange-currency-to"]);
  echo $_POST["amount"] . " converted from US Dollars to the currency of ".$_POST["exchange-currency-to"]." using the conversion rate of " . $exchangeRate . " is ". (float)$exchangeRate * (float)$_POST["amount"]." .";
} 
