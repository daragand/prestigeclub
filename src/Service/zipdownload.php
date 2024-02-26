<?php

namespace App\Service;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\PhotoGroup;
use App\Repository\PhotoGroupRepository;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Storage\FileSystemStorage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ZipDownload
{
private StorageInterface $storage;
private PhotoGroup $photoGroup;
private PhotoGroupRepository $photoGroupRepository;
private ParameterBagInterface $parameterBag;

public function __construct( ParameterBagInterface $parameterBag,PhotoGroupRepository $photoGroupRepository)
{
    
    $this->parameterBag = $parameterBag;
    $this->photoGroupRepository = $photoGroupRepository;

   
}


    public function zipCreate(Order $order)
    {
       $folderZip = $this->parameterBag->get('dossier_zip');
       
       
       
if (!file_exists($folderZip)) {
    mkdir($folderZip, 0755, true);
} else {
    chmod($folderZip, 0755);
}
        
        $zip = new \ZipArchive();
        $zipName = $folderZip. uniqid().'_' .$order->getUsers()->getLastname().'_' .$order->getUuidOrder(). '.zip';
        
        //récupération de la ou des photo(s) du groupe lié au club. Cette recherche s'assure de ne pas réprendre de photos liés à un autre club ou une autre section du club
        $photoGroupe = $this->photoGroupRepository->createQueryBuilder('phg')
        ->join('phg.club', 'club')
        ->join('phg.groupID','groupe')
        ->where('club.id = :clubID')
        ->andWhere('groupe.id = :groupID')
        ->setParameter('clubID', $order->getLicencie()->getClub()->getId())
        ->setParameter('groupID',$order->getLicencie()->getGroupes()->getId() )
        ->getQuery()
        ->getResult();

        //récupération des photos individuelles en fonction du forfait.Elle permet surtout de ne pas récupérer de photos individuelles si le forfait est de type 'Gratuit'.
        // TODO : a voir à terme pour une gestion selon le nombre de photos autorisées par le forfait. En plaçant dans l'entité Forfait ne nombre de photos individuelles autorisées, nous pourrons nous en servir comme critère de recherche.
        if($order->getForfait()){
            switch($order->getForfait()->getName()){
                case 'Gratuite':
                    $photos = [];
                    break;
                case 'Champion':
                    $photos = $order->getPhotos();
                    break;
                case 'Prestige':
                    $photos = $order->getPhotos();
                    break;
                default:
                    $photos = [];
                    break;
                
                

        }
    }


    //si les photos individuelles ou photos de groupes sont existantes, on les ajoute au zip
    if ($photoGroupe || count($photos) > 0) {
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        //gestion de la photo de groupe
        if($photoGroupe){
            foreach($photoGroupe as $key => $itemPhGroupe){
                $filePhGroupe= $itemPhGroupe->getPhotoGroupFile()->getPathName();
                //TODO : à supprimer au lancement en ligne
                $goodFilePath = str_replace('/','\\',$filePhGroupe);
               if(file_exists($goodFilePath) ){ 
                $zip->addFile($goodFilePath,'photo_de_Groupe'.$key.'.jpg');
               }  
            }
        }




        $fileNames = [];
        foreach ($photos as $key =>$photo) {
            //TODO : au lancement en ligne, retirer le changement de slash par antislash
            $filePath = $photo->getPhotoFile()->getPathName();
            $goodFilePath = str_replace('/','\\',$filePath); //
            
            array_push($fileNames,$goodFilePath) ;
            //récupération du chemin du fichier et modification du nom du fichier
            if(file_exists($goodFilePath) ){
                $zip->addFile($goodFilePath,'photo_'.$key.'_'.$photo->getLicencie()->getLastName().'_'.$photo->getLicencie()->getFirstName().'.jpg');
            }
           
        }

        $zip->close();
    }
        return $zipName;
    }
}
