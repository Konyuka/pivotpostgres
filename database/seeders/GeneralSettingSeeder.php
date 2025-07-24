<?php

namespace Database\Seeders;

use App\Http\traits\ENVFilePutContent;
use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    use ENVFilePutContent;

    public function run(): void
    {
        GeneralSetting::truncate();

        $siteTitle = "PivotPro";
        $timeZone = "Africa/Nairobi";
        $dateFormat = "d-m-Y";

        GeneralSetting::create([
            'site_title' => $siteTitle,
            'site_logo'  => "logo.png",
            'time_zone' => $timeZone,
            'currency' => "KES",
            'currency_format' => "prefix",
            'default_payment_bank' => 1,
            'date_format' => $dateFormat,
            'theme' => "default.css",
            'footer' => "Tech Duka",
            'footer_link' => "https://www.techduka.co.ke",
        ]);

        //writting timezone info in .env file
        $this->dataWriteInENVFile('APP_NAME',$siteTitle);
        $this->dataWriteInENVFile('APP_TIMEZONE',$timeZone);
        $this->dataWriteInENVFile('Date_Format',$dateFormat);
        $js_format = config('date_format_conversion.' . $dateFormat);
        $this->dataWriteInENVFile('Date_Format_JS',$js_format);
        $this->dataWriteInENVFile('ENABLE_EARLY_CLOCKIN',1);
    }
}
