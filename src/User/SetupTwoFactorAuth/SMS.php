<?php

namespace ZitadelPhpClient\User\SetupTwoFactorAuth;

use Exception;

/**
 * Set up an OTP SMS for a user.
 */
class SMS
{
    private array $settings;
    private int $userid;

    /**Initialize the SMS OTP setup
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

    /**Add the OTP SMS method. The phone number has to be verified to add this second factor.
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function add() {
        $this->request("POST");
    }

    /**Remove the OTP SMS method from the user
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function remove() {
        $this->request("DELETE");
    }

    /**
     * @param $type
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    private function request($type) {
        $token = $this->settings["userToken"];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/users/$this->userid/otp_sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"{}",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        if(isset($response->code)) {
            throw new Exception("Error-Code: " . $response->code . " Message: " . $response->message);
        }
    }
}