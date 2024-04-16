<?php

namespace ZitadelPhpClient\User;

use Exception;

/**Class to edit user data. Important: To change the email address, phone number or the password, use the Email or Password Class!
 *
 */
class Edit
{
    private array $settings;
    private int $userid;
    private array $userChanges;
    /**Initialize the user data change. Important: To change the email address or the password, use the Email or Password Class!
     * @param $settings array The settings array
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }
    /**Set the user id of the user
     * @param $userid int The user id of the user
     * @return void
     */
    public function setUserId(int $userid) {
        $this->userid = $userid;
    }
    /**Change the username of the user
     * @param $username string The username of the new user
     * @return void
     */
    public function setUserName(string $username) {
        $this->userChanges["username"] = $username;
    }
    /**Set the full name of the new user. You must always enter your full name, even if you don't change it.
     * @param $givenName string Given Name
     * @param $familyName string Family Name
     * @return void
     */
    public function setName(string $givenName, string $familyName) {
        $this->userChanges["profile.givenName"] = $givenName;
        $this->userChanges["profile.familyName"] = $familyName;
    }
    /**Change the nickname
     * @param $nickName string Nickname
     * @return void
     */
    public function setNickName(string $nickName) {
        $this->userChanges["profile.nickName"] = $nickName;
    }
    /**Change display name
     * @param $displayName string Display name
     * @return void
     */
    public function setDisplayName(string $displayName) {
        $this->userChanges["profile.displayName"] = $displayName;
    }
    /**Change the preferred user language
     * @param $lang string Shortcode of the language, e.g. "en" or "de"
     * @return void
     */
    public function setLanguage(string $lang) {
        $this->userChanges["profile.preferredLanguage"] = $lang;
    }
    /**Change the gender of the user
     * @param $gender string Default: GENDER_UNSPECIFIED, Possible values: GENDER_MALE, GENDER_FEMALE, GENDER_DIVERSE
     * @return void
     */
    public function setGender(string $gender) {
        if ($gender == "GENDER_FEMALE" or $gender == "GENDER_MALE" or $gender == "GENDER_DIVERSE") {
            $this->userChanges["profile.gender"] = $gender;
        } else {
            $this->userChanges["profile.gender"] = "GENDER_UNSPECIFIED";
        }
    }

    /**Change the user data and sends the data to Zitadel
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function edit() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/users/human/$this->userid?" . $this->encodeUserData(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'PUT',
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
    private function encodeUserData(): string {
        $encodedString = "";
        foreach ($this->userChanges as $key => $value) {
            $encodedKey = urlencode($key);
            $encodedValue = urlencode($value);
            $encodedString .= "$encodedKey=$encodedValue&";
        }
        return rtrim($encodedString, '&');
    }
}