<?php namespace Modules\User\Repositories\Users;

use Hash;
use Illuminate\Events\Dispatcher;

class UserRepository implements UserRepositoryInterface
{
    private $model;
    
    private $dispatcher;
    
    public function __construct($model = null, Dispatcher $dispatcher = null) {        
        $this->model = $model;
        $this->dispatcher = $dispatcher;
    }
    
    public function create(array $credentials)
    {                        
        $this->fill($credentials);
        
        $this->model->save();
        
        return $this->model;
    }
        
    public function fill(array $credentials)
    {        
        $this->dispatcher->fire('jhawai.user.filling', compact('user', 'credentials'));

        $loginNames = $this->model->getLoginNames();

        list($logins, $password, $attributes) = $this->parseCredentials($credentials, $loginNames);

        if (is_array($logins)) {
            $this->model->fill($logins);
        } else {
            $loginName = reset($loginNames);

            $this->model->fill([
                $loginName => $logins,
            ]);
        }

        $this->model->fill($attributes);

        if (isset($password)) {            
            $password = Hash::make($password);

            $this->model->fill(compact('password'));
        }

        $this->dispatcher->fire('jhawaii.user.filled', compact('user', 'credentials'));
    }
    
    protected function parseCredentials(array $credentials, array $loginNames)
    {
        if (isset($credentials['password'])) {
            $password = $credentials['password'];

            unset($credentials['password']);
        } else {
            $password = null;
        }

        $passedNames = array_intersect_key($credentials, array_flip($loginNames));

        if (count($passedNames) > 0) {
            $logins = [];

            foreach ($passedNames as $name => $value) {
                $logins[$name] = $credentials[$name];
                unset($credentials[$name]);
            }
        } elseif (isset($credentials['login'])) {
            $logins = $credentials['login'];
            unset($credentials['login']);
        } else {
            $logins = [];
        }

        return [$logins, $password, $credentials];
    }
}

