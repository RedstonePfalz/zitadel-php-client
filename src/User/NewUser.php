<?php

namespace ZitadelPhpClient\User;

use Exception;
use ZitadelPhpClient\ZitadelPhpClient;

class NewUser
{
    private $settings;
    private $userid;
    private $username;
    private $organizationId;
    private $organizationDomain;
    private $givenName;
    private $familyName;
    private $nickName;
    private $displayName;
    private $preferredLanguage;
    private $gender;
    private $email;
    private $isEmailVerified;
    private $phone;
    private $isPhoneVerified;
    private $password;
    private $passwordChangeRequired;
    private $metadata;
    private $idpLinks;

    /**Initialize the user creation
     * @param $settings array The settings array
     */
    public function __construct($settings)
    {
        $this->settings = $settings;
    }

    /**Set the user id of the new user
     * @param $userid int The user id of the new user
     * @return void
     */
    public function setUserId($userid)
    {
        $this->userid = $userid;
    }

    /**Set the username id the new user
     * @param $username string The username of the new user
     * @return void
     */
    public function setUserName($username)
    {
        $this->username = $username;
    }

    /**Set the organization membership of the new user
     * @param $orgId int Organization-Id
     * @param $orgDomain string Organization-Domain
     * @return void
     */
    public function setOrganization($orgId, $orgDomain)
    {
        $this->organizationId = $orgId;
        $this->organizationDomain = $orgDomain;
    }

    /**Set the full name of the new user
     * @param $givenName string Given Name
     * @param $familyName string Family Name
     * @return void
     */
    public function setName($givenName, $familyName)
    {
        $this->givenName = $givenName;
        $this->familyName = $familyName;
    }

    /**Set the nickname
     * @param $nickName string Nickname
     * @return void
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;
    }

    /**Set display name
     * @param $displayName string Display name
     * @return void
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**Set the preferred user language
     * @param $lang string Shortcode of the language, e.g. "en" or "de"
     * @return void
     */
    public function setLanguage($lang) {
        $this->preferredLanguage = $lang;
    }

    /**Set the gender of the new user
     * @param $gender string Default: GENDER_UNSPECIFIED, Possible values: GENDER_MALE, GENDER_FEMALE, GENDER_DIVERSE
     * @return void
     */
    public function setGender($gender) {
        if ($gender == "GENDER_FEMALE" or $gender == "GENDER_MALE" or $gender == "GENDER_DIVERSE") {
            $this->gender = $gender;
        } else {
            $this->gender = "GENDER_UNSPECIFIED";
        }
    }

    /**Set the Email address
     * @param $email string Email address
     * @return void
     */
    public function setEmail($email) {
        $this->email = $email;
        $this->isEmailVerified = true;
    }

    /**Set the phone number
     * @param $phone string Phone number in the format with county code, e.g. "+491590123456"
     * @return void
     */
    public function setPhone($phone) {
        $this->phone = $phone;
        $this->isPhoneVerified = true;

    }

    /**Add Metadata to the user Profile
     * @param $key string Key
     * @param $value string Value
     * @return void
     */
    public function addMetaData($key, $value) {
        $this->metadata[] = [
            "key" => $key,
            "value" => base64_encode($value)
        ];
    }

    /**Set a password for the new user account
     * @param $password string The password
     * @param $changeRequired bool If a change is required, the user have to set a new password at the next login.
     * @return void
     */
    public function setPassword($password, $changeRequired) {
        $this->password = $password;
        $this->passwordChangeRequired = $changeRequired;
    }

    /**Add an Identity Provider to the User-Profile, so the user can sign in through e.g. Google or GitHub
     * @param $idpId int The ID of the Identity Provider
     * @param $userId string The user id you get from the Identity Provider
     * @param $userName string The username you get from the Identity Provider
     * @return void
     */
    public function addIDPLink($idpId, $userId, $userName) {
        $this->idpLinks[] = [
            "idpId" => $idpId,
            "userId" => $userId,
            "userName" => $userName
        ];
    }

    /**Create the new user and sends the data to Zitadel
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function create()
    {
        $token = $this->settings["serviceUserToken"];
        $request = array(
            "userId" => $this->userid,
            "username" => $this->username,
            "organization" => array(
                "orgId" => $this->organizationId,
                "orgDomain" => $this->organizationDomain
            ),
            "profile" => array(
                "givenName" => $this->givenName,
                "familyName" => $this->familyName,
                "nickName" => $this->nickName,
                "displayName" => $this->displayName,
                "preferredLanguage" => $this->preferredLanguage,
                "gender" => $this->gender
            ),
            "email" => array(
                "email" => $this->email,
                "isVerified" => $this->isEmailVerified
            ),
            "phone" => array(
                "phone" => $this->phone,
                "isVerified" => $this->isPhoneVerified
            ),
            "metadata" => $this->metadata,
            "password" => array(
                "password" => $this->password,
                "changeRequired" => $this->passwordChangeRequired
            ),
            "idpLinks" => $this->idpLinks
        );
        echo json_encode($request);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/human",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($request),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
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