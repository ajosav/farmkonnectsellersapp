<?php

namespace App\Http\Controllers\Wallet;

use App\User;
use App\Model\Wallet;
use App\Model\Account;
use GuzzleHttp\Client;
use App\Model\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use App\Events\WalletCreditValidated;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use App\Events\SuccessfulUserWalletWithdrawal;

class WalletController extends Controller
{

    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallet = Wallet::where('user_id', Auth::user()->uuid);

        if ($wallet == null) {
            # code...

            event(new Verified(Auth::user()));
        }
        //
        return view('pages.user.wallet');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $account = Auth::user()->bank_account;

        if (!$account) {
            # code...

            return redirect('/bank-account')->with('error', 'Kindly save your bank account to continue.');
        }
        return view('pages.user.withdraw');
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
    public function show()
    {
        //
        $transactions = Transaction::where('user_id', Auth::user()->uuid)->latest()->get();

        $total_credit = Transaction::where('user_id', Auth::user()->uuid)->where('type', 'Credit')->where('status', 1)->sum('amount');

        $total_debit = Transaction::where('user_id', Auth::user()->uuid)->where('type', 'Debit')->where('status', 1)->sum('amount');

        return view('pages.user.transactions', ['transactions' => $transactions, 'total_credit' => $total_credit, 'total_debit' => $total_debit]);
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

    protected function validate_rave_transaction(string $transaction_id)
    {
        $client = new Client();

        $base_url = "https://api.flutterwave.com/v3/transactions/" . $transaction_id . "/verify";

        $token = env('RAVE_TEST_SECRET_KEY');

        $headers = [
            'Content-Type'        => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $request = $client->get($base_url, [
            'headers' => $headers
        ]);

        $response = $request->getBody()->getContents();

        $response = json_decode($response);

        $status = $response->status;

        if ($status != 'success') {
            # code...
            return false;
        }

        return $response;
    }


    public function find_user($uuid)
    {
        return User::where('uuid', $uuid)->firstOrFail();
    }


    public function credit_wallet($amount, $user_id)
    {
        $user = $this->find_user($user_id);

        if ($user != null) {
            # code...
            $current_balance = $user->wallet->balance;

            return Wallet::where('user_id', $user_id)->update([
                'balance' => $current_balance + $amount
            ]);
        }
    }

    public function debit_wallet($amount, $user_id)
    {
        $user = $this->find_user($user_id);

        if ($user != null) {
            # code...
            $current_balance = $user->wallet->balance;

            if ($amount > $current_balance) {
                # code...
                return false;
            }

            return Wallet::where('user_id', $user_id)->update([
                'balance' => $current_balance - $amount
            ]);
        }
    }

    public function set_cookie(Request $request)
    {

        $response = new Response('Set Cookie');
        $response->withCookie(cookie('amount', $request->amount));
        return $response;
    }

    public function status(Request $request)
    {
        # code...
        $txn_id = $request->transaction_id;
        $txn_ref = $request->tx_ref;
        $amount = $request->cookie('amount');
        Cookie::queue(Cookie::forget('amount'));

        $valid_transaction = $this->validate_rave_transaction($txn_id);

        if ($valid_transaction != true) {
            # code...
            $log = (object) [
                'transaction_id' => $txn_id,
                'txn_ref' => $txn_ref,
                'reason' => 'The transaction was not completed on flutterwave.',
                'time' => now()
            ];

            Log::channel('transactions')->info(json_encode($log));
            return redirect()->route('wallet')->with('error', 'Transaction Error. Please Try again later.');
        }

        $amount_paid = $valid_transaction->data->amount;

        if ($amount_paid < $amount) {
            # code...

            $log = [
                'transaction_id' => $txn_id,
                'txn_ref' => $txn_ref,
                'reason' => 'The amount debited from customer was ' . $amount_paid . ' instead of ' . $amount,
            ];

            $log = (object) $log;

            Log::channel('transactions')->info(json_encode($log));

            return redirect()->route('wallet')->with('error', 'Invalid Transaction. Please Try again later.');
        }

        $transaction = new Transaction();

        $existing_transaction = $transaction->existing_transaction($valid_transaction->data->id, $valid_transaction->data->tx_ref);

        if ($existing_transaction) {
            # code...

            return redirect()->route('wallet')->with('error', 'Invalid Transaction Detected.');
        }

        $credit_wallet = $this->credit_wallet($amount_paid, Auth::user()->uuid);

        if ($credit_wallet) {
            # code...
            event(new WalletCreditValidated($valid_transaction));

            return redirect()->route('wallet')->with('success', 'Wallet Successfully credited.');
        }

        return redirect()->route('wallet')->with('error', 'Error Crediting wallet. Kindly try again later.');
    }

    protected function account_validator($request)
    {
        return Validator::make($request, [
            'remember' => ['required'],
            'country' => ['required', 'string'],
            'account_bank' => ['required', 'numeric'],
            'account_name' => ['required', 'string'],
            'bank_name' => ['required', 'string'],
            'account_number' => ['required', 'string']
        ]);
    }

    protected function withdrawal_validator($request)
    {
        return Validator::make($request, [
            'amount' => ['required', 'digits']
        ]);
    }

    protected function fetch_banks($country)
    {
        $client = new Client();
        $base_url = "https://api.flutterwave.com/v3/banks/" . $country;

        $token = env('RAVE_TEST_SECRET_KEY');

        $headers = [
            'Content-Type'        => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        try {

            $request = $client->get($base_url, [
                'headers' => $headers
            ]);

            $list = $request->getBody()->getContents();

            return ['status' => $request->getStatusCode(), 'list' => $list];
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $message = json_decode($e->getResponse()->getBody());
                $status_code = $e->getResponse()->getStatusCode();
                return ['status' => $status_code, 'error' => $message];
            }
        } catch (ConnectException $e) {
            return ['status' => 400, 'error' => 'Poor Network Connection. System unable to fetch supported banks.'];
        } catch (RequestException $e) {
            return ['status' => 400, 'error' => 'Request timeout. Fetching of supported banks failed.'];
        }
    }

    public function accounts()
    {
        //
        $account = Auth::user()->bank_account;

        $fetch_banks = $this->fetch_banks('NG');

        $fetch_banks = (object) $fetch_banks;

        return view('pages.user.bank-account', ['account' => $account, 'fetch_banks' => $fetch_banks]);
    }

    public function bank_account(Request $request)
    {
        $this->account_validator($request->all());

        $request->input('user_id', Auth::user()->uuid);

        $request->merge(['user_id' => auth()->user()->uuid, 'country' => 'NG']);

        $save_account = Account::updateOrCreate(['user_id' => Auth::user()->uuid], $request->except('confirm'));

        if ($save_account) {
            # code...
            return redirect('/home')->with('success', 'Bank Account Successfully Saved.');
        }

        return redirect()->back()->with('error', 'Error saving bank account. Please try again later.');
    }

    public function confirm_withdrawal(Request $request)
    {
        $user = new User();

        $this->withdrawal_validator($request->all());

        $request->merge(['recipient_email' => auth()->user()->email]);

        $recipient = $user->find_by_email($request->recipient_email);

        $transaction = $request->all();

        $transaction = (object) $transaction;

        return view('pages.user.confirm-withdrawal', ['transaction' => $transaction, 'recipient' => $recipient]);
    }

    public function withdraw_money(Request $request)
    {
        $account_number = Auth::user()->bank_account->account_number;
        $account_bank = Auth::user()->bank_account->account_bank;
        $account_uuid = Auth::user()->bank_account->uuid;
        $account_name = Auth::user()->bank_account->account_name;
        $amount = $request->amount;

        $url = url("/confirm-withdrawal");

        $data = [
            "account_number" => $account_number,
            "account_bank" => $account_bank,
            "amount" => $amount,
            "narration" => env('APP_NAME') . " Wallet Withdrawal",
            "currency" => "NGN",
            "reference" => 'FSA-' . mt_rand() . "_PMCKDU_1", //Remove the suffix when going live with the payment.
            "beneficiary_name" =>  $account_name,
            "callback_url" => $url
        ];

        $available_balance = Auth::user()->wallet->balance;

        if ($available_balance < $amount) {
            # code...

            return response()->json(['status' => '0', 'msg' => 'Insufficient Wallet Balance.']);
        }

        $process_withdrawal = $this->rave_withdrawal($data);

        if (!$process_withdrawal->status_code) {
            # code...
            if ($process_withdrawal->status) {
                # code...
                $status = $process_withdrawal->status;

                if ($status == 400) {
                    # code...
                    return response()->json(['status' => '0', 'msg' => $process_withdrawal->error->message]);
                }
            }
        }

        $status_code = $process_withdrawal->status_code;

        if ($status_code == 200) {
            # code...
            $response = json_decode($process_withdrawal->response);
            $status = $response->status;
            $message = $response->message;

            if ($status == 'success' && $message == 'Transfer Queued Successfully') {
                # code...

                $transfer_id = $response->data->id;

                $amount_withdrawn = $response->data->amount;

                $bank_name = $response->data->bank_name;

                $account_number = $response->data->account_number;

                $txn_ref = $response->data->narration;

                Wallet::where('user_id', Auth::user()->uuid)->update([
                    'balance' => $available_balance - $amount_withdrawn
                ]);

                event(new SuccessfulUserWalletWithdrawal($response));

                return response()->json(['status' => '1', 'msg' => 'Withdrawal Request Successfully Queued']);
            }
        }
    }

    private function rave_withdrawal($data)
    {
        $base_url = "https://api.flutterwave.com/v3/transfers";

        $token = env('RAVE_TEST_SECRET_KEY');

        $headers = [
            'Content-Type'        => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token
        ];

        $client = new Client();

        try {
            //code...
            $request = $client->post($base_url, [
                'headers' => $headers,
                'json' => $data
            ]);

            $status_code = $request->getStatusCode();

            $response = $request->getBody()->getContents();

            return (object) ['status_code' => $status_code, 'response' =>  $response];
        } catch (ClientException $e) {
            if ($e->hasResponse()) {
                $message = json_decode($e->getResponse()->getBody());
                $status_code = $e->getResponse()->getStatusCode();
                return (object) ['status' => $status_code, 'error' => $message];
            }
        } catch (ConnectException $e) {
            return (object) ['status' => 400, 'error' => 'Poor Network Connection.'];
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $message = json_decode($e->getResponse()->getBody());
                $status_code = $e->getResponse()->getStatusCode();
                return (object) ['status' => $status_code, 'error' => $message];
            }
        }
    }
}