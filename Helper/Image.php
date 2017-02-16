<?php

namespace Trive\AdaptiveResize\Helper;

class Image extends \Magento\Catalog\Helper\Image
{
    const IMAGE_ID = 'category_page_grid';

    /**
     * Product image factory
     *
     * @var \Magento\Catalog\Model\Product\ImageFactory
     */
    protected $_productImageFactory;

    /**
     * @var \Trive\AdaptiveResize\Model\Product\ImageFactory
     */
    protected $_modelImageFactory;

    /**
     * @var
     */
    protected $_scheduleAdaptiveResize;

    /**
     * Crop position
     *
     * @var
     */
    protected $_cropPosition;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Catalog\Model\Product\ImageFactory $productImageFactory
     * @param \Magento\Framework\View\Asset\Repository $assetRepo
     * @param \Magento\Framework\View\ConfigInterface $viewConfig
     * @param \Trive\AdaptiveResize\Model\Product\ImageFactory $imageFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\Product\ImageFactory $productImageFactory,
        \Magento\Framework\View\Asset\Repository $assetRepo,
        \Magento\Framework\View\ConfigInterface $viewConfig,
        \Trive\AdaptiveResize\Model\Product\ImageFactory $imageFactory
    ){
        $this->_modelImageFactory = $imageFactory;
        parent::__construct($context,$productImageFactory,$assetRepo,$viewConfig);
    }

    /**
     * Set new model for adaptive resize
     *
     * @return $this
     */
    protected function _setModel()
    {
        $this->_model = $this->_modelImageFactory->create();
        return $this;
    }

    /**
     * @param null $width
     * @param null $height
     * @return string
     */
    public function adaptiveResize($width = null, $height = null)
    {
        if(is_null($width)){
            $width = $this->getAttribute('width');
        }

        if(is_null($height)){
            $height = !is_null($width) ? $width : $this->getAttribute('height');
        }

        $this->_getModel()
            ->setWidth($width)
            ->setHeight($height)
            ->setKeepAspectRatio(true)
            ->setKeepFrame(false)
            ->setConstrainOnly(false);
        $this->_scheduleAdaptiveResize = true;

        return $this->getUrl();
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @param null $imageId
     * @param array $attributes
     * @return $this
     */
    public function init($product, $imageId = null, $attributes = [])
    {
        $this->_reset();
        $this->_setModel();

        $imageId = is_null($imageId) ? self::IMAGE_ID : $imageId;

        $this->attributes = array_merge(
            $this->getConfigView()->getMediaAttributes('Magento_Catalog', self::MEDIA_TYPE_CONFIG_NODE, $imageId),
            $attributes
        );

        $this->setProduct($product);
        $this->setImageProperties();
        $this->setWatermarkProperties();

        return $this;
    }

    /**
     * Set crop position
     *
     * @param string $position top, top-left, top-right, bottom, bottom-left, bottom-right, or center, center-left, center-right
     *
     * @return \Trive\AdaptiveResize\Helper\Image
     */
    public function setCropPosition($position)
    {
        $this->_cropPosition = $position;
        return $this;
    }

    /**
     * @return $this|void
     */
    protected function _reset()
    {
        $this->_scheduleAdaptiveResize = false;
        $this->_cropPosition = 0;
        parent::_reset();
    }

    /**
     * @return $this
     */
    protected function applyScheduledActions()
    {
        $this->initBaseFile();
        if ($this->isScheduledActionsAllowed()) {
            $model = $this->_getModel();
            if ($this->_scheduleRotate) {
                $model->rotate($this->getAngle());
            }
            if ($this->_scheduleResize) {
                $model->resize();
            }
            if ($this->_cropPosition) {
                $this->_getModel()->setCropPosition($this->_cropPosition);
            }
            if ($this->_scheduleAdaptiveResize) {
                $model->adaptiveResize();
            }
            if ($this->getWatermark()) {
                $model->setWatermark($this->getWatermark());
            }
            $model->saveFile();
        }

        return $this;
    }

}