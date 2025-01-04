<?php
/**
 * DomainNameAPI Registrar Module
 * @package    coremio/modules/Registrars/DomainNameAPI
 * @version    1.17.5
 * @since      File available since Release 7.0.0
 * @license    MIT License https://opensource.org/licenses/MIT
 * @link       https://visecp.com/
 * @author     https://visecp.com/
 * @maintainer Bünyamin AKÇAY<bunyamin@bunyam.in>
 */

class DomainNameAPI {
    public  $api     = false;
    public  $config  = [];
    public  $lang    = [];
    public  $error   = NULL;
    public  $whidden = [];
    private $order   = [];
    private $username, $password, $tmode;
    private $domainCacheTTL= 1024;

    const DEFAULT_CACHE_TTL = 3600;
    const CACHE_KEY_PREFIX = 'DNA-';
    const CACHE_TABLE = 'mod_dna_cache_elements';
    const QUERY_CACHE_TTL = 300;

    function __construct($external = []) {

        $this->config = Modules::Config("Registrars", __CLASS__);
        $this->lang   = Modules::Lang("Registrars", __CLASS__);
        if (is_array($external) && sizeof($external) > 0)
            $this->config = array_merge($this->config, $external);
        if (!isset($this->config["settings"]["username"]) || !isset($this->config["settings"]["password"])) {
            $this->error = $this->lang["error1"];
            return false;
        }


        if (!class_exists("\DomainNameApi\DomainNameAPI_PHPLibrary")) {
            include __DIR__ . DS . "api.php";
        }

        if (isset($this->config["settings"]["whidden-amount"])) {
            $whidden_amount            = $this->config["settings"]["whidden-amount"];
            $whidden_currency          = $this->config["settings"]["whidden-currency"];
            $this->whidden["amount"]   = $whidden_amount;
            $this->whidden["currency"] = $whidden_currency;
        }

        $username = $this->config["settings"]["username"];
        $password = $this->config["settings"]["password"];
        $password = Crypt::decode($password, Config::get("crypt/system"));
        $tmode    = (bool)$this->config["settings"]["test-mode"];
        
        if (isset($this->config["settings"]["dom-cache-ttl"])) {
            $this->domainCacheTTL      = $this->config["settings"]["dom-cache-ttl"];
        }

        $this->username = $username;
        $this->password = $password;
        $this->tmode    = $tmode;

    }

    /**
     * Set credentials
     * @return bool
     * @throws SoapFault
     */
    private function set_credentials()
    {
        if ($this->api instanceof \DomainNameApi\DomainNameAPI_PHPLibrary) {
            return $this->api;
        }
        $this->api = new \DomainNameApi\DomainNameAPI_PHPLibrary($this->username, $this->password);
    }


    public function set_order($order = []) {
        $this->order = $order;
        return $this;
    }

    /**
     * Set config
     * @return string
     */
    private function setConfig($username, $password, $tmode) {
        $this->config["settings"]["username"]  = $username;
        $this->config["settings"]["password"]  = $password;
        //$this->config["settings"]["test-mode"] = $tmode;

        $this->api = new \DomainNameApi\DomainNameAPI_PHPLibrary($username, $password);
    }

    /**
     * Test connection
     * @param $config
     * @return bool
     */
    public function testConnection($config = []) {
        $username = $config["settings"]["username"];
        $password = $config["settings"]["password"];

        if (!$username || !$password) {
            $this->error = $this->lang["error8"];
            return false;
        }

        $password = Crypt::decode($password, Config::get("crypt/system"));
        $tmode    = false;
        $this->setConfig($username, $password, $tmode);

            $check   = $this->api->GetResellerDetails();

            if($check["result"] != "OK"){
                $this->error = $check["error"]["Details"];
            return false;
            }

        return true;
    }

    /**
     * Get TLDs
     * @return array
     */
    public function questioning($sld = NULL, $tlds = []) {
        $this->set_credentials();
        if ($sld == '' || empty($tlds)) {
            $this->error = $this->lang["error2"];
            return false;
        }

 
        $response = $this->rememberCache("domain_query_".md5(json_encode([$sld, $tlds])),function () use ($sld, $tlds){
            return $this->api->CheckAvailability([$sld], $tlds, 1, "create");
        },self::QUERY_CACHE_TTL);


        $result = [];

        if (is_array($tlds) && count($tlds)) {
            foreach ($tlds as $tld) {
                $result[$tld] = [
                    'status'  => "error",
                    'message' => "something went wrong"
                ];
            }
        }

        foreach ($response as $domain) {
            if (isset($domain['TLD'])) {
                $tld                    = $domain["TLD"];
                $result[$tld]['status'] = $domain["Status"] == "available" ? "available" : "unavailable";
                unset($result[$tld]['message']);

                if (isset($domain["IsFee"]) && $domain["IsFee"] === true) {
                    $result[$tld]['premium']       = true;
                    $result[$tld]['premium_price'] = [
                        'amount'   => number_format($domain["Price"], 2, '.', ''),
                        'currency' => $domain["Currency"],
                    ];
                }
            }
        }



        return $result;

    }


