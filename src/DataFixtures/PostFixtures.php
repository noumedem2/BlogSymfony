<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Doe');
        $user->setLastName('Jane');
        $user->setEmail("janedoe@example.com");
        $user->setPassword('$2y$13$fLtNiWjgTKoUZbjWRwFX9.gGO1e9TH2wGpd0NUN0po.pIxiQN4m.q');
        $manager->persist($user);
        $manager->flush();

        $faker = Factory::create('fr-Fr');
        for ($i=0; $i < 100; $i++) { 
            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setDescription($faker->text(1000));
            $post->setUser($user);
            $manager->persist($post);
            $manager->flush();
        }
    }
}
