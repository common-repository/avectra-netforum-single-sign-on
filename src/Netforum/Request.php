<?php

namespace Netforum;

use Netforum\Traits\SingletonTrait;
use Netforum\Exceptions\RuntimeException;

class Request extends \SoapClient {

    use SingletonTrait;

    protected $config;
    protected $token;
    protected $ssoToken;
    protected $cstToken;

    public function __construct($wsdl, array $params) {
        $this->config = (object) $params;
        $this->wsdl = $wsdl;
        $this->wsdl_params = $this->constructParams($params);
        parent::__construct($wsdl, $this->wsdl_params);
        return $this;
    }

    public function getTimeout() {
        return (int) $this->config->timeout;
    }

    public function setTimeout(int $timeout_e) {
        $this->config->timeout = $timeout_e;
    }

    public function getSoapVersion() {
        return SOAP_1_2;
    }

    protected function constructParams(array $temp_e) {
        if ($this->config->debug) {
            $temp_e += array('trace' => true);
        } $temp_e += array('exceptions' => true,'soap_version' => $this->getSoapVersion(),'connection_timeout' => $this->getTimeout(),'default_socket_timeout' => $this->getTimeout(),'cache_wsdl' => WSDL_CACHE_BOTH,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,'encoding' => 'UTF-8','user_agent' => 'NetForum Api (Simple) by FusionSpan llc.');
        return array_filter($temp_e);
    }

    public function __doRequest($request, $location, $action, $version, $one_way = 0) {
        ini_set('default_socket_timeout', $this->getTimeout());
        if ($this->config->debug) {
            dd(colorize(' >>> Sending Request _______', 'blue'));
            dd(colorize('Params: 
Request: ' . prettyXML($request) . "\nLocation: {$location}\nAction: {$action}\nVersion: {$version}", 'blue'));
            dd(colorize('________________', 'blue') . '

');
        } return parent::__doRequest($request, $location, $action, $version);
    }

    public function auth() {
        $response = $this->request('Authenticate', array('parameters' => array('userName' => $this->config->username,'password' => $this->config->password)));
        if (is_object($response)) {
            $this->token = $response->AuthenticateResult;
        } return $this;
    }

    public function authSso($user = null, $pass = null) {
        if (is_null($user) && is_null($pass) && !$this->config->credentials) {
            throw new \Exception('Client credentials are required.');
        } if (is_null($user) && is_null($pass) && $this->config->credentials) {
            $user = $this->config->credentials['username'];
            $pass = $this->config->credentials['password'];
        } $response = $this->auth()->request('GetSignOnToken', array('parameters' => array('Email' => $user,'Password' => $pass,'Minutes' => $this->config->ttl)));
        if (is_object($response) && isset($response->GetSignOnTokenResult)) {
            $this->ssoToken = array_pop(explode('=', $response->GetSignOnTokenResult));
        } return $this;
    }

    public function authCST() {
        $response = $this->auth()->request('GetCstKeyFromSignOnToken', array('parameters' => array('szEncryptedSingOnToken' => $this->ssoToken)));
        if (is_object($response)) {
            $this->cstToken = $response->GetCstKeyFromSignOnTokenResult;
        } return $this;
    }

    public function getToken() {
        return $this->token;
    }

    public function getSsoToken() {
        if (is_null($this->ssoToken)) {
            $this->authSso();
        } return $this->ssoToken;
    }

    public function getCstToken() {
        if (is_null($this->cstToken)) {
            $this->authSso();
            $this->authCST();
        } return $this->cstToken;
    }

    public function getCustomerByKey($key = null) {
        return $this->OD()->request('GetCustomerByKey', array('parameters' => array('szCstKey' => is_null($key) ? $this->getCstToken() : $key)));
    }

    public function request($u_e, array $params = array(), $p_e = null) {
        try {
            if ($this->config->debug) {
                dd(colorize('Command is ' . $u_e, 'yellow'));
            } if (!isset($params['parameters']['AuthToken']) && isset($this->token)) {
                $params['parameters']['AuthToken'] = $this->token;
                $p_e = new \SoapHeader('http://www.avectra.com/OnDemand/2005/', 'AuthorizationToken', array('Token' => $this->token));
                if ($this->config->debug) {
                    dd('SENDING HEADERS: ');
                    dd($p_e);
                }
            } $soap_call = $this->__soapCall($u_e, $params, null, $p_e);
            if ($this->config->debug) {
                dd(colorize(" <<< {$u_e} Response Received _______", 'green') . '

');
                dd($this->beauty_xml($u_e, $soap_call));
                dd(colorize('________________', 'green') . '

');
            } return $this->beauty_xml($u_e, $soap_call);
        } catch (\SoapFault $e) {
            $msg = $e->getMessage();
            if (preg_match('/failed to load external entity/i', $msg)) {
                $msg = 'request failed, netForum did not respond to our request, try again.';
            } throw new RuntimeException($msg, $e->getCode(), $e);
        }
    }

    private function beauty_xml($u_e, $response) {
        $sp6e0a4c = $u_e . 'Result';
        if (!isset($response->{$sp6e0a4c}->any)) {
            return $response;
        } libxml_use_internal_errors(true);
        $response = simplexml_load_string($response->{$sp6e0a4c}->any);
        $response = is_object($response) && isset($response->Result) ? $response->Result : $response;
        return sizeof($response) ? $response : array();
    }

    protected function OD() {
        $this->auth();
        if (!isset($this->od)) {
            if (preg_match('/signon/', $this->wsdl)) {
                $wsdl = $this->getWsdlPage('netforumxmlondemand.asmx');
            } else {
                $wsdl = $this->getWsdlPage('netFORUMXMLONDemand.asmx');
            } $this->od = new static($wsdl, $this->wsdl_params);
        } $this->od->token = $this->token;
        $this->od->ssoToken = $this->ssoToken;
        $this->od->cstToken = $this->cstToken;
        return $this->od;
    }

    protected function getWsdlPage($page, $wsdl_e = null) {
        if (is_null($wsdl_e)) {
            $wsdl_e = $this->wsdl;
        } $parse_url_e = parse_url($wsdl_e);
        $parse_url_e['path'] = dirname($parse_url_e['path']) . '/' . $page;
        return http_build_url($wsdl_e, $parse_url_e);
    }

}
