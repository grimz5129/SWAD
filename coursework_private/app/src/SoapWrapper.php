<?php

/**
 * SoapWrapper.php
 *
 * A helper class to call soap functions to and fetch the data back.
 *
 * @author Joe
 */

namespace Coursework;


use phpDocumentor\Reflection\Types\String_;

class SoapWrapper
{
    function __construct()
    {
    }
    function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * buildSoapClient()
     * Function to set up the soap client handle.
     *
     * @return \SoapClient|string - Returns the now open soap client
     * @author Joe
     */
    function buildSoapClient() {
        $soap_client_handle = false;
        $soap_client_parameters = array();
        $exception = '';
        $wsdl = WSDL;

        $soap_client_parameters = ['trace' => true, 'exceptions' => true];

        try {
            $soap_client_handle = new \SoapClient($wsdl, $soap_client_parameters);
        } catch (\SoapFault $exception) {
            $soap_client_handle = "Something went wrong when connecting to the soap server - More information can be found in the log.";
            // Add Logging here
        }
        return $soap_client_handle;
    }

    /**
     * makeSoapCall()
     * Perform a soap call for a specified object from the response.
     *
     * @param $soap_client - The client handle for the open soap client
     * @param $webservicefuntion - The function to be called on the foreign soap server
     * @param $webservice_call_paramenters - The parameters for the call to the soap server
     * @param $webservice_value - The object you want from the soap call
     *
     * @return array[Obj] - Returns an array of messege objects
     *
     * @author Joe
     */
    function makeSoapCall($soap_client, $webservicefuntion, $webservice_call_paramenters, $webservice_value) {
        $soapResult = null;
        $output = [];

        // Check if soap client exists

        if ($soap_client !== null){
            try {

                // Perform soap call and store raw xml in $soapResult
                $soapResult = $soap_client->__soapCall($webservicefuntion, $webservice_call_paramenters);

                // Iterate through array of xml strings and convert each one
                foreach ($soapResult as $soapItem) {
                  set_error_handler(function() {});
                  // try catch here
                  try{
                    $item = new \SimpleXMLElement($soapItem);
                    array_push($output, $item);
                  }
                  catch(\Exception $exception){

                  }
                  restore_error_handler();
                }

            } catch(\SoapFault $exception) {
                $soapResult = "Something went wrong when connecting to the soap server";
            }

            return $output;

        }

    }
}
