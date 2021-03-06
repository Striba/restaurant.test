<?php

namespace Rest\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Rest\Http\Requests;
use Rest\Http\Controllers\Controller;
use Auth;

class AdminController extends \Rest\Http\Controllers\Controller
{
    //
    protected $user;
    protected $d_rep;
    protected $template;
    protected $content = false;
    protected $title;
    protected $vars;

    public function __construct()
    {
        $this->user = Auth::user();
        if(!$this->user){
            abort(403);
        }
    }

    public function renderOutput()
    {
        $this->vars = array_add($this->vars, 'title', $this->title);

        $navigation = view(env('THEME').'.admin.navigation')->render();
        $this->vars = array_add($this->vars, 'navigation', $navigation);

        if($this->content){
            $this->vars = array_add($this->vars, 'content', $this->content);
        }

        return view($this->template)->with($this->vars);
    }
}
