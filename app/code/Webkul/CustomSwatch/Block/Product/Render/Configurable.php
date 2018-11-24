<?php
namespace Webkul\CustomSwatch\Block\Product\Render;

class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{
   public function getJsonSwatchConfig()
    {
        $attributesData = $this->getSwatchAttributesData();
        $allOptionIds = $this->getConfigurableOptionsIds($attributesData);
        $swatchesData = $this->swatchHelper->getSwatchesByOptionsId($allOptionIds);

        $config = [];
        foreach ($attributesData as $attributeId => $attributeDataArray) {
            if (isset($attributeDataArray['options'])) {
                $config[$attributeId] = $this->addSwatchDataForAttribute(
                    $attributeDataArray['options'],
                    $swatchesData,
                    $attributeDataArray,
                    $attributeId
                );
            }
        }

        return $this->jsonEncoder->encode($config);
    }
    protected function addSwatchDataForAttribute(
        array $options,
        array $swatchesCollectionArray,
        array $attributeDataArray,
        $attributeId=null
    ) {
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance(); //instance of\Magento\Framework\App\ObjectManager
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface'); 
        $currentStore = $storeManager->getStore();
        $mediaUrl = $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'/customswatch/';

        $result = [];
        foreach ($options as $optionId => $label) {
            if (isset($swatchesCollectionArray[$optionId])) {
                $result[$optionId] = $this->extractNecessarySwatchData($swatchesCollectionArray[$optionId]);
                $result[$optionId] = $this->addAdditionalMediaData($result[$optionId], $optionId, $attributeDataArray);
                $result[$optionId]['label'] = $label;
                if ($attributeId) {
                    if($this->getProduct()->getId()==19 && $attributeId == 93) {
                        if($optionId == 7) {
                            $result[$optionId]['type'] = 2;
                            $result[$optionId]['value'] = $mediaUrl.'30x20/blue-jeans.jpg';
                            $result[$optionId]['thumb'] = $mediaUrl.'110x90/blue-jeans.jpg';
                        } elseif ($optionId == 8) {
                            $result[$optionId]['type'] = 2;
                            $result[$optionId]['value'] = $mediaUrl.'30x20/black-jeans.jpg';
                            $result[$optionId]['thumb'] = $mediaUrl.'110x90/black-jeans.jpg';
                        }
                    }
                }
            }
        }

        return $result;
    }
}
