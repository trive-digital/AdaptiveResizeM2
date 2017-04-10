
# Image Resize and Crop for Magento 2

_Based on (AdaptiveResize)[https://github.com/obukhow/AdaptiveResize] for Magento 1_

Examples:
  1.  $this->helper('Trive\AdaptiveResize\Helper\Image')->init($_product)->adaptiveResize(240,300);
  2.  $this->helper('Trive\AdaptiveResize\Helper\Image')->init($_product,'category_page_grid')->adaptiveResize(240,300);
  3.  $this->helper('Trive\AdaptiveResize\Helper\Image')->init($_product,'category_page_grid')->setCropPosition('top')->adaptiveResize(240);

You can use following parameters with setCropPosition function:
  - top
  - top-left
  - top-right
  - bottom
  - bottom-left
  - bottom-right
  - center
  - center-left
  - center-right

