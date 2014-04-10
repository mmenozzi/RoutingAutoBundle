<?php

namespace Spec\Symfony\Cmf\Bundle\RoutingAutoBundle\AutoRoute\DefunctRouteHandler;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LeaveRedirectDefunctRouteHandlerSpec extends ObjectBehavior
{
    function let(
        AdapterInterface $adapter
    ) {
        $this->beConstructedWith(
            $adapter
        );
    }

    function it_should_handle_defunct_routes(
        AdapterInterface $adapter,
        UrlContextCollection $urlContextCollection,
        AutoRouteInterface $route1,
        AutoRouteInterface $route2,
        AutoRouteInterface $newRoute1,
        \stdClass $subjectObject
    ) {

        $adapter->getReferringAutoRoutes($subjectObject)->willReturn(array($route1, $route2));
        $urlContextCollection->getSubjectObject()->willReturn($subjectObject);

        // lets say route 1 is in the route stack, 
        $urlContextCollection->containsAutoRoute($route1)->willReturn(true);
        $urlContextCollection->containsAutoRoute($route2)->willReturn(false);

        $route2->getAutoRouteTag()->willReturn('route2');
        $urlContextCollection->getAutoRouteByTag('route2')->willReturn($newRoute1);

        $adapter->migrateAutoRouteChildren($route2, $newRoute1)->shouldBeCalled();
        $adapter->removeAutoRoute($route2)->shouldBeCalled();

        $this->handlePreCommit($urlContextCollection);
    }
}
