<?php namespace Modules\News\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Modules\Core\Http\Controllers\BasePublicController;
use Modules\News\Repositories\NewsRepository;

class PublicController extends BasePublicController
{
    /**
     * @var PageRepository
     */
    private $news;
    /**
     * @var Application
     */
    private $app;

    public function __construct(NewsRepository $news, Application $app)
    {
        parent::__construct();
        $this->news = $news;
        $this->app = $app;
    }

    /**
     * @param $slug
     * @return \Illuminate\View\View
     */
    public function uri($slug)
    {
        $page = $this->page->findBySlug($slug);

        $this->throw404IfNotFound($page);

        $template = $this->getTemplateForPage($page);

        return view($template, compact('page'));
    }

    /**
     * @return \Illuminate\View\View
     */
    public function homepage()
    {
        $page = $this->news->homepage();

        $this->throw404IfNotFound($page);

        $template = $this->getTemplateForPage($page);

        return view('frontend', compact('page'));
    }

    /**
     * Return the template for the given page
     * or the default template if none found
     * @param $page
     * @return string
     */
    private function getTemplateForPage($page)
    {
        return (view()->exists($page->template)) ? $page->template : 'default';
    }

    /**
     * Throw a 404 error page if the given page is not found
     * @param $page
     */
    private function throw404IfNotFound($page)
    {
        if (is_null($page)) {
            $this->app->abort('404');
        }
    }
}
