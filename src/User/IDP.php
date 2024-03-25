<?php

namespace ZitadelPhpClient\User;

use Exception;

/**
 * Class for working with external Identity Providers.
 */
class IDP
{
    private array $settings;
    private int $userid;
    private string $idpId;
    private string $idpToken;
    private string $idpIntentId;
    private string $successUrl;
    private string $failureUrl;
    private string $authUrl;
    private string $idpUserId;
    private string $idpUserName;
    private string $idpAccessToken;
    private string $idpRawInformation;
    private string $idpEmail;
    private bool $isIdpEmailVerified;
    private string $idpPicture;
    private string $idpProfile;

    /**Initialize the IDP setup
     * @param $settings array The settings array
     */
    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    /**Set the userid
     * @param $userid int User id
     * @return void
     */
    public function setUserId(int $userid) {
        $this->userid = $userid;
    }

    /**Set the id of the Identity Provider.
     * @param string $idpId ID of the Identity Provider
     * @return void
     */
    public function setIdpId(string $idpId) {
        $this->idpId = $idpId;
    }

    /**Set the User-ID for the IDP-Account
     * @param string $idpUserId User-ID for the IDP-Account
     * @return void
     */
    public function setIdpUserId(string $idpUserId) {
        $this->idpUserId = $idpUserId;
    }

    /**Set the IDP Intent ID
     * @param string $idpIntentId IDP Intent ID. Zitadel sends it in the GET-Parameter "id" to the success URL
     * @return void
     */
    public function setIdpIntentId(string $idpIntentId) {
        $this->idpIntentId = $idpIntentId;
    }

    /**Set the IDP Token
     * @param string $idpToken IDP Token. Zitadel sends it in the GET-Parameter "token" to the success URL
     * @return void
     */
    public function setIdpToken(string $idpToken) {
        $this->idpToken = $idpToken;
    }

    /**Set the success URL. After the OAuth-Flow, the user will be redirected to it, if the login was successful.
     * @param string $successUrl Success-URL
     * @return void
     */
    public function setSuccessUrl(string $successUrl) {
        $this->successUrl = $successUrl;
    }

    /**Set the failure URL. After the OAuth-Flow, the user will be redirected to it, if the login was successful.
     * @param string $failureUrl Failure URL
     * @return void
     */
    public function setFailureUrl(string $failureUrl) {
        $this->failureUrl = $failureUrl;
    }

    /**Get the Auth-URL to start the login flow with the external IDP
     * @return string AuthURL.
     */
    public function getAuthUrl(): string {
        return $this->authUrl;
    }

    /**Get the OAuth-Access Token from the IDP
     * @return string OAuth-Access Token
     */
    public function getAccessToken(): string {
        return $this->idpAccessToken;
    }

    /**Get the User-ID from the IDP
     * @return string User-ID
     */
    public function getIdpUserId(): string {
        return $this->idpUserId;
    }

    /**Get the Username from the IDP
     * @return string Username
     */
    public function getIdpUserName(): string {
        return $this->idpUserName;
    }

    /**Get the raw user data from the IDP
     * @return string JSON string with the raw user data
     */
    public function getIdpRawInformation(): string {
        return $this->idpRawInformation;
    }

    /**Get the Email address from the IDP
     * @return string Email address
     */
    public function getIdpEmail(): string {
        return $this->idpEmail;
    }

    /**Returns true, when the Email address from the IDP is verified.
     * @return bool Is the Email address verified
     */
    public function isIdpEmailVerified(): bool {
        return $this->isIdpEmailVerified;
    }

    /**Get the profile picture from the IDP
     * @return string Profile picture URL
     */
    public function getIdpPicture(): string {
        return $this->idpPicture;
    }

    /**Get the profile URL from the IDP
     * @return string Profile URL
     */
    public function getIdpProfile(): string {
        return $this->idpProfile;
    }

    /**Start the OAuth Flow. If the request is successful, the user will be redirected to the success URL with the GET Parameters id(IDP-Intent-ID) and token(IDP-Token)
     * @return void
     * @throws Exception
     */
    public function startFlow() {
        $token = $this->settings["userToken"];
        $curl = curl_init();
        $request = array(
            "idpId" => $this->idpId,
            "urls" => array(
                "successUrl" => $this->successUrl,
                "failureUrl" => $this->failureUrl
            )
        );
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/idp_intents",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request),
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
            $this->authUrl = $response->authUrl;
        }
        curl_close($curl);
    }

    /**Fetch the data from the IDP.
     * @return void
     * @throws Exception
     */
    public function fetchIdpData() {
        $token = $this->settings["userToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/idp_intents/$this->idpIntentId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{
                \"idpIntentToken\": \"$this->idpToken\"
            }",
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
            $this->idpAccessToken = $response->idpInformation->oauth->accessToken;
            $this->idpUserId = $response->idpInformation->userId;
            $this->idpUserName = $response->idpInformation->userName;
            $this->idpEmail = $response->idpInformation->rawInformation->email;
            $this->isIdpEmailVerified = $response->idpInformation->rawInformation->email_verified;
            $this->idpPicture = $response->idpInformation->rawInformation->picture;
            $this->idpProfile = $response->idpInformation->rawInformation->profile;
            $this->idpRawInformation = json_encode($response->idpInformation->rawInformation);
        }
        curl_close($curl);
    }

    /**Link the IDP with a user account, so the user can sign in through the IDP
     * @return void
     * @throws Exception
     */
    public function linkIdpToUser() {
        $token = $this->settings["serviceUserToken"];
        $request = array(
            "idpLink" => array(
                "idpId" => $this->idpId,
                "userId" => $this->idpUserId,
                "userName" => $this->idpUserName
            )
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/links",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        if(isset($response->code)) {
            throw new Exception("Error-Code: " . $response->code . " Message: " . $response->message);
        }
        curl_close($curl);
    }
}