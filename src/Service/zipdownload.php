<?php

namespace App\Service;
use App\Entity\Order;
use App\Entity\Photo;
use App\Entity\PhotoGroup;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Storage\FileSystemStorage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class ZipDownload
{
private StorageInterface $storage;
private PhotoGroup $photoGroup;
private ParameterBagInterface $parameterBag;

public function __construct( ParameterBagInterface $parameterBag)
{
    
    $this->parameterBag = $parameterBag;

   
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
        $zipName = $folderZip. $order->getId().'_' .$order->getUsers()->getLastname(). '.zip';
        

        //récupération des photos en fonction du forfait
        if($order->getForfait()){
            switch($order->getForfait()->getName()){
                case 'Gratuite':
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    $photos = [];
                    break;
                case 'Champion':
                    $photos = $order->getLicencie()->getPhotos()->slice(0, 2);
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    break;
                case 'Prestige':
                    $photos = $order->getLicencie()->getPhotos()->slice(0, 4);
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    break;
                default:
                    $photos = [];
                    break;
                
                

        }
    }
    
    
//ajout de photo de groupe si existant
    // if($photoGroupe){
        // $pathGroup = $this->photoGroupe->getPhotoGroupFile();
       
    //     $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
    //     $zip->addFile('uploads/photos/' . $photoGroupe->getPhotoGroup(), $photoGroupe->getPhoto());
    //     $zip->close();
    // }

    
    //si les photos sont existantes, on les ajoute au zip
    if (count($photos) > 0) {
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        $fileNames = [];
        foreach ($photos as $photo) {
            
            array_push($fileNames,$photo->getPhotoFile()->getPathName()) ;
            $zip->addFile($photo->getPhotoFile()->getPathName());
        }
        
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            array_push($fileNames,$filename) ;
        }
        dd($fileNames);

        $zip->close();
    }
        return $zipName;
    }
}
