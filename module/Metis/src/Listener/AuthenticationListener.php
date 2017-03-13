<?php

namespace Metis\Listener;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;

class AuthenticationListener implements ListenerAggregateInterface
{
  use ListenerAggregateTrait;

  public function attach(EventManagerInterface $events, $priority = 1)
  {
      $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, [$this, 'checkAuthentication']);
  }

  public function checkAuthentication($event)
  {

      $auth = $event->getApplication()->getServiceManager()->get('doctrine.authenticationservice.orm_default');

      $router = $event->getRouter();
      $request = $event->getRequest();
      $matchedRoute = $router->match($request);
      $matchedRouteName = $matchedRoute->getMatchedRouteName();

      if ($auth->hasIdentity() && $matchedRouteName != 'login') {
          //instead of using the "AbstractAdminController" we could check
          //the access rights here and redirect if necessary
      } else {
          $redirect = $event->getApplication()->getServiceManager()->get('ControllerPluginManager')->get('redirect');
          $redirect->toRoute('login');
      }
  }
}
