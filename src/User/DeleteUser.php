<?php

namespace ZitadelPhpClient\User;

use Exception;

class DeleteUser
{
    private $settings;
    private $userid;
    /**Initialize the user deletion
     * @param $settings array The settings array
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**Set the user ID
     * @param $userid int The id of the user
     * @return void
     */
    public function setUserId($userid) {
        $this->userid = $userid;
    }

    /**Deletes the user and sends the request to Zitadel
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function delete() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_HTTPHEADER => array(
                "Accept: application/json",
                "Authorization: Bearer $token"
            )
        ));
        $response = json_decode(curl_exec($curl));
        if(isset($response->code)) {
            throw new Exception("Error-Code: " . $response->code . " Message: " . $response->message);
        }
        curl_close($curl);
    }
}