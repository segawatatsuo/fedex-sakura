<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Model\User;
use App\Model\Product;
use App\Model\Preference;
use App\Model\Quotation;
use App\Model\Quotation_detail;
use App\Model\Payment_method;

use Carbon\Carbon;

use Illuminate\Support\Str;

use App\Model\Userinformation; //Userinformationモデル

class ProductController extends Controller
{
    public function plan()
    {
        //コントローラーの引数に$resultを使わずコントローラーの関数内でrequest関数を呼ぶ
        //articleにはAirStokingやネールといった商品分類が入っている
        $result = request('article');
        if ($result=="AirStocking") {
            $result="Air Stocking";
        }
        session()->put('article', $result);
        //dd(session('article'));

        session()->put('type', 'fedex');

        $user_id = Auth::id();

        //為替 Preferenceテーブルの1レコード目(tts,ttb)にあるので使う際に$exchange->tts
        $exchange = Preference::first();

        $fedexItems = Product::where('hidden_item', '!=', '1')->where('category', 'Air Stocking')->orderBy('sort_order', 'asc')->get();

        //カテゴリーのユニークだけ(ここではAIRSTOCKINGだけだが、今後ネールなどが入ってくる)
        $categorys=Product::where('hidden_item', '!=', '1')->where('category', 'Air Stocking')->groupBy('category')->orderBy('sort_order', 'asc')->get(['category']);

        //Air Stocking中分類（PREMIUM-SILKとDIAMOND LEGS）
        $groups=Product::where('hidden_item', '!=', '1')->where('category', 'Air Stocking')->groupBy('group')->orderBy('sort_order', 'asc')->get(['group']);

        //グループ別の商品配列
        $items=[];
        foreach ($groups as $g) {
            $b=Product::where('hidden_item', '!=', '1')->where('group', $g->group)->orderBy('sort_order', 'asc')->get();
            array_push($items, $b);
        }

        //振込先情報をセッションに入れる
        $payee=Payment_method::where('selection', '選択')->get();
        session(['bank' => $payee[0]['bank']]);
        session(['branch' => $payee[0]['branch']]);
        session(['swift_code' => $payee[0]['swift_code']]);
        session(['account' => $payee[0]['account']]);
        session(['name' => $payee[0]['name']]);
        //dd(session('branch'));

        //Userinformationsテーブルから住所等を取り出す
        $Userinformations = User::find($user_id)->userinformations;

        if ($Userinformations != null) {
            $consignee = $Userinformations->consignee;
            $address_line1 = $Userinformations->address_line1;
            $address_line2 = $Userinformations->address_line2;
            $city = $Userinformations->city;
            $state = $Userinformations->state;
            $country = $Userinformations->country;
            $country_codes = $Userinformations->country_codes;
            $phone = $Userinformations->phone;
            $fax = $Userinformations->fax;
        } else {
            $consignee = "consignee";
            $address_line1 = "address_line1";
            $address_line2 = "address_line2";
            $city = "city";
            $state = "state";
            $country = "country";
            $country_codes = "country_codes";
            $phone = "phone";
            $fax = "fax";
        }

        $user = array(
            'user_id' => $user_id, 'consignee' => $consignee, 'address_line1' => $address_line1,
            'address_line2' => $address_line2, 'city' => $city, 'state' => $state, 'country' => $country, 'country_codes' => $country_codes, 'phone' => $phone, 'fax' => $fax
        );

        //ユーザーをセッション「session('user')['項目']」に入れる
        $collection = collect($user);
        session()->put('user', $collection);
        return view('fedex', compact('categorys', 'groups', 'items', 'exchange'));
    }

