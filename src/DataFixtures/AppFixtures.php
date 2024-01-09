<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Cart;
use App\Entity\Club;
use App\Entity\User;
use App\Entity\Group;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\Sport;
use App\Entity\Livret;
use App\Entity\Address;
use App\Entity\Forfait;
use App\Entity\Options;
use App\Entity\Licencie;
use App\Entity\OptionList;
use App\Entity\PhotoGroup;
use App\Entity\OrderStatus;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

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

        ///////////////////// Sports ////////////////////////
        $listSports = [
            'Athlétisme',
            'Badminton',
            'Basketball',
            'Boxe',
            'Cyclisme',
            'Equitation',
            'Escrime',
            'Football',
            'Gymnastique',
            'Handball',
            'Hockey',
            'Judo',
            'Karaté',
            'Natation',
            'Pétanque',
            'Rugby',
            'Taekwondo',
            'Tennis',
            'Tennis de table',
            'Tir à l\'arc',
            'Volleyball',
        ];
        
        $sportObjects = [];

        foreach ($listSports as $sport) {
            $sportObject = new Sport();
            $sportObject->setName($sport);
            array_push($sportObjects, $sportObject);
            $manager->persist($sportObject);
        }




        /////////////////   Club   ////////////////////////
        $objectClubs = [];
        //ajout de 50 clubs
        for ($i = 0; $i < 20; $i++) {
            $objectClub = new Club();
            $objectClub->setName($faker->company)
                ->setLogo($faker->imageUrl(640, 480, 'icon du Club'))
                ->setSport($sportObjects[$faker->numberBetween(0, (count($sportObjects) - 1))])
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
                $objectGroup->addClub($objectClubs[$faker->numberBetween(0, ((count($objectClubs)) - 1))]);
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
            $randNumber = rand(0, (count($objectClubs) - 1));

            $objectLicencie = new Licencie();
            $objectLicencie->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setBirthdate($faker->dateTimeBetween('-20 years', '-5 years'))
                ->setSlug($faker->slug)
                ->setEmail($faker->email)
                ->setUpdatedAt($faker->dateTimeBetween('-1 years', 'now'))
                ->setClub($objectClubs[$randNumber]);

            //selon le nom du club, récupération des groupes correspondants
            $groupsLicencies = $objectClubs[$randNumber]->getGroups();
            //ajout du groupe dans le licencié
            $randomGroup = $groupsLicencies[$faker->numberBetween(0, count($groupsLicencies) - 1)];
            if ($randomGroup instanceof Group) {
                $objectLicencie->setGroupes($randomGroup);
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
            $randNumber = rand(0, (count($objectClubs) - 1));

            $objectPhotoGroup = new PhotoGroup();
            $objectPhotoGroup->setPath($faker->imageUrl(640, 480, 'photo de groupe'))
                ->setClub($objectClubs[$randNumber])
                ->setDatePublication($faker->dateTimeBetween('-1 years', 'now'))
                ->setGroupID($objectsGroups[$faker->numberBetween(0, count($objectsGroups) - 1)]);
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
            ['ROLE_CLUB'],
            ['ROLE_ADMIN'],
            ['ROLE_PARENT'],
        ];

        for ($i = 0; $i < 20; $i++) {
            $objectUser = new User();
            $objectUser->setEmail($faker->email)
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setPassword($faker->password)
                ->setUuidUser($faker->uuid)
                ->setAddress($objectAddresses[$faker->numberBetween(0, 99)])
                ->setRoles($roles[$faker->numberBetween(0, 2)])
                ;
                
            //s'il s'agit d'un parent, je lui ajoute un licencié
            if (in_array('ROLE_PARENT', $objectUser->getRoles())) {
                
                $objectUser->addLicency($objectLicencies[$faker->numberBetween(0, (count($objectLicencies) - 1))]);
            }
            //s'il s'agit d'un club, je lui ajoute un club
            if (in_array('ROLE_CLUB', $objectUser->getRoles())) {
                $objectUser->addClub($objectClubs[$faker->numberBetween(0, (count($objectClubs) - 1))]);
            }
            array_push($objectUsers, $objectUser);
            $manager->persist($objectUser);
        }
        /////////////////////////////////  Livret  ///////////////////////////////////////

        $objectLivrets = [];

        foreach ($objectLicencies as $licencie) {
            $objectLivret = new Livret();
            $objectLivret->setLicencie($licencie)
                ->setPath($faker->imageUrl(640, 480, 'livret'));
            array_push($objectLivrets, $objectLivret);
            $manager->persist($objectLivret);
        }

///////////////////////////////////// OptionsList ///////////////////////////////////////

        $objectOptionsLists = [];

        for($i=0; $i<20; $i++){

        $objectOptionsList = new OptionList();
        $objectOptionsList->setPhotos($objectPhotos[$faker->numberBetween(0, (count($objectPhotos) - 1))])
            ->setOptions($objectsOptions[$faker->numberBetween(0, (count($objectsOptions) - 1))])
            ->setQuantity($faker->numberBetween(1, 2))
            ->setIsArchived($faker->boolean)
            ->setPhotos($objectPhotos[$faker->numberBetween(0, (count($objectPhotos) - 1))]);

        array_push($objectOptionsLists, $objectOptionsList);
        $manager->persist($objectOptionsList);
        }



        //////////////////////////////  Cart  ///////////////////////////////////////

        $objectCarts = [];

        $parents = [];
        foreach ($objectUsers as $user) {
            if (in_array('ROLE_PARENT', $user->getRoles())) {
               
                array_push($parents, $user);
            }
        }
        
        

        foreach ($parents as $parent) {
            
            $objectCart = new Cart();
            $objectCart->setUsers($parent)
                ->setForfait($objectsForfaits[$faker->numberBetween(0, (count($objectsForfaits) - 1))])
                ->setUuidCart($faker->uuid);
            

            //gestion des photos en fonction du forfait
            if ($objectCart->getForfait()->getName() == 'Gratuite') {
                $objectCart->setAmount(0);
            } elseif ($objectCart->getForfait()->getName() == 'Champion') {
                //gestion des options
           
                $objectCart->addOptionList($objectOptionsLists[$faker->numberBetween(0, (count($objectOptionsLists) - 1))]);
                $objectCart->setAmount($objectCart->getForfait()->getPrice() + $objectCart->getOptionLists()[0]->getOptions()->getPrice());
                //ajout de 2 photos individuelles prises aléatoirement. Je ne prends pas de photos lié à l'utilisateur par gain de temps
                
                for ($i = 0; $i < 2; $i++) {
                    $objectCart->addPhoto($objectPhotos[$faker->numberBetween(0, (count($objectPhotos) - 1))]);
                }
            } elseif ($objectCart->getForfait()->getName() == 'Prestige') {
                $objectCart->addOptionList($objectOptionsLists[$faker->numberBetween(0, (count($objectOptionsLists) - 1))]);
                $objectCart->setAmount($objectCart->getForfait()->getPrice() + $objectCart->getOptionLists()[0]->getOptions()->getPrice());
           
                //ajout de 4 photos individuelles prises aléatoirement. Je ne prends pas de photos lié à l'utilisateur par gain de temps
                for ($i = 0; $i < 4; $i++) {
                    $objectCart->addPhoto($objectPhotos[$faker->numberBetween(0, (count($objectPhotos) - 1))]);
                }
            }
                array_push($objectCarts, $objectCart);
                $manager->persist($objectCart);
        }

        //////////////////////////////  Order  ///////////////////////////////////////

        $objectOrders = [];
        //pour les options, je prends les options rattachées à un panier

        $optionsWithCarts = [];

        foreach ($objectOptionsLists as $optionList) {
            if ($optionList->getCart() != null) {
                array_push($optionsWithCarts, $optionList);
            }
        }


        //ajout de 50 commandes
        for ($i = 0; $i < 20; $i++) {
            $objectOrder = new Order();
            
            $objectOrder->setPaymentDate($faker->dateTimeBetween('-1 years', 'now'))
                ->setOrderStatus($objectsOrderStatus[$faker->numberBetween(0, (count($objectsOrderStatus) - 1))])
                ->setUsers($parents[$faker->numberBetween(0, (count($parents) - 1))])
                ->setUuidOrder($faker->uuid)
                ->setLicencie($objectLicencies[$faker->numberBetween(0, (count($objectLicencies) - 1))])
                ->setAmount($faker->randomFloat(2, 0, 100))
                ->setForfait($objectsForfaits[$faker->numberBetween(0, (count($objectsForfaits) - 1))]);

            
                
            array_push($objectOrders, $objectOrder);
            $manager->persist($objectOrder);
        }
        //ajout des options dans les commandes à partir des options rattachées à un panier
        foreach($optionsWithCarts as $optionWithCart){
            $objectOrders[$faker->numberBetween(0, (count($objectOrders) - 1))]->addOptionList($optionWithCart);
        }


        $manager->flush();
    }


}

