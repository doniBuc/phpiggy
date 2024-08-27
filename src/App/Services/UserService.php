<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

class UserService
{
    public function __construct(private Database $db) {}

    public function isEmailTaken(string $email)
    {
        $emailCount = $this->db->prepare(
            "SELECT COUNT(*) FROM users WHERE email = :email",
            ['email' => $email]
        )->count(); // chainable method
        if ($emailCount > 0) {
            throw new ValidationException(['email' => ['Email Taken']]);
        }
    }

    public function createUser(array $formData)
    {

        $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['case' => 12]);
        $this->db->prepare(
            "INSERT INTO users(email,password,age,country,social_media_url) 
            VALUES(:email,:password,:age,:country,:socialMediaUrl)",
            [
                'email' => $formData['email'],
                'password' => $password,
                'age' => $formData['age'],
                'country' => $formData['country'],
                'socialMediaUrl' => $formData['socialMediaUrl']
            ]

        );

        //before returning a respond lets start authenticating user
        //first were going to generate a new session id, whenever the authentication status sub the user changes we should always generate new id, this include registering new user 
        session_regenerate_id();

        //before logging the user into apps where inserting a user into the database 
        //-> a common feature in the databases is ability to keep track of the latest record inserted in the table
        $_SESSION['user'] = $this->db->get_last_id();
    }
    public function login(array $loginData)
    {
        $user = $this->db->prepare(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $loginData['email']]
        )->find();

        $passwordMatch = password_verify(
            $loginData['password'],
            $user['password'] ?? ""
        ); // comparing the password


        if (!$user || !$passwordMatch) {
            throw new ValidationException(['password' => ['Invalid credentials']]);
        }

        session_regenerate_id();

        // if user is authenticated, we can update the session to store user info
        $_SESSION['user'] = $user['id']; // we only need is id

    }

    public function logout()
    {
        // 1st approach: by unset you only destroying a specific variable
        unset($_SESSION['user']);
        session_regenerate_id();


        //2nd approach: by using destroy all cookie
        session_destroy();
        $params = session_get_cookie_params();

        setcookie(
            'PHPSESSID',
            '',
            time() - 3600, // for security we subract random second to time now to set the expiration in past
            $params['domain'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
}
