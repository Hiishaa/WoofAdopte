<?php

namespace App\DataFixtures;

use App\Entity\Dog;
use App\Repository\AnnouncementRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DogFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct(AnnouncementRepository $announcementRepository)
    {
        $this->announcementRepository = $announcementRepository;
    }

    public function load(ObjectManager $manager): void
    {

        $announcements = $this->announcementRepository->findAll();
        $dogs = [
            (new Dog())
                ->setName("Kiro")
                ->setIsLof(0)
                ->setBackground("Il provient d'un élevage de la campagne")
                ->setIsPetFriendly(1)
                ->setDescription("Une boule d'énergie pour égailler votre vie")
                ->setIsAdopted(0),
            (new Dog())
                ->setName("Doggo")
                ->setIsLof(1)
                ->setBackground("Renifleur de coke à Medellin")
                ->setIsPetFriendly(0)
                ->setDescription("Très vif mais à quelques tocs à cause de son métier")
                ->setIsAdopted(0),
            (new Dog())
                ->setName("Kim")
                ->setIsLof(1)
                ->setBackground("A été abandonnée au bord de la route")
                ->setIsPetFriendly(1)
                ->setDescription("Est très câline")
                ->setIsAdopted(0),
            (new Dog())
                ->setName("Brasco")
                ->setIsLof(0)
                ->setBackground("Il gère un cartel")
                ->setIsPetFriendly(1)
                ->setDescription("Pire ennemi de Doggo")
                ->setIsAdopted(0),
            (new Dog())
                ->setName("Pazzi")
                ->setIsLof(0)
                ->setBackground("Retrouvé dans une poubelle derrière une pizzeria")
                ->setIsPetFriendly(1)
                ->setDescription("Il est traumatisé des pizzas, il est donc obèse, il aboie aussi avec un accent et il a une grande gesture")
                ->setIsAdopted(1),
        ];
        foreach ($dogs as $dog) {
            $nb = mt_rand(0, count($announcements) - 1);

            $dog->setAnnouncement($announcements[$nb]);
            $manager->persist($dog);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            AnnouncementFixtures::class,
        ];
    }
}