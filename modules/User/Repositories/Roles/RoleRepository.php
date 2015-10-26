<?php namespace Modules\User\Repositories\Roles;

class RoleRepository implements RoleRepositoryInterface
{
    protected $model;
    
    public function __construct($model) {
        $this->model = $model;
    }
    
    public function findById($id) {
        
    }
    
    public function findByName($name) {
        return $this
            ->model
            ->newQuery()
            ->where('name', $name)
            ->first();
    }
    
    public function findBySlug($slug) {
        
    }
}