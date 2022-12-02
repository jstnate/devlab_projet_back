<?php

class User
{
    public string $id;
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $pseudo,
        public string $email,
        public string $password
    ){}

    public function inputVerify(): bool
    {
        $isVerify = true;
        if ($this->email === '' || $this->firstName === '' || $this->lastName === '' || $this->pseudo === '') {
            $isVerify = false;
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $isVerify = false;
        }

        if ($this->password === '') {
            $isVerify = false;
        }

        return $isVerify;
    }

}