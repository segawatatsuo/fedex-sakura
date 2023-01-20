@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h3 style="display: inline;vertical-align: middle;margin-right: 10px;">Mail</h3>
                </div>
            </div>
        </div>
    </section>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    <!-- フラッシュメッセージ -->
                    @if (session('flash_message'))
                        <div class="flash_message bg-success text-center py-3 my-0 mb-3">
                            {{ session('flash_message') }}
                        </div>
                    @endif


                    <!-- /.card-header -->
                    <div class="card-body">


                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">ユーザー登録メール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_3"
                                                        value="{{ $mails->subject_3 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea style="height: 15em;" class="form-control" name="contents_3" placeholder="contents:">{{ $mails->contents_3 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_3">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>置換 <span style="font-size: 80%">※replacement1※<br>
                                            Name<br>
                                            E-Mail Address<br>
                                            Country<br>
                                            Company Name
                                            </span></div>
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '3',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">Consigneeメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_7"
                                                        value="{{ $mails->subject_7 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea style="height: 23em;" class="form-control" name="contents_7" placeholder="contents:">{{ $mails->contents_7 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_7">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>置換<span style="font-size: 80%">※replacement2※<br>
                                                Consignee<br>
                                                Address Line1<br>
                                                Address Line2<br>
                                                City<br>
                                                State<br>
                                                Country<br>
                                                Zip Code<br>
                                                Phone Number<br>
                                                Contact person<br>
                                            
                                            </span></div>
                                            
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------


                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '7',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">Importerメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_8"
                                                        value="{{ $mails->subject_8 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea style="height: 31.5em;" class="form-control" name="contents_8" placeholder="contents:">{{ $mails->contents_8 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_8">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>置換 <span style="font-size: 80%">※replacement3※<br>
                                                Importer company<br>
                                                Address Line1<br>
                                                Address Line2<br>
                                                Company City<br>
                                                Company State<br>
                                                Company Country<br>
                                                Company Zip<br>
                                                Company Phone<br>
                                                Company President<br>
                                                Company Initials<br>
                                                
                                                Your Business Type<br>
                                                Your Items of Business<br>
                                                Your Customer(s)<br>
                                                Your company Website/URL<br>
                                                Your FedEX Account<br>
                                                Your company SNS
                                                </span></div>


                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '8',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>



                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">Quotationメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_4"
                                                        value="{{ $mails->subject_4 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="contents_4" placeholder="contents:">{{ $mails->contents_4 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_4">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '4',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>



                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">INVOICEメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_5"
                                                        value="{{ $mails->subject_5 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="contents_5" placeholder="contents:">{{ $mails->contents_5 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_5">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '5',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">ご注文ありがとうございましたメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_1"
                                                        value="{{ $mails->subject_1 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="contents_1" placeholder="contents">{{ $mails->contents_1 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_1">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '1',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>



                        <div>
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">お支払いありがとうございましたメール</h3>
                                        </div>

                                        <div class="card-body">
                                            <form method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <input class="form-control" name="subject_2"
                                                        value="{{ $mails->subject_2 }}" placeholder="Subject:">
                                                </div>
                                                <div class="form-group">
                                                    <textarea class="form-control" name="contents_2" placeholder="contents:">{{ $mails->contents_2 }}</textarea>
                                                </div>
                                                <input type="hidden" name="subject" value="subject_2">
                                                <input type="submit" class="btn btn-primary" value="更新"
                                                    formaction="{{ route('mail.update', $mails->id) }}">
                                            </form>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-primary card-outline">
                                        <div class="card-header">
                                            <h3 class="card-title">文例</h3>
                                        </div>
                                        <div class="card-body">
                                            <div>顧客名 <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------
                                            <div>本文</div>
                                            ----------------------------------
                                            <div>フッター <span style="font-size: 80%">※ 自動差し込み</span></div>
                                            ----------------------------------

                                            <form method="POST">
                                                @csrf
                                                <input type="submit" class="btn btn-info" value="テスト"
                                                    formaction="{{ route('mailsend.send', [
                                                        'email' => $user->email,
                                                        'name' => $user->name,
                                                        'no' => '2',
                                                    ]) }}">
                                            </form>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                    </div>

                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
    <script>
        $(() => {

            $('#summernote').summernote();

        });
    </script>
@stop