    public function fedex()
    {
        //コントローラーの引数に$resultを使わずコントローラーの関数内でrequest関数を呼ぶ
        //articleにはAirStokingやネールといった商品分類が入っている
        $result = request('article');
        if ($result=="AirStocking") {
            $result="Air Stocking";
        }
        session()->put('article', $result);
        //dd(session('article'));

        session()->put('type', 'fedex');

        $user_id = Auth::id();

        //為替 Preferenceテーブルの1レコード目(tts,ttb)にあるので使う際に$exchange->tts
        $exchange = Preference::first();

        $fedexItems = Product::where('hidden_item', '!=', '1')->where('category', session('article'))->orderBy('sort_order', 'asc')->get();

        //カテゴリーのユニークだけ(ここではAIRSTOCKINGだけだが、今後ネールなどが入ってくる)
        $categorys=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('category')->orderBy('sort_order', 'asc')->get(['category']);

        //Air Stocking中分類（PREMIUM-SILKとDIAMOND LEGS）
        $groups=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('group')->orderBy('sort_order', 'asc')->get(['group']);

        //グループ別の商品配列
        $items=[];
        foreach ($groups as $g) {
            $b=Product::where('hidden_item', '!=', '1')->where('group', $g->group)->orderBy('sort_order', 'asc')->get();
            array_push($items, $b);
        }

        //振込先情報をセッションに入れる
        $payee=Payment_method::where('selection', '選択')->get();
        session(['bank' => $payee[0]['bank']]);
        session(['branch' => $payee[0]['branch']]);
        session(['swift_code' => $payee[0]['swift_code']]);
        session(['account' => $payee[0]['account']]);
        session(['name' => $payee[0]['name']]);

        //Userinformationsテーブルから住所等を取り出す
        $Userinformations = User::find($user_id)->userinformations;

        if ($Userinformations != null) {
            $consignee = $Userinformations->consignee;
            $address_line1 = $Userinformations->address_line1;
            $address_line2 = $Userinformations->address_line2;
            $city = $Userinformations->city;
            $state = $Userinformations->state;
            $country = $Userinformations->country;
            $country_codes = $Userinformations->country_codes;
            $phone = $Userinformations->phone;
            $fax = $Userinformations->fax;
        } else {
            $consignee = "consignee";
            $address_line1 = "address_line1";
            $address_line2 = "address_line2";
            $city = "city";
            $state = "state";
            $country = "country";
            $country_codes = "country_codes";
            $phone = "phone";
            $fax = "fax";
        }

        $user = array(
            'user_id' => $user_id, 'consignee' => $consignee, 'address_line1' => $address_line1,
            'address_line2' => $address_line2, 'city' => $city, 'state' => $state, 'country' => $country, 'country_codes' => $country_codes, 'phone' => $phone, 'fax' => $fax
        );


        //ユーザーをセッション「session('user')['項目']」に入れる
        $collection = collect($user);
        session()->put('user', $collection);

        return view('fedex', compact('categorys', 'groups', 'items', 'exchange'));
    }

    public function air()
    {
        //コントローラーの引数に$resultを使わずコントローラーの関数内でrequest関数を呼ぶ
        //articleにはAirStokingやネールといった商品分類が入っている
        $result = request('article');
        if ($result=="AirStocking") {
            $result="Air Stocking";
        }
        session()->put('article', $result);
        //dd(session('article'));

        session()->put('type', 'air');

        $user_id = Auth::id();

        //為替 Preferenceテーブルの1レコード目(tts,ttb)にあるので使う際に$exchange->tts
        $exchange = Preference::first();

        $fedexItems = Product::where('hidden_item', '!=', '1')->where('category', session('article'))->orderBy('sort_order', 'asc')->get();

        //カテゴリーのユニークだけ(ここではAIRSTOCKINGだけだが、今後ネールなどが入ってくる)
        $categorys=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('category')->orderBy('sort_order', 'asc')->get(['category']);

        //Air Stocking中分類（PREMIUM-SILKとDIAMOND LEGS）
        $groups=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('group')->orderBy('sort_order', 'asc')->get(['group']);

        //グループ別の商品配列
        $items=[];
        foreach ($groups as $g) {
            $b=Product::where('hidden_item', '!=', '1')->where('group', $g->group)->orderBy('sort_order', 'asc')->get();
            array_push($items, $b);
        }

        //振込先情報をセッションに入れる
        $payee=Payment_method::where('selection', '選択')->get();
        session(['bank' => $payee[0]['bank']]);
        session(['branch' => $payee[0]['branch']]);
        session(['swift_code' => $payee[0]['swift_code']]);
        session(['account' => $payee[0]['account']]);
        session(['name' => $payee[0]['name']]);

        //Userinformationsテーブルから住所等を取り出す
        $Userinformations = User::find($user_id)->userinformations;

        if ($Userinformations != null) {
            $consignee = $Userinformations->consignee;
            $address_line1 = $Userinformations->address_line1;
            $address_line2 = $Userinformations->address_line2;
            $city = $Userinformations->city;
            $state = $Userinformations->state;
            $country = $Userinformations->country;
            $country_codes = $Userinformations->country_codes;
            $phone = $Userinformations->phone;
            $fax = $Userinformations->fax;
        } else {
            $consignee = "consignee";
            $address_line1 = "address_line1";
            $address_line2 = "address_line2";
            $city = "city";
            $state = "state";
            $country = "country";
            $country_codes = "country_codes";
            $phone = "phone";
            $fax = "fax";
        }

        $user = array(
            'user_id' => $user_id, 'consignee' => $consignee, 'address_line1' => $address_line1,
            'address_line2' => $address_line2, 'city' => $city, 'state' => $state, 'country' => $country, 'country_codes' => $country_codes, 'phone' => $phone, 'fax' => $fax
        );

        //ユーザーをセッション「session('user')['項目']」に入れる
        $collection = collect($user);
        session()->put('user', $collection);
        //return view('fedex', compact('premium_silks', 'diamond_legs', 'exchange'));
        //\Session::flash('flash_message', 'More than [10] inputs per product<BR>Total [100] or more inputs per series');
        //session()->flash('flash_message', 'メッセージ');
        return view('air', compact('categorys', 'groups', 'items', 'exchange'));
    }

