<?php

namespace ZitadelPhpClient\Settings;

use Exception;

class PasswordComplexity
{
    private array $settings;
    private ?string $orgId = null;
    private ?int $minLength;
    private bool $requiresUppercase;
    private bool $requiresLowercase;
    private bool $requiresNumber;
    private bool $requiresSymbol;
    private ?string $resourceOwnerType;



    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    public function setOrgId(string $orgId):void {
        $this->orgId = $orgId;
    }

    public function getminLength():int {
        return $this->minLength;
    }

    public function requiresUppercase():bool {
        return $this->requiresUppercase;
    }

    public function requiresLowercase():bool {
        return $this->requiresLowercase;
    }

    public function requiresNumber():bool {
        return $this->requiresNumber;
    }

    public function requiresSymbol():bool {
        return $this->requiresSymbol;
    }

    public function getResourceOwnerType():string {
        return $this->resourceOwnerType;
    }

    public function sendRequest(): void {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/settings/password/complexity?ctx.orgId=" . $this->orgId,
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
            $response = $response->settings;
            $this->minLength = $response->minLength ?? null;
            $this->requiresUppercase = $response->requiresUppercase ?? "";
            $this->requiresLowercase = $response->requiresLowercase ?? "";
            $this->requiresNumber = $response->requiresNumber ?? "";
            $this->requiresSymbol = $response->requiresSymbol ?? "";
            $this->resourceOwnerType = $response->resourceOwnerType ?? null;

        }
        curl_close($curl);
    }
}