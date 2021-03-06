<?php

namespace Rest\Http\Controllers;

use Illuminate\Http\Request;

use Rest\Http\Requests;
use Rest\Menu;
use Rest\Repositories\ReserveRepository;
use Rest\Breakfast;
use Session;
use DB;

class ReserveController extends SiteController
{
    public function __construct(ReserveRepository $res_rep)
    {
        parent::__construct(new \Rest\Repositories\MenuRepository(new Menu()));

        $this->template = env('THEME').'.reserveIndex';
        $this->res_rep = $res_rep;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->session()->get('reserve');

        if($request->ajax()){
            return view(env('THEME').'.reserveModal')->with('data', $data)->render();
        }

        return redirect('/');

    }

    public function add(Request $request, $id = null)
    {
        //айди блюда и колличество получаем из аджакс запроса:
        $id = $request->input('id');
        $qty = $request->input('qty');

//        echo 'айди номер:'.$id.'<br>';
//        echo 'кол-во:'.$qty.'<br>';
        //dd($qty);
        //dd($request);
        if ($id){
            $dish = Breakfast::select('*')->where('id', ['?' => $id])->first();
            //dd($dish);
            if (!$dish){
                return false;
            }
        }

        //dd($dish);

        $this->res_rep->addDishToReserve($request, $dish, $qty);

//        Session::forget('reserve.2');
//        Session::forget('reserve.1');
        $data = session('reserve');
        //dd(session('reserve'));
        //dump(session('reserve'));
        //dd(session());

        if($request->ajax()){
            return view(env('THEME').'.reserveModal')->with('data', $data)->render();

        }

        return redirect('/');

    }

    //Метод очистить заказ:
    public function clear(Request $request)
    {

        Session::forget('reserve');
        return view(env('THEME').'.reserveModal')->render();

    }

    public function delete(Request $request, $id = null)
    {
        if($id == null){
            $id = !empty($request->input('idToDel')) ? $request->input('idToDel') : null;
        }


        if (session('reserve.'.$id)){
            $this->res_rep->deleteItem($id);
        }

        if(session('reserve')){
            $data = $request->session()->get('reserve');
        }
        else{
            $data = null;
        }

        if($request->ajax()){
            return view(env('THEME').'.reserveModal')->with('data', $data)->render();

        }

        return back();


    }

//    public function checkout(Request $request)
//    {
//
//        //dd($request);
//        $session = $request->session()->get('reserve');
//        dd($session);
//        //код сохранения заказа в БД
//
//
//    }

    public function order(Request $request)
    {
        $this->title = 'Оформить заказ';

        if(session('reserve')){
            $data = $request->session()->get('reserve');
        } else {
            $data = null;
        }

        $reserve = view(env('THEME').'.reserve')->with('data',$data)->render();

        $this->vars = array_add($this->vars, 'reserve', $reserve);

        return $this->renderOutput();
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
        //dd($request);
        $session = $request->session()->get('reserve');
        //dd($session);
        //код сохранения заказа в БД

//        $users = $request->user()->breackfasts()->get();
//        dd($users);


        //
//        $db = DB::table('breakfasts')->get();
//        foreach ($db as $item){
//            echo 'Название блюда: '.$item->name.'<br>';
//        }
//        dd($db);


        //$data = array();
        if(!empty($request->except('_token'))){
            echo 'Ваше сообщение: '.$request->input('note');
            //$data['note'] = $request->input('note');
            //$data['dishes'] = $request->session()->get('reserve');
            $data = $request->session()->get('reserve');
//            $this->model->fill($data);
//            dd($this->model);
            //dd($session);
            $result = $this->res_rep->addReserve($data);
        }



//        if(is_array($result) && !empty($result['error'])){
//            return back()->with($result);
//        }

//        return redirect('/admin')->with($result);

//        //Сохранение заказа
//        $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
//        $data['note'] = !empty($_POST['note']) ? $_POST['note'] : '';
//        $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
//        $order_id = Order::saveOrder($data);


//        $result = $this->a_rep->addArticle($request);
//
//        if(is_array($result) && !empty($result['error'])){
//            return back()->with($result);
//        }
//
//        return redirect('/admin')->with($result);

        if(!empty($_POST)){
            //Сохранение заказа
            $data['user_id'] = isset($user_id) ? $user_id : $_SESSION['user']['id'];
            $data['note'] = !empty($_POST['note']) ? $_POST['note'] : '';
            $user_email = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : $_POST['email'];
            $order_id = Order::saveOrder($data);
        }

        return redirect('/');
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
