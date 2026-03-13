<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Core\ApiAuth\Application\DTOs\LoginDTO;
use App\Core\ApiAuth\Application\UseCases\GetAuthenticatedUserUseCase;
use App\Core\ApiAuth\Application\UseCases\LoginUseCase;
use App\Http\Requests\AuthApi\LoginRequest;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly GetAuthenticatedUserUseCase $getAuthenticatedUserUseCase,
    ) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $dto = LoginDTO::fromRequest($request);
        $token = $this->loginUseCase->execute($dto);

        if ($token === null) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return response()->json($token->toArray());
    }

    public function logout(): void {}

    public function refresh(): void {}

    public function me(): JsonResponse
    {
        $authenticatedUser = $this->getAuthenticatedUserUseCase->execute();

        if ($authenticatedUser === null) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'data' => $authenticatedUser->toArray(),
        ]);
    }
}
