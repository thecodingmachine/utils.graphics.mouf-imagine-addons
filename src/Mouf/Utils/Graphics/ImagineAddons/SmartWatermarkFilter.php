<?php
namespace Mouf\Utils\Graphics\ImagineAddons;


use Imagine\Filter\FilterInterface;
use Imagine\Image\AbstractImagine;
use Imagine\Image\Box;
use Imagine\Image\BoxInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 * This filter is used to add a watermark upon an image.
 *
 * By default, the watermark is applied. If you want to disable, for any reason, this watermarking,
 * just add a w parameter in the URL with the value 0 (didn't found better solution from now).
 * w = 0 : don't apply the watermark
 * else : apply the watermark
 *
 * Class SmartWatermarkFilter
 * @package Mouf\Utils\Graphics\ImagineAddons
 */
class SmartWatermarkFilter implements FilterInterface{

    const CENTER = "center";
    const TOP = "top";
    const TOP_LEFT = "top-left";
    const TOP_RIGHT = "top-right";
    const LEFT = "left";
    const RIGHT = "right";
    const BOTTOM = "bottom";
    const BOTTOM_LEFT = "bottom-left";
    const BOTTOM_RIGHT = "bottom-right";

    /**
     * @var string
     */
    private $position;

    /**
     * @var BoxInterface
     */
    private $waterMarkSize;

    /**
     * @var string
     */
    private $waterMarkPath;

    /**
     * @var AbstractImagine
     */
    private $imagine;

    /**
     * @var int
     */
    private $relativeSize;

    /**
     * The size of the watermark relatively to the original image (between 0 and 1).
     *
     * @param int $relativeSize
     */
    public function setRelativeSize($relativeSize) {
        $this->relativeSize = $relativeSize;
    }

    /**
     * @param string $position
     * @param BoxInterface $waterMarkSize
     * @param string $waterMarkPath
     * @param AbstractImagine $imagine
     */
    function __construct($position, BoxInterface $waterMarkSize, $waterMarkPath, AbstractImagine $imagine)
    {
        $this->position = $position;
        $this->waterMarkSize = $waterMarkSize;
        $this->waterMarkPath = $waterMarkPath;
        $this->imagine = $imagine;
    }

    /**
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function apply(ImageInterface $image){
        $size = $image->getSize();
        if (isset($this->relativeSize)) {
            $watermarkBox = new Box($size->getWidth() * $this->relativeSize, $size->getHeight() * $this->relativeSize);
        } else {
            $watermarkBox = $this->waterMarkSize;
        }
        $watermark = $this->imagine->open(ROOT_PATH . $this->waterMarkPath)->thumbnail($watermarkBox);
        $watermarkSize = $watermark->getSize();

        if (isset($_GET["w"]) && $_GET["w"] == 0) {
            return $image;
        } else {
            switch ($this->position) {
                case 'top-left':
                    $x = 0;
                    $y = 0;
                    break;
                case 'top':
                    $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                    $y = 0;
                    break;
                case 'top-right':
                    $x = $size->getWidth() - $watermarkSize->getWidth();
                    $y = 0;
                    break;
                case 'left':
                    $x = 0;
                    $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                    break;
                case 'center':
                    $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                    $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                    break;
                case 'right':
                    $x = $size->getWidth() - $watermarkSize->getWidth();
                    $y = ($size->getHeight() - $watermarkSize->getHeight()) / 2;
                    break;
                case 'bottom-left':
                    $x = 0;
                    $y = $size->getHeight() - $watermarkSize->getHeight();
                    break;
                case 'bottom':
                    $x = ($size->getWidth() - $watermarkSize->getWidth()) / 2;
                    $y = $size->getHeight() - $watermarkSize->getHeight();
                    break;
                case 'bottom-right':
                    $x = $size->getWidth() - $watermarkSize->getWidth();
                    $y = $size->getHeight() - $watermarkSize->getHeight();
                    break;
                default:
                    throw new \InvalidArgumentException("Unexpected position '$this->position'");
                    break;
            }
            return $image->paste($watermark, new Point($x, $y));
        }

    }

}