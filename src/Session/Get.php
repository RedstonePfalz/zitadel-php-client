<?php

namespace ZitadelPhpClient\Session;

use Exception;

/**
 * Get a session and all its information like the time of the user or password verification.
 */
class Get
{
    private array $settings;
    private string $sessionId;
    private string $sessionToken;
    private string $creationDate;
    private string $changeDate;
    private string $userVerifiedAt;
    private string $userId;
    private string $userLoginName;
    private string $userDisplayName;
    private string $userOrganizationId;
    private string $passwordVerifiedAt;
    private string $idpVerifiedAt;
    private string $totpVerifiedAt;
    private string $optSmsVerifiedAt;
    private string $otpEmailVerifiedAt;
    private string $expirationDate;


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
        $this->sessionToken = $sessionToken;
    }

    public function getCreationDate(): string {
        return $this->creationDate;
    }

    public function getChangeDate(): string {
        return $this->changeDate;
    }

    public function getUserVerifiedAt(): string {
        return $this->userVerifiedAt;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getUserLoginName(): string {
        return $this->userLoginName;
    }

    public function getUserDisplayName(): string {
        return $this->userDisplayName;
    }

    public function getUserOrganizationId(): string {
        return $this->userOrganizationId;
    }

    public function getPasswordVerifiedAt(): string {
        return $this->passwordVerifiedAt;
    }

    public function getIdpVerifiedAt(): string {
        return $this->idpVerifiedAt;
    }

    public function getEmailVerifiedAt(): string {
        return $this->otpEmailVerifiedAt;
    }

    public function getExpirationDate(): string {
        return $this->expirationDate;
    }

    public function getTotpVerifiedAt(): string {
        return $this->totpVerifiedAt;
    }

    public function getSmsVerifiedAt(): string {
        return $this->optSmsVerifiedAt;
    }

    /**Get the session
     * @return void
     * @throws Exception Returns an error, if the communication with Zitadel fails or one of the checks fails.
     */
    public function get() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/sessions/$this->sessionId?sessionToken=$this->sessionToken",
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
            $this->creationDate = $response->session->creationDate;
            $this->changeDate = $response->session->changeDate;
            $this->userVerifiedAt = $response->session->factors->user->verifiedAt;
            $this->userId = $response->session->factors->user->id;
            $this->userLoginName = $response->session->factors->user->loginName;
            $this->userDisplayName = $response->session->factors->user->displayName;
            $this->userOrganizationId = $response->session->factors->user->organizationId;

            if (isset($response->session->factors->password->verifiedAt)) {
                $this->passwordVerifiedAt = $response->session->factors->password->verifiedAt;
            }

            if (isset($response->session->factors->intent->verifiedAt)) {
                $this->idpVerifiedAt = $response->session->factors->intent->verifiedAt;
            }

            if (isset($response->session->factors->otpEmail->verifiedAt)) {
                $this->otpEmailVerifiedAt = $response->session->factors->otpEmail->verifiedAt;
            }

            if (isset($response->session->factors->otpSms->verifiedAt)) {
                $this->optSmsVerifiedAt = $response->session->factors->otpSms->verifiedAt;
            }

            if (isset($response->session->factors->totp->verifiedAt)) {
                $this->totpVerifiedAt = $response->session->factors->totp->verifiedAt;
            }

            if (isset($response->session->expirationDate)) {
                $this->expirationDate = $response->session->expirationDate;
            }
        }
        curl_close($curl);
    }
}