    public function ship()
    {
        //コントローラーの引数に$resultを使わずコントローラーの関数内でrequest関数を呼ぶ
        //articleにはAirStokingやネールといった商品分類が入っている
        $result = request('article');
        session()->put('article', $result);

        session()->put('type', 'ship');
        $user_id = Auth::id();

        //為替 Preferenceテーブルの1レコード目(tts,ttb)にあるので使う際に$exchange->tts
        $exchange = Preference::first();

        $fedexItems = Product::where('hidden_item', '!=', '1')->where('category', session('article'))->orderBy('sort_order', 'asc')->get();

        //カテゴリーのユニークだけ(ここではAIRSTOCKINGだけだが、今後ネールなどが入ってくる)
        $categorys=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('category')->orderBy('sort_order', 'asc')->get(['category']);

        //Air Stocking中分類（PREMIUM-SILKとDIAMOND LEGS）
        $groups=Product::where('hidden_item', '!=', '1')->where('category', session('article'))->groupBy('group')->orderBy('sort_order', 'asc')->get(['group']);

        //グループ別の商品配列
        $items=[];
        foreach ($groups as $g) {
            $b=Product::where('hidden_item', '!=', '1')->where('group', $g->group)->orderBy('sort_order', 'asc')->get();
            array_push($items, $b);
        }

        //振込先情報をセッションに入れる
        $payee=Payment_method::where('selection', '選択')->get();
        session(['bank' => $payee[0]['bank']]);
        session(['branch' => $payee[0]['branch']]);
        session(['swift_code' => $payee[0]['swift_code']]);
        session(['account' => $payee[0]['account']]);
        session(['name' => $payee[0]['name']]);

        //Userinformationsテーブルから住所等を取り出す
        $Userinformations = User::find($user_id)->userinformations;

        if ($Userinformations != null) {
            $consignee = $Userinformations->consignee;
            $address_line1 = $Userinformations->address_line1;
            $address_line2 = $Userinformations->address_line2;
            $city = $Userinformations->city;
            $state = $Userinformations->state;
            $country = $Userinformations->country;
            $country_codes = $Userinformations->country_codes;
            $phone = $Userinformations->phone;
            $fax = $Userinformations->fax;
        } else {
            $consignee = "consignee";
            $address_line1 = "address_line1";
            $address_line2 = "address_line2";
            $city = "city";
            $state = "state";
            $country = "country";
            $country_codes = "country_codes";
            $phone = "phone";
            $fax = "fax";
        }

        $user = array(
            'user_id' => $user_id, 'consignee' => $consignee, 'address_line1' => $address_line1,
            'address_line2' => $address_line2, 'city' => $city, 'state' => $state, 'country' => $country, 'country_codes' => $country_codes, 'phone' => $phone, 'fax' => $fax
        );

        //ユーザーをセッション「session('user')['項目']」に入れる
        $collection = collect($user);
        session()->put('user', $collection);
        //return view('fedex', compact('premium_silks', 'diamond_legs', 'exchange'));
        return view('ship', compact('categorys', 'groups', 'items', 'exchange'));
    }
}
