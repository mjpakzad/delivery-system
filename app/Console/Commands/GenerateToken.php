<?php

namespace App\Console\Commands;

use App\Models\Business;
use App\Models\Courier;
use App\Services\Contracts\BusinessServiceInterface;
use App\Services\Contracts\CourierServiceInterface;
use App\Services\Contracts\TokenServiceInterface;
use Illuminate\Console\Command;

class GenerateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delivery:generate-token {--business=* : Id of business to generate token for} {--courier=* : Id of couriers to generate token for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command generate token for business and courier user';

    private TokenServiceInterface $tokenService;
    private BusinessServiceInterface $businessService;
    private CourierServiceInterface $courierService;

    private function injectTokenService(): void
    {
        $this->tokenService = resolve(TokenServiceInterface::class);
    }

    private function injectBusinessService(): void
    {
        $this->businessService = resolve(BusinessServiceInterface::class);
    }

    private function injectCourierService(): void
    {
        $this->courierService = resolve(CourierServiceInterface::class);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $businesses = $this->option('business');
        $couriers = $this->option('courier');
        $businessesCount = count($businesses);
        $couriersCount = count($couriers);
        $this->injectTokenService();
        $this->injectBusinessService();
        $this->injectCourierService();
        if($businessesCount + $couriersCount > 0) {
            $this->alert('Your key(s) generated:');
            $this->generateBusinessesToken($businesses);
            $this->generateCouriersToken($couriers);
            return 0;
        }
        $businesses = [Business::factory()->create()->id];
        $couriers = [Courier::factory()->create()->id];
        $this->generateBusinessesToken($businesses);
        $this->generateCouriersToken($couriers);
        return 0;
    }

    /**
     * @param array $businesses
     * @return void
     */
    public function generateBusinessesToken(array $businesses): void
    {
        foreach ($businesses as $businessId) {
            $business = $this->businessService->find($businessId);
            $token = $this->tokenService->generateBusinessToken($business);
            $this->info('The following token is for business (id:' . $business->id . ')' );
            $this->info($token);
        }
    }

    /**
     * @param array $couriers
     * @return void
     */
    private function generateCouriersToken(array $couriers): void
    {
        foreach ($couriers as $courierId) {
            $courier = $this->courierService->find($courierId);
            $token = $this->tokenService->generateCourierToken($courier);
            $this->info('The following token is for courier (id:' . $courier->id . ')' );
            $this->info($token);
        }
    }
}
