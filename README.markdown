usecase

```php
<?php
        use Symfony\Component\EventDispatcher\EventDispatcher;
        use Symfony\Component\HttpFoundation\Request;

        use PhpDirect\Server;
        use PhpDirect\Service\ServiceManager;
        use PhpDirect\EventSubscriber\ApiRequestSubscriber;
        use PhpDirect\EventSubscriber\EchoRequestSubscriber;
        use PhpDirect\EventSubscriber\MasterRequestParserSubscriber;
        use PhpDirect\EventSubscriber\CallbackResolverSubscriber;
        use PhpDirect\EventSubscriber\SingleResponseWrapperSubscriber;
        use PhpDirect\EventSubscriber\UniversalErrorCatcherSubscriber;
        use PhpDirect\Request\SingleRequest;

        $directServiceManager = new ServiceManager();
        $directServiceManager->add('Shoes', 'getAll', array($controller, 'getAll'));
        $directServiceManager->add('Shoes', 'update', function(\stdClass $submitData) {
            //do update
        });

        // TODO refactor
        $directServiceManager->setFormHandler('Shoes', 'update');

        $directEventDispatcher = new EventDispatcher;
        $directEventDispatcher->addSubscriber(new ApiRequestSubscriber($directServiceManager));
        $directEventDispatcher->addSubscriber(new EchoRequestSubscriber());
        $directEventDispatcher->addSubscriber(new MasterRequestParserSubscriber());
        $directEventDispatcher->addSubscriber(new CallbackResolverSubscriber($directServiceManager));
        $directEventDispatcher->addSubscriber(new SingleResponseWrapperSubscriber());
        $directEventDispatcher->addSubscriber(new UniversalErrorCatcherSubscriber($debug = true));

        $directServer = new Server($directEventDispatcher);

        $response = $directServer->handle(Request::createFromGlobals());

        $response->send();

```