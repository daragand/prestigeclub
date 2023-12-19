<?php

namespace App\services;

use App\Entity\Order;


class ZipDownload
{
    public function zipCreate(Order $order)
    {
        $zip = new \ZipArchive();
        $zipName = 'zip/' . $order->getId().'_' .$order->getUsers()->getLastname(). '_'. '.zip';

        //récupération des photos en fonction du forfait
        if($order->getForfait()){
            switch($order->getForfait()->getName()){
                case 'Gratuite':
                    $photoGroupe = $order->getPhotos()->slice(0, 10);
                    break;
                case 'Champion':
                    $photos = $order->getPhotos()->slice(0, 1);
                    break;
                case 'Prestige':
                    $photos = $order->getPhotos()->slice(0, 30);
                    break;
                default:
                    $photos = $order->getPhotos()->slice(0, 10);
                    break;
                
                

        }
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
