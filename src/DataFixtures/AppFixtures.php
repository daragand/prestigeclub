<?php

namespace App\DataFixtures;

use App\Entity\Club;
use App\Entity\Address;
use App\Entity\Group;
use App\Entity\Licencie;
use App\Entity\PhotoGroup;
use App\Entity\Cart;
use App\Entity\Forfait;
use App\Entity\Livret;
use App\Entity\Options;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\OrderStatus;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create('fr_FR');


        ////////////////////////////////  Options  //////////////////////////////////////
        /**
         * Création des options
         */
        $options = [
            [
                'name' => '20 x 30 cm',
                'description' => 'Aggrandissement de la photo de votre choix en 20 x 30 cm',
                'price' => 7.99,
            ],
            [
                'name' => '30 x 45 cm',
                'description' => 'Aggrandissement de la photo de votre choix en 30 x 45 cm',
                'price' => 10.50,
            ]
        ];

        //les options sont instanciés et enregistrés dans un tableau
        $objectsOptions = [];

        //boucle pour créer les options
        foreach ($options as $option) {
            $objectOption = new Options();
            $objectOption->setName($option['name'])
                ->setDescription($option['description'])
                ->setPrice($option['price']);
            array_push($objectsOptions, $objectOption);
            $manager->persist($objectOption);
        }

        ////////////////////////////////  Forfait  //////////////////////////////////////
        $forfaits = [
            [
                'name' => 'Gratuite',
                'description' => 'Contient 1 photo d\'équipe',
                'price' => 0,
            ],
            [
                'name' => 'Champion',
                'description' => 'Contient 2 photos individuelles et 1 photo d\'équipe',
                'price' => 25,
            ],
            [
                'name' => 'Prestige',
                'description' => 'Contient 4 photos individuelles et 1 photo d\'équipe',
                'price' => 35,
            ],

        ];

        //les forfaits sont instanciés et enregistrés dans un tableau
        $objectsForfaits = [];

        //boucle pour créer les forfaits
        foreach ($forfaits as $forfait) {
            $objectForfait = new Forfait();
            $objectForfait->setName($forfait['name'])
                ->setDescription($forfait['description'])
                ->setPrice($forfait['price']);
            array_push($objectsForfaits, $objectForfait);
            $manager->persist($objectForfait);
        }

        ////////////////////////////////  OrderStatus  //////////////////////////////////////
        $orderStatus = [
            'Annulée',
            'Payée',
            'Traitée',
            'Finalisée',
            'En cours de traitement',
        ];

        //les orderStatus sont instanciés et enregistrés dans un tableau
        $objectsOrderStatus = [];

        //boucle pour créer les orderStatus
        foreach ($orderStatus as $status) {
            $objectOrderStatus = new OrderStatus();
            $objectOrderStatus->setName($status);
            array_push($objectsOrderStatus, $objectOrderStatus);
            $manager->persist($objectOrderStatus);
        }

        ////////////////////////////////  Address  //////////////////////////////////////
        /**
         * Création d'un tableau qui contiendra les adresses.
         */
        $objectAddresses = [];
        for ($j = 0; $j < 100; $j++) {
            $objectAddress = new Address();
            $objectAddress->setAddress($faker->streetAddress)
                ->setZip($faker->postcode)
                ->setCity($faker->city);
            array_push($objectAddresses, $objectAddress);
            $manager->persist($objectAddress);
        }

        /////////////////   Club   ////////////////////////
        $objectClubs = [];
        //ajout de 50 clubs
        for ($i = 0; $i < 20; $i++) {
            $objectClub = new Club();
            $objectClub->setName($faker->company)
                ->setLogo($faker->imageUrl(640, 480, 'icon du Club'))
                ->setAddress($objectAddresses[$faker->numberBetween(0, 99)]);
            array_push($objectClubs, $objectClub);
            $manager->persist($objectClub);
        }






        //////////////////////////////  Groupes  //////////////////////////////////////

        $groups = [
            'Baby Club (3-5 ans)',
            'Mini-poussins (6-8 ans)',
            'Poussins (9-10 ans)',
            'Benjamins (11-12 ans)',
            'Minimes (13-14 ans)',
            'Cadets (15-16 ans)',
            'Juniors (17-18 ans)',
            'Seniors (19 ans et plus)',
        ];

        //les groupes sont instanciés et enregistrés dans un tableau
        $objectsGroups = [];

        //boucle pour créer les groupes
        foreach ($groups as $group) {
            $objectGroup = new Group();
            $objectGroup->setName($group);
            /**
             * ajout d'une quantité aléatoire de clubs dans chaque groupe
             */
            for ($i = 0; $i < rand(0, 7); $i++) {
                $objectGroup->addClub($objectClubs[$faker->numberBetween(0, ((count($objectClubs))-1))]);
            }
            array_push($objectsGroups, $objectGroup);
            $manager->persist($objectGroup);
        }

        //////////////////////////////  Licencies  ///////////////////////////////////////

        /**
         * Ajout des licenciés dans les clubs
         */
         $objectLicencies = [];

         for ($i = 0; $i < 100; $i++) {
            //numéro aléatoire pour choisir un club
            $randNumber =rand(0, (count($objectClubs)-1));

             $objectLicencie = new Licencie();
             $objectLicencie->setFirstname($faker->firstName)
                 ->setLastname($faker->lastName)
                 ->setBirthdate($faker->dateTimeBetween('-20 years', '-5 years'))
                 ->setSlug($faker->slug)
                 ->addClub($objectClubs[$randNumber])
                 ;

                 //selon le nom du club, récupération des groupes correspondants
                  $groupsLicencies = $objectClubs[$randNumber]->getGroups();
                //ajout du groupe dans le licencié
                $randomGroup = $groupsLicencies[$faker->numberBetween(0, count($groupsLicencies)-1)];
                if ($randomGroup instanceof Group) {
                    $objectLicencie->addGroupe($randomGroup);
                }

                //persistence de l'objet et ajout dans le tableau
                array_push($objectLicencies, $objectLicencie);
                $manager->persist($objectLicencie);

                  
         }
             

        //////////////////////////////  PhotoGroup  ///////////////////////////////////////

        /**
         * Ajout des photos de groupe dans les clubs
         */

         $objectPhotoGroups = [];

        for ($i = 0; $i < 100; $i++) {
            //numéro aléatoire pour choisir un club
            $randNumber =rand(0, (count($objectClubs)-1));

            $objectPhotoGroup = new PhotoGroup();
            $objectPhotoGroup->setPath($faker->imageUrl(640, 480, 'photo de groupe'))
                ->setClub($objectClubs[$randNumber])
                ->setGroupID($objectsGroups[$faker->numberBetween(0, count($objectsGroups)-1)]);
            array_push($objectPhotoGroups, $objectPhotoGroup);
                $manager->persist($objectPhotoGroup);

    }

        ///////////////////  Photo   ////////////////////////
      $objectPhotos = [];

      foreach ($objectLicencies as $licencie) {
        //ajout pour chaque licencié de 4 photos
        for ($i = 0; $i < 4; $i++) {
          $objectPhoto = new Photo();
          $objectPhoto->setPath($faker->imageUrl(640, 480, 'photo de groupe'))
              ->setDatePublication($faker->dateTimeBetween('-1 years', 'now'))
              ->setDownloaded($faker->boolean)
              ->setLicencie($licencie);
          array_push($objectPhotos, $objectPhoto);
          $manager->persist($objectPhoto);
        }
      }

      //////////////////////////////  User  ///////////////////////////////////////

      $objectUsers = [];
      $roles = [
        ['ROLE_USER'],
        ['ROLE_ADMIN'],
        ['ROLE_CLUB'],
      ];

      for($i = 0; $i < 20; $i++) {
        $objectUser = new User();
        $objectUser->setEmail($faker->email)
            ->setFirstname($faker->firstName)
            ->setLastname($faker->lastName)
            ->setPassword($faker->password)
            ->setAddress($objectAddresses[$faker->numberBetween(0, 99)])
            ->setRoles($roles[$faker->numberBetween(0, 2)]);
            //s'il s'agit d'un parent, je lui ajoute un licencié
            if($objectUser->getRoles() == ['ROLE_USER']) {
              $objectUser->addLicency($objectLicencies[$faker->numberBetween(0, (count($objectLicencies)-1))]);
            }
            //s'il s'agit d'un club, je lui ajoute un club
            if($objectUser->getRoles() == ['ROLE_CLUB']) {
              $objectUser->addClub($objectClubs[$faker->numberBetween(0, (count($objectClubs)-1))]);
            }
        array_push($objectUsers, $objectUser);
        $manager->persist($objectUser);
      }
      /////////////////////////////////  Livret  ///////////////////////////////////////

      $objectLivrets = [];

        foreach ($objectLicencies as $licencie) {
            $objectLivret = new Livret();
            $objectLivret->setLicencie($licencie)
                ->setPath($faker->imageUrl(640, 480, 'livret'))
                ;
            array_push($objectLivrets, $objectLivret);
            $manager->persist($objectLivret);
        }





    //////////////////////////////  Cart  ///////////////////////////////////////

    $objectCarts = [];

    $parents = [];
    foreach ($objectUsers as $user) {
        if($user->getRoles() == ['ROLE_USER']) {
            array_push($parents, $user);
        }
    }

        foreach($parents as $parent) {
            $objectCart = new Cart();
            $objectCart->setUser($parent);
            array_push($objectCarts, $objectCart);
            $manager->persist($objectCart);

        }




    $manager->flush();
}
}