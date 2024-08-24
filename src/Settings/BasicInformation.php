<?php

namespace ZitadelPhpClient\Settings;

use Exception;

class BasicInformation
{
    private array $settings;
    private string $defaultOrgId;
    private string $defaultLanguage;
    private array $supportedLanguages;

    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    public function getDefaultOrgId(): string {
        return $this->defaultOrgId;
    }

    public function getDefaultLanguage(): string {
        return $this->defaultLanguage;
    }

    public function getSupportedLanguages(): array {
        return $this->supportedLanguages;
    }

    public function sendRequest(): void {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/settings",
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
            $this->defaultOrgId = $response->defaultOrgI ?? "";
            $this->defaultLanguage = $response->defaultLanguage ?? "";
            $this->supportedLanguages = $response->supportedLanguages ?? "";
        }
        curl_close($curl);
    }
}