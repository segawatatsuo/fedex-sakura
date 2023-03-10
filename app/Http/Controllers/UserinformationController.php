<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Model\User;
use App\Model\Product;
use App\Model\Preference;
use App\Model\Quotation;
use App\Model\Quotation_detail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Model\Userinformation;
use App\Model\Invoice_serial_number;
use App\Model\Invoice_counter;

//use App\Http\Controllers\QuotationController;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegisterMail;
use App\Model\Emailtext;

class UserinformationController extends Controller
{
    //初めての人の住所登録
    public function entry(Request $request)
    {
        /*
        $request->validate(
            ['country_codes' => 'required|size:2',],
            ['country_codes.required' => '2 letters',]
        );
        */
        $type = $request->type;

        $user_id = $request->user_id;
        $quotation_no = $request->quotation_no;
        $user = new Userinformation();
        $user->user_id = $request->user_id;
        $user->consignee = $request->consignee;
        $user->address_line1 = $request->address_line1;
        $user->address_line2 = $request->address_line2;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->country_codes = $request->country_codes;
        $user->zip = $request->zip;
        $user->phone = $request->phone;
        $user->person = $request->person;

        $user->save();
        
        //ここで最初のメールを出す
        $name = user::find($user_id)->name;
        $email = user::find($user_id)->email;
        //メール文章
        $mails = Emailtext::find(1);
        $mail_subject = $mails["subject_7"];
        $content = $mails["contents_7"];
 
        $consignee = $request->consignee;
        $address_line1 = $request->address_line1;
        $address_line2 = $request->address_line2;
        $city = $request->city;
        $state = $request->state;
        $country = $request->country_codes;
        $zip = $request->zip;
        $phone = $request->phone;
        $person = $request->person;
        $data = 
        "Consignee | ". $consignee . "\n" .
        "Address line1 | ". $address_line1 . "\n" .
        "Address line2 | ". $address_line2 . "\n" .
        "City | ". $city . "\n" .
        "State | ". $state . "\n" .
        "Country | ". $country . "\n" .
        "zip code | ". $zip . "\n" .
        "phone number | ". $phone. "\n" .
        "Contact person | ". $person;
        $content = str_replace('※replacement2※', $data, $content);
        $content = $content;

        Mail::send(new RegisterMail($name, $email, $mail_subject, $content));
        //ここで最初のメールを出す
       
        

        $uuid = $quotation_no;
        $main = [];

        $quotations = Quotation::where('quotation_no', $quotation_no)->get();

        $shipper = $quotations[0]->shipper;
        $consignee = $quotations[0]->consignee;
        $port_of_loading = $quotations[0]->port_of_loading;
        $final_destination = $quotations[0]->final_destination;
        $sailing_on = $quotations[0]->sailing_on;
        $arriving_on = $quotations[0]->arriving_on;
        $expiry = $quotations[0]->expiry;
        $preference_data = Preference::first();

        $main = [$quotation_no, $preference_data, $shipper, $consignee, $port_of_loading, $final_destination, $sailing_on, $arriving_on, $expiry];

        $quotations_sub = Quotation_detail::where('quotation_no', $request->quotation_no)->get();
        $data = [];
        $items = [];

        foreach ($quotations_sub as $quotation) {
            $product_code = $quotation->product_code;
            $product_name = $quotation->product_name;
            $quantity = $quotation->quantity;
            $ctn = $quotation->ctn;
            $quantity = $quotation->quantity;
            $unit_price = $quotation->unit_price;
            $amount = $quotation->amount;
            $data = [$product_code, $product_name, $quantity, $ctn, $unit_price, $amount];
            array_push($items, $data);
        }

        $quantity_total = $quotations[0]->quantity_total;
        $ctn_total = $quotations[0]->ctn_total;
        $amount_total = $quotations[0]->amount_total;
        $total = [$quantity_total, $ctn_total, $amount_total];

        //return view('quotation', compact('uuid', 'preference_data', 'items', 'ctn_total', 'quantity_total', 'amount_total', 'sailing_on', 'user'));
        return view('quotation', compact('quotation_no', 'preference_data', 'items', 'ctn_total', 'quantity_total', 'amount_total', 'sailing_on', 'user', 'type'));
    }


