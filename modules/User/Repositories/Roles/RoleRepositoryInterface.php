<?php namespace Modules\User\Repositories\Roles;

interface RoleRepositoryInterface
{
    /**
     * Finds a role by the given primary key.
     *
     * @param  int  $id
     * @return \Modules\User\Repositories\Roles\RoleRepositoryInterface
     */
    public function findById($id);

    /**
     * Finds a role by the given slug.
     *
     * @param  string  $slug
     * @return \Modules\User\Repositories\Roles\RoleRepositoryInterface
     */
    public function findBySlug($slug);

    /**
     * Finds a role by the given name.
     *
     * @param  string  $name
     * @return \Modules\User\Repositories\Roles\RoleRepositoryInterface
     */
    public function findByName($name);
}