    /**
     * Register domain
     * @param $domain
     * @param $sld
     * @param $tld
     * @param $year
     * @param $dns
     * @param $whois
     * @param $wprivacy
     * @param $tcode
     * @return array|array[]|false|string[]
     */
    public function register($domain = '', $sld = '', $tld = '', $year = 1, $dns = [], $whois = [], $wprivacy = false, $tcode = NULL) {
        $this->set_credentials();
        $detail = $this->api->GetDetails($domain);
        if ($detail["result"] == "OK") {

            if ($detail["data"]["Status"] != "Active"){
                return [
                    'status' => "FAIL",
                    //'message' => $this->lang["error6"],
                ];
            }else{
                return ['config' => ["ID" => $detail["data"]["ID"]]];
            }

        }

        $whois = $this->contactProcess($whois);

        $dns = array_values($dns);

        $additional = [];

        if (substr($domain, -3) == ".tr") {
            $additional['TRABISDOMAINCATEGORY'] = $whois['Registrant']['Company'] ? 0 : 1;
            $additional['TRABISCOUNTRYID']      = $whois['Registrant']['Country'] == "TR" ? 215 : 888;
            $additional['TRABISCOUNTRYNAME']    = $whois['Registrant']['Country'];
            $additional['TRABISCITYNAME']       = $whois['Registrant']['City'];
            $additional['TRABISCITIYID']        = 888;

            $user = User::getInfo($this->order["owner_id"], [
                'identity',
                'company_tax_number',
                'company_tax_office'
            ]);

            $identity     = "11111111111";
            $name_surname = $whois["Registrant"]['FirstName'] . ' ' . $whois["Registrant"]['LastName'];
            $tax_number   = '1111111111';
            $tax_office   = 'Bilinmiyor';


            if ($user) {
                if ($user['identity'])
                    $identity = $user['identity'];
                if ($user["company_tax_office"])
                    $tax_office = $user["company_tax_office"];
                if ($user["company_tax_number"])
                    $tax_number = $user["company_tax_number"];
            }

            if ($whois['Registrant']['Company']) {
                $additional['TRABISORGANIZATION'] = $whois["Registrant"]["Company"];
                $additional['TRABISTAXOFFICE']    = $tax_office;
                $additional['TRABISTAXNUMBER']    = $tax_number;
            } else {
                $additional['TRABISCITIZIENID']  = $identity;
                $additional['TRABISNAMESURNAME'] = $name_surname;
            }

        }


        Modules::save_log("Registrars", __CLASS__, "PreRegister", [
            'domain'   => $domain,
            'year'     => $year,
            'start_at' => date('Y-m-d H:i:s'),
        ], []);

        $response = $this->api->RegisterWithContactInfo($domain, $year, $whois, $dns, false, $wprivacy, $additional);

        Modules::save_log("Registrars", __CLASS__, "Register", [
            'domain'    => $domain,
            'year'      => $year,
            'whois'     => $whois,
            'finish_at' => date('Y-m-d H:i:s'),
        ], $response);


        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];

