<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactUs;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:الصفحه الرئيسيه', ['only' => ['main', 'getStatistics']]);
    // }
    public function main()
    {
        $totalCategory = Category::count();
        $totalReport = ContactUs::count();
        $totalUsers = User::count();
        $totalQuestion = Reservation::count();






        //########################################################################################################################################################//
        $oneYearAgo = now()->subYear()->startOfMonth(); // Start from one year ago

        // Query to get the number of users created per month
        $statistics = DB::table('users')
            ->whereDate('created_at', '>=', $oneYearAgo)
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('YEAR(created_at) as year'), DB::raw('COUNT(id) as user_count'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        $monthlyData = array_fill_keys($monthNames, 0); // Initialize all months with 0 count

        foreach ($statistics as $statistic) {
            $monthName = $monthNames[$statistic->month - 1]; // Convert month number to name
            $monthlyData[$monthName] = $statistic->user_count;
        }

        $labels = array_keys($monthlyData);
        $userCounts = array_values($monthlyData);

        $colors = array_map(function () {
            return '#' . substr(md5(rand()), 0, 6); // Generate a random color for each month
        }, $monthNames);

        // Create the chart
        $chartjs = app()->chartjs
            ->name('lineChartUsers')
            ->type('line')
            ->size(['width' => 600, 'height' => 400])
            ->labels($labels)
            ->datasets([
                [
                    "label" => "عدد مستخدمي التطبيق",
                    'backgroundColor' => $colors, // Use generated colors
                    'borderColor' => $colors, // Use the same colors for borders
                    'data' => $userCounts,
                ]
            ])
            ->options([
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'beginAtZero' => true,
                            ],
                        ],
                    ],
                ],
                'tooltips' => [
                    'enabled' => true,
                ],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ]);


        return view('dashboard.home.index', [
            'totalCategories' => $totalCategory,
            'totalInquiries' => $totalReport,
            'totalQuestions' => $totalQuestion,
            'totalUsers' => $totalUsers,
            'chartjs' => $chartjs
        ]);
    }
}
