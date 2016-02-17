<?php
namespace Mouf\Utils\Graphics\ImagineAddons;


use Imagine\Filter\FilterInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Maagi\ImagineFocalResizer\FocalPoint;
use Maagi\ImagineFocalResizer\Resizer;

class FocalCropFilter implements FilterInterface{

    /**
     * @var BoxInterface
     */
    private $size;

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
        $resizer = new Resizer();


        //TODO: find a better way to retrieve these params (x &  y)
        if (isset($_GET["x"])){
            $xFocus = $_GET["x"];
            $focalX = 2 * $xFocus / $image->getSize()->getWidth() - 1;
        }else{
            $focalX = 0;
        }
        if (isset($_GET["y"])){
            $yFocus = $_GET["y"];
            $focalY = 2 * $yFocus / $image->getSize()->getHeight() - 1;
        }else{
            $focalY = 0;
        }


        return $resizer->resize(
            $image,
            // target size
            $this->size,
            // crop around the point at 50% to the right and 70% to the top
            new FocalPoint($focalX, $focalY),
            // Imagine filter for resize (optional)
            ImageInterface::FILTER_UNDEFINED
        );
    }

}