            if (stristr($this->error, 'ERR_INVALID_ORGANIZATION'))
                $this->error = $this->lang["error11"];
            return false;
        }

        $status  = "SUCCESS";
        $message = NULL;

        if($response["data"]["Status"] == "waitingfordocument"){
            $status  = "FAIL";
            $message = $this->lang["error12"];
        }


        return [
            'status'  => $status,
            'message' => $message,
            'config'  => [
                'ID' => $response["data"]["ID"],
            ],
        ];

    }

    /**
     * Renew domain
     * @param $params
     * @param $domain
     * @param $sld
     * @param $tld
     * @param $year
     * @param $oduedate
     * @param $nduedate
     * @return bool
     */
    public function renewal($params = [], $domain = '', $sld = '', $tld = '', $year = 1, $oduedate = '', $nduedate = '') {
        $this->set_credentials();
        $OrderDetails = $this->api->getDetails($domain);
        if ($OrderDetails["result"] != "OK") {
            $this->error = $OrderDetails["error"]["Details"];
            return false;
        }

        $handle = $this->api->Renew($domain, $year);

        Modules::save_log("Registrars", __CLASS__, ucfirst(__FUNCTION__), [
            'domain'    => $domain,
            'year'      => $year,
        ], $handle);

        if ($handle["result"] != "OK") {
            $this->error = $handle["error"]["Details"];
            return false;
        }

        return true;
    }


    /**
     * Transfer domain
     * @param $domain
     * @param $sld
     * @param $tld
     * @param $year
     * @param $dns
     * @param $whois
     * @param $wprivacy
     * @param $tcode
     * @return array|false
     */
    public function transfer($domain = '', $sld = '', $tld = '', $year = 1, $dns = [], $whois = [], $wprivacy = false, $tcode = '') {
        $this->set_credentials();

        $response = $this->api->Transfer($domain, $tcode, $year);

        Modules::save_log("Registrars", __CLASS__, ucfirst(__FUNCTION__), [
            'domain'    => $domain,
            'year'      => $year,
        ], $response);


        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Details"];
            return false;
        }

        $this->ModifyDns(['domain' => $domain], $dns);
        $this->ModifyWhois(['domain' => $domain], $whois);

        $returnData = [
            'status'        => "SUCCESS",
            'message'       => NULL,
            'config'        => [
                'ID' => $response["data"]["ID"],
            ],
            'creation_info' => $response,
        ];

        if ($wprivacy) {
            $returnData["whois_privacy"] = [
                'status'  => $this->purchasePrivacyProtection(['domain' => $domain]),
                'message' => $this->error,
            ];
        }

        return $returnData;
    }

    /**
     * Nameserver Details
     * @param $params
     * @return array|false
     */
    public function NsDetails($params = []) {
        $this->set_credentials();
        $domain       = trim($params["domain"]);
        $domainCacheKey = "dom_".$domain;
        $domainDetail =$this->rememberCache($domainCacheKey,function () use ($domain){
            return $this->api->getDetails($domain);
        },$this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $returns = [];

        foreach (range(0,5) as $k => $v) {
            if (isset($domainDetail["data"]["[NameServers"][$k])) {
                $returns["ns" . ($k + 1)] = $domainDetail["data"]["[NameServers"][$k];
            }
        }

        return $returns;
    }

    /**
     * Modify Nameservers
     * @param $params
     * @param $dns
     * @return bool
     */
    public function ModifyDns($params = [], $dns = [])
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);

        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $new_dns = [];

        if ($dns) {
            $dns = array_values($dns);
            foreach ($dns as $k => $dn) {
                $new_dns["ns" . ($k + 1)] = $dn;
            }
        }

        $modifyDns = $this->api->ModifyNameServer($domain, $new_dns);
        if ($modifyDns["result"] != "OK") {
            $this->error = $modifyDns["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);

        return true;
    }


    /**
     * Child Nameserver Details
     * @param array $params
     * @return bool
     */
    public function CNSList($params = []) {
        $this->set_credentials();
        $domain       = trim($params["domain"]);
        $domainCacheKey = "dom_".$domain;
        $domainDetail =$this->rememberCache($domainCacheKey,function () use ($domain){
            return $this->api->getDetails($domain);
        },$this->domainCacheTTL);

        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $childNsList = $domainDetail["data"]["ChildNameServers"] ? $domainDetail["data"]["ChildNameServers"] : [];

        if ($childNsList) {
            $result = [];
            $i      = 0;
            foreach ($childNsList as $v) {
                $i          += 1;
                $result[$i] = [
                    'ns' => $v["ns"],
                    'ip' => $v["ip"]
                ];
            }
            return $result;
        } else {
            return [];
        }
    }


    /**
     * Add Child Nameserver
     * @param $params
     * @param $ns
     * @param $ip
     * @return array|false|string[]
     */
    public function addCNS($params = [], $ns = '', $ip = '')
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $addCNS = $this->api->AddChildNameServer($domain, $ns, $ip);
        if ($addCNS["result"] != "OK") {
            $this->error = $addCNS["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);

        return [
            'ns' => $ns,
            'ip' => $ip
        ];
    }

    /**
     * Modify Child Nameserver
     * @param $params
     * @param $cns
     * @param $ns
     * @param $ip
     * @return bool
     */
    public function ModifyCNS($params = [], $cns = [], $ns = '', $ip = '') {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail =$this->rememberCache($domainCacheKey,function () use ($domain){
            return $this->api->getDetails($domain);
        },$this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return true;
        }

        if ($cns["ip"] != $ip) {
            $cns_ns = $cns["ns"];
            $modify = $this->api->ModifyChildNameServer($domain, $cns_ns, $ip);
            if ($modify["result"] != "OK") {
                $this->error = $modify["error"]["Details"];
                return false;
            }
        }
        $this->invalidateCache($domainCacheKey);

        return true;
    }


    /**
     * Delete Child Nameserver
     * @param $params
     * @param $cns
     * @param $ip
     * @return bool
     */
    public function DeleteCNS($params = [], $cns = '', $ip = '')
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $delete = $this->api->DeleteChildNameServer($domain, $cns);
        if ($delete["result"] != "OK") {
            $this->error = $delete["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);

        return true;
    }

    /**
     * Get Whois Privacy
     * @param array $params
     * @return string
     */
    public function getWhoisPrivacy($params = [])
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        return $domainDetail["data"]["PrivacyProtectionStatus"] ? "active" : "passive";
    }

    /**
     * Modify Whois Privacy
     * @param $params
     * @param $whois
     * @return bool
     */
    public function ModifyWhois($params = [], $whois = [])
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return true;
        }

        $modifyContact = $this->api->SaveContacts($domain, $this->contactProcess($whois));
        if ($modifyContact["result"] != "OK") {
            $this->error = $modifyContact["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);
        $this->invalidateCache('cnt_' . $domainCacheKey);
        return true;
    }

    /**
     * Set Contact Details
     * @param array $data
     * @param string $type
     * @return array
     */
    public function contactProcess($data = [], $type = 'Contact') {
        $this->set_credentials();

        $formatWhoisData = function ($v) {
            $whois_arr= [
                "FirstName"        => $v["FirstName"],
                "LastName"         => $v["LastName"],
                "Company"          => $v["Company"],
                "EMail"            => $v["EMail"],
                "AddressLine1"     => $v["AddressLine1"],
                "State"            => $v["State"],
                "City"             => $v["City"],
                "Country"          => $v["Country"],
                "Fax"              => $v["Fax"],
                "FaxCountryCode"   => $v["FaxCountryCode"],
                "Phone"            => $v["Phone"],
                "PhoneCountryCode" => $v["PhoneCountryCode"],
                "Type"             => 'Contact',
                "ZipCode"          => $v["ZipCode"],
                "Status"           => '',
            ];

            if(strlen(trim($whois_arr["FirstName"])) == 0){
                $whois_arr["FirstName"]= 'Isimyok' ;
            }
            if(strlen(trim($whois_arr["LastName"])) == 0){
                $whois_arr["LastName"]= $whois_arr["FirstName"] ;
            }
            if(strlen(trim($whois_arr["AddressLine1"])) == 0){
                $whois_arr["AddressLine1"]= 'Addres yok' ;
            }
            if(strlen(trim($whois_arr["City"])) == 0){
                $whois_arr["City"]= 'ISTANBUL' ;
            }
            if(strlen(trim($whois_arr["Country"])) == 0){
                $whois_arr["Country"]= 'TR' ;
            }
            if(strlen(trim($whois_arr["ZipCode"])) == 0){
                $whois_arr["ZipCode"]= '34000' ;
            }
            if(strlen(trim($whois_arr["PhoneCountryCode"])) == 0){
                $whois_arr["PhoneCountryCode"]= '90' ;
            }
            if(strlen(trim($whois_arr["Phone"])) == 0){
                $whois_arr["Phone"]= '5555555555' ;
            }


            return $whois_arr;
        };

        if (isset($data["registrant"])) {
            $whois_data = [];
            foreach ($data as $k => $v) {
                $whois_data[ucfirst($k)] = $formatWhoisData($v);
            }
            return $whois_data;
        } else {
            $whois_data = $formatWhoisData($data);
            return [
                "Administrative" => $whois_data,
                "Billing"        => $whois_data,
                "Technical"      => $whois_data,
                "Registrant"     => $whois_data,
            ];
        }

    }


    /**
     * Get Transfer Lock
     * @param $params
     * @return array
     */
    public function getTransferLock($params = [])
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        return $domainDetail["data"]["LockStatus"] == "true";
    }

    /**
     * Check Active
     * @param $params
     * @return bool
     */
    public function isInactive($params = [])
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }
        return $domainDetail["data"]["Status"] != "Active";
    }

    /**
     * Modify Transfer Lock
     * @param $params
     * @param $type
     * @return bool
     */
    public function ModifyTransferLock($params = [], $type = '')
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $modify = $type == "enable" ? $this->api->EnableTheftProtectionLock($domain) : $this->api->DisableTheftProtectionLock($domain);

        if ($modify["result"] != "OK") {
            $this->error = $modify["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);

        return true;
    }


    /**
     * Modify Privacy Protection
     * @param $params
     * @param $staus
     * @return bool
     */
    public function modifyPrivacyProtection($params = [], $staus = false)
    {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $modify = $this->api->ModifyPrivacyProtectionStatus($domain, $staus);
        if ($modify["result"] != "OK") {
            $this->error = $modify["error"]["Details"];
            return false;
        }
        $this->invalidateCache($domainCacheKey);

        return true;
    }

    /**
     * Enable Privacy Protection
     * @param $params
     * @return bool
     */
    public function purchasePrivacyProtection($params = []) {
        return $this->modifyPrivacyProtection($params, true);
    }

    public function getAuthCode($params = []) {
        $this->set_credentials();
        $domain       = trim($params["domain"]);
        $domainCacheKey = "dom_".$domain;
        $domainDetail =$this->rememberCache($domainCacheKey,function () use ($domain){
            return $this->api->getDetails($domain);
        },$this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }
        return $domainDetail["data"]["AuthCode"];
    }

    public function sync($params = []) {
        $this->set_credentials();
        $domain       = $params["domain"];
        $OrderDetails = $this->api->getDetails($domain);
        if ($OrderDetails["result"] != "OK") {
            $this->error = $OrderDetails["error"]["Details"];
            return false;
        }

        $endtime       = isset($OrderDetails["data"]["Dates"]["Expiration"]) ? DateManager::format("Y-m-d", $OrderDetails["data"]["Dates"]["Expiration"]) : false;
        $currentstatus = $OrderDetails["data"]["Status"] ?? false;

        $return_data = [
            'status' => 'unknown',
        ];

        if ($endtime) {
            $return_data["endtime"] = $endtime;
        }

        if ($currentstatus == "Active") {
            $return_data["status"] = "active";
        } elseif ($currentstatus == "Expired") {
            $return_data["status"] = "expired";
        }

        return $return_data;
    }

    public function transfer_sync($params = []) {
        $this->set_credentials();
        $domain       = $params["domain"];
        $OrderDetails = $this->api->getDetails($domain);
        if ($OrderDetails["result"] != "OK") {
            $this->error = $OrderDetails["error"]["Details"];
            return false;
        }

        $endtime       = isset($OrderDetails["data"]["Dates"]["Expiration"]) ? DateManager::format("Y-m-d", $OrderDetails["data"]["Dates"]["Expiration"]) : false;
        $currentstatus = $OrderDetails["data"]["Status"] ?? false;

        $return_data = [
            'status' => 'unknown',
        ];

        if ($endtime)
            $return_data["endtime"] = $endtime;

        if ($currentstatus == "Active") {
            $dns = [];
/*
            if (isset($params["ns1"]) && $params["ns1"]) {
                $dns[] = $params["ns1"];
            }
            if (isset($params["ns2"]) && $params["ns2"]) {
                $dns[] = $params["ns2"];
            }
            if (isset($params["ns3"]) && $params["ns3"]) {
                $dns[] = $params["ns3"];
            }
            if (isset($params["ns4"]) && $params["ns4"]) {
                $dns[] = $params["ns4"];
            }

            $this->ModifyDns(['domain' => $domain], $dns);
            $this->ModifyWhois(['domain' => $domain], $params["whois"]);
*/

            $return_data["status"] = "active";
        } elseif ($currentstatus == "Expired") {
            $return_data["status"] = "expired";
        }

        return $return_data;
    }

    public function get_info($params = []) {
        $this->set_credentials();
        $domain         = trim($params["domain"]);
        $domainCacheKey = "dom_" . $domain;
        $domainDetail   = $this->rememberCache($domainCacheKey, function () use ($domain) {
            return $this->api->getDetails($domain);
        }, $this->domainCacheTTL);
        if ($domainDetail["result"] != "OK") {
            $this->invalidateCache($domainCacheKey);
            $this->error = $domainDetail["error"]["Details"];
            return false;
        }

        $data = $domainDetail["data"];

        $result = [];

        $cdate   = DateManager::format("Y-m-d H:i:s", $data["Dates"]["Start"]);
        $duedate = DateManager::format("Y-m-d H:i:s", $data["Dates"]["Expiration"]);

        $wprivacy = $data["PrivacyProtectionStatus"];

        $nameservers = isset($data["NameServers"][0]) ? $data["NameServers"] : [];

        $ns1      = $nameservers[0] ?? false;
        $ns2      = $nameservers[1] ?? false;
        $ns3      = $nameservers[2] ?? false;
        $ns4      = $nameservers[3] ?? false;


         $contacts  =$this->rememberCache('cnt_'.$domainCacheKey,function () use ($domain){
            return $this->api->GetContacts($domain);
        },$this->domainCacheTTL);

        $whois_data = $contacts["data"]["contacts"];

        if ($whois_data) {
            $types = [
                'Registrant',
                'Administrative',
                'Technical',
                'Billing',
            ];

            $whois = [];

            foreach ($types as $ct) {
                $s_k = strtolower($ct);

                $w_data = $whois_data[$ct] ?? $whois_data["Registrant"];

                if (!$w_data["Address"]["State"])
                    $w_data["Address"]["State"] = 'N/A';

                $whois[$s_k] = [
                    'FirstName'        => $w_data["FirstName"],
                    'LastName'         => $w_data["LastName"],
                    'Name'             => $w_data["FirstName"] . ($w_data["LastName"] ? ' ' : '') . $w_data["LastName"],
                    'Company'          => $w_data["Company"],
                    'EMail'            => $w_data["EMail"],
                    'AddressLine1'     => $w_data["Address"]["Line1"],
                    'City'             => $w_data["Address"]["City"],
                    'State'            => $w_data["Address"]["State"],
                    'ZipCode'          => $w_data["Address"]["ZipCode"],
                    'Country'          => $w_data["Address"]["Country"],
                    'PhoneCountryCode' => $w_data["Phone"]["Phone"]["CountryCode"],
                    'Phone'            => $w_data["Phone"]["Phone"]["Number"],
                    'FaxCountryCode'   => $w_data["Phone"]["Fax"]["CountryCode"] ?? "",
                    'Fax'              => $w_data["Phone"]["Fax"]["Number"] ?? "",
                ];
            }
        }

        $result["whois_privacy"] = ['status' => $wprivacy ? "enable" : "disable"];

        if ($cdate)
            $result["creation_time"] = $cdate;
        if ($duedate)
            $result["end_time"] = $duedate;

        if (isset($ns1) && $ns1)
            $result["ns1"] = $ns1;
        if (isset($ns2) && $ns2)
            $result["ns2"] = $ns2;
        if (isset($ns3) && $ns3)
            $result["ns3"] = $ns3;
        if (isset($ns4) && $ns4)
            $result["ns4"] = $ns4;
        if (isset($whois) && $whois)
            $result["whois"] = $whois;

        $result["transferlock"] = $data["LockStatus"] == "true";

        return $result;

    }

    /**
     * Get Domain List , deprecated!
     * @param $test
     * @return array|bool
     * @throws SoapFault
     */
    public function domains($test = false) {
        if (!$test)
            $this->set_credentials();
        Helper::Load(["User"]);

        $response = $this->api->GetList();
        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];
            return false;
        }
        if ($test)
            return true;

        $result = [];

        if (isset($response["data"]["Domains"]) && $response["data"]["Domains"]) {
            foreach ($response["data"]["Domains"] as $res) {
                $cdate  = isset($res["Dates"]["Start"]) ? DateManager::format("Y-m-d H:i", $res["Dates"]["Start"]) : '';
                $edate  = isset($res["Dates"]["Expiration"]) ? DateManager::format("Y-m-d H:i", $res["Dates"]["Expiration"]) : '';
                $domain = $res["DomainName"] ?? '';
                if ($domain) {
                    $order_id    = 0;
                    $user_data   = [];
                    $is_imported = Models::$init->db->select("id,owner_id AS user_id")
                                                    ->from("users_products");
                    $is_imported->where("type", '=', "domain", "&&");
                    $is_imported->where("options", 'LIKE', '%"domain":"' . $domain . '"%');
                    $is_imported = $is_imported->build() ? $is_imported->getAssoc() : false;
                    if ($is_imported) {
                        $order_id  = $is_imported["id"];
                        $user_data = User::getData($is_imported["user_id"], "id,full_name,company_name", "array");
                    }

                    if ($res["Status"] == "Active")
                        $result[] = [
                            'domain'        => $domain,
                            'creation_date' => $cdate,
                            'end_date'      => $edate,
                            'order_id'      => $order_id,
                            'user_data'     => $user_data,
                        ];
                }
            }
        }
        return $result;
    }

    public function domainsdt($pageNumber, $pageLength,$search,$invalidation=0) {
        $this->set_credentials();
        Helper::Load(["User"]);

       if ($invalidation==1){
           $this->invalidateCache(["domainsdt","user_info_short_"]);
       }

        $listParams = [
            'PageNumber' => $pageNumber,
            'PageSize'   => $pageLength,
            'OrderColumn'=>'Id',
            'OrderDirection'=>'DESC',
        ];

        if(strlen($search)>1){
            $listParams['DomainName']=$search;
        }

        $response = $this->rememberCache("domainsdt_" . $pageNumber . "_" . $pageLength.'_'.md5($search), function () use ($listParams) {
            return $this->api->GetList($listParams);
        }, 180);


        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];
            return false;
        }

        $result    = [
            'data'  => [],
            'total' => 0,
        ];
        $user_data = [];

        if (isset($response["data"]["Domains"]) && $response["data"]["Domains"]) {

            $result['total'] = $response["TotalCount"];

            foreach ($response["data"]["Domains"] as $res) {
                $user_data[$res["DomainName"]] = [];

                $user_data[$res["DomainName"]]['user_info'] = $this->rememberCache("user_info_short_" . $res["DomainName"],
                    function () use ($res) {
                        $is_imported = Models::$init->db->select("id,owner_id AS user_id")
                                                        ->from("users_products");
                        $is_imported->where("type", '=', "domain", "&&");
                        $is_imported->where("options", 'LIKE', '%"domain":"' . $res["DomainName"] . '"%');
                        $is_imported = $is_imported->build() ? $is_imported->getAssoc() : false;
                        if ($is_imported) {
                            $user_data= User::getData($is_imported["user_id"], "id,full_name,company_name", "array");
                            $user_data['order_id']=$is_imported["id"];
                            return $user_data;
                        }
                        return [];
                    },180);
            }


            foreach ($response["data"]["Domains"] as $res) {
                $cdate  = isset($res["Dates"]["Start"]) ? DateManager::format("Y-m-d H:i", $res["Dates"]["Start"]) : '';
                $edate  = isset($res["Dates"]["Expiration"]) ? DateManager::format("Y-m-d H:i", $res["Dates"]["Expiration"]) : '';
                $domain = $res["DomainName"] ?? '';
                if ($domain) {
                    $order_id = $user_data[$domain]['user_info']['order_id'] ?? 0;
                    if ($res["Status"] == "Active")
                        $result['data'][] = [
                            'domain'        => $domain,
                            'creation_date' => $cdate,
                            'end_date'      => $edate,
                            'order_id'      => $order_id,
                            'user_data'     => $user_data[$domain]['user_info'],
                        ];
                }
            }
        }
        return $result;
    }

    public function import_domain($data = []) {
        $this->set_credentials();
        $config = $this->config;

        $imports = $results = [];

        Helper::Load([
            "Orders",
            "Products",
            "Money"
        ]);

        foreach ($data as $domain => $datum) {

            $domain_parse = Utility::domain_parser("http://" . $domain);
            $sld          = $domain_parse["host"];
            $tld          = $domain_parse["tld"];
            $user_id      = (int)$datum["user_id"];
            if (!$user_id){
                continue;
            }

            $info = $this->get_info([
                'domain' => $domain,
                'name'   => $sld,
                'tld'    => $tld,
            ]);

            if (!$info){
                $results[$domain] = 'domain info not found';
                continue;
            }


            $user_data = User::getData($user_id, "id,lang", "array");
            $ulang     = $user_data["lang"];
            $locallang = Config::get("general/local");
            $productID = Models::$init->db->select("id")
                                          ->from("tldlist")
                                          ->where("name", "=", $tld);

            $productID = $productID->build() ? $productID->getObject()->id : false;
            if (!$productID)
                continue;
            $productPrice     = Products::get_price("register", "tld", $productID);
            $productPrice_amt = $productPrice["amount"];
            $productPrice_cid = $productPrice["cid"];
            $start_date       = $info["creation_time"];
            $end_date         = $info["end_time"];

            $options = [
                "established"      => true,
                "group_name"       => Bootstrap::$lang->get_cm("website/account_products/product-type-names/domain", false, $ulang),
                "local_group_name" => Bootstrap::$lang->get_cm("website/account_products/product-type-names/domain", false, $locallang),
                "category_id"      => 0,
                "domain"           => $domain,
                "name"             => $sld,
                "tld"              => $tld,
                "dns_manage"       => true,
                "whois_manage"     => true,
                "transferlock"     => $info["transferlock"],
                "cns_list"         => $info["cns"] ?? [],
                "whois"            => $info["whois"] ?? [],
            ];

            if (isset($info["whois_privacy"]) && $info["whois_privacy"]) {
                $options["whois_privacy"] = $info["whois_privacy"]["status"] == "enable";
                $wprivacy_endtime         = DateManager::ata();
                if (isset($info["whois_privacy"]["end_time"]) && $info["whois_privacy"]["end_time"]) {
                    $wprivacy_endtime                 = $info["whois_privacy"]["end_time"];
                    $options["whois_privacy_endtime"] = $wprivacy_endtime;
                }
            }

            if (isset($info["ns1"]) && $info["ns1"]) {
                $options["ns1"] = $info["ns1"];
            }
            if (isset($info["ns2"]) && $info["ns2"]) {
                $options["ns2"] = $info["ns2"];
            }
            if (isset($info["ns3"]) && $info["ns3"]) {
                $options["ns3"] = $info["ns3"];
            }
            if (isset($info["ns4"]) && $info["ns4"]) {
                $options["ns4"] = $info["ns4"];
            }


            $order_data = [
                "owner_id"     => (int)$user_id,
                "type"         => "domain",
                "product_id"   => (int)$productID,
                "name"         => $domain,
                "period"       => "year",
                "period_time"  => 1,
                "amount"       => (float)$productPrice_amt,
                "total_amount" => (float)$productPrice_amt,
                "amount_cid"   => (int)$productPrice_cid,
                "status"       => "active",
                "cdate"        => $start_date,
                "duedate"      => $end_date,
                "renewaldate"  => DateManager::Now(),
                "module"       => $config["meta"]["name"],
                "options"      => Utility::jencode($options),
                "unread"       => 1,
            ];

            $insert = Orders::insert($order_data);
            if (!$insert){
                $results[$domain] = 'order insert failed';
                continue;
            }

            $imports[] = $order_data["name"] . " (#" . $insert . ")";
        }

        if ($imports) {
            $adata = UserManager::LoginData("admin");
            User::addAction($adata["id"], "alteration", "domain-imported", [
                'module'   => $config["meta"]["name"],
                'imported' => implode(", ", $imports),
            ]);
        }

        return $results;
    }

    public function cost_prices($type = 'domain')
    {
        $this->set_credentials();
        $config = $this->config;
        if (!isset($this->config["settings"]["adp"]) || !$this->config["settings"]["adp"]) {
            return false;
        }

        $response = $this->rememberCache("tld_list", function () {
            return $this->api->GetTldList(999);
        }, 180);


        if ($response["result"] != "OK" && isset($response["error"]["Details"]) && strlen($response["error"]["Details"]) >= 3) {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];
            return false;
        }

        $result = [];

        $excluded_tlds = $config['settings']['exclude'] ? explode(',', $config['settings']['exclude']) : [];

        foreach ($response["data"] as $row) {
            if ($row["status"] != "Active") {
                continue;
            }
            if (in_array($row["tld"], $excluded_tlds)) {
                continue;
            }

            if (!isset($row["pricing"]["registration"][1])) {
                continue;
            }

            $result[$row["tld"]] = [
                'register' => number_format(($row["pricing"]["registration"][1] ?? 0), 2, '.', ''),
                'transfer' => number_format(($row["pricing"]["transfer"][1] ?? 0), 2, '.', ''),
                'renewal'  => number_format(($row["pricing"]["renew"][1] ?? 0), 2, '.', ''),
            ];
        }

        return $result;
    }

    public function list_tlds() {
        $this->set_credentials();
        $config = $this->config;

        $response = $this->rememberCache("tld_list", function () {
            return $this->api->GetTldList(999);
        }, 180);


        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];
            return false;
        }

        Helper::Load([
            "Products",
            "Money"
        ]);

        $cost_cid    =  4;
        $profit_rate = Config::get("options/domain-profit-rate");
        $excluded_tlds = $config['settings']['exclude'] ? explode(',',$config['settings']['exclude']) : [];

        $tld_arr = [];


        foreach ($response["data"] as $row) {


            if ($row["status"] != "Active")
                continue;
            if (!isset($row["pricing"]["registration"][1]))
                continue;
            $name = Utility::strtolower(trim($row["tld"]));

            $tld_obj = [
                'tld'                     => $name,
                'module'                  => null,
                'register_cost'           => number_format($row["pricing"]["registration"][1], 2),
                'renewal_cost'            => number_format($row["pricing"]["renew"][1], 2),
                'transfer_cost'           => number_format($row["pricing"]["transfer"][1], 2),
                'register_current'        => null,
                'renewal_current'         => null,
                'transfer_current'        => null,
                'register_margin'         => null,
                'renewal_margin'          => null,
                'transfer_margin'         => null,
                'register_margin_percent' => null,
                'renewal_margin_percent'  => null,
                'transfer_margin_percent' => null,
            ];


            $check = Models::$init->db->select()->from("tldlist")->where("name", "=", $name);

            if ($check->build()) {
                $tld = $check->getAssoc();
                $pid = $tld["id"];

                $tld_obj['module'] = $tld["module"];

                $reg_price = Products::get_price("register", "tld", $pid);
                $ren_price = Products::get_price("renewal", "tld", $pid);
                $tra_price = Products::get_price("transfer", "tld", $pid);

                //currency_id
                $reg_currency = $reg_price["cid"];
                $ren_currency = $ren_price["cid"];
                $tra_currency = $tra_price["cid"];
                //amount
                $reg_amount= $reg_price["amount"];
                $ren_amount= $ren_price["amount"];
                $tra_amount= $tra_price["amount"];

                $tld_obj['register_current'] = number_format((Money::exChange($reg_amount, $reg_currency, $cost_cid)),2);
                $tld_obj['renewal_current'] = number_format((Money::exChange($ren_amount, $ren_currency, $cost_cid)),2);
                $tld_obj['transfer_current'] = number_format((Money::exChange($tra_amount, $tra_currency, $cost_cid)),2);

            }

            //calculate margin between current and cost as percentage
            if($tld_obj['register_current']!==null && $tld_obj['register_cost']<>0){
                $tld_obj['register_margin'] = floatval(number_format(($tld_obj['register_current'] - $tld_obj['register_cost']),2));
                $tld_obj['register_margin_percent'] = intval(number_format((($tld_obj['register_margin'] / $tld_obj['register_cost']) * 100),0));
            }
            if($tld_obj['renewal_current']!==null && $tld_obj['renewal_cost']<>0){
                $tld_obj['renewal_margin'] = floatval(number_format(($tld_obj['renewal_current'] - $tld_obj['renewal_cost']),2));
                $tld_obj['renewal_margin_percent'] = intval(number_format((($tld_obj['renewal_margin'] / $tld_obj['renewal_cost']) * 100),0));
            }
            if($tld_obj['transfer_current']!==null && $tld_obj['transfer_cost']<>0){
                $tld_obj['transfer_margin'] = floatval(number_format(($tld_obj['transfer_current'] - $tld_obj['transfer_cost']),2));
                $tld_obj['transfer_margin_percent'] =intval(number_format(( ($tld_obj['transfer_margin'] / $tld_obj['transfer_cost']) * 100),0));
            }

            $tld_obj['excluded'] =in_array($name,$excluded_tlds);

            $tld_arr[] = $tld_obj;


        }

        return ['tlds'          => $tld_arr];
    }

    public function apply_import_tlds($selected_tlds=[]) {
        $this->set_credentials();

        $response = $this->rememberCache("tld_list", function () {
            return $this->api->GetTldList(999);
        }, 180);
        if ($response["result"] != "OK") {
            $this->error = $response["error"]["Message"] . " : " . $response["error"]["Details"];
            return false;
        }

        Helper::Load([
            "Products",
            "Money"
        ]);

        $cost_cid    = isset($this->config["settings"]["cost-currency"]) ? $this->config["settings"]["cost-currency"] : 4;
        $profit_rate = Config::get("options/domain-profit-rate");

        foreach ($response["data"] as $row) {
            if(is_array($selected_tlds) && count($selected_tlds)>0){
                if($row["tld"]!='' && !in_array($row["tld"],$selected_tlds)){
                    continue;
                }
            }

            if ($row["status"] != "Active"){
                continue;
            }

            if (!isset($row["pricing"]["registration"][1])){
                continue;
            }

            $name = Utility::strtolower(trim($row["tld"]));


            $api_cost_prices = [
                'register' => number_format(($row["pricing"]["registration"][1] ?? 0), 2, '.', ''),
                'transfer' => number_format(($row["pricing"]["transfer"][1] ?? 0), 2, '.', ''),
                'renewal'  => number_format(($row["pricing"]["renew"][1] ?? 0), 2, '.', ''),
            ];

            $paperwork     = $row["IsDocumentRequired"] ? 1 : 0;
            $epp_code      = $row["IsTransferable"] ? 1 : 0;
            $dns_manage    = 1;
            $whois_privacy = 1;
            $module        = "DomainNameAPI";

            $check = Models::$init->db->select()
                                      ->from("tldlist")
                                      ->where("name", "=", $name);

            if ($check->build()) {
                $tld = $check->getAssoc();
                $pid = $tld["id"];

                $reg_price = Products::get_price("register", "tld", $pid);
                $ren_price = Products::get_price("renewal", "tld", $pid);
                $tra_price = Products::get_price("transfer", "tld", $pid);

                $tld_cid = $reg_price["cid"];


                $register_cost = Money::deformatter($api_cost_prices["register"]);
                $renewal_cost  = Money::deformatter($api_cost_prices["renewal"]);
                $transfer_cost = Money::deformatter($api_cost_prices["transfer"]);

                // ExChanges
                $register_cost = Money::exChange($register_cost, $cost_cid, $tld_cid);
                $renewal_cost  = Money::exChange($renewal_cost, $cost_cid, $tld_cid);
                $transfer_cost = Money::exChange($transfer_cost, $cost_cid, $tld_cid);


                $reg_profit = Money::get_discount_amount($register_cost, $profit_rate);
                $ren_profit = Money::get_discount_amount($renewal_cost, $profit_rate);
                $tra_profit = Money::get_discount_amount($transfer_cost, $profit_rate);

                $register_sale = $register_cost + $reg_profit;
                $renewal_sale  = $renewal_cost + $ren_profit;
                $transfer_sale = $transfer_cost + $tra_profit;

                Products::set("domain", $pid, [
                    'paperwork'     => $paperwork,
                    'epp_code'      => $epp_code,
                    'dns_manage'    => $dns_manage,
                    'whois_privacy' => $whois_privacy,
                    'register_cost' => $register_cost,
                    'renewal_cost'  => $renewal_cost,
                    'transfer_cost' => $transfer_cost,
                    'module'        => $module,
                ]);

                Models::$init->db->update("prices", [
                    'amount' => $register_sale,
                    'cid'    => $tld_cid,
                ])
                                 ->where("id", "=", $reg_price["id"])
                                 ->save();


                Models::$init->db->update("prices", [
                    'amount' => $renewal_sale,
                    'cid'    => $tld_cid,
                ])
                                 ->where("id", "=", $ren_price["id"])
                                 ->save();


                Models::$init->db->update("prices", [
                    'amount' => $transfer_sale,
                    'cid'    => $tld_cid,
                ])
                                 ->where("id", "=", $tra_price["id"])
                                 ->save();

            } else {

                $tld_cid = $cost_cid;


                $register_cost = Money::deformatter($api_cost_prices["register"]);
                $renewal_cost  = Money::deformatter($api_cost_prices["renewal"]);
                $transfer_cost = Money::deformatter($api_cost_prices["transfer"]);


                $reg_profit = Money::get_discount_amount($register_cost, $profit_rate);
                $ren_profit = Money::get_discount_amount($renewal_cost, $profit_rate);
                $tra_profit = Money::get_discount_amount($transfer_cost, $profit_rate);

                $register_sale = $register_cost + $reg_profit;
                $renewal_sale  = $renewal_cost + $ren_profit;
                $transfer_sale = $transfer_cost + $tra_profit;

                $insert = Models::$init->db->insert("tldlist", [
                    'status'        => "inactive",
                    'cdate'         => DateManager::Now(),
                    'name'          => $name,
                    'paperwork'     => $paperwork,
                    'epp_code'      => $epp_code,
                    'dns_manage'    => $dns_manage,
                    'whois_privacy' => $whois_privacy,
                    'currency'      => $tld_cid,
                    'register_cost' => $register_cost,
                    'renewal_cost'  => $renewal_cost,
                    'transfer_cost' => $transfer_cost,
                    'module'        => $module,
                ]);

                if ($insert) {
                    $tld_id = Models::$init->db->lastID();

                    Models::$init->db->insert("prices", [
                        'owner'    => "tld",
                        'owner_id' => $tld_id,
                        'type'     => 'register',
                        'amount'   => $register_sale,
                        'cid'      => $tld_cid,
                    ]);


                    Models::$init->db->insert("prices", [
                        'owner'    => "tld",
                        'owner_id' => $tld_id,
                        'type'     => 'renewal',
                        'amount'   => $renewal_sale,
                        'cid'      => $tld_cid,
                    ]);


                    Models::$init->db->insert("prices", [
                        'owner'    => "tld",
                        'owner_id' => $tld_id,
                        'type'     => 'transfer',
                        'amount'   => $transfer_sale,
                        'cid'      => $tld_cid,
                    ]);
                }

            }


        }


        return true;
    }

    public function getDNAUser()
    {
        $this->set_credentials();

        $response =$this->rememberCache("dna_user", function () {
            return $this->api->GetResellerDetails();
        }, 180);

        return $response;
    }


    public function rememberCache($key, $function, $ttl = self::DEFAULT_CACHE_TTL)
    {
        // Güvenli bir hash algoritması kullanın
        $cache_key = self::CACHE_KEY_PREFIX . substr($key, 0, 10) . '_' . hash('sha256', $this->username . $this->password . '-' . $key);

        $cache_object = Models::$init->db->select("name,content,updated_at")
                                         ->from(self::CACHE_TABLE)
                                         ->where("name", '=', $cache_key);

        $cache_object = $cache_object->build() ? $cache_object->getAssoc() : false;

        if (!$cache_object || time() > strtotime($cache_object["updated_at"]) + $ttl) {
            $response = $function();

            if (!isset($cache_object["name"])) {
                Models::$init->db->insert(self::CACHE_TABLE, [
                    'name'       => $cache_key,
                    'content'    => base64_encode(serialize($response)),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                Models::$init->db->update(self::CACHE_TABLE, [
                    'content'    => base64_encode(serialize($response)),
                    'updated_at' => date('Y-m-d H:i:s'),
                ])
                                 ->where("name", '=', $cache_key)
                                 ->save();
            }
        } else {
            $response = unserialize(base64_decode($cache_object["content"]));
        }
        return $response;
    }

    public function invalidateCache($key)
    {
        $invalidations = [];
        if(is_string($key) ){
            $invalidations[] = $key;
        }elseif (is_array($key)){
            $invalidations = $key;
        }else{
            return false;
        }
        if(count($invalidations) == 0){
            return false;
        }

        foreach ($invalidations as $k => $v) {
             $cache_key = "DNA-" . substr($v, 0, 10);
             Models::$init->db->delete("mod_dna_cache_elements")->where("name", "LIKE", "{$cache_key}%")->run();
        }

    }

}
