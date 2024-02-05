<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Transaction;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $startDate = Carbon::now()->startOfMonth(); // Get the first day of the current month
        $endDate = Carbon::now()->endOfMonth();

        $incomes = Transaction::where('income_outcome', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
        $collection = collect($incomes);
        $balance = $collection->sum('amount');

        $users = User::role('user')->get();
        return view('backend.dashboard', ['balence' => $balance, 'users' => $users]);
    }

    public function commissionChat($range)
    {
        if ($range === 'day') {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $incomes = Transaction::where('income_outcome', 'outcome')
            ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('h');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('h:m'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $incomes = Transaction::where('income_outcome', 'outcome')
            ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('F j');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $incomes = Transaction::where('income_outcome', 'outcome')
            ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('F j');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $incomes = Transaction::where('income_outcome', 'outcome')
            ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('Y F'),
                    'income' => $income
                ];
            });
        }

        return response()->json($incomes->all());
    }

    public function topupChat($range)
    {
        if ($range === 'day') {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $incomes = Transaction::where('income_outcome', 'income')
            ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('h');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('h:m'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $incomes = Transaction::where('income_outcome', 'income')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m-d');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $incomes = Transaction::where('income_outcome', 'income')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m-d');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'income' => $income
                ];
            });
        } elseif ($range === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $incomes = Transaction::where('income_outcome', 'income')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $collection = collect($incomes);

            $incomes = $collection->groupBy(function ($income) {
                return Carbon::parse($income->created_at)->format('Y-m');
            })->map(function ($group) {
                $income = $group->reduce(function ($carry, $item) {
                    return $carry + $item->amount;
                }, 0);
                return [
                    'date' => $group->first()->created_at->format('Y-F'),
                    'income' => $income
                ];
            });
        }

        return response()->json($incomes->all());
    }

    public function tripChat($range)
    {
        if ($range === 'day') {
            $startDate = Carbon::now()->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $trips = Trip::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($trips);

            $trips = $collection->groupBy(function ($trip) {
                return Carbon::parse($trip->created_at)->format('h');
            })->map(function ($group) {
                return [
                    'date' => $group->first()->created_at->format('h:m'),
                    'tripCount' => $group->count()
                ];
            });
        } elseif ($range === 'week') {
            $startDate = Carbon::now()->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();

            $trips = Trip::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($trips);

            $trips = $collection->groupBy(function ($trip) {
                return Carbon::parse($trip->created_at)->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'tripCount' => $group->count()
                ];
            });
        } elseif ($range === 'month') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            $trips = Trip::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($trips);

            $trips = $collection->groupBy(function ($trip) {
                return Carbon::parse($trip->created_at)->format('Y-m-d');
            })->map(function ($group) {
                return [
                    'date' => $group->first()->created_at->format('F j'),
                    'tripCount' => $group->count()
                ];
            });
        } elseif ($range === 'year') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfYear();

            $trips = Trip::whereBetween('created_at', [$startDate, $endDate])
                ->orderBy('created_at')
                ->get();

            $collection = collect($trips);

            $trips = $collection->groupBy(function ($trip) {
                return Carbon::parse($trip->created_at)->format('Y-m');
            })->map(function ($group) {
                return [
                    'date' => $group->first()->created_at->format('Y F'),
                    'tripCount' => $group->count()
                ];
            });
        }

        return response()->json($trips->all());
    }
}
