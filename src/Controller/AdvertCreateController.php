<?php

namespace App\Controller;

use App\Entity\Advert;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdvertCreateController
{

    
    public function __invoke(Advert $data)
    {
        $data->setFile($data->files->get('file'));
        if($data->getFile()) {
            $name = uniqid();
            $data->getFile()->move($this->getParameter('kernel.project_dir')."/public/assets/img",$name);
            $data->setImage("/assets/img/".$name);
        } else {
            $url = $data->getFileUrl();
            $data->setImage($url);
        }
        return $data;
    }
}