    //インボイスが初めてか確認
    public function invoice_confirm(Request $request)
    {
        $type = $request->type;

        $uuid = $request->quotation_no;
        $user_id = Auth::id();
        $Userinformations = Userinformation::where('user_id', '=', $user_id)->get();

        //初めての場合
        if ($Userinformations->first()->bill_company_address_line1 == null) {
            //フォームから最終目的地を受け取り登録してから移動
            $final_destination = $request->get('final_destination');
            $quotation_no = $request->quotation_no;

            $quotations = Quotation::where('quotation_no', $quotation_no)->get();
            foreach ($quotations as $quotation) {
                $quotation->final_destination = $final_destination;
                $quotation->save();
            }

            $user_id = Auth::id();
            $main = [];

            $final_destination = $request->get('final_destination');

            //Preferenceから
            $preference_data = Preference::first();

            ///////////////////////////////
            //インボイス番号作成
            //Userinformationの１行目からuser_idを取り出しイニシャルを探してインボイスNoを作成し保存
            //イニシャル
            $ui = Userinformation::where('user_id', $user_id)->first();
            $initial = $ui->initial;
            //国
            $country_code = $ui->country_codes;
            $un = User::where('id', '=', $user_id)->first();
            //国２文字
            $ct = strtoupper(substr($un->country, 0, 2));
            //会社名２文字
            $ui = Userinformation::where('user_id', '=', $user_id)->first();

            if (strtoupper(substr($ui->initial, 0, 2)) != null) {
                $cp = strtoupper(substr($ui->initial, 0, 2));
            } else {
                $cp = strtoupper(substr($ui->company_name, 0, 2));
            }
            if ($cp == "") {
                $cp = "CC";
            }
            //連番
            $latestOrder = Invoice_counter::where('id', 1)->first();
            $today = date('Y-m-d');

            if ($today != $latestOrder->last_update) {
                $latestOrder->count = 1;
                $latestOrder->last_update = date('Y/m/d');
                $latestOrder->save();
            } else {
                $latestOrder->count = $latestOrder->count + 1;
                $latestOrder->save();
            }
            $no = $latestOrder->count;
            $invoice_no =  $ct . $cp . date('md') . '_' . str_pad($no, 2, 0, STR_PAD_LEFT);
            ;
            $output = $invoice_no . '.pdf';
            $print_no = $invoice_no;
            ///////////////////////////////

            //uuid
            $uuid = $quotation_no;
            //英語日付
            $day = date("F j Y");

            //Quotationから見積り内容の行を取ってくる※ $quotation_noがNULL
            $quotations = \App\Model\Quotation::where('quotation_no', $quotation_no)->get();

            //Quotationsにフォームから来たfinal_destinationを上書き保存（これでQuotationsの入力は完了）
            //初めての人は前のコントローラーで保存しているのでフォームからはこない（$final_destinationがnullの場合もある）
            if ($final_destination != null) {
                foreach ($quotations as $quotation) {
                    $quotation->final_destination = $final_destination;
                    $quotation->save();
                }
            }

            //複数行ある可能性があるので配列の1行目[0]から
            //初めての場合エラーUndefined offset: 0

            $shipper = $quotations[0]->shipper;

            $consignee_no = $quotations[0]->consignee_no;
            $consignee = Userinformation::where('user_id', $consignee_no)->first()->consignee;
            $port_of_loading = $quotations[0]->port_of_loading;
            $final_destination = $quotations[0]->final_destination;
            $sailing_on = $quotations[0]->sailing_on;
            $arriving_on = $quotations[0]->arriving_on;
            $expiry = $quotations[0]->expiry;

            //入力フォームの表示用に
            $main = User::find($user_id);
            $company_name = $main->company_name;
            $country = $main->country;

            $address_line1 = $ui->address_line1;
            $address_line2 = $ui->address_line2;
            $city = $ui->address_line2;
            $state = $ui->state;
            $zip = $ui->zip;
            $phone = $ui->phone;
            $person = $ui->person;
            //イニシャルを２文字で作成
            $initial = substr($company_name, 0, 2);

            //上記項目を配列$mainにまとめる
            $main = [
                'invoice_no' => $invoice_no,
                'uuid' => $uuid,
                'quotation_no' => $quotation_no,
                'preference_data' => $preference_data,
                'shipper' => $shipper,
                'consignee' => $consignee,
                'port_of_loading' => $port_of_loading,
                'final_destination' => $final_destination,
                'sailing_on' => $sailing_on,
                'arriving_on' => $arriving_on,
                'expiry' => $expiry,
                'day' => $day,
                'company_name' => $company_name,
                'address_line1' => $address_line1,
                'address_line2' => $address_line2,
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'zip' => $zip,
                'phone' => $phone,
                'person' => $person,
                'initial' => $initial
            ];

            //商品を配列$itemsにまとめる
            $quotations_sub = \App\Model\Quotation_detail::where('quotation_no', $quotation_no)->get();
            $data = [];
            $items = [];
            foreach ($quotations_sub as $quotation) {
                $product_code = $quotation->product_code;
                $product_name = $quotation->product_name;
                $quantity = $quotation->quantity;
                $ctn = $quotation->ctn;
                $quantity = $quotation->quantity;
                $unit_price = $quotation->unit_price;
                $amount = $quotation->amount;
                $data = [$product_code, $product_name, $quantity, $ctn, $unit_price, $amount];
                array_push($items, $data);
            }

            $quantity_total = $quotations[0]->quantity_total;
            $ctn_total = $quotations[0]->ctn_total;
            $amount_total = $quotations[0]->amount_total;
            //合計関係を$totalにまとめる
            $total = [
                'quantity_total' => $quantity_total,
                'ctn_total' => $ctn_total,
                'amount_total' => $amount_total
            ];

            //画面上の顧客情報用(base.blade.php)
            $user = [
                'user_id' => $user_id,
                'consignee' => $consignee,
                'address_line1' => $ui->address_line1,
                'address_line2' => $ui->address_line2,
                'city' => $ui->city,
                'state' => $ui->state,
                'country' => User::where('id', $user_id)->first()->country,
                'country_codes' => $ui->country_codes,
                'zip' => $ui->zip,
                'phone' => $ui->phone,
                'fax' => $ui->fax
            ];


            //インボイステーブルにデータを登録
            $invoice = new \App\Model\Invoice();
            $invoice->quotation_no = $quotation_no;
            $invoice->invoice_no = $invoice_no;
            $invoice->customers_id = $user_id;
            $invoice->date_of_issue = date('Y/m/d H:i:s');
            $invoice->day = $day;
            $invoice->save();        
            
            return view('invoice_entryform', compact('uuid', 'user_id', 'final_destination', 'main', 'user', 'items', 'total', 'type'));


        } else {
            $user_id = Auth::id();
            $main = [];
            $type = $request->type;

            //送信formから
            $quotation_no = $request->get('quotation_no');
            $final_destination = $request->get('final_destination');

            //$i = new QuotationController(); // Quotationクラスのインスタンスを生成
            //$i->generate_quotation_pdf($request);

            //Preferenceから
            $preference_data = Preference::first();

            ///////////////////////////////
            //インボイス番号作成
            //Userinformationの１行目からuser_idを取り出しイニシャルを探してインボイスNoを作成し保存
            //イニシャル
            $ui = Userinformation::where('user_id', $user_id)->first();
            $initial = $ui->initial;
            //国
            $country_code = $ui->country_codes;
            $un = User::where('id', '=', $user_id)->first();
            //国２文字
            $ct = strtoupper(substr($un->country, 0, 2));
            //会社名２文字
            $ui = Userinformation::where('user_id', '=', $user_id)->first();

            if (strtoupper(substr($ui->initial, 0, 2)) != null) {
                $cp = strtoupper(substr($ui->initial, 0, 2));
            } else {
                $cp = strtoupper(substr($ui->company_name, 0, 2));
            }
            if ($cp == "") {
                $cp = "CC";
            }
            //連番
            $latestOrder = Invoice_counter::where('id', 1)->first();
            $today = date('Y-m-d');

            if ($today != $latestOrder->last_update) {
                $latestOrder->count = 1;
                $latestOrder->last_update = date('Y/m/d');
                $latestOrder->save();
            } else {
                $latestOrder->count = $latestOrder->count + 1;
                $latestOrder->save();
            }
            $no = $latestOrder->count;
            $invoice_no =  $ct . $cp . date('md') . '_' . str_pad($no, 2, 0, STR_PAD_LEFT);
            ;
            $output = $invoice_no . '.pdf';
            $print_no = $invoice_no;
            ///////////////////////////////

            //uuid
            $uuid = $quotation_no;
            //英語日付
            $day = date("F j Y");

            //Quotationから見積り内容の行を取ってくる※
            $quotations = \App\Model\Quotation::where('quotation_no', $quotation_no)->get();

            //Quotationsにフォームから来たfinal_destinationを上書き保存（これでQuotationsの入力は完了）
            //初めての人は前のコントローラーで保存しているのでフォームからはこない（$final_destinationがnullの場合もある）
            if ($final_destination != null) {
                foreach ($quotations as $quotation) {
                    $quotation->final_destination = $final_destination;
                    $quotation->save();
                }
            }

            //複数行ある可能性があるので配列の1行目[0]から
            $shipper = $quotations[0]->shipper;
            $consignee_no = $quotations[0]->consignee_no;
            $consignee = Userinformation::where('user_id', $consignee_no)->first()->consignee;
            $port_of_loading = $quotations[0]->port_of_loading;
            $final_destination = $quotations[0]->final_destination;
            $sailing_on = $quotations[0]->sailing_on;
            $arriving_on = $quotations[0]->arriving_on;
            $expiry = $quotations[0]->expiry;

            //上記項目を配列$mainにまとめる
            $main = [
                'invoice_no' => $invoice_no,
                'uuid' => $uuid,
                'quotation_no' => $quotation_no,
                'preference_data' => $preference_data,
                'shipper' => $shipper,
                'consignee' => $consignee,
                'port_of_loading' => $port_of_loading,
                'final_destination' => $final_destination,
                'sailing_on' => $sailing_on,
                'arriving_on' => $arriving_on,
                'expiry' => $expiry,
                'day' => $day
            ];

            $quotations_sub = \App\Model\Quotation_detail::where('quotation_no', $quotation_no)->get();
            //商品を配列$itemsにまとめる
            $data = [];
            $items = [];
            foreach ($quotations_sub as $quotation) {
                $product_code = $quotation->product_code;
                $product_name = $quotation->product_name;
                $quantity = $quotation->quantity;
                $ctn = $quotation->ctn;
                $quantity = $quotation->quantity;
                $unit_price = $quotation->unit_price;
                $amount = $quotation->amount;
                $data = [$product_code, $product_name, $quantity, $ctn, $unit_price, $amount];
                array_push($items, $data);
            }

            $quantity_total = $quotations[0]->quantity_total;
            $ctn_total = $quotations[0]->ctn_total;
            $amount_total = $quotations[0]->amount_total;
            //合計関係を$totalにまとめる
            $total = [
                'quantity_total' => $quantity_total,
                'ctn_total' => $ctn_total,
                'amount_total' => $amount_total
            ];

            //画面上の顧客情報用(base.blade.php)
            $user = [
                'user_id' => $user_id,
                'consignee' => $consignee,
                'address_line1' => $ui->address_line1,
                'address_line2' => $ui->address_line2,
                'city' => $ui->city,
                'state' => $ui->state,
                'country' => User::where('id', $user_id)->first()->country,
                'country_codes' => $ui->country_codes,
                'zip' => $ui->zip,
                'phone' => $ui->phone,
                'fax' => $ui->fax
            ];


            //インボイステーブルにデータを登録
            $invoice = new \App\Model\Invoice();
            $invoice->quotation_no = $quotation_no;
            $invoice->invoice_no = $invoice_no;
            $invoice->customers_id = $user_id;
            $invoice->date_of_issue = date('Y/m/d H:i:s');
            $invoice->day = $day;
            $invoice->save();


            return view('invoice', compact('main', 'items', 'total', 'user', 'type'));
        }
    }


