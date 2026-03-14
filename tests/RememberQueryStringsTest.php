<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Session;
use Kedeka\Support\Middleware\RememberQueryStrings;

describe('RememberQueryStrings', function () {
    beforeEach(function () {
        $this->middleware = new RememberQueryStrings;
    });

    it('passes through json requests without remembering', function () {
        $request = Request::create('/test', 'GET', ['page' => '2']);
        $request->headers->set('Accept', 'application/json');
        $route = new Route('GET', '/test', fn () => 'ok');
        $route->name('test.index');
        $request->setRouteResolver(fn () => $route);

        $response = $this->middleware->handle($request, fn () => new Response('ok'));

        expect($response->getContent())->toBe('ok');
    });

    it('passes through when remember is set to no', function () {
        $request = Request::create('/test', 'GET', ['page' => '2', 'remember' => 'no']);

        $route = new Route('GET', '/test', fn () => 'ok');
        $route->name('test.index');
        $request->setRouteResolver(fn () => $route);
        $request->setLaravelSession(app('session.store'));

        $response = $this->middleware->handle($request, fn () => new Response('ok'));

        expect($response->getContent())->toBe('ok');
    });

    it('stores query strings in session', function () {
        $request = Request::create('/test', 'GET', ['page' => '2', 'search' => 'hello']);

        $route = new Route('GET', '/test', fn () => 'ok');
        $route->name('test.index');
        $request->setRouteResolver(fn () => $route);
        $request->setLaravelSession(app('session.store'));

        $this->middleware->handle($request, fn () => new Response('ok'));

        $stored = session('remember_query_strings.test.index');
        expect($stored)->toBe(['page' => '2', 'search' => 'hello']);
    });

    it('redirects to remembered query strings when no params', function () {
        session(['remember_query_strings.test.index' => ['page' => '3', 'search' => 'foo']]);

        $request = Request::create('/test', 'GET');

        $route = new Route('GET', '/test', fn () => 'ok');
        $route->name('test.index');
        $request->setRouteResolver(fn () => $route);
        $request->setLaravelSession(app('session.store'));

        $response = $this->middleware->handle($request, fn () => new Response('ok'));

        expect($response->getStatusCode())->toBe(302)
            ->and($response->headers->get('Location'))->toContain('page=3')
            ->and($response->headers->get('Location'))->toContain('search=foo');
    });

    it('forgets remembered query strings when remember is forget', function () {
        session(['remember_query_strings.test.index' => ['page' => '3']]);

        $request = Request::create('/test', 'GET', ['remember' => 'forget']);

        $route = new Route('GET', '/test', fn () => 'ok');
        $route->name('test.index');
        $request->setRouteResolver(fn () => $route);
        $request->setLaravelSession(app('session.store'));

        $response = $this->middleware->handle($request, fn () => new Response('ok'));

        expect($response->getStatusCode())->toBe(302)
            ->and(session('remember_query_strings.test.index'))->toBeNull();
    });
});
