<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/OmniBlog for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace OmniBlog;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use OmniBlog\Model\Post;
use OmniBlog\Model\PostTable;
use OmniBlog\Model\Category;
use OmniBlog\Model\CategoryTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module implements AutoloaderProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        /* Example from: http://framework.zend.com/manual/2.2/en/modules/zend.mvc.examples.html#bootstraping
        $application = $e->getApplication();
        $config      = $application->getConfig();
        $view        = $application->getServiceManager()->get('ViewHelperManager');
        // You must have these keys in you application config
        $view->headTitle($config['view']['base_title']);

        // This is your custom listener
        $listener   = new Listeners\ViewListener();
        $listener->setView($view);
        $application->getEventManager()->attachAggregate($listener);
         */
    }
    
    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'OmniBlog\Model\PostTable' =>  function($sm) {
    						$tableGateway = $sm->get('PostTableGateway');
    						$table = new PostTable($tableGateway);
    						return $table;
    					},
    					'PostTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Post());
    						return new TableGateway('post', $dbAdapter, null, $resultSetPrototype);
    					},
    					'OmniBlog\Model\CategoryTable' =>  function($sm) {
    						$tableGateway = $sm->get('CategoryTableGateway');
    						$table = new CategoryTable($tableGateway);
    						return $table;
    					},
    					'CategoryTableGateway' => function ($sm) {
    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
    						$resultSetPrototype = new ResultSet();
    						$resultSetPrototype->setArrayObjectPrototype(new Category());
    						return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
    					},
    			),
    	);
    }
}
