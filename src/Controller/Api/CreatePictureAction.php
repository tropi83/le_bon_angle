<?php

namespace App\Controller\Api;

use App\Entity\Picture;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Vich\UploaderBundle\Storage\StorageInterface;

#[AsController]
final class CreatePictureAction extends AbstractController
{
    public function __invoke(Request $request, AdvertRepository $advertRepository, StorageInterface $storage): Picture
    {
        $uploadedFile = $request->files->get('file');
        $advertId = $request->request->get('advert');

        $advert = $advertRepository->findOneBy([
            'id' => $advertId
        ]);

        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture();
        $picture->file = $uploadedFile;
        if($advert){
            $picture->setAdvert($advert);
        }

        return $picture;
    }
}