<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use App\Model\Transaction;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Exception\RequestException;
use App\Http\Controllers\Wallet\WalletController;

class verifyWithdrawals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'verify:withdrawals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Call the API endpoint for verification of user withdrawal attempts.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //

        $pending_withdrawals = Transaction::where('status', 2)->where('type', 'Debit')->where('title', 'Withdrawal to Bank Account')->get();

        $count = count($pending_withdrawals);

        if ($count > 0) {
            # code...

            foreach ($pending_withdrawals as $withdrawal) {

                $transaction_id = $withdrawal->uuid;

                $base_url = "https://api.flutterwave.com/v3/transfers/" . $transaction_id;

                $token = env('RAVE_TEST_SECRET_KEY');

                $headers = [
                    'Content-Type'        => 'application/json',
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];

                $client = new Client();

                try {
                    //code...

                    $request = $client->get($base_url, [
                        'headers' => $headers
                    ]);

                    $status_code = $request->getStatusCode();

                    if ($status_code == 200) {
                        # code...
                        DB::transaction(function () use ($request, $transaction_id) {

                            $response = $request->getBody()->getContents();
                            $response = json_decode($response);

                            $status = $response->data->status;

                            $user_id = Transaction::where('uuid', $transaction_id)->first()->user->uuid;

                            $amount = $response->data->amount;

                            if ($status == 'SUCCESSFUL') {
                                # code...
                                $update_status = Transaction::where('uuid', $transaction_id)->update([
                                    'status' => 1
                                ]);
                            } elseif ($status == 'FAILED') {


                                $update_status = Transaction::where('uuid', $transaction_id)->update([
                                    'status' => 0
                                ]);

                                $wallet =  new WalletController();

                                $refund_cash = $wallet->credit_wallet($amount, $user_id);
                            }
                        });
                    }
                } catch (RequestException $ex) {
                    //throw $th;
                    Log::debug('Error confirming withdrawal status for transaction  -' . $transaction_id);
                }
            }
        }
    }
}