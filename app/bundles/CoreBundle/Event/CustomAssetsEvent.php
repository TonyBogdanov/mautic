<?php

namespace Mautic\CoreBundle\Event;

use Mautic\CoreBundle\Twig\Helper\AssetsHelper;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class CustomAssetsEvent.
 */
class CustomAssetsEvent extends Event
{
    /**
     * @var AssetsHelper
     */
    protected $assetsHelper;

    /**
     * CustomAssetsEvent constructor.
     */
    public function __construct(AssetsHelper $assetsHelper)
    {
        $this->assetsHelper = $assetsHelper;
    }

    /**
     * @param        $declaration
     * @param string $location
     * @param string $context
     */
    public function addCustomDeclaration($declaration, $location = 'head', $context = AssetsHelper::CONTEXT_APP)
    {
        $this->assetsHelper->setContext($context)
            ->addCustomDeclaration($declaration, $location)
            ->setContext(AssetsHelper::CONTEXT_APP);

        return $this;
    }

    /**
     * @param        $script
     * @param string $location
     * @param bool   $async
     * @param null   $name
     * @param string $context
     */
    public function addScript($script, $location = 'head', $async = false, $name = null, $context = AssetsHelper::CONTEXT_APP)
    {
        $this->assetsHelper->setContext($context)
            ->addScript($script, $location, $async, $name)
            ->setContext(AssetsHelper::CONTEXT_APP);

        return $this;
    }

    /**
     * @param        $script
     * @param string $location
     * @param string $context
     */
    public function addScriptDeclaration($script, $location = 'head', $context = AssetsHelper::CONTEXT_APP)
    {
        $this->assetsHelper->setContext($context)
            ->addScriptDeclaration($script, $location)
            ->setContext(AssetsHelper::CONTEXT_APP);

        return $this;
    }

    /**
     * @param        $stylesheet
     * @param string $context
     */
    public function addStylesheet($stylesheet, $context = AssetsHelper::CONTEXT_APP)
    {
        $this->assetsHelper->setContext($context)
            ->addStylesheet($stylesheet)
            ->setContext(AssetsHelper::CONTEXT_APP);

        return $this;
    }

    /**
     * @param        $styles
     * @param string $context
     */
    public function addStyleDeclaration($styles, $context = AssetsHelper::CONTEXT_APP)
    {
        $this->assetsHelper->setContext($context)
            ->addStyleDeclaration($styles)
            ->setContext(AssetsHelper::CONTEXT_APP);

        return $this;
    }
}
