<?php namespace Modules\User\Http\Controllers\Admin;

use Modules\Core\Contracts\Authentication;
use Modules\Core\Permissions\PermissionManager;
use Modules\User\Repositories\RoleRepository;
use Modules\User\Repositories\UserRepository;


class UserController extends BaseUserController {

    /**
     * @var UserRepository
     */
    private $user;

    /**
     * @var RoleRepository
     */
    private $role;

    /**
     * @var Authentication
     */
    private $auth;

    /**
     * @param PermissionManager $permisions
     * @param UserRepository $user
     * @param RoleRepository $role
     * @param Authentication $auth
     */
    public function __construct(
        PermissionManager $permisions,
        UserRepository $user,
        RoleRepository $role,
        Authentication $auth

    )
    {
        $this->permissions = $permisions;
        $this->user = $user;
        $this->role = $role;
        $this->auth = $auth;
    }

    /**
     * Display a listing of the resource
     * @return Response
     */
    public function index()
    {
        $users = $this->user->all();
        $currentUser = $this->auth->check();
        
        return view('user::admin.users.index', compact($users, $currentUser));
    }
}