<?php
namespace Mouf\Utils\Graphics\ImagineAddons;


use Imagine\Filter\FilterInterface;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Maagi\ImagineFocalResizer\FocalPoint;
use Maagi\ImagineFocalResizer\Resizer;

/**
 * This filter is a wrapper around the maagi/imagine-focalresizer package.
 * It allows the ImagePresetDisplayer instances to add a filter that will
 * crop the image from a "user defined" center rather then the default center.
 *
 * Just add x & y parameters in the URL (didn't found better solution from now).
 * X and Y will are absolute pixel coordinates in the original image.
 *
 * Note : the input image resized before the crop happens.
 * It is resized so that at least on of the dimension fits the bounding box ($size)
 *
 * Class FocalCropFilter
 * @package Mouf\Utils\Graphics\ImagineAddons
 */
class FocalCropFilter implements FilterInterface{

    /**
     * @var BoxInterface
     */
    private $size;

    /**
     * @param BoxInterface $size : the final size of the image
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
            $image, //input image
            $this->size, // target size
            new FocalPoint($focalX, $focalY), // crop around the point at $focalX % top and $focalY % right from the center
            ImageInterface::FILTER_UNDEFINED  // Imagine filter for resize (optional)
        );
    }

}
