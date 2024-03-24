<?php

namespace ZitadelPhpClient\Session;

use Exception;

/**
 * Terminate a session.
 */
class Terminate
{
    private array $settings;
    private string $sessionId;
    private string $sessionToken;

    /**Initialize the Session Termination
     * @param $settings array The settings array
     */
    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    /**Set the session id
     * @param $sessionId string ID of the session to terminate
     * @return void
     */
    public function setSessionId(string $sessionId) {
        $this->sessionId = $sessionId;
    }

    /**Set the session token
     * @param string $sessionToken The current token of the session, returned to a create or update request.
     * @return void
     */
    public function setSessionToken(string $sessionToken) {
        $this->sessionToken = $sessionToken;
    }

    /**Terminate the session
     * @return void
     * @throws Exception Returns an exception, if the communication with Zitadel fails
     */
    public function terminate() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/sessions/$this->sessionId",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{
                \"sessionToken\": \"$this->sessionToken\"
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
        }
        curl_close($curl);
    }
}