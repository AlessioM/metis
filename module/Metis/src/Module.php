<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Metis;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class Module
{
    const VERSION = '3.0.2dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }


    public function onDispatch(MvcEvent $event)
    {
        // Get controller and action to which the HTTP request was dispatched.
        $controller = $event->getTarget();
        $routeName = $event->getRouteMatch()->getMatchedRouteName();

        $auth = $event->getApplication()->getServiceManager()->get(\Zend\Authentication\AuthenticationService::class);

        // if not logged in and not already on the login page: redirect to log in
        if (!$auth->hasIdentity() && $routeName != 'login') {
            // Redirect the user to the "Login" page.
            return $controller->redirect()->toRoute('login', [], []);
        }
    }

    public function onBootstrap($e)
    {
        $sm = $e->getApplication()->getServiceManager();

        $eventManager = $e->getApplication()->getEventManager();
        $sharedEventManager = $eventManager->getSharedManager();
        // Register the event listener method.
        $sharedEventManager->attach(AbstractActionController::class,
            MvcEvent::EVENT_DISPATCH, [$this, 'onDispatch'], 100);

        $config = $sm->get('Config');

        $application = $e->getParam('application');
        $viewModel = $application->getMvcEvent()->getViewModel();

        $viewModel->copyright = $config['metis']['copyright'];
        $viewModel->page_title = $config['metis']['title'];


        /** @var HelperPluginManager $helperPluginManger */
       $helperPluginManger = $e->getApplication()->getServiceManager()->get('ViewHelperManager');

       $helperPluginManger->addInitializer(function($helper) {
           if ( $helper instanceof TranslatorAwareInterface ) {
               $helper->setTranslatorEnabled(false);
           }
       });
    }

}
