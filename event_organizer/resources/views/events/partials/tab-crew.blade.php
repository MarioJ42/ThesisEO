<div class="flex justify-between items-center mb-6">
    <h3 class="text-lg font-bold text-gray-900">Crew Assignments & Jobdesks</h3>
    @if(isset($crewSlots) && $crewSlots->count() === 0)
        <form action="{{ route($user->role . '.events.crew.generate', $event->id) }}" method="POST">
            @csrf
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold transition-colors">
                Generate Template Jobdesk
            </button>
        </form>
    @endif
</div>

@if(isset($crewSlots))
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
                            <tr class="hover:bg-gray-50/50">
                                <td class="px-5 py-4 text-sm font-bold text-gray-900">{{ $slot->jobdesk }}</td>

                                @if($slot->status === 'Vacant')
                                    <td class="px-5 py-4 text-sm text-gray-400 italic">Vacant (Waiting for applicants)</td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full uppercase">Vacant</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <form action="{{ route($user->role . '.events.crew.reject', [$event->id, $slot->id]) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-bold">Delete Slot</button>
                                        </form>
                                    </td>
                                @elseif($slot->status === 'Waiting')
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-blue-600">{{ $slot->crew_name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $slot->crew_phone ?? 'No Phone' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full uppercase animate-pulse">Needs Approval</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <form action="{{ route($user->role . '.events.crew.approve', [$event->id, $slot->id]) }}" method="POST" class="flex items-center justify-end gap-2">
                                            @csrf @method('PUT')
                                            <input type="number" name="fee" placeholder="Set Fee (Rp)" required class="w-28 text-xs border border-gray-300 rounded-md p-1.5 focus:border-blue-500">
                                            <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-md text-xs font-bold">Approve</button>
                                            <button type="submit" formaction="{{ route($user->role . '.events.crew.reject', [$event->id, $slot->id]) }}" class="bg-red-100 hover:bg-red-200 text-red-600 px-3 py-1.5 rounded-md text-xs font-bold">Reject</button>
                                        </form>
                                    </td>
                                @elseif($slot->status === 'Verified')
                                    <td class="px-5 py-4">
                                        <p class="text-sm font-bold text-gray-900">{{ $slot->crew_name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $slot->crew_phone ?? 'No Phone' }}</p>
                                    </td>
                                    <td class="px-5 py-4 text-center"><span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded-full uppercase">Verified</span></td>
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($slot->fee, 0, ',', '.') }}</span>
                                            <form action="{{ route($user->role . '.events.crew.reject', [$event->id, $slot->id]) }}" method="POST">
                                                @csrf @method('PUT')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-[11px] font-bold underline">Revoke</button>
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
