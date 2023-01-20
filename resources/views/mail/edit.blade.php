@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

    <form action="{{ route('customers.update', $customer->id) }}" method="post" style="display: inline;">
        @csrf
        @method('patch')

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h3 style="display: inline;vertical-align: middle;margin-right: 10px;">編集</h3>
                        <input type="submit" value=" 編集確定 " class="btn btn-primary">
                        <a href="#" class="btn btn-secondary" onclick='window.history.back(-1);'>前画面に戻る</a>
                    </div>
                </div>
            </div>
        </section>
    @stop

    @section('content')

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">

                <!-- エラー時のフラッシュメッセージ -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <!-- left column -->
                    <div class="col-md-6">


                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header" style="background-color:#77B1A1">
                                <h3 class="card-title">送り先住所1</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Customer">荷受人(Consignee)</label>
                                        <input type="text" value="{{ $customer->consignee }}"
                                            class="form-control" id="consignee" name="consignee">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">住所欄1(Address Line1)</label>
                                        <input type="text" value="{{ $customer->address_line1 }}"
                                            class="form-control" id="address_line1" name="address_line1">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">住所欄2(Address Line2)</label>
                                        <input type="text" value="{{ $customer->address_line2 }}"
                                            class="form-control" id="address_line2" name="address_line2">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">市町村(City)</label>
                                        <input type="text" value="{{ $customer->city }}" class="form-control"
                                            id="city" name="city">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">都道府県(State)</label>
                                        <input type="text" value="{{ $customer->state }}" class="form-control"
                                            id="state" name="state">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">国(Country)</label>
                                        <input type="text" value="{{ $customer->country }}"
                                            class="form-control" id="country" name="country">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">郵便番号(ZIP)</label>
                                        <input type="text" value="{{ $customer->zip }}" class="form-control"
                                            id="zip" name="zip">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">電話番号(Phone)</label>
                                        <input type="text" value="{{ $customer->phone }}" class="form-control"
                                            id="phone" name="phone">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                        </div>

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header" style="background-color:#77B1A1">
                                <h3 class="card-title">取引情報</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Customer">登録日(Regist Date)</label>
                                        <input type="text" value="{{ $customer->regist_date }}"
                                            class="form-control" id="regist_date" name="regist_date" style="background-color: white;">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">取引期間(Trading Term)</label>
                                        <input type="text" value="{{ $customer->trading_term }}"
                                            class="form-control" id="trading_term" name="trading_term">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">取引履歴(Trading History)</label>
                                        <input type="text" value="{{ $customer->trading_history }}"
                                            class="form-control" id="trading_history" name="trading_history">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">取引ランク(Trading Rank)</label>
                                        <input type="text" value="{{ $customer->trading_rank }}"
                                            class="form-control" id="trading_rank" name="trading_rank">
                                    </div>
                                </div>
                                <!-- /.card-body -->

                        </div>

                    </div>



                    <!-- Right column -->
                    <div class="col-md-6">

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header" style="background-color:#77B1A1">
                                <h3 class="card-title">担当者</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Customer">性別(Gender)</label>
                                        <input type="text" value="{{ $customer->gender }}"
                                            class="form-control" id="gender" name="gender">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">担当者名(Person)</label>
                                        <input type="text" value="{{ $customer->person }}"
                                            class="form-control" id="person" name="person">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                        </div>

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header" style="background-color:#77B1A1">
                                <h3 class="card-title">請求会社</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Customer">請求会社住所（本社）</label>
                                        <input type="text" value="{{ $customer->bill_company_address }}"
                                            class="form-control" id="bill_company_address" name="bill_company_address">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">代表者名(President)</label>
                                        <input type="text" value="{{ $customer->president }}"
                                            class="form-control" id="president" name="president">
                                    </div>
                                </div>
                                <!-- /.card-body -->
                        </div>

                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header" style="background-color:#77B1A1">
                                <h3 class="card-title">その他</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Customer">WEBサイト(Web Site)</label>
                                        <input type="text" value="{{ $customer->website }}"
                                            class="form-control" id="website" name="website">
                                    </div>
                                    <div class="form-group">
                                        <label for="Customer">業種(Industry)</label>
                                        <input type="text" value="{{ $customer->industry }}"
                                            class="form-control" id="industry" name="industry">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">営業品目(BusinessItems)</label>
                                        <input type="text" value="{{ $customer->business_items }}"
                                            class="form-control" id="business_items" name="business_items">
                                    </div>


                                    <div class="form-group">
                                        <label for="Customer">取引先名(CustomerName)</label>
                                        <input type="text" value="{{ $customer->customer_name }}"
                                            class="form-control" id="customer_name" name="customer_name">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">EMail</label>
                                        <input type="text" value="{{ $customer->email }}" class="form-control"
                                            id="email" name="email">
                                    </div>


                                    <div class="form-group">
                                        <label for="Customer">FAX</label>
                                        <input type="text" value="{{ $customer->fax }}" class="form-control"
                                            id="fax" name="fax">
                                    </div>


                                    <div class="form-group">
                                        <label for="Customer">FedExアカウント</label>
                                        <input type="text" value="{{ $customer->fedex }}" class="form-control"
                                            id="fedex" name="fedex">
                                    </div>

                                    <div class="form-group">
                                        <label for="Customer">SNSアカウント</label>
                                        <input type="text" value="{{ $customer->sns }}" class="form-control"
                                            id="sns" name="sns">
                                    </div>
                                </div>
                        </div>
                    </div>
    </form>





@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>

    <!--Flatpickr(カレンダー) -->
    <script>
        var regist_date = document.getElementById('regist_date');
        var fp = flatpickr(regist_date);
    </script>

@stop
