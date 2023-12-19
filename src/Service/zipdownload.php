<?php

namespace App\services;
use App\Entity\PhotoGroup;
use App\Entity\Photo;
use App\Entity\Order;
use Vich\UploaderBundle\Storage\FileSystemStorage;
use Vich\UploaderBundle\Storage\StorageInterface;


class ZipDownload
{
    public static function zipCreate(Order $order)
    {
        $zip = new \ZipArchive();
        $zipName = 'zip_' . $order->getId().'_' .$order->getUsers()->getLastname(). '_'. '.zip';

        //récupération des photos en fonction du forfait
        if($order->getForfait()){
            switch($order->getForfait()->getName()){
                case 'Gratuite':
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    $photos = [];
                    break;
                case 'Champion':
                    $photos = $order->getLicencie()->getPhotos()->slice(0, 1);
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    break;
                case 'Prestige':
                    $photos = $order->getLicencie()->getPhotos()->slice(0, 3);
                    $photoGroupe = $order->getLicencie()->getGroupes()->getPhotoGroup();
                    break;
                default:
                    $photos = [];
                    break;
                
                

        }
    }
//ajout de photo de groupe si existant
    if($photoGroupe){
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        $zip->addFile('uploads/photos/' . $photoGroupe->getPhotoGroupFile(), $photoGroupe->getPhoto());
        $zip->close();
    }


    //si les photos sont existantes, on les ajoute au zip
    if (count($photos) > 0) {
        $zip->open($zipName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        
        foreach ($photos as $photo) {
            $zip->addFile('uploads/photos/' . $photo->getPhoto(), $photo->getPhoto());
        }

        $zip->close();
    }
        return $zipName;
    }
}
