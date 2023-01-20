<?php

namespace App\Http\Controllers;

use App\Model\Emailtext;
use Illuminate\Http\Request;
use App\Model\Mail;
use Illuminate\Support\Facades\Auth;

class EmailtextController extends Controller
{

    public function index()
    {
        $mails = Emailtext::first();
        $user = Auth::user();
        return view('mail.index', compact('mails','user'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('mail.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'consignee' => 'required'
        ]);
        Emailtext::create($request->all());
        return redirect(route('mail.index'))->with('flash_message', '新規レコードを追加しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Emailtext  $emailtext
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //1件の詳細表示(Read) GET アクションshow ルートothers.show URL /others/{other}
        $other = Emailtext::find($id);
        return view('mail.show', compact('other'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Emailtext  $emailtext
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //既存レコードの変更のためのフォーム表示(Update) アクションedit ルートothers.edit URL /others/{other}/edit
        $other = Emailtext::find($id);
        return view('mail.edit', compact('other'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Emailtext  $emailtext
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $mails = Emailtext::find($id);
        $subject=$request->subject;
        if($subject=="subject_1"){
            $mails->subject_1=$request->input('subject_1');
            $mails->contents_1=$request->input('contents_1');
            $mails->save();
        }
        if($subject=="subject_2"){
            $mails->subject_2=$request->input('subject_2');
            $mails->contents_2=$request->input('contents_2');
            $mails->save();
        }
        if($subject=="subject_3"){
            $mails->subject_3=$request->input('subject_3');
            $mails->contents_3=$request->input('contents_3');
            $mails->save();
        }
        if($subject=="subject_4"){
            $mails->subject_4=$request->input('subject_4');
            $mails->contents_4=$request->input('contents_4');
            $mails->save();
        }
        if($subject=="subject_5"){
            $mails->subject_5=$request->input('subject_5');
            $mails->contents_5=$request->input('contents_5');
            $mails->save();
        }
        if($subject=="subject_7"){
            $mails->subject_7=$request->input('subject_7');
            $mails->contents_7=$request->input('contents_7');
            $mails->save();
        }
        if($subject=="subject_8"){
            $mails->subject_8=$request->input('subject_8');
            $mails->contents_8=$request->input('contents_8');
            $mails->save();
        }

        return redirect(route('mail.index'))->with('flash_message', '更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Emailtext  $emailtext
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $other = Emailtext::find($id);

        $other->delete();
        return redirect(route('mail.index'))->with('flash_message', '顧客情報を削除しました');
    }
}
