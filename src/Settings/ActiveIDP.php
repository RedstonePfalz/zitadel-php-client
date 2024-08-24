<?php

namespace ZitadelPhpClient\Settings;

use Exception;

class ActiveIDP
{
    private array $settings;
    private ?string $orgId = null;
    private array $identityProviders;

    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    public function setOrgId(string $orgId):void {
        $this->orgId = $orgId;
    }

    public function get(): array {
        return $this->identityProviders;
    }

    public function sendRequest(): void {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/settings/login/idps?ctx.orgId=" . $this->orgId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        if(isset($response->code)) {
            throw new Exception("Error-Code: " . $response->code . " Message: " . $response->message);
        } else {

            $this->identityProviders = $response->identityProviders;
        }
        curl_close($curl);
    }
}