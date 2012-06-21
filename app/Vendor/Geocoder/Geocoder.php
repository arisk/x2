<?php

/**
 * Google Geocoding API Geocoder class for PHP 5
 * @link https://developers.google.com/maps/documentation/geocoding
 * @example $coder = new Geocoder();
 *          $coder->setSecure(true); 
 *          $coder->address = 'Boston';
 *          $result = $coder->get();
 *  
 * @property boolean $secure Should I fetch the results securely?
 * @property array $params holds all url arguments to be sent to the Geocoder
 * 
 * @version 1.1
 * @author Aris Karageorgos <aris@deepspacehosting.com>
 * @copyright deepspacehosting.com 2012
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Geocoder{
    /**
     * Google Maps API V3 URL
     */

    const GEO_URL = 'maps.googleapis.com/maps/api/geocode/';

    /**
     * HTTP or HTTPS
     */
    const HTTP = 'http://';
    const HTTPS = 'https://';

    /**
     * Output format. We're doing only XML but the API is capable of others.
     */
    const OUTPUT = 'xml';

    /**
     * Question mark
     */
    const Q = '?';

    private $secure = false;
    private $params = array(
        'address' => null,
        'latlng' => null,
        'bounds' => null,
        'region' => null,
        'language' => null,
        'sensor' => 'false',
    );

    /**
     * @property array $errors list of errors return from the Geocoder with explanations.
     * This will be available as part of the return XML
     */
    private $errors = array(
        'OK' => 'No errors',
        'ZERO_RESULTS' => 'There are no results to display',
        'OVER_QUERY_LIMIT' => 'You have exceeded your Google Maps Quota for today',
        'REQUEST_DENIED' => 'Your request has been denied',
        'INVALID_REQUEST' => 'Invalid Request. Please check your address or coordinates',
    );

    /**
     * Check to see if the SimpleXML extension is loaded on startup
     */
    public function __construct(){
        if(!extension_loaded('simplexml')){
            throw new Exception('The Simple XML extension is required to use this class');
        }
    }

    /**
     * Magic method to set properties
     * @param string $name The name of the property to be set
     * @param string $value The value that the property will be set to
     * @return void
     */
    public function __set($name, $value){
        $this->params[$name] = $value;
    }

    /**
     * Magic method to get properties
     * @param string $name The name of the property to get
     * @return mixed the variable or false if it doesn't exist
     */
    public function __get($name){
        if(array_key_exists($name, $this->params)){
            return $this->params[$name];
        }
        return false;
    }

    /**
     * Magic method to check if a property is set
     * @param string $name The name of the property to be checked
     * @return boolean true if the variable is set and false otherwise
     */
    public function __isset($name){
        return isset($this->params[$name]);
    }

    /**
     * Magic method to unset a property
     * @param string $name The name of the property to be unset
     */
    public function __unset($name){
        unset($this->params[$name]);
    }

    /**
     * Getter for secure property. 
     * Used to check if the url should begin with http:// or https://
     * @return bool $secure
     */
    public function getSecure(){
        return $this->secure;
    }

    /**
     * Setter for secure property. 
     * Used to set if the url should begin with http:// or https://
     */
    public function setSecure($val){
        $this->secure = (bool) $val;
    }

    /**
     * This is where it all happens. Build the URL to be sent to the Geocoder.
     * Some basic checking if an address or coordinates have been set is checked.
     * Additionally if the request is made through https a check is made to see 
     * if the openssl extension is installed.
     * @return SimpleXMLElement XML containing the geocoded address or an error message
     */
    public function get(){
        if($this->secure === true){
            $protocol = self::HTTPS;
        }
        else{
            $protocol = self::HTTP;
        }
        if(!empty($this->address)){
            unset($this->latlng);
            $params = http_build_query($this->params);
        }
        elseif(!empty($this->latlng)){
            unset($this->address);
            $params = http_build_query($this->params);
        }
        else{
            return array(
                'status' => 'INVALID_REQUEST',
                'message' => $this->errors['INVALID_REQUEST'],
            );
        }
        $url = $protocol.self::GEO_URL.self::OUTPUT.self::Q.$params;
        try{
            if($this->getSecure() === true){
                if(!extension_loaded('openssl')){
                    throw new Exception('You need to install the OpenSSL extension in order to make secure requests');
                }
            }
            $result = new SimpleXMLElement($url, 0, true);
            $result->addChild('message', $this->errors[(string) $result->status]);
        }
        catch(Exception $e){
            print $e->getMessage();
        }
        return $result;
    }

    /**
     * Returns always the first element of the result set. The first element is 
     * always the most specific to the address or coordinates. The structure 
     * varies now the root element is <result> so status and message are not under
     * <result> instead of <GeocodeResponse>
     */
    public function getFirst(){
        $xml = $this->get();
        if(isset($xml->result) && !empty($xml->result)){
            $new_xml = $xml->result[0];
            $new_xml->addChild('status', $xml->status);
            $new_xml->addChild('message', $xml->message);
            return $new_xml;
        }
        else{
            return $xml;
        }
    }
}