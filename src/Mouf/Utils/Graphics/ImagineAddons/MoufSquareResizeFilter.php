<?php
namespace Mouf\Utils\Graphics\ImagineAddons;

use Imagine\Filter\FilterInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Box;
use Imagine\Image\Point;

class MoufSquareResizeFilter implements FilterInterface{

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function apply(ImageInterface $image){
        $oldSize = $image->getSize();
        $oWidth = $oldSize->getWidth();
        $oHeight = $oldSize->getHeight();
        
        if($oHeight > $oWidth) {
            $size = $oWidth;
            $nHeight = ceil(($oHeight - $size) / 2);
            $nWidth = 0;
        }
        else {
            $size = $oHeight;
            $nWidth = ceil(($oWidth - $size) / 2);
            $nHeight = 0;
        }
        
        $newSize = new Box($size, $size);
        $position = new Point($nWidth, $nHeight);
        
        return $image->crop($position, $newSize);
    }
}