    public function invoice_entry_and_go(Request $request)
    {
        $user_id = Auth::id();
        $main = [];
        $type = $request->type;

        $ui = Userinformation::where('user_id', $user_id)->first();

        $ui->importer_name = $request->importer_name;
        $ui->bill_company_address_line1 = $request->bill_company_address_line1;
        $ui->bill_company_address_line2 = $request->bill_company_address_line2;
        $ui->bill_company_city = $request->bill_company_city;
        $ui->bill_company_state = $request->bill_company_state;
        $ui->bill_company_country = $request->bill_company_country;
        $ui->bill_company_zip = $request->bill_company_zip;
        $ui->bill_company_phone = $request->bill_company_phone;
        $ui->president = $request->president;
        $ui->initial = $request->initial;

        $ui->industry = $request->industry;
        $ui->business_items = $request->business_items;
        $ui->customer_name = $request->customer_name;
        $ui->fedex = $request->fedex;
        $ui->sns = $request->sns;
        $ui->website = $request->website;
        $ui->save();


        //送信formから
        $quotation_no = $request->get('quotation_no');
        $final_destination = $request->get('final_destination');

        //Preferenceから
        $preference_data = Preference::first();

        ///////////////////////////////
        //インボイス番号作成
        //Userinformationの１行目からuser_idを取り出しイニシャルを探してインボイスNoを作成し保存
        //イニシャル
        $ui = Userinformation::where('user_id', $user_id)->first();
        $initial = $ui->initial;
        //国
        $country_code = $ui->country_codes;
        $un = User::where('id', '=', $user_id)->first();
        //国２文字
        $ct = strtoupper(substr($un->country, 0, 2));
        //会社名２文字
        $ui = Userinformation::where('user_id', '=', $user_id)->first();

        if (strtoupper(substr($ui->initial, 0, 2)) != null) {
            $cp = strtoupper(substr($ui->initial, 0, 2));
        } else {
            $cp = strtoupper(substr($ui->company_name, 0, 2));
        }
        if ($cp == "") {
            $cp = "CC";
        }
        //連番
        $latestOrder = Invoice_counter::where('id', 1)->first();
        $today = date('Y-m-d');

        if ($today != $latestOrder->last_update) {
            $latestOrder->count = 1;
            $latestOrder->last_update = date('Y/m/d');
            $latestOrder->save();
        } else {
            $latestOrder->count = $latestOrder->count + 1;
            $latestOrder->save();
        }
        $no = $latestOrder->count;
        $invoice_no =  $ct . $cp . date('md') . '_' . str_pad($no, 2, 0, STR_PAD_LEFT);
        ;
        $output = $invoice_no . '.pdf';
        $print_no = $invoice_no;
        ///////////////////////////////


        //uuid
        $uuid = $quotation_no;
        //英語日付
        $day = date("F j Y");

        //Quotationから見積り内容の行を取ってくる※
        $quotations = \App\Model\Quotation::where('quotation_no', $quotation_no)->get();

        //Quotationsにフォームから来たfinal_destinationを上書き保存（これでQuotationsの入力は完了）
        //初めての人は前のコントローラーで保存しているのでフォームからはこない（$final_destinationがnullの場合もある）
        if ($final_destination != null) {
            foreach ($quotations as $quotation) {
                $quotation->final_destination = $final_destination;
                $quotation->save();
            }
        }

        //複数行ある可能性があるので配列の1行目[0]から
        $shipper = $quotations[0]->shipper;
        $consignee_no = $quotations[0]->consignee_no;
        $consignee = Userinformation::where('user_id', $consignee_no)->first()->consignee;
        $port_of_loading = $quotations[0]->port_of_loading;
        $final_destination = $quotations[0]->final_destination;
        $sailing_on = $quotations[0]->sailing_on;
        $arriving_on = $quotations[0]->arriving_on;
        $expiry = $quotations[0]->expiry;

        //上記項目を配列$mainにまとめる
        $main = [
            'invoice_no' => $invoice_no,
            'uuid' => $uuid,
            'quotation_no' => $quotation_no,
            'preference_data' => $preference_data,
            'shipper' => $shipper,
            'consignee' => $consignee,
            'port_of_loading' => $port_of_loading,
            'final_destination' => $final_destination,
            'sailing_on' => $sailing_on,
            'arriving_on' => $arriving_on,
            'expiry' => $expiry,
            'day' => $day
        ];

        //商品を配列$itemsにまとめる
        $quotations_sub = \App\Model\Quotation_detail::where('quotation_no', $quotation_no)->get();
        $data = [];
        $items = [];
        foreach ($quotations_sub as $quotation) {
            $product_code = $quotation->product_code;
            $product_name = $quotation->product_name;
            $quantity = $quotation->quantity;
            $ctn = $quotation->ctn;
            $quantity = $quotation->quantity;
            $unit_price = $quotation->unit_price;
            $amount = $quotation->amount;
            $data = [$product_code, $product_name, $quantity, $ctn, $unit_price, $amount];
            array_push($items, $data);
        }

        $quantity_total = $quotations[0]->quantity_total;
        $ctn_total = $quotations[0]->ctn_total;
        $amount_total = $quotations[0]->amount_total;
        //合計関係を$totalにまとめる
        $total = [
            'quantity_total' => $quantity_total,
            'ctn_total' => $ctn_total,
            'amount_total' => $amount_total
        ];

        //画面上の顧客情報用(base.blade.php)
        $user = [
            'user_id' => $user_id,
            'consignee' => $consignee,
            'address_line1' => $ui->address_line1,
            'address_line2' => $ui->address_line2,
            'city' => $ui->city,
            'state' => $ui->state,
            'country' => User::where('id', $user_id)->first()->country,
            'country_codes' => $ui->country_codes,
            'zip' => $ui->zip,
            'phone' => $ui->phone,
            'fax' => $ui->fax
        ];


        //インボイステーブルにデータを登録
        $invoice = new \App\Model\Invoice();
        $invoice->quotation_no = $quotation_no;
        $invoice->invoice_no = $invoice_no;
        $invoice->customers_id = $user_id;
        $invoice->date_of_issue = date('Y/m/d H:i:s');
        $invoice->day = $day;
        $invoice->save();



        //ここでImporterメール
        $name = user::find($user_id)->name;
        $email = user::find($user_id)->email;
        //メール文章
        $mails = Emailtext::find(1);
        $mail_subject = $mails["subject_8"];
        $content = $mails["contents_8"];
        $user = Auth::user();
        $id = $user->id;
        $user = User::with('Userinformation')->find($id);

        $importer_name = $user->userinformation->importer_name;
        $bill_company_address_line1 = $user->userinformation->bill_company_address_line1;
        $bill_company_address_line2 = $user->userinformation->bill_company_address_line2;
        $bill_company_city = $user->userinformation->bill_company_city;
        $bill_company_state = $user->userinformation->bill_company_state;
        $bill_company_country = $user->userinformation->bill_company_country;
        $bill_company_zip = $user->userinformation->bill_company_zip;
        $bill_company_phone = $user->userinformation->bill_company_phone;
        $president = $user->userinformation->president;
        $initial = $user->userinformation->initial;

        $industry = $user->userinformation->industry;
        $business_items = $user->userinformation->business_items;
        $customer_name = $user->userinformation->customer_name;
        $website = $user->userinformation->website;
        $fedex = $user->userinformation->fedex;
        $sns = $user->userinformation->sns;

        $data = 
        "Importer company | ". $importer_name . "\n" .
        "Address line1 | ". $bill_company_address_line1 . "\n" .
        "Address line2 | ". $bill_company_address_line2 . "\n" .
        "Company City | ". $bill_company_city . "\n" .
        "Company State | ". $bill_company_state . "\n" .
        "Company Country | ". $bill_company_country . "\n" .
        "Company Zip | ". $bill_company_zip . "\n" .
        "Company Phone | ". $bill_company_phone. "\n" .
        "Company President | ". $president . "\n" .
        "Company Initials | ". $initial . "\n" .

        "Your Business Type | ". $industry . "\n" .
        "Your Items of Business	 | ". $business_items . "\n" .
        "Your Customer(s) | ". $customer_name . "\n" .
        "Your company Website/URL | ". $website . "\n" .
        "Your FedEX Account | ". $fedex . "\n" .
        "Your company SNS | ". $sns ;
        $content = str_replace('※replacement3※', $data, $content);

        Mail::send(new RegisterMail($name, $email, $mail_subject, $content));
        //ここでImporterメール

        return view('invoice', compact('main', 'items', 'total', 'user', 'type'));
    }




