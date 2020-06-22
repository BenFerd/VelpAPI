<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateController
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function __invoke(User $data)
    {
        $hash = $this->encoder->encodePassword($data, $data->getPassword());
        $data->setPassword($hash);
        return $data;
    }
}
