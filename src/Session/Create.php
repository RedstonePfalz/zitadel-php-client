<?php

namespace ZitadelPhpClient\Session;

use Exception;

/**
 * At the end, you get a token, which is required for further updates of this session.
 * First, you have to set a user identifier. This can either be the user id or the login name.
 * Then, you can define checks e.g. the Password or TOTP-Codes. If one check fails, the session won't be created.
 * You can also define challenges e.g. an Email-OTP or SMS-OTP. Then, the Zitadel-API will send back the code.
 */
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

    /**Set the user identifier "id". You can't set both user identifiers. This will result in an error.
     * @param string $userid User Id
     * @return void
     */
    public function setUserId(string $userid) {
        $this->request["checks"]["user"]["userId"] = $userid;
    }

    /**Set the user identifier "login name". You can't set both user identifiers. This will result in an error.
     * @param string $loginName The login name e.g. doe@your-zitadel-instance.com
     * @return void
     */
    public function setLoginName(string $loginName) {
        $this->request["checks"]["user"]["loginName"] = $loginName;
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

    /**Returns the session id.
     * @return string The session id
     */
    public function getSessionId(): string {
        return $this->sessionId;
    }

    /**Returns the session token
     * @return string The session token
     */
    public function getSessionToken(): string {
        return $this->sessionToken;
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

    /**Creates the session
     * @return void
     * @throws Exception Returns an error, if the communication with Zitadel fails or one of the checks fails.
     */
    public function create() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/sessions",
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