<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-Fr');
        for ($i=0; $i < 100; $i++) { 
            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setDescription($faker->text(1000));
            $manager->persist($post);
            $manager->flush();
        }
    }
}
