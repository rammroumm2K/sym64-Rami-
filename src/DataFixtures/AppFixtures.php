<?php
namespace App\DataFixtures;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Section;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
class AppFixtures extends Fixture
{
   private UserPasswordHasherInterface $passwordHasher;
   public function __construct(UserPasswordHasherInterface $passwordHasher)
   {
       $this->passwordHasher = $passwordHasher;
   }
   public function load(ObjectManager $manager): void
   {
       $faker = Factory::create();
       $slugify = new Slugify();
       // Création des utilisateurs...
       $users = []; // Stocke les utilisateurs pour attribuer des auteurs plus tard
       // ROLE_ADMIN
       $admin = new User();
       $admin->setUsername('admin')
             ->setEmail('admin@example.com')
             ->setFullname('Admin User')
             ->setRoles(['ROLE_ADMIN'])
             ->setPassword($this->passwordHasher->hashPassword($admin, 'admin'))
             ->setIsActive(true);
       $manager->persist($admin);
       $users[] = $admin;
       // ROLE_REDAC
       for ($i = 1; $i <= 5; $i++) {
           $redac = new User();
           $redac->setUsername("redac{$i}")
                 ->setEmail($faker->unique()->email)
                 ->setFullname($faker->name)
                 ->setRoles(['ROLE_REDAC'])
                 ->setPassword($this->passwordHasher->hashPassword($redac, "redac{$i}"))
                 ->setIsActive(true);
           $manager->persist($redac);
           $users[] = $redac;
       }
       // ROLE_USER
       for ($i = 1; $i <= 24; $i++) {
           $user = new User();
           $user->setUsername("user{$i}")
                ->setEmail($faker->unique()->email)
                ->setFullname($faker->name)
                ->setRoles(['ROLE_USER'])
                ->setPassword($this->passwordHasher->hashPassword($user, "user{$i}"))
                ->setIsActive($i % 4 !== 0);
           $manager->persist($user);
       }
       // Création des sections
       $sections = [];
       for ($i = 1; $i <= 6; $i++) {
           $section = new Section();
           $section->setTitle($title = $faker->sentence)
                   ->setSlug($slugify->slugify($title))
                   ->setDescription($faker->paragraph);
           $manager->persist($section);
           $sections[] = $section;
       }
       // Création des articles
       for ($i = 1; $i <= 160; $i++) {
           $article = new Article();
           $article->setTitle($title = $faker->sentence)
                   ->setSlug($slugify->slugify($title))
                   ->setContent($faker->paragraphs(3, true))
                   ->setCreatedAt($createdAt = $faker->dateTimeBetween('-6 months', 'now'))
                   ->setAuthor($users[array_rand($users)]) // Sélectionne un auteur aléatoire
                   ->setSection($sections[array_rand($sections)]); // Section aléatoire
           // 3 chances sur 4 que l'article soit publié
           if (rand(0, 3) > 0) {
               $article->setPublishedAt($faker->dateTimeBetween($createdAt));
           }
           $manager->persist($article);
       }
       // Exécute les insertions dans la base de données
       $manager->flush();
   }
}