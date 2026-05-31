<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    // Show all habits for the logged-in user
    public function index()
    {
        $habits = Habit::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('habits.index', ['habits' => $habits]);
    }

    // Show create form
    public function create()
    {
        return view('habits.create');
    }

    // Save new habit
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|min:3|max:50',
            'description' => 'nullable|max:255',
            'category'    => 'required|in:health,productivity,learning,fitness,mindfulness,custom',
            'frequency'   => 'required|in:daily,weekly',
            'target_days' => 'required|integer|between:1,7',
            'color'       => 'required',
            'icon'        => 'required',
        ]);

        Habit::create([
            'user_id'     => auth()->id(),
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'frequency'   => $request->frequency,
            'target_days' => $request->target_days,
            'color'       => $request->color,
            'icon'        => $request->icon,
            'status'      => 'active',
            'start_date'  => today(),
        ]);

        return redirect('/habits')->with('success', 'Habit created successfully!');
    }

    // Show habit detail page
    public function show($id)
    {
        $habit = Habit::findOrFail($id);

        // Check ownership
        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $totalCompleted  = $habit->completedCount();
        $currentStreak   = $habit->currentStreak();
        $longestStreak   = $habit->longestStreak();
        $completionRate  = round($habit->completionRate());

        return view('habits.show', [
            'habit'          => $habit,
            'totalCompleted' => $totalCompleted,
            'currentStreak'  => $currentStreak,
            'longestStreak'  => $longestStreak,
            'completionRate' => $completionRate,
        ]);
    }

    // Show edit form
    public function edit($id)
    {
        $habit = Habit::findOrFail($id);

        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        return view('habits.edit', ['habit' => $habit]);
    }

    // Update habit
    public function update(Request $request, $id)
    {
        $habit = Habit::findOrFail($id);

        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'name'        => 'required|min:3',
            'description' => 'nullable',
            'category'    => 'required',
            'frequency'   => 'required',
            'target_days' => 'required|integer|between:1,7',
            'color'       => 'required',
            'icon'        => 'required',
        ]);

        $habit->update([
            'name'        => $request->name,
            'description' => $request->description,
            'category'    => $request->category,
            'frequency'   => $request->frequency,
            'target_days' => $request->target_days,
            'color'       => $request->color,
            'icon'        => $request->icon,
        ]);

        return redirect('/habits')->with('success', 'Habit updated successfully!');
    }

    // Delete habit
    public function destroy($id)
    {
        $habit = Habit::findOrFail($id);

        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        $habit->delete();
        return redirect('/habits')->with('success', 'Habit deleted successfully!');
    }

    // Log today's completion
    public function log($id)
    {
        $habit = Habit::findOrFail($id);

        if ($habit->user_id !== auth()->id()) {
            abort(403);
        }

        // Prevent duplicate log for today
        $alreadyLogged = HabitLog::where('habit_id', $id)
            ->where('user_id', auth()->id())
            ->whereDate('logged_date', today())
            ->exists();

        if ($alreadyLogged) {
            return back()->with('error', 'Already logged today!');
        }

        HabitLog::create([
            'habit_id'    => $id,
            'user_id'     => auth()->id(),
            'logged_date' => today(),
            'completed'   => true,
        ]);

        return back()->with('success', 'Great job! Habit logged!');
    }
}
