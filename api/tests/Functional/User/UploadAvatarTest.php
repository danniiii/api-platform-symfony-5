<?php

declare(strict_types=1);

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadAvatarTest extends UserTestBase
{
    public function testUploadAvatar(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$pedro->request(
            'POST',
            \sprintf('%s/%s/avatar', $this->endpoint, $this->getPedroId()),
            [],
            ['avatar' => $avatar]
        );

        $response = self::$pedro->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUploadAvatarWithWrongInputName(): void
    {
        $avatar = new UploadedFile(
            __DIR__.'/../../../fixtures/avatar.png',
            'avatar.png'
        );

        self::$pedro->request(
            'POST',
            \sprintf('%s/%s/avatar', $this->endpoint, $this->getPedroId()),
            [],
            ['non-valid-input' => $avatar]
        );

        $response = self::$pedro->getResponse();

        $this->assertEquals(JsonResponse::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}