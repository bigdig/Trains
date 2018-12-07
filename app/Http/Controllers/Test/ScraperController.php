<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Clue\React\Buzz\Browser;

use App\Contract\Scraper;

class ScraperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $loop = \React\EventLoop\Factory::create();

        $client = new Browser($loop);
        $client->get('https://www.pexels.com/photo/kitten-cat-rush-lucky-cat-45170/')
            ->then(function(\Psr\Http\Message\ResponseInterface $response) {
                echo $response->getBody();
            });

        $loop->run();
    }

    //异步多个
    public function indexMore(){
        $loop = \React\EventLoop\Factory::create();

        $client = new Browser($loop);
        $client->get('https://www.pexels.com/photo/kitten-cat-rush-lucky-cat-45170/')
            ->then(function(\Psr\Http\Message\ResponseInterface $response) {
                echo $response->getBody();
            });

        $client->get('https://www.pexels.com/photo/adorable-animal-baby-blur-177809/')
            ->then(function(\Psr\Http\Message\ResponseInterface $response) {
                echo $response->getBody();
            });

        $loop->run();
    }
    //封装browser包装器
    public function indexDemo(){
        $urls = ['https://www.pexels.com/photo/kitten-cat-rush-lucky-cat-45170/','https://www.pexels.com/photo/adorable-animal-baby-blur-177809/'];
//        return (new Scraper())->scrape($urls);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
