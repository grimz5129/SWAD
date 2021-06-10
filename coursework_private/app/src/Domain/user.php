<?php
/**
 * user.php
 * Doctrine entity metadata class
 *
 * @author Joe
 * @author Nicholas
 */

namespace Coursework\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class user {

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer", unique=true)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $User_ID;

    /**
     * @var string $Username
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $Username;

    /**
     * @var string $Password
     *
     * @ORM\Column(type="string")
     */
    protected $Password;

    /**
     * @var string $Email
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $Email;

    /**
     * @var string $ActiveSessionId
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $ActiveSessionId;

    /**
     * @var string $PHP7PasswordAlgorithm
     *
     * @ORM\Column(type="string")
     */
    protected $PHP7PasswordAlgorithm;

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->Password;
    }

    /**
     * @return int
     */
    public function getUserID()
    {
        return $this->User_ID;
    }

    /**
     * Set the username
     * @param string $username [description]
     */
    public function setUsername(string $username)
    {
        if (strlen($username) < 6) {
            throw new \Exception('Username must be longer than 6 characters');
        }

        $this->Username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->Username;
    }

    /**
     * @param string $Password
     */
    public function setPassword($Password, $passwordConfirmation = '')
    {
        if ($passwordConfirmation !== '' &&
            $passwordConfirmation !== $Password) {
                throw new \Exception('Passwords do not match');
        }

        if (strlen($Password) < 6) {
            throw new \Exception('Password must be longer than 6 characters');
        }

        // Limitation of bcrypt, which is the PASSWORD_DEFAULT AToW
        if (strlen($Password) > 72) {
            throw new \Exception('Password must be less than 72 characters long');
        }

        $password_hashed = password_hash($Password, PASSWORD_DEFAULT);

        if ($password_hashed === false) {
            throw new \Exception('Failed to set password');
        }

        $this->PHP7PasswordAlgorithm = PASSWORD_DEFAULT;
        $this->Password = $password_hashed;
    }

    /**
     * Wrapper for PHP password_verify
     * @param  string $password password to validate
     * @return bool           'true' if password is correct, 'false' otherwise
     */
    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->Password);
    }

    /**
     * Creates a new session for the current user if the credentials are valid
     * @param  string $password Password to use for authentication
     * @return [type]           [description]
     */
    public function doLogin(string $password)
    {
        if (!$this->checkPassword($password)) {
            throw new \Exception('Incorrect password');
        }
        else {
            $sessionId = bin2hex(openssl_random_pseudo_bytes(32));
            $_SESSION['id'] = $sessionId;
            $this->ActiveSessionId = $sessionId;
        }
    }

    /**
     * Log in an user
     * @return [type] [description]
     */
    public function doLogout()
    {
        unset($_SESSION['id']);
        $this->ActiveSessionId = null;
    }

    /**
     * @param string $Email
     */
    public function setEmail(string $Email)
    {
        $Email = filter_var(strtolower(trim($Email)), FILTER_SANITIZE_EMAIL);

        if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Email address is not valid');
        }

        $this->Email = $Email;
    }

    /**
     * Return non-sensitive user data in an array
     * @return array
     */
    public function asArray(): array
    {
        return [
            'Username' => $this->getUsername(),
            'Email' => $this->getEmail()
        ];
    }
}
