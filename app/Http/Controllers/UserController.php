<?php

namespace App\Http\Controllers;

use App\Core\Data\ApiResponse;
use App\Core\Data\ErrorResponse;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * User controller
 */
class UserController extends Controller
{
    /**
     * User repository
     *
     * @var UserRepository
     */
    private $repository;

    /**
     * User controller constructor
     *
     * @param UserRepository $repository
     */
    function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Check user credentials and authenticate a valid user
     *
     * @param Request $request
     * @return JsonResponse
     */
    function authenticate(Request $request): JsonResponse
    {
        try {
            // Check if the inputs are valid
            $this->validate($request, [
                'username' => 'required',
                'password' => 'required'
            ]);

            $username = $request->input('username');
            $password = $request->input('password');

            // Get user by username
            $user = $this->repository->getByFilters([
                'username' => $username
            ]);

            // Check if the user exists
            if ($user == null) {
                return response()->json(new ErrorResponse("User does not exist", ["User does not exist"]), 400);
            }

            $credentials = $request->only(['username', 'password']);

            if (! $token = Auth::attempt($credentials)) {
                return response()->json(new ErrorResponse("Username or password incorrect", ["Username or password incorrect"]), 400);
            }

            // Return the json representation for the user
            return response()->json(new ApiResponse(200, [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => auth()->user(),
                'expires_in' => auth()->factory()->getTTL() * 60 * 24
            ]), 200);
        } catch (\Exception $e) {
            return response()->json(new ErrorResponse("An error occurred", [$e->getMessage()]), 400);
        }
    }

    /**
     * Create a new user in the database
     *
     * @param Request $request
     * @return JsonResponse
     */
    function createUser(Request $request): JsonResponse
    {
        // Check if the inputs are valid
        $this->validate($request, [
            'name' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

        $name = $request->input('name');
        $username = $request->input('username');
        $password = $request->input('password');

        $user = $this->repository->getByFilters([
            'username' => $username
        ]);

        if ($user != null) {
            throw new DatabaseException("El usuario ya existe", 400);
        }

        // Encrypt user password
        $passwordHashed = app('hash')->make($password);

        // Create and return the new user
        $user = $this->repository->createOrUpdate([
            'name' => $name,
            'username' => $username,
            'password' => $passwordHashed
        ]);

        $credentials = $request->only(['username', 'password']);
        $token = Auth::attempt($credentials);

        // Return the json representation for the user
        return response()->json(new ApiResponse(200, [
                'access_token' => $token,
                'token_type' => 'bearer',
                'user' => auth()->user(),
                'expires_in' => auth()->factory()->getTTL() * 60 * 24
            ]), 200);
    }
}