<?php

/**
 * Custom footer with the privacy policy for dutch locale environment.
 */

declare(strict_types=1);

namespace PrivacyFooterNameSpace;

use Fisharebest\Webtrees\I18N;
use Fisharebest\Webtrees\Module\AbstractModule;
use Fisharebest\Webtrees\Module\ModuleCustomInterface;
use Fisharebest\Webtrees\Module\ModuleCustomTrait;
use Fisharebest\Webtrees\Module\ModuleFooterInterface;
use Fisharebest\Webtrees\Module\ModuleFooterTrait;
use Fisharebest\Webtrees\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PrivacyFooter extends AbstractModule implements ModuleCustomInterface, ModuleFooterInterface {
    use ModuleCustomTrait;
    use ModuleFooterTrait;

    // Module constants
    public const CUSTOM_AUTHOR = 'Lars van Ravenzwaaij';
    public const CUSTOM_VERSION = '1.0.0';
    
    /** @var LanguageSwitch */
    private $language_switch;

    /**
     * @return string
     */
    public function title(): string
    {
        return I18N::translate('Privacy policy');
    }

 /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleAuthorName()
     */
    public function customModuleAuthorName(): string
    {
        return self::CUSTOM_AUTHOR;

    }
    /**
     * {@inheritDoc}
     * @see \Fisharebest\Webtrees\Module\ModuleCustomInterface::customModuleVersion()
     */
    public function customModuleVersion(): string
    {
        return self::CUSTOM_VERSION;
    }

    /**
     * Bootstrap the module
     */
    public function boot(): void
    {
        // Register a namespace for our views.
        View::registerNamespace($this->name(), $this->resourcesFolder() . 'views/');
    }

    /**
     * Where does this module store its resources
     *
     * @return string
     */
    public function resourcesFolder(): string
    {
        return __DIR__ . '/resources/';
    }
    
    /**
     * Additional custom translations.
     *
     * @param string $language
     *
     * @return array<string,string>
     */
    public function customTranslations(string $language): array
    {
        $this->language_switch = $language;

        return [];
   
   /**
     * A footer, to be added at the bottom of every page.
     *
     * @param ServerRequestInterface $request
     *
     * @return string
     */
    public function getFooter(ServerRequestInterface $request): string
    {
        $tree = $request->getAttribute('tree');

        $url = route('module', [
            'module' => $this->name(),
            'action' => 'Page',
            'tree'   => $tree ? $tree->name() : null,
        ]);

        return view($this->name() . '::footer', ['url' => $url]);
    }

    /**
     * Generate the page that will be shown when we click the link in the footer.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function getPageAction(ServerRequestInterface $request): ResponseInterface
    {
        $page = '';
        switch ($this->language_switch) {
            case 'nl':
            case 'be':
                $page = '::page-dutch';
                break;
            default:
                $page = '::page';
        }
        
        return $this->viewResponse($this->name() . '::page', [
            'title' => $this->title(),
            'tree'  => $request->getAttribute('tree'),
        ]);
    }
};
