<?php

declare(strict_types=1);

namespace Framework\Http;

/* Core of the aplication, it receive a request and send a response back,
passing it to the specific method configure on a route table.*/
class Kernel
{
    public function __construct(private Router $router)
    {
    }

    public function handle(Request $request): Response
    {
        $method = $request->getMethod();
        $uri    = $request->getPathInfo();

        [$status, $handler] = $this->router->dispatcher(
            $method,
            $uri
        );

        try {
            if ($status === Router::NOT_FOUND) {
                throw new \RuntimeException('Route Not found exception');
            }

            $response = null;

            if (is_callable($handler)) {
                $response =  call_user_func($handler, $request);
            }

            if (is_array($handler)) {
                [$controller, $method] = $handler;

                $controller = new $controller();

                if (!method_exists($controller, $method)) {
                    throw new \RuntimeException('Controller method dont exist');
                }

                $response = call_user_func_array([$controller, $method], ['request' => $request]);
            }

            if (!$response instanceof Response) {
                return new Response($response);
            }

            return $response;
        } catch(\RuntimeException) {
            return new Response('Not Found', 404);
        } catch(\Exception) {
            return new Response('Internal error', 500);
        }
    }
}
