<?php

require_once "settings.php";

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use ZitadelPhpClient\User\Account;
use ZitadelPhpClient\User\Create;
use ZitadelPhpClient\User\Delete;
use ZitadelPhpClient\User\Edit;
use ZitadelPhpClient\User\Email;
use ZitadelPhpClient\User\Get;
use ZitadelPhpClient\User\Phone;


class UserTest extends TestCase
{

    private static array $settings;
    private int $userid = 1357833333333;

    public static function setUpBeforeClass(): void
    {
        $settings = new test\settings();
        self::$settings = $settings->settings;
    }

    public function testUserCreate(){

        $create_user = new Create(self::$settings);
        $create_user->setUserId($this->userid);
        $create_user->setName("John", "Doe");
        $create_user->setEmail("john@doe.com");
        $create_user->setPassword("S3cr3t!S3cr3t!", true);
        $create_user->setPhone("+49170123456789");
        $create_user->setUsername("johndoe");

        try {
            $create_user->create();

            $this->assertTrue(true);
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
        sleep(1);
    }

    #[Depends("testUserCreate")]
    public function testUserEdit() {
        $edit_user = new Edit(self::$settings);
        $edit_user->setUserId($this->userid);
        $edit_user->setName("John", "Doe");

        $edit_user->setNickName("DoeJohn");
        $edit_user->setDisplayName("TestUser");

        try {
            $edit_user->edit();

            $this->assertTrue(true);
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[Depends("testUserCreate")]
    public function testUserGet() {
        $get_user = new Get(self::$settings);
        $get_user->setUserId($this->userid);

        try {
            $get_user->fetch();

            $this->assertEquals("john@doe.com", $get_user->getEmail());
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[Depends("testUserCreate")]
    public function testUserEmail() {
        $email = new Email(self::$settings);
        $email->setUserId($this->userid);

        try {
            $email->changeEmail("doe@john.com");

            $this->assertTrue(true);
            $this->assertNotNull($email->getVerificationCode());
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[Depends("testUserCreate")]
    public function testUserPhone() {
        $phone = new Phone(self::$settings);
        $phone->setUserId($this->userid);

        try {
            $phone->changePhone("+49170123456780");

            $this->assertTrue(true);
            $this->assertNotNull($phone->getVerificationCode());
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[Depends("testUserCreate")]
    public function testUserStates() {
        $account = new Account(self::$settings);
        $account->setUserId($this->userid);

        try {
            $account->deactivate();
            $account->reactivate();

            $this->assertTrue(true);
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }

    #[Depends("testUserCreate")]
    public function testUserDelete() {
        $delete_user = new Delete(self::$settings);
        $delete_user->setUserId($this->userid);

        try {
            $delete_user->delete();

            $this->assertTrue(true);
        } catch (exception $e) {
            $this->fail($e->getMessage());
        }
    }
}