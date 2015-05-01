<?php
namespace Mouf\Utils\Graphics\ImagineAddons;


use Imagine\Filter\FilterInterface;
use Imagine\Image\AbstractImagine;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

class MoufResizeFilter implements FilterInterface{

    /**
     * @var BoxInterface
     */
    private $size;

    /**
     * @var bool
     */
    private $mustFitInside = true;

    /**
     * @param BoxInterface $size
     */
    function __construct(BoxInterface $size)
    {
        $this->size = $size;
    }

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function apply(ImageInterface $image){
        $oldSize = $image->getSize();
        $oWidth = $oldSize->getWidth();
        $oHeight = $oldSize->getHeight();

        $xRation = (float) $oWidth / $this->size->getWidth();
        $yRation = (float) $oHeight / $this->size->getHeight();

        $finalRatio = $this->mustFitInside ? max(array($xRation, $yRation)) : min(array($xRation, $yRation));;

        $newWidth = $oWidth / $finalRatio;
        $newHeight = $oHeight / $finalRatio;

        $newImageWidth = $newWidth;
        $newImageHeight = $newHeight;

        return $image->resize($image->getSize()->widen($newImageWidth));
    }

    /**
     * @param boolean $mustFitInside
     */
    public function setMustFitInside($mustFitInside)
    {
        $this->mustFitInside = $mustFitInside;
    }

}