    //初めてインボイスを行う場合の住所登録
    public function invoice_entry(Request $request)
    {
        $request->validate(
            ['initial' => 'required|size:2',],
            ['initial.required' => '2 letters',]
        );

        $uuid = $request->uuid;
        $user_id = $request->user_id;
        $type = $request->type;

        $quotation_no = $request->quotation_no;
        $user = Userinformation::where('user_id', $user_id)->first();
        $user->bill_company_address_line1 = $request->bill_company_address_line1;
        $user->bill_company_address_line2 = $request->bill_company_address_line2;
        $user->bill_company_city = $request->bill_company_city;
        $user->bill_company_state = $request->bill_company_state;
        $user->bill_company_country = $request->bill_company_country;
        $user->bill_company_zip = $request->bill_company_zip;
        $user->bill_company_phone = $request->bill_company_phone;
        $user->president = $request->president;
        $user->industry = $request->industry;
        $user->business_items = $request->business_items;
        $user->customer_name = $request->customer_name;
        $user->initial = $request->initial;
        $user->fedex = $request->fedex;
        $user->sns = $request->sns;
        $user->website = $request->website;
        $user->save();



        $quotation_no = $request->quotation_no;

        $uuid = $quotation_no;

        $main = [];

        $item = Quotation::where('quotation_no', $request->quotation_no)->get();

        $quotations = Quotation::where('quotation_no', $quotation_no)->get();


        $consignee_no = $quotations[0]->consignee_no;

        $consignee = Userinformation::where('user_id', $consignee_no)->first()->consignee;
        $shipper = $quotations[0]->shipper;
        $consignee = $quotations[0]->consignee;
        $port_of_loading = $quotations[0]->port_of_loading;
        $final_destination = $quotations[0]->final_destination;
        $sailing_on = $quotations[0]->sailing_on;
        $arriving_on = $quotations[0]->arriving_on;
        $expiry = $quotations[0]->expiry;
        $preference_data = Preference::first();
        $ui = Userinformation::where('user_id', $user_id)->first();
        $initial = $ui->initial;


        ///////////////////////////////
        //インボイス番号作成
        //Userinformationの１行目からuser_idを取り出しイニシャルを探してインボイスNoを作成し保存
        //イニシャル
        $ui = Userinformation::where('user_id', $user_id)->first();
        $initial = $ui->initial;
        //国
        $country_code = $ui->country_codes;
        $un = User::where('id', '=', $user_id)->first();
        //国２文字
        $ct = strtoupper(substr($un->country, 0, 2));
        //会社名２文字
        $ui = Userinformation::where('user_id', '=', $user_id)->first();

        if (strtoupper(substr($ui->initial, 0, 2)) != null) {
            $cp = strtoupper(substr($ui->initial, 0, 2));
        } else {
            $cp = strtoupper(substr($ui->company_name, 0, 2));
        }
        if ($cp == "") {
            $cp = "CC";
        }
        //連番
        $latestOrder = Invoice_counter::where('id', 1)->first();
        $today=date('Y-m-d');

        if ($today!=$latestOrder->last_update) {
            $latestOrder->count=1;
            $latestOrder->last_update=date('Y/m/d');
            $latestOrder->save();
        } else {
            $latestOrder->count=$latestOrder->count+1;
            $latestOrder->save();
        }
        $no=$latestOrder->count;
        $invoice_no =  $ct . $cp . date('md') . '_' . str_pad($no, 2, 0, STR_PAD_LEFT);
        ;
        $output = $invoice_no . '.pdf';
        $print_no = $invoice_no;
        ///////////////////////////////



        //uuid
        $uuid = $quotation_no;
        //英語日付
        $day = date("F j Y");


        $user =
            [
                'user_id' => $user_id,
                'consignee' => $consignee,
                'address_line1' => $ui->address_line1,
                'address_line2' => $ui->address_line2,
                'city' => $ui->city,
                'state' => $ui->state,
                'country' => User::where('id', $user_id)->first()->country,
                'country_codes' => $ui->country_codes,
                'zip' => $ui->zip,
                'phone' => $ui->phone,
                'fax' => $ui->fax
            ];
        $main =
            [
                'quotation_no' => $quotation_no,
                'preference_data' => $preference_data,
                'shipper' => $shipper,
                'consignee' => $consignee,
                'port_of_loading' => $port_of_loading,
                'final_destination' => $final_destination,
                'sailing_on' => $sailing_on,
                'arriving_on' => $arriving_on,
                'expiry' => $expiry,
                'day' => $day,
                'invoice_no' => $invoice_no,
                'uuid' => $quotation_no
            ];

        //商品を配列$itemsにまとめる
        $quotations_sub = Quotation_detail::where('quotation_no', $quotation_no)->get();
        $data = [];
        $items = [];
        foreach ($quotations as $quotation) {
            $product_code = $quotation->product_code;
            $product_name = $quotation->product_name;
            $quantity = $quotation->quantity;
            $ctn = $quotation->ctn;
            $quantity = $quotation->quantity;
            $unit_price = $quotation->unit_price;
            $amount = $quotation->amount;
            $data = [$product_code, $product_name, $quantity, $ctn, $unit_price, $amount];
            array_push($items, $data);
        }

        $quantity_total = $quotations[0]->quantity_total;
        $ctn_total = $quotations[0]->ctn_total;
        $amount_total = $quotations[0]->amount_total;
        //合計関係を$totalにまとめる
        $total = [
            'quantity_total' => $quantity_total,
            'ctn_total' => $ctn_total,
            'amount_total' => $amount_total
        ];

        return view('invoice', compact('uuid', 'user_id', 'main', 'user', 'items', 'total', 'type'));
    }
}
