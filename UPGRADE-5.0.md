# User facing changes
- The Pipedrive Plugin has been removed from Mautic Core, you can use https://www.mautic.org/blog/integrator/exciting-news-new-integration-plugin-pipedrive-crm instead.
- The Citrix Plugin has been removed from Mautic Core. you can use https://www.leuchtfeuer.com/en/mautic/downloads/mautic-goto-plugin/ instead.

# Backwards compatibility breaking changes
*   Platform Requirements
    *   Minimal PHP version was increased from 7.4 to 8.0 and 8.1.
*   Configuration
    * Replace all occurances of `%kernel.root_dir%` to `%kernel.project_dir%/app` as the "root_dir" was deprecated in Symfony 4 and removed in Symfony 5. The "project_dir" variable is path to the Mautic root directory. The "root_dir" variable was path to the app directory.
    * The index_dev.php was removed. Use env variables to set the environment.
    * We are following symfony env naming convention. [synfomy 4.4](https://symfony.com/doc/4.4/configuration.html#selecting-the-active-environment)
      * `.env`                contains default values for the environment variables needed by the app
      * `.env.local`          uncommitted file with local overrides
      * `.env.$APP_ENV`       committed environment-specific defaults
      * `.env.$APP_ENV.local` uncommitted environment-specific overrides
    * The system run similar index_dev.php if you use `APP_ENV=dev` and `APP_DEBUG=1` in your .env.local file.
* Installation
    * The email step was removed from both GUI and CLI installers.
    * The installation is considered completed once `db_driver` and `site_url` parameters are set. It used to be `db_driver` and `mailer_from_name`.  
* Commands
    * The command `bin/console mautic:segments:update` will no longer update the campaign members but only the segment members. Use also command `bin/console mautic:campaigns:update` to update the campaign members if you haven't already. Both commands are recommended from Mautic 1.
    * Command `Mautic\LeadBundle\Command\CheckQueryBuildersCommand` and the methods it use:
        * `Mautic\LeadBundle\Model\ListModel::getVersionNew()`
        * `Mautic\LeadBundle\Model\ListModel::getVersionOld()`
*   Services
    * Repository service `mautic.user.token.repository` for `Mautic\UserBundle\Entity\UserTokenRepository` was removed as it was duplicated. Use `mautic.user.repository.user_token` instead.
    * In tests replace `self::$container->get('mautic.http.client.mock_handler')` with `self::$container->get(\GuzzleHttp\Handler\MockHandler::class)` to get HTTP client mock handler.
* JS Dependencies
    * Most of the JS libraries were moved from hard-coded location in the CoreBundle to package.json so we can manage them with NPM
    * This means that when you run `composer install` then it will also run `npm install` to download JS dependencies and `bin/console mautic:assets:generate` to build the production assets.
    * Libraries `jquery-color`, `jquery-play-sound` and `html5notifications` were removed as unused. Details in https://github.com/mautic/mautic/pull/12265.
    * Library `jvectormap` was replaced with its accessor `jvectormap-next` as it was unmaintaned. Details in https://github.com/mautic/mautic/pull/12359.
    * Library `quicksearch` was updated from unmaintained vendor to latest version of its successor. Details in https://github.com/mautic/mautic/pull/12372.
    * Library `jQueryUI` was updated from version 1.12.1 to 1.13.2. Details in https://github.com/mautic/mautic/pull/12394.
    * Modernizr JS was upgraded from 2.8.3 to 3.12.0 and reduced to only used features. Details in https://github.com/mautic/mautic/pull/12402.
*   Other
    * `Mautic\UserBundle\Security\Firewall\AuthenticationListener::class` no longer implements the deprecated `Symfony\Component\Security\Http\Firewall\ListenerInterface` and was made final. The `public function handle(GetResponseEvent $event)` method was changed to `public function __invoke(RequestEvent $event): void` to support Symfony 5.
    * `Mautic\IntegrationsBundle\Configuration\PluginConfiguration` removed - we don't use it
    * `Mautic\SmsBundle\Callback\ResponseInterface` removed - we don't use it
    * `Mautic\CoreBundle\Controller\AbstractModalFormController` removed - we don't use it
    * `Mautic\CoreBundle\Templating\Helper\ExceptionHelper` removed - we don't use it
    * `Mautic\LeadBundle\Model\LeadModel::getContactFromRequest()` method removed. Use `Mautic\LeadBundle\Helper\ContactRequestHelper::getContactFromQuery()` instead.
    * `Mautic\LeadBundle\Model\LeadModel::mergeLeads()` method removed. Use `\Mautic\LeadBundle\Deduplicate\ContactMerger::merge()` directly.
    * `Mautic\LeadBundle\Model\LeadModel::checkForDuplicateContact()` method do not take Lead as a second parameter anymore and so it do not merges contacts. Use `\Mautic\LeadBundle\Deduplicate\ContactMerger::merge()` afterwards.
    * Class `Mautic\LeadBundle\Model\LegacyLeadModel` removed. Use `\Mautic\LeadBundle\Deduplicate\ContactMerger` instead.
    * `Mautic\CoreBundle\Doctrine\AbstractMauticMigration::entityManager` protected property was removed as unused.
    * The User entity no longer implements `Symfony\Component\Security\Core\User\AdvancedUserInterface` as it was removed from Symfony 5. These methods required by the interface were also removed:
        * `Mautic\UserBundle\Entity\User::isAccountNonExpired()`
        * `Mautic\UserBundle\Entity\User::isAccountNonLocked()`
        * `Mautic\UserBundle\Entity\User::isCredentialsNonExpired()`
        * `Mautic\UserBundle\Entity\User::isEnabled()`
    * Two French regions were updates based on ISO_3166-2 (Val-d\'Oise, La Réunion). If you use it in API, please change values to Val d\'Oise or Réunion
    * `AbstractMauticTestCase::loadFixtures` and `AbstractMauticTestCase::loadFixtureFiles` now accept only two arguments: `array $fixtures` and `bool $append`. If you need to use old parameters - refer to the documentation of `LiipTestFixturesBundle`
    * Transactional emails in campaigns ignore the DNC setting.
    * There are no unsubscribe headers in transactional emails.
    * The SortablePanels templates, JS and CSS was removed as unused.
    * Country name of Swaziland was update to Eswatini based on Standard: ISO 3166.
    * `Mautic\CoreBundle\Controller\CommonController::addFlash()` was renamed to `CommonController::addFlashMessage()`to prevent naming collision with `Symfony\Bundle\FrameworkBundle\Controller\AbstractController::addFlash()`. Controllers adding flash messages should use `$this->addFlashMessage()`.

# Dependency injection improvements

Mautic 5 adds support for Symfony's [autowiring](https://symfony.com/doc/5.4/service_container/autowiring.html) and [autoconfigure](https://symfony.com/doc/4.4/service_container.html#the-autoconfigure-option) for services.

Advantages:
- New services no longer need to have any definition in the app/bundles/*Bundle/Config/config.php. Symfony will guess what services are needed in the services by types of arguments in the constructor.
- Services that aren't used in other services as dependencies like **subscribers**, **commands** and **form types** were deleted completely.
- Existing service definitions can be reduced to setting just the string alias to keep backward compatibility and controllers working.
- `app/config/services.php` is automatically configuring all bundles including plugins so if the bundle doesn't do anything uncommon then it should work out of the box.
- The legacy services definitions in `*Bundle/Config/config.php` file are still working but will be removed in Mautic 6.

Possible backward compatibility breaks:
- If your plugin does break it may be using some value objects out of common places. Get inspiration in existing `plugins/*Bundle/Config/services.php` to exclude the folders or files from autowiring.
- Some services might need to be configured. For example if they need a config parameter in the constructor. Follow [the official Symfony docs](https://symfony.com/doc/5.4/service_container.html#explicitly-configuring-services-and-arguments) to configure such services.
- Start converting your controllers to support DI over loading services from container as that is an anti-pattern that Symfony no longer supports. That is the reason why all the services are set as public so the old controllers can still work. This will change throughout the life of Mautic 5 and will be removed in Mautic 6. See https://symfony.com/doc/current/controller/service.html
