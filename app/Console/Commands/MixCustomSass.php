<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class MixCustomSass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mix:existing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fire Laravel Mix For All Existing Custom CSS Files';

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
     * @return mixed
     */
    public function handle()
    {

        $this->comment('Locating all existing CSS files...');

        $files = [];
        foreach ( \File::allFiles(public_path('css')) as $file ) {
            if ( preg_match('/^\d+/', basename($file)) ) {
                $colors = [];
                $color_keys = str_split(preg_replace('/\.css/', '', basename($file)), 2);
                foreach ( $color_keys as $key ) {
                    $key = preg_replace('/^0/', '', $key);
                    if ( !isset(User::$adminlyColors[$key]) ) {
                        continue;
                    }
                    $colors[] = User::$adminlyColors[$key];
                }
                $files[] = $colors;
            }
        }

        $this->comment('Found ' . count($files) . ' CSS files that need to be updated...');

        $success_count = 0;
        foreach ( $files as $colors_arr ) {
            // build colors string
            $colors = implode(',', array_values($colors_arr));

            // determine the filename based on our colors and their index position
            $filename = '';
            $color_keys = [];
            foreach ( $colors_arr as $color ) {
                $color_keys[] = str_pad(array_search($color, User::$adminlyColors), 2, '0', STR_PAD_LEFT);
            }
            $filename .= implode('', $color_keys) . '.css';

            if ( file_exists(base_path('public/css/' . $filename)) ) {

                // production
                //exec('cd ' . base_path() . ' && node node_modules/cross-env/dist/bin/cross-env.js NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js --env.colors=' . $colors . ' --env.filename=' . $filename . ' 2>&1', $output, $return_var);

                // development
                exec('cd ' . base_path() . ' && node node_modules/cross-env/dist/bin/cross-env.js NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js --env.colors=' . $colors . ' --env.filename=' . $filename . ' 2>&1', $output, $return_var);

                if ( $return_var === 0 ) {
                    $this->info($filename . ' rebuilt successfully!');
                    $success_count++;
                } else {
                    $this->error($filename . ' failed the rebuild.');
                }

            }

        }

        if ( $success_count > 0 ) {
            $this->info($success_count . '/' . count($files) . ' CSS files were updated successfully.');
        } else {
            $this->error('0 CSS files were updated.');
        }

    }
}
