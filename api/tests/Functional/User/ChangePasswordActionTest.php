<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ChangePasswordActionTest extends UserTestBase
{
    public function testChangePassword(): void
    {
        $payload = [
            'oldPassword' => 'password',
            'newPassword' => 'new-password',
        ];

        self::$pedro->request(
            'PUT',
            sprintf('%s/%s/change_password', $this->endpoint, $this->getPedroId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$pedro->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testChangePasswordWithInvalidOldPassword(): void
    {
        $payload = [
            'oldPassword' => 'non-a-good-one',
            'newPassword' => 'new-password',
        ];

        self::$pedro->request(
            'PUT',
            sprintf('%s/%s/change_password', $this->endpoint, $this->getPedroId()),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$pedro->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}
