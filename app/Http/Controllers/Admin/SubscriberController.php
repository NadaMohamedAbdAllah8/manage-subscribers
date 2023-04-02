<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Subscriber\StoreRequest;
use App\Services\Subscriber;
// use \Debugbar;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class SubscriberController extends Controller
{
    protected $subscriber;
    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->subscriber->validateAPIKey() == false) {
            $data = [
                'title' => 'Subscribers',
                'subscribers' => [],
                'message' => 'API key is invalid!',
            ];
            return view('admin.pages.subscribers.index', $data);
        }

        // $result = $this->subscriber->listSubscribers();
        // if ($result['success'] === true) {$subscribers = $result['data']['subscribers'];
        //     /**return Datatables::of($records)->make(true);
        //      */
        //     $data = [
        //         'title' => 'Subscribers',
        //         'subscribers' => $subscribers,
        //         'message' => null,
        //     ];
        //     return view('admin.pages.subscribers.index', $data);
        // }
        else {
            $data = [
                'title' => 'Subscribers',
                'subscribers' => [],
                'message' => '',
            ];
            return view('admin.pages.subscribers.index', $data);
        }
    }

    public function data()
    {
        $subscribers = [];
        $result = $this->subscriber->listSubscribers();
        if ($result['success'] === true) {
            $subscribers = $result['data']['subscribers'];

        }
        return Datatables::of($subscribers)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => 'Create Subscriber',
        ];
        return view('admin.pages.subscribers.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $result = $this->subscriber->store($request);
        if ($result['success'] == false) {
            return redirect(route('admin.subscribers.index'))
                ->with('error', $result['error_message']);
        } else {
            return redirect(route('admin.subscribers.index'))
                ->with('success', 'Subscriber Created');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // fetch a subscriber GET https://connect.mailerlite.com/api/subscribers/(:id or :email)
        $data = [
            'title' => 'Create Subscriber',
            'subscriber' => $id,
        ];
        return view('admin.pages.subscribers.edit', $data);

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
        try { $result = $this->subscriber->delete($id);
            if ($result['success']) {
                return response()->json([], 200);
            } else {
                //return $result['error_message'];
                return response()->json(['error' => $result['error_message'], 'id' => $id], 500)
                    ->header('Content-Type', 'application/json');
            }} catch (\Exception $e) {
            return response()->json(['error' => 'Cannot delete!', 'id' => $id], 500)
                ->header('Content-Type', 'application/json');

        }
    }
}
