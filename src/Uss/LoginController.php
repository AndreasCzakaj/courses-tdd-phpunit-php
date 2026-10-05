<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\Uss;

class LoginController
{
    public function __construct(private readonly UserSelfService $service)
    {
    }

    public function action(mixed $body): HttpResult
    {
        // the body is untrusted input: the service validates it
        return ControllerUtils::respond(200, fn () => $this->service->login($body));
    }
}
