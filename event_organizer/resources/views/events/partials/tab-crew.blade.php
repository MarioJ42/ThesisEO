<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-gray-900 m-0">Crew Assignments & Jobdesks</h3>
    @if(isset($crewSlots) && $crewSlots->count() === 0)
        <form action="{{ route($user->role . '.events.crew.generate', $event->id) }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                Generate Template Jobdesk
            </button>
        </form>
    @endif
</div>

@if(isset($crewSlots))
    <div class="mb-8 bg-gray-50 border border-gray-200 rounded-lg p-5">
        <h4 class="font-bold text-gray-800 mb-2 m-0">Add Jobdesk</h4>
        <form action="{{ route($user->role . '.events.crew.add_custom_job', $event->id) }}" method="POST" class="flex flex-col sm:flex-row gap-4 items-end m-0">
            @csrf
            <div class="w-full sm:w-1/2">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Jobdesk</label>
                <input type="text" name="jobdesk" required class="w-full border-gray-300 text-sm rounded-md p-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
            </div>
            <div class="w-full sm:w-1/4">
                <label class="block text-xs font-semibold text-gray-700 mb-1">Session</label>
                <select name="session" required class="w-full border-gray-300 text-sm rounded-md p-2.5 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-colors">
                    <option value="morning">Morning Session</option>
                    <option value="reception">Reception Session</option>
                </select>
            </div>
            <div>
                <button type="submit" class="bg-gray-800 hover:bg-black text-white px-5 py-2.5 rounded-md text-sm font-bold w-full sm:w-auto whitespace-nowrap transition-all shadow-md flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Slot
                </button>
            </div>
        </form>
    </div>

    @foreach(['morning' => 'Morning Session', 'reception' => 'Reception Session'] as $sessionKey => $sessionTitle)
        @php $sessionSlots = $crewSlots->where('session', $sessionKey); @endphp
        @if($sessionSlots->count() > 0)
            <div class="mb-8 bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-5 py-3 border-b border-gray-200">
                    <h4 class="font-bold text-gray-800">{{ $sessionTitle }}</h4>
                </div>
                <table class="min-w-full w-full whitespace-nowrap">
                    <thead class="bg-white border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Jobdesk</th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Crew Name</th>
                            <th class="px-5 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Status</th>
                            <th class="px-5 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Action / Fee</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($sessionSlots as $slot)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $slot->jobdesk }}</td>

                                @if($slot->status === 'Vacant')
                                    <td class="px-5 py-4 text-sm text-gray-400 italic">Vacant (Waiting for applicants)</td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase">Vacant</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <form action="{{ route($user->role . '.events.crew.delete', [$event->id, $slot->id]) }}" method="POST" class="m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-800 text-xs font-bold bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Delete Slot</button>
                                        </form>
                                    </td>

                                @elseif($slot->status === 'Requested')
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-blue-600 m-0">{{ $slot->crew_name }}</p>
                                        <p class="text-[10px] text-gray-500 m-0">{{ $slot->crew_phone ?? 'No Phone' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full uppercase animate-pulse">Needs Approval</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <form action="{{ route($user->role . '.events.crew.approve', [$event->id, $slot->id]) }}" method="POST" class="flex items-center gap-2 m-0">
                                                @csrf @method('PUT')
                                                <input type="number" name="fee" placeholder="Fee (Rp)" required class="w-24 text-xs border border-gray-300 rounded-md p-1.5 focus:border-blue-500">
                                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-bold transition-colors">Approve</button>
                                            </form>

                                            <form action="{{ route($user->role . '.events.crew.reject', [$event->id, $slot->id]) }}" method="POST" class="m-0">
                                                @csrf @method('PUT')
                                                <button type="submit" class="text-orange-600 hover:text-orange-800 text-xs font-bold bg-orange-50 px-3 py-1.5 rounded-lg transition-colors" title="Reject Applicant">Reject Crew</button>
                                            </form>
                                        </div>
                                    </td>

                                @elseif($slot->status === 'Verified')
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-gray-900 m-0">{{ $slot->crew_name }}</p>
                                        <p class="text-[10px] text-gray-500 m-0">{{ $slot->crew_phone ?? 'No Phone' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Verified</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="text-sm font-bold text-emerald-600 mr-2">Rp {{ number_format($slot->fee, 0, ',', '.') }}</span>

                                            <form action="{{ route($user->role . '.events.crew.reject', [$event->id, $slot->id]) }}" method="POST" class="m-0">
                                                @csrf @method('PUT')
                                                <button type="submit" class="text-orange-600 hover:text-orange-800 text-xs font-bold bg-orange-50 px-3 py-1.5 rounded-lg transition-colors" title="Remove Crew from this slot">Remove Crew</button>
                                            </form>

                                            <form action="{{ route($user->role . '.events.crew.delete', [$event->id, $slot->id]) }}" method="POST" class="m-0">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="confirmDelete(this)" class="text-red-600 hover:text-red-800 text-xs font-bold bg-red-50 px-3 py-1.5 rounded-lg transition-colors" title="Completely Delete Slot">Delete Slot</button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endforeach
@endif
