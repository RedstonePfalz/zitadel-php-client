<?php

namespace ZitadelPhpClient\User;

use Exception;

class GetUser
{
    private array $settings;
    private int $userid;
    private string $userState;
    private string $userName;
    private array $loginNames;
    private string $preferredLoginName;
    private string $givenName;
    private string $familyName;
    private string $nickName;
    private string $displayName;
    private string $preferredLanguage;
    private string $gender;
    private string $email;
    private bool $isEmailVerified;
    private string $phone;
    private bool $isPhoneVerified;
    private string $profilePicture;
    private string $rawUserData;

    /**Initialize the GetUser class
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

    /**Returns the Profile Picture URL. Notice: If the user didn't set a profile picture, you'll get an error when accessing this function.
     * @return string Profile Picture URL
     */
    public function getProfilePicture(): string {
        return $this->profilePicture;
    }

    /**Returns the User state.
     * @return string User state. Possible values: USER_STATE_UNSPECIFIED, USER_STATE_ACTIVE, USER_STATE_INACTIVE, USER_STATE_DELETED, USER_STATE_LOCKED, USER_STATE_INITIAL
     */
    public function getUserState(): string {
        return $this->userState;
    }

    /**Returns the username
     * @return string Username
     */
    public function getUsername(): string {
        return $this->userName;
    }

    /**Returns an array with the possible login names
     * @return array Possible login names
     */
    public function getLoginNames(): array {
        return $this->loginNames;
    }

    /**Returns the preferred Login name
     * @return string Preferred Login name
     */
    public function getPreferredLoginName(): string {
        return $this->preferredLoginName;
    }

    /**Returns the given name
     * @return string Given Name
     */
    public function getGivenName(): string {
        return $this->givenName;
    }

    /**Returns the family name
     * @return string Family Name
     */
    public function getFamilyName(): string {
        return $this->familyName;
    }

    /**Returns the nickname
     * @return string Nickname
     */
    public function getNickname(): string {
        return $this->nickName;
    }

    /**Returns the display name
     * @return string Display name
     */
    public function getDisplayName(): string {
        return $this->displayName;
    }

    /**Returns the preferred language
     * @return string Preferred language
     */
    public function getPreferredLanguage(): string {
        return $this->preferredLanguage;
    }

    /**Returns the gender
     * @return string Gender. Possible values: GENDER_UNSPECIFIED, GENDER_MALE, GENDER_FEMALE, GENDER_DIVERSE
     */
    public function getGender(): string {
        return $this->gender;
    }

    /**Returns the email address
     * @return string Email address
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**Returns true if the email is verified
     * @return bool verification of the email address
     */
    public function isEmailVerified(): bool {
        return $this->isEmailVerified;
    }

    /**Returns the phone number. Notice: If the user didn't set a profile picture, you'll get an error when accessing this function.
     * @return string
     */
    public function getPhone(): string {
        return $this->phone;
    }

    /**Returns true if the phone number is verified
     * @return bool verification of the phone number
     */
    public function isPhoneVerified(): bool {
        return $this->isPhoneVerified;
    }

    /**Returns the raw user data
     * @return string JSON-encoded raw user data
     */
    public function getRawUserData(): string {
        return $this->rawUserData;
    }

    /**Fetch the user data from Zitadel
     * @return void
     * @throws Exception Returns an exception, if the communication with Zitadel fails
     */
    public function fetch() {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid",
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
            $this->rawUserData = json_encode($response->user);

            $this->userState = $response->user->state;
            $this->userName = $response->user->username;
            $this->loginNames = $response->user->loginNames;
            $this->preferredLoginName = $response->user->preferredLoginName;
            $this->givenName = $response->user->human->profile->givenName;
            $this->familyName = $response->user->human->profile->familyName;
            $this->nickName = $response->user->human->profile->nickName;
            $this->displayName = $response->user->human->profile->displayName;
            $this->preferredLanguage = $response->user->human->profile->preferredLanguage;

            if(isset($response->user->human->profile->avatarUrl)) {
                $this->profilePicture = $response->user->human->profile->avatarUrl;
            }


            $this->gender = $response->user->human->profile->gender;
            $this->email = $response->user->human->email->email;

            if(isset($response->user->human->email->isVerified)) {
                $this->isEmailVerified = $response->user->human->email->isVerified;
            } else {
                $this->isEmailVerified = false;
            }

            if(isset($response->user->human->phone->phone)) {
                $this->phone = $response->user->human->phone->phone;
            }

            if(isset($response->user->human->phone->isVerified)) {
                $this->isPhoneVerified = $response->user->human->phone->isVerified;
            } else {
                $this->isPhoneVerified = false;
            }
        }
        curl_close($curl);
    }
}