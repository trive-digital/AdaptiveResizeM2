<?php

namespace Trive\AdaptiveResize\Model\Product;

class Image extends \Magento\Catalog\Model\Product\Image
{
    const DEFAULT_WIDTH = 240;

    const POSITION_TOP     = 'top';
    const POSITION_TOP_LEFT     = 'top-left';
    const POSITION_TOP_RIGHT     = 'top-right';
    const POSITION_BOTTOM = 'bottom';
    const POSITION_BOTTOM_LEFT = 'bottom-left';
    const POSITION_BOTTOM_RIGHT = 'bottom-right';
    const POSITION_CENTER = 'center';
    const POSITION_CENTER_LEFT = 'center-left';
    const POSITION_CENTER_RIGHT = 'center-right';

    /**
     * Crop position from top
     *
     * @var float
     */
    protected $_topRate = 0.5;

    /**
     * Crop position from bootom
     *
     * @var float
     */
    protected $_bottomRate = 0.5;

    /**
     * Crop position from left
     *
     * @var float
     */
    protected $_leftRate = 1;

    /**
     * Crop position from right
     *
     * @var float
     */
    protected $_rightRate = 1;

    /**
     * @return $this
     */
    public function adaptiveResize()
    {

        if(is_null($this->getWidth())){
            $this->setWidth(self::DEFAULT_WIDTH);
        }

        if (is_null($this->getHeight())) {
            $this->setHeight($this->getWidth());
        }

        $processor = $this->getImageProcessor();

        $currentRatio = $processor->getOriginalWidth() / $processor->getOriginalHeight();
        $targetRatio = $this->getWidth() / $this->getHeight();

        if ($targetRatio > $currentRatio) {
            $processor->resize($this->getWidth(), null);
        } else {
            $processor->resize(null, $this->getHeight());
        }

        $diffWidth  = $processor->getOriginalWidth() - $this->getWidth();
        $diffHeight = $processor->getOriginalHeight() - $this->getHeight();

        $processor->crop(
                    floor($diffHeight * $this->_topRate),
                    floor( ($diffWidth / 2) * $this->_leftRate),
                    ceil( ($diffWidth / 2) * $this->_rightRate),
                    ceil($diffHeight * $this->_bottomRate)
                );

        return $this;
    }

    /**
     * Set crop position
     *
     * @param string $position top, bottom or center
     *
     * @return \Trive\AdaptiveResize\Model\Product\Image
     */
    public function setCropPosition($position)
    {
        switch ($position) {
            case self::POSITION_TOP:
                $this->_topRate    = 0;
                $this->_bottomRate = 1;
                $this->_leftRate = 1;
                $this->_rightRate = 1;
                break;
            case self::POSITION_TOP_LEFT:
                $this->_topRate    = 0;
                $this->_bottomRate = 1;
                $this->_leftRate = 0;
                $this->_rightRate = 2;
                break;
            case self::POSITION_TOP_RIGHT:
                $this->_topRate    = 0;
                $this->_bottomRate = 1;
                $this->_leftRate = 2;
                $this->_rightRate = 0;
                break;
            case self::POSITION_BOTTOM:
                $this->_topRate    = 1;
                $this->_bottomRate = 0;
                $this->_leftRate = 1;
                $this->_rightRate = 1;
                break;
            case self::POSITION_BOTTOM_LEFT:
                $this->_topRate    = 1;
                $this->_bottomRate = 0;
                $this->_leftRate = 0;
                $this->_rightRate = 2;
                break;
            case self::POSITION_BOTTOM_RIGHT:
                $this->_topRate    = 1;
                $this->_bottomRate = 0;
                $this->_leftRate = 2;
                $this->_rightRate = 0;
                break;
            case self::POSITION_CENTER_LEFT:
                $this->_topRate    = 0.5;
                $this->_bottomRate = 0.5;
                $this->_leftRate = 0;
                $this->_rightRate = 2;
                break;
            case self::POSITION_CENTER_RIGHT:
                $this->_topRate    = 0.5;
                $this->_bottomRate = 0.5;
                $this->_leftRate = 2;
                $this->_rightRate = 0;
                break;
            default:
                $this->_topRate    = 0.5;
                $this->_bottomRate = 0.5;
                $this->_leftRate = 1;
                $this->_rightRate = 1;
        }
        return $this;
    }

}