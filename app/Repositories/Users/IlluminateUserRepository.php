<?php

namespace App\Repositories\Users;

use Hash;
use Closure;
use Illuminate\Events\Dispatcher;

class IlluminateUserRepository implements UserRepositoryInterface
{
    private $model;

    private $dispatcher;

    /**
     * @param type       $model
     * @param Dispatcher $dispatcher
     */
    public function __construct($model = null, Dispatcher $dispatcher = null)
    {
        if (isset($model)) {
            $this->model = $model;
        }
        $this->dispatcher = $dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function findById($id)
    {
        return $this
            ->model
            ->newQuery()
            ->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByCredentials(array $credentials)
    {
        if (empty($credentials)) {
            return;
        }

        $loginNames = $this->model->getLoginNames();

        list($logins, $password, $credentials) = $this->parseCredentials($credentials, $loginNames);

        $query = $this->model->newQuery();

        if (is_array($logins)) {
            foreach ($logins as $key => $value) {
                $query->where($key, $value);
            }
        } else {
            $query->whereNested(function ($query) use ($loginNames, $logins) {
                foreach ($loginNames as $name) {
                    $query->orWhere($name, $logins);
                }
            });
        }

        return $query->first();
    }

    /**
     * {@inheritdoc}
     */
    public function validForUpdate($user, array $credentials)
    {
        if ($user instanceof UserInterface) {
            $user = $user->getUserId();
        }

        return $this->validateUser($credentials, $user);
    }
    /**
     * {@inheritdoc}
     */
    public function create(array $credentials, Closure $callback = null)
    {
        $user = $this->model;

        $this->dispatcher->fire('hawaiioption.user.creating', compact('user', 'credentials'));

        $this->fill($user, $credentials);

        if ($callback) {
            $result = $callback($user);

            if ($result === false) {
                return false;
            }
        }

        $user->save();

        $this->dispatcher->fire('hawaiioption.user.created', compact('user', 'credentials'));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function update($user, array $credentials)
    {
        if (!$user instanceof UserInterface) {
            $user = $this->findById($user);
        }

        $this->dispatcher->fire('hawaiioption.user.updating', compact('user', 'credentials'));

        $this->fill($user, $credentials);

        $user->save();

        $this->dispatcher->fire('hawaiioption.user.updated', compact('user', 'credentials'));

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        return Hash::check($credentials['password'], $user->password);
    }

    /**
     * {@inheritdoc}
     */
    public function recordLogin(UserInterface $user)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function recordLogout(UserInterface $user)
    {
        return $user->save() ? $user : false;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($module)
    {
        $this->model = $module;

        return $this;
    }

    /**
     * Validates the user.
     *
     * @param array $credentials
     * @param int   $id
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    protected function validateUser(array $credentials, $id = null)
    {
        $instance = $this->model;

        $loginNames = $instance->getLoginNames();

        // We will simply parse credentials which checks logins and passwords
        list($logins, $password, $credentials) = $this->parseCredentials($credentials, $loginNames);

        if ($id === null) {
            if (empty($logins)) {
                throw new InvalidArgumentException('No [login] credential was passed.');
            }

            if (empty($password)) {
                throw new InvalidArgumentException('You have not passed a [password].');
            }
        }

        return true;
    }

    protected function fill(UserInterface $user, array $credentials)
    {
        $this->dispatcher->fire('hawaiioption.user.filling', compact('user', 'credentials'));

        $loginNames = $user->getLoginNames();

        list($logins, $password, $attributes) = $this->parseCredentials($credentials, $loginNames);

        if (is_array($logins)) {
            $user->fill($logins);
        } else {
            $loginName = reset($loginNames);

            $user->fill([
                $loginName => $logins,
            ]);
        }

        $user->fill($attributes);

        if (isset($password)) {
            $password = Hash::make($password);

            $user->fill(compact('password'));
        }

        $this->dispatcher->fire('hawaiioption.user.filled', compact('user', 'credentials'));
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
