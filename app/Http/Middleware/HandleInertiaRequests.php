<?php

namespace App\Http\Middleware;

use App\Http\Resources\Admin\Profile\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Storage;
use ReflectionClass;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user('sanctum');

        $defaults = [
            'user_type' => $user ? (new ReflectionClass($user))->getShortName() : null,
            'storage_url' => Storage::url(''),
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'danger' => $request->session()->get('danger'),
                    'warning' => $request->session()->get('warning'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ];

        // TODO
        // $additionalProps = match ($request->routeIs('*')) {
        //     true => $this->renderAdminProps($request, $user),
        // };

        $additionalProps = $this->renderAdminProps($request, $user);

        return array_merge(
            parent::share($request),
            $defaults,
            $additionalProps
        );
    }
    protected function renderAdminProps(Request $request, ?User $user = null): array
    {
        if (!$user) {
            return [];
        }

        return [
            'auth' => [
                'user' => ProfileResource::make($user),
            ],

            // TODO: Add Spatie Roles and Permission
            // 'session_permissions' => fn() => match (in_array(HasRoles::class, class_uses($user))) {
            //     true => $user->getAllPermissions()->pluck('name'),
            //     false => [],
            // },
        ];
    }

}
