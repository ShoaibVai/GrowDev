<?php

namespace App\Http\Controllers;

use App\Services\SupabaseServiceEnhanced;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class SupabaseTestController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseServiceEnhanced $supabase)
    {
        $this->supabase = $supabase;
    }

    /**
     * Show test interface
     */
    public function index()
    {
        return view('supabase-test');
    }

    /**
     * Test connection
     */
    public function testConnection(): JsonResponse
    {
        $result = $this->supabase->testConnection();
        
        return response()->json([
            'success' => $result['success'],
            'status' => $result['status'],
            'message' => $result['success'] ? 'Connection successful' : $result['error']
        ]);
    }

    /**
     * Test signup
     */
    public function testSignup(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:6',
                'name' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status' => 422
                ], 422);
            }

            $result = $this->supabase->signUp(
                $request->email,
                $request->password,
                ['name' => $request->name]
            );

            return response()->json([
                'success' => $result['success'],
                'data' => $result['data'],
                'status' => $result['status'] ?? null,
                'message' => $result['success'] ? 'User created successfully' : ('Signup failed: ' . ($result['data']['msg'] ?? 'Unknown error'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * Test signin
     */
    public function testSignin(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status' => 422
                ], 422);
            }

            $result = $this->supabase->signIn($request->email, $request->password);

            return response()->json([
                'success' => $result['success'],
                'data' => $result['data'],
                'status' => $result['status'] ?? null,
                'message' => $result['success'] ? 'Login successful' : ('Login failed: ' . ($result['data']['msg'] ?? 'Unknown error'))
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * List users
     */
    public function listUsers(): JsonResponse
    {
        $result = $this->supabase->listUsers();

        return response()->json([
            'success' => $result['success'],
            'users' => $result['data']['users'] ?? [],
            'count' => count($result['data']['users'] ?? []),
            'message' => $result['success'] ? 'Users retrieved successfully' : 'Failed to retrieve users'
        ]);
    }

    /**
     * Check database schema
     */
    public function checkSchema(): JsonResponse
    {
        $schemaInfo = $this->supabase->getSchemaInfo();

        return response()->json([
            'success' => true,
            'schema' => $schemaInfo,
            'tables_count' => count(array_filter($schemaInfo, function($info) {
                return $info['exists'] ?? false;
            }))
        ]);
    }

    /**
     * Get user profile
     */
    public function getProfile(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|string'
        ]);

        $result = $this->supabase->getProfile($request->user_id);

        return response()->json([
            'success' => $result['success'],
            'profile' => $result['data'],
            'status' => $result['status'] ?? null
        ]);
    }
}