<?php

namespace App\Console\Commands;

use App\Models\EmdPricingPlan;
use App\Models\EmdUserTransaction;
use App\Models\EmdWebUser;
use App\Models\User;
use App\Repositories\EmdUserTransactionRepository;
use Faker\Factory as Faker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class RandomUserGeneratorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'random:user-generator {no_of?} {password?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates random users and user transactions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (config('app.env') == "local") {
            $no_of = (int) ($this->argument('no_of') ?? 10);
            $password = $this->argument('password') ?? "password";
            if (strlen($password) < 8) {
                dd("Password length must be 8 characters or higher");
            }
            $no_of = $no_of > 200 ? 200 : $no_of;
            $faker = Faker::create();
            for ($i = 0; $i < $no_of; $i++) {
                $user = User::create([
                    'name' => $faker->name,
                    'email' => $faker->unique()->safeEmail,
                    'password' => Hash::make($password),
                    'hash' => time(),
                    'admin_level' => User::WEB_REGISTER,
                ]);
                $emd_web_user = EmdWebUser::create([
                    'user_id' => $user->id,
                    'register_from' => EmdWebUser::REGISTER_FROM_RANDOM,
                    'api_key' => md5(time()),
                    'is_web_premium' => 0,
                    'is_api_premium' => 0,
                ]);

                if (rand(0, 1) == 1) {
                    $emd_pricing_plan = EmdPricingPlan::where('is_custom', EmdPricingPlan::SIMPLE_PLAN)->where('is_mobile', 0)->where('is_api', EmdPricingPlan::WEB_PLAN)->inRandomOrder()->first();
                    if ($emd_pricing_plan) {
                        $data = [];
                        $data['id'] = $emd_pricing_plan->id;
                        $data['order_status'] = EmdUserTransaction::OS_RANDOM_GENERATOR;
                        $data['order_no'] = "R" . time();
                        $data['payment_from'] = "Random";
                        $data['is_test_mode'] = EmdUserTransaction::TEST_MODE;
                        EmdUserTransactionRepository::assign_plan_to_user(request: $data, user_id: $user->id, plan_id: true);
                        $emd_web_user->is_web_premium = 1;
                        $emd_web_user->save();
                    }
                }
            }
            dump($no_of . " Random User Generated");
        } else {
            dump("You are not in local mode");
        }
        return Command::SUCCESS;
    }
}
