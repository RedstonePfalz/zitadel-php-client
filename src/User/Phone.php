<?php

namespace ZitadelPhpClient\User;

use Exception;

/**
 * Class for changing and verifying a phone number
 */
class Phone
{
    private array $settings;
    private int $userid;
    private string $returnedVerificationCode;
    /**Initialize the phone number change
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

    /**Returns the phone number verification code
     * @return string Verification code
     */
    public function getVerificationCode(): string
    {
        return $this->returnedVerificationCode;
    }

    /**Change the phone number
     * @param $phone string Phone number
     * @return void
     * @throws Exception Returns an exception with an error code and a message if the communication with Zitadel fails
     */
    public function changePhone(string $phone) {
        $token = $this->settings["serviceUserToken"];

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/phone",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{
                \"phone\": \"$phone\",
                \"returnCode\": {}
            }",
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
        } else {
            $this->returnedVerificationCode = $response->verificationCode;
        }
    }

    /**Get a new Verification code
     * @return void
     */
    public function resendVerificationCode() {
        $curl = curl_init();
        $token = $this->settings["serviceUserToken"];
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/phone/resend",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "returnCode": {}
            }',
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        $this->returnedVerificationCode = $response->verificationCode;
        curl_close($curl);
    }

    /**Verifies a phone number with a verification code
     * @param $verifyCode string Verification Code
     * @return bool Is the verification code correct?
     */
    public function verify(string $verifyCode): bool {
        $token = $this->settings["serviceUserToken"];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/phone/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>"{
                \"verificationCode\": \"$verifyCode\"
            }",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if(isset($response->code)) {
            return false;
        }
        return true;
    }
}