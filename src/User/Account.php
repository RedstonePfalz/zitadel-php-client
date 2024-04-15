<?php

namespace ZitadelPhpClient\User;

use Exception;

/**
 * Class for the general management of the user account e.g. Deactivating the account
 */
class Account
{
    private array $settings;
    private int $userid;
    private string $action;
    /**Initialize the user account class
     * @param $settings array The settings array
     */
    public function __construct(array $settings) {
        $this->settings = $settings;
    }

    /**Set the userid
     * @param $userid int User id
     * @return void
     */
    public function setUserId(int $userid): void {
        $this->userid = $userid;
    }

    /**Deactivate a user. He won't be able to log in. Use deactivate user when the user should not be able to use the account anymore, but you still need access to the user data.
     * You'll get an error, if the user is already deactivated
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function deactivate(): void {
        $this->action = "deactivate";
        $this->request();
    }

    /**Reactivate a user. He will be able to log in again.
     * You'll get an error, if the user is not deactivated.
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function reactivate(): void {
        $this->action = "reactivate";
        $this->request();
    }

    /**Lock a user. The user won't be able to log in.
     * You'll get an error, if the user is not locked.
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function lock(): void {
        $this->action = "lock";
        $this->request();
    }

    /** Unlock a user. You'll get an error, if the user is not locked.
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function unlock(): void {
        $this->action = "unlock";
        $this->request();
    }

    /**
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    private function request(): void {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/$this->action",
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