<?php

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Category;
use App\Entity\Message;
use App\Entity\Review;
use App\Entity\Topic;
use Faker\Factory;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * L'encodeur de mot de passe
     *
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder; // hash
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_BE');
        // Boucle fixtures category.
        for ($c = 0; $c < 10; $c++) {
            $category = new Category();

            $category->setName($faker->randomElement(["Aide", "Animaux", "Babysitting", "Bricolage", "Event", "Jardin", "Informatique", "Soins", "Ecole", "Transport"]))
                ->setDescription($faker->sentence());

            $manager->persist($category);
        }
        // Boucle fixtures User.
        for ($u = 0; $u < 10; $u++) {

            $user = new User();
            $hash = $this->encoder->encodePassword($user, "password"); //hash le mdp avant d'etre persist en db.

            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setCity($faker->city)
                ->setPassword($hash)
                ->setRegisterDate($faker->dateTimeBetween('-6 months'));

            $manager->persist($user);
            // Boucle fixtures 5 advert par User.
            for ($a = 0; $a < 5; $a++) {

                $advert = new Advert();
                $advert->setUser($user)
                    ->setTitle("Travail")
                    ->setDescription($faker->sentence())
                    ->setPubDate($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category);

                $manager->persist($advert);
            }

            // Boucle Review.
            for ($r = 0; $r < 5; $r++) {
                $review = new Review();
                $review->setTitle($faker->randomElement(['Super', 'Bien', 'Pas mal', 'Mauvais']))
                    ->setDescription($faker->sentence())
                    ->setPubDate($faker->dateTimeBetween('-6 months'))
                    ->setNote(mt_rand(0, 10))
                    ->setAuthor($user)
                    ->setConcern($user);

                $manager->persist($review);
            }

            // Boucle fixture 10 Topic par User.
            for ($t = 0; $t < 10; $t++) {
                $topic = new Topic();

                $topic->setTitle($faker->word())
                    ->setDescription($faker->sentence())
                    ->setCategory($category)
                    ->setAuthor($user);

                $manager->persist($topic);

                // Boucle fixture 5 Messages par Topic
                for ($m = 0; $m < 5; $m++) {
                    $message = new Message();

                    $message->setContent($faker->sentence())
                        ->setDate($faker->dateTimeBetween('-6 months'))
                        ->setTopic($topic)
                        ->setAuthor($user);

                    $manager->persist($message);
                }
            }
        }


        $manager->flush();
    }
}
