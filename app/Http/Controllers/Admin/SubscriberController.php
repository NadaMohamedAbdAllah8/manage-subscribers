<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SubscriberService;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    protected $subscriber_service;
    public function __construct(SubscriberService $subscriber_service)
    {
        $this->subscriber_service = $subscriber_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->subscriber_service->validateAPIKey() == false) {
            $data = [
                'title' => 'Subscribers',
                'subscribers' => [],
                'message' => 'API key is invalid!',
            ];
            return view('admin.pages.subscribers.index', $data);
        }

        $data = [
            'title' => 'Subscribers',
            'subscribers' => [],
            'message' => null,
        ];

        return view('admin.pages.subscribers.index', $data);
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
    public function store(
        //StoreRequest
        Request $request) {
        //dd($request->all());
        $result = $this->subscriber_service->store($request);
        // dd($result['error_messages']);
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
        //
    }
}
