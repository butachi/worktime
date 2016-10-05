<?php

namespace App\Repositories\Cookies;

use Illuminate\Cookie\CookieJar;
use Illuminate\Http\Request;

class CookieRepository implements CookieRepositoryInterface
{
    /**
     * The current request.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The cookie object.
     *
     * @var \Illuminate\Cookie\CookieJar
     */
    protected $jar;

    /**
     * The cookie key.
     *
     * @var string
     */
    protected $key = 'jhawaii_superstar';

    /**
     * Create a new Illuminate cookie driver.
     *
     * @param \Illuminate\Http\Request     $request
     * @param \Illuminate\Cookie\CookieJar $jar
     * @param string                       $key
     */
    public function __construct(Request $request, CookieJar $jar, $key = null)
    {
        $this->request = $request;

        $this->jar = $jar;

        if (isset($key)) {
            $this->key = $key;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function put($value)
    {
        $cookie = $this->jar->forever($this->key, $value);

        $this->jar->queue($cookie);
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        $key = $this->key;

        $queued = $this->jar->getQueuedCookies();

        if (isset($queued[$key])) {
            return $queued[$key];
        }

        return $this->request->cookie($key);
    }

    /**
     * {@inheritdoc}
     */
    public function forget()
    {
        $cookie = $this->jar->forget($this->key);

        $this->jar->queue($cookie);
    }
}
