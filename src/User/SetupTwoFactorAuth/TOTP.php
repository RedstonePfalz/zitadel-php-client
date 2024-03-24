<?php

namespace ZitadelPhpClient\User\SetupTwoFactorAuth;


use Exception;
use chillerlan\QRCode\QRCode;

/**
 * Set up a TOTP generator for a user
 */
class TOTP
{
    private array $settings;
    private int $userid;
    private string $secret;
    private string $totpUri;

    /**Initialize the TOTP setup
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

    /**Get the TOTP URI
     * @return string TOTP URI
     */
    public function getURI(): string {
        return $this->totpUri;
    }

    /**Get the TOTP Secret
     * @return string TOTP secret
     */
    public function getSecret(): string {
        return $this->secret;
    }

    /**Get the TOTP QR-Code ready for any Authenticator-App
     * @return string Base64 encoded SVG image url e.g. data:image/svg+xml;base64,PD94...
     */
    public function getQRCode(): string {
        $qrCode = new QRCode;
        return $qrCode->render($this->totpUri);
    }

    /**Set up a TOTP token for a user.
     * You can get the URI via the getURI() command, the TOTP secret via the getSecret() command or the QR-Code via the getQRCode() command
     * @return void
     * @throws Exception
     */
    public function start() {
        $token = $this->settings["userToken"];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/totp",
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
        } else{
            $this->totpUri = $response->uri;
            $this->secret = $response->secret;
        }
    }

    /**Verify the TOTP method creation. You can only use this function to finish the TOTP setup. To verify the Code after login, use the 'Create' class in 'Session'.
     * @param $verifyCode
     * @return bool Is the TOTP code correct?
     */
    public function verify($verifyCode): bool
    {
        $token = $this->settings["userToken"];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->settings["domain"] . "/v2beta/users/$this->userid/totp/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "{
                \"code\": \"$verifyCode\"
            }",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Bearer $token"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        if (isset($response->code)) {
            return false;
        }
        return true;
    }

}