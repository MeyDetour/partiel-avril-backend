<?php

namespace App\Service;

use App\Entity\Image;
use App\Entity\QrCodeImage;
use App\Repository\ImageRepository;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
class ImageService
{
    public function __construct(private UploaderHelper $helper, private CacheManager $cacheManager, private ImageRepository $imageRepository){

    }
    public  function getImageUrl( Image|QrCodeImage $image,$filter){
         return $this->cacheManager->generateUrl($this->helper->asset($image),$filter);
    }
}