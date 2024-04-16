<?php

namespace ZitadelPhpClient\User;

use Exception;

/**
 *Class to create a new user.
 */
class Create
{
    private array $settings;
    private array $request;
    private int $userid;
    private string $username;
    private string $organizationId;
    private string $organizationDomain;
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
    private string $password;
    private bool $passwordChangeRequired;
    private array $metadata;
    private array $idpLinks;

    /**Initialize the user creation
     * @param $settings array The settings array
     */
    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**Set the user id of the new user (optional). If not set, you'll get one from Zitadel.
     * @param $userid string The user id of the new user
     * @return void
     */
    public function setUserId(string $userid)
    {
        $this->request["userId"] = $userid;
    }

    /**Set the username of the new user. If you don't set one, the email address will be used as username.
     * @param $username string The username of the new user
     * @return void
     */
    public function setUserName(string $username)
    {
        $this->request["username"] = $username;
    }

    /**Set the organization membership of the new user
     * @param $orgId int Organization-Id
     * @param $orgDomain string Organization-Domain
     * @return void
     */
    public function setOrganization(int $orgId, string $orgDomain)
    {
        $this->request["organization"]["orgId"] = $orgId;
        $this->request["organization"]["orgDomain"] = $orgDomain;
    }

    /**Set the full name of the new user (required)
     * @param $givenName string Given Name
     * @param $familyName string Family Name
     * @return void
     */
    public function setName(string $givenName, string $familyName)
    {
        $this->request["profile"]["givenName"] = $givenName;
        $this->request["profile"]["familyName"] = $familyName;
    }

    /**Set the nickname (optional)
     * @param $nickName string Nickname
     * @return void
     */
    public function setNickName(string $nickName)
    {
        $this->request["profile"]["nickName"] = $nickName;
    }

    /**Set display name (optional)
     * @param $displayName string Display name
     * @return void
     */
    public function setDisplayName(string $displayName)
    {
        $this->request["profile"]["displayName"] = $displayName;
    }

    /**Set the preferred user language (optional). If you don't set one, the default language will be used.
     * @param $lang string Shortcode of the language, e.g. "en" or "de"
     * @return void
     */
    public function setLanguage(string $lang) {
        $this->request["profile"]["preferredLanguage"] = $lang;
    }

    /**Set the gender of the new user (optional).
     * @param $gender string Default: GENDER_UNSPECIFIED, Possible values: GENDER_MALE, GENDER_FEMALE, GENDER_DIVERSE
     * @return void
     */
    public function setGender(string $gender) {
        if ($gender == "GENDER_FEMALE" or $gender == "GENDER_MALE" or $gender == "GENDER_DIVERSE") {
            $this->request["profile"]["gender"] = $gender;
        } else {
            $this->request["profile"]["gender"] = "GENDER_UNSPECIFIED";
        }
    }

    /**Set the Email address (required). The email address will automatically marked as verified.
     * @param $email string Email address
     * @return void
     */
    public function setEmail(string $email) {
        $this->request["email"]["email"] = $email;
        $this->request["email"]["isVerified"] = true;
    }

    /**Set the phone number (optional). The phone number will be automatically marked as verified.
     * @param $phone string Phone number in the format with county code, e.g. "+491590123456"
     * @return void
     */
    public function setPhone(string $phone) {
        $this->request["phone"]["phone"] = $phone;
        $this->request["phone"]["isVerified"] = true;

    }

    /**Add Metadata to the user Profile (optional). The value will be automatically Base64 encoded.
     * @param $key string Key
     * @param $value string Value
     * @return void
     */
    public function addMetaData(string $key, string $value) {
        $this->request["metadata"][] = [
            "key" => $key,
            "value" => base64_encode($value)
        ];
        echo json_encode($this->request);
    }

    /**Set a password for the new user account (required)
     * @param $password string The password
     * @param $changeRequired bool If a change is required, the user have to set a new password at the next login.
     * @return void
     */
    public function setPassword(string $password, bool $changeRequired) {
        $this->request["password"]["password"] = $password;
        $this->request["password"]["changeRequired"] = $changeRequired;
    }

    /**Add an Identity Provider to the User-Profile, so the user can sign in through e.g. Google or GitHub (optional). To get the required data to link an IDP, use the IDP class.
     * @param $idpId int The ID of the Identity Provider
     * @param $userId string The user id you get from the Identity Provider
     * @param $userName string The username you get from the Identity Provider
     * @return void
     */
    public function addIDPLink(int $idpId, string $userId, string $userName) {
        $this->request["idpLinks"][] = [
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
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2/users/human",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->request),
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