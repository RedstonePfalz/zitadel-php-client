<?php

namespace ZitadelPhpClient\Session;

use Exception;

class Create
{
    private array $settings;
    private string $sessionId;
    private string $sessionToken;
    private string $smsCode;
    private string $emailCode;
    private array $request;

    /**Initialize the Session create setup
     * @param $settings array The settings array
     */
    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    public function setUserId(string $userid) {
        $this->request["checks"]["user"]["userId"] = $userid;
    }

    public function setLoginName(string $loginName) {
        $this->request["checks"]["user"]["loginName"] = $loginName;
    }

    public function setPassword(string $password) {
        $this->request["checks"]["password"]["password"] = $password;
    }

    public function setIdpIntentId(string $idpIntentId) {
        $this->request["checks"]["idpIntent"]["idpIntentId"] = $idpIntentId;
    }

    public function setIdpIntentToken(string $idpIntentToken) {
        $this->request["checks"]["idpIntent"]["idpIntentToken"] = $idpIntentToken;
    }

    public function setTOTPCode(string $totpCode) {
        $this->request["checks"]["totp"]["code"] = $totpCode;
    }

    public function setSmsCode(string $smsCode) {
        $this->request["checks"]["otpSms"]["code"] = $smsCode;
    }

    public function setOtpEmail(string $otpEmail) {
        $this->request["checks"]["otpEmail"]["code"] = $otpEmail;
    }

    public function returnSmsCode() {
        $this->request["challenges"]["otpSms"]["returnCode"] = true;
    }

    public function returnEmailCode() {
        $this->request["challenges"]["otpEmail"]["returnCode"] = json_decode("{}");
    }

    /**Set the session lifetime
     * @param int $seconds Duration in Seconds after which the session will be automatically invalidated
     * @return void
     */
    public function setLifetime(int $seconds) {
        $this->request["lifetime"] = $seconds . "s";
    }

    public function getSessionId(): string {
        return $this->sessionId;
    }

    public function getSessionToken(): string {
        return $this->sessionToken;
    }

    public function getSmsCode(): string {
        return $this->smsCode;
    }

    public function getEmailCode(): string {
        return $this->emailCode;
    }

    public function create() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/sessions",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($this->request),
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
            $this->sessionId = $response->sessionId;
            $this->sessionToken = $response->sessionToken;
            if (isset($response->challenges->otpSms)) {
                $this->smsCode = $response->challenges->otpSms;
            }

            if (isset($response->challenges->otpEmail)) {
                $this->emailCode = $response->challenges->otpEmail;
            }
        }
        curl_close($curl);
    }

}