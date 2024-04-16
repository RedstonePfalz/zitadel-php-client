<?php

namespace ZitadelPhpClient\Session;

use Exception;

/**
 * Update a session.
 * First, you have to set the session id and the session token.
 * Then, you can define checks e.g. the Password or TOTP-Codes. If one check fails, the session won't be created.
 * You can also define challenges e.g. an Email-OTP or SMS-OTP. Then, the Zitadel-API will send back the code.
 */
class Update
{
    private array $settings;
    private string $sessionId;
    private string $smsCode;
    private string $emailCode;
    private array $request;

    /**Initialize the Session create setup
     * @param $settings array The settings array
     */
    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    /**Set the session id (required).
     * @param string $sessionId The session id
     * @return void
     */
    public function setSessionId(string $sessionId) {
        $this->sessionId = $sessionId;
    }

    /**Set the session token (required).
     * @param string $sessionToken The session token
     * @return void
     */
    public function setSessionToken(string $sessionToken) {
        $this->request["sessionToken"] = $sessionToken;
    }

    /**Set the check "password".
     * @param string $password The user password
     * @return void
     */
    public function setPassword(string $password) {
        $this->request["checks"]["password"]["password"] = $password;
    }

    /**Set the check "External Identity Providers". You also have to set the IDP-Intent-Token.
     * You get the required value from the IDP-class.
     * @param string $idpIntentId The IDP-Intent-ID
     * @return void
     */
    public function setIdpIntentId(string $idpIntentId) {
        $this->request["checks"]["idpIntent"]["idpIntentId"] = $idpIntentId;
    }

    /**Set the check "External Identity Providers". You also have to set the IDP-Intent-ID.
     * You get the required value from the IDP-class.
     * @param string $idpIntentToken The IDP-Intent-Token
     * @return void
     */
    public function setIdpIntentToken(string $idpIntentToken) {
        $this->request["checks"]["idpIntent"]["idpIntentToken"] = $idpIntentToken;
    }

    /**Set the check "TOTP-Code".
     * @param string $totpCode The TOTP-Code
     * @return void
     */
    public function setTOTPCode(string $totpCode) {
        $this->request["checks"]["totp"]["code"] = $totpCode;
    }

    /**Set the check "SMS-OTP".
     * @param string $smsCode The SMS-Code
     * @return void
     */
    public function setSmsCode(string $smsCode) {
        $this->request["checks"]["otpSms"]["code"] = $smsCode;
    }

    /**Set the check "Email-OTP".
     * @param string $otpEmail The Email-Code
     * @return void
     */
    public function setOtpEmail(string $otpEmail) {
        $this->request["checks"]["otpEmail"]["code"] = $otpEmail;
    }

    /**Set the challenge "SMS-OTP".
     * @return void
     */
    public function returnSmsCode() {
        $this->request["challenges"]["otpSms"]["returnCode"] = true;
    }

    /**Set the challenge "Email-OTP".
     * @return void
     */
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

    /**Returns the SMS-OTP-Code. Works only, if you used the function returnSmsCode() before.
     * @return string The SMS-OTP-Code
     */
    public function getSmsCode(): string {
        return $this->smsCode;
    }

    /**Returns the Email-OTP-Code. Works only, if you used the function returnEmailCode() before.
     * @return string The Email-OTP-Code
     */
    public function getEmailCode(): string {
        return $this->emailCode;
    }

    /**Updates the session
     * @return void
     * @throws Exception Returns an error, if the communication with Zitadel fails or one of the checks fails.
     */
    public function update() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/sessions/$this->sessionId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PATCH',
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