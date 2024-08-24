<?php

namespace ZitadelPhpClient\Settings;

use Exception;

class LoginSettings
{
    private array $settings;
    private ?string $orgId = null;
    private bool $allowUsernamePassword;
    private bool $allowRegister;
    private bool $allowExternalIdp;
    private bool $forceMfa;
    private ?string $passkeysType;
    private bool $hidePasswordReset;
    private bool $ignoreUnknownUsernames;
    private ?string $defaultRedirectUri;
    private ?string $passwordCheckLifetime;
    private ?string $externalLoginCheckLifetime;
    private ?string $mfaInitSkipLifetime;
    private ?string $secondFactorCheckLifetime;
    private ?string $multiFactorCheckLifetime;
    private ?array $secondFactors;
    private ?array $multiFactors;
    private bool $allowDomainDiscovery;
    private bool $disableLoginWithEmail;
    private bool $disableLoginWithPhone;
    private ?string $resourceOwnerType;
    private bool $forceMfaLocalOnly;

    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    public function setOrgId(string $orgId):void {
        $this->orgId = $orgId;
    }

    public function allowUsernamePassword(): bool {
        return $this->allowUsernamePassword;
    }

    public function allowRegister(): bool {
        return $this->allowRegister;
    }

    public function allowExternalIdp(): bool {
        return $this->allowExternalIdp;
    }

    public function forceMfa(): bool {
        return $this->forceMfa;
    }

    public function getPasskeysType(): string {
        return $this->passkeysType;
    }

    public function hidePasswordReset(): bool {
        return $this->hidePasswordReset;
    }

    public function ignoreUnknownUsernames(): bool {
        return $this->ignoreUnknownUsernames;
    }

    public function getDefaultRedirectUri(): string {
        return $this->defaultRedirectUri;
    }

    public function getPasswordCheckLifetime(): string {
        return $this->passwordCheckLifetime;
    }

    public function getExternalLoginCheckLifetime(): string {
        return $this->externalLoginCheckLifetime;
    }

    public function getMfaInitSkipLifetime(): string {
        return $this->mfaInitSkipLifetime;
    }

    public function getSecondFactorCheckLifetime(): string {
        return $this->secondFactorCheckLifetime;
    }

    public function getMultiFactorCheckLifetime(): string {
        return $this->multiFactorCheckLifetime;
    }

    public function getSecondFactors(): array {
        return $this->secondFactors;
    }

    public function getMultiFactors(): array {
        return $this->multiFactors;
    }

    public function allowDomainDiscovery(): bool {
        return $this->allowDomainDiscovery;
    }

    public function disableLoginWithEmail(): bool {
        return $this->disableLoginWithEmail;
    }

    public function disableLoginWithPhone(): bool {
        return $this->disableLoginWithPhone;
    }

    public function getResourceOwnerType(): string {
        return $this->resourceOwnerType;
    }

    public function forceMfaLocalOnly(): bool {
        return $this->forceMfaLocalOnly;
    }

    public function sendRequest(): void {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/settings/login?ctx.orgId=" . $this->orgId,
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

            $this->allowUsernamePassword = $response->allowUsernamePassword ?? "";
            $this->allowRegister = $response->allowRegister ?? "";
            $this->allowExternalIdp = $response->allowExternalIdp ?? "";
            $this->forceMfa = $response->forceMfa ?? "";
            $this->passkeysType = $response->passkeysType ?? null;
            $this->hidePasswordReset = $response->hidePasswordReset ?? "";
            $this->ignoreUnknownUsernames = $response->ignoreUnknownUsernames ?? "";
            $this->defaultRedirectUri = $response->defaultRedirectUri ?? null;
            $this->passwordCheckLifetime = $response->passwordCheckLifetime ?? null;
            $this->externalLoginCheckLifetime = $response->externalLoginCheckLifetime ?? null;
            $this->mfaInitSkipLifetime = $response->mfaInitSkipLifetime ?? null;
            $this->secondFactorCheckLifetime = $response->secondFactorCheckLifetime ?? null;
            $this->multiFactorCheckLifetime = $response->multiFactorCheckLifetime ?? null;
            $this->secondFactors = $response->secondFactors ?? null;
            $this->multiFactors = $response->multiFactors ?? null;
            $this->allowDomainDiscovery = $response->allowDomainDiscovery ?? "";
            $this->disableLoginWithEmail = $response->disableLoginWithEmail ?? "";
            $this->disableLoginWithPhone = $response->disableLoginWithPhone ?? "";
            $this->resourceOwnerType = $response->resourceOwnerType ?? null;
            $this->forceMfaLocalOnly = $response->forceMfaLocalOnly ?? "";
        }
        curl_close($curl);
    }
}