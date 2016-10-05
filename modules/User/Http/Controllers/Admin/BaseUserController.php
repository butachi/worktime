<?php namespace Modules\User\Http\Controllers\Admin;

use Pingpong\Modules\Routing\Controller;

abstract class BaseUserController extends Controller {

    /**
     * @var PermissionManager
     */
    protected $permissions;

    /**
     * @param $request
     * @return array
     */
    protected function mergeRequestWithPermissions($request)
    {
        return array_merge($request->all(), ['permissions' => $this->permissions->clean($request->permissions)]);
    }
}