<?php

namespace Trive\AdaptiveResize\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

use Magento\Framework\App\Helper\Context;
use Magento\Catalog\Helper\ImageFactory as HelperFactory;
use Trive\AdaptiveResize\Model\Product\ImageFactory as ModelFactory;

class Image extends \Magento\Framework\App\Helper\AbstractHelper
{

    protected $_helperFactory;

    protected $_modelFactory;

    public function __construct(
        Context $context,
        HelperFactory $helperFactory,
        ModelFactory $modelFactory
    ){
        $this->_helperFactory = $helperFactory;
        $this->_modelFactory = $modelFactory;
        parent::__construct($context);
    }

    /**
     * Initialize Helper to work with Image
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param string $imageId
     * @param array $attributes
     * @return $this
     */
    public function init($product,$imageId,$attributes = null){

        /** @var \Magento\Catalog\Helper\Image $helper */
        $helper = $this->_helperFactory->create();

        $helper->init($product, $imageId);

        return $this;

    }

    public function adaptiveResize($width, $height = null)
    {

        /**
         * @var \Trive\AdaptiveResize\Model\Product\Image
         */
        $model = $this->_modelFactory->create();

//        $model->setWidth($width)
//            ->setHeight((!is_null($height)) ? $height : $width)
//            ->setKeepAspectRatio(true)
//            ->setKeepFrame(false)
//            ->setConstrainOnly(false);

        return $model->adaptiveResize()->getUrl();
    }


}